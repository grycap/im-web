<?php

use PHPUnit\Framework\TestCase;

final class GroupTest extends TestCase
{

    public function testManageGroups()
    {
        $grp = uniqid();
        $res = insert_group($grp, "desc");
        $this->assertEquals($res, "");

        $res = get_groups();
        $this->assertEquals("users", $res[0]["name"]);

        $res = get_group($grp);
        $this->assertEquals("desc", $res["description"]);

        $newgrp = uniqid();
        $res = edit_group($grp, $newgrp, "newdesc");
        $this->assertEquals($res, "");

        $res = get_group($newgrp);
        $this->assertEquals("newdesc", $res["description"]);

        $res = delete_group($newgrp);
        $this->assertEquals($res, "");
        $res = get_group($newgrp);
        $this->assertEquals(NULL, $res);
    }
}
?>