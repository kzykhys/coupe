<?php

use Coupe\Common\ContentType;

class ContentTypeTest extends \PHPUnit_Framework_TestCase
{

    public function testValidContentType()
    {
        $this->assertEquals('image/jpeg', ContentType::getType('jpeg'));
    }

    public function testInvalidContentType()
    {
        $this->assertEquals('application/octet-stream', ContentType::getType('foo'));
    }

} 