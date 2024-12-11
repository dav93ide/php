<?php
	include("db_functions.php");
	session_start();
	if(isset($_COOKIE['cookie']) OR isset($_SESSION['username'])){
		setcookie("cookie","",time()-3600);
		delete_user_online($_SESSION['username']);
		session_destroy();		
		header('location: index.html');
	}
	else{
		header("location: 404.html");
	}
?>
