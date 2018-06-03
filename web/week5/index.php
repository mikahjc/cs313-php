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

  if (isset($_POST["book"]) && isset($_POST["chapter"]) && isset($_POST["verse"]) && isset($_POST["content"]) && isset($_POST["topic"]))
  {
    foreach($_POST["topic"] as $topic) {
      if ($topic == 'new') {
        $stmt = $db->prepare('INSERT INTO topics(name) VALUES (:topic);');
        $stmt->bindValue('topic', $_POST["newTopic"], PDO::PARAM_STR);
        $stmt->execute();
      }
    }
    $stmt = $db->prepare('INSERT INTO scriptures(book, chapter, verse, content) VALUES (:book, :chapter, :verse, :content);');
    $stmt->bindValue('book', $_POST["book"], PDO::PARAM_STR);
    $stmt->bindValue('chapter', intval($_POST["chapter"]), PDO::PARAM_INT);
    $stmt->bindValue('verse', intval($_POST["verse"]), PDO::PARAM_INT);
    $stmt->bindValue('content', $_POST["content"], PDO::PARAM_STR);
    $stmt->execute();
    $scriptureId = $db->lastInsertId('scriptures_id_seq');
    echo $scriptureId;

    foreach($_POST["topic"] as $topicId) {
      $stmt = $db->prepare('INSERT INTO scripture_topics(scripture_id, topic_id) VALUES (:scripture_id, :topic_id);');
      $stmt->bindValue('scripture_id', $scriptureId, PDO::PARAM_INT);
      $stmt->bindValue('topic_id', $topicId, PDO::PARAM_INT);
      $stmt->execute();
    }
  }


  $stmt = $db->prepare('SELECT id, book, chapter, verse, content FROM scriptures');
  $stmt->execute();
  $scriptures = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach($scriptures as $row)
  {
    // Print scripture information
    echo "<div><span class='bold'>" . $row["book"] . " " . $row["chapter"] . ":" . $row["verse"]. "</span>";
    echo " - \"" . $row["content"] . "\"<br>Topics: ";

    // Query database for topics list on the current scripture
    $stmt = $db->prepare('SELECT name FROM topics_list WHERE scripture_id=:id');
    $stmt->bindValue('id', $row["id"], PDO::PARAM_INT);
    $stmt->execute();
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Print all returned topic names
    foreach ($topics as $topicName) {
      echo $topicName["name"] . ", ";
    }

    echo "</div>";
  }
  ?>
</html>
