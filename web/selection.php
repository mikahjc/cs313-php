<!DOCTYPE html>
<html>
<head>
    <title>Mikah's CS 313 Selection</title>
    <link rel="stylesheet" href="style.css">
    <script src="selector.js"></script>
</head>
<body>
    <div id="gbc" class="container">
        <img class="center" src="gbc_select.png" width="540" height="902">
        <div class="menuItems">
            <p><a class="menuEntry" href="index.php">ABOUT ME</a></p>
            <?php
                for ($i = 3; $i <= 14; $i++) {
                    $currentWeek = "week".$i;
                    echo "<p><a class='menuEntry' id='".$currentWeek."' ";
                    if (file_exists("./".$currentWeek)) {
                        echo "href='./".$currentWeek".'/index.php'>";
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

</body>
</html>