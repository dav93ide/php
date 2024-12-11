<?php
					include("db_functions.php");
					if(isset($_POST['username'])){
					
						$arrPosts = get_posts($_POST['username']);
						if($arrPosts){
							$username = $_POST['username'];
							$arrgroups = array();
							$maxposts = 0;
							$n=0;
							for($i=0;$i<count($arrPosts[2]);$i++){
								if(!in_array($arrPosts[2][$i],$arrgroups)){
									$first = false;
									$n++;
									if($maxposts == ($n-1)){
										$maxposts++;
									}
					?>
									<br/><br/>
									<div class="postgroup" id="postgroup<?php echo $maxposts; ?>D" onclick="show_post(<?php echo $maxposts; ?>)" >
										<span class="textPost" > [-- <?php echo $n; ?> --]<br/>
											<span style="color:yellow">	User:	</span> 	<?php echo $arrPosts[1][$i]; ?> <br/>
											<span style="color:yellow">	Data Invio:	</span>	<?php echo $arrPosts[3][$i]; ?><br/><br/>
											<?php echo $arrPosts[0][$i]; ?><br/>
										</span>
										<form action="options.php" method="POST">
											<?php
												if(isset($_GET['status'])){
													?>
														<input type="hidden" value="<?php echo $username; ?>" name="otheruser" />
														<input type="hidden" value="otheruser.php?username=<?php echo $username;?>" name="returnlink" />
													<?php
												}
											?>
											<input type="hidden" value="<?php echo $arrPosts[2][$i]; ?>" name="postgroup" /><br/>
											<textarea maxlength=1000 name="posttext" class="PostTextArea" ></textarea>
											<input id="optionsSubmitButton" style="position:absolute;right:3%;"  type="SUBMIT" name="answerpost" value="Rispondi" /> <br/>
										</form>
										
									</div>
					<?php
									array_push($arrgroups,$arrPosts[2][$i]);
									
								}
								else{
					?>	
									
									<div class="postgroup" style="position:absolute;left:0%;top:0%;z-index:10;" id="postgroup<?php echo $maxposts; ?>" onmouseleave="hide_posts()" >
					<?php
									if(!$first){
					?>
										<span class="textPost" > [-- <?php echo $n; ?> --]<br/>
											<span style="color:yellow">	User:	</span> 	<?php echo $arrPosts[1][(($i==0) ? 0 : $i - 1)]; ?> <br/>
											<span style="color:yellow">	Data Invio:	</span>	<?php echo $arrPosts[3][(($i==0) ? 0 : $i - 1)]; ?><br/><br/>
											<?php echo $arrPosts[0][(($i==0) ? 0 : $i - 1)]; ?><br/>
										</span>
					<?php
										$first = true;
									}
									$v = $i;
									do{
										if($arrPosts[2][$i] == $arrPosts[2][$v]){
					?>
												<span class="textPost" ><br/><br/>
													<span style="color:yellow">	User:	</span> 	<?php echo $arrPosts[1][$v]; ?> <br/>
													<span style="color:yellow">	Data Invio:	</span>	<?php echo $arrPosts[3][$v]; ?><br/><br/>
													<?php echo $arrPosts[0][$v]; ?> <br/>
												</span>
												
						<?php
											$v++;
										}
										else{
											break;
										}
									} while($v < count($arrPosts[2]));
									$i = (($v==0) ? 0 : $v - 1);
					?>
											<br/><br/>
											<form action="options.php" method="POST">		
												<?php
													if(isset($_GET['status'])){
												?>							
														<input type="hidden" value="<?php echo $username; ?>" name="otheruser" />
														<input type="hidden" value="otheruser.php?username=<?php echo $username;?>" name="returnlink" />
												<?php
												}
												?>
												<input type="hidden" value="<?php echo $arrPosts[2][$i]; ?>" name="postgroup" />
												<textarea maxlength=1000 name="posttext" class="PostTextArea" ></textarea>
												<input id="optionsSubmitButton" style="position:absolute;right:1%;"  type="SUBMIT" name="answerpost" value="Rispondi" /> <br/>
											</form>
										</div>
									<?php
									$maxposts++;
								}						
							}
						}
						else{
							echo "<br/><br/><font style=\"color:#FFF;font-size:20px;\" >Nessun Post Da Visualizzare </font> <br/><br/><br/><br/><br/>";
						}
						if(isset($maxposts)){
							echo "<p id=\"maxposts\" hidden>".$maxposts."</p>";
						}						
				}
				else{
					header("location: 404.html");					
				}
				?>
