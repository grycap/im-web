<?php

use PHPUnit\Framework\TestCase;

final class DBTest extends TestCase
{

    public function testSQLiteDB()
    {
    	$db = new IMDBSQLite3();
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

    private function expectQueries($queries)
    {
    	$mysqli = $this->getMockBuilder('mysqli')
    	->setMethods(array('query','real_escape_string', 'commit', 'select_db', 'close'))
    	->getMock();
    	
    	$mysqli->expects($this->any())
    	->method('real_escape_string')
    	->will($this->returnCallback(function($str) { return addslashes($str); }));

    	$mysqli->expects($this->any())
    	->method('commit')
    	->willReturn(True);
    	
    	$mysqli->expects($this->any())
    	->method('select_db')
    	->willReturn(True);

    	$mysqli->expects($this->any())
    	->method('query')
    	->will($this->returnCallback(function($query) use ($queries) {
    		$this->assertTrue(isset($queries[$query]));
    		$results = $queries[$query];
    		$mysqli_result = $this->getMockBuilder('mysqli_result')
    		->setMethods(array('fetch_assoc','free'))
    		->disableOriginalConstructor()
    		->getMock();
    		$mysqli_result->expects($this->any())
    		->method('fetch_assoc')
    		->will($this->returnCallback(function() use ($results) {
    			static $r = 0;
    			return isset($results[$r])?$results[$r++]:false;
    		}));
    			return $mysqli_result;
    	}));
    		
    	return $mysqli;
    }
    
    public function testMySQLDB()
    {
    	$username = uniqid();
    	$mysqli = $this->expectQueries(array(
    			"select * from user" => array(array('username' => 'admin')),
    			"select * from user" => array(array('username' => 'admin')),
    			"insert into user values(NULL, '" . $username . "','passwd','0')" => True,
    			"select * from user where username = '" . $username . "'" => array(array('username' => 'admin')),
    			"update user set password = 'somepass' where username = '" . $username . "'" => array(array('username' => 'admin')),
    			"select * from user where username = '" . $username . "'" => array(array('username' => 'admin', 'password' => 'somepass'))
    	));
    	$db = new IMDBMySQL($mysqli);
    	$res = $db->direct_query("select * from user");
    	
    	$this->assertEquals("admin",$res[0]['username']);
    	
    	$res = $db->get_items_from_table("user");
    	$this->assertEquals("admin",$res[0]['username']);

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
?>