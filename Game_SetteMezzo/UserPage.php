<?php
	session_start();
	include('Serialization_&_File_Functions.php');
	include('DB_Functions.php');
	function __autoload($nomeClasse){
		require_once($nomeClasse.".php");
	}
	if(!isset($_SESSION['utente'])){
		header("location: 404.php");
	}
	updateOnline($_SESSION['utente']->getName());
	delOffline();
	$_SESSION['computer'] = unserialize(readFromFileSerializedObject("dataDir/C0MPUT3R.dat"));
	unset($_SESSION['userCards']);
	unset($_SESSION['computerCards']);
	unset($_SESSION['user2Cards']);
	unset($_SESSION['starting']);
	unset($_SESSION['MexPass']);
	unset($_SESSION['randCards']);
?>
<html>
	<head>
		<title>	User Page </title>
		<link href="CssLayouts.css" rel="stylesheet" type="text/css">
		<meta http-equiv="refresh" content="30" URL="UserPage.php">
	</head>
	<body id="blackBody">
		<div id="topImage"> </div>
		<div id="topMenuLog">
			<?php
				echo "<font color=yellow size= 4> Username: </font> 
				<font color=white size=4>" . $_SESSION['utente'] -> getName() . "</font> <br/>
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
				echo "
					</font> <br/><br/><br/>
					<font color=red size=4>
						Seleziona Modalit&aacute Di Gioco: <br/> <br/>
					</font>
					<button id=\"voidButton\" onclick=\"SinglePlayer()\"> Player VS C0|\/|PU73R </button> <br/>
					<button id=\"voidButton\" onclick=\"TwoPlayers()\"> 2Players VS C0|\/|PU73R </button>
					<br/><br/><br/>
					<p id=\"stamp1\"> </p>";
			?>
		</div>
		<div id="rightMain">
			<font color=blue size=5> 
				<b> 7eMMeZZo Casin&ograve Royal </b> <br/>
			</font>
			<?php
				echo "<br/><br/><div class='dueColonne'>".$_SESSION['utente'] -> toString();
				// Stampa Statistiche Computer
				echo $_SESSION['computer'] -> toString() . "</div>";
				// Stampa Eventuali Errori
				if(isset($_SESSION['Error'])){
					echo "<br/> <br/> <br/><b><font color=red size=4> Attenzione Abbiamo Riscontrato L'Errore: </font></b><br/><br/>" . $_SESSION['Error'] . "<br/>";
					unset($_SESSION['Error']);
				}
				// Bottone Fine Partita Clickato
				if(isset($_GET['bttn3'])){
					// Se L'Utente Ha Iniziato Una Partita E Abbandona Prima Della Fine Conta Come Sconfitta (SinglePlayer)
					if(isset($_SESSION['gotTheWin']) && $_SESSION['gotTheWin'] == 1) {
						$_SESSION['utente'] -> addNSconfitte($_SESSION['puntata']);
						$_SESSION['utente'] -> addPortafogli(-$_SESSION['puntata']);
						$_SESSION['computer'] -> addNVittorie($_SESSION['puntata']);
						echo "<br/>[+] Vittoria Assegnata Al Computer. [+]";
					}
					serializzaOggetti();
					unset($_SESSION['gotTheWin']);
				}
				unset($_SESSION['puntata']);
				unset($_SESSION['gotTheWin']);
			?>
		</div>
		<div id="bottomInfo">
			<c1>Gioco vietato ai minori di 18 anni, pu&ograve causare dipendenza patologica.</c1>
			<c2> 7eMMeZZo Casin&ograve Royal &copy All Rights Reserved </c2>
		</div>
	</body>
</html>

<script type="text/javascript">
	function SinglePlayer(){
		document.getElementById("stamp1").innerHTML = "<font color=white>[*] Giocatore Singolo:</font><br/><br/><form action='Check.php' method='GET'><input type='text' id='text1' name='schei' placeholder='Inserisci Una Puntata' /><br/><br/><input type='SUBMIT' id=\"button2\" name='bttn1' value='Avvia Partita' /></form><br/>";
	}
	
	function TwoPlayers(){
		document.getElementById("stamp1").innerHTML = "<font color=white>[*] Due Giocatori:</font><br/><br/><form action='Check.php' method='GET'><input type='text' id='text1' name='schei' placeholder='Inserisci Una Puntata' /><br/><br/><input type='SUBMIT' id=\"button2\" name='2players' value='Avvia Partita' /></form><br/>";
	}
</script>