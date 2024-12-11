<?php
	include('DB_Functions.php');
	
	session_start();
	checkDB();
	if(!file_exists('dataDir')){
		mkdir('dataDir');
	}
	if(isset($_SESSION['username']) && isset($_COOKIE['cookie'])){
		header('location:user_page.php');
	}
?>

<html>
	<head>
		<title> 
			Web Making
		</title>
	</head>
	<body style="background-color:black;">
		<center>
			<br/><br/><br/>
			<form action='check.php' method='POST'>
				<input type="text" name="username" placeholder="Username" />
				<input type="password" name="pass" placeholder="Password" /> <br/> <br/>
				<input type="SUBMIT" name="Log-In" value="Log-In" />
				<input type="SUBMIT" name="Registrati" value="Registrazione" />
			</form> 
			<br/><br/><br/>
			
			<?php
				if(isset($_SESSION['Error'])){
					echo "<font color=white>".$_SESSION['Error']."</font><br/>";
					unset($_SESSION['Error']);
				}
				if(isset($_SESSION['Message'])){
					echo "<font color=white>".$_SESSION['Message']."</font><br/>";
					unset($_SESSION['Message']);
				}
			?>
	</body>
</html>
		