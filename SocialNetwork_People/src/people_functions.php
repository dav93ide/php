<?php

	function make_user_dir($name){
		if(!file_exists('data/'.$name)){
			mkdir('data/'.$name);
		}
	}
	
	function check_valid_mail($email){
		$mail = explode("@",$email)[1];
		$mail = explode(".",$mail)[0];
		$validMail = Array("gmail","libero","yahoo","hotmail");
		if(!in_array($mail, $validMail)){
			return True;
		}
		else{
			return False;
		}
	}
	
	function add_pass_request($username,$email){
		$fname = $_SERVER['DOCUMENT_ROOT'] . "/People/passwordRequests.txt";
		if(!file_exists($fname)){
				$file = fopen($fname,"w");
		}
		else{
				$file = fopen($fname,"a+");
		}
		$time = date("j F Y h:i:s A");
		$str = "\n\n
		#############################################################
		#
		#	+ Forgot Password Request +
		#
		#	+ Username: $username
		#	+ Email: $email 
		#	+ Date:	$time 
		#
		#############################################################
		\n\n";
		fwrite($file,$str);
		fclose($file);
	}
?>
