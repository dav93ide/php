<?php
	$connection = mysql_connect('localhost','root');
	mysql_query("CREATE DATABASE User_DB",$connection);									//Creo DB
	mysql_select_db('User_DB',$connection);
	mysql_query('CREATE TABLE UsersTab(ID int(10) NOT NULL AUTO_INCREMENT, 
		name varchar(50) NOT NULL, pass varchar(50) NOT NULL, PRIMARY KEY(ID))',$connection);			//Creo Tab Utenti
	mysql_query('CREATE TABLE tabimg(idpk INT(10) NOT NULL AUTO_INCREMENT, idfk INT(10) NOT NULL,
		imglink VARCHAR(100) NOT NULL, PRIMARY KEY(idpk), UNIQUE (idfk), 
		FOREIGN KEY(idfk) REFERENCES userstab(ID))',$connection);					//Creo Assczn Img
	mysql_query('CREATE TABLE imglist(id INT(10) NOT NULL AUTO_INCREMENT, imgname VARCHAR(100) NOT NULL,
		PRIMARY KEY(id))',$connection);												//Creo Img List
	mysql_query("INSERT INTO userstab(ID,name,pass) VALUES (1,'root','toor')",$connection);			//Inserisco Valori Default
	mysql_query("INSERT INTO tabimg(idpk,idfk,imglink) VALUES (1,1,'img/img_01.jpg')",$connection);
	mysql_query("INSERT INTO imglist(id,imgname) VALUES (1,'img_01.jpg')",$connection);
	mysql_query("INSERT INTO imglist(id,imgname) VALUES (2,'kali.jpg')",$connection);
	mysql_query("INSERT INTO imglist(id,imgname) VALUES (3,'peace.jpg')",$connection);
	mysql_query("INSERT INTO imglist(id,imgname) VALUES (4,'default.png')",$connection);
	mysql_close($connection);
	header("location: index.php");
?>