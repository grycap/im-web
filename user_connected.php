<?php
if (isset($_SESSION['user_name'])) {
        $user_name = $_SESSION['user_name'];
} elseif (isset($_SESSION['user'])) {
    $user_name = $_SESSION['user'];
}
?>
<div id="caja_nombre_usuario">
        <div id="texto_open_sans">
                <?php 
                if (isset($user_name)) {
                    ?>
                &nbsp&nbsp&nbsp&nbsp<img style="vertical-align: middle;" src="images/verde.png" />
                    <?php 
                               if (isset($_SESSION['user_token'])) {
                               	echo '<a href="oidc_user_info.php">';
                               }
                               echo htmlspecialchars($user_name);
                               echo ' is connected';
                               if (isset($_SESSION['user_token'])) {
                               	echo '</a>';
                               }
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

