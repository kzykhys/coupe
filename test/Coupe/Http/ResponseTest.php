<?php

use Coupe\Http\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{

    public function testResponse()
    {
        $response = new Response();
        $response
            ->setHeader('Content-Type', 'text/plain')
            ->setBody('hello')
            ->setCode(200);

        $this->assertEquals('text/plain', $response->getHeader('Content-Type'));
        $this->assertEquals('hello', $response->getBody());
        $this->assertEquals(200, $response->getCode());

        $this->assertEquals("HTTP/1.1 200 OK\nContent-Type: text/plain\nContent-Length: 5\n\nhello", (string) $response);
    }

} 