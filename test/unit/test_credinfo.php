<?php

use PHPUnit\Framework\TestCase;

final class CredinfoTest extends TestCase
{
	/**
	 * @runInSeparateProcess
	 */
	public function testNoRand()
	{
		$this->expectOutputString('');
		$_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
		include('../../credinfo.php');
		$this->assertEquals(array('Location: error.php'),xdebug_get_headers());
		$this->assertEquals($_SESSION['error'], 'Invalid rand parameter.');
	}

    /**
     * @runInSeparateProcess
     */
    public function testNoOp()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("rand"=>"123");
        include('../../credinfo.php');
        $this->assertEquals(array('Location: error.php'),xdebug_get_headers());
        $this->assertEquals($_SESSION['error'], 'No op');
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreate()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"add", "type"=>"EC2", "id"=>"ec2", "rand"=>"123",
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

        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"edit", "type"=>"EC2", "id"=>"ec2", "rowid"=>$rowid,
        		"service_region"=>"region", "rand"=>"123");
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

        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"enable", "id"=>$rowid, "rand"=>"123");
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

        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"disable", "id"=>$rowid, "rand"=>"123");
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

        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"order", "id"=>$rowid, "order"=>$order, "new_order"=>intval($order)-1, "rand"=>"123");
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

        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"delete", "id"=>$rowid, "rand"=>"123");
        include('../../credinfo.php');
        $this->assertEquals(array('Location: credentials.php'),xdebug_get_headers());
    }
}
?>