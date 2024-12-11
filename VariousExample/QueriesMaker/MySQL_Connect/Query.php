<?php
	if(isset($_REQUEST['txtArea']) && count($_REQUEST['txtArea']) <= 1 ){
		if(count($_REQUEST['dbName']) == 0){
			$err = "Devi Inserire Il Nome Di Un DB...";
		}
		else{
			$con = mysql_connect('localhost','root');
			if(mysql_select_db($_REQUEST['dbName'],$con)){
				$ris = mysql_query($_REQUEST['txtArea'], $con);
				if(!$ris){
					$err = "Errore nell'esecuzione della query...";
				}
				else{
					$fields = mysql_num_fields($ris);
				}
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
					while($qArrImAMightyPirate = mysql_fetch_assoc($ris)){
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
				}
			?>
		</div>
		</center>
	</body>
</html>