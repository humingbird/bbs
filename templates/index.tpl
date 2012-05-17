<!DOCTYPE html> 
<html>
	<head>
		<title>掲示板</title>
		<meta name="viewport" content="width=640px,user-scalable=yes" />
		<script type="text/javascript" src = "bbs.js"></script>
		<!-- スマートフォンとPCで読み込むCSSファイルを変える -->
		<style type="text/css">
			@import url("bbs.css") screen and (min-width:960px);
			@import url("bbs_sp.css") screen and (min-width:480px) and (max-width:768px);
		</style>
	</head>
	<body onload="displayError()">
		<h3>掲示板</h3>
		<!-- スレッド一覧表示 -->
		<div id="thread_list">
			スレッド一覧(最新10件表示）</br>
			{foreach from=$title key=id item=val}
				<a href="?page=list&id={$val.id}">{$val.title}</a>    
			{/foreach}
		</div>
		<!-- スレッド一覧表示ここまで -->
		<!-- インデックス表示 -->
		{foreach from=$list key=id item=val}
			<div id="thread">
				<div class="title"><h4>No:{$val.id}  <a href="?page=list&id={$val.id}">{$val.title}</a></h4></div>
				<div>1 名前:{if $val.email}<a href='mailto:{$val.email}'>{/if}{if $val.name}<span id=name>{$val.name}</span>{else}<span id=name>名無しさん</span>{/if}{if $val.email}</a>{/if}  投稿日時:{$val.created}</div>
				<div>
					{$val.description}
				</div>
				</br>				
				<!-- ここから最大10件表示 -->
				{foreach from=$comment[$val.id] key=k item=c}
					<div class="comment" id="c_{$c.id}">
						<div>{$c.id +1} 名前:{if $c.email}<a href='mailto:{$c.email}'>{/if}{if $c.name}<span id=name>{$c.name}</span>{else}<span id=name>名無しさん</span>{/if}{if $c.email}</a>{/if}  投稿日時:{$c.created}</div>
						<div>
							{$c.description}
						</div>
					</div>
					</br>
				{/foreach}
				<!-- for文ここまで -->
				<div id="list_nav"><a href="?page=list&id={$val.id}">全て表示する</a>  <a href="?page=list&id={$val.id}&limit=50">最新50件</a>  <a href="?page=list&id={$val.id}&limit=100">1-100</a>
				  <a href="#">板のトップ</a>  <a href="">リロード</a></div>
			</div>
			<!-- ここからコメント投稿 -->
			<div id="form">
				{if $flag[$k] !=1}
				<div class="error" id="error_{$val.id}"></div>
				<form method="POST" action="?regist=1" id = "input_comment">
					<div id="form_name">名前<input type="text" name="name" {if $profile.name}value="{$profile.name}"{/if} ></div>
					<div id="form_email">email<input type="email" name="email"></div>
					<div>コメント</br>
						<textarea name="comment" cols=40 rows=4></textarea>
					</div>
					<div><input type="submit" value="投稿"></div>
					<input type="hidden" name="threadId" value="{$val.id}">
				</form>
				<div id="fb_login"><a href="{$login_url}">fbログイン</a></div>
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
