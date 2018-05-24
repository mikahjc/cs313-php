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
<body onload="initializeGBMenu(5)">
    <div id="gbc" class="container">
        <img class="center" src="assets/img/gbc_select.png" width="540" height="902" usemap="#screenMap">
        <div class="gbMenuItems" id="menuContainer">
            <p onmouseover="menuMouseOver(0)"><a id="home" class="gbMenuEntry" href="/">MAIN MENU</a></p>
            <?php
                for ($i = 3; $i <= 14; $i++) {
                    $currentWeek = "week".$i;
                    echo "<p onmouseover='menuMouseOver(".($i-2);
                    echo ")'><a class='gbMenuEntry' id='".$currentWeek."' ";
                    if (file_exists("./".$currentWeek."/index.php")) {
                        echo "href='./".$currentWeek."''>";
                    } else {
                        echo "href='#' onClick='oak()'>";
                    }
                    echo "WEEK ".$i."</a></p>\n";
                }
            ?>
        </div>
        <img id="selector" src="/assets/img/select.png"> 
        <div id="dialogLine1">Choose a page.</div>
        <div id="dialogLine2"></div>
    </div>
    <map name="screenMap">
        <area shape="rect" coords="89,521,143,564" id="up_button" href="#up" alt="UP">
        <area shape="rect" coords="89,614,143,657" id="down_button" href="#down" alt="DOWN">
        <area shape="rect" coords="49,561,90,616" href="#left" alt="LEFT">
        <area shape="rect" coords="142,561,183,616" href="#right" alt="RIGHT">
        <area shape="circle" coords="361,605,34" href="/" alt="B">
        <area shape="circle" coords="456,575,34" id="a_button" href="javascript:clickSelectedLink();" alt="A">
        <area shape="rect" coords="207,729,257,751" href="#select" alt="SELECT">
        <area shape="rect" coords="283,729,333,751" href="#start" alt="START">
    </map>
    <?php include "footer.php"; ?>
</body>
</html>
