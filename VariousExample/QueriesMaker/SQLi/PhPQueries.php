<?php
	if(isset($_REQUEST['txtArea']) && count($_REQUEST['txtArea']) <= 1 ){
		if(count($_REQUEST['dbName']) == 0){
			$err = "Devi Inserire Il Nome Di Un DB...";
		}
		else{
			if(($db = new mysqli('localhost:3306', 'root', '', $_REQUEST['dbName'])) != Null){
				$ris = $db->query($_REQUEST['txtArea']);
				if(!$ris){
					$err = "Errore nell'esecuzione della query...";
				}
				else{
					$fields = $ris->field_count;
				}
				$db->close();
			}
			else{
				$err = "Errore nella connessione con il DB...";
			}
		}
	}
	else{
		$err = "Inserire una query valida...";
	}
?>

<html>
	<head>
		<title>
			Query Result
		</title>
	</head>
	<body style="background-color:black;">
		<center>
		<a style="color:white;position:absolute;left:5%;" href="Query.html">Ritorna</a> 
		<div style="position:absolute;left:10%;
		<?php if(isset($err)){ echo "right:10%;";}?> top:30%;background-color:yellow;border-radius:10px;"> <br/> <br/>
			<?php
				if(isset($err)){
					echo "$err <br/> <br/>";
				}
				else{
					echo "<font size=5> Risultati Trovati: </font> <br/> <table> ";
					$c = True;
					while($qArrImAMightyPirate = $ris->fetch_assoc()){
						if($c){
							echo "<tr>";
							$k = array_keys($qArrImAMightyPirate);
							for($n=0;$n<$fields;$n++){
								$col1 = dechex(rand(0,16));
								$col2 = dechex(rand(0,16));
								echo "<td><font color='#$col1 0 $col2' size=3>$k[$n]</font><td>";
							}
							echo "</tr>";
						}
						echo "<tr>";
						$val = array_values($qArrImAMightyPirate);
						for($n=0;$n<$fields;$n++){
							echo "<td>$val[$n]<td>";
						}
						$c = False;
						echo "</tr>";
					}
					echo "</table> <br/> <br/>";
					//$ris->free();
					$ris->close();
				}
			?>
		</div>
		</center>
	</body>
</html>