<?php 
	session_start();
	include('DB_Functions.php');
	if(!isset($_REQUEST)){
		header("location: 404.php");
	}
?>
	
<?php
	// Elimina Follower
	if(isset($_REQUEST['delFllw'])){
		if(isset($_REQUEST['fllwrList']) & strlen($_REQUEST['fllwrList']) > 0){
			if(checkFollower($_REQUEST['fllwrList'])){
				$_SESSION['Message'] = "[+] Follower Eliminato Correttamente [+]";
				delFollower($_REQUEST['fllwrList']);
			}
			else{
				$_SESSION['Error'] = "[+] Nome Inserito Non Valido [+]";
			}
		}
		else{
			$_SESSION['Error'] = "[+] Nessun Nome Selezionato [+]";
		}
		header('location: user_page.php');
	}
	
	// Controllo Registrazione
	if(isset($_REQUEST['Registrati'])){
		unset($_SESSION['Message']);
		unset($_SESSION['Error']);
		if(searchNameDB($_REQUEST['username'])){
			$_SESSION['Error'] = "[+] Username Esistente, Impossibile Completare La Registrazione [+] ";
			header('location: index.php');
		}
		else{	// \/:*?"<>|
			if($_REQUEST['username'] == null || $_REQUEST['pass'] == null){
				$_SESSION['Error'] = "[+] Per Registrarsi Inserire Username E Password [+]";
				header('location: index.php');
			}
			else{
				$vetVal = Array("\\", "/", ":", "*", "|", "<", ">", "\"", "?", "#");
				$check = false;
				foreach($vetVal as $c){
					if(strpos($_REQUEST['username'],$c)){ 
						$check = True;
						break;
					}
				}
				if($check){
					$_SESSION['Error'] = "[+] Carattere Non Valido: $c [+] <br/> <br/> Lo username non pu&oacute contenere uno tra i caratteri: \ / : * ? \" < > |";
					header('location: index.php');
				}
				else{
					$fileName = 'dataDir/'.$_REQUEST['username'];
					$pass = password_hash($_REQUEST['pass'], PASSWORD_DEFAULT);	// Creo Hash Dalla Password
					insertUserDB($_REQUEST['username'],$pass);
					$reg = True;
					unset($_SESSION['Error']);
					mkdir($fileName);
				}
			}
		}
	}
	
	// Controllo Accesso
	if(isset($_REQUEST['Log-In']) || isset($reg)){
		unset($_SESSION['Error']);
		unset($_SESSION['Message']);
		if(loginDB($_REQUEST['username'], $_REQUEST['pass'])){
			$_SESSION['Message'] = ((isset($reg))
					? $_SESSION['Message'] = "[+] Registrazione E Accesso Effettuati [+] <br/>"
					:"[*] Nome Utente - Pass Trovati [*] <br/>");
			setcookie("cookie",$_REQUEST['username'],time()+3600);
			$_SESSION['username'] = $_REQUEST['username'];
			header('location: user_page.php');
		}
		else{
			if(searchNameDB($_REQUEST['username'])){
				$_SESSION['Error'] = "[*] Password Errata [*]";
			}
			else{
				$_SESSION['Error'] = "[*] Nome Utente Non Trovato [*]";
			}
			header('location: index.php');
		}
	}
	
	// Log-Out
	if(isset($_REQUEST['logout'])){
		header('location: Byebye.php');
	}
	
?>