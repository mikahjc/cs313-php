<?php $GBC_NOTICE = True;?>

<!DOCTYPE html>
<html>
<head>
    <title>Mikah's CS 313 Homepage</title>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div id="gbc" class="container">
        <img class="center" src="assets/img/gbc_main.png" width="540" height="902" usemap="#screenMap">
        <div class="gbMenuItems">
            <p><a class="gbMenuEntry" href="selection.php">ASSIGNMENTS</a></p>
            <p>NEW GAME</p>
            <p>OPTION</p>
        </div>
        <div id="timeDisplay">
            <?php
                date_default_timezone_set("America/Boise");

                $currentTimezone = date_default_timezone_get();
                echo strtoupper(date("l"))."<br>".str_repeat('&nbsp;',3);
                if (date("G") < 4 || date("G") > 17) {
                    echo "NITE&nbsp;";
                } elseif (date("G") > 4 && date("G") < 10) {
                    echo "MORN&nbsp;";
                } else {
                    echo "DAY&nbsp;";
                }
                echo date("g:i");
            ?>
        </div>
    </div>
    <map name="screenMap">
        <area shape="rect" coords="89,521,143,564" href="#up" alt="UP">
        <area shape="rect" coords="89,614,143,657" href="#down" alt="DOWN">
        <area shape="rect" coords="49,561,90,616" href="#left" alt="LEFT">
        <area shape="rect" coords="142,561,183,616" href="#right" alt="RIGHT">
        <area shape="circle" coords="361,605,34" href="#b" alt="B">
        <area shape="circle" coords="456,575,34" href="#a" alt="A">
        <area shape="rect" coords="207,729,257,751" href="#select" alt="SELECT">
        <area shape="rect" coords="283,729,333,751" href="#start" alt="START">
    </map>
    <?php include "footer.php";?>
</body>
</html>