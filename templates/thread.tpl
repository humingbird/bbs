<html>
	<head>
		<title>新規スレッド作成</title>
	</head>
	<body>
		<h5>必要事項を記入して、作成ボタンを押してください</h5>
		<form method="POST" action="?page=thread&regist=1">
			<div>タイトル</br>
				<input type="text" name="title"></div>
			</div>
			<div>名前</br>
				<input type="text" name="name"></div>
			</div>
			<div>email</br>
				<input type="text" name="email">
			</div>
			<div>コメント</br>
				<textarea name="comment" cols=40 rows=4 wrap="hard"></textarea>
			</div>
			<div><input type="submit" value="作成"></div>
		</form>
	</body>
</html>