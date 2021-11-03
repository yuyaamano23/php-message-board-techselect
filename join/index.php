<?php
require('./db_connect.php');

if ($_POST['author_name'] === '') {
	$error['author_name'] = 'blank';
}

if ($_POST['message'] === '') {
	$error['message'] = 'blank';
}

// 投稿をデータベースへ保存する
if ($_POST['author_name'] && $_POST['message']) {
	// 以下がないとリロード時に$_POSTの内容が残り、再度投稿してしまう
	header("Location:{$_SERVER['PHP_SELF']}");
	echo 'yobareta';

	// 空白除去
	$author_name = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['author_name']);
	$message = preg_replace('/\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z/u', '', $_POST['message']);


	$sql = "INSERT INTO posts (author_name, message, created) VALUES (:author_name, :message, NOW())"; // INSERT文を変数に格納。:nameや:categoryはプレースホルダという、値を入れるための単なる空箱'
	$stmt = $PDO->prepare($sql); //挿入する値は空のまま、SQL実行の準備をする
	$stmt->bindValue(':author_name', $author_name, PDO::PARAM_STR);
	$stmt->bindValue(':message', $message, PDO::PARAM_STR);
	$stmt->execute(); //挿入する値が入った変数をexecuteにセットしてSQLを実行
	// 成功したら投稿が完了しましたを表示したい
	$_POST = [];    // $_POST = []; $_POST初期化
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
	<title>PHP掲示板</title>

	<link rel="stylesheet" href="./style.css" />
</head>

<body>
	<div id="content">
		<div class="form-block">
			<h1>PHP掲示板</h1>
			<hr>
			<form action="" method="post" enctype="multipart/form-data">
				<dl>
					<dd>
						<input placeholder="投稿者氏名:必須" type="text" name="author_name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['author_name'], ENT_QUOTES)); ?>" />
						<?php if ($error['author_name'] === 'blank') : ?>
							<p class="error">*投稿者氏名を入力してください</p>
						<?php endif; ?>
					</dd>
					<dd>
						<!-- 以下改行してはいけない -->
						<textarea placeholder="投稿内容:必須" type="text" name="message" rows="4" cols="40"><?php print(htmlspecialchars($_POST['message'], ENT_QUOTES)); ?></textarea>
						<?php if ($error['message'] === 'blank') : ?>
							<p class="error">*投稿内容を入力してください</p>
						<?php endif; ?>
					</dd>
				</dl>
				<div><input type="submit" value="入力内容を確認する" /></div>
			</form>
		</div>
	</div>
	<div class="list-block">
		<?php foreach ($posts as $post) : ?>
			<p><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?></p>
			<p><?php print(htmlspecialchars($post['author_name'], ENT_QUOTES)); ?></p>
			<p><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></p>
		<?php endforeach; ?>
	</div>
</body>

</html>