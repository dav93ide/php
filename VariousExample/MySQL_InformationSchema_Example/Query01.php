<?php
	include('funzDB.php');
	initDB();
?>
<html>
	<head>
		<title> Prova Interrogazioni Database </title>
	</head>
	<body>
		<font color=blue size=6> Method: </font> <br/>
			+] SELECT * FROM information_schema.tables			=> DBs Name + Tables Name "provatabella" <br/>
			+] SELECT * FROM information_schema.columns			=> All Table Columns <br/>
			+] SELECT * FROM mysql.user_error					=> All mysql Users
			+] select * from information_schema.USER_PRIVILEGES => All User Privileges
		<center><br/><br/>
		<form target="" method="GET">
			<input type="text" name="query" placeholder="Inserire La Query" /> <br/>
			<input type="SUBMIT" name="send" value="Invia" />
		</form> <br/> <br/>
		<?php
			if (isset($_GET['query'])){
				$result = exQuery($_GET['query']);
				$c = True;
				echo "<table>";
				$fields = mysql_num_fields($result);
				while($arr = mysql_fetch_assoc($result)){
					if($c){
						echo "<tr>";
						$k = array_keys($arr);
						for($n=0;$n<$fields;$n++){
							echo "<td><font color='red' size=3>|$k[$n]|</font><td>";
						}
						echo "</tr>";
					}
					echo "<tr>";
					$val = array_values($arr);
					for($n=0;$n<$fields;$n++){
						echo "<td>|$val[$n]|<td>";
					}
					$c = False;
					echo "</tr>";
				}
				echo "</table>";
			}
		?>		
	</center>
	</body>
</html>