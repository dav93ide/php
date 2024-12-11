<?php
	// Classe Giocatore
	class Giocatore extends Computer{

		private $portafogli;
		
		public function __construct($username){
			parent::__construct($username);				// Invoco Metodo Costruttore Super Classe
			$this -> portafogli = 1000;
		}
		public function getPortafogli(){
			return $this -> portafogli;
		}			
		public function addPortafogli($val){
			$this -> portafogli += $val;
		}
		
		// Override Di "toString()"
		public function toString(){
			// Riprendo "toString()" Della SuperClasse E Aggiungo Attributo Di Classe
			return "<br/>" . parent::toString() .	
				"<font color='blue'>[+] Portafogli => <font color='red'>" . (($this -> portafogli > 0) ? $this -> portafogli : "<font color = #A0F>CREDITO_ESAURITO!</font>") . " &euro; </font>[+]</font> <br/> <br/>";
			
		}
	}
?>