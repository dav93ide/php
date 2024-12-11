<?php
	session_start();
	include('DB_Functions.php');
	function __autoload($nomeClasse){
		require_once($nomeClasse.".php");
	}
	if(isset($_COOKIE['cookie'])){
		setcookie("cookie","",time()-3600);
	}
	if(isset($_SESSION['utente'])){
		deleteUserOnline($_SESSION['utente']->getName());
	}
	else{
		header("location: 404.php");
	}
	session_destroy();
?>
<html>
	<head>
		<title> ! Bye Bye ! </title>
		<link href="CssLayouts.css" rel="stylesheet" type="text/css">
	</head>
	<body id="blackBody">
	<div id="topImage"> </div>
		<center> <br/> <br/>
			<font color=blue size=5> 
				7eMMeZZo Casin&ograve Royal 
			</font> <br/> <br/>
			<font color=yellow size=4>
					Spera Che Tornerai Presto A Trovarci! </font> <br/> <br/><br/><br/><br/>
			</font>
			<font color=blue size=3>
				Ritorna Alla Pagina Principale:
			</font>
			<br/>
			<font color=yellow size=3>
				<a href="MainPage.php"> Link </a>
			</font>
		</center>
	</body>
</html>