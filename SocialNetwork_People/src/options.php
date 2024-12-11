<?php
	session_start();
	include("db_functions.php");
	include("people_functions.php");
	if(isset($_POST['returnlink'])){
		$return = "location:".$_POST['returnlink'];
	}
	else{
		$return = "location: userpage.php";
	}
	switch($_POST){
		case isset($_POST["changepass"]):
			if(isset($_POST['oldpass']) && isset($_POST['newpass']) && isset($_POST['passconfirm'])){
				if($_POST['passconfirm'] == $_POST['newpass']){
					if(change_pass($_SESSION['username'],$_POST['newpass'],$_POST['oldpass'])){
						$_SESSION['Message'] = "Password Cambiata Con Successo!";
						header($return);
					}
					else{
						$_SESSION['Message'] = "La Vecchia Password Non Corrisponde";
						header($return);
					}
				}
				else{
					$_SESSION['Message'] = "Le Nuove Password Non Corrispondono";
					header($return);
				}
			}
			else{
				$_SESSION['Message'] = "Prego Settare Tutti I Campi";
				header($return);
			}
		break;
		case isset($_POST["changepicture"]):	// File < +-2Mb )
			if(isset($_FILES['image'])){
				if(isset($_POST['profile'])){
					$idimg = add_img($_SESSION['username'],$_FILES['image'],$_POST['imgname'],$_POST['profile'],$_POST['imgtext']);
					switch($idimg){
						case "noimg":
							$_SESSION['Message'] = "Il File Deve Essere Un'Immagine!";
							header($return);
						break;
						case "big":
							$_SESSION['Message'] = "Il File Non Deve Superare I Due Megabyte";
							header($return);
						break;
						default:
							if($_POST['profile'] == 'y'){
								 change_profile_picture($idimg,$_SESSION['username']);
								 $_SESSION['Message'] = "Immagine Profilo Caricata Con Successo";
								 header($return);
							}
							else{
								$_SESSION['Message'] = "Immagine Caricata Con Successo";
								header($return);
							}
						break;
					}
				}
				else{
					$_SESSION['Message'] = "Selezionare Un'Opzione Per Il Bottone Radio";
					header($return);
				}
			}
			else{
				$_SESSION['Message'] = "Nessuna Immagine Inserita";
				header($return);
			}
		break;
		case isset($_POST["changemail"]):
			if(isset($_POST['oldmail']) && isset($_POST['newmail'])){
				if(confirm_mail($_POST['oldmail'],$_SESSION['username'])){
					if(check_valid_mail($_POST['newmail'])){
						if(search_mail($_POST['newmail'])){
							change_email($_SESSION['username'], $_POST['newmail']);
							$_SESSION['Message'] = "Email Cambiata Con Successo";
							header($return);
						}
						else{
							$_SESSION['Message'] = "Email Gi&aacute Assegnata A Un Altro Utente";
							header($return);
						}
					}
					else{
						$_SESSION['Message'] = "Email Inserita Non valida";
						header($return);
					}
				}
				else{
					$_SESSION['Message'] = "Nome Email Precedente Non Corretto";
					header($return);
				}
			}
			else{
				$_SESSION['Message'] = "Prego Settare Tutti I Campi";
				header($return);
			}
		break;
		case isset($_POST['changequestion']):
			if(isset($_POST['secretanswer']) && isset($_POST['secret_question']) && isset($_POST['passquestion'])){
				switch($_POST['secret_question']){
					case "q1":
						$question = "Nome Del Tuo Animale Domestico";
					break;
					case "q2":
						$question = "Nome Del Tuo Libro Preferito";
					break;
					case "q3":
						$question = "Data Dell'Evento Pi&ugrave Importante Della Tua Vita";
					break;
					case "q4":
						$question = "Nome Della Tua Canzone Preferita";
					break;
					case "q5":
						$question = "Un Codice PIN Alfanumerico Segreto";
					break;
					case "q6":
						$question = "Nome Del Tuo Film Preferito";
					break;
					default:
						die();
					break;
				}
				change_secret_question($_POST['secretanswer'],$_POST['passquestion'],$_SESSION['username'],$question);
				$_SESSION['Message'] = "Domanda Segreta Cambiata Con Successo";
				header($return);
			}
			else{
				$_SESSION['Message'] = "Prego Settare Tutti I Campi";
				header($return);
			}
		break;
		case isset($_POST['followuser']):
			if(isset($_POST['fllwuser'])){
				if(check_locked($_POST['fllwuser'],$_SESSION['username'])){
					$_SESSION['Message'] = "Questo Utente Ti Ha Bloccato, Non Puoi Più Diventare Suo Follower";
					header($return);
				}
				else{
					if(check_locked($_SESSION['username'],$_POST['fllwuser'])){
						$_SESSION['Message'] = "Prima Di Poter Diventare Follower Di Questo Utente Devi Sbloccarlo!";
						header($return);						
					}
					else{
						if(search_name($_POST['fllwuser'])){
						add_follower($_SESSION['username'],$_POST['fllwuser']);
						$_SESSION['Message'] = "Ora Stai Seguendo: " . $_POST['fllwuser'];
						header($return);
					}
						else{
							$_SESSION['Message'] = "Nome Utente Non Trovato";
							header($return);
						}	
					}
				}
			}
			else{
				$_SESSION['Message'] = "Prego Settare Tutti I Campi";
				header($return);
			}
		break;
		case isset($_POST['delFllw']):
			if(isset($_POST['fllwrList'])){
				if(search_name($_POST['fllwrList'])){
					del_follower($_SESSION['username'],$_POST['fllwrList']);
					$_SESSION['Message'] = "Follower Eliminato Correttamente";
					header($return);
				}
				else{
					$_SESSION['Message'] = "Nome Utente Non Trovato";
					header($return);
				}
			}
			else{
				$_SESSION['Message'] = "Prego Settare Tutti I Campi";
				header($return);
			}
		break;
		case isset($_POST['sendnewmex']):
			if(isset($_POST['usersendmex']) && isset($_POST['messagetext'])){
				if(check_locked($_POST['usersendmex'], $_SESSION['username'])){
					$_SESSION['Message'] = "Questo Utente Ti Ha Bloccato, Non Puoi Più Contattarlo";
					header($return);					
				} 
				else {
					if(check_locked($_SESSION['username'],$_POST['usersendmex'])){
						$_SESSION['Message'] = "Prima Di Poter Contattare Questo Utente Devi Sbloccarlo!";
						header($return);
					}
					else{
						if(search_name($_POST['usersendmex'])){
							save_mex($_POST['usersendmex'],$_SESSION['username'],$_POST['messagetext'],false);
							$_SESSION['Message'] = "Messaggio Inviato Correttamente";
							header($return);
						}
						else{
							$_SESSION['Message'] = "Nome Destinatario Non Trovato";
							header($return);
						}
					}	
				}
			}
			else{
				$_SESSION['Message'] = "Prego Settare Tutti I Campi";
				header($return);
			}
		break;
		case isset($_POST['sendanswermex']):
			if(isset($_POST['messagetext']) && strlen($_POST['messagetext']) > 1 && $_POST['mexslists'] != 'null'){
				if(check_locked(get_name_from_id(get_otheruserid_from_mexgroup($_SESSION['username'],$_POST['mexslists'])),$_SESSION['username'])){
					$_SESSION['Message'] = "Questo Utente Ti Ha Bloccato, Non Puoi Più Contattarlo";
					header($return);
				}
				else{
					if(check_locked($_SESSION['username'],get_name_from_id(get_otheruserid_from_mexgroup($_SESSION['username'],$_POST['mexslists'])))){
						$_SESSION['Message'] = "Prima Di Poter Contattare Questo Utente Devi Sbloccarlo!";
						header($return);						
					}
					else{
						if(save_mex_2($_POST['messagetext'],$_SESSION['username'],$_POST['mexslists'])){
							$_SESSION['Message'] = "Messaggio Inviato Con Successo";
							header($return);
						}
						else{
							$_SESSION['Message'] = "Non &eacute Stato Possibile Inviare Il Messaggio";
							header($return);
						}
					}
				}
			}
			else{
				$_SESSION['Message'] = "Prego Settare Tutti I Campi";
				header($return);
			}
		break;
		case isset($_POST['delmex']):
			if($_POST['mexslists'] != 'null'){
				delete_mex($_POST['mexslists'],$_SESSION['username']);
				$_SESSION['Message'] = "Gruppo Di Messaggi Eliminati";
				header($return);
			}
			else{
				$_SESSION['Message'] = "Prego Settare Tutti I Campi";
				header($return);
			}
		break;
		case isset($_POST['lock']):
			if(isset($_POST['lockuser'])){
				if(lock_user($_SESSION['username'], $_POST['lockuser'])){
					$_SESSION['Message'] = "Hai Bloccato L'Utente: \"".$_POST['lockuser'] . "\"";
					header($return);
				}
				else{
					$_SESSION['Message'] = "Si &eacute Verificato Un Errore Durante L'Esecuzione Del Comando";
					header($return);
				}
			}
			else{
				$_SESSION['Message'] = "Prego Settare Tutti I Campi";
				header($return);
			}
		break;
		case isset($_POST['block']):
			if(isset($_POST['blockedlist']) || isset($_POST['lockuser'])){
				if(unlock_user($_SESSION['username'], ((isset($_POST['blockedlist']))?$_POST['blockedlist'] : $_POST['lockuser']))){
					$_SESSION['Message'] = "Hai Sbloccato L'Utente: \"".((isset($_POST['blockedlist']))?$_POST['blockedlist'] : $_POST['lockuser']) . "\"";
					header($return);
				}
				else{
					$_SESSION['Message'] = "Si &eacute Verificato Un Errore Durante L'Esecuzione Del Comando";
					header($return);
				}
			}
			else{
				$_SESSION['Message'] = "Prego Settare Tutti I Campi";
				header($return);
			}
		break;
		case isset($_POST['addpost']):
			if(isset($_POST['posttext']) && strlen($_POST['posttext']) > 1){
				add_post(((isset($_POST['otheruser'])) ? $_POST['otheruser'] : $_SESSION['username']),$_SESSION['username'],$_POST['posttext'],((isset($_POST['postgroup'])) ? $_POST['postgroup'] : false));
				$_SESSION['Message'] = "Post Aggiunto Con Successo";
				header($return);
			}
			else{
				$_SESSION['Message'] = "Prego Settare Tutti I Campi";
				header($return);
			}
		break;
		case isset($_POST['answerpost']):
			if(isset($_POST['posttext']) && strlen($_POST['posttext']) > 1 && isset($_POST['postgroup'])){
				add_post(((isset($_POST['otheruser'])) ? $_POST['otheruser'] : $_SESSION['username']),$_SESSION['username'],$_POST['posttext'],((isset($_POST['postgroup'])) ? $_POST['postgroup'] : false));
				$_SESSION['Message'] = "Post Di Risposta Aggiunto Con Successo";
				header($return);
			}
			else{
				$_SESSION['Message'] = "Prego Settare Tutti I Campi";
				header($return);
			}
		break;
		case isset($_POST['sendchatmex']):
			add_mex_chat($_POST['inputTextChat'],$_POST['otheruser'],$_POST['username']);
		default:
			header($return);
		break;
	}

?>
