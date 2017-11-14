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
        include('../../operate.php');
        $this->assertEquals(array('Location: error.php?msg=Error%3A+Connect+error%3A+Connection+refused+%28111%29'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testDestroy()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"destroy", "id"=>"id");
        include('../../operate.php');
        $this->assertEquals(array('Location: error.php?msg=Error%3A+Connect+error%3A+Connection+refused+%28111%29'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testDestroyVM()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"destroyvm", "infid"=>"id", "vmid"=>"vid");
        include('../../operate.php');
        $this->assertEquals(array('Location: error.php?msg=Error%3A+Connect+error%3A+Connection+refused+%28111%29'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testStopVM()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"stopvm", "infid"=>"id", "vmid"=>"vid");
        include('../../operate.php');
        $this->assertEquals(array('Location: error.php?msg=Error%3A+Connect+error%3A+Connection+refused+%28111%29'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testStartVM()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"startvm", "infid"=>"id", "vmid"=>"vid");
        include('../../operate.php');
        $this->assertEquals(array('Location: error.php?msg=Error%3A+Connect+error%3A+Connection+refused+%28111%29'),xdebug_get_headers());
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
        include('../../operate.php');
        $this->assertEquals(array('Location: error.php?msg=Error%3A+Connect+error%3A+Connection+refused+%28111%29'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testReconfigure()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"reconfigure", "infid"=>"id");
        include('../../operate.php');
        $this->assertEquals(array('Location: error.php?msg=Error%3A+Connect+error%3A+Connection+refused+%28111%29'),xdebug_get_headers());
    }
}
?>