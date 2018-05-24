<!DOCTYPE html>
<html>
<head>
  <title>Scripture Reference</title>
  <link href="/assets/css/style.css" rel="stylesheet">
</head>
  <h1>Scripture Resources</h1><br>
  <?php
  try {
    $username = "www-data";
    $password = "webpasswd";
    $db = new PDO('pgsql:host=localhost;dbname=team5;', $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $ex) {
    echo 'Error: ' . $ex->getMessage();
    die();
  }

  foreach($db->query('SELECT book, chapter, verse, content FROM scriptures') as $row)
  {
    echo "<div><span class='bold'>" . $row["book"] . " " . $row["chapter"] . ":" . $row["verse"]. "</span>";
    echo " - \"" . $row["content"] . "\"</div>";
  }
  ?>
</html>
