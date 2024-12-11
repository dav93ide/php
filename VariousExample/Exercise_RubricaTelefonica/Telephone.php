
<!--|x|=============================================|x|
    |x|      ________________________________       |x|
    |x|         |Telephone||------||PhP|	 	    |x|
    |x|      ¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯¯       |x|
    |x|=============================================|x|-->
<?php
	/* Crea Il File Qualore Non Esistesse */
	if(!file_exists('elenco.txt')){
		$file = fopen('elenco.txt', 'w');
		fwrite($file,fileSpacer());
	}
	
	/* Intestazione HTML */
	echo "	<html>
	<head>
		<title> Telephone </title>
		<center> <h1> [x] Rubrica Telefonica [x] </h1> <center>
	</head>
	<body>
	<br/><br/><br/>
	<center>";
	
	/* Gruppo Di Controllo Input */
if(isset($_POST['name']) || isset($_POST['sname']) || isset($_POST['ssurname'])){
	echo "<a href='Telephone.php'> Ritorna Alla Pagina </a> <br/> <br/>";
	if(isset($_POST['sname']) || isset($_POST['ssurname'])){
		echo "Ricerca Eseguita Per : <br/> Nome => " . $_POST['sname'] . "<br/> Cognome => " . $_POST['ssurname'] . "<br/>" . fileSpacer();
		searchContact($_POST['sname'],$_POST['ssurname']);
	}
	else{
	if(($_POST['name'] != null) && (!isset($_POST['list'])) && (!isset($_POST['search'])) && ($_POST['surname']!=null) && ($_POST['cel']!=null)){
		echo "<font color='red' size=5><br/>[***] Dati Aggiornati [***]";
		$obj = new contattiTel($_POST['name'],$_POST['surname'],$_POST['cel'],(($_POST['mail']!='') ? $_POST['mail'] 			: 'Nessuna Mail'));
		$file = fopen('elenco.txt','a+');
		foreach($obj -> getList() as $c => $v){
			switch($c){
				case 'Nome':
					fwrite($file,"\r\n0:" . $v);
					break;
				case 'Cognome':
					fwrite($file,"\r\n1:" . $v);
					break;
				case 'Numero_Telefono':
					fwrite($file,"\r\n2:" . $v);
					break;
				case 'E-Mail':
					fwrite($file,"\r\n3:" . $v);
					break;
			}
		}
		fwrite($file,"\r\n".fileSpacer());
		fclose($file);
	}
	else{
		if(isset($_POST['list']) && isset($_POST['search'])){
			echo "<font color='green' size=5> [***] Selezionare Una Sola Opzione [***]";
		}
		else{
			if(isset($_POST['search'])){
				echo "<font color='blue' size =7> ||| Ricerca ||| <br/> <br/> </font>
				<font size=2>[---] E' Possibile Effettuare Ricerche In Base A Nome E/O Cognome [---] </font> <br/> <br/>
				<form action='' method='POST'>
					<input type='TEXT' name='sname' placeholder='Nome' />
					<input type='TEXT' name='ssurname' placeholder='Cognome' /><br/>
					<input type='SUBMIT' value='cerca'/>
				</form>";
			}
			else{
				if(isset($_POST['list'])){
					echo "<font color='blue' size=7>||| Lista Contatti ||| </font> <br/> <br/>";
					echo printList();
				}
				else{
					echo "<font color='green' size=5>[***] Inserire Valori [***]";
				}
			}
		}
	}
	
	/* Chiusura HTML */
	echo "			</font> 
				</center>
			</body>
		</html> ";
	$_POST['name'] = null;
	$_POST['surname'] = null;
	$_POST['cel'] = null;
	$_POST['mail'] = null;
}}
else{
	echo "
		<form action='' method='POST'>
			<input type='TEXT' name='name' placeholder='Nome' />
			<input type='TEXT' name='surname' placeholder='Cognome' /><br/>
			<input type='TEL' name='cel' placeholder='Numero Di Telefono' />
			<input type='EMAIl' name='mail' placeholder='E-Mail' /> <br/>
			<p> Richiedi Lista : <INPUT TYPE='CHECKBOX' NAME='list' value='1' /> </p>
			<p> Cerca Contatto : <INPUT TYPE='CHECKBOX' NAME='search' value='1' /> </p>
			<input type='SUBMIT' value='Invia'/>
			<input type='RESET' value='Reset'/>
		</form>";
}
	
	/* Ritorna Un Array Di Oggetti "contattiTel" Recuperati Tramite Parsing Del File "elenco.txt" */
	function initOb(){
		$vet = Array();
		$file = fopen('elenco.txt', 'r');
		$riga = fgets($file);
		$n = '';
		while($riga != ''){			
			switch($riga[0]){
				case '0':
					$n = substr($riga,2);
					break;
				case '1':
					$s = substr($riga,2);
					break;
				case '2':
					$t = substr($riga,2);
					break;
				case '3':
					$e = substr($riga,2);
					break;
				case '*':
					if($n!=''){
						array_push($vet, new contattiTel($n,$s,$t,(($e!='') ? $e : 'Nessuna Mail')));
					}
					break;
			}
			$riga = fgets($file);
		}					
		fclose($file);
		return $vet;
	}
	
	/* Cerco Se Nome E/O Cognome E' Tra I Contatti */
	function searchContact($nome,$cognome){
		$vetTel = initOb();
		$check = false;
		for($i=0;$i<count($vetTel);$i++){
			$n = $vetTel[$i] -> getName();
			$s = $vetTel[$i] -> getSurname(); 
			$n = strtolower(substr($n,0,(strlen($n)-2)));
			$s = strtolower(substr($s,0,(strlen($s)-2)));
			$nome = strtolower($nome);
			$cognome = strtolower($cognome);
			if(($n == $nome && $s == $cognome) || ($nome=='' && $s == $cognome) || ($cognome== '' && $n == $nome)){
				if($check == false){
					echo "<br/> [***] Corrispondenze Trovate [***] <br/>";
					echo fileSpacer();
					$check = true;
				}
				echo "<br/>[***] Contatto " . ($i+1) . " [***] <br/> <br/>";
				foreach(($vetTel[$i] -> getList()) as $c => $v){
					echo  $c . " => " . $v ."<br/>";
				}
				echo fileSpacer();
			}
		}
		if($check == false){
			echo "<br/> <br/> [***] Nessuna Corrispondenza Trovata [***]";
		}
	}
	
	/*Semplice Delimitatore*/
	function fileSpacer(){
		return "******************************";
	}

	/* Stampa Attributi Di Tutti Gli Oggetti "contattiTel" Recuperati Con "initOb()"*/
	function printList(){
		$vetTel = initOb();
		if(count($vetTel) == 0){
			echo " Nessun Contatto Disponibile <br/>";
		}
		else{
			for($i=0;$i<count($vetTel);$i++){
				echo "<br/>[***] Contatto " . ($i+1) . " [***] <br/> <br/>";
				foreach(($vetTel[$i] -> getList()) as $c => $v){
					echo  $c . " => " . $v ."<br/>";
				}
				echo fileSpacer();
			}
		}
	}
	
	/*Classe Contatti*/
	class contattiTel{
		
			private $name;
			private $surname;
			private $telephone;
			private $email;
		
		
		public function __construct($nome,$cognome,$num,$mail){
			$this -> name = $nome;
			$this -> surname = $cognome;
			$this -> telephone = $num;
			$this -> email = $mail;
		}
		
		/*
		public function __destruct(){
			echo "[+] Metodo Distruttore Chiamato [+]";
		}
		*/
		
		public function getList(){
			return Array(
			'Nome' => $this -> name,
			'Cognome' => $this -> surname,
			'Numero_Telefono' => $this -> telephone,
			'E-Mail' => $this -> email
			);
		}
		
		public function setName($s){
			$this -> name = $s;
		}
		
		public function setSurname($s){
			$this -> surname = $s;
		}
		
		public function setTelephone($s){
			$this -> telephone = $s;
		}
		
		public function setMail($s){
			$this -> email = $s;
		}
		
		public function getName(){
			return $this -> name;
		}
		
		public function getSurname(){
			return $this -> surname;
		}
		
		public function getTelephone(){
			return $this -> telephone;
		}
		
		public function getMail(){
			return $this -> email;
		}
		
	}
?>