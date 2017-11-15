<?php

use PHPUnit\Framework\TestCase;

final class RADLPagesTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testRADLForm()
    {
        $res = insert_radl("admin", "radltest", "radldesc", "radlbody", "users", '1', '0', '1', '0', '0', '0');
        $this->assertEquals("", $res);
        $res = get_radls("admin");
        $rowid = $res[0]["rowid"];

        $this->expectOutputRegex('/.*input type="hidden" name="id" value="' . $rowid . '".*/');
        $this->expectOutputRegex('/.*<textarea type="RADL" align="bottom" name="radl">radlbody<\/textarea>.*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("id"=>$rowid);
        include('../../radlform.php');
    }

    /**
     * @runInSeparateProcess
     */
    public function testRADLList()
    {
        $res = get_radls("admin");
        $rowid = $res[0]["rowid"];

        $this->expectOutputRegex('/.*gradlinfo.php\?op=delete&id=' . $rowid . '.*/');
        $this->expectOutputRegex('/.*radlinfo.php\?op=launch&id=' . $rowid . '.*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        include('../../radl_list.php');

        $res = delete_radl($rowid);
        $this->assertEquals("", $res);
    }
}
?>
