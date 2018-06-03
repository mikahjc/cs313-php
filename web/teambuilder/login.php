<?php
	$error = NULL;
	session_start();
	if(isset($_SESSION["loggedin"])) {
		header("location: /teambuilder/");
		die();
	}
	if(isset($_POST["email"]) && isset($_POST["password"])) {
		require "dbConnect.php";

    	$db = get_db();

    	$email = $_POST["email"];
    	$password = $_POST["password"];

    	$stmt = $db->prepare('SELECT id FROM users WHERE email=:email AND password=:password');
    	$stmt->bindValue('email', $email, PDO::PARAM_STR);
    	$stmt->bindValue('password', $password, PDO::PARAM_STR);
    	$stmt->execute();
    	
    	$numberOfRows = $stmt->rowCount();

    	if ($numberOfRows == 1) {
    		$_SESSION["loggedin"] = true;
    		$result = $stmt->fetch(PDO::FETCH_ASSOC);
    		$_SESSION["userId"] = $result["id"];
    		header("location: /teambuilder/");
    		die();
    	} else {
    		$error = "Invalid Email or Password<br>";
    	}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Teambuilder Login</title>
	<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
	<?php echo $error; ?>
	<form action="login.php" method="POST">
		Email: <input type="text" name="email"><br>
		Password: <input type="password" name="password"><br>
		<button type="submit">Login</button>
	</form>
</body>
</html>