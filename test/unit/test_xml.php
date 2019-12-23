<?php

use PHPUnit\Framework\TestCase;

final class XMLTest extends TestCase
{
    private function getIM($value, $success=true) {
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        
        $val1 = new xmlrpcval($success);
        $val2 = $value;
        $value = new xmlrpcval(array($val1, $val2), "array");

        $resp = $this->createMock(xmlrpcresp::class);
        $resp->method('faultCode')
            ->willReturn(false);
        $resp->method('value')
            ->willReturn($value);

        $im = $this->getMockBuilder(IMXML::class)
            ->setMethods(['send_xmlrpc_call'])
            ->getMock();
        $im->method('send_xmlrpc_call')
            ->willReturn($resp);

        return $im;        
    }

    public function testGetInfrastructureList()
    {
        $value = new xmlrpcval(array(new xmlrpcval("infid1"), new xmlrpcval("infid2")), "array");
        $im = $this->getIM($value);
        $res = $im->GetInfrastructureList();
        $this->assertEquals(array("infid1","infid2"), $res);

        $_SESSION = array("user"=>"admin", "password"=>"admin", "user_token"=>"token");
        $res = $im->GetInfrastructureList();
        $this->assertEquals(array("infid1","infid2"), $res);
    }

    public function testCreateInfrastructure()
    {
        $value = new xmlrpcval("infid1");
        $im = $this->getIM($value);
        $res = $im->CreateInfrastructure("radl", true);
        $this->assertEquals("infid1", $res);
    }

    public function testDestroyInfrastructure()
    {
        $value = new xmlrpcval("");
        $im = $this->getIM($value);
        $res = $im->DestroyInfrastructure("radl", true);
        $this->assertEquals("", $res);
    }

    public function testGetInfrastructureInfo()
    {
        $value = new xmlrpcval(array(new xmlrpcval("vmid1"), new xmlrpcval("vmid2")), "array");
        $im = $this->getIM($value);
        $res = $im->GetInfrastructureInfo("radl");
        $this->assertEquals(array("vmid1","vmid2"), $res);
    }

    public function testGetInfrastructureContMsg()
    {
        $value = new xmlrpcval("contmsg");
        $im = $this->getIM($value);
        $res = $im->GetInfrastructureContMsg("radl");
        $this->assertEquals("contmsg", $res);
    }

    public function testGetVMInfo()
    {
        $value = new xmlrpcval("vminfo");
        $im = $this->getIM($value);
        $res = $im->GetVMInfo("infid", "vmid");
        $this->assertEquals("vminfo", $res);
    }

    public function testGetVMProperty()
    {
        $value = new xmlrpcval("vmprop");
        $im = $this->getIM($value);
        $res = $im->GetVMProperty("infid", "vmid", "prop");
        $this->assertEquals("vmprop", $res);
    }

    public function testGetVMContMsg()
    {
        $value = new xmlrpcval("contmsg");
        $im = $this->getIM($value);
        $res = $im->GetVMContMsg("infid", "vmid");
        $this->assertEquals("contmsg", $res);
    }

    public function testStartVM()
    {
        $value = new xmlrpcval("");
        $im = $this->getIM($value);
        $res = $im->StartVM("infid", "vmid");
        $this->assertEquals("", $res);
    }

    public function testStopVM()
    {
        $value = new xmlrpcval("");
        $im = $this->getIM($value);
        $res = $im->StopVM("infid", "vmid");
        $this->assertEquals("", $res);
    }

    public function testAddResource()
    {
        $value = new xmlrpcval("");
        $im = $this->getIM($value);
        $res = $im->AddResource("infid", "radl");
        $this->assertEquals("OK", $res);
    }

    public function testRemoveResource()
    {
        $value = new xmlrpcval(1);
        $im = $this->getIM($value);
        $res = $im->RemoveResource("infid", "vmid");
        $this->assertEquals(1, $res);
    }

    public function testReconfigure()
    {
        $value = new xmlrpcval("");
        $im = $this->getIM($value);
        $res = $im->Reconfigure("infid", "radl");
        $this->assertEquals("", $res);
    }

    public function testExportInfrastructure()
    {
        $value = new xmlrpcval("ok");
        $im = $this->getIM($value);
        $res = $im->ExportInfrastructure("infid", true);
        $this->assertEquals("ok", $res);
    }

    public function testImportInfrastructure()
    {
        $value = new xmlrpcval("ok");
        $im = $this->getIM($value);
        $res = $im->ImportInfrastructure("str_inf");
        $this->assertEquals("ok", $res);
    }

    public function testGetInfrastructureState()
    {
        $run = new xmlrpcval("running");
        $value = new xmlrpcval(array("state"=>$run),"struct");
        $im = $this->getIM($value);
        $res = $im->GetInfrastructureState("infid");
        $this->assertEquals("running", $res["state"]);
    }
}
?>