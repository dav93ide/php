<?php
	
	function getUserPages(){
		$dir = dir('dataDir/'.$_SESSION['username']);
		$arrFile = array();
		while( false !== ($file = $dir -> read())){
			array_push($arrFile,$file);
		}
		$dir -> close();
		return $arrFile;
	}
	
	function writePage($fileName, $text){
		$text2 = ((substr($fileName,-1) == "p") ? textValidation($text) : False);
		if($text2 === False){
			$filename = "dataDir/" . $_SESSION['username'] . "/" . $fileName;
			$file = fopen($filename, "w");
			$s = '<form style="position:absolute;right:1%;bottom:1%;" action="../../user_page.php"><input value="ReTuRn" name="return" type="SUBMIT" style="color:#00F;background-color:yellow;border:2px solid black;border-radius:25px;" /></form>';
			if(!strpos($text,$s)){
			$comment = "\n <!-- Non Cambiare Queste Linee! -->\n";
			$text .= $comment . '<form style="position:absolute;right:1%;bottom:1%;" action="../../user_page.php"><input value="ReTuRn" name="return" type="SUBMIT" style="color:#00F;background-color:yellow;border:2px solid black;border-radius:25px;" /></form>';
			}
			fwrite($file,$text);
			fclose($file);
			return True;
		}
		else{
			$temp = "\n/------------------------------------------------------------------------------------------------------------\\
			\nQuesto è un file txt temporaneo contenente il testo precedentemente inserito che non ha superato l'autenticazione. \nIn fondo al testo è possibile trovare le parole che non hanno permesso l'inserimento.\nTentare di inserire una nuova pagina andrà a sovrascrivere questo file qualora anch'essa venisse invalidata.\n
			\n\------------------------------------------------------------------------------------------------------------/
			\n Dopo Questo Divisore Inizierà Il Testo Della Pagina Che Era Stata Inserito Precedentemente \n
			\n|------------------------------------------------------------------------------------------------------------|
			\n\n\n";
			$temp .= $text2;
			$filename = "dataDir/" . $_SESSION['username'] . "/temp.txt";
			$file = fopen($filename, "w");
			fwrite($file,$temp);
			fclose($file);
			return False;
		}
	}

	function readPage($fileName){
		$filename = "dataDir/" . $_SESSION['username'] . "/" . $fileName;
		if(file_exists($filename)){
			$file = fopen($filename, "r");
			$str = "";
			while (($val = fgets($file)) != False){
				$ctr = "<!-- Non Cambiare Queste Linee! -->";
				if(strpos($val,$ctr)){
					break;
				}
				$str .= $val;	
			}
			fclose($file);
			return $str;
		}
		return False;
	}
	
	function delPage($fileName){
		$filename = "dataDir/" . $_SESSION['username'] . "/" . $fileName;
		if(file_exists($filename)){
			unlink($filename);
			return True;
		}
		return False;
	}
	
	function textValidation($text){
		$file = fopen("Invalid.txt","r");
		$check = False;
		$checkText = " " . $text;
		while (($val = fgets($file)) != False){
			$b = stripos($checkText,substr($val,0,-2));
			if($b){
				if(!$check){
					$text .= "
			\n\n\n\n
			\n|-------------------------------------------------------------------------------------------------------------|
			\nParole Non Ammesse Incontrate Nel Testo:
			\n|-------------------------------------------------------------------------------------------------------------|
			\n\n";
				}
				$text .= "$val \n";
				$check = True;
			}
		}
		return (($check) ? $text : False);
	}


?>