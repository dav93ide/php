<?php
	// Crea DB E Tabelle
	function initDB(){
		$connection = mysql_connect('localhost','root');
		mysql_query('CREATE DATABASE IF NOT EXISTS webmaking', $connection);
		mysql_select_db('webmaking',$connection);
		mysql_query('CREATE TABLE IF NOT EXISTS usertab(id INT NOT NULL AUTO_INCREMENT, name varchar(20) NOT NULL, pass VARCHAR(100) NOT NULL, registrazione DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(id), UNIQUE(name))',$connection);
		mysql_query("CREATE TABLE IF NOT EXISTS pagestab(id INT NOT NULL AUTO_INCREMENT, user_id INT NOT NULL, name varchar(50) NOT NULL, hide CHAR(1) DEFAULT 'n', deleted CHAR(1) DEFAULT 'n', creata DATETIME DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(id), FOREIGN KEY(user_id) REFERENCES usertab(id))",$connection);
		mysql_query("CREATE TABLE IF NOT EXISTS followertab(id_us INT NOT NULL, id_fllwr INT NOT NULL, added DATETIME DEFAULT CURRENT_TIMESTAMP)");
		mysql_close($connection);
	}
	
	// Controlla Esistenza Del DB
	function checkDB(){
		$connection = mysql_connect('localhost','root');
		$v = mysql_select_db('webmaking',$connection);
		if(!$v){
			initDB();
		}
	}
	
	// Inserisce username/password In Tabella Utenti Registrati
	function insertUserDB($name,$pass){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('webmaking',$connection);
		mysql_query('INSERT INTO usertab(name,pass) VALUES (\''.$name.'\',\''.$pass.'\')',$connection);
		mysql_close($connection);
	}
	
	// Cerca Un Nome Utente Nella Tabella Utenti Registrati
	function searchNameDB($name){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('webmaking',$connection);
		$ris = mysql_query("SELECT name FROM usertab WHERE name='$name'",$connection);
		$arr = mysql_fetch_array($ris);
		mysql_close($connection);
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
		mysql_select_db('webmaking',$connection);
		$ris = mysql_query("SELECT name,pass FROM usertab WHERE name = '$name'",$connection);
		$arr = mysql_fetch_array($ris);
		mysql_close($connection);
		if($arr[0] == $name && password_verify($pass, $arr[1])){
			return True;
		}
		else{
			return False;
		}
	}
	
	// Aggiunge pagina a pagestab
	function addPageDB($name,$hide){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('webmaking',$connection);
		$ris = mysql_query("SELECT id FROM usertab WHERE name='".$_SESSION['username']."'", $connection);
		$arr = mysql_fetch_assoc($ris);
		$id = $arr['id'];
		mysql_query("INSERT INTO pagestab(user_id,name,hide) VALUES($id, '$name', '$hide')");
		mysql_close($connection);
	}
	
	// "Elimina" pagina da pagestab
	function delPageDB($name){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('webmaking',$connection);
		$ris = mysql_query("UPDATE pagestab SET deleted='s' WHERE name='$name'", $connection);
		mysql_close($connection);
	}
	
	// Recupera pagine e nomi utenti
	function getUserList(){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('webmaking',$connection);
		$ris = mysql_query("SELECT usertab.name,pagestab.name FROM usertab,pagestab WHERE usertab.id = pagestab.user_id AND hide='n'", $connection);
		while($row = mysql_fetch_row($ris)){
			if(!isset($arr[$row[0]])){
				$arr[$row[0]] = array();
			}
			array_push($arr[$row[0]],$row[1]);
		}
		mysql_close($connection);
		return ((isset($arr))? $arr : False);
	}
	
	// Controlla Follower
	function checkFollower($name){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('webmaking',$connection);
		$ris = mysql_query("SELECT id FROM usertab WHERE name='$name'", $connection);
		$arr = mysql_fetch_assoc($ris);
		$ris = mysql_query("SELECT id_us,id_fllwr FROM followertab,usertab WHERE id_us=". $arr['id'] ." AND id_fllwr=usertab.id AND usertab.name = '". $_SESSION['username'] . "' ", $connection);
		if(mysql_fetch_row($ris)){
			return True;
		}
		return False;
		mysql_close($connection);
	}
	
	// Aggiunge Follower
	function addFollower($name){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('webmaking',$connection);
		$ris = mysql_query("SELECT id FROM usertab WHERE name='$name'", $connection);
		$idus1 = mysql_fetch_row($ris)[0];
		$ris = mysql_query("SELECT id FROM usertab WHERE name='". $_SESSION['username']."'", $connection);
		$idus2 = mysql_fetch_row($ris)[0];
		$ris = mysql_query("SELECT id FROM usertab WHERE ",$connection);
		mysql_query("INSERT INTO followertab(id_us,id_fllwr) VALUES ($idus1,$idus2)",$connection);
		mysql_close($connection);		
	}
	
	// Get Followers
	function getFollowers(){
		$arr = Array();
		$connection = mysql_connect('localhost','root');
		mysql_select_db('webmaking',$connection);
		$ris = mysql_query("SELECT name FROM usertab INNER JOIN followertab ON usertab.id =  followertab.id_us AND followertab.id_fllwr IN (SELECT id FROM usertab WHERE name='". $_SESSION['username']."')", $connection);
		while($row = mysql_fetch_row($ris)){
			array_push($arr,$row[0]);
		}
		mysql_close($connection);
		return $arr;
	}
	
	// Del Follower
	function delFollower($name){
		$connection = mysql_connect('localhost','root');
		mysql_select_db('webmaking',$connection);
		mysql_query("DELETE FROM followertab WHERE id_us IN (SELECT id FROM usertab WHERE name='$name') AND id_fllwr IN (SELECT id FROM usertab WHERE name='". $_SESSION['username'] ."')",$connection);
		mysql_close($connection);
	}

?>