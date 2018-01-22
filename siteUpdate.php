<?php

    session_start();
    include "database.php";
    include "adminCheck.php";
    if(!empty($_POST['alert']))
        {
            $sql = "UPDATE sitecontent SET attention=:alert WHERE ID=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["alert" => $_POST['alert'],"id" => 1]);
        }
        if(!empty($_POST['aboutUs']))
        {
            $sql = "UPDATE sitecontent SET aboutUs=:aboutUs WHERE ID=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["aboutUs" => $_POST['aboutUs'],"id" => 1]);
        }
        if(!empty($_POST['whatItTakes']))
        {
            $sql = "UPDATE sitecontent SET whatitTakes=:whatItTakes WHERE ID=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["whatItTakes" => $_POST['whatItTakes'],"id" => 1]);
        }
        if(!empty($_POST['whatItTakesUnder']))
        {
            $sql = "UPDATE sitecontent SET whatitTakes2=:whatItTakesUnder WHERE ID=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(["whatItTakesUnder" => $_POST['whatItTakesUnder'],"id" => 1]);
        }
        header("Location: manage-site-content.php");
    
?>