<html>
	<head>
		<title>新規スレッド作成</title>
		<script type="text/javascript" src = "bbs.js"></script>
		<link src="bbs.css" rel="stylesheet" type="text/stylesheet">
	</head>
	<body onload="displayError()" style="background-color:lightgray;">
		<h3>新規スレッド作成</h3>
		<h5>必要事項を記入して、作成ボタンを押してください</h5>
		<div class="error" id="error"></div>
		<form method="POST" action="?page=thread&regist=1">
			<div>タイトル</br>
				<input type="text" name="title"></div>
			</div>
			<div>名前</br>
				<input type="text" name="name" {if $profile.id}value="{$profile.id}"{/if}></div>
			</div>
			<div>email</br>
				<input type="text" name="email">
			</div>
			<div>コメント</br>
				<textarea name="comment" cols=40 rows=4 wrap="hard"></textarea>
			</div>
			{if $profile.link}<input type="hidden" name="fb_url" value="{$profile.link}">{/if}
			<div><input type="submit" value="作成"></div>
		</form>
		<div id="fb_login">{if !$login}<a href="{$fb_url}">fbログイン</a>{/if}
		<a href="{$home}">topに戻る</a>
	</body>
</html>
