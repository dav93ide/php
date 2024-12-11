<!--|☼|»»»»»»»»»»»»»»»»»»»»»»x««««««««««««««««««««««|☼|
    |☼|      ________________________________       |☼|
    |☼|      |Rubrica|     |------|      |PhP| 	    |☼|
    |☼|      ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯       |☼|
    |☼|»»»»»»»»»»»»»»»»»»»»»»x««««««««««««««««««««««|☼|-->

<html>
	<head>
		<style>
			p { 
				color: #F00;
				font-family:"Verdana","Times New Roman";
			}
		</style>
		<title> Rubrica </title>
	</head>
	<body>
		<center>
		<p> Inserimento Dati </p>
		<form action="" method="POST">
			<input type="text" name="nome" placeholder="Inserire Nome" />
			<input type="text" name="cognome" placeholder="Inserire Cognome" /> <br/> <br/>
			<input type="text" name="telefono" placeholder="Numero Di Telefono" />	
			<input type="text" name="email" placeholder="Email" /> <br/> <br/>
			<input type="submit" name="send" value="Invia" />
			<input type="reset" value="Resetta" /> <br/>
			<input type="submit" name="resrub" value="Resetta Rubrica" />
		</form>
		<?php
			session_start();
			// Crea Array Se Non Esiste O Se Premuto "ResettaRubrica"
			if(!isset($_SESSION['contatti']) || isset($_REQUEST['resrub'])){
					$_SESSION['contatti'] = Array();
			}
			// Invia Cliccato
			if(isset($_REQUEST['send'])){
				// Controllo Campi
				if($_REQUEST['nome'] != null && $_REQUEST['cognome']!=null && $_REQUEST['telefono']!=null){
				// Inserisce In Array Nuovo Oggetto "Contatto"
				array_push($_SESSION['contatti'], new Contatto($_REQUEST['nome'],$_REQUEST['cognome'],$_REQUEST['telefono'], $_REQUEST['email']));
				}
				else {
					echo "<br/> <br/> Dati Incompleti <br/> <br/>";
			} }
			// Se Elementi In Array > 0 
			if(count($_SESSION['contatti'])>0){
					echo "<br/> <br/> <br/> CONTATTI <br/> <br/><br/>";
					// Itero E Richiamo "toString" Su Ogni Elemento
					foreach($_SESSION['contatti'] as $n=>$c){
						echo "<br/>" . ($n+1)."] Info: <br/>".$c -> toString();
			}		}
			
			class Contatto{
				
				private $nome;
				private $cognome;
				private $telefono;
				private $email;
				
				public function __construct($n,$c,$t,$e){
					$this -> nome = $n;
					$this -> cognome = $c;
					$this -> telefono = $t;
					$this -> email = (($e!='')?$e:"/////");
				}
				
				public function toString(){
					return 
						"<br/>Nome = " . $this -> nome .
						"<br/>Cognome = " . $this -> cognome .
						"<br/>Telefono = " . $this -> telefono .
						"<br/>Email = " . $this -> email . 
						"<br/>----------------------------------------";
				}
			}
		
		
		?>
		
		</center>
		
	</body>
</html>