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

    /**
     * @expectedException Coupe\Exception\Exception
     */
    public function testCreateSocketFails()
    {
        if (defined('PHP_WINDOWS_VERSION_BUILD')) {
            throw new Coupe\Exception\Exception();
        }

        $path = $this->getCachePath('localhost:8443');

        try {
            $server = new SslHttpServer(new SslHttpHandler());
            $socket = $server->createSocket('localhost:8443');

            $server2 = new SslHttpServer(new SslHttpHandler());
            $server2->createSocket('localhost:8443');
        } catch (\Exception $e) {
            throw $e;
        } finally {
            fclose($socket);
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    protected function getCachePath($address)
    {
        $fileName = 'coupe_' . md5($address) . '.pem';

        return sys_get_temp_dir() . '/' . $fileName;
    }

} 