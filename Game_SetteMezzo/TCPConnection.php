<?php
	session_start();
	if(!isset($_SESSION['utente'])){
		header('location: 404.php');
	}
	function __autoload($nomeClasse){
		require_once($nomeClasse.".php");
	}
	include('DB_Functions.php');
	include('TCPFunctions.php');
	$answer = sendGameRequest();
?>
<html>
	<head>
		<title> Searching Players... </title>
		<meta http-equiv="refresh" content="5" URL="TCPConnection.php">
		<link href="CssLayouts.css" rel="stylesheet" type="text/css">
	</head>
	<body id="blackBody">
	<div id="topImage"> </div>
	<div id="topMenuLog">
			<?php
				echo "<font color=yellow size= 4> Username: </font> 
				<font color=white size=4>" . $_SESSION['utente'] -> getName() . "</font> <br/> <br/>
				<form action=\"Check.php\" method=\"GET\">
					<input type=\"submit\" id=\"button1\" name=\"logout\" value=\"Log-Out\"/>
				</form>";
			?>
		</div>
		<div id="leftMain">
			<?php
				$users = getOnlineUsers();
				echo " <font color=white size=2>Utenti Online:";
				foreach($users as $u){
					echo "<br/>" . $u;
				}
			?>
		</div>
		<div id="rightMain">
			<?php
				if($answer==False){
					echo "<br/><br/><br/><br/><font color =red size=5> Giocatore Non Trovato  </font> <br/> <br/> 
					<font color=#0F0 size=4> Attendere Prego </font> <br/> <br/> 
					<font color=#05F size=3> Ritentativo Tra 5 Secondi... </font>";
				}
				else{
					if($answer[0] != null && $answer[1] != null){
						$_SESSION['userTwo'] = substr($answer[0],1);
						$_SESSION['gameID'] = $answer[1];
						if($answer[0][0] == '1'){
							$_SESSION['starting'] = True;
							sleep(3);
						}
						else{
							$_SESSION['userTwo'] = substr($answer[0],1);
							$_SESSION['starting'] = False;
						}
						header('location: TwoPlayersGame.php');
					}
					else{
						echo "<br/><br/><br/><br/><font color=red size=5> Nessuna Risposta Dal Server. </font> <br/> <br/><br/><br/>
						<font color=#AAAAAA size=2> Questa Pagina Si Ricaricher&agrave Ogni 5 Secondi. </font> <br/> <br/><br/> <br/><br/> <br/><br/> <br/>
						<font color=#00F size=3> Ritorna Alla Pagina Principale: </font> <br/>
						<a href='UserPage.php'> Link </a>";
					}
				}
			?>
		</div>		
	</body>
</html>
		