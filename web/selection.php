<?php $GBC_NOTICE = True;?>

<!DOCTYPE html>
<html>
<head>
    <title>Mikah's CS 313 Selection</title>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="assets/script/selector.js"></script>
</head>
<body>
    <div id="gbc" class="container">
        <img class="center" src="assets/img/gbc_select.png" width="540" height="902">
        <div class="gbMenuItems">
            <p><a class="gbMenuEntry" href="/">MAIN MENU</a></p>
            <?php
                for ($i = 3; $i <= 14; $i++) {
                    $currentWeek = "week".$i;
                    echo "<p><a class='gbMenuEntry' id='".$currentWeek."' ";
                    if (file_exists("./".$currentWeek."/index.php")) {
                        echo "href='./".$currentWeek."''>";
                    } else {
                        echo "href='#' onClick='oak()'>";
                    }
                    echo "WEEK ".$i."</a></p>";
                }
            ?>
        </div>
        <div id="dialogLine1">Choose a page.</div>
        <div id="dialogLine2"></div>
    </div>
    <?php include "footer.php"; ?>
</body>
</html>