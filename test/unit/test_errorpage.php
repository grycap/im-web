<?php

use PHPUnit\Framework\TestCase;

final class ErrorPageTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testErrorPage()
    {
        $this->expectOutputRegex('/.*error msg.*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("msg"=>"error msg");
        include('../../error.php');
    }

    /**
     * @runInSeparateProcess
     */
    public function testIndexErrorPage()
    {
        $this->expectOutputRegex('/.*error msg.*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("error"=>"error msg");
        include('../../index.php');
    }

    /**
     * @runInSeparateProcess
     */
    public function testIndexInfoPage()
    {
        $this->expectOutputRegex('/.*info msg.*/');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        $_GET = array("info"=>"info msg");
        include('../../index.php');
    }
}
?>
