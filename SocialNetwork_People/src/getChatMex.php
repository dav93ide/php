	<?php
				include("db_functions.php");
				if(isset($_POST['getchatmex'])){
				$arrMex = get_mex_chat($_POST['username']);
				if($arrMex){					
					foreach($arrMex as $val){
						if($val['destinatario'][0] == $_POST['username']){
							?>
								<div class="divUsr" id="<?php echo $val['mittente'][0];?>" >
							<?php
						}
						else{
							?>
								<div class="divUsr" id="<?php echo $val['destinatario'][0];?>" >
							<?php
						}
						for($i=0;$i<count($val['mittente']);$i++){
							if($val['destinatario'][$i] == $_POST['username']){
								?>
								
								<div class="chatDivReceived">
								<?php echo $val['data'][$i] . " | " .$val['mittente'][$i]; ?><br/>
								<?php
								echo $val['contenuto'][$i];
							}
							else{
								?>
								<div class="chatDivSent">
								<?php echo $val['data'][$i] . " | " ?> Tu <br/>
								<?php
								echo $val['contenuto'][$i];
							}
						?>
							</div>
						<?php
						}
						?>
							</div>
						<?php
					}
				}
				else{
					?>
					<p> Non ci sono messaggi </p>
					<?php
				}
			}
			else{
				if(isset($_POST['getuseronline'])){
					$arr = get_online_users($_POST['username']);
					if($arr){
						delete_offline();
						foreach($arr as $i=>$v){
							$img = get_user_profile_picture($v);
							echo "<div class=\"divUsrChat\" id=\"$v.2\" > $v <img src=\"$img\" class=\"usrImgChat\" style=\"position:absolute;right:0;\" /></div>";
						}	
					}
					else{
						?>
							<div style="color:#FFF;text-align:center;">
								<br/> <br/> Al Momento Non Ci Sono Persone Online!
							</div>
						<?php
					}
				}	
				else{
					if(isset($_POST['iamalive'])){
						i_am_alive($_POST['username']);
						echo "aaaa";
					}
					else{
						header("location: 404.html");
					}
				}			
			}
			?>
