<?php

use PHPUnit\Framework\TestCase;

final class OperateTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testOperate()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        include('../../operate.php');
        $this->assertEquals(array('Location: error.php?msg=No op'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreate()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"create");
        $_POST = array("radl"=>"radl");

        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['CreateInfrastructure'])
            ->getMock();
        $im->method('CreateInfrastructure')
            ->willReturn("infid");

        $GLOBALS['mock_im'] = $im;
        include('../../operate.php');
        unset($GLOBALS['mock_im']);
        $this->assertEquals(array('Location: list.php'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testDestroy()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"destroy", "id"=>"id");

        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['DestroyInfrastructure'])
            ->getMock();
        $im->method('DestroyInfrastructure')
            ->willReturn("infid");

        $GLOBALS['mock_im'] = $im;
        include('../../operate.php');
        unset($GLOBALS['mock_im']);
        $this->assertEquals(array('Location: list.php'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testDestroyVM()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"destroyvm", "infid"=>"id", "vmid"=>"vid");

        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['RemoveResource'])
            ->getMock();
        $im->method('RemoveResource')
            ->willReturn("1");

        $GLOBALS['mock_im'] = $im;
        include('../../operate.php');
        unset($GLOBALS['mock_im']);
        $this->assertEquals(array('Location: list.php'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testStopVM()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"stopvm", "infid"=>"id", "vmid"=>"vid");

        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['StopVM'])
            ->getMock();
        $im->method('StopVM')
            ->willReturn("");

        $GLOBALS['mock_im'] = $im;
        include('../../operate.php');
        unset($GLOBALS['mock_im']);
        $this->assertEquals(array('Location: getvminfo.php?id=id&vmid=vid'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testStartVM()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"startvm", "infid"=>"id", "vmid"=>"vid");

        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['StartVM'])
            ->getMock();
        $im->method('StartVM')
            ->willReturn("");

        $GLOBALS['mock_im'] = $im;
        include('../../operate.php');
        unset($GLOBALS['mock_im']);
        $this->assertEquals(array('Location: getvminfo.php?id=id&vmid=vid'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testAddResource()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"addresource");
        $_POST = array("radl"=>"radl", "infid"=>"id");

        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['AddResource'])
            ->getMock();
        $im->method('AddResource')
            ->willReturn("vmid");

        $GLOBALS['mock_im'] = $im;
        include('../../operate.php');
        unset($GLOBALS['mock_im']);
        $this->assertEquals(array('Location: list.php'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testReconfigure()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"reconfigure", "infid"=>"id");

        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['Reconfigure'])
            ->getMock();
        $im->method('Reconfigure')
            ->willReturn("");

        $GLOBALS['mock_im'] = $im;
        include('../../operate.php');
        unset($GLOBALS['mock_im']);
        $this->assertEquals(array('Location: list.php'),xdebug_get_headers());
    }
}
?>