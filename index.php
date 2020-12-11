<?php
session_start();
require('dbconnect.php');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();
  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
  echo('<pre>');
  var_dump($member);
  echo('</pre>');
  
} else {
  header('Location:login.php');
  exit();
}

//タスクを取得する
$tasks = $db->prepare('SELECT * FROM tasks WHERE member_id=?');
$tasks->execute(array($member['id']));


?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>ToDo List</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="\style.css">
</head>

<body>
  <h1>To Do List</h1>

  <div class="form-group">
    <label class="control-label">タスク</label>
    <?php foreach ($tasks as $task) { ?>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
        <label class="form-check-label" for="defaultCheck1"><?= htmlspecialchars($task["taskname"], ENT_QUOTES) ?></label>
      </div>
    <?php  } ?>
  </div>

  <ul>
    <?php foreach ($tasks as $task) { ?>
      <li><?= htmlspecialchars($task["taskname"], ENT_QUOTES) ?></li>
    <?php  } ?>
    <?php
    $tasks = null;
    ?>
  </ul>

  <a href="insert.php">タスク登録</a>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>

</html>