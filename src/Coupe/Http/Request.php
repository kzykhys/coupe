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
     * @var
     */
    private $body = '';

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