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
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: error.php'),xdebug_get_headers());
        $this->assertEquals($_SESSION['error'], 'No op');
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreateIncorrectPass()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"add", "username"=>"userinfotest", "password"=>"passwordtest",
                       "password2"=>"password", "user_groups"=>array("users"), "permissions"=>"0",
                       "rand"=>"123");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: error.php'),xdebug_get_headers());
        $this->assertEquals($_SESSION['error'], 'The passwords are not equal.');

        $res = get_user("userinfotest");
        $this->assertEquals(NULL, $res);
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreate()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"add", "username"=>"userinfotest", "password"=>"passwordtest",
                       "password2"=>"passwordtest", "user_groups"=>array("users"), "permissions"=>"0",
        		       "rand"=>"123");
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

        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"edit", "id"=>"userinfotest", "username"=>"newuserinfotest", "password"=>"passwordtest",
        		       "password2"=>"passwordtest", "user_groups"=>array("users"), "permissions"=>"0", "rand"=>"123");
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

        $_SESSION = array("user"=>"newuserinfotest", "password"=>"passwordtest", "rand"=>"123");
        $_POST = array("op"=>"password", "oldpassword" => "passwordtest", "password"=>"npasswordtest",
        		       "password2"=>"npasswordtest", "rand"=>"123");
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

        $_SESSION = array("user"=>"newuserinfotest", "password"=>"passwordtest", "rand"=>"123");
        $_POST = array("op"=>"register", "username"=>"userinfotest2", "password"=>"npasswordtest",
        		       "password2"=>"npasswordtest", "rand"=>"123");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: index.php'),xdebug_get_headers());
        $this->assertEquals($_SESSION['error'], 'Username is not a valid email.');
        		
        $_POST = array("op"=>"register", "username"=>"user@server.com", "password"=>"npasswordtest",
        		       "password2"=>"npasswordtest", "rand"=>"123");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: index.php'),xdebug_get_headers());
        $this->assertEquals($_SESSION['info'], 'User added successfully');

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

        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"delete", "id"=>"newuserinfotest", "rand"=>"123");
        include('../../userinfo.php');
        $this->assertEquals(array('Location: user_list.php'),xdebug_get_headers());
    }
}
?>