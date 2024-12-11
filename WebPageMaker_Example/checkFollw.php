<?php
	session_start();
	include('DB_Functions.php');
	$karr = array_keys($_POST);
	$narr = array_values($_POST);
	if(($karr[0] == Null || $narr[0] == Null) && !isset($_SESSION['otherUser'])){
		header("location: PageList.php");
	}
	else{
		if(!isset($_SESSION['link'])){
			$_SESSION['otherUser'] = $karr[0];
			$_SESSION['link'] = "dataDir/$karr[0]/$narr[0]";
		}
	}
?>

<html>
	<head>
		<title>
			Follow The White Rabbit
		</title>
	</head>
	<body>
		<center>
		<div style="position:absolute;right:1%;left:1%;top:1%;bottom:1%;background-color:#000;border:2px solid white;border-radius=15px;">
		<?php
			if(checkFollower($_SESSION['otherUser'])){
				header("location: ".$_SESSION['link']);
			}
			else{
				if(isset($_REQUEST['newFollow'])){
					echo addFollower($_SESSION['otherUser']);
					echo " 
					<font color=yellow> 
						L'utente &eacute stato aggiunto alle persone che segui! <br/> <br/> <br/>
					</font>
					<font color= white> Procedi Alla Pagina Cliccando Il Link Sottostante... </font> <br/><br/>
					<a href='". $_SESSION['link'] ."' style=\"color:red;\"'> LINK </a>
					";
				}
				else{
					echo'
					<font color=yellow> 
						Sembra Tu Non Sia Ancora Un Follower Di Questo Utente! 
					</font> <br/> <br/>
					<font color=white> 
						Prima di procedere devi aggiungere l utente tra quelle che segui! <br/>
						Clicca sul bottone per continuare. <br/> <br/>
					</font>
					<form action="checkFollw.php" method="GET">
						<input type="SUBMIT" style="background-color:yellow; color:#00F; border-radius = 25px;" value="FollowTheWhiteRabbit" name="newFollow"/>
					</form>
					';
				}
			}
		?>
		</div>
		</center>
	</body>
</html>