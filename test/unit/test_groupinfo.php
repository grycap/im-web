<?php

use PHPUnit\Framework\TestCase;

final class GroupInfoTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testGroupInfo()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        include('../../groupinfo.php');
        $this->assertEquals(array('Location: error.php'),xdebug_get_headers());
        $this->assertEquals($_SESSION['error'], 'No op');
    }

    /**
     * @runInSeparateProcess
     */
    public function testAdd()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"add");
        $_POST = array("name"=>"grpname", "description"=>"description");
        include('../../groupinfo.php');
        $this->assertEquals(array('Location: group_list.php'),xdebug_get_headers());

        $res = get_group("grpname");
        $this->assertEquals("description", $res["description"]);
    }

    /**
     * @runInSeparateProcess
     * @depends testAdd
     */
    public function testEdit()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"edit");
        $_POST = array("id"=>"grpname", "name"=>"newgrpname", "description"=>"newdescription");
        include('../../groupinfo.php');
        $this->assertEquals(array('Location: group_list.php'),xdebug_get_headers());

        $res = get_group("newgrpname");
        $this->assertEquals("newdescription", $res["description"]);
    }

    /**
     * @runInSeparateProcess
     * @depends testAdd
     */
    public function testDelete()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"delete", "id"=>"newgrpname");
        include('../../groupinfo.php');
        $this->assertEquals(array('Location: group_list.php'),xdebug_get_headers());

        $res = get_group("newgrpname");
        $this->assertEquals(NULL, $res);
    }

}
?>