<?php
    $error = NULL;
    session_start();
    if(!isset($_SESSION["loggedin"])) {
        session_destroy();
        header("location: /teambuilder/login.php");
        die();
    }
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teambuilder Home</title>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <?php
    include "navbar.php";
    include "../footer.php";
    ?>
</body>
</html>
