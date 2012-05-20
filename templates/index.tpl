<!DOCTYPE html> 
<html>
	<head>
		<title>掲示板</title>
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
						//新規スレッドの下に新しく１０件分追加する
						var num = 10;
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
						 	for(var n =0;n<data[i].responce.length;n++){
						 		$("#thread_" + num).append('<div class="comment" id="comment_' + num + '"></div>');
						 		if(data[i].responce[n].name !=null){
						 			name = data[i].responce[n].name;
						 		}else{
						 			name = '名無しさん';
						 		}
						 		id = data[i].responce[n].id + 1;
						 		$("#comment_" + num).append('<div class="name">' + id + '名前:<span class = "span_name_c_' + num + '" id=name>' + name + '</span><span class="date" id="date_' + num + '">投稿日時:' + data[i].responce[n].created + '</span></div>');
						 		if(data[i].responce[n].email !=''){
						 			$(".span_name_c_" + num).wrap('<a href="mailto:' + data[i].responce[n].email + '"></a>');
						 		}
						 		if(data[i].responce[n].fb_url){
						 			$("#date_" + num).before('   <a href="' + data[i].responce[n].fb_url + '">facebook</a>');
						 		}
						 		$("#comment_" + num).append('<div>' + data[i].responce[n].description + '</div></br>');
						 	}
						 	$("#thread_" + num).append('<div id="list_nav"><a href="?page=list&id=' + data[i].id + '">全て表示する</a>  <a href="?page=list&id=' + data[i].id + '&limit=50">最新50件</a>  <a href="?page=list&id=' + data[i].id + '&limit=100">1-100</a>  <a href="#">板のトップ</a>  <a href="">リロード</a></div>');
						 	$("#thread_" + num).after('<div class="form" id="form_' + num + '">');
						 	//１０００件投稿フラグと,fbログイン状態を取得する何かを作る
						 	/*$("#form_" + num).append('<div class="error" id="error_' + data[i].id + '"></div>');
							$("#form_" + num).append('<form method="POST" action="?regist=1" id = "input_comment_' + num + '"></form>');
							$("#input_comment_" + num).append('<div id="form_name">名前<input type="text" name="name"></div>');
							$("#input_comment_" + num).append('<div id="form_email">email<input type="email" name="email"></div>');
							$("#input_comment_" + num).append('<div>コメント</br><textarea name="comment" cols=40 rows=4></textarea></div>');
							$("#input_comment_" + num).append('<div><input type="submit" value="投稿"></div>');
							$("#input_comment_" + num).append('<input type="hidden" name="threadId" value="' + data[i].id + '"></div>');*/
						 	num = num + 1;
						 }
					});
					$("#sp_link").css('display','none');
			}
		</script>
	</head>
	<body onload="displayError()">
		<h3>掲示板</h3>
		<!-- スレッド一覧表示 -->
		<div id="thread_list">
			スレッド一覧</br>
			{foreach from=$title key=id item=val}
				<a href="?page=list&id={$val.id}">{$val.title}</a>    
			{/foreach}
		</div>
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
				<div id="list_nav"><a href="?page=list&id={$val.id}">全て表示する</a>  <a href="?page=list&id={$val.id}&limit=50">最新50件</a>  <a href="?page=list&id={$val.id}&limit=100">1-100</a>
				  <a href="#">板のトップ</a>  <a href="">リロード</a></div>
			</div>
			<!-- ここからコメント投稿 -->
			<div class="form" id="form_{$id}">
				{if $flag[$k] !=1}
				<div class="error" id="error_{$val.id}"></div>
				<form method="POST" action="?regist=1" id = "input_comment">
					<div id="form_name">名前<input type="text" name="name" {if $profile.id}value="{$profile.id}"{/if} ></div>
					<div id="form_email">email<input type="email" name="email"></div>
					<div>コメント</br>
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
			<!-- ここまでコメント投稿 -->
		{/foreach}
		<!-- インデックス表示ここまで -->
		<div id="sp_link" style="border:solid;height:200px;">もっと見る</a></div>
		<!-- スレッド作成遷移 -->
		<div id="footer"><a href="?page=thread">新規スレッド作成</a></div>
		<!-- スレッド作成遷移ここまで -->
	</body>
</html>
