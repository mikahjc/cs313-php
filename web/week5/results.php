<!DOCTYPE html>
<html>
<body>
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
  $query = $_GET['book'];
  $stmt = $db->prepare('SELECT book, chapter, verse, content FROM scriptures WHERE book=:query');
  $stmt->bindValue('query', $query, PDO::PARAM_STR);
  $stmt->execute();
  echo "Query: " . $query . "<br>";
  $stmt->debugDumpParams();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    echo "<div><span class='bold'>" . $row["book"] . " " . $row["chapter"] . ":" . $row["verse"]. "</span>";
    echo " - \"" . $row["content"] . "\"</div>";
  }
?>
</body>
</html>
