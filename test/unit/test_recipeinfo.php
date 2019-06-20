<?php

use PHPUnit\Framework\TestCase;

final class RedipeInfoTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testNoRand()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        include('../../recipeinfo.php');
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
    	include('../../recipeinfo.php');
    	$this->assertEquals(array('Location: error.php'),xdebug_get_headers());
    	$this->assertEquals($_SESSION['error'], 'No op');
    }

    /**
     * @runInSeparateProcess
     */
    public function testCreate()
    {
        $db = new SQLite3("/tmp/recipes.db"); 
        $res = $db->exec('CREATE TABLE IF NOT EXISTS "recipes" (
            name VARCHAR(256) NOT NULL,
            version VARCHAR(256) NOT NULL,
            module VARCHAR(256) NOT NULL,
            recipe VARCHAR(500) NOT NULL,
            isapp BOOLEAN NOT NULL,
            galaxy_module VARCHAR(256) NOT NULL,
            description VARCHAR(500) NOT NULL,
            requirements VARCHAR(500) NOT NULL
            );');
        $db->close();

        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"add", "name"=>"radltest", "version"=>"version", "rand"=>"123",
                    "module"=>"module", "recipe"=>"recipe", "galaxy_module"=>"galaxy_module",
                    "description"=>"description", "requirements"=>"requirements");
        include('../../recipeinfo.php');
        $this->assertEquals(array('Location: recipe_list.php'),xdebug_get_headers());

        $res = get_recipes();
        $this->assertEquals("radltest", end($res)['name']);
        $this->assertEquals(1, end($res)['rowid']);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testEdit()
    {
        $this->expectOutputString('');

        $res = get_recipes();
        $rowid = end($res)['rowid'];

        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"edit", "id"=>$rowid, "name"=>"radltest", "version"=>"version",
                    "module"=>"newmodule", "recipe"=>"recipe", "galaxy_module"=>"galaxy_module",
        		    "description"=>"description", "requirements"=>"requirements", "rand"=>"123");
        include('../../recipeinfo.php');
        $this->assertEquals(array('Location: recipe_list.php'),xdebug_get_headers());

        $res = get_recipe($rowid);
        $this->assertEquals("newmodule", $res['module']);
    }

    /**
     * @runInSeparateProcess
     * @depends testCreate
     */
    public function testDelete()
    {
        $this->expectOutputString('');

        $res = get_recipes();
        $rowid = end($res)['rowid'];

        $_SESSION = array("user"=>"admin", "password"=>"admin", "rand"=>"123");
        $_POST = array("op"=>"delete", "id"=>$rowid, "rand"=>"123");
        include('../../recipeinfo.php');
        $this->assertEquals(array('Location: recipe_list.php'),xdebug_get_headers());

        unlink("/tmp/recipes.db");
    }
}
?>