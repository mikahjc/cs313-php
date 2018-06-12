<!DOCTYPE html>
<html>
<head>
    <title>Bill's PC</title>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/teambuilder.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/assets/script/billspc.js"></script>
</head>
<body>
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

    if (isset($_SESSION["releaseError"])) {
        $error = $_SESSION["releaseError"];
        if (strpos($error, 'violates foreign key constraint') !== false) {
            $whichTeamQuery = "SELECT team_name FROM teams WHERE member_1=:id OR member_2=:id OR member_3=:id OR member_4=:id OR member_5=:id OR member_6=:id";
            $stmt = $db->prepare($whichTeamQuery);
            $stmt->bindValue("id", $_SESSION["releaseErrorId"], PDO::PARAM_INT);
            $stmt->execute();
            echo "<script>alert('The Pokemon you tried to release is still on a team:\\n";
            foreach($stmt->fetch(PDO::FETCH_ASSOC) as $name) {
                echo $name."\\n";
            }
            echo "')</script>";
        }
        unset($_SESSION["releaseError"]);
    }
    
    $addingToTeam = false;

    if (isset($_POST["teamId"]) && isset($_POST["slot"])) {
        $_SESSION["teamId"] = $_POST["teamId"] ;
        $_SESSION["slot"] = $_POST["slot"];
        $addingToTeam = true;
    } else if (isset($_SESSION["teamId"]) && isset($_SESSION["slot"])) {
        $addingToTeam = true;
    }

    if (isset($_POST["newPokemon"])) {
        $pokemon = $_POST["newPokemon"];
        $level = $_POST["level"];
        $nickname = filter_var($_POST["nickname"], FILTER_SANITIZE_STRING);
        if ($nickname == "") $nickname = NULL;
        $ability = $_POST["ability"];
        $nature = $_POST["nature"];
        $heldItem = $_POST["item"];
        if ($heldItem == "") $heldItem = NULL;
        $moves[0] = $_POST["move1"];
        $moves[1] = $_POST["move2"];
        $moves[2] = $_POST["move3"];
        $moves[3] = $_POST["move4"];
        if ($moves[1] == "") $moves[1] = NULL;
        if ($moves[2] == "") $moves[2] = NULL;
        if ($moves[3] == "") $moves[3] = NULL;
        $hp_iv = $_POST["hp_iv"];
        $atk_iv = $_POST["atk_iv"];
        $def_iv = $_POST["def_iv"];
        $spa_iv = $_POST["spa_iv"];
        $spd_iv = $_POST["spd_iv"];
        $spe_iv = $_POST["spe_iv"];
        $hp_ev = $_POST["hp_ev"];
        $atk_ev = $_POST["atk_ev"];
        $def_ev = $_POST["def_ev"];
        $spa_ev = $_POST["spa_ev"];
        $spd_ev = $_POST["spd_ev"];
        $spe_ev = $_POST["spe_ev"];
        $insertStatement = "INSERT INTO team_members(pokemon,nickname,level,owner,ability,nature,held_item,move_1,move_2,move_3,move_4,hp_iv,atk_iv,def_iv,spa_iv,spd_iv,spe_iv,hp_ev,atk_ev,def_ev,spa_ev,spd_ev,spe_ev)
                            VALUES (:pokemon,:nickname,:level,:owner,:ability,:nature,:held_item,:move_1,:move_2,:move_3,:move_4,:hp_iv,:atk_iv,:def_iv,:spa_iv,:spd_iv,:spe_iv,:hp_ev,:atk_ev,:def_ev,:spa_ev,:spd_ev,:spe_ev);";
        $stmt = $db->prepare($insertStatement);
        $stmt->bindValue("pokemon", $pokemon, PDO::PARAM_INT);
        $stmt->bindValue("nickname", $nickname, PDO::PARAM_STR);
        $stmt->bindValue("level", $level, PDO::PARAM_INT);
        $stmt->bindValue("owner", $_SESSION["userId"], PDO::PARAM_INT);
        $stmt->bindValue("ability", $ability, PDO::PARAM_INT);
        $stmt->bindValue("nature", $nature, PDO::PARAM_INT);
        $stmt->bindValue("held_item", $heldItem, PDO::PARAM_INT);
        $stmt->bindValue("move_1", $moves[0], PDO::PARAM_INT);
        $stmt->bindValue("move_2", $moves[1], PDO::PARAM_INT);
        $stmt->bindValue("move_3", $moves[2], PDO::PARAM_INT);
        $stmt->bindValue("move_4", $moves[3], PDO::PARAM_INT);
        $stmt->bindValue("hp_iv", $hp_iv, PDO::PARAM_INT);
        $stmt->bindValue("atk_iv", $atk_iv, PDO::PARAM_INT);
        $stmt->bindValue("def_iv", $def_iv, PDO::PARAM_INT);
        $stmt->bindValue("spa_iv", $spa_iv, PDO::PARAM_INT);
        $stmt->bindValue("spd_iv", $spd_iv, PDO::PARAM_INT);
        $stmt->bindValue("spe_iv", $spe_iv, PDO::PARAM_INT);
        $stmt->bindValue("hp_ev", $hp_ev, PDO::PARAM_INT);
        $stmt->bindValue("atk_ev", $atk_ev, PDO::PARAM_INT);
        $stmt->bindValue("def_ev", $def_ev, PDO::PARAM_INT);
        $stmt->bindValue("spa_ev", $spa_ev, PDO::PARAM_INT);
        $stmt->bindValue("spd_ev", $spd_ev, PDO::PARAM_INT);
        $stmt->bindValue("spe_ev", $spe_ev, PDO::PARAM_INT);
        $stmt->execute();
    }

    if (isset($_POST["editPokemon"])) {
        $id = $_POST["editPokemon"];
        $level = $_POST["level"];
        $nickname = filter_var($_POST["nickname"], FILTER_SANITIZE_STRING);
        if ($nickname == "") $nickname = NULL;
        $ability = $_POST["ability"];
        $nature = $_POST["nature"];
        $heldItem = $_POST["item"];
        if ($heldItem == "") $heldItem = NULL;
        $moves[0] = $_POST["move1"];
        $moves[1] = $_POST["move2"];
        $moves[2] = $_POST["move3"];
        $moves[3] = $_POST["move4"];
        if ($moves[1] == "") $moves[1] = NULL;
        if ($moves[2] == "") $moves[2] = NULL;
        if ($moves[3] == "") $moves[3] = NULL;
        $hp_iv = $_POST["hp_iv"];
        $atk_iv = $_POST["atk_iv"];
        $def_iv = $_POST["def_iv"];
        $spa_iv = $_POST["spa_iv"];
        $spd_iv = $_POST["spd_iv"];
        $spe_iv = $_POST["spe_iv"];
        $hp_ev = $_POST["hp_ev"];
        $atk_ev = $_POST["atk_ev"];
        $def_ev = $_POST["def_ev"];
        $spa_ev = $_POST["spa_ev"];
        $spd_ev = $_POST["spd_ev"];
        $spe_ev = $_POST["spe_ev"];
        $insertStatement = "UPDATE team_members
                            SET nickname = :nickname,level=:level,ability=:ability,nature=:nature,held_item=:held_item,move_1=:move_1,move_2=:move_2,move_3=:move_3,move_4=:move_4,hp_iv=:hp_iv,atk_iv=:atk_iv,def_iv=:def_iv,spa_iv=:spa_iv,spd_iv=:spd_iv,spe_iv=:spe_iv,hp_ev=:hp_ev,atk_ev=:atk_ev,def_ev=:def_ev,spa_ev=:spa_ev,spd_ev=:spd_ev,spe_ev=:spe_ev
                            WHERE owner=:owner AND id=:pid;";
        $stmt = $db->prepare($insertStatement);
        $stmt->bindValue("pid", $id, PDO::PARAM_INT);
        $stmt->bindValue("nickname", $nickname, PDO::PARAM_STR);
        $stmt->bindValue("level", $level, PDO::PARAM_INT);
        $stmt->bindValue("owner", $_SESSION["userId"], PDO::PARAM_INT);
        $stmt->bindValue("ability", $ability, PDO::PARAM_INT);
        $stmt->bindValue("nature", $nature, PDO::PARAM_INT);
        $stmt->bindValue("held_item", $heldItem, PDO::PARAM_INT);
        $stmt->bindValue("move_1", $moves[0], PDO::PARAM_INT);
        $stmt->bindValue("move_2", $moves[1], PDO::PARAM_INT);
        $stmt->bindValue("move_3", $moves[2], PDO::PARAM_INT);
        $stmt->bindValue("move_4", $moves[3], PDO::PARAM_INT);
        $stmt->bindValue("hp_iv", $hp_iv, PDO::PARAM_INT);
        $stmt->bindValue("atk_iv", $atk_iv, PDO::PARAM_INT);
        $stmt->bindValue("def_iv", $def_iv, PDO::PARAM_INT);
        $stmt->bindValue("spa_iv", $spa_iv, PDO::PARAM_INT);
        $stmt->bindValue("spd_iv", $spd_iv, PDO::PARAM_INT);
        $stmt->bindValue("spe_iv", $spe_iv, PDO::PARAM_INT);
        $stmt->bindValue("hp_ev", $hp_ev, PDO::PARAM_INT);
        $stmt->bindValue("atk_ev", $atk_ev, PDO::PARAM_INT);
        $stmt->bindValue("def_ev", $def_ev, PDO::PARAM_INT);
        $stmt->bindValue("spa_ev", $spa_ev, PDO::PARAM_INT);
        $stmt->bindValue("spd_ev", $spd_ev, PDO::PARAM_INT);
        $stmt->bindValue("spe_ev", $spe_ev, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Get pokemon and members
    $longStatement = "SELECT p.id, p.name, t1.name as type1, t2.name as type2, gen
                      FROM pokemon p
                      LEFT JOIN types t1 ON p.type1 = t1.id
                      LEFT JOIN types t2 ON p.type2 = t2.id
                      ORDER BY p.id;";
    $stmt = $db->prepare($longStatement);
    $stmt->execute();
    $pokemonList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $longStatement = "SELECT tmv.id,pokemon,t1.name as type1,t2.name as type2,nickname,level,ability,nature,held_item,move_1,move_2,move_3,move_4,hp_iv,atk_iv,def_iv,spa_iv,spd_iv,spe_iv,hp_ev,atk_ev,def_ev,spa_ev,spd_ev,spe_ev
                      FROM team_members_view tmv
                      LEFT JOIN pokemon p ON tmv.pokemon = p.name
                      LEFT JOIN types t1 ON p.type1 = t1.id
                      LEFT JOIN types t2 ON p.type2 = t2.id
                      WHERE trainer_name=(SELECT trainer_name FROM users WHERE id=:tid)
                      ORDER BY id";
    $stmt = $db->prepare($longStatement);
    $stmt->bindValue("tid", $_SESSION["userId"], PDO::PARAM_INT);
    $stmt->execute();
    $teamMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <h1>Bill's PC</h1>
    <table id="pcbox">
        <tr>
            <th></th>
            <th>Name</th>
            <th>Type</th>
            <th>Ability<br>Nature<br>Held Item</th>
            <th>Moves</th>
            <th>IVs</th>
            <th>EVs</th>
            <th></th>
        </tr>
        <tr>
            <td></td>
            <td id="addrow" colspan="6">
                <form action='newpokemon.php' method="POST">
                    <select name="pokemon">
                        <option></option>
                        <?php $currentGen = 0; 
                            foreach($pokemonList as $row) {
                            $id = $row["id"];
                            $name = $row["name"];
                            $gen = $row["gen"];
                            $type1 = $row["type1"];
                            $type2 = $row["type2"];
                            if($currentGen != $gen && $gen != -1) {
                                echo "<option class='gray' val='gen'>Generation $gen</option>";
                                $currentGen = $gen;
                            }
                            if(!is_null($type2)) {
                                $typeString = $type1."/".$type2;
                            } else {
                                $typeString = $type1;
                            }
                            if (strpos($name, '-Mega') !== false) {
                                continue;
                            }
                            if ($gen > 0) {
                                echo "<option value='$id'>$name ($typeString)</option>\n";
                            } else if ($gen == -1) {
                                echo "<option value='$id'>$name ($typeString)</option>\n";
                            }
                        }?>
                    </select>
                    <button type="submit">Add new Pokemon</button>
                </form>
            </td>
        </tr>
        <?php
        if ($addingToTeam) {
            echo "<form action='teams.php' method='POST'>";
        }
        foreach($teamMembers as $member) {
            $id = $member["id"];
            $name = $member["pokemon"];
            $nickname = $member["nickname"];
            $level = $member["level"];
            $type1 = $member["type1"];
            $type2 = $member["type2"];
            $ability = $member["ability"];
            $nature = $member["nature"];
            $held_item = $member["held_item"];
            $moves[0] = $member["move_1"];
            $moves[1] = $member["move_2"];
            $moves[2] = $member["move_3"];
            $moves[3] = $member["move_4"];
            $hp_iv = $member["hp_iv"];
            $atk_iv = $member["atk_iv"];
            $def_iv = $member["def_iv"];
            $spa_iv = $member["spa_iv"];
            $spd_iv = $member["spd_iv"];
            $spe_iv = $member["spe_iv"];
            $hp_ev = $member["hp_ev"];
            $atk_ev = $member["atk_ev"];
            $def_ev = $member["def_ev"];
            $spa_ev = $member["spa_ev"];
            $spd_ev = $member["spd_ev"];
            $spe_ev = $member["spe_ev"];
            echo "<tr><td class='pkmn'><img class='center' src='/assets/img/sprites/pkmn/". strtolower($name) .".gif'/></td>";
            echo "<td class='pkmn'><span class='name'>$name</span>";
            $displayName = $name;
            if (!is_null($nickname)) {
                echo "<br>\"$nickname\"</td>";
                $displayName = $nickname;
            } 
            else echo "</td>";
            echo "<td class='pkmn'>$type1";
            if (!is_null($type2)) {
                echo "<br>$type2";
            }
            echo "</td><td class='pkmn' style='line-height: 200%'>$ability<br>$nature<br>$held_item</td>";
            echo "<td class='pkmn'>";
            foreach ($moves as $move) {
                if ($move != "") echo "$move<br>";
            }
            echo "</td>";
            echo "<td class='pkmn'><table class='values'><tr><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
            <tr><td>$hp_iv</td><td>$atk_iv</td><td>$def_iv</td><td>$spa_iv</td><td>$spd_iv</td><td>$spe_iv</td></tr></table></td>";
            echo "<td class='pkmn'><table class='values'><tr><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
            <tr><td>$hp_ev</td><td>$atk_ev</td><td>$def_ev</td><td>$spa_ev</td><td>$spd_ev</td><td>$spe_ev</td></tr></table></td><td class='pkmn'>";
            if ($addingToTeam) {
                echo "<button type='submit' name='teamMemberId' value='$id'>Add</button><br>"; 
            }
            $releaseFunction = '"releasePokemon(\''.$displayName.'\','.$id.')"';
            $editFunction = '"editPokemon('.$id.')"';
            echo "<button onclick=$editFunction>Modify</button>
                  <button onclick=$releaseFunction>Release</button></td>";
        }
        if ($addingToTeam) {
            echo "</form>";
        }
        ?>
    </table>
    <form id="editForm" action="newpokemon.php" method='POST'>
        <input type="hidden" name="edit" value="true">
        <input id="editValue" type="hidden" name="id" value="">
    </form>
    <form id="releaseForm" action="releasepokemon.php" method="POST">
        <input id="releaseValue" type="hidden" name="id" value="">
    </form>
    </div>
    <?php include "../footer.php"; ?>
</body>
</html>
