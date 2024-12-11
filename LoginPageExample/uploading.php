<?php
	session_start();
	$connection = mysql_connect("localhost","root");
	mysql_select_db("user_db",$connection);
	$img = $_FILES['image'];
	if(strcasecmp($img['name'],'') == 0){								//Nessun File Selezionato?
		$_SESSION['error'] = "Inserire Un Immagine";
		header("Location: homepage.php");
	}
	else{
		move_uploaded_file( $img["tmp_name"] , "img/" . $img['name']);		//Muovo il file nella cartella. 
		$name = $img['name'];										//Il "tmp_name" corrisponde a una memorizzazione temporanea del file sul server, se il file non viene correttamente
		$dd = mysql_query("SELECT id FROM imglist", $connection);	//processato si cancellerà (problema con file > +-2Mb )
		$ID = (mysql_num_rows($dd) + 1);
		$listimg = mysql_query("SELECT imgname FROM imglist",$connection);
		$arr = array();
		for($i=0;$i<mysql_num_rows($listimg);$i++){
			array_push($arr,mysql_result($listimg,$i));
		}
		if(!in_array($name,$arr)){							//Se il name_img non è già nella imgs_tab
			mysql_query("INSERT INTO imglist(ID,imgname) VALUES ($ID,'$name')",$connection);	//Inserisco new_img
			$str = "img/".$name;			//Creo Pathfile
			$str2=$_SESSION['user'];		//Memorizzo user_name della sessione in stringa, session['username'] problemi in query per singoli apici
			$_SESSION['img'] = $str;		// Salvo img per next_session
			$imgris = mysql_query("SELECT idpk FROM tabimg,userstab WHERE tabimg.idfk = userstab.ID AND userstab.name = '$str2'",$connection); //Prendo ID user_img
			$ris = mysql_result($imgris,0);
			mysql_query("UPDATE tabimg SET imglink = '$str' WHERE tabimg.idpk = '$ris' ",$connection);		//Update user_img
			header("Location: homepage.php");
			mysql_close($connection);
		}
		else{
			$_SESSION['error'] = "Nome Immagine Già Presente Nel DB";
		}
	}
	header("Location: homepage.php");	
?>