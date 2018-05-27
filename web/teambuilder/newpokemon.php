<?php
if (!isset($_POST["pokemon"])) {
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
    $stmt = $db->prepare('SELECT name FROM pokemon WHERE id=:pokemonId;');
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
    <form action="billspc.php" type='POST'>
        <table>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Ability</th>
                <th>Nature</th>
                <th>Held Item</th>
                <th>Moves</th>
                <th></th>
            </tr>
            <tr>
                <td><img src=<?php echo "'/assets/img/sprites/pkmn/". strtolower($name["name"]) .".gif'";?>/></td>
                <td><?=$name["name"]?></td>
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
                </td>
                <td>
                    <table>
                        <tr><th rowspan="2">IVs</th><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
                        <tr>
                            <td><input style="width: 40px;" type="number" name="hp_iv" min="0" max="31"></td>
                            <td><input style="width: 40px;" type="number" name="atk_iv" min="0" max="31"></td>
                            <td><input style="width: 40px;" type="number" name="def_iv" min="0" max="31"></td>
                            <td><input style="width: 40px;" type="number" name="spa_iv" min="0" max="31"></td>
                            <td><input style="width: 40px;" type="number" name="spd_iv" min="0" max="31"></td>
                            <td><input style="width: 40px;" type="number" name="spe_iv" min="0" max="31"></td>
                        </tr>
                    </table><br>
                    <table>
                        <tr><th rowspan="2">EVs</th><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
                        <tr>
                            <td><input style="width: 40px;" type="number" name="hp_ev" min="0" max="510"></td>
                            <td><input style="width: 40px;" type="number" name="atk_ev" min="0" max="510"></td>
                            <td><input style="width: 40px;" type="number" name="def_ev" min="0" max="510"></td>
                            <td><input style="width: 40px;" type="number" name="spa_ev" min="0" max="510"></td>
                            <td><input style="width: 40px;" type="number" name="spd_ev" min="0" max="510"></td>
                            <td><input style="width: 40px;" type="number" name="spe_ev" min="0" max="510"></td>
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
