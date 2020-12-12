<?php
session_start();
require('dbconnect.php');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time(); //セッションの時間を初期化
  $members = $db->prepare('SELECT * FROM members WHERE id=?'); //メンバーを選択する。引数を持たせて準備
  $members->execute(array($_SESSION['id'])); //引数をもたせて実行する
  $member = $members->fetch();//フェッチモードで取り出す
  echo ('<pre>');
  var_dump($member);
  echo ('</pre>');
} else {
  header('Location:login.php');
  exit();
}

if (!empty($_POST)) {
  if ($_POST['insert_task'] !== '') {
    $message = $db->prepare('INSERT INTO tasks SET member_id=?, taskname=?,created=NOW()');
    $message->execute(array(
      $member['id'],
      $_POST['insert_task'],
    ));
  } else {
    $error['insert_task'] = 'blank';
  }
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
  <link rel="stylesheet" href="\style.css">
</head>

<body>
  <h1>ToDo List</h1>

  <form action="" method="post">
    <dl>
      <dt><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>さん、メッセージをどうぞ</dt>
      <dd>
        <textarea name="insert_task" cols="50" rows="5"><?php print(htmlspecialchars($message, ENT_QUOTES)); ?></textarea>
        <?php if ($error['insert_task'] === 'blank') : ?>
          <p class="error">*タスクを入力してください</p>
        <?php endif; ?>
      </dd>
    </dl>
    <div>
      <p>
        <input type="submit" value="投稿する" />
      </p>
    </div>
  </form>

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


</body>

</html>