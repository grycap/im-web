<?php

use PHPUnit\Framework\TestCase;

final class RecipeTest extends TestCase
{

    public static function setUpBeforeClass() {
        $db = new SQLite3("/tmp/recipe.db"); 
        $res = $db->exec('CREATE TABLE "recipes" (
            name VARCHAR(256) NOT NULL,
            version VARCHAR(256) NOT NULL,
            module VARCHAR(256) NOT NULL,
            recipe VARCHAR(500) NOT NULL,
            isapp BOOLEAN NOT NULL,
            galaxy_module VARCHAR(256) NOT NULL,
            description VARCHAR(500) NOT NULL,
            requirements VARCHAR(500) NOT NULL
            );
            CREATE UNIQUE INDEX pk_index ON "recipes"("name","version");');
        $db->close();
    }

    public static function tearDownAfterClass() {
        unlink("/tmp/recipe.db");
    }

    public function testRecipe()
    {
        $res = insert_recipe("recname", "recver", "recdesc", "module", "recipe", "galaxy_module", "requirements");
        $this->assertEquals("", $res);

        $res = get_recipes();
        $rowid = $res[0]["rowid"];
        $this->assertEquals("recname", $res[0]["name"]);

        $res = get_recipe($rowid);
        $this->assertEquals("module", $res["module"]);

        $res = delete_recipe($rowid);
        $this->assertEquals("", $res);

        $res = get_recipe($rowid);
        $this->assertEquals(NULL, $res);
    }

}
?>