<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>eRRoRs</title>
<style>
div{
	background-image: url("img/Img07.jpg");
	
}

</style>
</head>
<body>

	
	<?php
		if(isset($_SESSION['Message'])){
			?>
			<div style="text-align:center;border:2px solid #000;color:#FFF;">
			<?php
			echo "<label style=\"color:#F0F\">Error Time!</label><br/><br/>" . $_SESSION['Message'];
			unset($_SESSION['Message']);
			?>
			</div>	
			<?php
		}
	?>
</body>
</html>
