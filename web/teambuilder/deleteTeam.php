<?php
if (!isset($_POST["team"])) {
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

try {
    $releaseStatement = "DELETE FROM teams WHERE owner=:tid AND id=:pid;";
    $stmt = $db->prepare($releaseStatement);
    $stmt->bindValue("tid", $_SESSION["userId"], PDO::PARAM_INT);
    $stmt->bindValue("pid", $_POST["team"], PDO::PARAM_INT);
    $stmt->execute();
    header("location: /teambuilder/teams.php");
    die();
} catch (PDOException $ex) {
    error_log($ex);
    //$_SESSION["releaseError"] = $ex;
    //$_SESSION["releaseErrorId"] = $_POST["id"];
    header("location: /teambuilder/teams.php");
    die();
}
?>
