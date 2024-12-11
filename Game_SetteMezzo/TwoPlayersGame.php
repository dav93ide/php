<?php
	session_start();
	include('Serialization_&_File_Functions.php');
	include('TCPFunctions.php');
	function __autoload($nomeClasse){
		require_once($nomeClasse.".php");
	}
	// Genero Vettore Carte E Lo Mescolo
	if(!isset($_SESSION['randCards'])){
		$_SESSION['randCards'] = range(0,39);
		shuffle($_SESSION['randCards']);
	}
	if(!isset($_SESSION['gameID'])){
		$_SESSION['Error'] = "Impossibile Trovare ID Partita...";
		header("location: UserPage.php");
	}
	if(!isset($_SESSION['utente'])){
		header("location: 404.php");
	}
	if(!isset($_SESSION['puntata'])){
		$_SESSION['Error'] = "Devi Definire Una Puntata Per Poter Giocare";
		header("location: UserPage.php");
	}
	// Se Oggetti "Cards" Per Utente1/2-Computer Non Esistono Li Creo
	if(!isset($_SESSION['userCards'])){
		$_SESSION['userCards'] = new Cards();
		$_SESSION['gotTheWin'] = 1;										// 2= Gioc2 Escluso 1=Partita In Corso 0=Partita Finita
	}
	if(!isset($_SESSION['computerCards'])){
		$_SESSION['computerCards'] = new Cards();
	}
	if(!isset($_SESSION['user2Cards'])){
		$_SESSION['user2Cards'] = new Cards();
	}
	// Passa Turno
	if(isset($_REQUEST['bttn4']) && $_SESSION['starting'] == True){
		$_SESSION['starting'] = False;
		sendPass($_SESSION['gameID']);
		sleep(5);
		header("location: TwoPlayersGame.php");
	}
	// Bottone Richiedi Carta Clickato
	if(isset($_GET['bttn2']) && $_SESSION['starting'] == True) {
		$nCarta = randCard();											// Vedi Sotto, "randCard()"
		$_SESSION['userCards'] -> addCard($nCarta);		
		unset($_SESSION['MexPass']);
	}
	// Bottone Fine Partita
	if(isset($_REQUEST['bttn3'])){
		// Aggiorno Valori Utente
		$_SESSION['utente'] -> addPortafogli(-$_SESSION['puntata']);
		$_SESSION['utente'] -> addNSconfitte($_SESSION['puntata']);
		// Se Utente2 Aveva Già Abbandonato O Vi E' Un Messaggio "Ended" Accodato
		if($_SESSION['gotTheWin'] == 2 || (($_SESSION['gotTheWin'] == 1)?((checkCards($_SESSION['gameID']))?((readCards($_SESSION['gameID']) == "Ended")?True:False):False):False)){
			$puntataGioc2 = moneyRequest($_SESSION['userTwo'],$_SESSION['gameID']);				// Aggiorno Valori Computer
			$_SESSION['computer'] -> addNVittorie($_SESSION['puntata'] + $puntataGioc2);
			sendEndGame($_SESSION['gameID']);								// Invio Segnale Fine Partita
			serializzaOggetti();
		}
		else{
			endGamePressed($_SESSION['gameID']);							// Invio Segnale "Abbandonato La Partita In Corso"
			serializzaPlayer();
		}
		// Impedisce Che Vada A Rileggere Il Suo Stesso Messaggio Di "lasciato la partita"
		$_SESSION['gotTheWin'] = 0;			
		echo '<script>
				alert("Partita Abbandonata,\n Vittoria Assegnata Al Computer. ");
				window.location.href = "UserPage.php";
			</script>';
	}
	$valoreAttualeUtente = $_SESSION['userCards'] -> getValoreAttuale();
	$valoreAttualeComputer = $_SESSION['computerCards'] -> getValoreAttuale();
	$valoreAttualeUtente2 = $_SESSION['user2Cards'] -> getValoreAttuale();
	// Blocco Pescaggio Computer
	if($valoreAttualeComputer < $valoreAttualeUtente && $valoreAttualeComputer < 7.5 
	&& $valoreAttualeUtente <= 7.5){
		$vetPescateBanco = array();
		while($valoreAttualeUtente > $valoreAttualeComputer){
			$nCartap = randCard();
			array_push($vetPescateBanco,$nCartap);
			$_SESSION['computerCards'] -> addCard($nCartap);
			$valoreAttualeComputer = $_SESSION['computerCards'] -> getValoreAttuale();
		}
		$valoreAttualeComputer = $_SESSION['computerCards'] -> getValoreAttuale();
	}
	// Se giocatore 2 E' Fuori Gioco
	if($_SESSION['gotTheWin'] != 2){
		// Se E' Settata Una Carta Pescata E Il Giocatore E' In Fase Di Pescaggio
		if($_SESSION['starting'] == True && isset($nCarta)){
			if(!isset($vetPescateBanco)){								
				$vetPescateBanco = "";
			}
			$_SESSION['starting'] = False;
			sendCards($nCarta,$vetPescateBanco,$_SESSION['gameID']);					// Invio Carte Al Server
			unset($nCarta);
			sleep(1);
		}
		else{	// Se Vi Sono Carte Da Recuperare E Il Giocatore E' In Fase Di Attesa
			if(checkCards($_SESSION['gameID']) && $_SESSION['starting'] == False){
				$_SESSION['starting'] = True;
				$arr = readCards($_SESSION['gameID']);				// Leggo Le Carte
				if($arr != "Pass" && $arr != "Ended"){				// Se Non Ho Recuperato Un "Ended" O Un "Pass"
					$userCard = $arr[0];							// Prima Carta Del Giocatore
					$_SESSION['user2Cards'] -> addCard($userCard);
					foreach($arr as $v){							// Successive Del Computer
						if($v != $userCard){
							$_SESSION['computerCards'] -> addCard($v);
						}
					}
				}
				else{	
					if($arr == "Pass"){
						$_SESSION['MexPass'] = "[*] Gioc2 Ha Passato Il Turno [*]";
					}
					else{
						$_SESSION['MexPass'] = "[*] Gioc2 Ha Abbandonato la Partita [*]";
						$_SESSION['gotTheWin'] = 2;
					}
				}
				header("location: TwoPlayersGame.php");
			}		
		}
	}
?>

<html>
	<head>
		<title> TwO Players Game </title>
		<link href="CssLayouts.css" rel="stylesheet" type="text/css">
		<link href="CssCards.css" rel="stylesheet" type="text/css">
		<meta http-equiv="refresh" content="
			<?php echo (($_SESSION['starting'] == False) ? 2 : 5); ?>
			" URL="UserPage.php">
	</head>
	<body id="blackBody">
		<div id="topImage"> </div>
		<div id="topMenuLog" style="height :  5%;">
			<?php
				echo "
				<font color=yellow size= 4> Username: </font> 
				<font color=white size=4>" . $_SESSION['utente'] -> getName() . "</font> <br/> <br/>";
			?>
		</div>
		<div id="leftMain" style="width: 24%;height: 99%;">
			<?php
				echo "<br/><font color=red size=2>[+] Scommessa => " . $_SESSION['puntata'] . " &euro; [+] <br/> </font>
				<font color=yellow size=2>[+] Portafogli => " . $_SESSION['utente'] -> getPortafogli() . " &euro; [+] <br/></font>
				<font color=green size=2>[+] Giocatore2 => " . $_SESSION['userTwo'] . " [+] </font> <br/> <br/>";
			?>
			<font color=red size=5> Seleziona Un'Opzione: </font> <br/> <br/>
			<form action='' method='GET'>
			<?php
				if($_SESSION['starting'] == True && $_SESSION['gotTheWin'] != 0){
					echo"<input type='SUBMIT' id='button2' name='bttn2' value='Richiedi Carta'/>";
				}
				else{
					echo"<font color=white size=2> In Attesa Che L'Altro Giocatore Peschi... </font>";
				}
				if($_SESSION['starting'] && $valoreAttualeUtente > $valoreAttualeUtente2 && $valoreAttualeUtente <= 7.5 && $valoreAttualeComputer >= 7.5 && $_SESSION['gotTheWin'] == 1){
					echo"<br/><input type='SUBMIT' id='button2' name='bttn4' value='Passa Turno'/>";
				}
			?>
				<br/><br/><input type='SUBMIT' id='button2' name='bttn3' value='Fine Partita' /> 
			</form>
		</div>
		<div id="rightMain" style="width: 75%;height: 100%;">
				<?php
					// Stampo eventuali Messaggi
					if(isset($_SESSION['MexPass'])){
						echo "<font size=2> " . $_SESSION['MexPass'] . "<br/> </font>";
					}
					if(($_SESSION['userCards'] -> getCardsNumber()) > 0 || ($_SESSION['user2Cards'] -> getCardsNumber()) >0){
						$vetCarteUtente = $_SESSION['userCards'] -> getCards();
						$vetCarteBanco = $_SESSION['computerCards'] -> getCards();
						$vetCarteUtente2 = $_SESSION['user2Cards'] -> getCards();
						echo "<font color='blue' size=2>[+] Valore Attuale Delle Tue Carte => " .  $valoreAttualeUtente . 
							" [+] </font> <br/>";
						echo "<font color='red' size=2>[+] Valore Attuale Delle Carte User2 => " .  $valoreAttualeUtente2 . 
							" [+] </font> <br/>";
						echo "<font color='green' size=2>[+] Valore Attuale Delle Carte Del Computer => " .  $valoreAttualeComputer . 
							" [+] </font><br/>";
						$vetCarteUtente = $_SESSION['userCards'] -> getCards();
						$vetCarteBanco = $_SESSION['computerCards'] -> getCards();
						$vetCarteUtente2 = $_SESSION['user2Cards'] -> getCards();
						// Stampo Carte Utente
						echo "<br/> <table><tr><font color='blue' size=2> Le Tue Carte :</tr> <tr> ";
						foreach($vetCarteUtente as $k){
							echo "<td> <p id=".$k.">&nbsp; </p> </td>";
						}
						// Stampo Carte Utente2
						echo "<table><font color='blue' size=2>Carte Giocatore2:<tr> ";
						foreach($vetCarteUtente2 as $k){
							echo "<td> <p id=".$k.">&nbsp; </p> </td>";
						}
						// Stampo Carte Computer
						echo "</tr></table><table><br/><tr>Carte Del Banco :</tr><tr>";
						foreach($vetCarteBanco as $k){
							echo "<td><p id=".$k.">&nbsp; </p></td>";
						}
						echo "</font></tr></table>";
					}
					else{
						echo "<font style=\"font-size:20; font-family:'Segoe Script';\">
							<br/>Connesso Con => <font color=red>". $_SESSION['userTwo']."</font>
							<br/><br/>
							<b><font size = 30> * </font><font size = 20> w </font><font size = 30> * </font><br/><br/><br/>
							TwO Players Game</b><br/><br/><br/>
							Attenzione: l'abbandono vale come sconfitta!
						</font>";
					}	
				?>
		</div>
		<div id="bottomInfo">
			<c1>Gioco vietato ai minori di 18 anni, pu&ograve causare dipendenza patologica.</c1>
			<c2> 7eMMeZZo Casin&ograve Royal &copy All Rights Reserved </c2>
		</div>
	</body>
</html>

<?php
	// Vittoria Computer
	if($valoreAttualeUtente > 7.5 && $valoreAttualeComputer <= 7.5 && ($valoreAttualeUtente2 > 7.5 || $_SESSION['gotTheWin']==2)){
		$puntataGioc2 = moneyRequest($_SESSION['userTwo'],$_SESSION['gameID']);
		$_SESSION['utente'] -> addPortafogli(-$_SESSION['puntata']);
		$_SESSION['utente'] -> addNSconfitte($_SESSION['puntata']);
		$_SESSION['computer'] -> addNVittorie($_SESSION['puntata'] + $puntataGioc2);
		serializzaOggetti();
		sendEndGame($_SESSION['gameID']);
		$_SESSION['gotTheWin'] = 0;
		echo '<script>
				alert("Il Banco Vince \nPerdi: '.$_SESSION['puntata'].' \u20AC");
				window.location.href = "UserPage.php";
			</script>';
	}	
	// Pareggio ALL
	if($valoreAttualeUtente == 7.5 && $valoreAttualeComputer == $valoreAttualeUtente && $valoreAttualeUtente2 == 7.5){
		$_SESSION['utente'] -> addNPareggi();
		$_SESSION['computer'] -> addNPareggi();
		$_SESSION['gotTheWin'] = 0;
		sendEndGame($_SESSION['gameID']);
		serializzaOggetti();
		echo '<script>
				alert("Partita Conclusa In Parit\xE1");
				window.location.href = "UserPage.php";
			</script>';
	}
	// Pareggio Giocatori
	if($valoreAttualeUtente == 7.5 && $valoreAttualeUtente2 == $valoreAttualeUtente && $valAttualeComputer > 7.5){
		$puntataGioc2 = moneyRequest($_SESSION['userTwo'],$_SESSION['gameID']);
		$_SESSION['utente'] -> addNVittorie(($_SESSION['puntata']/2));
		$_SESSION['computer'] -> addNSconfitte(($_SESSION['puntata']/2)+($puntataGioc2/2));
		$_SESSION['gotTheWin'] = 0;
		serializzaOggetti();
		echo '<script>
				alert("Parit\xE1 Tra I Giocatori. \nVinci Met\xE1 Puntata!");
				window.location.href = "UserPage.php";
			</script>';
	}
	// Pareggio Giocatore-Computer
	if($valoreAttualeUtente == 7.5 && $valoreAttualeComputer == $valoreAttualeUtente && ($valAttualeUtente2 > 7.5 || $_SESSION['gotTheWin'] == 2)){
		$puntataGioc2 = moneyRequest($_SESSION['userTwo'],$_SESSION['gameID']);
		$_SESSION['utente'] -> addNPareggi();
		$_SESSION['computer'] -> addNVittorie(($_SESSION['puntata'] + $puntataGioc2/2));
		$_SESSION['gotTheWin'] = 0;
		serializzaOggetti();
		echo '<script>
				alert("Parit\xE1 Con Il Computer. \n Partita Conclusa In Pareggio!");
				window.location.href = "UserPage.php";
		</script>';
	}
	// Vittoria Giocatore
	if($valoreAttualeUtente <= 7.5 && $valoreAttualeComputer > 7.5 && ($valoreAttualeUtente2 > 7.5 || $_SESSION['gotTheWin'] == 2)){
		$puntataGioc2 = moneyRequest($_SESSION['userTwo'],$_SESSION['gameID']);
		$_SESSION['utente'] -> addPortafogli($_SESSION['puntata']);
		$_SESSION['utente'] -> addNVittorie($_SESSION['puntata']);
		$_SESSION['computer'] -> addNSconfitte($_SESSION['puntata']-$puntataGioc2);	
		serializzaOggetti();
		sendEndGame($_SESSION['gameID']);
		$_SESSION['gotTheWin'] = 0;
		echo '<script>
				alert("Hai Vinto (: \nIncassi: '.$_SESSION['puntata'].' \u20AC");
				window.location.href = "UserPage.php";
			</script>';
	}
	// Esclusione Giocatore 2
	if($valoreAttualeUtente2 > 7.5 && $_SESSION['gotTheWin'] != 2){
		$_SESSION['gotTheWin'] = 2;
		$_SESSION['starting'] = True;
		echo '<script>
				alert("Il Giocatore 2 Ha Perso.");
			</script>';
	}
	// Sconfitta Giocatore 1
	if($valoreAttualeUtente > 7.5 &&  $_SESSION['gotTheWin'] == 1){
		$_SESSION['utente'] -> addPortafogli(-$_SESSION['puntata']);
		$_SESSION['utente'] -> addNSconfitte($_SESSION['puntata']);
		$_SESSION['gotTheWin'] = 0;
		serializzaPlayer();
		echo '<script>
				alert("Hai Perso ): \nPersi:'.$_SESSION['puntata'].' \u20AC");
				window.location.href = "UserPage.php";
			</script>';
	}
	// Funzione Recupero Carta Da Vettore
	function randCard(){
		$index = (($_SESSION['userCards']->getCardsNumber())+($_SESSION['computerCards']->getCardsNumber()));
		return $_SESSION['randCards'][$index];
	}
?>
