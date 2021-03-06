<?php
session_start();
require('./db_connect.php');

if ($_POST['author_name'] === '') {
	$error['author_name'] = 'blank';
}

if ($_POST['message'] === '') {
	$error['message'] = 'blank';
}

// 投稿をデータベースへ保存する
if ($_POST['author_name'] && $_POST['message']) {
	echo 'yobareta';

	// 空白除去
	$author_name = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['author_name']);
	$message = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['message']);


	$sql = "INSERT INTO posts (author_name, message, created) VALUES (:author_name, :message, NOW())"; // INSERT文を変数に格納。:nameや:categoryはプレースホルダという、値を入れるための単なる空箱'
	$stmt = $PDO->prepare($sql); //挿入する値は空のまま、SQL実行の準備をする
	$stmt->bindValue(':author_name', $author_name, PDO::PARAM_STR);
	$stmt->bindValue(':message', $message, PDO::PARAM_STR);
	$stmt->execute(); //挿入する値が入った変数をexecuteにセットしてSQLを実行
	// 成功したら投稿が完了しましたを表示リロードしても値が保持されるようにsessionを使った
	$_SESSION['succes_message'] = '投稿が完了しました';

	// 以下がないとリロード時に$_POSTの内容が残り、再度投稿してしまう
	header("Location: index.php");
}

// 投稿をデータベースから取得する
$posts = $PDO->query('SELECT * FROM posts');

?>

<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>PHPひとこと掲示板</title>

	<link rel="stylesheet" href="./style.css" />
</head>

<body>
	<div id="content">
		<?php if ($error['author_name'] !== 'blank') : ?>
			<p class="succes"><?php print(htmlspecialchars($_SESSION['succes_message'], ENT_QUOTES)); ?></p>
		<?php endif; ?>
		<p class="title">PHPひとこと掲示板</p>
		<hr>
		<div class="form-block">
			<form action="" method="post" enctype="multipart/form-data">
				<p class="sub-title">投稿者ニックネーム:(必須)</p>
				<input class="input-name" type="text" name="author_name" maxlength="255" value="<?php print(htmlspecialchars($_POST['author_name'], ENT_QUOTES)); ?>" />
				<?php if ($error['author_name'] === 'blank') : ?>
					<p class="error">*投稿者氏名を入力してください</p>
				<?php endif; ?>
				<!-- 以下改行してはいけない -->
				<p class="sub-title">投稿内容:(必須)</p>
				<textarea type="text" name="message" rows="4" cols="40"><?php print(htmlspecialchars($_POST['message'], ENT_QUOTES)); ?></textarea>
				<?php if ($error['message'] === 'blank') : ?>
					<p class="error">*投稿内容を入力してください</p>
				<?php endif; ?>
				<div><input class="submit-button" type="submit" value="投稿する" /></div>
			</form>
		</div>
	</div>
	<hr>
	<div class="list-block">
		<?php foreach ($posts as $post) : ?>
			<div class="list-item">
				<div class="list-title">
					<p><?php print(htmlspecialchars($post['author_name'], ENT_QUOTES)); ?></p>
					<p><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></p>
					<a href="delete.php?id=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">削除</a>
				</div>
				<p class="list-content"><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?></p>
			</div>
		<?php endforeach; ?>
	</div>
</body>

</html>