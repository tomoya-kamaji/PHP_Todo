<?php
try {
$db = new PDO('mysql:dbname=mini_todo;host=localhost:8889;charset=utf8', 'root', 'root');
} catch (PDOException $e) {
echo 'DB接続エラー！: ' . $e->getMessage();
}
?>