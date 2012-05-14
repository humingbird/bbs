//飛んできたパラメータに対してエラー表示を追加する
function displayError(){
	var param = getQueryString();

	if(param != null){
		if(param.comment == 1){
			var ele = document.createElement("div");
			var str = document.createTextNode('コメントを記入してください');
			ele.appendChild(str);

			document.getElementById('error').appendChild(ele);
		}else if(param.name == 2){
			var ele = document.createElement("div");
			var str = document.createTextNode('名前の文字数制限を越えています');
			ele.appendChild(str);

			document.getElementById('error').appendChild(ele);
		}else if(param.email ==2){
			var ele = document.createElement("div");
			var str = document.createTextNode('メールアドレスが長すぎます');
			ele.appendChild(str);

			document.getElementById('error').appendChild(ele);
		}else if(param.comment == 2){
			var ele = document.createElement("div");
			var str = document.createTextNode('コメントが長すぎます');
			ele.appendChild(str);

			document.getElementById('error').appendChild(ele);
		}else if(param.email == 3){
			var ele = document.createElement("div");
			var str = document.createTextNode('メールアドレスが正しくありません');
			ele.appendChild(str);

			document.getElementById('error').appendChild(ele);
		}else if(param.db == 1){
			var ele = document.createElement("div");
			var str = document.createTextNode('スレッド登録に失敗しました');
			ele.appendChild(str);

			document.getElementById('error').appendChild(ele);
		}
	}
	
}

//getパラメータを取得して配列の形で返す
function getQueryString(){
	if( 1 <document.location.search.length ){
		var query = document.location.search.substring(1);
		var parameters = query.split('&');

		var result = new Object();
		for( var i=0; i < parameters.length; i++){
			var element = parameters[i].split( '=');
			var paramName = decodeURIComponent( element[0] );
			var paramValue = decodeURIComponent( element[1] );

			result[ paramName ] = decodeURIComponent( paramValue );
		}

		return result;
	}
	return null;
}
