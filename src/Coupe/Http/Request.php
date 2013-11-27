<?php

namespace Coupe\Http;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Request
{

    use Traits\HeaderTrait;

    /**
     * @var int
     */
    private $time = 0;

    /**
     * @var string
     */
    private $method = 'GET';

    /**
     * @var string
     */
    private $uri = '/';

    /**
     * @var string
     */
    private $path = '/';

    /**
     * @var string
     */
    private $pathInfo = null;

    /**
     * @var string
     */
    private $protocol = 'HTTP';

    /**
     * @var string
     */
    private $protocolVersion = '1.1';

    /**
     * @var string
     */
    private $queryString = '';

    /**
     * @var string
     */
    private $body = '';

    /**
     * @var string
     */
    private $remoteAddr = '127.0.0.1';

    /**
     * @var int
     */
    private $remotePort = 0;

    /**
     *
     */
    public function __construct()
    {
        $this->time = time();
    }

    /**
     * @param mixed $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $protocol
     *
     * @return $this
     */
    public function setProtocol($protocol)
    {
        $this->protocol = $protocol;

        return $this;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param string $protocolVersion
     *
     * @return $this
     */
    public function setProtocolVersion($protocolVersion)
    {
        $this->protocolVersion = $protocolVersion;

        return $this;
    }

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * @param string $uri
     *
     * @return $this
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return rtrim($this->path, '/');
    }

    /**
     * @param string $pathInfo
     *
     * @return $this
     */
    public function setPathInfo($pathInfo)
    {
        $this->pathInfo = $pathInfo;

        return $this;
    }

    /**
     * @return string
     */
    public function getPathInfo()
    {
        return $this->pathInfo;
    }

    /**
     * @param string $queryString
     *
     * @return $this
     */
    public function setQueryString($queryString)
    {
        $this->queryString = $queryString;

        return $this;
    }

    /**
     * @return string
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * @param string $remoteAddr
     *
     * @return $this
     */
    public function setRemoteAddr($remoteAddr)
    {
        $this->remoteAddr = $remoteAddr;

        return $this;
    }

    /**
     * @return string
     */
    public function getRemoteAddr()
    {
        return $this->remoteAddr;
    }

    /**
     * @param int $remotePort
     *
     * @return $this
     */
    public function setRemotePort($remotePort)
    {
        $this->remotePort = $remotePort;

        return $this;
    }

    /**
     * @return int
     */
    public function getRemotePort()
    {
        return $this->remotePort;
    }

    /**
     *
     */
    public function __toString()
    {
        return sprintf(
            '%s %s %s/%s "%s" "%s"',
            $this->method,
            $this->uri,
            $this->protocol,
            $this->protocolVersion,
            $this->getHeader('Referer', '(no referrer)'),
            $this->getHeader('User-Agent', '(unknown)')
        );
    }

} 