<?php
$dsn = 'データベース名';
$user = '名前';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

$flag=0;

if(!empty($_POST['edit_no'])){
//編集内容をフォームに戻す
	$sql = "SELECT * FROM mission4 where id = :id";//データベース読みだし
	$stmt = $pdo -> prepare($sql);
	$stmt->bindParam(":id", $i, PDO::PARAM_INT);
	$i=$_POST['edit_no'];//編集先の配列番号を取得
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC); //配列としての呼び出し

	   if($result['pass'] == $_POST['edipass']){
	   //もし$result['pass']に入っている数字が$_POST['edipass']と等しいとき
		$edihidd = $result['id'];
		$ediname = $result['name'];
		$edicomm = $result['comment'];
	   //$edihiddと$edinameと$edicommという変数に$resultのID(行番号)と名前とコメントを入れる
	   }else{
		echo 'パスワードが間違っています'."<br>";
	   }
$flag=1;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "Content-Type" content = "text/html"; charset = "utf-8">
<title>mission_4-1</title>
</head>
<body>
<form action = "mission_4-1.php" method = "POST">
名前<input type = "text" value = "<?php echo $ediname;?>" name = "name"><br/>
コメント<input type = "text" value = "<?php echo $edicomm;?>" name = "comment"><br/>
パスワード<input type = 'text' value = "" name = "pass" >
<input type = "hidden" value = "<?php echo $edihidd;?>" name = "hide">
<input type = "submit" value = "送信"><br/><br/>
削除対象番号<input type = "text" value = "" name = "delete_no">
パスワード<input type = 'text' value = "" name = "delpass" >
<input type = "submit" value = "削除" name = "delete"><br/><br/>
編集対象番号<input type = "text" value = "" name = "edit_no">
パスワード<input type = 'text' value = "" name = "edipass" >
<input type = "submit" value = "編集" name = "edit">
</form>

<?php
if($flag==1){}
else if (!empty($_POST['delete_no'])) {
//削除対象番号欄が空で無いとき
//削除機能
	$sql1 ='SELECT * FROM mission4';
	$result = $pdo -> query($sql1);
	$count = 1;
	foreach($result as $row){
		$count += 1;
	}
	$sql = "SELECT * FROM mission4 where id = :id";//データベース読みだし
	$stmt = $pdo -> prepare($sql);
	$stmt->bindParam(":id", $i, PDO::PARAM_INT);
	$i=$_POST['delete_no'];//編集先の配列番号を取得
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC); //配列としての呼び出し
	if($result['pass']==$_POST['delpass']){ //パスワードが一致したとき
		$sql='SELECT * FROM mission4';
		$result=$pdo->query($sql);
	   	foreach($result as $row){
	           if($row['id']>$_POST['delete_no']&&$row['id']<$count){
		      $id=$row['id']-1;
   		      $name=$row['name'];
		      $comment=$row['comment'];
		      $time=$row['time'];
		      $pass=$row['pass'];
		      $sql="update mission4 set name='$name',comment='$comment',time='$time',pass='$pass' where id=$id";
		      $result=$pdo->query($sql);
		    }
	    }
		$id = $count-1;
		$sql = "delete from mission4 where id=$id";
		$result = $pdo->query($sql);

	}else{
		echo 'パスワードが間違っています'."<br>";
	}
}

else if(!empty($_POST['hide'])){
//編集機能
	$sql = "SELECT * FROM mission4 where id = :id";//データベース読みだし
	$stmt = $pdo -> prepare($sql);
	$stmt->bindParam(":id", $i, PDO::PARAM_INT);
	$i=$_POST['hide'];//編集先の配列番号を取得
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC); //配列としての呼び出し

	   if($result['pass'] == $_POST['pass']){
	   //もし$result['pass']に入っている数字が$_POST['pass']と等しいとき
		$ediname = $_POST['name'];
		$edicomm = $_POST['comment'];
		$editime = date("Y/m/d H:i:s");
		$sql = "update mission4 set name='$ediname' , comment='$edicomm',time='$editime' where id = $i";
		$result = $pdo->query($sql);

	}else{
		echo 'パスワードが間違っています'."<br>";
	}
}

else if(!empty($_POST['name']) and !empty($_POST['comment'])){
//名前欄とコメント欄が空で無いとき//
	$sql1 ='SELECT * FROM mission4';
	$result = $pdo -> query($sql1);
	$id = 1;
	foreach($result as $row){
		$id += 1;
	}
	$sql = $pdo->prepare("INSERT INTO mission4 (id,name,comment,time,pass) VALUES ('$id',:name,:comment,:time,:pass)");
	$sql->bindParam(':name', $name, PDO::PARAM_STR);
	$sql->bindParam(':comment', $comment, PDO::PARAM_STR);
	$sql->bindParam(':time', $time, PDO::PARAM_STR);
	$sql->bindParam(':pass', $pass, PDO::PARAM_STR);

	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$time = date("Y/m/d H:i:s");
	$pass = $_POST['pass'];
	$sql->execute();

}//名前とコメントが空では無いときのif文を閉じるかっこ

$sql = 'SELECT * FROM mission4';//表示
$results = $pdo -> query($sql);
foreach($results as $row){
//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['time'].',';
	echo $row['pass'].'<br>';
}

?>
</body>
</html>