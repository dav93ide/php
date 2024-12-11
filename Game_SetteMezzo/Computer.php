<?php
	// Classe Computer, Superclasse Di "Giocatore"
	class Computer{
		
		private $n_vittorie;
		private $n_sconfitte;
		private $n_partite;
		private $tot_vincita;
		private $max_vincita;
		private $max_perdita;
		private $nome;
		private $n_pareggi;
		
		public function __construct($name,$nVit=0,$nSco=0,$nPare=0,$nPart=0,$nTotV=0,$nMaxV=0,$nMaxP=0){
			$this -> nome = $name;
			$this -> n_vittorie = $nVit;
			$this -> n_sconfitte = $nSco;
			$this -> n_pareggi = $nPare;
			$this -> n_partite = $nPart;
			$this -> tot_vincita = $nTotV;
			$this -> max_vincita = $nMaxV;
			$this -> max_perdita = $nMaxP;
		}
		
		public function getName(){
			return $this -> nome;
		}
		
		public function addNPareggi(){
			$this->addNpartite();
			$this -> n_pareggi += 1;
		}
		
		public function addNVittorie($val){
			$this -> addNPartite();
			$this -> addTotVincita($val);
			if($this -> max_vincita < $val){
				$this -> setMaxVincita($val);
			}
			$this -> n_vittorie += 1;
		}
		
		public function addNSconfitte($val){
			$this -> addTotVincita(-$val);
			if($this->max_perdita < $val){
				$this -> setMaxPerdita($val);
			}
			$this -> addNPartite();
			$this -> n_sconfitte += 1;
		}
		
		private function addNPartite(){
			$this -> n_partite += 1;
		}
		
		private function addTotVincita($val){
			$this -> tot_vincita += $val;
		}
		
		private function setMaxVincita($val){
			$this -> max_vincita = $val;
		}
		
		private function setMaxPerdita($val){
			$this -> max_perdita = $val;
		}
		
		public function getNSconfitte(){
			return $this -> n_sconfitte;
		}
		
		public function getNVittorie(){
			return $this -> n_vittorie;
		}
		
		public function getNPareggi(){
			return $this -> n_pareggi;
		}
		
		public function getMaxVincita(){
			return $this -> max_vincita;
		}
		
		public function getMaxPerdita(){
			return $this -> max_perdita;
		}
		
		public function getTotVincita(){
			return $this -> tot_vincita;
		}
		
		public function getTotPartite(){
			return $this -> n_partite;
		}
		
		public function toString(){
			return "
			<font color='blue'>[+]  Nome => <font color='red'>" . $this -> nome . "</font>  [+] </font><br/>
			[+] Vincita Totale => " . $this -> tot_vincita . " &euro; [+] <br/>
			[+] Vincita Massima => " . $this -> max_vincita . " &euro; [+] <br/>
			[+] Perdita Massima => " . $this -> max_perdita . " &euro; [+] <br/>
			[+] Numero Vittorie => " . $this -> n_vittorie . " [+] <br/>
			[+] Numero Partite Totali => " . $this -> n_partite . " [+] <br/>
			[+] Numero Sconfitte => " . $this -> n_sconfitte . " [+] <br/>
			[+] Numero Pareggi => " . $this -> n_pareggi . " [+] <br/>";
		}	
	}
?>