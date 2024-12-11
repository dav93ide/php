<?php
	if(!isset($_REQUEST)){
		header("location: 404.php");
	}
	
	// Registrazione
	function new_user($name,$pass,$email,$birthdate,$first_name,$last_name,$sex,$secret_question,$secret_answer){
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db -> query("SELECT id FROM secretquestions WHERE question='$secret_question'");
		$id = $ris->fetch_assoc()['id'];
		$db -> query("INSERT INTO usertab(username,password,email,first_name,last_name,birth_date,sex,id_secret,secret_answer) VALUES ('$name','$pass','$email','$first_name','$last_name','$birthdate','$sex',$id,'$secret_answer')");
		$ris = $db -> query("SELECT id FROM usertab WHERE username='$name'");
		$id = $ris -> fetch_assoc()['id'];
		$db -> query("INSERT INTO userimg(id_img,id_user,img_name,profile,mex) VALUES(1,$id,'Default_Profile_Picture','y','Benvenuto In People Caro $name!')");
		$ris = $db -> query("SELECT id FROM usertab WHERE username='$name'");
		$id = $ris->fetch_assoc()['id'];
		$db -> query("INSERT INTO storedmex(id_user,id_mex) VALUES ($id,1)");
		$db->close();
		$ris -> close();
	}
	
	// Log-Time
	function log_time($name){
		$db = new mysqli('localhost','root','toor','people_db');
		$db -> query("UPDATE usertab SET lastlog = CURRENT_TIMESTAMP WHERE username='$name'");
		$db -> close();
	}
	
	// Cerca Nome Utente
	function search_name($name){
		if(get_id_from_name($name) && $name!='guest' && $name!='4dMiN'){
			return True;
		}
		else{
			return False;
		}
	}
	
	// Cerca Email
	function search_mail($email){
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db->query("SELECT id FROM usertab WHERE email='$email'");
		$db -> close();
		if($ris->num_rows==1){	
			$ris -> close();
			return True;
		}
		else{
			return False;
		}
	}
	
	// Confirm Email
	function confirm_mail($email,$name){
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db->query("SELECT id FROM usertab WHERE email='$email' AND username='$name'");
		$db -> close();
		if($ris->num_rows==1){	
			$ris -> close();
			return True;
		}
		else{
			return False;
		}
	}
	
	
	// Get Immagini Utente
	function get_user_imgs($name){
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db->query("SELECT im.img_link,usim.img_name,usim.mex,usim.upload_date FROM imglist im,userimg usim,usertab us WHERE im.id = usim.id_img AND usim.id_user=us.id AND us.username='$name'");
		$db->close();
		$arrImgLink = Array();
		$arrImgName = array();
		$arrImgMex = Array();
		$arrImgDate = Array();
		if($ris){
			while($arr = $ris-> fetch_assoc()){
				array_push($arrImgLink,$arr['img_link']);
				array_push($arrImgName,$arr['img_name']);
				array_push($arrImgMex,$arr['mex']);
				array_push($arrImgDate,$arr['upload_date']);
			}
			$ris->close();
			$arrImg = Array();
			array_push($arrImg,$arrImgLink);
			array_push($arrImg,$arrImgName);
			array_push($arrImg,$arrImgMex);
			array_push($arrImg,$arrImgDate);
			return $arrImg;
		}
		else{
			return False;
		}
	}
	
	// Get user profile picture
	function get_user_profile_picture($name){
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db->query("SELECT im.img_link FROM imglist im, userimg usim, usertab us WHERE im.id = usim.id_img AND usim.profile = 'y' AND us.id = usim.id_user AND us.username = '$name' ");
		$db->close();
		if($ris){
			$imgLink = $ris-> fetch_assoc()['img_link'];
			$ris->close();
			return $imgLink;
		}
		else{
			return False;
		}
	}
	
	// Get Info User
	function get_info_user($name){
		if(search_name($name)){
			$db = new mysqli('localhost','root','toor','people_db');
			$ris = $db -> query("SELECT * FROM usertab WHERE username='$name'");
			$db -> close();
			$arr = $ris -> fetch_assoc();
			$ris -> close();
			return $arr;
		}
		else{
			return False;
		}
		
	}
	
	// Get Mex
	function get_mex($name){
		$arrIdMitt = Array();
		$arrIdDest = Array();
		$arrMexText	= Array();
		$arrDateMex = Array();
		$arrMexGroup = Array();
		$db = new mysqli('localhost','root','toor','people_db');
		// Ottengo ID_Mittenti, ID_Destinatari
		$iduser = get_id_from_name($name);
		$mexGroups = $db -> query("
					SELECT mxt.mex_group
					FROM mextab mxt, storedmex st, usertab us
					WHERE mxt.id = st.id_mex AND st.id_user = $iduser ");
		if($mexGroups->num_rows > 0){
			while($arr = $mexGroups -> fetch_assoc()){
				if(!in_array($arr['mex_group'],$arrMexGroup)){
					$mex = $db -> query("SELECT message mex,id_mittente id_mit, id_destinatario id_dest, data_invio date FROM mextab WHERE mex_group =".$arr['mex_group']." AND id IN (SELECT id_mex FROM storedmex WHERE id_user=$iduser)");
					while($arrm = $mex -> fetch_assoc()){
						array_push($arrMexGroup,$arr['mex_group']);
						array_push($arrMexText,$arrm['mex']);
						array_push($arrIdMitt,$arrm['id_mit']);
						array_push($arrIdDest,$arrm['id_dest']);
						array_push($arrDateMex,$arrm['date']);
					}
				}
				else{
					continue;
				}
			}
		}
		else{
			$db -> close();
			return False;
		}
		$arrNameDest = Array();
		$arrNameMitt = Array();
		$arrFinal = Array();
		$db->close();
		$mex -> close();
		// Risolvo ID_Mittenti, ID_Destinatari
		for($i=0; $i<count($arrIdDest); $i++){
			$dest = get_name_from_id($arrIdDest[$i]);
			$mitt = get_name_from_id($arrIdMitt[$i]);
			if($dest && $mitt){
				array_push($arrNameDest,$dest);
				array_push($arrNameMitt,$mitt);
			}
			else{
				return False;
			}		
		}		
		array_push($arrFinal,$arrMexText);
		array_push($arrFinal,$arrMexGroup);
		array_push($arrFinal,$arrNameDest);
		array_push($arrFinal,$arrNameMitt);
		array_push($arrFinal,$arrDateMex);
		return $arrFinal;
	}
	
	// Cambia Email
	function change_email($name, $newMail){
		$db = new mysqli('localhost','root','toor','people_db');
		$db -> query("UPDATE usertab SET email='$newMail' WHERE username='$name'");
		$db->close();
	}
	
	// Cambia Password
	function change_pass($name,$newPass,$oldPass){
		if(check_password($oldPass,$name)){
			$db = new mysqli('localhost','root','toor','people_db');
			$password = password_hash($newPass, PASSWORD_DEFAULT);
			$db -> query("UPDATE usertab SET password='$password' WHERE username='$name'");
			$db -> close();
			return True;
		}
		else{
			return False;
		}
	}
	
	// Salva Messaggio (old Group)
	function save_mex_2($mex,$nameMitt,$group){
		$db = new mysqli('localhost','root','toor','people_db');
		$iduser = get_id_from_name($nameMitt);
		$ris = $db -> query("SELECT id_mittente idm, id_destinatario idd FROM mextab WHERE id_destinatario=$iduser AND mex_group=$group AND id_mittente!=$iduser OR id_mittente=$iduser AND mex_group=$group AND id_destinatario!=$iduser");
		$id = $ris->fetch_assoc();
		$nameDest = get_name_from_id(($id['idm'] == $iduser) ? $id['idd'] : $id['idm']);
		$db -> close();
		$ris -> close();
		return save_mex($nameDest,$nameMitt,$mex,$group);
	} 
	
	// Salva Messaggio (new Group)
	function save_mex($nameDest,$nameMitt,$mex,$old){
		$idDest = get_id_from_name($nameDest);
		$idMitt = get_id_from_name($nameMitt);
		if($idDest && $idMitt){
			$db = new mysqli('localhost','root','toor','people_db');
			if(!$old){
				$ris = $db -> query("SELECT MAX(mex_group) mex_group FROM mextab");
				$idMex = $ris->fetch_assoc()['mex_group'] + 1;
				$db -> query("INSERT INTO mextab(message,id_mittente,id_destinatario,mex_group) VALUES('$mex',$idMitt,$idDest,$idMex)");
				update_storedmex($idDest,$idMitt,$idMex);
				$ris -> close();
				$db -> close();
				return True;
			}
			else{
				$db -> query("INSERT INTO mextab(message,id_mittente,id_destinatario,mex_group) VALUES('$mex',$idMitt,$idDest,$old)");
				update_storedmex($idDest,$idMitt,$old);
				$db -> close();
				return True;
			}
		}
		else{
			return False;
		}
	}
	
	// Update StoredMex
	function update_storedmex($id_user,$id_other,$mexGroup){
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db -> query("SELECT MAX(id) id FROM mextab WHERE mex_group = $mexGroup");
		$idMex = $ris -> fetch_assoc()['id'];
		$db -> query("INSERT INTO storedmex(id_user,id_mex) VALUES ($id_user,$idMex)");
		$db -> query("INSERT INTO storedmex(id_user,id_mex) VALUES ($id_other,$idMex)");
		$ris -> close();
		$db -> close();
	}
	
	// Cambia Risposta Segreta
	function change_secret_question($answer,$password,$name,$question){
		if(check_password($password,$name)){
			$db = new mysqli('localhost','root','toor','people_db');
			$idq = get_question_id($question);
			$answer = md5($answer);
			$db -> query("UPDATE usertab SET id_secret=$idq,secret_answer='$answer' WHERE username='$name'");
			$db -> close();
			return True;
		}
		else{
			return False;
		}
	}
	
	// GET Risposta Segreta
	function get_secret_answer($username, $email,$questid){
		$db = new mysqli('localhost','root','toor','people_db');
		if(!confirm_mail($email,$username)){
			return False;
		}
		$ris = $db->query("SELECT secret_answer FROM usertab WHERE username='$username' AND id_secret = $questid");
		$db->close();
		if($ris->num_rows>0){
				$answer = $ris->fetch_assoc()['secret_answer'];
				$ris->close();
				return $answer;
		}
		else{
				$db->close();
				return False;
		}
	}
	
	// Get Question ID
	function get_question_id($question){
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db -> query("SELECT id FROM secretquestions WHERE question='$question'");
		$db -> close();
		if($ris){
			$id = $ris -> fetch_assoc()['id'];
			$ris -> close();
			return $id;
		}
		else{
			return False;
		}
	}

	// Check Password
	function check_password($password,$name){
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db -> query("SELECT username,password FROM usertab WHERE username='$name'");
		$db -> close();
		if($ris){
			$arr = $ris -> fetch_assoc();
			$ris -> close();
			if($name == $arr['username'] && password_verify($password,$arr['password'])){
				return True;
			}
		}
		else{
			return False;
		}
	}
	
	// Check Follower
	function check_follower($name,$otherName){
		$db = new mysqli('localhost','root','toor','people_db');
		$idUser = get_id_from_name($name);
		$idOtherUser = get_id_from_name($otherName);
		if(!$idUser || !$idOtherUser){
			return False;
		}
		$ris = $db -> query("SELECT id_usr,id_fllwr FROM followertab WHERE id_usr=$idOtherUser AND id_fllwr=$idUser");
		$db -> close();
		if($ris->num_rows == 1){
			$ris -> close();
			return True;
		}
		else{
			return False;
		}
	}
	
	// Aggiungi Follower
	function add_follower($name,$otherName){
		$db = new mysqli('localhost','root','toor','people_db');
		$idUser = get_id_from_name($name);
		$idOtherUser = get_id_from_name($otherName);
		if($idOtherUser){
			$db -> query("INSERT INTO followertab(id_usr,id_fllwr) VALUES ($idOtherUser,$idUser)");
			$db->close();
			return True;
		}
		else{
			$db->close();
			return False;
		}
	}
	
	// Get Followers
	function get_followers($name){
		$followers = Array();
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db -> query("SELECT username FROM usertab INNER JOIN followertab ON usertab.id =  followertab.id_usr AND followertab.id_fllwr IN (SELECT id FROM usertab WHERE username='$name')");
		$db -> close();
		if($ris){
			while($arr = $ris-> fetch_assoc()){
				array_push($followers,$arr['username']);
			}
			$ris -> close();
			return $followers;
		}
		else{
			return False;
		}
	}
	
	// Get User Followers
	function get_user_followers($name){
		$followers = Array();
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db -> query("SELECT username FROM usertab INNER JOIN followertab ON usertab.id =  followertab.id_fllwr AND followertab.id_usr IN (SELECT id FROM usertab WHERE username='$name')");
		$db -> close();
		if($ris){
			while($arr = $ris-> fetch_assoc()){
				array_push($followers,$arr['username']);
			}
			$ris -> close();
			return $followers;
		}
		else{
			return False;
		}
	}
	
	// Del Follower
	function del_follower($name,$otherName){
		$db = new mysqli('localhost','root','toor','people_db');
		$db->query("DELETE FROM followertab WHERE id_usr IN (SELECT id FROM usertab WHERE username='$otherName') AND id_fllwr IN (SELECT id FROM usertab WHERE username='$name')");
		$db -> close();
	}
	
	// Get ID From Name
	function get_id_from_name($name){
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db->query("SELECT id FROM usertab WHERE username='$name'");
		if($ris->num_rows>0 && $name!="4dMiN"){
			$id = $ris->fetch_assoc()['id'];
			$ris -> close();
			$db -> close();
			return $id;
		}
		else{
			$db -> close();
			return False;
		}
	}
	
	// Get Name From ID
	function get_name_from_id($id){
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db->query("SELECT username FROM usertab WHERE id=$id");
		if($ris->num_rows>0){
			$name = $ris->fetch_assoc()['username'];
			$ris -> close();
			$db -> close();
			return $name;
		}
		else{
			$db -> close();
			return False;
		}
	}
	
	// Blocca Utente
	function lock_user($name, $otherName){
		$userid = get_id_from_name($name);
		$otherid = get_id_from_name($otherName);
		if($otherid){
			$db = new mysqli('localhost','root','toor','people_db');
			$db -> query("INSERT INTO locked(id_usr,id_lckd) VALUES ($userid,$otherid)");
			$db -> query("DELETE FROM followertab WHERE id_usr=$userid AND id_fllwr=$otherid OR id_usr=$otherid AND id_fllwr=$userid");
			$db -> close();
			return True;
		}
		else{
			return False;
		}
	}
	
	// Sblocca Utente
	function unlock_user($name,$otherName){
		$userid = get_id_from_name($name);
		$otherID = get_id_from_name($otherName);
		if($otherID){
			$db = new mysqli('localhost','root','toor','people_db');
			$db -> query("DELETE FROM locked WHERE id_usr=$userid AND id_lckd=$otherID");
			$db -> close();
			return True;
		}
		else{
			return False;
		}
	}
	
	// Get Locked
	function get_lockeds($name){
		$id = get_id_from_name($name);
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db -> query("SELECT id_lckd FROM locked WHERE id_usr = $id");
		$db -> close();
		if($ris){
			$arrNames = Array();
			while($lock = $ris -> fetch_assoc()){
				$name = get_name_from_id($lock['id_lckd']);
				array_push($arrNames,$name);
			}
			$ris -> close();
			return $arrNames;
		}
		else{
			return False;
		}
	}
	
	// Aggiungi Immagine
	function add_img($name,$img,$img_name,$profile,$mex){
		$db = new mysqli('localhost','root','toor','people_db');
		$iduser = get_id_from_name($name);
		do{
			$imname = strval(time()) . "-$name";
			$ris = $db -> query("SELECT id FROM imglist WHERE img_link like '%$imname%'");
		} while($ris->num_rows > 0);
		$extns = pathinfo(basename($_FILES["image"]["name"]),PATHINFO_EXTENSION);
		$img_link = $_SERVER['DOCUMENT_ROOT']. "/People/data/$name/$imname.$extns";
		if($_FILES["image"]["size"] > 1800000 || $_FILES["image"]["size"] == 0){
			return "big";
		}
		if($extns != "jpg" && $extns != "png"&& $extns != "jpeg" && $extns != "gif"){
			return "noimg";
		}
		move_uploaded_file( $_FILES["image"]["tmp_name"] , $img_link);
		$db -> query("INSERT INTO imglist(img_link) VALUES ('data/$name/$imname.$extns')");
		$ris = $db -> query("SELECT id FROM imglist WHERE img_link = 'data/$name/$imname.$extns'");
		$idimg = $ris -> fetch_assoc()['id'];
		$db -> query("INSERT INTO userimg(id_img,id_user,img_name,profile,mex) VALUES ($idimg,$iduser,'$img_name','$profile','$mex')");
		$db -> close();
		$ris -> close();
		return $idimg;
	}
	
	// Change Profile Picture
	function change_profile_picture($id_img, $name){
		$db = new mysqli('localhost','root','toor','people_db');
		$id_user = get_id_from_name($name);
		$db -> query("UPDATE userimg SET profile='n' WHERE id_img!=$id_img AND id_user=$id_user");
		$db -> close();
	}

	// Funzione Di Ricerca
	function search_pattern($pattern){
		$db = new mysqli('localhost','root','toor','people_db');
		$arrUsers = Array();
		$ris = $db -> query("SELECT id FROM usertab WHERE username like '$pattern%' AND username!='4dMiN'");
		$db -> close();
		if($ris->num_rows > 0){
			while($arr = $ris -> fetch_assoc()){
				array_push($arrUsers,$arr['id']);
			}
			$ris -> close();
			return $arrUsers;
		}
		else{
			return False;
		}
	}
	
	// Delete Message
	function delete_mex($mexGroup,$username){
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db -> query("SELECT id FROM mextab WHERE mex_group=$mexGroup");
		$idUs = get_id_from_name($username);
		while($id = $ris->fetch_assoc()['id']){
			$db->query("DELETE FROM storedmex WHERE id_mex = $id AND id_user = $idUs");
		}
		$ris -> close();
		$db -> close();
	}
	
	// Check Locked
	function check_locked($name,$otherName){
		$idname = get_id_from_name($name);
		$idOtherName = get_id_from_name($otherName);
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db -> query("SELECT id_lckd FROM locked WHERE id_usr =$idname AND id_lckd =$idOtherName");
		if($ris->num_rows === 1){
			return True;
		}
		else{
			return False;
		}
	}
	
	// Get OtheruserID From Mex_Group
	function get_otheruserid_from_mexgroup($thisUser, $mexgroup){
		$iduser = get_id_from_name($thisUser);
		if(!$iduser){
			return false;
		}
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db -> query("SELECT id_mittente idm, id_destinatario idd FROM mextab WHERE id_destinatario=$iduser AND mex_group=$mexgroup AND id_mittente!=$iduser OR id_mittente=$iduser AND mex_group=$mexgroup AND id_destinatario!=$iduser");
		if($ris->num_rows > 0){
			$id = $ris->fetch_assoc();
			return (($id['idm'] == $iduser) ? $id['idd'] : $id['idm']);
		}
		else{
			return false;
		}
	}

	// Add Post Bacheca
	function add_post($udest,$umitt,$post,$postgroup){
		$idest = get_id_from_name($udest);
		$idmit = get_id_from_name($umitt);
		$db = new mysqli('localhost','root','toor','people_db');
		if(!$postgroup){
			$ris = $db -> query("SELECT MAX(post_group) pgroup FROM posts");
			$postgroup = (($ris -> num_rows == 0) ? 0 : $ris -> fetch_assoc() ['pgroup'] + 1);
			$ris -> close();
		}
		$db -> query("INSERT INTO posts(post,id_destinatario,id_mittente,post_group) VALUES ('$post',$idest,$idmit,$postgroup)");
		$db -> close();
		return strval($idest) . strval($idmit) . $post . strval($postgroup);
	}
	
	// GET Post Bacheca
	function get_posts($username){
		$idest = get_id_from_name($username);
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db -> query("SELECT post,id_mittente,post_group,data_invio FROM posts WHERE id_destinatario=$idest AND eliminato='n' ORDER BY post_group");
		if($ris -> num_rows > 0){
			$arrFinal = [array(),array(),array(),array()];
			while($arr = $ris -> fetch_assoc()){
				array_push($arrFinal[0],$arr['post']);
				array_push($arrFinal[1],get_name_from_id($arr['id_mittente']));
				array_push($arrFinal[2],$arr['post_group']);
				array_push($arrFinal[3],$arr['data_invio']);
			}
			return $arrFinal;
		}
		else{
			return false;
		}		
	}
	
	// ADD Mex Chat
	function add_mex_chat($mex_content,$other_usr,$usr){
		$db = new mysqli('localhost','root','toor','people_db');
		$other_id = get_id_from_name($other_usr);
		$id = get_id_from_name($usr);
		$query = "SELECT mex_group FROM chattab WHERE id_mitt = $other_id AND id_dest = $id OR id_mitt = $id AND id_dest = $other_id";
		$ris = $db -> query("$query");
		if($ris->num_rows>0){
			$mex_group = $ris -> fetch_assoc()['mex_group'];
		}
		else{
			$query = "SELECT MAX(mex_group) mg FROM chattab";
			$ris = $db->query("$query");
			$mex_group = ($ris->fetch_assoc()['mg'] + 1);
		}
		$query = "INSERT INTO chattab(id_dest,id_mitt,mex_content,mex_group) VALUES ($other_id,$id,'$mex_content',$mex_group)";
		$db-> query("$query");
		$ris -> close();
		$db-> close();
	}
	
	// GET Mex Chat
	function get_mex_chat($usr){
		$db = new mysqli('localhost','root','toor','people_db');
		$id = get_id_from_name($usr);
		$query = "SELECT id_dest,id_mitt,mex_content,date_sent,mex_group FROM chattab WHERE id_mitt=$id OR id_dest=$id ";
		$ris = $db -> query("$query");
		$db->close();
		if($ris->num_rows > 0){
			$arr_final = array(); 
			for($i=0;$i<$ris->num_rows;$i++){
				$arr = $ris->fetch_assoc();
				$mex_group= $arr['mex_group'];
				if(array_key_exists($mex_group,$arr_final)){
					array_push($arr_final[$mex_group]['mittente'],get_name_from_id($arr['id_mitt']));
					array_push($arr_final[$mex_group]['destinatario'],get_name_from_id($arr['id_dest']));
					array_push($arr_final[$mex_group]['contenuto'],$arr['mex_content']);
					array_push($arr_final[$mex_group]['data'],$arr['date_sent']);
				}
				else{
					$arr_final[$mex_group] = array();
					$arr_final[$mex_group]['mittente'] = array();
					$arr_final[$mex_group]['destinatario'] = array();
					$arr_final[$mex_group]['contenuto'] = array();
					$arr_final[$mex_group]['data'] = array(); 
					array_push($arr_final[$mex_group]['mittente'],get_name_from_id($arr['id_mitt']));
					array_push($arr_final[$mex_group]['destinatario'],get_name_from_id($arr['id_dest']));
					array_push($arr_final[$mex_group]['contenuto'],$arr['mex_content']);
					array_push($arr_final[$mex_group]['data'],$arr['date_sent']);
				}
			}
			$ris->close();
			return $arr_final;
		}
		else{
			return False;
		}
	}
			
	// DELETE online user
	function delete_user_online($name){
		$db = new mysqli('localhost','root','toor','people_db');
		$db -> query("DELETE FROM useronline WHERE name = '$name'");
		$db->close();
	}
	
	// GET all online users
	function get_online_users($name){
		$db = new mysqli('localhost','root','toor','people_db');
		$ris = $db -> query("SELECT name FROM useronline");
		$arr = array();
		for($i=0; $i < $ris->num_rows; $i++){
			$row = $ris -> fetch_assoc();
			if($row['name']!=$name){
				array_push($arr,$row['name']);
			}
		}
		$ris->close();
		$db->close();
		return $arr;
	}
	
	// DELETE users offline
	function delete_offline(){
		$db = new mysqli('localhost','root','toor','people_db');
		$db->query("DELETE FROM useronline WHERE connessione < (CURRENT_TIMESTAMP - 60)");
		$db->close();
	}
	
	// ADD user online
	function insert_user_online($name){
		$db = new mysqli('localhost','root','toor','people_db');
		$db -> query("INSERT INTO useronline(name) VALUES ('$name')");
		$db-> close();
	}
	
	//UPDATE user online
	function i_am_alive($name){
		$db = new mysqli('localhost','root','toor','people_db');
		$db -> query("UPDATE useronline SET connessione = now() WHERE name = '$name'");
		$db-> close();
	}
	
?>


