<?php

namespace Coupe\Http\Processor;

use Coupe\Http\Exception\BadCgiCallException;
use Coupe\Http\ProcessorInterface;
use Coupe\Http\Request;
use Coupe\Http\Response;
use KzykHys\Text\Text;
use Symfony\Component\Process\Process;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class PhpCgiProcessor implements ProcessorInterface
{

    /**
     * {@inheritdoc}
     */
    public function execute(\SplFileInfo $path, Request $request)
    {
        $bin = PHP_BINDIR . '/php-cgi';

        $process = new Process(
            $bin . ' -f ' . $path->getRealPath(),
            null,
            $this->createEnvVars($path, $request),
            $request->getBody()
        );
        $process->run();

        if (!$process->isSuccessful()) {
            throw new BadCgiCallException($process->getErrorOutput());
        }

        $response = $this->parseOutput($process->getOutput(), $process->getErrorOutput());

        if ($response->getHeader('Status')) {
            list($code,) = explode(' ', $response->getHeader('Status'), 2);
            $response->setCode($code);
        }

        return $response;
    }

    /**
     * @param \SplFileInfo $path
     *
     * @return boolean
     */
    public function isSupported(\SplFileInfo $path)
    {
        return ($path->getExtension() == 'php');
    }

    /**
     * @param \SplFileInfo $path
     * @param Request      $request
     *
     * @return array
     */
    protected function createEnvVars(\SplFileInfo $path, Request $request)
    {
        $env = [
            'REQUEST_URI'     => $request->getUri() . ($request->getQueryString() ? '?' . $request->getQueryString() : ''),
            'SERVER_NAME'     => 'localhost',
            'QUERY_STRING'    => $request->getQueryString(),
            'SCRIPT_NAME'     => $request->getUri(),
            'SCRIPT_FILENAME' => $path->getRealPath(),
            'REQUEST_METHOD'  => $request->getMethod(),
            'REDIRECT_STATUS' => 200,
            'SERVER_SOFTWARE' => 'Coupe/PHP 5.5.5 Development Server'
        ];

        if ($request->getHeader('Content-Length')) {
            $env['CONTENT_LENGTH'] = $request->getHeader('Content-Length');
            $env['CONTENT_TYPE'] = $request->getHeader('Content-Type');
        }

        if ($request->getHeader('Https')) {
            $env['HTTPS'] = 1;
        }

        foreach ($request->getHeaders() as $name => $value) {
            $variable = 'HTTP_' . str_replace('-', '_', strtoupper($name));
            $env[$variable] = $value;
        }

        return $env;
    }

    /**
     * @param        $output
     * @param string $errorOutput
     *
     * @return Response
     */
    protected function parseOutput($output, $errorOutput = '')
    {
        $response = new Response();
        list($header, $body) = explode("\r\n\r\n", $output, 2);
        $response->setBody($errorOutput . $body);

        $header = new Text($header);
        $cookies = [];
        foreach ($header->lines() as $line) {
            list($name, $value) = explode(':', $line, 2);
            if ('Set-Cookie' == $name) {
                $cookies[] = trim($value);
            } else {
                $response->setHeader(trim($name), trim($value));
            }
        }

        if (count($cookies)) {
            $response->setHeader('Set-Cookie', $cookies);
        }

        return $response;
    }

}