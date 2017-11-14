<?php

use PHPUnit\Framework\TestCase;

final class RESTTest extends TestCase
{

    public function testGetInfrastructureList()
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

    public function testGetInfrastructureInfo()
    {
        $_SESSION = array("user"=>"admin", "password"=>"admin");

        $resp = new Http_response(200, "vmid1\nvmid2");

        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['BasicRESTCall'])
            ->getMock();
        $im->method('BasicRESTCall')
            ->willReturn($resp);

        $res = $im->GetInfrastructureInfo("infid1");
        $this->assertEquals(array("vmid1","vmid2"), $res);
    }

    public function testGetInfrastructureState()
    {
        $_SESSION = array("user"=>"admin", "password"=>"admin");

        $resp = new Http_response(200, '{"state":{"state": "running", "vm_states" : {"vmid1": "running", "vmid2": "running"}}}');

        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['BasicRESTCall'])
            ->getMock();
        $im->method('BasicRESTCall')
            ->willReturn($resp);

        $res = $im->GetInfrastructureState("infid1");
        $this->assertEquals("running", $res);
    }

    public function testDestroyInfrastructure()
    {
        $_SESSION = array("user"=>"admin", "password"=>"admin");

        $resp = new Http_response(200, '');

        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['BasicRESTCall'])
            ->getMock();
        $im->method('BasicRESTCall')
            ->willReturn($resp);

        $res = $im->DestroyInfrastructure("infid1");
        $this->assertEquals("", $res);
    }
}
