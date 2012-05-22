<!DOCTYPE html> 
<html>
	<head>
		<title>掲示板</title>
		<!--[if lt IE 9]>
			<script src="css3-mediaqueries.js" type="text/javascript"></script>
		<![endif]-->
		<!-- スマートフォンとPCで読み込むCSSファイルを変える -->
		<style type="text/css">
			@import url("bbs.css") screen and (min-width:960px);
			@import url("bbs_sp.css") screen and (max-width:480px);
		</style>
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<script type="text/javascript" src ="jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src = "bbs.js"></script>
		<script type="text/javascript">
			$(function(){
				var link = $("#sp_link")[0];
				link.addEventListener("touchstart",displayThreadList,false);
			});
			function displayThreadList(e){
					$.getJSON("http://49.212.148.198/bbs/api/threadList.php?next=2",function(data){
						$.getJSON("http://49.212.148.198/bbs/api/login.php",function(login){
						 			if(login.login && login.profile.id){
						 				var defaultName = login.profile.id;
									}
						//新規スレッドの下に新しく5件分追加する
						var num = 5;
						for(var i=0;i<data.length;i++){
						 	$("#sp_link").before('<div class="thread" id ="thread_' + num + '"></div></br>');
						 	$("#thread_" + num).append('<div class="title"><h4>No:' + data[i].id + '  <a href="?page=list&id=' + data[i].id + '">' + data[i].title + '</a></h4></div>');
						 	if(data[i].name){
						 		var name = data[i].name;
						 	}else{
						 		var name = '名無しさん';
						 	}
						 	$("#thread_" + num).append('<div class="name">1 名前:<span class = "span_name_' + data[i].id + '" id=name>' + name + '</span><span class="date" id="date_' + data[i].id + '">投稿日時:' + data[i].created + '</span></div>');
						 	
						 	if(data[i].email){
						 		$(".span_name_" + data[i].id).wrap('<a href="mailto:' + data[i].email + '"></a>');
						 	}
						 	
						 	if(data[i].fb_url){
						 		$("#date_" + data[i].id).before('   <a href="' + data[i].fb_url + '">facebook</a>');
						 	}
						 	$("#thread_" + num).append('<div>' + data[i].description + '</div></br>');
						 	var id;
						 	if(data[i].responce != null){
						 		for(var n =0;n<data[i].responce.length;n++){
						 			$("#thread_" + num).append('<div class="comment" id="comment_' + num + '"></div>');
						 			if(data[i].responce[n].name){
						 				name = data[i].responce[n].name;
						 			}else{
						 				name = '名無しさん';
						 			}
						 			id = data[i].responce[n].id + 1;
						 			$("#comment_" + num).append('<div class="name">' + id + '名前:<span class = "span_name_c_' + data[i].id + '_' + data[i].responce[n].id + '" id=name>' + name + '</span><span class="date" id="date_' + data[i].id + '_' + data[i].responce[n].id + '">投稿日時:' + data[i].responce[n].created + '</span></div>');
						 			if(data[i].responce[n].email !=''){
						 				$(".span_name_c_" + data[i].id + '_' + data[i].responce[n].id).wrap('<a href="mailto:' + data[i].responce[n].email + '"></a>');
						 			}
						 			if(data[i].responce[n].fb_url != null){
						 				$("#date_" + data[i].id + '_' + data[i].responce[n].id).before('   <a href="' + data[i].responce[n].fb_url + '">facebook</a>');
						 			}
						 			$("#comment_" + num).append('<div>' + data[i].responce[n].description + '</div></br>');
						 		}
						 	}
						 	$("#thread_" + num).append('<div class="form" id="form_' + num + '">');
						 	//１０００件投稿フラグと,fbログイン状態を取得する何かを作る
						 	//$("#form_" + num).append('<div class="error" id="error_' + data[i].id + '"></div>');
						 	if(data[i].flag != 1){
									$("#form_" + num).append('<form method="POST" action="?regist=1&mode=sp" id = "input_comment_' + num + '"></form>');
									if(defaultName){
										$("#input_comment_" + num).append('<div id="form_name">名前<input type="text" name="name" value="' + defaultName + '"></div>');
									}else{
										$("#input_comment_" + num).append('<div id="form_name">名前<input type="text" name="name"></div>');
									}
									$("#input_comment_" + num).append('<div id="form_email">email<input type="email" name="email"></div>');
									$("#input_comment_" + num).append('<div>コメント</br><textarea name="comment" cols=40 rows=4></textarea></div>');
									$("#input_comment_" + num).append('<div><input type="submit" value="投稿"></div>');
									$("#input_comment_" + num).append('<input type="hidden" name="threadId" value="' + data[i].id + '"></div>');
									if(login.login && login.profile.link){
										$("#input_comment_" + num).append('<input type="hidden" name="fb_url" value="' + login.profile.link + '"></div>');
									}
							}
							if(!login.login){
								$("#form_" + num).after('<div id="fb_login"><a href="' + login.url + '">fbログイン</a></div>');
							}
							$("#thread_" + num).append('<div id="list_nav"><a href="?page=list&id=' + data[i].id + '">全て表示する</a>  <a href="?page=list&id=' + data[i].id + '&limit=50">最新50件</a>  <a href="?page=list&id=' + data[i].id + '&limit=100">1-100</a>  <a href="#">板のトップ</a>  <a href="">リロード</a></div>');
						 	num = num + 1;
						 }
						});
					});
					$("#sp_link").css('display','none');
			}
		</script>
	</head>
	<body onload="displayError()">
	<div id="main">
		<h3>掲示板</h3>
		<!-- スレッド一覧表示 -->
		<div id="thread_list">
			スレッド一覧</br>
			{foreach from=$title key=id item=val}
				<a href="?page=list&id={$val.id}">{$val.title}</a>    
			{/foreach}
		</div>
		</br>
		<!-- スレッド一覧表示ここまで -->
		<!-- インデックス表示 -->
		{foreach from=$list key=id item=val}
			<div class="thread" id="thread_{$id}">
				<div class="title"><h4>No:{$val.id}  <a href="?page=list&id={$val.id}">{$val.title}</a></h4></div>
				<div class="name">1 名前:{if $val.email}<a href='mailto:{$val.email}'>{/if}{if $val.name}<span id=name>{$val.name}</span>{else}<span id=name>名無しさん</span>{/if}{if $val.email}</a>{/if}
					{if $val.fb_url}<a href="{$val.fb_url}">facebook</a>{/if}   <span class="date">投稿日時:{$val.created}</span></div>
				<div>
					{$val.description}
				</div>
				</br>				
				<!-- ここから最大10件表示 -->
				{foreach from=$comment[$val.id] key=k item=c}
					<div class="comment" id="c_{$c.id}">
						<div class="name">{$c.id +1} 名前:{if $c.email}<a href='mailto:{$c.email}'>{/if}{if $c.name}<span id=name>{$c.name}</span>{else}<span id=name>名無しさん</span>{/if}{if $c.email}</a>{/if}  
							{if $c.fb_url}<a href="{$c.fb_url}">facebook</a>{/if}<span class="date">投稿日時:{$c.created}</span></div>
						<div>
							{$c.description}
						</div>
					</div>
					</br>
				{/foreach}
				<!-- for文ここまで -->
				  </br>
				<!-- ここからコメント投稿 -->
				<div class="form" id="form_{$id}">
					{if $flag[$k] !=1}
					<div class="error" id="error_{$val.id}"></div>
					<form method="POST" action="?regist=1" id = "input_comment">
						<div id="form_name">名前<input type="text" name="name" {if $profile.id}value="{$profile.id}"{/if} ></div>
						<div id="form_email">email<input type="email" name="email"></div>
						</br>
							<div id="text_area">{if $device !='android'}コメント</br>{/if}
								<textarea name="comment" cols=40 rows=4></textarea>
							</div>
						<div><input type="submit" value="投稿"></div>
						<input type="hidden" name="threadId" value="{$val.id}">
						{if $profile.link}<input type="hidden" name="fb_url" value="{$profile.link}">{/if}
					</form>
					<div id="fb_login">{if !$login}<a href="{$login_url}">fbログイン</a>{/if}</div>
					{else}
						このスレッドの書き込みは1000件を越えたので書き込みできません。
					{/if}
				</div>
				<div id="list_nav"><a href="?page=list&id={$val.id}">全て表示する</a>  <a href="?page=list&id={$val.id}&limit=50">最新50件</a>  <a href="?page=list&id={$val.id}&limit=100">1-100</a>
				  {if $device != 'pc'}</br>{/if}<a href="#">板のトップ</a>  <a href="">リロード</a></div>
				  </br>
				{if $device  == 'android'}
					</br>
					</br>
				{/if}
			</div>
			</br>
			<!-- ここまでコメント投稿 -->
		{/foreach}
		<!-- インデックス表示ここまで -->
		</br>
		<div id="sp_link" height="50%">もっと見る</a></div>
		<!-- スレッド作成遷移 -->
		<div id="footer"><a href="?page=thread">新規スレッド作成</a></div>
		<!-- スレッド作成遷移ここまで -->
	</div>
	</body>
</html>
