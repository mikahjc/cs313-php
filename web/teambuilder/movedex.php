<!DOCTYPE html>
<html>
<head>
    <title>Movedex</title>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <?php
    try {
        $username = "www-data";
        $password = "webpasswd";
        $db = new PDO('pgsql:host=localhost;dbname=teambuilder;', $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $ex) {
        // Dump error somewhere
        die();
    }
    
    // Get types
    $stmt = $db->prepare('SELECT id, name FROM types;');
    $stmt->execute();
    $types = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $longStatement =  "SELECT m.name, t.name as type, m.category, m.power, m.accuracy, m.pp, m.description\n";
    $longStatement .= "FROM moves m JOIN types t ON m.type = t.id\n";
    if (isset($_GET["typeFilter"]) || isset($_GET["categoryFilter"])) { 
        $typeFilter = $_GET["typeFilter"];
        $categoryFilter = $_GET["categoryFilter"];
        if ($typeFilter != "" && $categoryFilter != "") {
            $longStatement .= "WHERE m.type= :type AND m.category= :category;";
            $stmt = $db->prepare($longStatement);
            $stmt->bindValue('type', $typeFilter, PDO::PARAM_INT);
            $stmt->bindValue('category', $categoryFilter, PDO::PARAM_STR);
        } else if ($typeFilter != "") {
            $longStatement .= "WHERE m.type= :type;";
            $stmt = $db->prepare($longStatement);
            $stmt->bindValue('type', $typeFilter, PDO::PARAM_INT);
        } else if ($categoryFilter != "") {
            $longStatement .= "WHERE m.category= :category;";
            $stmt = $db->prepare($longStatement);
            $stmt->bindValue('category', $_GET["categoryFilter"], PDO::PARAM_STR);
        } else {
            $longStatement .= ";";
            $stmt = $db->prepare($longStatement);
        }
    } else {
        $longStatement .= ";";
        $stmt = $db->prepare($longStatement);
    }
    $stmt->execute();
    $moves = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h1>MoveDex</h1>
    <h2>Filter by:</h2>
    <form action="movedex.php" type='GET'>
    Type: <select name="typeFilter">
        <option></option>
        <?php foreach($types as $row) {
            $id = $row["id"];
            $type = $row["name"];
            echo "<option value='$id'>$type</option>";
        }?>
    </select>
    Category: <select name="categoryFilter">
        <option></option>
        <option value="Physical">Physical</option>
        <option value="Special">Special</option>
        <option value="Status">Status</option>
    </select>
    <button type='submit'>Filter</button>
</form><br>
<table>
<tr>
    <th>Move</th>
    <th>Type</th>
    <th>Category</th>
    <th>Power</th>
    <th>Accuracy</th>
    <th>PP</th>
    <th>Description</th>
</tr>
<?php
    foreach($moves as $row) {
        $move = $row["name"];
        $type = $row["type"];
        $category = $row["category"];
        $power = $row["power"];
        $accuracy = $row["accuracy"];
        $pp = $row["pp"];
        $description = $row["description"];
        echo "<tr><td>$move</td><td>$type</td><td>$category</td>";
        if ($power == 0) {
            echo "<td>--</td>";
        } else {
            echo "<td>$power</td>";
        }
        echo "<td>$accuracy</td><td>$pp</td><td>$description</td></tr>\n";
    } 
?>
</table>
    <?php include "../footer.php"; ?>
</body>
</html>
