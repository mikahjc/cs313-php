<?php
if (!isset($_POST["id"])) {
    header("Location: /teambuilder/billspc.php");
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

try {
    $releaseStatement = "DELETE FROM team_members WHERE owner=:tid AND id=:pid;";
    $stmt = $db->prepare($releaseStatement);
    $stmt->bindValue("tid", $_SESSION["userId"], PDO::PARAM_INT);
    $stmt->bindValue("pid", $_POST["id"], PDO::PARAM_INT);
    $stmt->execute();
    header("location: /teambuilder/billspc.php");
    die();
} catch (PDOException $ex) {
    $_SESSION["releaseError"] = $ex;
    $_SESSION["releaseErrorId"] = $_POST["id"];
    header("location: /teambuilder/billspc.php");
    die();
}
?>