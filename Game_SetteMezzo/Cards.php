<?php
	// Classe Carte
	class Cards{
		private $nomiCarte = Array(
				'Denari1', 'Denari2', 'Denari3', 'Denari4', 'Denari5', 'Denari6', 'Denari7', 'Denari8', 'Denari9', 'Denari10',
				'Coppe1','Coppe2','Coppe3','Coppe4','Coppe5','Coppe6','Coppe7','Coppe8','Coppe9','Coppe10',
				'Spade1', 'Spade2', 'Spade3', 'Spade4', 'Spade5', 'Spade6', 'Spade7', 'Spade8', 'Spade9', 'Spade10', 
				'Bastoni1', 'Bastoni2', 'Bastoni3', 'Bastoni4', 'Bastoni5', 'Bastoni6', 'Bastoni7', 'Bastoni8', 'Bastoni9','Bastoni10');
		private $vettoreCarte;		// Valori Interi
		private $valoreAttuale;
		private $hasJolly;
		
		function __construct(){
			$this -> vettoreCarte = Array();
			$this -> valoreAttuale = 0;
			$this -> hasJolly = False;
		}
		
		public function addCard($carta){
			array_push($this->vettoreCarte, $carta);
			if($carta == 9) {
				$this -> hasJolly = True;
			}
			if($this->hasJolly){
				$this -> calcolaValori();
			}
			else{
				$val = ($carta % 10) + 1;
				if($val < 8){
					$this-> valoreAttuale +=  $val;
				}
				else{
					$this-> valoreAttuale += 0.5;
				}
			}
		}
		
		private function calcolaValori(){
			$this -> valoreAttuale = 7;
			$valoreJolly = 7;
			$valoriPrecedenti = 0;
			foreach($this->vettoreCarte as $v){
				if($v == 9){
					continue;
				}
				$val = ($v % 10) + 1;
				if($val<8){
					$valoreJolly = ((($valoreJolly-$val)>=1) ? ($valoreJolly-$val) : 1);
					$this -> valoreAttuale = $valoreJolly + $val + $valoriPrecedenti;
					$valoriPrecedenti += $val;
				}
				else{
					$this -> valoreAttuale = $valoreJolly + 0.5 + $valoriPrecedenti;
					$valoriPrecedenti += 0.5;
				}
			}
		}
		
		public function getVettoreCarte(){
			return $this -> vettoreCarte;
		}
		
		public function getCards(){
			$arrCarte = Array();
			foreach($this -> vettoreCarte as $k){
				array_push($arrCarte,$this->nomiCarte[$k]);
			}
			return $arrCarte;
		}
		
		public function getCardName($index){
			return $this -> nomiCarte[$index];
		}
		
		public function getCardsNumber(){
			return count($this -> vettoreCarte);
		}
		
		public function getValoreAttuale(){
			return $this -> valoreAttuale;
		}
		
		public function haveCard($index){
			return in_array($index,$this -> vettoreCarte);
		}
		
		// Traduce Una Carta (es: "Cavallo Di Spade", Invece Di "Spade9")
		function traduciCarta($carta){
			$str1 = substr($carta,0,-1);
			$str2 = substr($carta,-1);
			switch($str2){
				case 1:
					$str2 = 'Asso Di ';
					break;
				case 8:
					$str2 = 'Fante Di ';
					break;
				case 9:
					$str2 = 'Cavallo Di ';
					break;
				case 0:
					$str2 = 'Re Di ';
					$str1 = substr($carta,0,-2);
					break;
				default:
					$str2 .= " Di ";
					break;
			}
			return ($str2 . $str1);
		}
	}
?>