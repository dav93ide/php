<?php
	// Crea DB E Tabelle
	function initDB(){
		$connection = mysql_connect('localhost','root');
		mysql_query('CREATE DATABASE IF NOT EXISTS 7emmezzo', $connection);
		mysql_select_db('7emmezzo',$connection);
		mysql_query('CREATE TABLE IF NOT EXISTS usertab(id INT NOT NULL AUTO_INCREMENT, name varchar(20) NOT NULL, pass VARCHAR(100) NOT NULL, registrazione DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(id), UNIQUE(name))',$connection);
		mysql_query('CREATE TABLE IF NOT EXISTS useronline(id INT NOT NULL AUTO_INCREMENT, name VARCHAR(20) NOT NULL, connessione DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(ID), UNIQUE(name))',$connection);
		mysql_close();
	}
	
	// Controlla Esistenza Del DB
	function checkDB(){
		$connection = mysql_connect('localhost','root');
		$v = mysql_select_db('7emmezzo',$connection);
		if(!$v){
			initDB();
		}
	}
	
	// Inserire username/password In Tabella Utenti Registrati
	function insertUserDB($name,$pass){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('7emmezzo',$connection);
		mysql_query('INSERT INTO usertab(name,pass) VALUES (\''.$name.'\',\''.$pass.'\')',$connection);
		mysql_close();
	}
	
	// Inserisce username In Tabella Utenti Online
	function insertOnlineDB($name){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('7emmezzo',$connection);
		mysql_query('INSERT INTO useronline(name) VALUES (\''.$name.'\')',$connection);
		mysql_close();
	}
	
	// Aggiorna User Online
	function updateOnline($name){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('7emmezzo',$connection);
		$ris = mysql_query("SELECT id FROM useronline WHERE name='$name'");
		$rows = mysql_num_rows($ris);
		if($rows==0){
			insertOnlineDB($name);
		}
		else{
			mysql_query("UPDATE useronline SET connessione=CURRENT_TIMESTAMP WHERE name='$name'");
		}
		mysql_close($connection);
	}
	
	// Elimina Users Offline
	function delOffline(){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('7emmezzo',$connection); 
		mysql_query("DELETE FROM useronline WHERE connessione < (CURRENT_TIMESTAMP - 60)",$connection);
		mysql_close($connection);
	}
	
	// Cerca Un Nome Utente Nella Tabella Utenti Registrati
	function searchNameDB($name){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('7emmezzo',$connection);
		$ris = mysql_query("SELECT name FROM usertab WHERE name='$name'",$connection);
		$arr = mysql_fetch_array($ris);
		mysql_close();
		if(in_array($name,$arr)){
			return True;
		}
		else{
			return False;
		}
	}

	// Esegue L'Autenticazione Per Il Log-In
	function loginDB($name, $pass){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('7emmezzo',$connection);
		$ris = mysql_query("SELECT name,pass FROM usertab WHERE name = '$name'",$connection);
		$arr = mysql_fetch_array($ris);
		mysql_close();
		if($arr[0] == $name && password_verify($pass, $arr[1])){
			return True;
		}
		else{
			return False;
		}
	}
	
	// Cancella Un Utente Online Dalla Tabella
	function deleteUserOnline($name){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('7emmezzo',$connection);
		$id = mysql_query("DELETE FROM useronline WHERE name = '$name' AND connessione>(TIME_STAMP+1100)",$connection);
		mysql_close();
	}
	
	// Recupera Tutti Gli Utenti Online
	function getOnlineUsers(){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('7emmezzo',$connection);
		$ris = mysql_query("SELECT name FROM useronline WHERE 1",$connection);
		$arr = array();
		for($i=0;$i<mysql_num_rows($ris);$i++){
			$row = mysql_fetch_row($ris);
			array_push($arr,$row[0]);
		}
		mysql_close();
		return $arr;
	}
?>