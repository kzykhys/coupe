<?php

namespace Coupe\Http;

use KzykHys\Text\Text;

/**
 * @author Kazuyuki Hayashi <hayashi@valnur.net>
 */
class RequestParser
{

    public function parseHeader($header)
    {
        $request = new Request();
        $header  = new Text($header);

        $lines = $header->lines();
        $first = array_shift($lines);

        $parts = explode(' ', $first);
        foreach ($parts as $index => $value) {
            switch ($index) {
                case 0:
                    $request->setMethod($value);
                    break;
                case 1:
                    $path = parse_url($value, PHP_URL_PATH);
                    $query = parse_url($value, PHP_URL_QUERY);
                    $request->setUri($path);
                    $request->setPath($path);
                    $request->setQueryString($query);
                    break;
                case 2:
                    list($protocol, $version) = explode('/', trim($value));
                    $request->setProtocol($protocol);
                    $request->setProtocolVersion($version);
                    break;
            }
        }

        foreach ($lines as $line) {
            if (!$line->trim()->isEmpty()) {
                list($name, $value) = explode(':', $line, 2);
                $request->setHeader(trim($name), trim($value));
            }
        }

        return $request;
    }


} 