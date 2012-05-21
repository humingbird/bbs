<html>
	<head>
		<title>掲示板:{$info.title}</title>
		<!-- スマートフォンとPCで読み込むCSSファイルを変える -->
		<style type="text/css">
			@import url("bbs.css") screen and (min-width:960px);
			@import url("bbs_sp.css") screen and (max-width:480px);
		</style>
		<meta name="viewport" content="width=device-width">
		<script type="text/javascript" src ="jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src = "bbs.js"></script>
		<script type="text/javascript">
			$(function(){
				var link = $("#sp_link")[0];
				link.addEventListener("touchstart",displayComment,false);
			});
			
			function displayComment(){
				var num = 10;
				while(num<1000){
					if($("#c_" + num).length && $("#c_" + num).css('display') == 'none'){
						var end = num +10;
						for(var i=num;i<end;i++){
							$("#c_" + i).css('display','');
						}
						break;
					}else if($("#c_" + num).length == 0){
						break;
					}
					num = num + 10;
				}
			}
		</script>
	</head>
	<body onload="displayError()">
		<div class="thread">
			<div class="title"><h4>No:{$info.id}    <span id=title>{$info.title}</span></h4></div>
			<div class="name" id="thread_info">1 名前:{if $info.email}<a href="mailto:{$info.email}">{/if}{if $info.name}<span id=name>{$info.name}</span>{else}<span id=name>名無しさん</span>{/if}{if $info.email}</a>{/if}
			{if $info.fb_url}<a href="{$info.fb_url}">facebook</a>{/if}  <span class="date">投稿日時：{$info.created}</span></div>
				<div>{$info.description}</div>
			</br>
			{if $comment}
			{foreach from=$comment key=k item=v}
				<div class="comment" {if $device != 'pc' && $k>9} style="display:none;"{/if} id="c_{$k}">
					<div>{$k + 2} 名前:{if $v.email}<a href="mailto:{$v.email}">{/if}{if $v.name}<span id=name>{$v.name}</span>{else}<span id=name>名無しさん</span>{/if}{if $v.email}</a>{/if} 
						{if $v.fb_url}<a href="{$v.fb_url}">facebook</a>{/if}   <span class="date">投稿日時：{$v.created}</span></div>
					<div>{$v.description}</div>
				</div>
				</br>
			{/foreach}
			{/if}
		<div id="sp_link">次の１０件</div>
		<!-- ここからコメント投稿 -->
		</br>
		<div>
			{if $flag !=1}
				<div class="error"id='error_{$info.id}'></div>
				<form method="POST" action="?page=list&regist=1">
					<div id="form_name">名前<input type="text" name="name"{if $profile.id}value="{$profile.id}"{/if}></div>
					<div id="form_email">email<input type="text" name="email"></div>
					{if $device  =='pc'}
						<div id="text_area">コメント</br>
							<textarea name="comment" wrap="hard"></textarea>
						</div>
					{else}
						コメント</br>
						<textarea name="comment"wrap="hard"></textarea>
					{/if}
					<div><input type="submit" value="投稿"></div>
					<input type="hidden" name="threadId" value="{$info.id}">
					{if $profile.link}<input type="hidden" name="fb_url" value="{$profile.link}">{/if}
				</form>
			{else}
					このスレッドの書き込みは1000件を越えたので書き込みできません。
			{/if}
			<div><a href="./">topに戻る</a>
				</div>
			<!-- ここまでコメント投稿 -->
		</div>
	</body>
</html>
