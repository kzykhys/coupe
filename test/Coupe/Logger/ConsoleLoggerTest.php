<?php

use Coupe\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\NullOutput;

class ConsoleLoggerTest extends \PHPUnit_Framework_TestCase
{

    public function testLogger()
    {
        $output = new NullOutput();
        $logger = new ConsoleLogger($output);

        $logger->log(100, '100');
        $logger->log(200, '200');
        $logger->log(300, '300');
        $logger->log(400, '400');
        $logger->log(500, '500');
        $logger->log(600, '500');
    }

} 