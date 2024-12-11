<!DOCTYPE html>
<html>
    <head>  
         <title>Ciao</title>
         <meta charset="UTF-8" />
    </head>
    <body>
		<?php
			if(isset($_GET["nome"])) {
				echo "<p>Ciao " . $_GET["nome"] . "!</p>";
				echo "<p>Hai inviato i dati col metodo GET.</p>";
				echo '<p>Clicca <a href="post.html">qui</a> per vedere il metodo POST.</p>';
			} else {if(isset($_POST["nome"])) {
				echo "<p>Ciao " . $_POST["nome"] . "!</p>";
				echo "<p>Hai inviato i dati col metodo POST.</p>";
				echo '<p>Clicca <a href="get.html">qui</a> per vedere il metodo GET.</p>';
			} else {
				echo "<p>Ciao utente anonimo!</p>";
				echo '<p>Clicca <a href="get.html">qui</a> per vedere il metodo GET.</p>';
				echo '<p>Clicca <a href="post.html">qui</a> per vedere il metodo POST.</p>';
			} }
		?>
	</body>
</html>