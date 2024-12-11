<?php
	session_start();
		if(empty($_POST['username'] || $_POST['password'])){						//Campi Vuoti?
			$_SESSION['error'] =  "<br/>Inserire Nome Utente E Password!<br/>";
			header("Location: index.php");
		}
		else{									//Sennò..
			$username = $_POST['username'];
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
					$_SESSION['error'] = '';
					$connection = mysql_connect('localhost','root');
					mysql_select_db('user_db',$connection);
					$ris = mysql_query("SELECT * FROM userstab WHERE name = '$username'
						AND pass = '$password'", $connection);
					$risimg = mysql_query("SELECT imglink FROM tabimg,userstab
							WHERE tabimg.idpk = userstab.ID AND userstab.name = '$username'");
					$risimg = mysql_fetch_array($risimg,MYSQL_ASSOC);
					if(mysql_num_rows($ris) == 1){							//Setto Var Sessione
						$_SESSION['user'] = $username;
						$_SESSION['log'] = TRUE;
						$_SESSION['img'] = $risimg["imglink"];
						header("Location: homepage.php");
					}
					else{
						$_SESSION['error'] = "<br/>Username E Password Non Trovati...<br/>";
						header("Location: registration.php");
					}
					mysql_close($connection);
			/*	}
			}	*/
		}
?>