<?php

class RequestTest extends \PHPUnit_Framework_TestCase
{

    public function testRequest()
    {
        $request = new \Coupe\Http\Request();
        $request
            ->setBody('hello')
            ->setQueryString('abc=123&cde=456')
            ->setProtocolVersion('1.1')
            ->setProtocol('HTTP')
            ->setMethod('GET')
            ->setUri('/foo/bar')
            ->setHeader('foo', 'bar');

        $this->assertEquals('hello', $request->getBody());
        $this->assertEquals('abc=123&cde=456', $request->getQueryString());
        $this->assertEquals('1.1', $request->getProtocolVersion());
        $this->assertEquals('HTTP', $request->getProtocol());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/foo/bar', $request->getUri());
        $this->assertEquals('bar', $request->getHeader('foo'));
        $this->assertNull($request->getHeader('baz'));
        $this->assertTrue($request->getHeader('baz', true));
        $this->assertEquals(['foo' => 'bar'], $request->getHeaders());
        $this->assertEquals('127.0.0.1:0 GET /foo/bar HTTP/1.1 "(no referrer)"', (string) $request);
    }

} 