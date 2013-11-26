<?php

namespace Coupe\Http\Traits;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
trait HeaderTrait
{

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @param $name
     * @param $value
     *
     * @return $this
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * @param      $name
     * @param null $default
     *
     * @return null
     */
    public function getHeader($name, $default = null)
    {
        if (isset($this->headers[$name])) {
            return $this->headers[$name];
        }

        return $default;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

} 