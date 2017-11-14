<?php

use PHPUnit\Framework\TestCase;

final class CredinfoTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testNoOp()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        include('../../credinfo.php');
        $this->assertEquals(array('Location: error.php?msg=No op'),xdebug_get_headers());
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreate()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"add");
        $_POST = array("type"=>"EC2", "id"=>"ec2",
                    "username"=>"user", "password"=>"pass");
        include('../../credinfo.php');
        $this->assertEquals(array('Location: credentials.php'),xdebug_get_headers());

        $res = get_credentials("admin");
        $this->assertEquals("ec2", end($res)['id']);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testEdit()
    {
        $this->expectOutputString('');

        $res = get_credentials("admin");
        $rowid = end($res)['rowid'];

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"edit");
        $_POST = array("type"=>"EC2", "id"=>"ec2", "rowid"=>$rowid,
                    "service_region"=>"region");
        include('../../credinfo.php');
        $this->assertEquals(array('Location: credentials.php'),xdebug_get_headers());

        $res = get_credentials("admin");
        $this->assertEquals("ec2", end($res)['id']);
        $this->assertEquals("region", end($res)['service_region']);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testEnable()
    {
        $this->expectOutputString('');

        $res = get_credentials("admin");
        $rowid = end($res)['rowid'];

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"enable", "id"=>$rowid);
        include('../../credinfo.php');
        $this->assertEquals(array('Location: credentials.php'),xdebug_get_headers());

        $res = get_credentials("admin");
        $this->assertEquals("1", end($res)['enabled']);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testDisable()
    {
        $this->expectOutputString('');

        $res = get_credentials("admin");
        $rowid = end($res)['rowid'];

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"disable", "id"=>$rowid);
        include('../../credinfo.php');
        $this->assertEquals(array('Location: credentials.php'),xdebug_get_headers());

        $res = get_credential($rowid);
        $this->assertEquals("0", $res['enabled']);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testOrder()
    {
        $this->expectOutputString('');

        $res = get_credentials("admin");
        $rowid = end($res)['rowid'];
        $order = end($res)['ord'];

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"order", "id"=>$rowid, "order"=>$order, "new_order"=>intval($order)-1);
        include('../../credinfo.php');
        $this->assertEquals(array('Location: credentials.php'),xdebug_get_headers());

        $res = get_credential($rowid);
        $this->assertEquals(intval($order)-1, $res['ord']);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testDelete()
    {
        $this->expectOutputString('');

        $db = new IMDB();
        $res = $db->direct_query("select max(rowid) from credentials");
        $db->close();
        $rowid = $res[0][0];

        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("op"=>"delete", "id"=>$rowid);
        include('../../credinfo.php');
        $this->assertEquals(array('Location: credentials.php'),xdebug_get_headers());
    }
}
?>