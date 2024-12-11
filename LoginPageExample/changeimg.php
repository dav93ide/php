<html>
	<head>
		<title> Change Image </title>
	</head>
	<body>
		<p><center> Inserisci Un Immagine</center> </p>
		<?php
			session_start();
			$connection = mysql_connect("localhost","root");
			mysql_select_db("user_db",$connection);
			$ris = mysql_query('SELECT * FROM imglist',$connection);			// Estraggo contenuto imglist
			$rows = mysql_num_rows($ris);										//Conto righe
			$fields = mysql_list_fields('user_db','imglist',$connection);		//Listo i nomi dei campi
			$arr=array();
			for($j=0;$j<mysql_num_fields($fields);$j++){
				array_push($arr,mysql_field_name($fields,$j));				//Aggiungo nome_campo a array
			}
			if( $rows != 0){			//Se ho righe
				$i=0;
				echo "<table bgcolor= \"#000\" border=\"1\" cellspacing=\"4\">";		//Creo Tabella
				echo " <tr> <td> <font color='white' size=5> N_Risultato </td>";
				for($j=0;$j<count($arr);$j++){										//Intestazione con nome_campi tabella
					echo "<td> <font color='white' size=5>" . $arr[$j]."</td>";
				}
				echo "</tr>";	
				while($rows = mysql_fetch_array($ris,MYSQL_ASSOC)){						// Finché ottengo array associativo in $rows da $ris continuo
					$i+=1;
					echo "<tr><td><font color='white' size=3> " . $i . "</td>";
					for($j=0;$j<count($arr);$j++){									//Ciclo per num_campi
						echo "<td><font color='white' size=3>" . $rows["$arr[$j]"] . "</td>";	//Uso nome_campo(cioé $arr[$j]) come key dell'array
					}
					echo "</tr></font>";
				}
			}
		?>
		<form enctype="multipart/form-data" action ="uploading.php" method="POST">		
			<input type="file" name="image"/>											<!--Iserimento File-->
			<br/>
			<input type="submit" value="Send-Img"/>
		</form>
	</body>
</html>