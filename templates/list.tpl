<html>
	<head>
		<title>掲示板:{$info.title}</title>
	</head>
	<body>
		<div>
			<div><h4>threadId:{$info.id}    {$info.title}</h4></div>
			{foreach from=$comment key=k item=v}
				<div>
					<div>id:{$k + 1} 名前:{if $v.email}<a href="{$v.email}">{/if}{if $v.name}{$v.name}{else}ななしさん{/if}{if $v.email}</a>{/if}  {$v.created}</div>
					<div>{$v.description}</div>
				</div>
			{/foreach}
		</div>
		<div><a href="./">topに戻る</a>
	</body>
</html>