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
        <div class="menuItems">
            <p><a class="menuEntry" href="selection.php">ASSIGNMENTS</a></p>
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
        <area shape="rect" coords="123,105,367,137" href="selection.php" alt="Continue">
    </map>
    <?php include "footer.php";?>
</body>
</html>