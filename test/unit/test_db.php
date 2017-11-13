<?php

use PHPUnit\Framework\TestCase;

final class DBTest extends TestCase
{

    public function testDB()
    {
        $db = new IMDB();
        $res = $db->direct_query("select * from user");

        $this->assertEquals("admin",$res[0]['username']);

        $res = $db->get_items_from_table("user");
        $this->assertEquals("admin",$res[0]['username']);

        $username = uniqid();
        $fields = array();
        $fields[] = "'" . $username . "'";
        $fields[] = "'passwd'";
        $fields[] = "'0'";
        $res = $db->insert_item_into_table("user",$fields);
        $this->assertEquals("",$res);

        $res = $db->get_items_from_table("user", array("username" => "'" . $username . "'"));
        $this->assertEquals(1,count($res));

        $fields = array();
        $fields["password"] = "'somepass'";
        $where = array("username" => "'" . $username . "'");
        $res = $db->edit_item_from_table("user",$fields,$where);
        $this->assertEquals("",$res);

        $res = $db->get_items_from_table("user", array("username" => "'" . $username . "'"));
        $this->assertEquals("somepass",$res[0]["password"]);

        $db->close();
    }

}
