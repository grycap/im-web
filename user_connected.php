<div id="caja_nombre_usuario">
        <div id="texto_open_sans">
                <?php 
                        if (isset($_SESSION['user'])) {
                ?>
                &nbsp&nbsp&nbsp&nbsp<img style="vertical-align: middle;" src="images/verde.png" />
                <?php 
                                echo $_SESSION['user'];
                                echo ' is connected'; 
                ?> 
&nbsp&nbsp|&nbsp&nbsp <a href = "logout.php">Sign out</a>
&nbsp&nbsp|&nbsp&nbsp <a href = "password.php">Change password</a>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                <?php 
                        }
                ?> 
</div>
</div>

