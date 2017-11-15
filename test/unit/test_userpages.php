<?php

use PHPUnit\Framework\TestCase;

final class UserPagesTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testUserConn()
    {
        $this->expectOutputRegex('/.*admin is connected.*/');
        $_SESSION = array("user"=>"admin");
        include('../../user_connected.php');
    }

    /**
     * @runInSeparateProcess
     */
    public function testUserForm()
    {
        $this->expectOutputRegex('/.*input type="hidden" name="id" value="admin".*/');
        $this->expectOutputRegex('/.*input type="text" name="username" value="admin".*/');
        $this->expectOutputRegex('/.*option value="1"[\\n\n ]+selected="selected" +>Administrator.*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("id"=>"admin");
        include('../../userform.php');
    }

    /**
     * @runInSeparateProcess
     */
    public function testUserList()
    {
        $this->expectOutputRegex('/.*<td>[ \n\\n]+admin[ \n\\n]+<\/td>.*/');
        $this->expectOutputRegex('/.*<td>[ \n\\n]+users<br>[ \n\\n]+<\/td>.*/');
        $this->expectOutputRegex('/.*<td>[ \n\\n]+Administrator[ \n\\n]+<\/td>.*/');
        $this->expectOutputRegex('/.*userform.php\?id=admin.*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        include('../../user_list.php');
    }
}
?>
