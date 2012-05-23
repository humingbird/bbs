<html>
	<head>
		<title>新規スレッド作成</title>
		<!--[if lt IE 9]>
			<script src="css3-mediaqueries.js" type="text/javascript"></script>
		<![endif]-->
		<!-- スマートフォンとPCで読み込むCSSファイルを変える -->
		<style type="text/css">
			@import url("bbs.css") screen and (min-width:960px);
			@import url("bbs_sp.css") screen and (max-width:480px);
			@import url("bbs_sp.css") screen and (max-width:768px);
		</style>
		<meta name="viewport" content="width=device-width">
		<script type="text/javascript" src = "bbs.js"></script>
	</head>
	<body onload="displayError()">
		<div id="regist_thread">
		<h3>新規スレッド作成</h3>
		<h5>必要事項を記入して、作成ボタンを押してください</h5>
		<div class="error" id="error"></div>
		<form method="POST" action="?page=thread&regist=1">
			<div>タイトル</br>
				<input type="text" name="title"></div>
			<div>名前</br>
				<input type="text" name="name" {if $profile.id}value="{$profile.id}"{/if}></div>
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
		</div>
	</body>
</html>
