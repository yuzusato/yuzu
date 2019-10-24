<?php
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	$sql = "CREATE TABLE IF NOT EXISTS BulletinBoard"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "pass char(25),"
	. "time DATETIME"
	.");";
	$time = date('Y/n/j H:i:s');	
	$stmt = $pdo->query($sql);
	
	$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";
	
	$sql ='SHOW CREATE TABLE BulletinBoard';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";
	
	
//投稿機能
if( !empty($_POST["name"]) && ( !empty ($_POST["comment"]))){
		
		if(!empty($_POST["showedit"])) { 
 	//編集モード
		$ShowNum =$_POST["showedit"];
		$id = $ShowNum; //変更する投稿番号
		$name = $_POST["name"];
		$comment = $_POST["comment"]; //変更したい名前、変更したいコメントは自分で決めること
		$sql = 'update BulletinBoard set name=:name,comment=:comment, time=:time where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt ->bindParam(':time', $time, PDO::PARAM_STR);
		$stmt->execute();
		
		}else{
		
		//新規投稿モード
		$sql = $pdo -> prepare("INSERT INTO BulletinBoard (name, comment, pass, time) VALUES (:name, :comment, :pass, :time)");
		$name = $_POST["name"];
		$comment = $_POST["comment"];
		$pass=$_POST["password0"];
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
		$sql -> bindParam(':time', $time, PDO::PARAM_STR);
		$sql -> execute();
		}}
//投稿機能終わり

//編集機能
if(!empty($_POST["editnum"]) && (!empty($_POST["password2"]))){
	$id= $_POST["editnum"];
	$decide = $_POST["password2"];
	$sql = "SELECT * FROM BulletinBoard where id=$id";
	// SQLステートメントを実行し、結果を変数に格納
	$stmt = $pdo->query($sql);
	// foreach文で配列の中身を一行ずつ出力
		foreach ($stmt as $row) {
			$pass2=$row["pass"];
				if($pass2==$decide){
					$value0= $id;
					$value1= $row["name"];
					$value2=$row["comment"];
}}}

//削除機能
if(!empty($_POST["delnumber"]) && (!empty($_POST["password1"]))){
	$delete = $_POST["password1"];
	$id = $_POST["delnumber"];
	
	$sql = "SELECT * FROM BulletinBoard where id=$id";
	// SQLステートメントを実行し、結果を変数に格納
	$stmt = $pdo->query($sql);
	// foreach文で配列の中身を一行ずつ出力
		foreach ($stmt as $row) {
			$DELETE=$row["pass"];
				if($DELETE==$delete){
					$sql = 'delete from BulletinBoard where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->execute();
}}}
?>
	
<!DOCTYPE html>
<head>
<meta charset ="utf-8">
<title>mission_5.php</title>
</head>
<body>
<form action = "mission_5.php" method = "POST">
<h4>投稿フォーム:<h4>
    <input type = "text" name="name" cols="10"  placeholder="名前"
        <?php
         if(isset($value1))
         echo "value=\"".$value1."\"";
         ?>
    >
    <input type = "text" name="comment"  cols="30" placeholder="コメント"
        <?php
        if(isset($value2))
        echo "value=\"".$value2."\"";
        ?> 
    >
    <input type = "text" name="password0" cols="10"  placeholder="パスワード">
      <?php
         if(isset($password0))
         echo "value=\"".$password0."\"";
         ?>
    <input type = "hidden" name="showedit"
        <?php
        if(isset($value0))
        echo "value=\"".$value0."\"";
        ?>
     >
    <p><input type ="submit" name="return" value ="送信"></p>
<h4>削除番号指定用フォーム:</h4>
    <input type="text" name="delnumber" placeholder="削除対象番号（半角数字)"></p>
    <input type = "text" name="password1" cols="10"  placeholder="パスワード"
    <?php
         if(isset($password1))
         echo "value=\"".$password1."\"";
         ?>
    >
    <p><input type="submit" value="送信"></p>
<h4>編集番号指定用フォーム:</h4>
    <input type="text" name="editnum" placeholder="編集対象番号（半角数字)"></p>
    <input type = "text" name="password2" cols="10"  placeholder="パスワード"
     <?php
         if(isset($password2))
         echo "value=\"".$password2."\"";
         ?>
         >
    <p><input type="submit" value="編集"></p>

</form>
</body>
</html>

<?php
//表示
	$sql = 'SELECT * FROM BulletinBoard';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['pass'].',';
		echo $row['time'].'<br>';
	echo "<hr>";
	}	
?>	