<?php
	session_start();
	if((empty($_POST['username'])) OR (empty($_POST['password']))){						//Controllo campi pieni
		$_SESSION['error'] = "<br/>Inserire Un Nome Utente E Una Password!<br/>";	
		header("Location: registration.php");
	}
	else{
		$username = $_POST['username'];										//Assegno var
		$password = $_POST['password'];
		/* Con questo codice il sistema accetterà solo caratteri alfanumerici -- Non Provato*/
			/*if(!ctype_alnum($username)){
				$_SESSION['error'] =  "Errore, Username Non Esclusivamente Alfanumerico!!";
				header("Location: index.php");
			}
			else{
				if(!ctype_alnum($password)){
					$_SESSION['error'] = "Errore, Password Non Esclusivamente Alfanumerica!!";
					header("Location: index.php");
				}
				else{*/
		$connection = mysql_connect('localhost','root');
		mysql_select_db('user_db',$connection);
		$ris = mysql_query("SELECT * FROM userstab WHERE name = '$username'",$connection);		//Cero Nome
		if(mysql_num_rows($ris) != 0){									//Se righe di riscontro
			$_SESSION['error'] = "Nome Utente Gi&agrave Esistente!!";
			header("Location: registration.php");
		}
		else{															//Altrimenti
			$dd = mysql_query("SELECT ID FROM userstab", $connection);	//Trovo ultimo ID usato
			$ID = (mysql_num_rows($dd) + 1);
			mysql_query("INSERT INTO userstab(ID,name,pass) VALUES ($ID,'$username','$password')",$connection);	// Inserisco dati
			mysql_query("INSERT INTO tabimg(idpk,idfk,imglink) VALUES ($ID,$ID,'img/default.png')");	//Inserisco img default
			$_SESSION['error'] = "Username Creato!!";
			header("Location: index.php");
		}
		mysql_close($connection);
		/*	}
				}	*/
	}

?>