<!DOCTYPE html>
<html>
<head>
    <title>Teams</title>
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
    $stmt = $db->prepare('SELECT id, team_name FROM teams WHERE owner=1;');
    $stmt->execute();
    $teamList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (isset($_POST["team"])) { 
        $teamId = $_POST["team"];
        if ($teamId != "") {
            $longStatement =  "SELECT team_name, member_1, member_2, member_3, member_4, member_5, member_6\n";
            $longStatement .= "FROM teams\n";
            $longStatement .= "WHERE owner=1 AND id=:teamId;";
            $stmt = $db->prepare($longStatement);
            $stmt->bindValue("teamId", $teamId, PDO::PARAM_INT);
            $stmt->execute();
            $teamData = $stmt->fetch(PDO::FETCH_ASSOC);
            for ($i = 1; $i <= 6; $i++) {
            	if (is_numeric($teamData["member_$i"])) {
            		$longStatement = "SELECT pokemon, nickname, level, ability, nature, held_item, move_1, move_2, move_3, move_4, hp_iv, atk_iv, def_iv, spa_iv, spd_iv, spe_iv, hp_ev, atk_ev, def_ev, spa_ev, spd_ev, spe_ev FROM team_members_view WHERE trainer_name=(SELECT trainer_name FROM users WHERE id=1) AND id=:memberId;";
            		$stmt = $db->prepare($longStatement);
            		$stmt->bindValue("memberId", intval($teamData["member_$i"]), PDO::PARAM_INT);
            		$stmt->execute();
            		$members["member_$i"] = $stmt->fetch(PDO::FETCH_ASSOC);
            	}
            }
        }
    }
    ?>
    <h1>Teams</h1>
    <h2>Select Team:</h2>
    <form action="teams.php" method='POST'>
    Team: <select name="team">
        <option></option>
        <?php foreach($teamList as $row) {
            $id = $row["id"];
            $name = $row["team_name"];
            echo "<option value='$id'>$name</option>\n";
        }?>
    </select>
    <button type='submit'>Load team</button>
	</form><br>
	<form action="teams.php" method="POST">
		Name: <input type='text' name='newTeam'>
		<button type='submit'>Create team</button>
	</form>
<?php
if (isset($members)) { 
	echo "<h1>".$teamData["team_name"]."</h1>
	<table>";
	$imageRow = "<tr>";
	$nameRow = "<tr>";
	$dataRow = "<tr>";
	$endOfTeam = false;
	for ($i = 1; $i <= 6; $i++) {
		if (isset($members["member_$i"])) {
			$member = $members["member_$i"];
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

			// Image and name
			$imageRow .= "<td><img src='/assets/img/sprites/pkmn/". strtolower($name) .".gif'/></td>";
			$nameRow .= "<td><h3>$name";
			if (!is_null($level)) $nameRow .= " ($level)</h3>";
			else $nameRow .= "</h3>";
			if (!is_null($nickname)) $nameRow .= "<h4>\"$nickname\"</h4>";
			$nameRow .= "</td>";
			$dataRow .= "<td>Ability: $ability<br>Nature: $nature<br>";
			if (!is_null($held_item)) $dataRow .= "Item: $held_item<br>";
			$dataRow .= "Moves:<br>";
			foreach ($moves as $move) $dataRow .= "\t$move<br>";
			$dataRow .= "IVs:<table><tr><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
			                 <tr><td>$hp_iv</td><td>$atk_iv</td><td>$def_iv</td><td>$spa_iv</td><td>$spd_iv</td><td>$spe_iv</td></tr></table><br>";
			$dataRow .= "EVs:<table><tr><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
			                 <tr><td>$hp_ev</td><td>$atk_ev</td><td>$def_ev</td><td>$spa_ev</td><td>$spd_ev</td><td>$spe_ev</td></tr></table><br>";
			$dataRow .= "</td>";
		} else if ($endOfTeam == false) {
			$imageRow .= "<td><form action='billspc.php' method='POST'>";
			$imageRow .= "<input type='hidden' name='teamId' value='$teamId'><input type='hidden' name='slot' value='$i'>";
			$imageRow .= "<button type='submit'>Add</button></form></td>";
			break;
		}
	}
	echo $imageRow.$nameRow.$dataRow."</table>";
}
?>
    <?php include "../footer.php"; ?>
</body>
</html>
