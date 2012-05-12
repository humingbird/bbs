<html>
	<head>
		<title>掲示板</title>
	</head>
	<body>
		<!-- インデックス表示 -->
		{foreach from=$list key=id item=val}
			<div>１つのスレッドの大枠
				<div>スレッドid:{$val.id}  <b>{$val.title}</b></div>
				<!-- ここから最大三件表示 -->
				{foreach from=$comment[$val.id] key=k item=c}
					<div>
						各スレについたコメントの大枠
						<div>ID:{$k +1} 名前:{if $c.email}<a href='{$c.email}}'>{/if}{if $c.name}{$c[$k].name}{else}ななしさん{/if}{if $c.email}</a>{/if}{$c.created}</div>
						<div>
							{$c.description}
						</div>
					</div>
				{/foreach}
				<!-- for文ここまで -->
			</div>
			<!-- ここからコメント投稿 -->
			<div>
				<form method="POST" action="?regist=1">
					<div>名前<input type="text" name="name">email
					<input type="text" name="email"></div>
					<div>コメント</br>
						<textarea name="comment" cols=40 rows=4 wrap="hard"></textarea>
					</div>
					<div><input type="submit" value="投稿"></div>
					<input type="hidden" name="threadId" value="{$val.id}">
				</form>
			<div>
			<div><a href="?page=list&id={$val.id}">全て表示する</a></div>
		{/foreach}
		<!-- インデックス表示ここまで -->
		<!-- スレッド作成遷移 -->
		<div style="background-color:lightgray;"><a href="?page=thread">新規スレッド作成</a></div>
		<!-- スレッド作成遷移ここまで -->
	</body>
</html>