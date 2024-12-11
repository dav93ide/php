<html>
	<head>
		<title> Registrazione </title>
		<link href="ConPiùStile.css" rel="stylesheet" type="text/css">
		<style>
			body{
				background:url(img/peace.jpg);
				background-position:0 50;
				-moz-background-size: cover;
				-webkit-background-size: cover;
				background-size: cover;
			}
		</style>
	</head>
	<body>
		<?php
			session_start();
			echo "<center><font color='red'; size=5> "  . $_SESSION['error'] . "</font></center>";
		?>
		<form action="aggiorna.php" method="POST">
				<div id="main">
				<center><h1> Registrazione </h1></center>
				<div id="login">
				<center><label> Nome Utente: </label> <br/>
				<input id="name" name="username" placeholder="username" type="text"> <br/><br/><br/>
				<label> Password Utente: </label> <br/>
				<input id="password" name="password" placeholder="******" type="password"><br/><br/><br/>
				<input name="registration" type="submit" value="ReGiStRaTiOn"></center>
		</form>
		<br/><br/><br/><br/><br/>
		<?php
			echo "<p><font color='white'; size=3><center>Clicca <a href=\"index.php\" style=\"color:white\"> QUI </a> per tornare all'index.</center></p>";
			$_SESSION['error'] = "";
		?>
		</div></div>
	</body>
</html>