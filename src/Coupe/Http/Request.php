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
    private $serverName = 'localhost';

    /**
     * @var int
     */
    private $serverPort = 8080;
    
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
     * @return Request
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
     * @return Request
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
     * @return Request
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
     * @return Request
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
     * @return Request
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
     * @return Request
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
     * @return Request
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
     * @return Request
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
     * @param string $serverName
     *
     * @return Request
     */
    public function setServerName($serverName)
    {
        $this->serverName = $serverName;

        return $this;
    }

    /**
     * @return string
     */
    public function getServerName()
    {
        return $this->serverName;
    }

    /**
     * @param int $serverPort
     *
     * @return Request
     */
    public function setServerPort($serverPort)
    {
        $this->serverPort = $serverPort;

        return $this;
    }

    /**
     * @return int
     */
    public function getServerPort()
    {
        return $this->serverPort;
    }

    
    
    /**
     * @param string $remoteAddr
     *
     * @return Request
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
     * @return Request
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
            '%s:%s %s %s %s/%s "%s"',
            $this->remoteAddr,
            $this->remotePort,
            $this->method,
            $this->uri,
            $this->protocol,
            $this->protocolVersion,
            $this->getHeader('Referer', '(no referrer)')
        );
    }

} 