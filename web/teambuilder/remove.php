<?php
if (!isset($_POST["teamId"]) || !isset($_POST["slot"])) {
    header("Location: /teambuilder/teams.php");
    die();
}
session_start();
    if(!isset($_SESSION["loggedin"])) {
        session_destroy();
        header("location: /teambuilder/login.php");
        die();
    }
    require "dbConnect.php";

    $db = get_db();

	$slotName = "member_".$_POST["slot"];
    $releaseStatement = "UPDATE teams SET $slotName=null WHERE owner=:tid AND id=:teamId;";
    $stmt = $db->prepare($releaseStatement);
    $stmt->bindValue("tid", $_SESSION["userId"], PDO::PARAM_INT);
    $stmt->bindValue("teamId", $_POST["teamId"], PDO::PARAM_INT);
    $stmt->execute();
    header("location: /teambuilder/teams.php");
    die();
?>