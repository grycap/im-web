<?php

use PHPUnit\Framework\TestCase;

final class RESTTest extends TestCase
{

    public function testREST()
    {
        $_SESSION = array("user"=>"admin", "password"=>"admin");

        $resp = new Http_response(200, "infid1\ninfid2");

        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['BasicRESTCall'])
            ->getMock();
        $im->method('BasicRESTCall')
            ->willReturn($resp);

        $res = $im->GetInfrastructureList();
        $this->assertEquals(array("infid1","infid2"), $res);
    }
}
