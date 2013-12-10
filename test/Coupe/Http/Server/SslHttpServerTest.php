<?php

use Coupe\Http\Server\SslHttpServer;
use Coupe\Http\SslHttpHandler;

class SslHttpServerTest extends \PHPUnit_Framework_TestCase
{

    public function testSocket()
    {
        $path = $this->getCachePath('localhost:8443');
        if (file_exists($path)) {
            unlink($path);
        }

        $server = new SslHttpServer(new SslHttpHandler());
        $socket = $server->createSocket('localhost:8443');
        $this->assertInternalType('resource', $socket);
        fclose($socket);

        $server = new SslHttpServer(new SslHttpHandler());
        $socket = $server->createSocket('localhost:8443');
        $this->assertInternalType('resource', $socket);
        fclose($socket);

        if (file_exists($path)) {
            unlink($path);
        }
    }


    protected function getCachePath($address)
    {
        $fileName = 'coupe_' . md5($address) . '.pem';

        return sys_get_temp_dir() . '/' . $fileName;
    }

} 