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
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    echo "<div><span class='bold'>" . $row["book"] . " " . $row["chapter"] . ":" . $row["verse"]. "</span>";
    echo " - \"" . $row["content"] . "\"</div>";
  }
?>
<form action='index.php' method='POST'>
  Book: <input type="text" name="book"><br>
  Chapter: <input type="number" min="1" name="chapter"><br>
  Verse: <input type="number" min="1" name="verse"><br>
  Content: <textarea name="content"></textarea><br>
  <?php
  foreach($db->query('SELECT id, name FROM topics') as $row)
  {
    $id = $row["id"];
    $name = $row["name"];
    echo "<input type='checkbox' name='topic[]' value='$id'>$name<br>";
  }
  ?>
  <input type='checkbox' name='topic[]' value='new'><input type="text" name="newTopic"><br>
  <input type='submit'>
</form>
</body>
</html>
