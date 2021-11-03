<?php
session_start();

require('./db_connect.php');

$sql = "DELETE FROM posts WHERE id = :id"; // DELETE文を変数に格納。:idはプレースホルダという、値を入れるための単なる空箱'
$stmt = $PDO->prepare($sql); //挿入する値は空のまま、SQL実行の準備をする
$stmt->bindValue(':id', $_GET['id'], PDO::PARAM_STR);
$stmt->execute(); //挿入する値が入った変数をexecuteにセットしてSQLを実行

$_SESSION['succes_message'] = "削除が完了しました";

header("Location: index.php");
exit();
