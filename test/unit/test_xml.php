<?php

use PHPUnit\Framework\TestCase;

final class XMLTest extends TestCase
{

    public function testXML()
    {
        $_SESSION = array("user"=>"admin", "password"=>"admin");

        $val1 = new xmlrpcval(true);
        $val2 = new xmlrpcval(array(new xmlrpcval("infid1"), new xmlrpcval("infid2")), "array");
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

        $res = $im->GetInfrastructureList();
        $this->assertEquals(array("infid1","infid2"), $res);
    }
}
