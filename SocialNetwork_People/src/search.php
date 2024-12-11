<?php
	session_start();
	include("db_functions.php");
	if(!isset($_SESSION['username'])){
		header("location: 404.html");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title> Risultati Ricerca </title>
		<link rel="stylesheet" href="css/MoreStyle.css" />
		<script src="js/jquery-2.2.4.min.js"> </script>
		<style>
			#tbl{
				width:500px;
				color:white;
			}
			body{
					background-image: url("img/Img01.jpg");
					background-color: #000;
				}
		</style>
	</head>
	<body>
		<div class="topMenu" style="position:absolute;left:-5%;width:120%;text-align:center;">
			<br/>
			<h1 style="color:#57F;"> &lt;&lt;~ PeoPle ~>> </h1> 
		<br/>
		<span style="color:white;"> Pattern Di Ricerca:
			<?php
				echo $_POST['srcuser'];
			?>
		</span> <br/> <br/>
		</div>
<?php
	if(isset($_POST['searchuser'])){
		$usersId = search_pattern($_POST['srcuser']);
		$usersInfo = array();
		$usersPictures = array();
		if($usersId){
			foreach($usersId as $v){
				array_push($usersInfo,get_info_user(get_name_from_id($v)));
				array_push($usersPictures,get_user_profile_picture(get_name_from_id($v)));
			}
?>
			<div class="divBacheca" style="background-image: url('img/Img07.jpg');background-size: cover;	opacity:1;position:absolute;top:28%;left:0%;right:45%;height:100%;">
				<span style="font-size:15px;position:absolute;top:5%;left:5%;">
				<table class="line" >
					<?php
						echo "<tr><td style=\"color:yellow;\">Username</td><td style=\"color:yellow;\">Name</td><td style=\"color:yellow;\">Surname</td><td style=\"color:yellow;\">Img</td></tr><tr><td><br/></td><td><br/></td></tr>"; 
						for($i=0;$i<count($usersInfo);$i++){
							echo "<tr><td id=\"tbl\"><a style=\"color:#F0F;\" href=\"otheruser.php?username=".$usersInfo[$i]['username']."\" >".$usersInfo[$i]['username'] ."</a></td><td id=\"tbl\">" . $usersInfo[$i]['first_name'] . "</td><td id=\"tbl\">" . $usersInfo[$i]['last_name'] . "</td><td id=\"tbl\"><a href=\"otheruser.php?username=".$usersInfo[$i]['username']."\" ><img style=\"border:1px solid white;width:100px;height:100px;border-radius:25px;z-index:1;\" src=\"".$usersPictures[$i]."\" /></a> </td> </tr>";
							$len = ($i+1);
						}
					?>
				</table>
				</span>
			</div>
<?php
		}
		else{
?>	
			<br/><br/><br/>
			<span style="color:#F00;font-size:25px;"> Nessun Risultato Trovato </span>
<?php				
		}
	}
	else{
		$_SESSION['Message'] = "Prego Settare Tutti I Campi";
			header("location: userpage.php");
	}
?>
<script>

	$(document).ready(function(){
		var a = $("table").height();
		change_height();
	});
	
	function change_height(){
		var count = <?php echo $len; ?>;
		if(count>5){
			for(n=5;n<=count;n++){
				$(".divBacheca").height($("table").height()+50);
			}
		}
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
	</body>
</html>
