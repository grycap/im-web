<?php

use PHPUnit\Framework\TestCase;

final class RADLTest extends TestCase
{

    public function testRADL()
    {
        $res = insert_radl("admin", "radltest", "radldesc", "radlbody", "users", '1', '0', '1', '0', '0', '0');
        $this->assertEquals("", $res);

        $res = get_radls("admin");
        $rowid = $res[0]["rowid"];
        $this->assertEquals("radltest", $res[0]["name"]);

        $username = uniqid();
        $res = insert_user($username, "pass", array('users'), '');
        $this->assertEquals($res, "");

        $res = radl_user_can($rowid, $username, "r");
        $this->assertEquals(true, $res);

        $res = edit_radl($rowid, "newname", "radldesc", "radlbody", '0', '0', '0', '0', '0', '0', '0');
        $this->assertEquals("", $res);

        $res = radl_user_can($rowid, $username, "r");
        $this->assertEquals(false, $res);

        $res = delete_user($username);
        $this->assertEquals($res, "");

        $res = delete_radl($rowid);
        $this->assertEquals("", $res);

        $res = get_radl($rowid);
        $this->assertEquals(NULL, $res);
    }

}
?>