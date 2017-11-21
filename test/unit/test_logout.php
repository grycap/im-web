<?php

use PHPUnit\Framework\TestCase;

final class LogoutPageTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testLogoutPage()
    {
        $this->expectOutputString('');
        $_SESSION = array("user"=>"admin", "password"=>"admin");
        include('../../logout.php');
        $this->assertFalse(isset($_SESSION['user']));
        $this->assertContains('Location: index.php',xdebug_get_headers()); 
    }

}
?>
