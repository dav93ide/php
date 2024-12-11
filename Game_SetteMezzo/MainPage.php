<!--|☼|»»»»»»»»»»»»»»»»»»»»»»x««««««««««««««««««««««|☼|
    |☼|      ________________________________       |☼|
    |☼|      |7eMMeZZo|    |------|     |PhP| 	    |☼|
    |☼|      ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯       |☼|
    |☼|»»»»»»»»»»»»»»»»»»»»»»x««««««««««««««««««««««|☼|-->

<?php
	session_start();
	unset($_SESSION['reg']);
	include('Serialization_&_File_Functions.php');
	include('DB_Functions.php');
	checkDB();
	if(isset($_COOKIE['cookie'])){
		$fileName = 'dataDir/'.$_COOKIE['cookie'] . '.dat';
		$_SESSION['utente'] = unserialize(readFromFileSerializedObject($fileName));
	}
	function __autoload($nomeClasse){
		require_once($nomeClasse.".php");
	}
	// Controllo Esistenza Directory "dataDir"
	if(!file_exists('dataDir')){
		mkdir('dataDir');
	}
	// Controllo Esistenza File "Computer"
	if(!file_exists("dataDir/C0MPUT3R.dat")){
		$_SESSION['computer'] = new Computer("(()|\/|PU73R");		// Se Non Esiste Creo L'Oggetto
		serializzaOggetti();						// Dunque Lo Serializzo
	}
	// Recupero Oggetto "Computer"
	else{
		$_SESSION['computer'] = unserialize(readFromFileSerializedObject("dataDir/C0MPUT3R.dat")); 
	}
?>
<html>
	<head>
		<title> 7eMMeZZo Casin&ograve Royal </title>
		<link href="CssLayouts.css" rel="stylesheet" type="text/css">
	</head>
	<body id="blackBody">
		<?php
			if(!isset($_COOKIE['cookie'])){
				echo "
				<script>
					var c = confirm(\"Questo sito fa uso di cookie, cliccando 'accetta' o navigando fra le pagine si acconsente al loro utilizzo.\");
					if(!c){
						window.location.href = \"Byebye.php\";
					}
				</script>";
			}
		?>
		<div id="topImage"> </div>
		<div id="topMenuLog">
			<?php
				if(isset($_SESSION['utente'])){
					echo "<font color=yellow size= 4> Username: </font> 
					<font color=white size=4>" . $_SESSION['utente'] -> getName() . "</font> <br/> <br/>
					<form action=\"Check.php\" method=\"GET\">
						<input type=\"submit\" id=\"button1\" name=\"logout\" value=\"Log-Out\"/>
					</form>";
				}
				else{
					echo "
					<form action=\"Check.php\" method=\"POST\">
						<input type=\"text\" id=\"text1\" name=\"username\" placeholder=\"Username\" style=\"position:absolute;right:19%;top:20%;\" />
						<input type=\"password\" id=\"text1\" name=\"pass\" placeholder=\"Password\" style=\"position:absolute;right:2%;top:20%;\"/> <br/>
						<input type=\"submit\" id=\"button1\" name=\"Registrati\" value=\"Registrati\" style=\"position:absolute;right:19%;top:25%;\" />
						<input type=\"submit\" id=\"button1\" name=\"Log-In\" value=\"Log-In\" 
						style=\"position:absolute;right:2%;top:25%;\"/>
					</form>";
				}
			?>
		</div>
		<div id="leftMain">
			<?php
				if(!isset($_SESSION['utente'])){
					echo "
					<font color=white size=3>
						Entra a far parte della nostra community di giocatori, <br/><br/>
					</font>
					<font color=yellow size=3>
						potrai vincere un mucchio di soldi!
					</font>
					<br/><br/><br/><br/><br/><br/>
					<font color=red size=4>
						<b><br/> Cosa Aspetti? Unisciti a noi! </b>
						<br/><br/><br/><br/><br/> <br/><br/><br/> 
					</font>
					<font color=#0F0 size=2>
						Riceverai Subito 1000 Crediti!
					</font> ";
				}
				else{
					echo"<font color=yellow size=3>
							Attualmente supportiamo le seguenti opzioni di gioco: <br/><br/>
							[*] Player Vs Computer <br/>
							[*] 2 Players Vs Computer
						<br/><br/><br/><br/><br/> 
					</font>";
				}
			?>
		</div>
		<div id="rightMain">
			<font color=blue size=5> 
				<br/><b> 7eMMeZZo Casin&ograve Royal </b> <br/>
				Ti d&aacute il Benvenuto! </font> <br/> <br/><br/><br/><br/>
				<?php
					if(!isset($_SESSION['utente'])){
						echo "Sembra tu non abbia ancora effettuato l'accesso, <br/> prego accedi con il tuo account o registrati per poter iniziare a giocare!";
					}
					else{
						echo "Siamo lieti di rivederti nostro caro utente <font size=4 color=blue>". $_SESSION['utente'] -> getName() ."</font>!
						<br/><br/>Procedi alla nostra pagina di gioco cliccando il bottone sottostante (:
						<br/><br/>
						<input type=\"button\" id=\"clickMe\"onClick=\"location='UserPage.php'\" value='Cliccami'/>";
					}
					// Stampa Messaggi
					if(isset($_SESSION['Message'])){
						echo "<br/><br/><br/>" . $_SESSION['Message'];
						unset($_SESSION['Message']);
					}
					// Stampa Eventuali Errori
					if(isset($_SESSION['Error'])){
						echo "<br/> <br/> <br/><b><font color=red size=4> Attenzione Abbiamo Riscontrato L'Errore: </font></b><br/><br/>" . $_SESSION['Error'] . "<br/>";
						unset($_SESSION['Error']);
					}
				?>
		</div>
		<div id="bottomInfo">
			<c1>Gioco vietato ai minori di 18 anni, pu&ograve causare dipendenza patologica.</c1>
			<c2> 7eMMeZZo Casin&ograve Royal &copy All Rights Reserved </c2>
		</div>
	</body>
</html>