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
						<div>ID:1 名前:{if $c.email}<a href='{$c.email}}'>{/if}{if $c.name}{$c[$k].name}{else}ななしさん{/if}{if $c.email}</a>{/if}{$c.created}</div>
						<div>
							{$c.description}
						</div>
					</div>
				{/foreach}
				<!-- for文ここまで -->
			</div>
		{/foreach}
		<!-- インデックス表示ここまで -->
		<!-- スレッド作成遷移 -->
		<div style="background-color:lightgray;"><a href="thread.html">新規スレッド作成</a></div>
		<!-- スレッド作成遷移ここまで -->
	</body>
</html>