<?php

class CertificateTest extends \PHPUnit_Framework_TestCase
{

    public function testCertificate()
    {
        $cert = new \Coupe\Ssl\Certificate();
        $cert->createForAddress('localhost');
    }

} 