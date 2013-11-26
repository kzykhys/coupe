<?php

namespace Coupe\Http;

use Coupe\Common\ContentType;
use Coupe\Exception\Exception;
use Coupe\Http\Exception\ResourceNotFoundException;
use CoroutineIO\Server\HandlerInterface;
use CoroutineIO\Socket\ProtectedStreamSocket;
use CoroutineIO\Socket\StreamSocket;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class HttpHandler implements HandlerInterface
{

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var array
     */
    private $env = [];

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ProcessorInterface[]
     */
    private $processors = [];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge([
            'indexes'  => ['index.php', 'index.html'],
            'fallback' => 'index.php',
            'docroot'  => getcwd(),
        ], $options);

        $this->logger = new NullLogger();
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return $this
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param ProcessorInterface $processor
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * @param StreamSocket $socket
     *
     * @return \Generator
     */
    public function handleClient(StreamSocket $socket)
    {
        $this->prepareSocket($socket);

        $parser = new RequestParser();
        $header = $body = '';

        while (false === strpos($header, "\r\n\r\n")) {
            $header .= (yield $socket->read(1));
        }

        $request = $parser->parseHeader($header);

        if ($request->getMethod() == 'POST') {
            if ($length = $request->getHeader('Content-Length')) {
                while (strlen($body) < $length) {
                    $body .= (yield $socket->read(4096));
                }
            } else {
                while ('' != ($part = (yield $socket->read(4096)))) {
                    $body .= $part;
                }
            }
        }

        $request->setBody($body);

        $response = $this->handleRequest($request, new ProtectedStreamSocket($socket));

        // Fixme: Connection=keep-alive is not supported at this time
        $response->setHeader('Connection', 'close');

        $chunk = str_split($response, 4096);

        foreach ($chunk as $buffer) {
            $bytes = (yield @$socket->write($buffer));
            if (false === $bytes) {
                break;
            }
        }

        yield $socket->close();
    }

    /**
     * @param StreamSocket $socket
     */
    protected function prepareSocket(StreamSocket $socket)
    {
        $socket->block(false);
    }

    /**
     * @param Request               $request
     * @param ProtectedStreamSocket $socket
     *
     * @throws ResourceNotFoundException
     *
     * @return Response
     */
    protected function handleRequest(Request $request, ProtectedStreamSocket $socket)
    {
        try {
            $file = $this->findFile($request);

            if ($processor = $this->findProcessor($file)) {
                $response = $processor->execute($file, $request);
            } else {
                $response = new Response(file_get_contents($file));
                $response->setHeader('Content-Type', ContentType::getType($file->getExtension()));
            }
        } catch (ResourceNotFoundException $e) {
            $response = $this->handleError(404, $e);
        } catch (Exception $e) {
            $response = $this->handleError(500, $e);
        }

        $this->logger->log($response->getCode(), (string) $request);

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return \SplFileInfo
     * @throws ResourceNotFoundException
     */
    protected function findFile(Request $request)
    {
        $path = $this->options['docroot'] . '/' . ltrim($request->getUri(), '/');
        $file = new \SplFileInfo($path);

        if ($file->isDir()) {
            // find indexes
            foreach ($this->options['indexes'] as $index) {
                $fixedPath = rtrim($path, '/') . '/' . $index;
                $fixedFile = new \SplFileInfo($fixedPath);
                if ($fixedFile->isFile()) {
                    return $fixedFile;
                }
            }

            // find fallback
            $path = $this->options['docroot'] . '/' . ltrim($this->options['fallback'], '/');
            $file = new \SplFileInfo($path);

            if ($file->isFile()) {
                return $file;
            }
        }

        if ($file->isFile()) {
            return $file;
        }

        if (preg_match('{^([^\.]*?\.[^/]+)(/.*)$}', $request->getUri(), $matches)) {
            $file = new \SplFileInfo($this->options['docroot'] . '/' . $matches[1]);
            if ($file->isFile()) {
                $this->env['PATH_INFO'] = $matches[2];

                return $file;
            }
        }

        throw new ResourceNotFoundException();
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return bool|ProcessorInterface
     */
    protected function findProcessor(\SplFileInfo $file)
    {
        foreach ($this->processors as $processor) {
            if ($processor->isSupported($file)) {
                return $processor;
            }
        }

        return false;
    }

    /**
     * @param int        $code
     * @param \Exception $e
     *
     * @return Response
     */
    protected function handleError($code, \Exception $e)
    {
        $path = __DIR__ . sprintf('/Resources/html/%d.html', $code);

        if (file_exists($path)) {
            return new Response(file_get_contents($path), $code);
        }

        return new Response(sprintf('<html><body><h1>Error %d</h1><p>%s</p></body></html>', $code, $e->getMessage()), $code);
    }

}