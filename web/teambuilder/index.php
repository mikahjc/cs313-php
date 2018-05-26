<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Teambuilder Home</title>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <div id="controls">
        <a class='button' href="strategydex.php">Mini StrategyDex</a>
        <a class='button' href="movedex.php">Movedex</a>
        <a class='button' href="#">Abilitydex</a>
        <a class='button' href="teams.php">Manage Teams</a>
    </div>
    <?php include "../footer.php"; ?>
</body>
</html>
