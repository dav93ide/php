<?php
	session_start();
	include('Serialization_&_File_Functions.php');
	function __autoload($nomeClasse){
		require_once($nomeClasse.".php");
	}
	if(!isset($_SESSION['utente'])){
		header("location: 404.php");
	}
	// Genero Vettore Carte E Lo Mescolo
	if(!isset($_SESSION['randCards'])){
		$_SESSION['randCards'] = range(0,39);
		shuffle($_SESSION['randCards']);
	}
	if(!isset($_SESSION['puntata'])){
		$_SESSION['Error'] = "Devi Definire Una Puntata Per Poter Giocare";
		header("location: UserPage.php");
	}
	// Se Oggetti "Cards" Per Utente-Computer Non Esistono Li Creo
	if(!isset($_SESSION['userCards'])){
		$_SESSION['userCards'] = new Cards();
	}
	if(!isset($_SESSION['computerCards'])){
		$_SESSION['computerCards'] = new Cards();
	}
	// Bottone Richiedi Carta Clickato
	if(isset($_GET['bttn2'])) {
		$nCarta = randCard();	// Vedi Sotto, "randCard()"
		$_SESSION['userCards'] -> addCard($nCarta);	
		$_SESSION['gotTheWin'] = 1;				// 1=Partita In Corso, 0=Partita Finita
	}
	$valoreAttualeUtente = $_SESSION['userCards'] -> getValoreAttuale();
	$valoreAttualeComputer = $_SESSION['computerCards'] -> getValoreAttuale();
?>

<html>
	<head>
		<title> Single Player Game </title>
		<link href="CssLayouts.css" rel="stylesheet" type="text/css">
		<link href="CssCards.css" rel="stylesheet" type="text/css">
	</head>
	<body id="blackBody">
		<div id="topImage"> </div>
		<div id="topMenuLog">
			<?php
				echo "
				<font color=yellow size= 4> Username: </font> 
				<font color=white size=4>" . $_SESSION['utente'] -> getName() . "</font> <br/> <br/>";
			?>
		</div>
		<div id="leftMain">
			<?php
				echo "<br/><font color=white size=2>[+] Scommessa Attuale => " . $_SESSION['puntata'] . " &euro; [+] <br/>
				[+] Portafogli => " . $_SESSION['utente'] -> getPortafogli() . " &euro; [+] </font> <br/> <br/>";
			?>
			<font color=red size=5> Seleziona Un'Opzione: </form> <br/> <br/>
			<form action='' method='GET'>
				<input type='SUBMIT' id='button2' name='bttn2' value='Richiedi Carta'/>
			</form>
			<form action='UserPage.php' method='GET'>					
				<input type='SUBMIT' id='button2' name='bttn3' value='Fine Partita' />
			</form>
		</div>
		<div id="rightMain">
			<?php
				// Stampo Testo Carta Pescata Utente
				if(isset($nCarta)){
					echo "<font color='blue' size = 2> [!] Hai Pescato Un ". 
						$_SESSION['userCards'] -> traduciCarta(
						$_SESSION['userCards'] -> getCardName($nCarta))." [!] </font> <br/>" ;
				}
				// Blocco Pescaggio Computer
				if($valoreAttualeComputer < $valoreAttualeUtente && $valoreAttualeComputer <= 7.5 
						&& $valoreAttualeUtente <= 7.5){
					echo "<font color='green' size=2> [!] Il Computer Ha Pescato: - ";
					while($valoreAttualeUtente > $valoreAttualeComputer){
						$nCarta = randCard();
						$_SESSION['computerCards'] -> addCard($nCarta);
						// Stampo Testo Carta Pescata Computer
						echo $_SESSION['computerCards'] -> traduciCarta($_SESSION['computerCards']	-> 	getCardName($nCarta)) ." - ";
						$valoreAttualeComputer = $_SESSION['computerCards'] -> getValoreAttuale();
					}
					echo "[!]</font><br/>";
					$valoreAttualeComputer = $_SESSION['computerCards'] -> getValoreAttuale();
				}
				// Se L'Utente Ha Delle Carte Calcolo I Valori, Li Stampo E Stampo Le Carte.
				if(($_SESSION['userCards'] -> getCardsNumber()) > 0){
					$vetCarteUtente = $_SESSION['userCards'] -> getCards();
					$vetCarteBanco = $_SESSION['computerCards'] -> getCards();
					echo "<font color='blue' size=2>[+] Valore Attuale Delle Tue Carte => " .  $valoreAttualeUtente . 
						" [+] </font> <br/>";
					echo "<font color='green' size=2>[+] Valore Attuale Delle Carte Del Computer => " .  $valoreAttualeComputer . 
						" [+] </font><br/>";
					// Stampo Carte Utente
					echo "<table><tr><font color='blue' size=2> Le Tue Carte :</tr> <tr> ";
					foreach($vetCarteUtente as $k){
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
					echo "<font style=\"color:'red'; font-size:20; font-family:'Segoe Script'\"><br/><br/><br/><br/><br/><br/><b>Richiedi Una Carta O Termina La Partita Prima Di Iniziarla</b></font>";
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
	if($valoreAttualeUtente > 7.5 && $valoreAttualeComputer <= 7.5){
		$_SESSION['utente'] -> addPortafogli(-$_SESSION['puntata']);
		$_SESSION['utente'] -> addNSconfitte($_SESSION['puntata']);
		$_SESSION['computer'] -> addNVittorie($_SESSION['puntata']);
		serializzaOggetti();
		$_SESSION['gotTheWin'] = 0;
		echo '<script>
				alert("Il Banco Vince \nPerdi: '.$_SESSION['puntata'].' \u20AC");
				window.location.href = "UserPage.php";
			</script>';
	}
	// Pareggio
	if($valoreAttualeUtente == 7.5 && $valoreAttualeComputer == $valoreAttualeUtente){
		$_SESSION['utente'] -> addNPareggi();
		$_SESSION['computer'] -> addNPareggi();
		$_SESSION['gotTheWin'] = 0;
		serializzaOggetti();
		echo '<script>
				alert("Partita Conclusa In Parit\xE1");
				window.location.href = "UserPage.php";
			</script>';
	}
	// Vittoria Giocatore
	if($valoreAttualeUtente<=7.5 && $valoreAttualeComputer > 7.5){
		$_SESSION['utente'] -> addPortafogli($_SESSION['puntata']);
		$_SESSION['utente'] -> addNVittorie($_SESSION['puntata']);
		$_SESSION['computer'] -> addNSconfitte($_SESSION['puntata']);
		serializzaOggetti();
		$_SESSION['gotTheWin'] = 0;
		echo '<script>
				var c = alert("Hai Vinto (: \nIncassi: '.$_SESSION['puntata'].' \u20AC");
				window.location.href = "UserPage.php";
			</script>';
	}
	// Condizione Di Parità Momentanea, Obbligo Di Pescare Per L'Utente
	if($valoreAttualeUtente == $valoreAttualeComputer && $valoreAttualeComputer < 7.5 && $valoreAttualeUtente != 0){
		echo '<script>
				alert("Situazione Di Parit\xE1, \n Se non peschi Vince Il Banco");
			</script>';
	}
	// Funzione Recupero Carta Da Vettore
	function randCard(){
		$index = (($_SESSION['userCards']->getCardsNumber())+($_SESSION['computerCards']->getCardsNumber()));
		return $_SESSION['randCards'][$index];
	}
?>
