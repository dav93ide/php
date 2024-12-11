<html>
	<head>
	<style>
		body{
			background:url(img/kali.jpg);
			background-position:0 50;
			-moz-background-size: cover;
			-webkit-background-size: cover;
			background-size: cover;
		}
		img{
			border:3px solid #000;
			width:133px;
			height:66px;
		}
	</style>
		<title> HomePage </title>
		<link href="ConPiùStile.css" rel="stylesheet" type="text/css">
	</head>
	<body>

		<?php
			session_start();
			if($_SESSION['log'] == TRUE){								//Se Loggato Continua
				echo $_SESSION['error'];
				echo "<br/> <center><font color='white'; size=20;>Benvenuto Utente: " . $_SESSION['user'] . "! <br/> :D </center></font><br/>";
				echo "<br/><br/><br/> <center><font color='white'; size=20;>!HomePage! </font> </center>";
				echo '<img src=\''.$_SESSION['img'].'\' align="right" title="Immagine Profilo">
					<form action="index.php" method="GET">
						<br/><br/><br/><br/><br/>
						<input id="logout" name="LogOut" type="submit" value="LoG-OuT">
					</form>';
				if($_SESSION['user']=='root'){							//Se admin
					printf('<br/><br/><font color="white"; size=4;> Sei Admin Del DB, Puoi Inserire Ed Eseguire Una Query Scrivendola Qui Sotto. </font>
						<form action="query.php" method="GET">
							<input id="query" name="query" placeholder="query" type="text"><br/>
							<input id="SubmitQuery" name="SubmitQuery" type="submit" value="Submit-Query"<br/>
						</form>');
				}
				echo '<br/><br/><br/><br/><font color="white" size=4>Cambia Immagine Profilo: </font>
					<form action="changeimg.php" method="GET">
						<input id="SubmitImg" name="SubmitImg" type="submit" value="Change-Img"<br/><br/>
						<font color="white" size=2>!!Le Immagini Più Grandi Di +- 
							2Mb Possono Creare Problemi Di Memorizzazione!! </font> <br/><br/>
					</form>';
				echo "<br/><br/><right><font color='white' size=4>".$_SESSION['error']."</right></font>";
				$_SESSION['error'] = '';	
			}
			else{
				sleep(5);
				$_SESSION['error'] = "Non Puoi Loggare Nell'Homepage Senza Prima Aver Effettuato L'accesso...";
				header("Location: index.php");
			}
	?>
	</body>
</html>
