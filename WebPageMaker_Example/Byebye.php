<?php
	session_start();
	include('DB_Functions.php');
	if(isset($_COOKIE['cookie'])){
		setcookie("cookie","",time()-3600);
	}
	if(!isset($_SESSION['username'])){
		header("location: 404.php");
	}
	session_destroy();
	header('location: index.php')
?>