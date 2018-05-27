<!DOCTYPE html>
<html>
<head>
    <title>Bill's PC</title>
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
    
    // Get pokemon and members
    $stmt = $db->prepare('SELECT id, name, gen FROM pokemon ORDER BY id;');
    $stmt->execute();
    $pokemonList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $longStatement = "SELECT pokemon, nickname, level, ability, nature, held_item, move_1, move_2, move_3, move_4, hp_iv, atk_iv, def_iv, spa_iv, spd_iv, spe_iv, hp_ev, atk_ev, def_ev, spa_ev, spd_ev, spe_ev FROM team_members_view WHERE trainer_name=(SELECT trainer_name FROM users WHERE id=:tid)";
    $stmt = $db->prepare($longStatement);
    $stmt->bindValue("tid", 1, PDO::PARAM_INT);
    $stmt->execute();
    $teamMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <h1>Bill's PC</h1>
    <table>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Ability<br>Nature<br>Held Item</th>
            <th>Moves</th>
            <th>IVs</th>
            <th>EVs</th>
        </tr>
        <tr>
            <td></td>
            <td colspan="5">
                <form action='newpokemon.php' method="POST">
                    <input type="hidden" name="teamId" value="$_POST['teamId']">
                    <input type="hidden" name="slot" value="$_POST['slot']">
                    <select name="pokemon">
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
                    <button type="submit">Add new Pokemon</button>
                </form>
            </td>
        </tr>
        <?php
        foreach($teamMembers as $member) {
            $name = $member["pokemon"];
            $nickname = $member["nickname"];
            $level = $member["level"];
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
            echo "<tr><td><img class='center' src='/assets/img/sprites/pkmn/". strtolower($name) .".gif'/></td>";
            echo "<td>$name";
            if (!is_null($nickname)) echo "<br>\"$nickname\"</td>";
            else echo "</td>";
            echo "<td style='line-height: 150%;'>$ability<br>$nature<br>$held_item</td>";
            echo "<td>";
            foreach ($moves as $move) echo "$move<br>";
            echo "</td>";
            echo "<td><table><tr><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
            <tr><td>$hp_iv</td><td>$atk_iv</td><td>$def_iv</td><td>$spa_iv</td><td>$spd_iv</td><td>$spe_iv</td></tr></table></td>";
            echo "<td><table><tr><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
            <tr><td>$hp_ev</td><td>$atk_ev</td><td>$def_ev</td><td>$spa_ev</td><td>$spd_ev</td><td>$spe_ev</td></tr></table></td>";
        }
        ?>
    </table>
    
    <?php include "../footer.php"; ?>
</body>
</html>
