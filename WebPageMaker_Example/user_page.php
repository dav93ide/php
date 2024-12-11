<?php
	session_start();
	include('File_Functions.php');
	include ('DB_Functions.php');
	unset($_SESSION['error']);
	unset($_SESSION['message']);
	unset($_SESSION['link']);
	unset($_SESSION['otherUser']);
	if(!isset($_SESSION['username']) && !isset($_COOKIE['cookie'])){
		header('location: 404.php');
	}
?>	
<html>
	<head>
		<title>
			User Page
		</title>
	</head>
	<body>
		<div style="position:absolute;right:1%;left:1%;top:1%;bottom:75%;background-color:white;border:2px solid black;">
		<center>
			<form action='check.php' method='POST'>
				<input style="background-color:yellow; color:blue;border-color:black;position:absolute;right:1%;border-radius:10px;" type="SUBMIT" value="Log-Out" name="logout"/>
			</form>
			<br/> <br/>
			<font color=blue size=5>
				Benvenuto Utente: <font color=red> <?php echo $_COOKIE["cookie"];?> </font>
			</font>
			<br/><br/><br/>
			Questa &egrave la tua pagina personale! 
			Da qui potrai vedere tutte le tue pagine, crearne di nuove o cercare e visitare le pagine di altri utenti!
			<br/><br/><br/>
		</center>
		</div>
		<div style="position:absolute;left:1%;right:50%;top:25%;bottom:35%;background-color:yellow;">
			<center> <font color=blue> Le Tue Pagine </font> <br/> <br/>
				<?php echo makeHtmlList(1); ?>
			</center>
		</div>
		<div style="position:absolute;right:1%;left:50%;top:25%;bottom:35%;background-color:#F0A;">
			<center><font color=yellow> Opzioni </font> <br/> <br/>
				<form action="operation.php" method="GET">
					<INPUT TYPE="text" LIST="pageList" NAME="htmlName" placeholder="Lista Pagine">
						<DATALIST ID="pageList">
							<?php echo makeHtmlList(2);	?>
					</DATALIST> <br/> <br/>
					<input Style="background-color:#AFA;border-color:yellow;border-radius:10px;"type="SUBMIT" name="changePage" value="Change Page" /> <br/><br/>
					<input Style="background-color:#AF0;border-color:yellow;border-radius:10px;" type="SUBMIT" name="newPage" value="New Page" /> <br/><br/>
					<input Style="background-color:#0FA;border-color:yellow;border-radius:10px;" type="SUBMIT" name="delPage" value="Delete Page" /> <br/><br/>
				</form>
				<form action="check.php" method="GET">
					<INPUT TYPE="text" LIST="fllwrList" NAME="fllwrList" placeholder="Persone Che Segui">
						<DATALIST ID="fllwrList">
							<?php echo makeHtmlList(3);	?>
					</DATALIST>
					<input Style="background-color:#0AA;border-color:yellow;border-radius:10px;" type="SUBMIT" name="delFllw" value="Elimina Follower" /> <br/>
				</form>
			</center>
		</div>
		<br/> <br/> <br/>
		<div style="position:absolute;left:1%;right:1%;bottom:0%;top:65%;background-color:#000;">
			<center><font color=white style="color:white;">
			<br/> <br/>
			<?php
				if(isset($_SESSION['Error'])){
					echo $_SESSION['Error'];
					unset($_SESSION['Error']);
				}
				if(isset($_SESSION['Message'])){
					echo $_SESSION['Message'];
					unset($_SESSION['Message']);
				}
			?>
			<br/><br/>
			Per Visualizzare La Lista Delle Pagine Degli Altri Utenti! <br/> <br/>
			<a href="PageList.php" style="color:#00F;border-radius:15px;border:1px white solid;font-size:20;background-color:#F0F;"> Clicca Qui </a> 
			</font></center>
		</div>
	</body>
</html>
	
<?php
	function makeHtmlList($option){
		switch($option){
			case 1:
			case 2:
				$arr = getUserPages();
			break;
			case 3:
				$arr = getFollowers();
			break;
		}
		$str = "";
		foreach($arr as $v){
			if($v == '..' | $v == '.'){
				continue;
			}
			switch($option){
				case 1:
					$str .= " <li> <a style=\"color:red;\" href=\"dataDir/" . $_SESSION['username'] . "/". $v ."\">" . 
					((!strpos($v,"html")) ? substr($v,0,-4) : substr($v,0,-5))  . "</a> </li>";
				break;
				case 2:
				case 3:
					$str .= "<OPTION VALUE=\"" . $v . "\">";
				break;
			}
		}
		if($str==""){
			$str = "<li> Nessuna Pagina Trovata </li>";
		}
		return $str;
	}
?>