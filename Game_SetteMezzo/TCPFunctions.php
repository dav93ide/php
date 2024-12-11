<?php
	// Creazione E Connessione Della Socket
	function initSocket(){
		$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
		socket_connect($socket,'127.0.0.1',666);
		return $socket;		
	}
	
	// Recupera Nome Gioc2 + ID Game
	function sendGameRequest(){
		$answer = array();
		$socket = initSocket();
		$send = "1" . $_SESSION['utente'] -> getName();
		socket_write($socket,$send);
		$player2 = socket_read($socket,1024);	// Recupera Nome Altro Gioc
		array_push($answer,$player2);
		if($player2 != "708"){
			socket_write($socket,"ACK");
			$idGame = socket_read($socket,1024);		// Recupera ID Game
			array_push($answer,$idGame);
			socket_write($socket,strval($_SESSION['puntata']));
			socket_read($socket,1024);
			socket_close($socket);
			return $answer;
		}
		return False;
		
	}
	
	// Richiede Puntata Giocatore 2
	function moneyRequest($playerName,$idGame){
		$socket = initSocket();
		$send = "8" . $idGame;
		socket_write($socket,$send);
		socket_read($socket,1024);
		socket_write($socket,$playerName);
		$puntata = intval(socket_read($socket,1024));
		socket_close($socket);
		return $puntata;
	}
	
	// Passa Turno
	function sendPass($idGame){
		$socket = initSocket();
		$send = "6" . $idGame;
		socket_write($socket,$send);
		return;
	}
	
	// Invia Fine Partita
	function sendEndGame($idGame){
		$socket = initSocket();
		$send = "5".$idGame;
		socket_write($socket,$send);
		return;
	}
	
	// Invia Carte Computer E Carta Utente
	function sendCards($userCard,$pcCards,$gameID){
		$socket = initSocket();
		$send = "2" . $gameID;
		socket_write($socket,$send);
		if(socket_read($socket,1024) == "ACK"){
			socket_write($socket,$userCard);
			if(socket_read($socket,1024) == "ACK"){
				if($pcCards != null){
					foreach($pcCards as $p){
						socket_write($socket,$p);
						socket_read($socket,1024);
					}
				}
				socket_write($socket,"END");
				if(socket_read($socket,1024) == "ACK"){
					socket_close($socket);
					return True;
				}	
			}
		}
		socket_close($socket);
		return False;	
	}
	
	// Invia Bottone Fine Partita Premuto
	function endGamePressed($gameID){
		$socket = initSocket();
		$send = "7" . $gameID;
		socket_write($socket,$send);
		return;
	}
	
	//Legge Carta Utente E Carte Computer
	function readCards($gameID){
		$arrCards = array();
		$socket = initSocket();
		$send = "3" . $gameID;
		socket_write($socket,$send);
		$cards = socket_read($socket,1024);
		if($cards != null){
			if($cards == "Pass"){
				return $cards;
			}
			else{
				if($cards == "Ended"){
					return $cards;
				}
				else{
					$arrCards = explode(".",$cards);
					return $arrCards;
				}	
			}
		}
		socket_close($socket);
		return False;
	}

	// Controlla Se Vi Sono Carte Da Leggere
	function checkCards($gameID){
		$socket = initSocket();
		$send = "4" . $gameID;
		socket_write($socket,$send);
		$answer = socket_read($socket,1024);
		if($answer != "1"){
			return False;
		}
		else{
			return True;
		}
	}
	



?>