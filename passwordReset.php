<!DOCTYPE HTML>
<?php
    session_start();
    require "database.php";

    if(isset($_POST["submit"])){
        $sql = "SELECT * FROM students WHERE studentID = :studentID";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['studentID' => $_GET['userID']]);
        $userData = $stmt->fetchAll();

        if($userData[0][4]===$_GET['hash']){
            $sql = "UPDATE users SET passwordHash = :passHash";
            $stmt = $pdo->prepare($sql);
            $success = $stmt->execute(['passHash' => password_hash($_POST['password'], PASSWORD_DEFAULT)]);
        }
    }
?>
<html>
    <head>

        <title>LPNHS - Password Reset</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="baseCSS.css">


    </head>

    <header id = "header"><?php include "header.php"; ?></header>

    <body>
		<div id = "footerPusher">

            <form id="login" class="form" action="passwordReset.php?hash=<?php echo $_GET['hash'];?>&userID=<?php echo $_GET['userID'];?>" method="post">
                <div>
                    <h id="logTitle">Password Reset</h>
                    <hr class="loghr">
                    <br/>
                    <?php 
                        if($success){
                            echo '<p style = "text-align: center; font-size: 16px; font-weight: bold;">Password Updated</p>';
                        }
                    ?>
                    <input class="input2" placeholder = "Password*" type = "password" name = "password" autofocus required>
                    <br/><br/>
                    <button id = "loginButton" type = "submit" name = 'submit' value="changePassword" style = "min-height: 75px;">Change Password</button>
                </div>              
            </form>
            
        </div>
    </body>

    <footer id = "footer"><?php include "footer.php"; ?></footer>

</html> 