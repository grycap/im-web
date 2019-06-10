<?php

use PHPUnit\Framework\TestCase;

final class RADLinfoTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testNoOp()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        include('../../radlinfo.php');
        $this->assertEquals(array('Location: error.php'),xdebug_get_headers());
        $this->assertEquals($_SESSION['error'], 'No op');
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreate()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"add");
        $_POST = array("name"=>"radltest", "description"=>"radldesc",
                    "radl"=>"radlbody", "group"=>"users");
        include('../../radlinfo.php');
        $this->assertEquals(array('Location: radl_list.php'),xdebug_get_headers());

        $res = get_radls("admin");
        $this->assertEquals("radltest", end($res)['name']);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testEdit()
    {
        $this->expectOutputString('');

        $res = get_radls("admin");
        $rowid = end($res)['rowid'];

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"edit");
        $_POST = array("name"=>"radltest", "id"=>$rowid, "description"=>"newradldesc",
                    "radl"=>"radlbody\n@input.wn@", "group"=>"users");
        include('../../radlinfo.php');
        $this->assertEquals(array('Location: radl_list.php'),xdebug_get_headers());

        $res = get_radl($rowid);
        $this->assertEquals("newradldesc", $res['description']);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testLaunchWithoutParams()
    {
        $this->expectOutputString('');

        $res = get_radls("admin");
        $rowid = end($res)['rowid'];

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"launch", "id"=>$rowid);
        include('../../radlinfo.php');
        $this->assertEquals(array('Location: radl_list.php?parameters=' . $rowid),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testLaunch()
    {
        $this->expectOutputString('');

        $res = get_radls("admin");
        $rowid = end($res)['rowid'];

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"launch", "id"=>$rowid, "parameters"=>"1", "wn"=>"2");

        $im = $this->getMockBuilder(IMRest::class)
            ->setMethods(['CreateInfrastructure'])
            ->getMock();
        $im->method('CreateInfrastructure')
            ->willReturn("infid");

        $GLOBALS['mock_im'] = $im;
        include('../../radlinfo.php');
        unset($GLOBALS['mock_im']);
        $this->assertEquals(array('Location: list.php'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testDelete()
    {
        $this->expectOutputString('');

        $res = get_radls("admin");
        $rowid = end($res)['rowid'];

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"delete", "id"=>$rowid);
        include('../../radlinfo.php');
        $this->assertEquals(array('Location: radl_list.php'),xdebug_get_headers());
    }
}
?>