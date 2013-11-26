<?php

namespace Coupe\Http;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class Response
{

    use Traits\HeaderTrait;

    /**
     * @var array
     */
    private static $messages = [
        100 => 'Continue',
        101 => 'Switching Protocols',

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    ];

    /**
     * @var int
     */
    private $code = 200;

    /**
     * @var string
     */
    private $body = '';

    /**
     * @param string $body
     * @param int    $code
     */
    public function __construct($body = '', $code = 200)
    {
        $this->body = $body;
        $this->code = $code;
    }

    /**
     * @param int $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     *
     */
    public function outputHeader()
    {
        $header = '';

        if (strlen($this->body)) {
            $this->setHeader('Content-Length', strlen($this->body));
        }

        foreach ($this->headers as $name => $values) {
            if (!is_array($values)) {
                $values = [$values];
            }
            foreach ($values as $value) {
                $header .= sprintf("%s: %s\n", $name, $value);
            }
        }

        return $header;
    }

    /**
     *
     */
    public function outputBody()
    {
        return $this->getBody();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return
            sprintf("HTTP/1.1 %d %s\n", $this->code, self::$messages[$this->code]) .
            $this->outputHeader() .
            "\n" .
            $this->outputBody()
        ;
    }

} 