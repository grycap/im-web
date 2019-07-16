<?php

use PHPUnit\Framework\TestCase;

final class OperateTest extends TestCase
{
	/**
	 * @runInSeparateProcess
	 */
	public function testOperateRand()
	{
		$this->expectOutputString('');
		$_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
		include('../../operate.php');
		$this->assertEquals(array('Location: error.php'),xdebug_get_headers());
		$this->assertEquals($_SESSION['error'], 'Invalid rand parameter.');
	}

    /**
     * @runInSeparateProcess
     */
    public function testOperate()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("rand"=>"123");
        include('../../operate.php');
        $this->assertEquals(array('Location: error.php'),xdebug_get_headers());
        $this->assertEquals($_SESSION['error'], 'No op');
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreate()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"create", "radl"=>"radl", "rand"=>"123");

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
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"destroy", "infid"=>"id", "rand"=>"123");

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
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"destroyvm", "infid"=>"id", "vmid"=>"vid", "rand"=>"123");

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
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"stopvm", "infid"=>"id", "vmid"=>"vid", "rand"=>"123");

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
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"startvm", "infid"=>"id", "vmid"=>"vid", "rand"=>"123");

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
    public function testRebootVM()
    {
    	$this->expectOutputString('');
    	$_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
    	$_POST = array("op"=>"rebootvm", "infid"=>"id", "vmid"=>"vid", "rand"=>"123");
    	
    	$im = $this->getMockBuilder(IMRest::class)
    	->setMethods(['RebootVM'])
    	->getMock();
    	$im->method('RebootVM')
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
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"addresource", "radl"=>"radl", "infid"=>"id", "rand"=>"123");

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
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"reconfigure", "infid"=>"id", "rand"=>"123");

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