<?php
	session_start();
	include('DB_Functions.php');
	include('File_Functions.php');
	unset($_SESSION['link']);
?>

<html>
	<head>
		<title>
			Page List
		</title>
	</head>
	<body style="background-color:black;">
		<center>
			<font color=yellow size=10> Lista Pagine Utenti </font>
			<br/> <br/> <br/>
			<div style="position:absolute;right:1%;left:1%;top:10%;bottom:10%;background-color:#F0F;border:2px solid black;border-radius=15px;">
				<?php
					$arrImAMightyPirate = getUserList();
					if(!$arrImAMightyPirate){
						echo "<br/><br/>Non Ci Sono Pagine Disponibili";
					}	
					else{
						echo '<form action="checkFollw.php" method="POST">';
						foreach($arrImAMightyPirate as $k => $v){
							if($_SESSION['username'] == $k){
								continue;
							}
							echo "<ul> Utente => $k <br/>";
							foreach($v as $vv){
								echo "<li> <input type=\"SUBMIT\" style=\"border:none;background-color:#F0F;\"value=\"$vv\" 
								name=\"$k\" /> </li>";
							} 
							echo "</ul>";
						}
						echo "</form>";
					}
				?>			
			</div>
			<div style="position:absolute;right:1%;left:1%;top:90%;bottom:0%;background-color:#F0F;border:2px solid black;border-radius=15px;">
				Ritorna Alla Tua Pagina Utente <br/>
				<a style="color:#FFF;" href="user_page.php"> Link </a>
			</div>
		</center>
	</body>
</html>