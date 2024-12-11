<?php

	// Serializza Gli Oggetti "Computer.dat" E "Username.dat"
	function serializzaOggetti(){
		$file = fopen('dataDir/C0MPUT3R.dat','w');
		fwrite($file, serialize($_SESSION['computer']));
		fclose($file);
		if(isset($_SESSION['utente'])){
			$fileName = 'dataDir/' . $_SESSION['utente'] -> getName() . ".dat";
			$file = fopen($fileName,'w');
			fwrite($file,serialize($_SESSION['utente']));
			fclose($file);
		}
	}
	
	// Serializza Solo "Username.dat"
	function serializzaPlayer(){
		$fileName = 'dataDir/' . $_SESSION['utente'] -> getName() . ".dat";
		$file = fopen($fileName,'w');
		fwrite($file,serialize($_SESSION['utente']));
		fclose($file);
	}
	
	// Leggo Tutte Le Righe Di Un File Contenente Un Oggetto Serializzato E Le Ritorno In Un'Unica Stringa
	function readFromFileSerializedObject($fileName){
		$file = fopen($fileName,'r');
		$riga = fgets($file);
		$str = '';
		while($riga != ''){
			$str .= $riga;
			$riga = fgets($file);
		}
		return $str;
	}
	
?>