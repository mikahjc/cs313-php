<!DOCTYPE html>
<html>
<head>
    <title>Teams</title>
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/teambuilder.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="/assets/script/teams.js"></script>
</head>
<body>
	<?php
	session_start();
	if(!isset($_SESSION["loggedin"])) {
        session_destroy();
        header("location: /teambuilder/login.php");
        die();
    }
    $userId = $_SESSION["userId"];

    require "dbConnect.php";

    $db = get_db();
    
    if (isset($_POST["newTeam"])) {
    	$newTeamName = filter_var($_POST["newTeam"], FILTER_SANITIZE_STRING);
    	if ($newTeamName != "") {
    		$stmt = $db->prepare("INSERT INTO teams(owner, team_name) VALUES (:userId, :teamName);");
    		$stmt->bindValue("userId", $userId, PDO::PARAM_INT);
    		$stmt->bindValue("teamName", $newTeamName, PDO::PARAM_STR);
    		try {
    		$stmt->execute();
    		} catch (PDOException $ex) {
    			echo "<script>alert('That team already exists!');</script>";
    		}
    	}
    }

    if (isset($_POST["name"])) {
      $stmt = $db->prepare('UPDATE teams SET team_name = :newName WHERE owner=:tid AND id=:id;');
      $stmt->bindValue("newName", filter_var($_POST["name"], FILTER_SANITIZE_STRING), PDO::PARAM_STR);
      $stmt->bindValue("tid", $userId, PDO::PARAM_INT);
      $stmt->bindValue("id", $_SESSION["currentTeam"], PDO::PARAM_INT);
      $stmt->execute();
    }

    // Get types
    $stmt = $db->prepare('SELECT id, team_name FROM teams WHERE owner=:tid ORDER BY team_name;');
    $stmt->bindValue("tid", $_SESSION["userId"], PDO::PARAM_INT);
    $stmt->execute();
    $teamList = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST["teamMemberId"]) && isset($_SESSION["teamId"]) && isset($_SESSION["slot"])) {
    	$teamId = $_SESSION["teamId"];
    	$teamMemberId = $_POST["teamMemberId"];
    	$slot = "member_".$_SESSION["slot"];
    	$stmt = $db->prepare("UPDATE teams SET $slot = :teamMemberId WHERE id = :teamId AND owner=:tid;");
    	$stmt->bindValue("teamMemberId", $teamMemberId, PDO::PARAM_INT);
    	$stmt->bindValue("teamId", $teamId, PDO::PARAM_INT);
    	$stmt->bindValue("tid", $userId, PDO::PARAM_INT);
    	$stmt->execute();
    	unset($_SESSION["teamId"]);
    	unset($_SESSION["slot"]);
    }
    
    if (isset($_POST["team"])) {
    	$teamId = $_POST["team"];
    	$_SESSION["currentTeam"] = $teamId;
    } else if (isset($_SESSION["currentTeam"])) {
    	$teamId = $_SESSION["currentTeam"];
    }

    if (isset($teamId) && $teamId != "") { 
        $longStatement =  "SELECT team_name, member_1, member_2, member_3, member_4, member_5, member_6\n";
        $longStatement .= "FROM teams\n";
        $longStatement .= "WHERE owner=:userId AND id=:teamId;";
        $stmt = $db->prepare($longStatement);
        $stmt->bindValue("teamId", $teamId, PDO::PARAM_INT);
        $stmt->bindValue("userId", $userId, PDO::PARAM_INT);
        $stmt->execute();
        $teamData = $stmt->fetch(PDO::FETCH_ASSOC);
        for ($i = 1; $i <= 6; $i++) {
        	if (is_numeric($teamData["member_$i"])) {
         		$longStatement = "SELECT id, pokemon, nickname, level, ability, nature, held_item, move_1, move_2, move_3, move_4, hp_iv, atk_iv, def_iv, spa_iv, spd_iv, spe_iv, hp_ev, atk_ev, def_ev, spa_ev, spd_ev, spe_ev FROM team_members_view WHERE trainer_name=(SELECT trainer_name FROM users WHERE id=:tid) AND id=:memberId;";
           		$stmt = $db->prepare($longStatement);
           		$stmt->bindValue("memberId", intval($teamData["member_$i"]), PDO::PARAM_INT);
           		$stmt->bindValue("tid", $userId, PDO::PARAM_INT);
           		$stmt->execute();
           		$members["member_$i"] = $stmt->fetch(PDO::FETCH_ASSOC);
           	}
        }
    }
    ?>
    <div class='centered'>
	<?php include "navbar.php";?>
    <h1>Teams</h1>
    <h2>Select Team:</h2>
    <form name="teamSelect" action="teams.php" method='POST'>
    Team: <select name="team">
        <option></option>
        <?php foreach($teamList as $row) {
            $id = $row["id"];
            $name = $row["team_name"];
            echo "<option ";
            if ($id == $_SESSION["currentTeam"])
            	echo "selected ";
            echo "value='$id'>$name</option>\n";
        }?>
    </select>
    <button type='submit'>Load team</button>
    <button type='button' onclick='deleteTeam()'>Delete team</button>
	</form><br>
	<form action="teams.php" method="POST">
		Name: <input type='text' name='newTeam'>
		<button type='submit'>Create team</button>
	</form>
<?php
if (isset($teamData)) { 
	echo "<br><form action='teams.php' method='POST'><table><tr><td><input name='name' style='font-size: 2em; background-color: LightSteelBlue; border: none; border-radius: 5px;' value='".$teamData["team_name"]."'></td>
        <td><button type='submit'>Rename</button></td></tr></table></form>
	<table id='team'>";
	$imageRow = "<tr>";
	$nameRow = "<tr>";
	$dataRow = "<tr>";
	for ($i = 1; $i <= 6; $i++) {
		if (isset($members["member_$i"])) {
			$member = $members["member_$i"];
			$id = $member["id"];
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
			echo "<tr><td class='pkmn'><img src='/assets/img/sprites/pkmn/". strtolower($name) .".gif'/></td>";
			echo "<td class='pkmn'><h3>$name";
			if (!is_null($level)) echo " ($level)</h3>";
	    else "</h3>";
			if (!is_null($nickname)) echo "<h4>\"$nickname\"</h4>";
			echo "</td>";
			echo "<td class='pkmn'>Ability: $ability<br>Nature: $nature<br>";
			if (!is_null($held_item)) echo "Item: $held_item</td>";
			echo "<td class='pkmn'>Moves:<br>";
			foreach ($moves as $move) echo "$move<br>";
      echo "</td><td class='pkmn'>";
			echo "IVs:<table class='values'><tr><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
			                 <tr><td>$hp_iv</td><td>$atk_iv</td><td>$def_iv</td><td>$spa_iv</td><td>$spd_iv</td><td>$spe_iv</td></tr></table></td><td class='pkmn'>";
			echo "EVs:<table class='values'><tr><th>HP</th><th>Atk</th><th>Def</th><th>SpA</th><th>SpD</th><th>Spe</th></tr>
			                 <tr><td>$hp_ev</td><td>$atk_ev</td><td>$def_ev</td><td>$spa_ev</td><td>$spd_ev</td><td>$spe_ev</td></tr></table></td>";
			echo "<td class='pkmn'>";
      echo "<form action='newpokemon.php' method='POST'>
        <input type='hidden' name='edit' value='true'>
              <button value='$id' name='id' type='submit'>Modify</button></form>";
      echo "<form action='remove.php' method='POST'>
						 <input value='$i' name='slot' type='hidden'>
						 <input value='$teamId' name='teamId' type='hidden'>
						 <button type='submit'>Remove</button></form>";
			echo "</td></tr>";
		} else {
			echo "<td><form action='billspc.php' method='POST'>";
			echo "<input type='hidden' name='teamId' value='$teamId'><input type='hidden' name='slot' value='$i'>";
			echo "<button type='submit'>Add</button></form></td></tr>";
		}
	}
	echo "</table>";
}
?>
</div>
    <?php include "../footer.php"; ?>
</body>
</html>
