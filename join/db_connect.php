<?php
try {
    $PDO = new PDO('mysql:dbname=message_board;host=localhost;charset=utf8', 'root', 'root');
} catch (PDOException $e) {
    echo 'DB接続エラー: ' . $e->getMessage();
}
