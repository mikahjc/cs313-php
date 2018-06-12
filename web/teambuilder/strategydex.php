<!DOCTYPE html>
<html>
<head>
    <title>StrategyDex</title>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/teambuilder.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <div class='centered'>
    <?php
    include "navbar.php";
    require "dbConnect.php";

    $db = get_db();
    
    // Get types
    $stmt = $db->prepare('SELECT id, name, gen FROM pokemon ORDER BY id;');
    $stmt->execute();
    $pokemonList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (isset($_GET["pokemon"])) { 
        $pokemonId = $_GET["pokemon"];
        if ($pokemonId != "") {
            $longStatement =  "SELECT pv.name, pv.number, pv.type1, pv.type2, pv.previous_evolution, pv.base_hp, pv.base_attack, pv.base_defense, pv.base_special_attack, pv.base_special_defense, pv.base_speed\n";
            $longStatement .= "FROM pokemon_view pv\n";
            $longStatement .= "WHERE pv.id=:pokemonId";
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
    Type: <select name="pokemon">
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
$pokemonData = $pokemonData[0];
$number = $pokemonData["number"];
    $name = $pokemonData["name"];
    $type1 = $pokemonData["type1"];
    $type2 = $pokemonData["type2"];
    $evolution = $pokemonData["previous_evolution"];
    $hp = $pokemonData["base_hp"];
    $atk = $pokemonData["base_attack"];
    $def = $pokemonData["base_defense"];
    $spatk = $pokemonData["base_special_attack"];
    $spdef = $pokemonData["base_special_defense"];
    $speed = $pokemonData["base_speed"];

    if ($evolution == "") {
        $evolution = "Nothing";
    }
echo "<h2><a href='https://www.smogon.com/dex/sm/pokemon/$name'>$name</a></h2>
<table class='values'>
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
    </tr>
    <tr>
        <td><img src='/assets/img/sprites/pkmn/". strtolower($name) .".gif'/></td>
        <td>$number</td>
        <td>$type1</td>
        <td>$type2</td>
        <td>$evolution</td>
        <td>$hp</td>
        <td>$atk</td>
        <td>$def</td>
        <td>$spatk</td>
        <td>$spdef</td>
        <td>$speed</td>
    </tr>";
     
}
?>
</table>
</div>
    <?php include "../footer.php"; ?>
</body>
</html>
