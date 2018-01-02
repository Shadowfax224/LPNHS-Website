<!DOCTYPE HTML>
<?php 
    session_start();
    include "database.php";
?>
<html>
<head>
    <title>NHS Test - Manage Site Content</title>
    
    <!--TODO: Icon-->
    
    
    <!--Style Sheets-->
    <link rel="stylesheet" href="baseCSS.css">
    <style>
        #footerPusher p{
            text-align: left;
        }
        .expander{
            display: inline-block;
        }
        input{
            display: block;
            width: 75%;
            margin: 10px;
        }
        button.submit{
            margin: 10px;
        }
    </style>
    
    <!--Scripts-->
    <!--jQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>
    
	<!--Included via PHP-->
<header id = "header"><?php include "header.php"; ?></header>

<body>
    <div id = "footerPusher">
        <!--Included via JQuery-->
        <header id = "header"></header>

        <!--Fixed Img in Background-->
        <img id = "fixedBGImg" src = "https://www.nhs.us/assets/images/nhs/NHS_header_logo.png">
        
        <div id = "index" class = "classic panel">
            <p class = "expander">Manage Home Page</p>
            <button type = "button" id = "indexExpander" class = "classicColor expander">Expand</button>
            <div id = "indexDropdown" class = "vanish">
                <hr>
                <p>Alert</p>
                <input id = "alertText" placeholder="Enter text to appear on Home Page. Leave empty to remove alert.">
                <p>About Us</p>
                <input id = "aboutUsText">
                <button id = "indexSubmit" type = "button" class = "classicColor submit">Submit</button>
            </div>
        </div>
        <div id = "whatItTakes" class = "classic panel">
            <p class = "expander">Manage What It Takes Page</p>
            <button type = "button" id = "whatItTakesExpander" class = "classicColor expander">Expand</button>
            <div id = "whatItTakesDropdown" class = "vanish">
                <hr>
                
                <button id = "whatItTakesSubmit" type = "button" class = "classicColor submit">Submit</button>
            </div>
        </div>
    </div>
    <!--Included via JQuery-->
    <footer id = "footer"><?php include 'footer.php';?></footer>
    <!--Firebase.js
    <script src="https://www.gstatic.com/firebasejs/4.1.3/firebase.js"></script>
    <script>
        // Initialize Firebase
        var config = {
            apiKey: "AIzaSyByQW8Cyp9yAIMm5xCrNZqF-5kqJ-w6g-4",
            authDomain: "nhs-project-test.firebaseapp.com",
            databaseURL: "https://nhs-project-test.firebaseio.com",
            projectId: "nhs-project-test",
            storageBucket: "nhs-project-test.appspot.com",
            messagingSenderId: "239221174231"
        };
        firebase.initializeApp(config);
    </script>
    <script src = "requireAdminPermissions.js"></script>
    <script src = "manageSiteContent.js"></script>
	-->
</body>
</html>