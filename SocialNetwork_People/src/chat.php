<?php
	session_start();	
?>
<!DOCTYPE html>
<html>
	<head>
		<script src="js/jquery-2.2.4.min.js"></script>
		<link rel="stylesheet" href="css/MoreStyle.css" >
	</head>
	<body>
		
		<div class="chatText" >
			<div class="chatTextBody">
			
			</div>
			<div>
				<textarea class="inputTextChat" type="text" placeholder="Insert Text" ></textarea>
				<button class="textChatBttn" > Send </button>
				<button class="backChatBttn"> Back </button>
				<span class="alertChat" ></span>
			</div>
		</div>
		<div class="chatOnline">
		</div>
		
	<script>
		var other_usr;
		
		$(document).ready(function(){
			$(".chatText").hide();
			$(".divUsr").hide();
			get_chat_mex();
			var chattimer = window.setInterval(get_chat_mex, 5000);
			var onlinetimer = window.setInterval(get_user_online, 5000);
			var alivetimer = window.setInterval(i_am_alive, 1000);
			get_user_online()
			
			$(document).keypress(function(e) {
				if(e.which == 13) {
					if($(".inputTextChat").val() != ""){
						send_chat_mex();
					}
					else{
						$(".alertChat").html("Insert<br/>Text");
					}
				}
			});
			
			$(".textChatBttn").click(function(){
				if($(".inputTextChat").val() != ""){
					send_chat_mex();	
				}
				else{
					$(".alertChat").html("Insert<br/>Text");
				}			
			});

			$(".backChatBttn").click(function(){
				$(".chatOnline").show();		
				$(".chatText").hide();
				$("#"+other_usr).hide();			
			});

			function send_chat_mex(){
				var textMex = $(".inputTextChat").val();
				
				$.ajax({
					type: 'POST',
					url: 'options.php',
					data:{'inputTextChat': textMex, 'sendchatmex' : 'true', 'otheruser' : other_usr,'username' : '<?php echo $_SESSION['username']; ?>'},
					dataType: 'html',

					/*
					+ In Case Of Success...
					*/
					success: function(data){
						$(".alertChat").html("Mex<br/>Sent");
					},
					
					/*
					+ In Case Of Error...
					*/
					error: function(wrong){
						$(".alertChat").html("Mex<br/>Not Sent");
					}
				});
				
				$(".inputTextChat").val("");
			}
			function get_chat_mex(){
				$.ajax({
					type: 'POST',
					url: 'getChatMex.php',
					data:{'getchatmex' : 'true' , 'username' : '<?php echo $_SESSION['username']; ?>'},
					dataType: 'html',
					success: function(data){
						$(".chatTextBody").html(data);
						$(".divUsr").hide();
						$(".alertChat").html("");
						$("#"+other_usr).show();
					},
					error: function(wrong){
						$(".chatTextBody").html("Error:" + wrong.status);
					}
				});
			}
			
			function get_user_online(){
				$.ajax({
					type: 'POST',
					url: 'getChatMex.php',
					data:{'getuseronline' : 'true' , 'username' : '<?php echo $_SESSION['username']; ?>'},
					dataType: 'html',
					success: function(data){
						$(".chatOnline").html(data);
						$(".divUsrChat").click(function(){
							$(".chatText").show();
							other_usr = $(this).attr('id');
							other_usr = $(this).attr('id').substr(0,($(this).attr('id').length - 2));
							$("#"+other_usr).show();
							$(".chatOnline").hide();
						});
					},
					error: function(wrong){
						$(".chatOnline").html("Error:" + wrong.status);
					}
				});
			}
			
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
		});
	
	</script>
	
	</body>
</html>
