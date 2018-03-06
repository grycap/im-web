<?php
if (isset($_SESSION['user_name'])) {
        $user = $_SESSION['user_name'];
} elseif (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
}
?>
<div id="caja_nombre_usuario">
        <div id="texto_open_sans">
                <?php 
                        if (isset($user)) {
                ?>
                &nbsp&nbsp&nbsp&nbsp<img style="vertical-align: middle;" src="images/verde.png" />
                <?php 
                                echo $user;
                                echo ' is connected'; 
                ?> 
&nbsp&nbsp|&nbsp&nbsp <a href = "logout.php">Sign out</a>
&nbsp&nbsp|&nbsp&nbsp
<?php
                                if (!isset($_SESSION['user_token'])) {
?>
<a href = "password.php">Change password</a>
                <?php 
                                }
?>
&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
<?php
                        }
                ?> 
</div>
</div>

