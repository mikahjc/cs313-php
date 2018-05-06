<!DOCTYPE html>
<html>
<head>
    <title>Mikah's CS 313 Homepage</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="gbc" class="container">
        <img class="center" src="gbc_main.png" width="540" height="902" usemap="#screenMap">
        <div class="timeDisplay">
            <?php
                echo strtoupper(date("l"))."<br>".str_repeat('&nbsp;',3);
                if (date("G") < 4 || date("G") > 17) {
                    echo "NITE&nbsp;";
                } elseif (date("G") > 4 || date("G") < 10) {
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
</body>
</html>