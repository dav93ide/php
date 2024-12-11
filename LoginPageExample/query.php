<html>
	<head>
		<style>
			body{
				background:url(img/kali.jpg);
				background-position:0 50;
				-moz-background-size: cover;
				-webkit-background-size: cover;
				background-size: cover;
			}
		</style>
		<title> Query </title>
	</head>
		<?php
	session_start();
	if($_SESSION['user'] != 'root'){
		$_SESSION['error'] = "Non Puoi Eseguire Interrogazioni Senza Aver Loggato Come Admin!!..";
		header("Location: index.php");
	}
	else{
		if(isset($_GET['SubmitQuery'])){
			$connection = mysql_connect("localhost","root");
			mysql_select_db("user_db",$connection);
			$ris = mysql_query($_GET['query'],$connection);
			$rows = mysql_num_rows($ris);
			$fields = mysql_list_fields('user_db','userstab',$connection);
			$arr=array();
			for($j=0;$j<mysql_num_fields($fields);$j++){
				array_push($arr,mysql_field_name($fields,$j));
			}
			if( $rows != 0){								//Creo Tabella Results
				$i=0;
				echo "<table bgcolor= \"#000\" border=\"1\" cellspacing=\"4\">";
				echo " <tr> <td> <font color='white' size=5> N_Risultato </td>";
				for($j=0;$j<count($arr);$j++){
					echo "<td> <font color='white' size=5>" . $arr[$j]."</td>";
				}
				echo "</tr>";	
				while($rows = mysql_fetch_array($ris,MYSQL_ASSOC)){
					$i+=1;
					echo "<tr><td><font color='white' size=3> " . $i . "</td>";
					for($j=0;$j<count($arr);$j++){
						echo "<td><font color='white' size=3>" . $rows["$arr[$j]"] . "</td>";
					}
					echo "</tr></table></font>";
				}
			}
			echo "<br/> <br/><font color='white' size=5>Clicca <a href=\"homepage.php\"> QUI </a> per ritornare all'homepage.</font>";
		}
	}
	mysql_close($connection);
	?>
	</body>
</html>

