<?php
	$error = NULL;
	session_start();
	if(isset($_SESSION["loggedin"])) {
		header("location: /teambuilder/");
		die();
	}
	if($_POST["email"] != "" && 
     $_POST["password"] != "" &&
     $_POST["first"] != "" &&
     $_POST["last"] != "" &&
     $_POST["trainer"] != "") {
		require "dbConnect.php";

    	$db = get_db();

    	$email = $_POST["email"];
    	$password = $_POST["password"];
      $first = $_POST["first"];
      $last = $_POST["last"];
      $trainer = $_POST["trainer"];

    	$stmt = $db->prepare('SELECT id FROM users WHERE email=:email');
    	$stmt->bindValue('email', $email, PDO::PARAM_STR);
    	$stmt->execute();
    	
    	$numberOfRows = $stmt->rowCount();

    	if ($numberOfRows != 0) {
    		$error = "Email address already in use.";
    	} else {
        $stmt = $db->prepare('INSERT INTO users(email, first_name, last_name, trainer_name, password) VALUES (:email, :first, :last, :trainer, :password)');
    	  $stmt->bindValue('email', $email, PDO::PARAM_STR);
    	  $stmt->bindValue('first', $first, PDO::PARAM_STR);
    	  $stmt->bindValue('last', $last, PDO::PARAM_STR);
    	  $stmt->bindValue('trainer', $trainer, PDO::PARAM_STR);
    	  $stmt->bindValue('password', $password, PDO::PARAM_STR);
        $stmt->execute();
        header("location: /teambuilder/");
        die();
    	}
	} else {
    $error = "All fields required!<br>";
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Teambuilder Registration</title>
	<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet"> 
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/teambuilder.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>function validateForm() {
      var pwd1 = document.forms["register"]["password"].value;
      var pwd2 = document.forms["register"]["confirmPass"].value;
      if (pwd1 !== pwd2) {
        alert("Passwords do not match!");
        return false;
      }
      return true;
    }</script>
</head>
<body>
  <div class="centered">
	<?php echo $error; ?>
	<form action="register.php" name="register" method="POST" onsubmit="return validateForm()">
    <table>
      <tr>
		    <td>First Name:</td><td><input type="text" name="first"></td>
      </tr>
      <tr>
		    <td>Last Name:</td><td><input type="text" name="last"></td>
      </tr>
      <tr>
		    <td>Trainer Name:<br>(Nickname)</td><td><input type="text" name="trainer"></td>
      </tr>
      <tr>
		    <td>Email:</td><td><input type="text" name="email"></td>
      </tr>
      <tr>
		    <td>Password:</td><td><input type="password" name="password"></td>
      </tr>
      <tr>
		    <td>Confirm Password:</td><td><input type="password" name="confirmPass"></td>
      <tr>
        <td colspan='2'>
		      <button type="submit" style="width: 100%">Register</button>
        </td>
      </tr>
    </table>
	</form>
  </div>
</body>
</html>
