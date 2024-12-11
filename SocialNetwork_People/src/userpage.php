<?php
	session_start();
	include("db_functions.php");
	if(!isset($_SESSION['username']) && !isset($_COOKIE['cookie'])){
		header("location: 404.html");
	}
	else{
?>

<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
			<title> 
				Pagina Utente
			</title>
			<link rel="stylesheet" href="css/MoreStyle.css" >
			<script src="js/jquery-2.2.4.min.js"></script>
			<style>
				body{
					background-image: url("img/Img01.jpg");
					background-color: #000;
				}
			</style>
		</head>
		<body>
			<div class="divBacheca" >
				<!-- BACHECA -->
						<font class="tTitle" style="position:absolute;right:15%;"> Bacheca Utente </font> <br/><br/><br/><br/>
				<form style="position:absolute;top:25px;left:2%;" action="options.php" method="POST">
					<input id="optionsSubmitButton" type="SUBMIT" name="addpost" value="Aggiungi Post" onmouseover="show_post_textarea()"/> <br/>
					<textarea maxlength=1000 id="PostTextArea" style="position:absolute;height:100px;width:450%;z-index:1;" name="posttext" onmouseleave="hide_post_textarea()"></textarea>		
				</form>
				<div id="bacheca"></div>
			</div> 
			<div class="topMenu" style="position:absolute;top:1%;left:3%;right:3%;bottom:85%;" >
				<?php
					if(isset($_SESSION['Message'])){
				?>
					<span style="position:absolute;top:65px;left:5%;color:white;"> <?php echo $_SESSION['Message']; ?> </span>
				<?php
						unset($_SESSION['Message']);
					}
				?>
				<img src="<?php echo get_user_profile_picture($_SESSION['username']); ?>" class="usrimg" />
				<p style="position:absolute;right:18%;top:3%;color:#22F;"> <?php echo $_SESSION['username']; ?> </p>
				<form action="ByeBye.php" method="get" >
					<input style="position:absolute;right:18%;top:50%;background-color:black;border-radius:5px;color:yellow;border:2px solid yellow;width:75px;height:30px;cursor:pointer;" type="SUBMIT" value="Logout" />
				</form>
				<button class="menuButton left" style="position:absolute;left:5%;top:10%;" onmouseover="buildMenu(0)"> Config </button>
				<div id="menu0" class="builtInMenu left" style="position:absolute;left:3%;top:60%;" onmouseleave="noMenu(0)"> <br/> <br/>
					<button id="menuButton" class="menuSlct" onclick="buildOption(1)"> Password </button> <br/> <br/>
					<button id="menuButton" class="menuSlct" onclick="buildOption(2)"> Immagine </button> <br/> <br/>
					<button id="menuButton" class="menuSlct" onclick="buildOption(3)"> Email </button> <br/> <br/>
					<button id="menuButton" class="menuSlct" onclick="buildOption(4)"> Domanda Segreta </button> <br/> <br/>
				</div>
				<button class="menuButton middle one" style="position:absolute;left:13%;top:10%;" onmouseover="buildMenu(1)" > Followers </button>
				<div id="menu1" class="builtInMenu" style="position:absolute;left:14%;top:60%;" onmouseleave="noMenu(1)"> 
					<br/> <br/>
					<button id="menuButton" class="menuSlct" onclick="buildOption(5)"> Cerca Utente </button> <br/> <br/>
					<button id="menuButton" class="menuSlct" onclick="buildOption(6)"> Aggiungi Utente </button> <br/> <br/>
					<button id="menuButton" class="menuSlct" onclick="buildOption(7)"> Elimina Follower </button> <br/> <br/>
					<button id="menuButton" class="menuSlct" onclick="buildOption(10)"> S/Blocca Utente	</button> <br/> <br/>
				</div>
				<button class="menuButton middle two" style="position:absolute;left:25%;top:10%;" onmouseover="buildMenu(2)" > Messaggi </button>
				<div id="menu2" class="builtInMenu" style="position:absolute;left:26%;top:60%;" onmouseleave="noMenu(2)"> <br/> <br/>
					<button id="menuButton" class="menuSlct" onclick="buildOption(8)"> Invia Nuovo Messaggio </button> <br/> <br/>
					<button id="menuButton"	class="menuSlct" onclick="buildOption(9)" > Leggi Messaggi </button> <br/> <br/>
					<button id="menuButton" class="menuSlct" onclick="buildOption(9)"> Elimina Messaggi </button> <br/> <br/>
				</div>
				<button class="menuButton right" style="position:absolute;left:37%;top:10%;" onmouseover="buildMenu(3)" > Profilo </button>
				<div id="menu3" class="builtInMenu right" style="position:absolute;left:37%;top:60%;" onmouseleave="noMenu(3)"><br/> <br/>
					<button id="menuButton" class="menuSlct" onclick="buildOption(11)"> Info Profilo </button> <br/> <br/>
					<button id="menuButton"	class="menuSlct" onclick="buildOption(12)" > Album Immagini </button> <br/> <br/>
					<button id="menuButton"	class="menuSlct"onclick="buildOption(13)" > Tuoi Followers </button> <br/> <br/>
				</div>
			</div>
			<div class="optdiv" style="position:absolute;top:18%;left:60%;">
				<!-- Cambia Password -->
				<div class="divOptions" id="opt1" >
					<br/> <p class="txtOpt" > Cambia Password </p>
					<form  action="options.php" method="POST">
						<label>Vecchia Password:</label><input type="password" name="oldpass" required /><br/> <br/>
						<label>Nuova Password:</label><input type="password" name="newpass" required /> <br/>  <br/>
						<label>Repeat Password:</label><input type="password" name="passconfirm" required /> <br/> <br/>
						<input id="optionsSubmitButton" type="SUBMIT" value="INVIA" name="changepass" />
					</form>
				</div>
				<!-- Cambia Immagine -->
				<div class="divOptions" id="opt2" >
					<br/> <span class="txtOpt" > Cambia Immagine </span>
					<br/> <br/>
					<form enctype="multipart/form-data" action ="options.php" method="POST">
						<label> Foto Profilo? </label>
						<input type="radio" name="profile" value="y" required> yes
						<input type="radio" name="profile" value="n" required> no <br/> <br/>
						<label> Nome Immagine: </label>
						<input type="text" name="imgname" placeholder="Nome Dell'Immagine" /> <br/> <br/>
						<label> Messaggio Nell'Immagine: </label>
						<label style="color:#000;" onclick="show_img_textarea()"> Click Here </label> <br/> <br/> 
						<input style="border:none;background-color:black;color:white;" type="file" name="image" required/> <br/> <br/>
						<input id="optionsSubmitButton" type="submit" value="UPLOAD" name="changepicture"/> <br/> <br/>
						<textarea maxlength=500 id="textAreaImgMex" style="width:250px;height:100px" name="imgtext" onmouseleave="hide_img_textarea()">Testo Messaggio Immagine</textarea>
					</form>
				</div>
				<!-- Cambia Email -->
				<div class="divOptions" id="opt3" >
					<br/> <p class="txtOpt"> Cambia E-Mail </p>
					<form action="options.php" method="POST">
						<label>Vecchia Email:</label><input type="MAIL" name="oldmail" required /> <br/> <br/>
						<label>Nuova Email:</label><input type="MAIL" name="newmail" required /> <br/> <br/>
						<input id="optionsSubmitButton" type="SUBMIT" value="INVIA" name="changemail" />
					</form>
				</div>
				<!-- Cambia Domanda Segreta -->
				<div class="divOptions" id="opt4" >
					<br/> <span style="color:#FFF;font-family:Arial;font-size:20px;"> Cambia Domanda Segreta </span> <br/><br/>
					<form action="options.php" method="POST">
						<label>Password:</label><input type="password" name="passquestion" placeholder="Inserisci Password" /> <br/><br/>
						<label>Seleziona Una Domanda:</label>
						<SELECT NAME="secret_question" required>
							<option VALUE="q1"> Nome Del Tuo Animale Domestico </option>
							<option VALUE="q2"> Nome Del Tuo Libro Preferito </option>
							<option VALUE="q3"> Data Dell'Evento Pi&ugrave Importante Della Tua Vita </option>
							<option VALUE="q4"> Nome Della Tua Canzone Preferita </option>
							<option VALUE="q5"> Un Codice PIN Alfanumerico Segreto </option>
							<option VALUE="q6"> Nome Del Tuo Film Preferito </option>
						</SELECT> <br/> <br/>
						<label>Risposta:</label><input type="text" name="secretanswer" maxlength=50 required /> <br/> <br/>
						<input id="optionsSubmitButton" type="SUBMIT" value="INVIA" name="changequestion" />
					</form>
				</div>
				<!-- Cerca Utente -->
				<div class="divOptions" id="opt5" >
					<center> <br/> <p class="txtOpt"> Cerca Un Utente </p>
					<form action="search.php" method="POST">
						Pattern Di Ricerca: 
						<input type="text" name="srcuser" required/> <br/> <br/>
						<input id="optionsSubmitButton" type="SUBMIT" value="CERCA" name="searchuser" />
					</form>
					</center>
				</div>
				<!-- Aggiungi Utente -->
				<div class="divOptions" id="opt6" >
					<br/> <p class="txtOpt"> Be A Follower! </p>
					<form action="options.php" method="POST">
						<label>Nome Utente:</label> 
						<input type="text" name="fllwuser" required /> <br/> <br/>
						<input id="optionsSubmitButton" type="SUBMIT" value="INVIA" name="followuser" /> 
					</form>
				</div>
				<!-- Elimina Follower -->
				<div class="divOptions" id="opt7" >
					<br/> <p class="txtOpt" > Stop Follow... </p>
					<form action="options.php" method="POST">
						<label>Seleziona Utente: </label>	
						<SELECT ID="fllwrList" NAME="fllwrList" placeholder="Persone Che Segui">
							<option value=""> ////////// </option>
							<?php
								$arr = get_followers($_SESSION['username']);
								foreach($arr as $v){
									echo "<option value=\"$v\">$v</option>";
								}
							?>
						</SELECT> <br/> <br/>
						<input id="optionsSubmitButton" type="SUBMIT" name="delFllw" value="Elimina Follower" />
					</form>
				</div>
				<!-- Invia Messaggio -->
				<div class="divOptions" id="opt8" >
					<br/> <span class="txtOpt">Invia Nuovo Messaggio</span> <br/><br/>
					<form action="options.php" method="POST">
						<label>Nome Utente:</label> 
						<input type="text" name="usersendmex" /><br/><br/>
						<TEXTAREA NAME="messagetext" style="width:250px;height:100px;"></TEXTAREA><br/><br/>
						<input type="SUBMIT" id="optionsSubmitButton" name="sendnewmex" value="INVIA" />
					</form>
				</div>
				<!-- Leggi Messaggi --> <!-- Elimina Messaggi -->
				<div class="divOptions" style="position:absolute;top:5%;left:5%;right:5%;" id="opt9" >
					<br/> <p class="txtOpt">Lista Messaggi Ricevuti</p>
					<form action="options.php" method="POST">
						<label>Lista Messaggi:</label>
						<SELECT ID="mexslist" name="mexslists" >
							<option value="null"></option>
							<?php
								$arrMex = get_mex($_SESSION['username']);
								$parsed = Array();
								$n = 0;
								for($i=0;$i<count($arrMex[1]);$i++){
									if(!in_array($arrMex[1][$i],$parsed)){
										$n++;
										array_push($parsed,$arrMex[1][$i]);
										// IDMexGroup = $arrMex[1][$i];
										echo "<option value=\"".$arrMex[1][$i]."\">IDMex:".$n." | ".(($arrMex[3][$i]==$_SESSION['username']) ? "Tu " : $arrMex[3][$i])." -> ".(($arrMex[2][$i]==$_SESSION['username']) ? "Tu " : $arrMex[2][$i])."</option>";
									}
									else{
										continue;
									}
								}
							?>
						</SELECT> <br/> <br/>
						<label>Testo Messaggio Di Risposta</label><br/>
						<TEXTAREA NAME="messagetext" style="width:250px;height:100px;"></TEXTAREA><br/><br/>
						<input type="SUBMIT" id="optionsSubmitButton" name="delmex" value="ELIMINA GRUPPO MESSAGGI" />
						<input type="SUBMIT" id="optionsSubmitButton" name="sendanswermex" value="RISPONDI" />
					</form> <br/>
					<button id="viewMex" class="menuButton" value="VISUALIZZA" onclick="view_mex()" > VISUALIZZA </button> <br/> <br/>
				</div>
				<!-- Blocca/Sblocca Utenti -->
				<div class="divOptions" id="opt10" >
				<br/> <p class="txtOpt"> Blocca/Sblocca Utente </p>
					<form action="options.php" method="POST">
						<label>Utenti Bloccati:</label>	
						<SELECT ID="blckdlist" NAME="blockedlist" placeholder="Utenti Bloccati">
							<option value=""> ////////// </option>
							<?php
								$arr = get_lockeds($_SESSION['username']);
								foreach($arr as $v){
									echo "<option value=\"$v\">$v</option>";
								}
							?>
						</SELECT> <br/> <br/>
						<label>Blocca Utente:</label> 
						<input type="text" name="lockuser" placeholder="Nome Utente" /> <br/> <br/> <br/>
						<input type="SUBMIT" id="optionsSubmitButton" name="lock" value="BLOCCA" />
						<input type="SUBMIT" id="optionsSubmitButton" name="block" value="SBLOCCA" />
					</form>
				</div>
				<!-- Mostra Info Profilo -->
				<div class="divOptions" id="opt11" >
				<br/> <p class="txtOpt"> Info Profilo </p>
					<?php
						$arr = get_info_user($_SESSION['username']);
					?>
						<span style="color:#FFF">
					<?php
						echo "<label>Username:</label>    " . $arr['username'] . "<br/>";
						echo "<label>Email:</label>    " . $arr['email'] . "<br/>";
						echo "<label>Nome:</label>    " . $arr['first_name'] . "<br/>";
						echo "<label>Cognome:</label>    " . $arr['last_name'] . "<br/>";
						echo "<label>Data Di Nascita:</label>    " . $arr['birth_date'] . "<br/>";
						echo "<label>Sesso:</label>    " . $arr['sex'] . "<br/>";
						echo "<label>Data Registrazione:</label>    " . $arr['registrazione'] . "<br/>";
						echo "<label>Ultimo Accesso:</label>    " . $arr['lastlog'] . "<br/>";
					?>
						</span>
				</div>
				<!-- Mostra Immagini -->
				<div class="divOptions" id="opt12" >
				<br/> <span class="txtOpt"> Album Immagini </span>
						<select id="selectimg" name="select_img" onchange="view_img()" >
							<option value="null"></option>
						<?php
							$arrImg = get_user_imgs($_SESSION['username']);
							for($i=0;$i<count($arrImg[1]);$i++){
									echo "<option value=\"$i\">".$arrImg[1][$i]."</option>";
								}
						?>
						</select>
				</div>
				<!-- Mostra User Followers -->
				<div class="divOptions" id="opt13" >
				<br/> <span class="txtOpt"> I Tuoi Followers </span>
					<br/><br/><br/>
						<SELECT ID="fllwrList" NAME="fllwrList" placeholder="Persone Che Segui">
							<option value=""> ////////// </option>
							<?php
								$arr = get_user_followers($_SESSION['username']);
								foreach($arr as $v){
									echo "<option value=\"$v\">$v</option>";
								}
							?>
						</SELECT>
				</div>
			</div>
			<span style="color:white;">
			<div id="imgcontainer" style="position:absolute;top:15%;left:5%;bottom:5%;" onmouseleave="hide_img_container()">				
			</div>
			</span>
			<span style="color:white;">
			<div id="mexcontainer" style="position:absolute;top:15%;left:5%;" onmouseleave="hide_mex_container()">				
			</div>
			</span>
			<button class="chatBttn"> Chat Now </button>
			<div class="chatDiv">
				<button class="exitBttn" style="position:absolute;top:0%;right:0%;"> </button>
				<iframe src="chat.php" class="chatiFrame"></iframe>
			</div>
		</body>
	</html>
	<?php
	}
?>

<script type="text/javascript">
	
	
	$(document).ready(function(){
			getPosts();	
			hideAll(666,1);
			hide_img_container();
			hide_mex_container();
			hide_post_textarea();
			var timer = window.setInterval( getPosts, 60000);
			$(".chatDiv").hide();
			
			$(".menuButton").mouseover(function(){
				$(".optdiv").addClass("animated");				
			});
			
			$(".topMenu").mouseover(function(){
				$(".divBacheca").addClass("animated");
			});
			
			/*
				FadeIn Scroll Dei Post - Funzionamento Non Ottimale (Funziona Se Lentamente)
			*/
			$(window).scroll(function() {
				var y = $(window).height() % document.getElementById("postgroup1D").offsetHeight;
				$("#postgroup"+parseInt($(window).scrollTop()/y)+"D").fadeIn();
			});

			$(".chatBttn").click(function(){
				$(".chatBttn").hide();	
				$(".chatDiv").show();
			});
			
			$(".exitBttn").click(function(){
				$(".chatDiv").hide();
				$(".chatBttn").show();
			});
			
	});
	
	function view_img(){
		var img_links = [<?php for($n=0;$n<count($arrImg[0]);$n++){ echo (($n!=(count($arrImg[0])-1)) ? "\"".$arrImg[0][$n]."\"," : "\"".$arrImg[0][$n]."\"");}?>];
		var img_names = [<?php for($n=0;$n<count($arrImg[1]);$n++){ echo (($n!=(count($arrImg[1])-1)) ? "\"".$arrImg[1][$n]."\"," : "\"".$arrImg[1][$n]."\"");}?>];
		var img_mex = [<?php for($n=0;$n<count($arrImg[2]);$n++){ echo (($n!=(count($arrImg[2])-1)) ? "\"".$arrImg[2][$n]."\"," : "\"".$arrImg[2][$n]."\"");}?>];
		var img_upload = [<?php for($n=0;$n<count($arrImg[3]);$n++){ echo (($n!=(count($arrImg[3])-1)) ? "\"".$arrImg[3][$n]."\"," : "\"".$arrImg[3][$n]."\"");}?>];
		if(document.getElementById("selectimg").value!="null"){
			var imgcont = document.getElementById("imgcontainer");
			imgcont.style.visibility="visible";
			imgcont.innerHTML = "<br/><br/><br/><font color=yellow>Name:</font> " + img_names[document.getElementById("selectimg").value] +"<br/><font color=yellow>Testo:</font> " + img_mex[document.getElementById("selectimg").value] + "<br/><font color=yellow>Upload:</font> " + img_upload[document.getElementById("selectimg").value] + "<br/><br/><img style=\"border-radius:15px;\" src=\""+img_links[document.getElementById("selectimg").value]+"\" />";
			$(".divBacheca").fadeOut(500);
			$(".optdiv").fadeOut(500);
			$(".topMenu").fadeOut(500);
		}	
	}
	
	function view_mex(){
		var urname = "<?php echo $_SESSION['username']; ?>";
		var mex_date = [<?php for($n=0;$n<count($arrMex[4]);$n++){ echo (($n!=(count($arrMex[4])-1)) ? "\"".$arrMex[4][$n]."\"," : "\"".$arrMex[4][$n]."\"");}?>];
		var mex_group = [<?php for($n=0;$n<count($arrMex[1]);$n++){ echo (($n!=(count($arrMex[1])-1)) ? "\"".$arrMex[1][$n]."\"," : "\"".$arrMex[1][$n]."\"");}?>];
		var mex_text =[<?php for($n=0;$n<count($arrMex[0]);$n++){ echo (($n!=(count($arrMex[0])-1)) ? "\"".$arrMex[0][$n]."\"," : "\"".$arrMex[0][$n]."\"");}?>];
		var mex_mitt =[<?php for($n=0;$n<count($arrMex[3]);$n++){ echo (($n!=(count($arrMex[3])-1)) ? "\"".$arrMex[3][$n]."\"," : "\"".$arrMex[3][$n]."\"");}?>];
		if(document.getElementById("mexslist").value!="null"){
			var mexcont = document.getElementById("mexcontainer");
			mexcont.style.visibility="visible";
			var mex_list = [];
			var mex_mitts = [];
			var mex_dates = [];
			var group = document.getElementById("mexslist").value;
			for(var i=0;i<mex_group.length;i++){
				if(group == mex_group[i]){
					mex_list.push(mex_text[i]);
					mex_mitts.push(mex_mitt[i]);
					mex_dates.push(mex_date[i]);
				}				
			}
			mexcont.innerHTML = "<br/><br/>";
			for(var i=0; i<mex_list.length; i++){
				mexcont.innerHTML += "<div style=\"border:2px solid #0F0;width:100%;\">[ "+(i+1)+" ]<br/><span style=\"position:relative;left:5px;\"><font color=yellow>Mittente:</font> " + ((mex_mitts[i]==urname) ? "Tu" : mex_mitts[i]) +"<br/><font color=yellow>Data Invio:</font> "+ mex_dates[i] + "<br/><br/>" + mex_list[i] + "<br/><br/></span></div>";
			}
			$(".divBacheca").fadeOut(500);
			$(".optdiv").fadeOut(500);
			$(".topMenu").fadeOut(500);
		}
		
	}

	function buildMenu(n){
		hideAll(n,0);
		var menu = document.getElementById("menu" + n.toString());
		menu.style.visibility = 'visible';
	}
	
	
	function noMenu(n){
		var menu = document.getElementById("menu" + n.toString());
		menu.style.visibility = 'hidden';
	}
	
	
	function buildOption(n){
		hideAll(n,1);
		var opt = document.getElementById("opt" + n.toString());
		opt.style.visibility = 'visible';
	}
	
	
	function hideAll(n,z){	
		for(var i=0;i<=3;i++){
			if(n == i){
				continue;
			}
			var menu = document.getElementById("menu" + i.toString());
			menu.style.visibility = 'hidden';
		}
		if(z == 1){
			for(var i=1;i<=13;i++){
				if(n == i){
					continue;
				}
				var opt = document.getElementById("opt" + i.toString());
				opt.style.visibility = 'hidden';
			}
		}
		hide_img_textarea();
	}
	
	function show_img_textarea(){
		var textarea = document.getElementById("textAreaImgMex");
		textarea.style.visibility="visible";
	}
	
	function hide_img_textarea(){
		var textarea = document.getElementById("textAreaImgMex");
		textarea.style.visibility="hidden";
	}
	
	function hide_img_container(){
		var imgcont = document.getElementById("imgcontainer");
		imgcont.style.visibility="hidden";
		$(".divBacheca").fadeIn(1000);
		$(".optdiv").fadeIn(1000);
		$(".topMenu").fadeIn(1000);
	}
	
	function hide_mex_container(){
		var imgcont = document.getElementById("mexcontainer");
		imgcont.style.visibility="hidden";
		$(".divBacheca").fadeIn(1000);
		$(".optdiv").fadeIn(1000);
		$(".topMenu").fadeIn(1000);
	}
	
	function hide_post_textarea(){
		var textarea = document.getElementById("PostTextArea");
		textarea.style.visibility="hidden";
	}
	
	function show_post_textarea(){
		var textarea = document.getElementById("PostTextArea");
		textarea.style.visibility="visible";
	}

	function hide_posts(){
		if(maxposts){
			for(var i=1;i<=parseInt($("#maxposts").text()); i++){
				try{
					if(document.getElementById("postgroup"+i)){
						document.getElementById("postgroup"+i).style.visibility="hidden";
					}
					$("#postgroup"+i+"D").css({"opacity":"1"});
					/*
						FadeOut Per Scroll Dei Post - Funzionamento Non Ottimale (Funziona Se Lentamente)
					*/
					if(i>5 && $(window).scrollTop() < ($(window).height() % document.getElementById("postgroup1D").offsetHeight)){
						$("#postgroup"+i+"D").fadeOut();
					}
				} 
				catch(Exception){}
			}
		}		
	}
	

	function show_post(n){
		var postgroup = document.getElementById("postgroup"+n);
		postgroup.style.visibility="visible";
		for(i=0;i <= parseInt($("#maxposts").text()); i++){
				if(i!=n){
					$("#postgroup"+i+"D").css({"opacity":"0.4"});
				}
		}
		var postop = document.getElementById("postgroup"+n+"D").offsetTop;
		var height = document.getElementById("postgroup"+n+"D").offsetHeight;
		var pos = postop - (height/22);
		pos = pos.toString();
		postgroup.style.top = pos + "px";
		
	}
	


	function getPosts(){
			$.ajax({
				type: 'POST',
				url: 'getPosts.php',
				data:{'username':'<?php echo $_SESSION['username'] ?>'},
				dataType: 'html',

				/*
				+ In Case Of Success...
				*/
				success: function(data){
					$("#bacheca").html(data);
					hide_posts();
				},
				
				/*
				+ In Case Of Error...
				*/
				error: function(wrong){
					$("#bacheca").append("<br/>" + wrong.status + "<br/><br/><br/><br/><br/>");
				}
			});
	}
	
</script>
