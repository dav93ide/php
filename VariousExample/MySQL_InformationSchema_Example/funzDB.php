<?php
	function initDB(){
		$con = mysql_connect('localhost','root');
		$v = mysql_select_db('provadatabase',$con);
		if(!$v){
			mysql_query('CREATE DATABASE IF NOT EXISTS provadatabase', $con);
			mysql_select_db('provadatabase',$con);
			mysql_query('CREATE TABLE IF NOT EXISTS provatabella(id INT NOT NULL AUTO_INCREMENT, name varchar(20) NOT NULL, pass varchar(20) NOT NULL, PRIMARY KEY(id))',$con);
			for($n=0;$n<10;$n++){
					mysql_query("INSERT INTO provatabella(name,pass) VALUES('user".$n."','pass".$n."')",$con);
			}
		}		
		mysql_close($con);
	}
	
	function exQuery($query){
		$con = mysql_connect('localhost','root');
		mysql_select_db('provadatabase',$con);
		$ris = mysql_query($query);
		mysql_close($con);
		return $ris;
	}
?>