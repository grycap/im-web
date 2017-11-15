<?php

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testCheckUser()
    {
        $_SESSION = array();
        $this->assertEquals(
            false,
            check_session_user()
        );

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $this->assertEquals(
            true,
            check_session_user()
        );

        $username = uniqid();
        $res = insert_user($username, "pass", array('users'), '');
        $this->assertEquals($res, "");

        $_SESSION = array("user"=>$username, "password"=>"pass");
        $this->assertEquals(
            false,
            check_admin_user()
        );
    }

    public function testGetUsers()
    {
        $res = get_users();
        $this->assertGreaterThanOrEqual(1, count($res));
        $this->assertEquals('admin', $res[0]['username']);

        $res = get_user("admin");
        $this->assertEquals('admin', $res['username']);

        $res = get_user_groups("admin");
        $this->assertEquals('users', $res[0]['grpname']);
    }

    public function testManageUsers()
    {
        $res = change_password("admin", "test");
        $this->assertEquals('', $res);

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $this->assertEquals(
            false,
            check_session_user()
        );

        $_SESSION = array("user"=>"admin", "password"=>"test");
        $this->assertEquals(
            true,
            check_session_user()
        );

        $res = change_password("admin", "admin");

        $username = uniqid();
        $res = insert_user($username, "pass", array('users'), '');
        $this->assertEquals($res, "");

        $res = edit_user($username, $username, "pass", array(), "1");
        $this->assertEquals($res, "");

        $_SESSION = array("user"=>$username, "password"=>"pass");
        $this->assertEquals(
            true,
            check_session_user()
        );

        $res = delete_user($username);
        $this->assertEquals($res, "");

        $res = get_user($username);
        $this->assertEquals("", $res);
    }
}
?>