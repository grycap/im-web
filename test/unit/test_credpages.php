<?php

use PHPUnit\Framework\TestCase;

final class CredPagesTest extends TestCase
{

    /**
     * @runInSeparateProcess
     */
    public function testCredForm()
    {
        $this->expectOutputRegex('/.*input type="text" name="username" value="admin".*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("id"=>"1");
        include('../../credform.php');
    }

    /**
     * @runInSeparateProcess
     */
    public function testCredList()
    {
        $this->expectOutputRegex('/.*IMRow\.png.*/');
        $this->expectOutputRegex('/.*credinfo.php\?op=delete&id=2.*/');
        $this->expectOutputRegex('/.*http:\/\/appsgrycap.i3m.upv.es:32080\/vmrc\/vmrc.*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        include('../../credentials.php');
    }
}
?>
