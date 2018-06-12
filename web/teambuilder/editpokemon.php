<?php
if (!isset($_POST["pokemon"]) || $_POST["pokemon"] == "") {
    header("Location: /teambuilder/billspc.php");
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Pokemon</title>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/teambuilder.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/assets/script/newPokemon.js"></script>
</head>
<body>
    <?php
    include "navbar.php";
    session_start();
    if(!isset($_SESSION["loggedin"])) {
        session_destroy();
        header("location: /teambuilder/login.php");
        die();
    }
    require "dbConnect.php";

    $db = get_db();
    
    // Get types
    $stmt = $db->prepare('SELECT p.name, t1.name as type1, t2.name as type2 FROM pokemon p LEFT JOIN types t1 ON p.type1 = t1.id LEFT JOIN types t2 ON p.type2 = t2.id WHERE p.id=:pokemonId;');
    $stmt->bindValue("pokemonId", $_POST["pokemon"], PDO::PARAM_INT);
    $stmt->execute();
    $name = $stmt->fetch(PDO::FETCH_ASSOC);    

    $stmt = $db->prepare('SELECT ability, name, hidden FROM allowed_abilities join abilities on allowed_abilities.ability = abilities.id WHERE pokemon=:pokemonId;');
    $stmt->bindValue("pokemonId", $_POST["pokemon"], PDO::PARAM_INT);
    $stmt->execute();
    $abilityList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('SELECT id, name, boosted, weakened FROM natures;');
    $stmt->execute();
    $natureList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('SELECT id, name, description FROM items;');
    $stmt->execute();
    $itemList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('SELECT move AS id, name, type, category, power, accuracy, pp, description, how_learned, learned_at FROM allowed_moves JOIN moves ON allowed_moves.move = moves.id WHERE pokemon=:pokemonId;');
    $stmt->bindValue("pokemonId", $_POST["pokemon"], PDO::PARAM_INT);
    $stmt->execute();
    $moveList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h1>New Pokemon</h1>
    <form action="billspc.php" method='POST'>
        <input type="hidden" name="newPokemon" value=<?php echo "'".$_POST["pokemon"]."'"?>>
        <table>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Type</th>
                <th>Level</th>
                <th>Ability</th>
                <th>Nature</th>
                <th>Held Item</th>
                <th>Moves</th>
            </tr>
            <tr>
                <td><img src=<?php echo "'/assets/img/sprites/pkmn/". strtolower($name["name"]) .".gif'";?>/></td>
                <td><span class="name"><?=$name["name"]?><span><br><br><input type="text" name="nickname" placeholder="Nickname"></td>
                <td><?php
                    echo $name["type1"];
                    if(!is_null($name["type2"])) {
                        echo "<br>".$name["type2"];
                    }
                ?>
                <td>
                    <input type="number" min="1" max="100" value="50" style="width: 35px;" name="level">
                </td>
                <td>
                    <select name="ability">
                        <option></option>
                        <?php foreach($abilityList as $ability) {
                            $id = $ability["ability"];
                            $name = $ability["name"];
                            $hidden = $ability["hidden"];
                            if ($hidden) {
                                echo "<option style='background-color: lightgray;' value='$id'>$name</option>";
                            } else {
                                echo "<option value='$id'>$name</option>";
                            }
                        }?>
                    </select>
                </td>
                <td>
                    <select name="nature">
                        <option></option>
                        <?php foreach($natureList as $nature) {
                            $id = $nature["id"];
                            $name = $nature["name"];
                            echo "<option value='$id'>$name</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select name="item">
                        <option></option>
                        <?php foreach($itemList as $item) {
                            $id = $item["id"];
                            $name = $item["name"];
                            echo "<option value='$id'>$name</option>";
                        }  
                        ?>
                    </select>
                </td>
                <td>
                    <select name="move1">
                        <option></option>
                        <?php foreach($moveList as $move) {
                            $id = $move["id"];
                            $name = $move["name"];
                            echo "<option value='$id'>$name</option>";
                        }
                        ?>
                    </select><br>
                    <select name="move2">
                        <option></option>
                        <?php foreach($moveList as $move) {
                            $id = $move["id"];
                            $name = $move["name"];
                            echo "<option value='$id'>$name</option>";
                        }
                        ?>
                    </select><br>
                    <select name="move3">
                        <option></option>
                        <?php foreach($moveList as $move) {
                            $id = $move["id"];
                            $name = $move["name"];
                            echo "<option value='$id'>$name</option>";
                        }
                        ?>
                    </select><br>
                    <select name="move4">
                        <option></option>
                        <?php foreach($moveList as $move) {
                            $id = $move["id"];
                            $name = $move["name"];
                            echo "<option value='$id'>$name</option>";
                        }
                        ?>
                    </select>
                </td></tr>
                <tr><td colspan='8' style='text-align: center'>
                    <table style="margin: auto;">
                        <tr><th rowspan="2">IVs</th><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
                        <tr>
                            <td><input style="width: 40px;" type="number" name="hp_iv" min="0" max="31" value="0"></td>
                            <td><input style="width: 40px;" type="number" name="atk_iv" min="0" max="31" value="0"></td>
                            <td><input style="width: 40px;" type="number" name="def_iv" min="0" max="31" value="0"></td>
                            <td><input style="width: 40px;" type="number" name="spa_iv" min="0" max="31" value="0"></td>
                            <td><input style="width: 40px;" type="number" name="spd_iv" min="0" max="31" value="0"></td>
                            <td><input style="width: 40px;" type="number" name="spe_iv" min="0" max="31" value="0"></td>
                        </tr>
                    </table><br>
                    <table style="margin: auto;">
                        <tr><th rowspan="2">EVs</th><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
                        <tr>
                            <td><input style="width: 40px;" type="number" name="hp_ev" min="0" max="510" value="0"></td>
                            <td><input style="width: 40px;" type="number" name="atk_ev" min="0" max="510" value="0"></td>
                            <td><input style="width: 40px;" type="number" name="def_ev" min="0" max="510" value="0"></td>
                            <td><input style="width: 40px;" type="number" name="spa_ev" min="0" max="510" value="0"></td>
                            <td><input style="width: 40px;" type="number" name="spd_ev" min="0" max="510" value="0"></td>
                            <td><input style="width: 40px;" type="number" name="spe_ev" min="0" max="510" value="0"></td>
                        </tr>
                    </table>
                </td></tr>
            </tr>
            <tr>
                <td colspan="8">
                    <button type="submit" style="width: 100%">Save</button>
                </td>
            </tr>
        </table>
    </form>
    <?php include "../footer.php"; ?>
</body>
</html>
