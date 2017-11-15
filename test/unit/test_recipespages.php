<?php

use PHPUnit\Framework\TestCase;

final class RecipePagesTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testRecipeForm()
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

        $res = insert_recipe("recname", "recver", "recdesc", "module", "recipe", "galaxy_module", "requirements");
        $this->assertEquals("", $res);
        $res = get_recipes();
        $rowid = $res[0]["rowid"];

        $this->expectOutputRegex('/.*input type="hidden" name="id" value="' . $rowid . '".*/');
        $this->expectOutputRegex('/.*<textarea type="RECIPE" align="bottom" name="recipe">recipe<\/textarea>.*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("id"=>$rowid);
        include('../../recipeform.php');
    }

    /**
     * @runInSeparateProcess
     */
    public function testRecipeList()
    {
        $res = get_recipes();
        $rowid = $res[0]["rowid"];

        $this->expectOutputRegex('/.*recipeform.php\?id=' . $rowid . '.*/');
        $this->expectOutputRegex('/.*recipeinfo.php\?op=delete&id=' . $rowid . '.*/');
        $this->expectOutputRegex('/.*<td>[ \n\\n\t]+recname[ \n\\n\t]+<\/td>.*/');
        $this->expectOutputRegex('/.*<td>[ \n\\n\t]+recver[ \n\\n\t]+<\/td>.*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        include('../../recipe_list.php');

        unlink("/tmp/recipes.db");
    }
}
?>
