<?php

if (basename($_SERVER["SCRIPT_FILENAME"]) == 'dbConnect.php') {
	http_response_code(404);
	die();
}

function get_db() {
	$db = NULL;

	try {
    	$username = "www-data";
    	$password = "webpasswd";

    	$db = new PDO('pgsql:host=localhost;dbname=teambuilder;', $username, $password);

    	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $ex) {
    	error_log(print_r($ex, true));
    	die();
	}

	return $db;
}
?>