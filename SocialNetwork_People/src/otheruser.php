<!DOCTYPE html>

<html>
	<head>
		<title>
			OtherUserpage
		</title>
		<link href="css/MoreStyle.css" rel="stylesheet" type="text/css">
		<script src="js/jquery-2.2.4.min.js"></script>
		<style>
			body{
					background-image: url("img/Img01.jpg");
					background-color: #000;
				}
		</style>
	</head>
	<body>
	
<?php
	session_start();
	if(isset($_GET['username']) AND isset($_SESSION['username'])){
		if($_GET['username'] == $_SESSION['username']){
			$_SESSION['Message'] = "Non Puoi Visitare La Tua Stessa Pagina Se Sei Loggato";
			header("location: userpage.php");
			$ret = true;
		}
		else{
			$username = $_GET['username'];
			?>
				<a href="userpage.php" style="position:absolute;left:1%;top:1%;color:yellow;z-index:1;" > R<br/>E<br/>T<br/>U<br/>R<br/>N </a>
			<?php
		}	
	}
	else{
		if(!isset($_SESSION['username'])){
			header("location: 404.html");
		}
		else{
			header("location: userpage.php");
		}
	}
	include("db_functions.php");
	if(!check_locked($username,$_SESSION['username'])){
?>
		<div class="divBacheca">
				<!-- BACHECA -->
					<font class="tTitle" style="position:absolute;right:15%;" > Bacheca Utente </font>
			<form style="position:absolute;top:1%;left:1%;" action="options.php" method="POST">
				<input id="optionsSubmitButton" type="SUBMIT" name="addpost" value="Aggiungi Post" onmouseover="show_post_textarea()"/> <br/>
				<textarea maxlength=1000 id="PostTextArea" style="width:250px;height:100px;border-radius:5px;" name="posttext" onmouseleave="hide_post_textarea()"></textarea>		
				<input type="hidden" value="<?php echo $username; ?>" name="otheruser" />
				<input type="hidden" value="otheruser.php?username=<?php echo $username;?>" name="returnlink" />
			</form>
			<br/><br/>
			<div id="bacheca"></div>
		</div>
		<div class="topMenu" style="position:absolute;top:1%;left:3%;right:3%;bottom:85%;" >
			<?php
				if(check_follower($_SESSION['username'],$username)){
					$follower = true;
			?>
				<span style="position:absolute;top:3%;right:18%;color:white;"> Sei Un Follower Di Questo Utente </span>
			<?php
				}
				else{
					$follower = false;
			?>
				<span style="position:absolute;top:3%;right:18%;color:white;"> Non Sei Un Follower Di Questo Utente </span>
			<?php
				}
				if(isset($_SESSION['Message']) && !isset($ret)){
			?>
				<span style="position:absolute;top:75%;left:5%;color:white;"> <?php echo $_SESSION['Message']; ?> </span>
			<?php
					unset($_SESSION['Message']);
				}
			?>
			<img src="<?php echo get_user_profile_picture($username); ?>" class="usrimg" />
			<p style="position:absolute;right:18%;top:30%;color:#22F;"> <?php echo $username; ?> </p>
			<?php
				if(isset($_SESSION['username'])){
					if($_SESSION['username'] != $username){
			?>	
			<button class="menuButton" style="position:absolute;left:5%;top:10%;" onmouseover="buildMenu()"> Options </button>
			<div id="menu0" class="builtInMenu" style="position:absolute;left:5%;top:70%;" onmouseleave="hideMenu()"> 
				<br/> <br/>
					<form action="options.php" method="POST">
					<?php
						if(!$follower){
					?>
						<input class="menuSlct" type="SUBMIT" id="menuButton" name="followuser" value="Segui" /> <br/> <br/>
					<?php
						} else {
					?>
						<input class="menuSlct" type="SUBMIT" id="menuButton" name="delFllw" value="Non Seguire" /> <br/> <br/>
					<?php
						}
					?>
						<input class="menuSlct" type="hidden" name="fllwuser" value="<?php echo $username;?>"/>
						<?php
							if(!check_locked($_SESSION['username'],$username)){
						?>
						<input class="menuSlct" type="SUBMIT" id="menuButton" name="lock" value="Blocca" /> <br/><br/>
						<?php
							}
							else{
						?>
						<input class="menuSlct" type="SUBMIT" name="block" id="menuButton" value="Sblocca"/> <br/><br/>
						<?php
							}
						?>
						<input type="hidden" name="fllwrList" value="<?php echo $username;?>"/>
						<input type="hidden" name="lockuser" value="<?php echo $username;?>"/>
						<input type="hidden" name="returnlink" value="otheruser.php?username=<?php echo $username;?>" />
					</form>
					<button id="menuButton" class="menuSlct" onclick="hideOptions();buildOption(1)" > Invia Messaggio </button> <br/> <br/>
					<button id="menuButton" class="menuSlct" onclick="hideOptions();buildOption(2)"> Vedi Album </button> <br/> <br/>
			</div>
			<?php
					}
				}
			?>
		</div>
		<?php
			if(isset($_SESSION['username'])){
				if($_SESSION['username'] != $username){
		?>	
		<div class="optdiv" style="position:absolute;top:18%;left:60%;">
			<div class="divOptions" id="opt1" >
				<center> <br/> <span class="txtOpt" >Invia Nuovo Messaggio</span> <br/><br/>
				<form action="options.php" method="POST">
					Nome Utente: <?php echo $username;?>
					<input type="hidden" name="usersendmex" value="<?php echo $username;?>" /><br/><br/>
					<TEXTAREA NAME="messagetext" style="width:250px;height:100px;"></TEXTAREA><br/><br/>
					<input type="SUBMIT" id="optionsSubmitButton" name="sendnewmex" value="INVIA" />
					<input type="hidden" name="returnlink" value="otheruser.php?username=<?php echo $username;?>"/>
				</form>
				</center>
			</div>
			<div class="divOptions" id="opt2" >
				<center> <br/> <span class="txtOpt" > Album Immagini </span>
					<?php
						if(check_follower($_SESSION['username'],$username)){
					?>
					<select id="selectimg" name="select_img" onchange="view_img()" >
					<option value="null"></option>
					<?php
						$arrImg = get_user_imgs($username);
						for($i=0;$i<count($arrImg[1]);$i++){
								echo "<option value=\"$i\">".$arrImg[1][$i]."</option>";
							}
					?>
					</select>
					<?php
						}
						else{
							echo "<br/><br/>Non Puoi Vedere Le Immagini Se Prima Non Segui Questo Utente!";
						}
					?>
				</center>
			</div>
			<?php
					}
				}
			?>
		</div>
		<span style="color:white;">
			<div id="imgcontainer" style="position:absolute;top:18%;left:5%;" onmouseleave="hide_img_container()">				
			</div>
		</span>
	</body>
</html>
<script type="text/javascript">
	
	$(document).ready(function(){
			getPosts();	
			hideMenu();
			hideOptions();
			hide_img_container();
			hide_post_textarea();
			var timer = window.setInterval( getPosts, 6000);
			
			$(".menuButton").mouseover(function(){
				$(".optdiv").addClass("animated");				
			});
			
			$(".topMenu").mouseover(function(){
				$(".divBacheca").addClass("animated");
			});
			
			/*
				FadeIn Per Scroll Dei Post - Funzionamento Non Ottimale (Funziona Se Lentamente)
			*/
			$(window).scroll(function() {
					var y = $(window).height() % document.getElementById("postgroup1D").offsetHeight;
					$("#postgroup"+parseInt($(window).scrollTop()/y)+"D").fadeIn();
			});
	});
	
	<?php
		if(check_follower($_SESSION['username'],$username)){
	?>
	function view_img(){
		var img_links = [<?php for($n=0;$n<count($arrImg[0]);$n++){ echo (($n!=(count($arrImg[0])-1)) ? "\"".$arrImg[0][$n]."\"," : "\"".$arrImg[0][$n]."\"");}?>];
		var img_names = [<?php for($n=0;$n<count($arrImg[1]);$n++){ echo (($n!=(count($arrImg[1])-1)) ? "\"".$arrImg[1][$n]."\"," : "\"".$arrImg[1][$n]."\"");}?>];
		var img_mex = [<?php for($n=0;$n<count($arrImg[2]);$n++){ echo (($n!=(count($arrImg[2])-1)) ? "\"".$arrImg[2][$n]."\"," : "\"".$arrImg[2][$n]."\"");}?>];
		var img_upload = [<?php for($n=0;$n<count($arrImg[3]);$n++){ echo (($n!=(count($arrImg[3])-1)) ? "\"".$arrImg[3][$n]."\"," : "\"".$arrImg[3][$n]."\"");}?>];
		if(document.getElementById("selectimg").value!="null"){
			var imgcont = document.getElementById("imgcontainer");
			imgcont.style.visibility="visible";
			imgcont.innerHTML = "<br/><br/><br/><font color=yellow>Name:</font> " + img_names[document.getElementById("selectimg").value] +"<br/><font color=yellow>Testo:</font> " + img_mex[document.getElementById("selectimg").value] + "<br/><font color=yellow>Upload:</font> " + img_upload[document.getElementById("selectimg").value] + "<br/><br/><img style=\"border-radius:15px;width:100%;height:50%;\" src=\""+img_links[document.getElementById("selectimg").value]+"\" />";
		}
	}
	<?php
		}
	?>
	
	function buildMenu(){
		var menu = document.getElementById("menu0");
		menu.style.visibility = 'visible';
	}
	
	function hideMenu(){
		var menu = document.getElementById("menu0");
		menu.style.visibility = 'hidden';
	}
	
	function hideOptions(){
		for(var i=1;i<=2;i++){
			var opt = document.getElementById("opt" + i.toString());
			opt.style.visibility = 'hidden';
		}
	}
	
	function buildOption(n){
		var opt = document.getElementById("opt" + n.toString());
		opt.style.visibility = 'visible';
	}
	
	function hide_img_container(){
		var imgcont = document.getElementById("imgcontainer");
		imgcont.style.visibility="hidden";
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
					/*
						FadeOut Per Scroll Dei Post - Funzionamento Non Ottimale (Funziona Se Lentamente)
					*/
					$("#postgroup"+i+"D").css({"opacity":"1"});
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
				if(i==n){
					continue;
				}else{
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
				data:{'username':'<?php echo $username ?>','status': 'other'},
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
	
	var alivetimer = window.setInterval(i_am_alive, 1000);
	function i_am_alive(){
		$.ajax({
			type: 'POST',
			url: 'getChatMex.php',
			data:{'iamalive' : 'true' , 'username' : '<?php echo $_SESSION['username']; ?>'},
			dataType: 'html',
			success: function(data){
			},
			error: function(wrong){
			}
		});
	}
</script>

<?php
	}
	else{
?>	
	<center>
	<span style="color:#0F0;"> Sorry Dude <br/></span>
	<span style="color:yellow;"> This User Has Blocked You ): </span>
	</center>
	</body>
</html>
<?php
	}
?>
