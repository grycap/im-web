<?php

use PHPUnit\Framework\TestCase;

final class RESTTest extends TestCase
{

    private function getIM($value, $status = 200) {
        $_SESSION = array("user"=>"admin", "password"=>"admin");

        $resp = new Http_response($status, $value);
        
        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['BasicRESTCall'])
            ->getMock();
        $im->method('BasicRESTCall')
            ->willReturn($resp);

        return $im;        
    }

    public function test_get_auth_data()
    {
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $im = IMRest::connect("","");
        $res = $im->get_auth_data();
        $this->assertEquals("type = InfrastructureManager; username = admin; password = admin\\ntype = VMRC; host = http://servproject.i3m.upv.es:8080/vmrc/vmrc; username = micafer; password = ttt25\\n", $res);
    }

    public function testBasicRESTCall()
    {
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $im = IMRest::connect("localhost","8899");
        $headers = array('Accept: text/*');
        $res = $im->BasicRESTCall("GET", '/infrastructures', $headers);
        $this->assertEquals(0, $res->getStatus());
    }

    public function testGetInfrastructureList()
    {
        $im = $this->getIM("infid1\ninfid2");
        $res = $im->GetInfrastructureList();
        $this->assertEquals(array("infid1","infid2"), $res);
    }

    public function testGetInfrastructureInfo()
    {
        $im = $this->getIM("vmid1\nvmid2");
        $res = $im->GetInfrastructureInfo("infid1");
        $this->assertEquals(array("vmid1","vmid2"), $res);
    }

    public function testGetInfrastructureState()
    {
        $im = $this->getIM('{"state":{"state": "running", "vm_states" : {"vmid1": "running", "vmid2": "running"}}}');
        $res = $im->GetInfrastructureState("infid1");
        $this->assertEquals("running", $res["state"]);
    }

    public function testDestroyInfrastructure()
    {
        $im = $this->getIM("");
        $res = $im->DestroyInfrastructure("infid1");
        $this->assertEquals("", $res);
    }

    public function testGetVMInfo()
    {
        $im = $this->getIM("vminfo");
        $res = $im->GetVMInfo("infid1", "vmid1");
        $this->assertEquals("vminfo", $res);
    }

    public function testGetInfrastructureContMsg()
    {
        $im = $this->getIM("contmsg");
        $res = $im->GetInfrastructureContMsg("infid1");
        $this->assertEquals("contmsg", $res);
    }

    public function testGetVMProperty()
    {
        $im = $this->getIM("vmprop");
        $res = $im->GetVMProperty("infid1", "vmid1", "prop");
        $this->assertEquals("vmprop", $res);
    }

    public function testGetVMContMsg()
    {
        $im = $this->getIM("contmsg");
        $res = $im->GetVMContMsg("infid1", "vmid1");
        $this->assertEquals("contmsg", $res);
    }

    public function testCreateInfrastructure()
    {
        $im = $this->getIM("infid");
        $res = $im->CreateInfrastructure("radl");
        $this->assertEquals("infid", $res);
    }

    public function testStartVM()
    {
        $im = $this->getIM("");
        $res = $im->StartVM("infid", "vmid");
        $this->assertEquals("", $res);
    }

    public function testStopVM()
    {
        $im = $this->getIM("");
        $res = $im->StopVM("infid", "vmid");
        $this->assertEquals("", $res);
    }

    public function testAddResource()
    {
        $im = $this->getIM("vmid1\nvmid2");
        $res = $im->AddResource("infid", "tosca_definitions_version: ");
        $this->assertEquals("vmid1\nvmid2", $res);
    }

    public function testRemoveResource()
    {
        $im = $this->getIM("");
        $res = $im->RemoveResource("infid", "vmid");
        $this->assertEquals("", $res);
    }

    public function testReconfigure()
    {
        $im = $this->getIM("");
        $res = $im->Reconfigure("infid", "vmid");
        $this->assertEquals("", $res);
    }

    public function testGetOutputs()
    {
        $im = $this->getIM('{"outputs": {"key": "value"}}');
        $res = $im->GetOutputs("infid");
        $this->assertEquals(array("key"=>"value"), $res);
    }
    
}
?>