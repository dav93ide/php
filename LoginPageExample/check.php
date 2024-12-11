<?php
	session_start();											// Avvio sessione per memorizzare errori
	$connection = mysql_connect('localhost','root');			// Stabilisco connessione con il server
	$lsdbs = mysql_list_dbs($connection);						// Ottengo lista nome DBs nel server
	$arr = array();												// Genero array vuoto per iterazione e confronto
	for($i=0;$i<mysql_num_rows($lsdbs);$i++){					// Num max cicli < numero righe della lista
		array_push($arr,mysql_db_name($lsdbs,$i));				// Aggiungo al vettore ogni nome db presente nella lista
	}
	if(in_array('user_db',$arr)){								// Se contiene procedi
		mysql_select_db('user_db',$connection);					// Connetto al db
		$arr = array();											// Svuoto array
		$lstbs = mysql_list_tables("user_db",$connection);		// Ottengo lista tabelle nel DBs
		for($i=0;$i<mysql_num_rows($lstbs);$i++){
			array_push($arr,mysql_tablename($lstbs,$i));		// Add nomeTabella in array
		}
		if((in_array('userstab',$arr)) AND (in_array('tabimg',$arr)) AND (in_array('imglist',$arr))){	// Se contiene procedi
			$_SESSION['check'] = TRUE;
			header("location:index.php");
		}
		else{
			header("location:createDB.php");					// Altrimenti reindirizza
		}
	}
	else{
		header("location:createDB.php");						// Altrimenti reindirizza
	}
	mysql_close($connection);									// Chiusura connessione
?>