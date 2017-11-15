<?php

use PHPUnit\Framework\TestCase;

final class GroupPagesTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testGroupForm()
    {
        $this->expectOutputRegex('/.*input type="text" name="name" value="users".*/');
        $this->expectOutputRegex('/.*input maxlength="256" size="200" type="descr" name="description" value="Grupo general de usuarios".*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("id"=>"users");
        include('../../groupform.php');
    }

    /**
     * @runInSeparateProcess
     */
    public function testGroupList()
    {
        $this->expectOutputRegex('/.*groupform.php?id=users.*/');
        $this->expectOutputRegex('/.*<td>[ \n\\n\t]+users[ \n\\n\t]+<\/td>.*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        include('../../group_list.php');
    }
}
?>
