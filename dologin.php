<?php
	include_once 'functions.php';
	opendb();
	$email=mysql_real_escape_string(htmlentities($_POST['email']));
	$pass=mysql_real_escape_string(htmlentities($_POST['password']));
	
	$msg='Your email or password were incorrect or your account is not yet validated';
	
	$query="SELECT * FROM users WHERE Email='$email' AND Validated='1'";
	$result=mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($result)==1){
		$row=mysql_fetch_assoc($result);
		$salted=sha1($pass.$row['Salt']);
		if($salted==$row['Password']){
			$userid=$row['UserID'];
			//Login
			$sesskey=sha1(mt_rand());
			$query="SELECT * FROM sessions WHERE SessionKey='$sesskey'";
			$result=mysql_query($query) or die(mysql_error());
			
			while(mysql_num_rows($result)>1){
				$sesskey=sha1(mt_rand());
				$query="SELECT * FROM sessions WHERE SessionKey='$sesskey'";
				$result=mysql_query($query) or die(mysql_error());
			}
			
			$time=time()+3600;
			
			$ip=$_SERVER['REMOTE_ADDR'];
			
			$query="INSERT INTO sessions (SessionKey, UserID, SessionTimeout, IP) VALUES ('$sesskey', '$userid', '$time', '$ip')";
			mysql_query($query) or die(mysql_error());
			
			setcookie("user", $userid, $time);
			setcookie("sessionkey", $sesskey, $time);
			$loggedin=1;
		}
	}
	
	closedb($conn);
	include 'index.php';
?>