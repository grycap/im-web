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
    }
}
