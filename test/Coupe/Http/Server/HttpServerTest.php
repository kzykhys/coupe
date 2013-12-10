<?php


use Coupe\Http\HttpHandler;
use Coupe\Http\Server\HttpServer;

class HttpServerTest extends \PHPUnit_Framework_TestCase
{

    public function testSocket()
    {
        $server = new HttpServer(new HttpHandler());
        $socket = $server->createSocket('localhost:8000');

        $this->assertInternalType('resource', $socket);

        fclose($socket);
    }

} 