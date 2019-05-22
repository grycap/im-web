<?php

use PHPUnit\Framework\TestCase;

final class UserinfoTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testNoOp()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: error.php?msg=No op'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreateIncorrectPass()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"add");
        $_POST = array("username"=>"userinfotest", "password"=>"passwordtest",
                    "password2"=>"password", "user_groups"=>array("users"), "permissions"=>"0");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: error.php?msg=The+passwords+are+not+equal.'),xdebug_get_headers());

        $res = get_user("userinfotest");
        $this->assertEquals(NULL, $res);
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreate()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"add");
        $_POST = array("username"=>"userinfotest", "password"=>"passwordtest",
                    "password2"=>"passwordtest", "user_groups"=>array("users"), "permissions"=>"0");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: user_list.php'),xdebug_get_headers());

        $res = get_user("userinfotest");
        $this->assertEquals("0", $res['permissions']);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testEdit()
    {
        $this->expectOutputString('');

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"edit");
        $_POST = array("id"=>"userinfotest", "username"=>"newuserinfotest", "password"=>"passwordtest",
                    "password2"=>"passwordtest", "user_groups"=>array("users"), "permissions"=>"0");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: user_list.php'),xdebug_get_headers());

        $res = get_user("newuserinfotest");
        $this->assertEquals("0", $res['permissions']);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testChangePassword()
    {
        $this->expectOutputString('');

        $_SESSION = array("user"=>"newuserinfotest", "password"=>"passwordtest");
        $_GET = array("op"=>"password");
        $_POST = array("password"=>"npasswordtest", "password2"=>"npasswordtest");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: list.php'),xdebug_get_headers());

        $res = get_user("newuserinfotest");
        $res = check_password("npasswordtest", $res["password"]);
        $this->assertEquals(true, $res);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testRegister()
    {
        $this->expectOutputString('');

        $_SESSION = array("user"=>"newuserinfotest", "password"=>"passwordtest");
        $_GET = array("op"=>"register");
        $_POST = array("username"=>"userinfotest2", "password"=>"npasswordtest", "password2"=>"npasswordtest");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: index.php?error=Username is not a valid email.'),xdebug_get_headers());
        		
        $_POST = array("username"=>"user@server.com", "password"=>"npasswordtest", "password2"=>"npasswordtest");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: index.php?info=User added successfully'),xdebug_get_headers());

        $res = get_user("user@server.com");
        $res = check_password("npasswordtest", $res["password"]);
        $this->assertEquals(true, $res);

        $err = delete_user("user@server.com");
        $this->assertEquals("", $err);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testDelete()
    {
        $this->expectOutputString('');

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"delete", "id"=>"newuserinfotest");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: user_list.php'),xdebug_get_headers());
    }
}
?>