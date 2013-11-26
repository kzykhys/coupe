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

    /**
     * @expectedException Coupe\Exception\Exception
     */
    public function testCreateSocketFails()
    {
        try {
            $server = new HttpServer(new HttpHandler());
            $socket = $server->createSocket('localhost:8000');

            $server2 = new HttpServer(new HttpHandler());
            $server2->createSocket('localhost:8000');
        } catch (\Exception $e) {
            throw $e;
        } finally {
            fclose($socket);
        }
    }

} 