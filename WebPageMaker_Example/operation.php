<?php
	session_start();
	include('File_Functions.php');
	include('DB_Functions.php');
	
	if(!isset($_SESSION['username']) | !isset($_COOKIE['cookie'])){ 
		header('location: 404.php');
	}
	else{
		if($_REQUEST['htmlName'] == ""){
			$_SESSION['Error'] = "[+] Campi Non Completi [+]";
			header('location: user_page.php');
		}
		else{
			if(strpos(" ".$_REQUEST['htmlName'],"../")){
				$_SESSION['Error'] = "[+] Dot-Dot-Slash Non &eacute Permesso :D :D [+]";
				header('location: user_page.php');
			}
			else{
				if(isset($_REQUEST['delPage'])){
					if(delPage($_REQUEST['htmlName'])){
						delPageDB($_REQUEST['htmlName']);
						$_SESSION['Message'] = "[+] Pagina Eliminata Correttamente [+]";
					}
					else{
						$_SESSION['Error'] = "[+] Non E' Stato Possibile Eliminare La Pagina [+]";
					}
					header('location: user_page.php');
				}
				if(isset($_REQUEST['changePage'])){
					if(!file_exists("dataDir/" . $_SESSION['username'] . "/" . $_REQUEST['htmlName'])){
						$_SESSION['Error'] = "[+] La Pagina Inserita Non Esiste [+]";
						header('location: user_page.php');
					}
					$string = readPage($_REQUEST['htmlName']);
					$modifica = True;
					$intestazione = "Modifica Pagina Esistente";
					unset($_REQUEST['changePage']);
				}
				if(isset($_REQUEST['newPage'])){
					if(file_exists("dataDir/" . $_SESSION['username'] . "/" . $_REQUEST['htmlName'].".php") & file_exists("dataDir/" . $_SESSION['username'] . "/" . $_REQUEST['htmlName'].".html")){
						$_SESSION['Error'] = "[+] La Pagina Inserita Esiste Gi&aacute [+]";
						header('location: user_page.php');
					}
					$intestazione = "Crea Nuova Pagina";
				}
				if(isset($_REQUEST['sendText'])){
					if(isset($_REQUEST['extFile'])){
						$_SESSION['htmlName'] .= (($_REQUEST['extFile'] == "HTML")? ".html" : ".php");
						$hide = (($_REQUEST['hidePage'] == "Hide") ? 's' : 'n' );
						addPageDB($_SESSION['htmlName'],$hide);
					}
					if($_SESSION['textArea'] = " "){
						$_SESSION['textArea'] = "Pagina Vuota";
					}
					if(writePage($_SESSION['htmlName'],$_REQUEST['textArea'])){
						$_SESSION['Message'] = "[+] Operazione Eseguita Con Successo [+]";
					}
					else{
						$_SESSION['Error'] = "[+] Sono Presenti Parole Non Ammesse Nel Testo [+] <br/> <br/> E' stato creato un file temporaneo nel quale potrai trovare il testo non convalidato";
					}
					header('location: user_page.php');
				}
				$_SESSION['htmlName'] = $_REQUEST['htmlName'];
			}
		}
	}
	
	
?>

<html>
	<head>
		<title>
			Option Panel
		</title>
	</head>
	<body>
	<center>
		<div style="position:absolute;right:1%;left:1%;top:1%;bottom:90%;background-color:#000;">
			<font color=yellow size=5> 
				<?php echo $intestazione; ?> <br/>
			</font>
			<font style="position:absolute;left:1%;top:10%;color:white;font-size:10;"> 
			Poich&egrave non &eacute possibile inserire direttamente le tabulazioni: premendo il bottone "TAB" <br/> tutti i caratteri "<font size=5>\t</font>" inseriti nel testo verranno sostituiti man-mano da tabulazioni.
			</font>
		</div>
		<div style="position:absolute;right:85%;left:1%;top:10%;bottom:1%;background-color:#0FF;">
			<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" id="TAB" onclick='ButtonFunction("tab")'> TAB </button> <br/> <br/>
			<font color=#F00> HTML Tags: </font> <br/>
			<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" id="html" onclick='ButtonFunction("html")'> html </button>
			<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" id="head" onclick='ButtonFunction("head")'> head </button>
			<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" id="body" onclick='ButtonFunction("body")'> body </button>
			<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" id="font" onclick='ButtonFunction("font")'> font </button>
			<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" id="form" onclick='ButtonFunction("form")'> form </button>
			<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" id="button" onclick='ButtonFunction("button")'> Submit_Button </button>
			<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" id="text" onclick='ButtonFunction("text")'> text </button>
		</div>
		<div style="position:absolute;right:1%;left:15%;top:10%;bottom:1%;background-color:#000;">
			<form action="" method="POST">
				<textarea id="txtar" style="overflow-y:scroll; width:700px; height:450px; margin:5px;" NAME="textArea">
<?php if(isset($string)){ echo $string;} else{ echo "";} ?></textarea><br/> <br/>
				<?php 
				if(!isset($modifica)){
					echo '<SELECT NAME="extFile">';
					if(!file_exists("dataDir/" . $_SESSION['username'] . "/" . $_REQUEST['htmlName'].".html")){
						echo "<OPTION>HTML</OPTION>";
					}
					if(!file_exists("dataDir/" . $_SESSION['username'] . "/" . $_REQUEST['htmlName'].".php")){
						echo "<OPTION>PhP</OPTION>";
					}
					echo '</SELECT>';
				}
				?>				
				<input style="border-radius:25px; border-color:red;"type="SUBMIT" name="sendText" value="Invia" />
				<SELECT NAME="hidePage">
					<OPTION>Not Hide</OPTION>
					<OPTION>Hide</OPTION>
				</SELECT>
			</form>
			<font color=#0F0> Ritorna Alla Tua Pagina Utente </font> <br/>
				<a style="color:#FFF;" href="user_page.php"> Link </a>			
		</div>
	</center>
	</body>
</html>

<script type="text/javascript">
		function ButtonFunction(id){
			var txtArea = document.getElementById("txtar").value;
			var str = "";
			switch(id){
				case 'tab':
					for(var v=0; v < txtArea.length; v++){
						var c = txtArea[v];
						if(c == '\\' && txtArea[v+1] == 't'){
							str = "\t" + txtArea.substring((v+2));
							txtArea = txtArea.substring(0,v);
						}
					}
				break;
				case 'html':
					str = "<html> </html>";
				break;
				case 'head':
					str = "<head> </head>";
				break;
				case 'body':
					str = "<body> </body>";
				break;
				case 'font':
					str = "<font style=\"color: ;font-size: ;\"> </font>";
				break;
				case 'form':
					str = "<form action=\"\" method=\"\"> </form>";
				break;
				case 'button':
					str = "<input type=\"SUBMIT\" style=\"\" value=\"\" name=\"\" />";
				break;
				case 'text':
					str = "<input type=\"text\" style=\"\" value=\"\" name=\"\" />";
				break;
				default:
					str = "Uknown";
				break;
			}
			ntxt = txtArea + str;
			document.getElementById("txtar").value = ntxt; 
		}
</script>