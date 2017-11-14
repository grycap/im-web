<?php

use PHPUnit\Framework\TestCase;

final class Http_responseTest extends TestCase
{

    public function testConnect()
    {
        $this->assertInstanceOf(
            Http::class,
            Http::connect('www.google.es')
        );
    }

    public function testExec()
    {
        $headers = array("Authorization: Basic");
        $res = Http::connect('www.google.es', 443, 'https')
        ->setHeaders($headers)
        ->exec('GET', '/');
            
        $status = $res->getStatus();
        $output = $res->getOutput();

        $this->assertEquals(200, $status);
        $this->assertStringStartsWith('<!doctype html>', $output);

        $methods = array('DELETE','PUT', 'POST');
        foreach ($methods as $method) {
            $res = Http::connect('www.google.es', 80, 'http')
            ->setHeaders($headers)
            ->exec($method, '/');

            $status = $res->getStatus();
            $this->assertEquals(405, $status);
        }
    }

}
?>