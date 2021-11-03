<?php
require('../db_connect.php');

if (!empty($_POST)) {
    $statement = $db->prepare('INSERT INTO members SET name=?, created=NOW()');
    echo $statement->execute(array(
        'Lebron James',
    ));

    exit();
}

if ($_POST['name'] === '') {
    print('名前が入力されていません');
} else {
    print('名前が入力されています');
}

?>
<div id="content">
<p>次のフォームに必要事項をご記入ください。</p>
<form action="" method="post" enctype="multipart/form-data">
	<dl>
		<dt>ニックネーム<span class="required">必須</span></dt>
		<dd>
            <input type="text" name="name" size="35" maxlength="255" value="" />
		</dd>
		<dd>
            <input type="file" name="image" size="35" value="test"  />
        </dd>
	</dl>
	<div><input type="submit" value="入力内容を確認する" /></div>
</form>
</div>
</body>
</html>