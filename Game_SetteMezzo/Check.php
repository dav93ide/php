<?php 
	session_start();
	include('Serialization_&_File_Functions.php');
	include('DB_Functions.php');
	function __autoload($nomeClasse){
		require_once($nomeClasse.".php");
	}
	if(!isset($_REQUEST)){
		header("location: 404.php");
	}
?>


<!--|☼|»»»»»»»»»»»»»»»»»»»»»»x««««««««««««««««««««««|☼|
    |☼|      ________________________________       |☼|
    |☼|      |7 e 1/2|     |------|      |PhP| 	    |☼|
    |☼|      ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯       |☼|
    |☼|»»»»»»»»»»»»»»»»»»»»»»x««««««««««««««««««««««|☼|-->
	
	
<?php
// Controllo Registrazione
	if(isset($_REQUEST['Registrati'])){
		unset($_SESSION['Message']);
		unset($_SESSION['Error']);
		if(searchNameDB($_REQUEST['username'])){
			$_SESSION['Error'] = "[+] Username Esistente, Impossibile Completare La Registrazione [+] ";
		}
		else{
			if($_REQUEST['username'] == null || $_REQUEST['pass'] == null){
				$_SESSION['Error'] = "[+] Per Registrarsi Inserire Username E Password [+]";
			}
			else{
				if($_REQUEST['username'] == "C0MPUT3R"){
					$_SESSION['Error'] = "[+] Non E' Possibile Utilizzare Questo Username [+]";
				}
				else{
					$pass = password_hash($_REQUEST['pass'], PASSWORD_DEFAULT);	// Creo Hash Dalla Password
					insertUserDB($_REQUEST['username'],$pass);
					$_SESSION['reg'] = True;
					unset($_SESSION['Error']);
				}
			}
		}
		header('location: MainPage.php');
	}
	// Controllo Accesso
	if(isset($_REQUEST['Log-In']) || isset($_SESSION['reg'])){
		unset($_SESSION['Error']);
		unset($_SESSION['Message']);
		if(loginDB($_REQUEST['username'], $_REQUEST['pass'])){
			$_SESSION['Message'] = ((isset($_SESSION['reg']))
					? $_SESSION['Message'] = "[+] Registrazione E Accesso Effettuati [+] <br/>"
					:"[*] Nome Utente - Pass Trovati [*] <br/>");
			$fileName = 'dataDir/'.$_REQUEST['username'] . '.dat';
			// Se Il File "username.dat" Non Esiste Lo Creo E Vi Serializzo Un Nuovo Oggetto "Giocatore"
			if(!file_exists($fileName)){
				$_SESSION['utente'] = new Giocatore($_REQUEST['username']);
				serializzaOggetti();
			}
			else{		// Altrimenti Recupero L'Oggetto "Giocatore" Serializzato Precedentemente
				$_SESSION['utente'] = unserialize(readFromFileSerializedObject($fileName));
			}
			setcookie("cookie",$_SESSION['utente'] -> getName(),time()+3600);
			insertOnlineDB($_REQUEST['username']);
			unset($_SESSION['reg']);
		}
		else{
			if(searchNameDB($_REQUEST['username'])){
				$_SESSION['Error'] = "[*] Password Errata [*]";
			}
			else{
				$_SESSION['Error'] = "[*] Nome Utente Non Trovato [*]";
			}
		}
		header('location: MainPage.php');
	}
	// Log-Out
	if(isset($_REQUEST['logout'])){
		unset($_SESSION['Error']);
		$_SESSION['Message'] = "[+] Log-Out Effettuato [+]<br/>";
		header('location: Byebye.php');
	}
	// Inizio Partita
	if(isset($_GET['bttn1']) || isset($_GET['2players'])){
		if($_GET['schei'] == ''){
			$_SESSION['Error'] = "[!-!-!] Devi Inserire Una Puntata Prima Di Procedere [!-!-!]";
			header('location: UserPage.php');
		}
		else{
			$vetGet = str_split($_GET['schei'],1);	// Splitto La Stringa Ottenendo Array Con I Caratteri
			$vetVal = Array("0","1","2","3","4","5","6","7","8","9",".");	// Array Di Controllo
			$check = false;
			foreach($vetGet as $c){
				if(!in_array($c,$vetVal)){	// Se Il Carattere "$c" Non E' Nell'Array Metto Check A True 
					$check = True;	// "P.S. = "in_array" Compara Anche Sul Tipo Non Solo Sul Valore
					break;
				}
			}
			if($check){
				$_SESSION['Error'] = "[!-!-!] Inserimento Della Puntata Non Valido, Inserire Un Numero [!-!-!]";
				header('location: UserPage.php');
			}
			else{
				if($_GET['schei'] > $_SESSION['utente'] -> getPortafogli()){
					$_SESSION['Error'] = "[!-!-!] Stai Puntando Pi&ugrave Di Quanto Possiedi 	[!-!-!]";
					header('location: UserPage.php');
				}
				else{
					$_SESSION['puntata'] = $_GET['schei'];
					unset($_SESSION['Error']);
					if(isset($_GET['bttn1'])){
						header('location: SinglePlayerGame.php');
					}
					else{
						header('location: TCPConnection.php');
					}
				}
			}
		}	
	}
?>