<?php

use Coupe\Http\RequestParser;

class RequestParserTest extends \PHPUnit_Framework_TestCase
{

    public function testParser()
    {
        $parser = new RequestParser();
        $request = $parser->parseHeader(
            "GET /foo/bar?abc=5#1234 HTTP/1.1\n" .
            "Connection: keep-alive\n" .
            "Host: localhost:8000\n" .
            "\n"
        );
    }

} 