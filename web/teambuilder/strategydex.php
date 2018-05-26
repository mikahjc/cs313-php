<!DOCTYPE html>
<html>
<head>
    <title>StrategyDex</title>
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
    $stmt = $db->prepare('SELECT id, name, gen FROM pokemon;');
    $stmt->execute();
    $pokemonList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (isset($_GET["pokemon"])) { 
        $pokemonId = $_GET["pokemon"];
        if ($pokemonId != "") {
            $longStatement =  "SELECT pv.name, pv.number, pv.type1, pv.type2, pv.previous_evolution, pv.base_hp, pv.base_attack, pv.base_defense, pv.base_special_attack, pv.base_special_defense, pv.base_speed\n";
            $longStatement .= "FROM pokemon_view pv\n";
            $longStatement .= "WHERE pv.id=:pokemonId;";
            $stmt = $db->prepare($longStatement);
            $stmt->bindValue("pokemonId", $pokemonId, PDO::PARAM_INT);
            $stmt->execute();
            $pokemonData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    ?>
    <h1>Mini StrategyDex</h1>
    <h2>Select Pokemon:</h2>
    <form action="strategydex.php" type='GET'>
    Type: <select name="typeFilter">
        <option></option>
        <?php foreach($pokemonList as $row) {
            $id = $row["id"];
            $name = $row["name"];
            $gen = $row["gen"];
            if ($gen > 0) {
                echo "<option value='$id'>Gen $gen: $name</option>\n";
            } else if ($gen == -1) {
                echo "<option value='$id'>Alolan: $name</option>\n";
            }
        }?>
    </select>
    <button type='submit'>I choose you!</button>
</form><br>
<?php
if (isset($pokemonData)) { 
echo "<h3>
<table>
<tr>
    <th></th>
    <th>Pokedex #</th>
    <th>Type 1</th>
    <th>Type 2</th>
    <th>Evolves from</th>
    <th>HP</th>
    <th>Atk.</th>
    <th>Def.</th>
    <th>Sp. Atk.</th>
    <th>Sp. Def.</th>
    <th>Speed</th>
</tr>";
    $number = $row["number"];
    $name = $row["name"];
    $type1 = $row["type1"];
    $type2 = $row["type2"];
    $evolution = $row["previous_evolution"];
    $hp = $row["base_hp"];
    $atk = $row["base_attack"];
    $def = $row["base_defense"];
    $spatk = $row["base_special_attack"];
    $spdef = $row["base_special_defense"];
    $speed = $row["base_speed"];
    echo "<tr><td><img src='https://www.smogon.com/dex/media/sprites/xy/$name.gif/></td>";
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
