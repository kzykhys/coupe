<?php

use Coupe\Http\Processor\PhpCgiProcessor;

class PhpCgiProcessorTest extends \PHPUnit_Framework_TestCase
{

    public function testProcessor()
    {
        $request = new \Coupe\Http\Request();
        $request->setUri('/index.php');
        $request->setMethod('POST');
        $request->setProtocol('HTTP');
        $request->setProtocolVersion('1.1');
        $request->setHeader('User-Agent', 'phpunit');
        $request->setHeader('Https', 1);
        $request->setHeader('Content-Type', 'application/x-www-form-urlencoded');
        $request->setHeader('Content-Length', '5');
        $request->setBody('aa=11');

        $file = new \SplFileInfo(__DIR__ . '/../../Resources/php/test.php');
        $processor = new PhpCgiProcessor();

        $this->assertTrue($processor->isSupported($file));

        $response = $processor->execute(new \SplFileInfo(__DIR__ . '/../../Resources/php/test.php'),$request);
    }

} 