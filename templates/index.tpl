<html>
	<head>
		<title>掲示板</title>
		<script type="text/javascript" src = "bbs.js"></script>
		<link type="text/css" href="bbs.css" rel="stylesheet">
	</head>
	<body onload="displayError()">
		<div id="error"></div>
		<h3>掲示板</h3>
		<!-- スレッド一覧表示 -->
		<div id="thread_list">
			>>スレッド一覧(最新10件表示）</br>
			{foreach from=$title key=id item=val}
				<a href="?page=list&id={$val.id}">{$val.title}</a>    
			{/foreach}
		</div>
		<!-- スレッド一覧表示ここまで -->
		<!-- インデックス表示 -->
		{foreach from=$list key=id item=val}
			<div id="thread">
				<h4>No:{$val.id}  <a href="?page=list&id={$val.id}">{$val.title}</a></h4>
				<!-- ここから最大三件表示 -->
				{foreach from=$comment[$val.id] key=k item=c}
					<div class="comment" id="c_{$k + 1}">
						<div>{$k +1} 名前:{if $c.email}<a href='mailto:{$c.email}'>{/if}{if $c.name}<span id=name>{$c.name}</span>{else}<span id=name>ななしさん</span>{/if}{if $c.email}</a>{/if}  投稿日時:{$c.created}</div>
						<div>
							{$c.description}
						</div>
					</div>
					<hr>
				{/foreach}
				<!-- for文ここまで -->
				<a href="?page=list&id={$val.id}">全て表示する</a>  <a href="?page=list&id={$val.id}&limit=50">最新50件</a>  <a href="?page=list&id={$val.id}&limit=100">1-100</a>
				  <a href="#">板のトップ</a>  <a href="">リロード</a>
			</div>
			<!-- ここからコメント投稿 -->
			<div id="form">
				{if $login && $flag[$k] !=1}
				<form method="POST" action="?regist=1" id = "input_comment">
					<div id="form_name">名前<input type="text" name="name">email
					<input type="text" name="email"></div>
					<div>コメント</br>
						<textarea name="comment" cols=40 rows=4></textarea>
					</div>
					<div><input type="submit" value="投稿"></div>
					<input type="hidden" name="threadId" value="{$val.id}">
				</form>
				{else if !$login}
					コメントを書き込むにはログインが必要です</br>
					<a href="{$login_url}">facebookログイン</a>
				{else}
					このスレッドの書き込みは1000件を越えたので書き込みできません。
				{/if}
			</div>
			<!-- ここまでコメント投稿 -->
		{/foreach}
		<!-- インデックス表示ここまで -->
		<!-- スレッド作成遷移 -->
		<div id="footer"><a href="?page=thread">新規スレッド作成</a></div>
		<!-- スレッド作成遷移ここまで -->
	</body>
</html>
