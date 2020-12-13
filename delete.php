<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require('dbconnect.php');

var_dump($_SESSION['id']);


if(isset($_SESSION['id'])){
//他のユーザのやつも消さない？？
    $id = $_REQUEST['id'];//タスクID
    $tasks = $db->prepare('SELECT * FROM tasks WHERE id=?');
    $tasks->execute(array($id));
    $task = $tasks->fetch();
    

    if($task['id'] == $id){
        $del = $db->prepare('DELETE FROM tasks WHERE id=?');
        $del->execute(array($id));
    }

}
header('Location: index.php');
exit();
?>