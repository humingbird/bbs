<?php

/**
 * 複数のクラスで利用できるメソッドを置いておくクラス
 */
 
class Util{
	
	/**
	 * バリデーション処理
	 *
	 * @params array  $postData  postされたスレッドデータ
	 * @params string $page      エラー時にリダイレクトするページ名
	 * @return array 			チェックを通ったデータ
	 */
	public function checkParams($postData,$page=null){
			foreach($postData as $key=>$value){
				$postData[$key] = htmlspecialchars($value);
			}
			$this->checkUndefined($postData,$page);
			$this->countWords($postData,$page);
			$this->checkEmail($postData['email'],$page,$postData['threadId']);
			return $postData;
		}
	
	/**
	 * 必須項目の空欄チェック
	 *
	 * @params array  $postData  postされたスレッドデータ
	 * @params string $page      エラー時にリダイレクトするページ名
	 */
	public function checkUndefined($postData,$page){
		$check = false;

		if($postData['title'] === ""){
			$check = true;
			$errorComment['title'] = 1;
		}
		if($postData['comment'] === ""){
			$check = true;
			$errorComment['comment'] =1;
		}
		//引っかかった場合は警告コメントの種類判別のためにパラメータを付与してリダイレクト
		if($check){
			foreach($errorComment as $key=>$value){
				$error = $error.$key.'='.$value.'&';
			}
			$error = rtrim($error,'&');
			
			if($page && $page != 'list'){
				$param = '?page='.$page.'&'.$error;
			}else if($page === 'list'){
				$param = '?page='.$page.'&id='.$postData['threadId'].'&'.$error.'#error_'.$postData['threadId'];
			}else{
				$param = '?'.$error.'&id='.$postData['threadId'].'#error_'.$postData['threadId'];
			}
			header("Location:".Config::$home_url.$param);
			exit;
		}
	}
		
	/**
	 * 文字数制限チェック
	 *
	 * @params array $postData  postされたスレッドデータ
	 * @params string $page      エラー時にリダイレクトするページ名
	 */
	public function countWords($postData,$page){
		$check = false;
			
		if(array_key_exists('title',$postData)){
			if(mb_strlen($postData['title'])>100){
				$check = true;
				$errorComment['title']=2;
			}
		}
		if(mb_strlen($postData['name'])>30){
			$check = true;
			$errorComment['name']=2;
		}
		if(mb_strlen($postData['email'])>30){
			$check = true;
			$errorComment['email']=2;
		}
		if(mb_strlen($postData['comment'])>300){
			$check = true;
			$errorComment['comment']=2;
		}
		//引っかかった場合は警告コメントの種類判別のためにパラメータを付与してリダイレクト
		if($check){
			foreach($errorComment as $key=>$value){
				$error = $error.$key.'='.$value.'&';
			}
			$error = rtrim($error,'&');
			
			if($page && $page != 'list'){
				$param = '?page='.$page.'&'.$error;
			}else if($page === 'list'){
				$param = '?page='.$page.'&id='.$postData['threadId'].'&'.$error.'#error_'.$postData['threadId'];
			}else{
				$param = '?'.$error.'&id='.$postData['threadId'].'#error_'.$postData['threadId'];
			}
			header("Location:".Config::$home_url.$param);
			exit;
		}
	}
	
	/**
	 * メールアドレスかどうか（簡易的に）チェックする
	 *
	 * @params string $email     postされたメールアドレス
	 * @params string $page      遷移するページ名
	 * @params string $threadId  スレッドid
	 */
	public function checkEmail($email,$page,$threadId){
		$check = false;
		
		if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email) && $email) {
			$check = true;
			$errorComment['email'] = 3;
		}
		//引っかかった場合は警告コメントの種類判別のためにパラメータを付与してリダイレクト
		if($check){
			foreach($errorComment as $key=>$value){
				$error = $error.$key.'='.$value.'&';
			}
			$error = rtrim($error,'&');
			
			if($page && $page != 'list'){
				$param = '?page='.$page.'&'.$error;
			}else if($page === 'list'){
				$param = '?page='.$page.'&id='.$threadId.'&'.$error.'#error_'.$threadId;
			}else{
				$param = '?'.$error.'&id='.$threadId.'#error_'.$threadId;
			}
			header("Location:".Config::$home_url.$param);
			exit;
		}
	}
	
	/**
	 * 改行追加とコメント中にurlのようなものがあったらリンクを張る
	 *
	 * @params array   $commentList  コメント一覧
	 * @return arrya                 処理の終わったコメント一覧
	 */
	public function checkCommentLink($commentList){
		foreach($commentList as $key=>$val){
			//urlらしきものが入っていたらリンクタグを追加
			$escape = preg_quote('-._~%:/?#[]@!$&\'()*+,;=', '/');
			$pattern = '/((http|https):\/\/[0-9a-z' . $escape . ']+)/i';
			$req = '<a href="\\1">\\1</a>';
				
			$commentList[$key]['description'] = preg_replace($pattern,$req,$val['description']);
		}
		return $commentList;
	}
	/**
	 * 改行</br>追加
	 *
	 * @params array $comment  コメントデータ
	 * @return array           チェックしたコメント
	 */
	public function checkComment($comment){
		foreach($comment as $key=>$value){
			$comment[$key]['description'] = preg_replace('/\\n/','</br>',$value['description']);
		}
		return $comment;
	}
	
	/**
	 * コメントを表示用にソートする
	 * @params array  $comment  コメントデータ
	 * @params string $limit    表示件数（指定がない場合は10件）
	 * @return array            表示件数分のコメント
	 */
	public function sortComment($comment,$limit =10){
		foreach($comment as $key=>$value){
			$date[$key]= $value['created'];
		}
		//投稿時刻でソートする
		arsort($date);
		//指定件数分で区切る
		$sorted = array_chunk($date,(int)$limit,TRUE);
		//もっかいソートする
		asort($sorted[0]);
		
		foreach($sorted[0] as $key=>$value){
			$result[] = $comment[$key];
		}
		return $result;
	}
	
}
 
