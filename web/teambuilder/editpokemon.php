<?php
if (!isset($_POST["id"])) {
    header("Location: /teambuilder/billspc.php");
    die();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Pokemon</title>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="/assets/css/style.css">
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

    $longStatement = "SELECT id, pokemon, nickname, level, ability, nature, held_item, move_1, move_2, move_3, move_4, hp_iv, atk_iv, def_iv, spa_iv, spd_iv, spe_iv, hp_ev, atk_ev, def_ev, spa_ev, spd_ev, spe_ev FROM team_members WHERE owner=:tid AND id=:pid;";
    $stmt = $db->prepare($longStatement);
    $stmt->bindValue("tid", $_SESSION["userId"], PDO::PARAM_INT);
    $stmt->bindValue("pid", $_POST["id"], PDO::PARAM_INT);
    $stmt->execute();
    $teamMember = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get name
    $stmt = $db->prepare('SELECT name FROM pokemon WHERE id=:pokemonId;');
    $stmt->bindValue("pokemonId", $teamMember["pokemon"], PDO::PARAM_INT);
    $stmt->execute();
    $name = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('SELECT ability, name, hidden FROM allowed_abilities join abilities on allowed_abilities.ability = abilities.id WHERE pokemon=:pokemonId;');
    $stmt->bindValue("pokemonId", $teamMember["pokemon"], PDO::PARAM_INT);
    $stmt->execute();
    $abilityList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('SELECT id, name, boosted, weakened FROM natures;');
    $stmt->execute();
    $natureList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('SELECT id, name, description FROM items;');
    $stmt->execute();
    $itemList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('SELECT move AS id, name, type, category, power, accuracy, pp, description, how_learned, learned_at FROM allowed_moves JOIN moves ON allowed_moves.move = moves.id WHERE pokemon=:pokemonId;');
    $stmt->bindValue("pokemonId", $teamMember["pokemon"], PDO::PARAM_INT);
    $stmt->execute();
    $moveList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h1>Edit Pokemon</h1>
    <form action="billspc.php" method='POST'>
        <input type="hidden" name="editPokemon" value=<?php echo "'".$_POST["id"]."'"?>>
        <table>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Level</th>
                <th>Ability</th>
                <th>Nature</th>
                <th>Held Item</th>
                <th>Moves</th>
                <th></th>
            </tr>
            <tr>
                <td><img src=<?php echo "'/assets/img/sprites/pkmn/". strtolower($name["name"]) .".gif'";?>/></td>
                <td><?=$name["name"]?><br><input type="text" name="nickname" placeholder="Nickname" value=<?php echo "'".$teamMember["nickname"]."'";?>></td>
                <td>
                    <input type="number" min="1" max="100" value=<?php echo "'".$teamMember["level"]."'";?> name="level">
                </td>
                <td>
                    <select name="ability">
                        <option></option>
                        <?php foreach($abilityList as $ability) {
                            $id = $ability["ability"];
                            $name = $ability["name"];
                            $hidden = $ability["hidden"];
                            echo "<option ";
                            if ($id == $teamMember["ability"]) {
                                echo "selected ";
                            }
                            if ($hidden) {
                                echo "style='background-color: lightgray;' value='$id'>$name</option>";
                            } else {
                                echo "value='$id'>$name</option>";
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
                            echo "<option ";
                            if ($id == $teamMember["nature"]) {
                                echo "selected ";
                            }
                            echo "value='$id'>$name</option>";
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
                            echo "<option ";
                            if ($id == $teamMember["held_item"]) {
                                echo "selected ";
                            }
                            echo "value='$id'>$name</option>";
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
                            echo "<option ";
                            if ($id == $teamMember["move_1"]) {
                                echo "selected ";
                            }
                            echo "value='$id'>$name</option>";
                        }
                        ?>
                    </select><br>
                    <select name="move2">
                        <option></option>
                        <?php foreach($moveList as $move) {
                            $id = $move["id"];
                            $name = $move["name"];
                            echo "<option ";
                            if ($id == $teamMember["move_2"]) {
                                echo "selected ";
                            }
                            echo "value='$id'>$name</option>";
                        }
                        ?>
                    </select><br>
                    <select name="move3">
                        <option></option>
                        <?php foreach($moveList as $move) {
                            $id = $move["id"];
                            $name = $move["name"];
                            echo "<option ";
                            if ($id == $teamMember["move_3"]) {
                                echo "selected ";
                            }
                            echo "value='$id'>$name</option>";
                        }
                        ?>
                    </select><br>
                    <select name="move4">
                        <option></option>
                        <?php foreach($moveList as $move) {
                            $id = $move["id"];
                            $name = $move["name"];
                            echo "<option ";
                            if ($id == $teamMember["move_4"]) {
                                echo "selected ";
                            }
                            echo "value='$id'>$name</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <table>
                        <tr><th rowspan="2">IVs</th><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
                        <tr>
                            <td><input style="width: 40px;" type="number" name="hp_iv" min="0" max="31" value=<?php echo "'".$teamMember["hp_iv"]."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="atk_iv" min="0" max="31" value=<?php echo "'".$teamMember["atk_iv"]."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="def_iv" min="0" max="31" value=<?php echo "'".$teamMember["def_iv"]."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="spa_iv" min="0" max="31" value=<?php echo "'".$teamMember["spa_iv"]."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="spd_iv" min="0" max="31" value=<?php echo "'".$teamMember["spd_iv"]."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="spe_iv" min="0" max="31" value=<?php echo "'".$teamMember["spe_iv"]."'";?>></td>
                        </tr>
                    </table><br>
                    <table>
                        <tr><th rowspan="2">EVs</th><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
                        <tr>
                            <td><input style="width: 40px;" type="number" name="hp_ev" min="0" max="510" value=<?php echo "'".$teamMember["hp_ev"]."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="atk_ev" min="0" max="510" value=<?php echo "'".$teamMember["atk_ev"]."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="def_ev" min="0" max="510" value=<?php echo "'".$teamMember["def_ev"]."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="spa_ev" min="0" max="510" value=<?php echo "'".$teamMember["spa_ev"]."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="spd_ev" min="0" max="510" value=<?php echo "'".$teamMember["spd_ev"]."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="spe_ev" min="0" max="510" value=<?php echo "'".$teamMember["spe_ev"]."'";?>></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="7">
                    <button type="submit">Save</button>
                </td>
            </tr>
        </table>
    </form>
    <?php include "../footer.php"; ?>
</body>
</html>
