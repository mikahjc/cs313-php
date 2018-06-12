<?php
if ((!isset($_POST["pokemon"]) || $_POST["pokemon"] == "") && ((!isset($_POST["id"]) || $_POST["id"] == ""))) {
    header("Location: /teambuilder/billspc.php");
    die();
    }
if (isset($_POST["edit"])) {
$editMode = true;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>
    <?php
    if ($editMode) {
      echo "Edit Pokemon";
    } else {
      echo "Add New Pokemon";
    }
    ?></title>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/teambuilder.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/assets/script/pokemonedit.js"></script>
</head>
<body onload='lockMoveSlots()'>
    <div class='centered'>
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

    $statsStrings = array(
        "hp" => "HP",
        "atk" => "Attack",
        "def" => "Defense",
        "spa" => "Special Attack",
        "spd" => "Special Defense",
        "spe" => "Speed",
    );
    
    if ($editMode) { 
    $longStatement = "SELECT id, pokemon, nickname, level, ability, nature, held_item, move_1, move_2, move_3, move_4, hp_iv, atk_iv, def_iv, spa_iv, spd_iv, spe_iv, hp_ev, atk_ev, def_ev, spa_ev, spd_ev, spe_ev FROM team_members WHERE owner=:tid AND id=:pid;";
    $stmt = $db->prepare($longStatement);
    $stmt->bindValue("tid", $_SESSION["userId"], PDO::PARAM_INT);
    $stmt->bindValue("pid", $_POST["id"], PDO::PARAM_INT);
    $stmt->execute();
    $teamMember = $stmt->fetch(PDO::FETCH_ASSOC);
    $_POST["pokemon"] = $teamMember["pokemon"];
    } else {
      $teamMember = null;
    }

    // Get types
    $stmt = $db->prepare('SELECT p.name, t1.name as type1, t2.name as type2 FROM pokemon p LEFT JOIN types t1 ON p.type1 = t1.id LEFT JOIN types t2 ON p.type2 = t2.id WHERE p.id=:pokemonId;');
    $stmt->bindValue("pokemonId", $_POST["pokemon"], PDO::PARAM_INT);
    $stmt->execute();
    $name = $stmt->fetch(PDO::FETCH_ASSOC);    

    $previous = $_POST["pokemon"];
    $i = 0;
    $evolutionIds = null;
    while(!is_null($previous)){
      $stmt = $db->prepare('SELECT previous FROM pokemon WHERE id=:id');
      $stmt->bindValue("id", $previous, PDO::PARAM_INT);
      $stmt->execute();
      $previous = $stmt->fetch(PDO::FETCH_ASSOC)["previous"];
      if (!is_null($previous))
        $evolutionIds[$i] = (int)$previous;
      $i++;
    }

    $stmt = $db->prepare('SELECT ability, name, hidden, abilities.description FROM allowed_abilities join abilities on allowed_abilities.ability = abilities.id WHERE pokemon=:pokemonId;');
    $stmt->bindValue("pokemonId", $_POST["pokemon"], PDO::PARAM_INT);
    $stmt->execute();
    $abilityList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('SELECT id, name, boosted, weakened FROM natures;');
    $stmt->execute();
    $natureList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $db->prepare('SELECT id, name, description FROM items;');
    $stmt->execute();
    $itemList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $longStatement = 'SELECT DISTINCT move AS id, moves.name, t.name as type, category, power, accuracy, pp, description, how_learned, learned_at FROM allowed_moves JOIN moves ON allowed_moves.move = moves.id LEFT JOIN types t ON moves.type = t.id WHERE pokemon=:pokemonId';    
    foreach($evolutionIds as $id) {
      $longStatement .= " OR pokemon=$id";
    }
    $longStatement .= " ORDER BY name;";
    $stmt = $db->prepare($longStatement);
    $stmt->bindValue("pokemonId", $_POST["pokemon"], PDO::PARAM_INT);
    $stmt->execute();
    $moveList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h1><?php if($editMode){ echo "Edit Pokemon";} else{ echo "New Pokemon";}?></h1>
    <form name="pokemon" action="billspc.php" onsubmit='return validateForm()' method='POST'>
        <input type="hidden" name="<?php if($editMode) echo "editPokemon"; else echo "newPokemon";?>" value="<?php if($editMode) echo $teamMember[id]; else echo $_POST["pokemon"];?>">
        <table>
            <tr class="rounded">
                <td rowspan='2'><img src=<?php echo "'/assets/img/sprites/pkmn/". strtolower($name["name"]) .".gif'";?>/></td>
                <th>Name</th>
                <th>Type</th>
                <th>Level</th>
                <th>Ability</th>
                <th>Nature</th>
                <th>Held Item</th>
                <th>Moves</th>
            </tr>
            <tr class="rounded">
                <td><span id="name" class="name"><?=$name["name"]?></span><br><br><input type="text" name="nickname" placeholder="Nickname" value=<?php echo "'".$teamMember["nickname"]."'";?>></td>
                <td><?php
                    echo $name["type1"];
                    if(!is_null($name["type2"])) {
                        echo "<br>".$name["type2"];
                    }
                ?>
                <td>
                    <input type="number" min="1" max="100" value=<?php echo "'".(is_null($teamMember["level"]) ? "50" : $teamMember["level"])."'";?> name="level">
                </td>
                <td>
                    <select name="ability" id="ability" onchange='updateAbility()'>
                        <option></option>
                        <?php $scriptAbilityDb = "<script> abilities = {};";
                        foreach($abilityList as $ability) {
                            $id = $ability["ability"];
                            $name = $ability["name"];
                            $hidden = $ability["hidden"];
                            $desc = htmlspecialchars($ability["description"], ENT_QUOTES);
                            $scriptAbilityDb .= "abilities[$id] = {'name':'$name','desc':'$desc'};";
                            echo "<option ";
                            if ($id == $teamMember["ability"]) {
                                echo "selected ";
                                $selectedAbility = array(
                                  "name" => $name,
                                  "desc" => $desc
                                );
                            }
                            if ($hidden) {
                                echo "style='background-color: lightgray;' value='$id' title='$desc'>$name (Hidden)</option>";
                            } else {
                                echo "value='$id' title='$desc'>$name</option>";
                            }
                        }
                        $scriptAbilityDb .= "</script>";
                        ?>
                    </select>
                </td>
                <td>
                    <select name="nature">
                        <option></option>
                        <?php foreach($natureList as $nature) {
                            $id = $nature["id"];
                            $name = $nature["name"];
                            $boosted = $statsStrings[$nature["boosted"]];
                            $weakened = $statsStrings[$nature["weakened"]];
                            echo "<option ";
                            if ($id == $teamMember["nature"]) {
                                echo "selected ";
                            }
                            echo "value='$id' title='";
                            if ($boosted == $weakened) {
                                echo "Neutral effect on stats";
                            } else {
                                echo "Boosts $boosted, weakens $weakened";
                            }
                            echo "'>$name</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select name="item" id="item" onchange='updateItem()'>
                        <option></option>
                        <?php $scriptItemDb = "<script> items = {};";
                        foreach($itemList as $item) {
                            $id = $item["id"];
                            $name = htmlspecialchars($item["name"], ENT_QUOTES);
                            $desc = htmlspecialchars($item["description"], ENT_QUOTES);
                            $scriptItemDb .= "items[$id] = {'name':'$name','desc':'$desc'};";
                            echo "<option ";
                            if ($id == null) {
                                $selectedItem = array(
                                  "name" => "None",
                                  "desc" => ""
                                );
                            }
                            if ($id == $teamMember["held_item"]) {
                                echo "selected ";
                                $selectedItem = array(
                                  "name" => $name,
                                  "desc" => $desc
                                );
                            }
                            echo "value='$id'>$name</option>";
                        }
                        $scriptItemDb .= "</script>";
                        ?>
                    </select>
                </td>
                <td>
                    <select name="move1" id="move1" onchange='updateMove(1)'>
                        <option></option>
                        <?php $move1 = null;
                        $scriptMovesDb = "<script>var moves = {};";
                        foreach($moveList as $move) {
                            $id = $move["id"];
                            $name = htmlspecialchars($move["name"], ENT_QUOTES);
                            $description = htmlspecialchars($move["description"], ENT_QUOTES);
                            $type = $move["type"];
                            $category = $move["category"];
                            $power = $move["power"];
                            $accuracy = $move["accuracy"];
                            $pp = $move["pp"];
                            $how = $move["how_learned"];
                            $level = $move["learned_at"];
                            $scriptMovesDb .= "moves[$id] = {'name':'$name','type':'$type','category':'$category','power':'$power','accuracy':'$accuracy','pp':'$pp','desc':'$description'};";
                            echo "<option ";
                            if ($id == $teamMember["move_1"]) {
                                echo "selected ";
                                $move1["id"] =$id;
                                $move1["name"] =$name;
                                $move1["type"] =$type;
                                $move1["category"] =$category;
                                $move1["power"] =$power;
                                $move1["accuracy"] =$accuracy;
                                $move1["pp"] =$pp;
                                $move1["description"] =$description;
                                $move1["how_learned"] =$how;
                                $move1["learned_at"] =$level;
                            }
                            echo "value='$id' title='$description'>$name ($how";
                            if (!is_null($level)) {
                                echo " $level";
                            }
                            echo ")</option>";
                        }
                        $scriptMovesDb .= "</script>";
                        ?>
                    </select><br>
                    <select name="move2" id="move2" onchange='updateMove(2)'>
                        <option></option>
                        <?php foreach($moveList as $move) {
                            $id = $move["id"];
                            $name = htmlspecialchars($move["name"], ENT_QUOTES);
                            $description = htmlspecialchars($move["description"], ENT_QUOTES);
                            $type = $move["type"];
                            $category = $move["category"];
                            $power = $move["power"];
                            $accuracy = $move["accuracy"];
                            $pp = $move["pp"];
                            $how = $move["how_learned"];
                            $level = $move["learned_at"];
                            echo "<option ";
                            if ($id == $teamMember["move_2"]) {
                                echo "selected ";
                                $move2["id"] =$id;
                                $move2["name"] =$name;
                                $move2["type"] =$type;
                                $move2["category"] =$category;
                                $move2["power"] =$power;
                                $move2["accuracy"] =$accuracy;
                                $move2["pp"] =$pp;
                                $move2["description"] =$description;
                                $move2["how_learned"] =$how;
                                $move2["learned_at"] =$level;
                            }
                            echo "value='$id'>$name ($how";
                            if (!is_null($level)) {
                                echo " $level";
                            }
                            echo ")</option>";
                        }
                        ?>
                    </select><br>
                    <select name="move3" id="move3" onchange='updateMove(3)'>
                        <option></option>
                        <?php foreach($moveList as $move) {
                            $id = $move["id"];
                            $name = htmlspecialchars($move["name"], ENT_QUOTES);
                            $description = htmlspecialchars($move["description"], ENT_QUOTES);
                            $type = $move["type"];
                            $category = $move["category"];
                            $power = $move["power"];
                            $accuracy = $move["accuracy"];
                            $pp = $move["pp"];
                            $how = $move["how_learned"];
                            $level = $move["learned_at"];
                            echo "<option ";
                            if ($id == $teamMember["move_3"]) {
                                echo "selected ";
                                $move3["id"] =$id;
                                $move3["name"] =$name;
                                $move3["type"] =$type;
                                $move3["category"] =$category;
                                $move3["power"] =$power;
                                $move3["accuracy"] =$accuracy;
                                $move3["pp"] =$pp;
                                $move3["description"] =$description;
                                $move3["how_learned"] =$how;
                                $move3["learned_at"] =$level;
                            }
                            echo "value='$id'>$name ($how";
                            if (!is_null($level)) {
                                echo " $level";
                            }
                            echo ")</option>";
                        }
                        ?>
                    </select><br>
                    <select name="move4" id="move4" onchange='updateMove(4)'>
                        <option></option>
                        <?php foreach($moveList as $move) {
                            $id = $move["id"];
                            $name = htmlspecialchars($move["name"], ENT_QUOTES);
                            $description = htmlspecialchars($move["description"], ENT_QUOTES);
                            $type = $move["type"];
                            $category = $move["category"];
                            $power = $move["power"];
                            $accuracy = $move["accuracy"];
                            $pp = $move["pp"];
                            $how = $move["how_learned"];
                            $level = $move["learned_at"];
                            echo "<option ";
                            if ($id == $teamMember["move_4"]) {
                                echo "selected ";
                                $move4["id"] =$id;
                                $move4["name"] =$name;
                                $move4["type"] =$type;
                                $move4["category"] =$category;
                                $move4["power"] =$power;
                                $move4["accuracy"] =$accuracy;
                                $move4["pp"] =$pp;
                                $move4["description"] =$description;
                                $move4["how_learned"] =$how;
                                $move4["learned_at"] =$level;
                            }
                            echo "value='$id'>$name ($how";
                            if (!is_null($level)) {
                                echo " $level";
                            }
                            echo ")</option>";
                        }
                        ?>
                    </select>
                </td></tr>
                <tr><td colspan='4' style='text-align: center'>
                    <table class="values" style="margin: auto;">
                        <tr><th rowspan="2">IVs</th><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
                        <tr>
                            <td><input style="width: 40px;" type="number" name="hp_iv" min="0" max="31" value=<?php echo "'".(is_null($teamMember["hp_iv"]) ? 0 : $teamMember["hp_iv"])."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="atk_iv" min="0" max="31" value=<?php echo "'".(is_null($teamMember["atk_iv"]) ? 0 : $teamMember["atk_iv"])."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="def_iv" min="0" max="31" value=<?php echo "'".(is_null($teamMember["def_iv"]) ? 0 : $teamMember["def_iv"])."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="spa_iv" min="0" max="31" value=<?php echo "'".(is_null($teamMember["spa_iv"]) ? 0 : $teamMember["spa_iv"])."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="spd_iv" min="0" max="31" value=<?php echo "'".(is_null($teamMember["spd_iv"]) ? 0 : $teamMember["spd_iv"])."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="spe_iv" min="0" max="31" value=<?php echo "'".(is_null($teamMember["spe_iv"]) ? 0 : $teamMember["spe_iv"])."'";?>></td>
                        </tr>
                    </table><br>
                    <table class="values" style="margin: auto;">
                        <tr><th rowspan="2">EVs</th><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
                        <tr>
                            <td><input style="width: 40px;" type="number" name="hp_ev" min="0" max="510" value=<?php echo "'".(is_null($teamMember["hp_ev"]) ? 0 : $teamMember["hp_ev"])."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="atk_ev" min="0" max="510" value=<?php echo "'".(is_null($teamMember["atk_ev"]) ? 0 : $teamMember["atk_ev"])."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="def_ev" min="0" max="510" value=<?php echo "'".(is_null($teamMember["def_ev"]) ? 0 : $teamMember["def_ev"])."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="spa_ev" min="0" max="510" value=<?php echo "'".(is_null($teamMember["spa_ev"]) ? 0 : $teamMember["spa_ev"])."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="spd_ev" min="0" max="510" value=<?php echo "'".(is_null($teamMember["spd_ev"]) ? 0 : $teamMember["spd_ev"])."'";?>></td>
                            <td><input style="width: 40px;" type="number" name="spe_ev" min="0" max="510" value=<?php echo "'".(is_null($teamMember["spe_ev"]) ? 0 : $teamMember["spe_ev"])."'";?>></td>
                        </tr>
                    </table>
                </td>
                    <td colspan='4'>
                        <table class="values">
                            <tr>
                                <th>Ability</th>
                                <th colspan='6'>Description</th>
                            </tr>
                            <tr>
                                <td id='abilityname'><?=$selectedAbility["name"]?></td>
                                <td id='abilitydesc' colspan='6'><?=$selectedAbility["desc"]?></td>
                            </tr>
                            <tr>
                                <th>Item</th>
                                <th colspan='6'>Description</th>
                            </tr>
                            <tr>
                                <td id='itemname'><?=$selectedItem["name"]?></td>
                                <td id='itemdesc' colspan='6'><?=$selectedItem["desc"]?></td>
                            </tr>
                            <tr>
                                <th>Move</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Power</th>
                                <th>Accuracy</th>
                                <th>PP</th>
                            </tr>
                            <tr>
                                <td id='m1name'><?=$move1["name"]?></td>
                                <td id='m1type'><?=$move1["type"]?></td>
                                <td id='m1category'><?=$move1["category"]?></td>
                                <td id='m1desc'><?=$move1["description"]?></td>
                                <td id='m1power'><?=$move1["power"]?></td>
                                <td id='m1accuracy'><?=$move1["accuracy"]?></td>
                                <td id='m1pp'><?=$move1["pp"]?></td>
                            </tr>
                            <tr>
                                <td id='m2name'><?=$move2["name"]?></td>
                                <td id='m2type'><?=$move2["type"]?></td>
                                <td id='m2category'><?=$move2["category"]?></td>
                                <td id='m2desc'><?=$move2["description"]?></td>
                                <td id='m2power'><?=$move2["power"]?></td>
                                <td id='m2accuracy'><?=$move2["accuracy"]?></td>
                                <td id='m2pp'><?=$move2["pp"]?></td>
                            </tr>
                            <tr>
                                <td id='m3name'><?=$move3["name"]?></td>
                                <td id='m3type'><?=$move3["type"]?></td>
                                <td id='m3category'><?=$move3["category"]?></td>
                                <td id='m3desc'><?=$move3["description"]?></td>
                                <td id='m3power'><?=$move3["power"]?></td>
                                <td id='m3accuracy'><?=$move3["accuracy"]?></td>
                                <td id='m3pp'><?=$move3["pp"]?></td>
                            </tr>
                            <tr>
                                <td id='m4name'><?=$move4["name"]?></td>
                                <td id='m4type'><?=$move4["type"]?></td>
                                <td id='m4category'><?=$move4["category"]?></td>
                                <td id='m4desc'><?=$move4["description"]?></td>
                                <td id='m4power'><?=$move4["power"]?></td>
                                <td id='m4accuracy'><?=$move4["accuracy"]?></td>
                                <td id='m4pp'><?=$move4["pp"]?></td>
                            </tr>
                        </table>
                    </td>
            </tr>
            <tr>
                <td colspan="4">
                    <button style="background-color: crimson; width: 100%" onclick='window.history.back();'>Cancel</button>
                </td>
                <td colspan="4">
                    <button type="submit" style="width: 100%">Save</button>
                </td>
            </tr>
        </table>
    </form>
    </div>
    <?php include "../footer.php"; echo $scriptMovesDb.$scriptAbilityDb.$scriptItemDb;?>
</body>
</html>
