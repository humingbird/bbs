<html>
	<head>
		<title>掲示板:{$info.title}</title>
		<script type="text/javascript" src = "bbs.js"></script>
		<link type="text/css" href="bbs.css" rel="stylesheet">
	</head>
	<body onload="displayError()">
		<div>
			<div id='error'></div>
			<h4>No:{$info.id}    <span id=title>{$info.title}</span></h4>
			{foreach from=$comment key=k item=v}
				<div class="comment">
					<div>{$k + 1} 名前:{if $v.email}<a href="mailto:{$v.email}">{/if}{if $v.name}<span id=name>{$v.name}</span>{else}<span id=name>ななしさん</span>{/if}{if $v.email}</a>{/if}  投稿日時：{$v.created}</div>
					<div>{$v.description}</div>
				</div>
				<hr>
			{/foreach}
			</div>
		</div>
		<!-- ここからコメント投稿 -->
			<div>
			{if $login && $flag !=1}
				<form method="POST" action="?page=list&regist=1">
					<div>名前<input type="text" name="name">email
					<input type="text" name="email"></div>
					<div>コメント</br>
						<textarea name="comment" cols=40 rows=4 wrap="hard"></textarea>
					</div>
					<div><input type="submit" value="投稿"></div>
					<input type="hidden" name="threadId" value="{$info.id}">
				</form>
				{else if !$login}
					コメントを書き込むにはログインが必要です</br>
					<a href="{$login_url}">facebookログイン</a>
				{else}
					このスレッドの書き込みは1000件を越えたので書き込みできません。
				{/if}
			<div>
		<!-- ここまでコメント投稿 -->

		<div><a href="./">topに戻る</a>
	</body>
</html>
