<!DOCTYPE HTML>
<html>

	<head>
	
		<title>
			- Trying /\ Ajax -
		</title>
		
		<style>
			#one{
				position: relative;
				text-align:center;
				background-color:yellow;
				animation-name: animazione;
				animation-duration: 10s;
				opacity: 1;
			}
			#two{
				position: relative;
				text-align:center;
				background-color: #F0F;	
				animation-name: animazione;
				animation-duration: 15s;	
				opacity: 1;		
			}
			<!-- Definizione dell'animazione -->
			@keyframes animazione{
				0%{
					background-color: #0F0;
					position: relative;
					top: 500%;
					right: 500%;
				}
				50%{
					background-color: #F0F;
					position:  relative;
					top: 50%;
					right: 50%;
				}
				100%{
					background-color: #F0F;
					position:  relative;
				}
			}
			
			@keyframes animazionedue{
				0%{
					background-color: #FF0;
					position: relative;
					top: 500%;
					right: 500%;
				}
				50%{
					background-color: #00F;
					position:  relative;
					top: 50%;
					right: 50%;
				}
				100%{
					background-color: #FF0;
					position:  relative;
				}
			}
			#three{
				text-align:center;
				background-color:white;
			}
		</style>
		
		<script src="jquery-2.2.4.min.js"> </script>
		
	</head>
	
	<body>
		La pagina utilzza Ajax per recuperare info da un'altra pagina.
		La prima parte recupera con JSON e non invia alcun dato, la seconda recupera HTML e effettua elaborazione PHP sulla pagina remota.
		Ti ho aggiunto il funzionamento di semplici animazioni se le vuoi aggiungere al programma, comunque oltre alla funzione ajax puoi utilizzare "$.post" e "$.get".
		<br/> <br/> <br/>
		
		<!-- Div Per Inserimenti -->
		<div style="text-align:center;">
		<span style="color:#F00;font-size:25px;">ANIMAZIONI</span> <br/> <br/>
			<label>Sposta A Destra: </label> <button id="animmovdx">Click</button> <br/>
			<label>Sposta A Sinistra: </label><button id="animmovsx">Click</button> <br/>
			<label>Colora Di Rosso: </label><button id="redcol">Click</button> <br/>
			<label>Colora Di Giallo: </label><button id="yellowcol">Click</button> <br/>
			<label>Mostra/Nascondi Parte JSON: </label><button id="toggleJSON">Click</button> <br/>
			<label>Mostra/Nascondi Parte HTML: </label><button id="toggleHTML">Click</button> <br/>
			<br/> <br/>
			Verra` Animato L'Elemento:
			<br/> <br/>
			<div id="soggetto" style="position:relative;right:-50%;color:white;border-radius:25px;border:2px solid red;background-color:black;width:125px;height:50px;">
				</div>
			<br/> <br/> <br/>
		</div>
	
		
		
		<div class="json" style="text-align:center;" >
			<span style="color:#F00;font-size:25px;">RISPOSTA STATICA IN JSON</span>
			<div id="one" class="test"> Hello_Layer: <br/> <br/> <br/> </div>
			<div id="one" class="test2"> Answer_Layer: <br/> <br/> <br/> </div>
			<div id="one" class="test3"> Error_Layer: <br/> <br/> <br/> </div>
		</div>
		<br/> <br/> <br/> <br/> <br/>
		
		<div class="html" style="text-align:center;" >
			<span style="color:#F00;font-size:25px;">RISPOSTA DINAMICA IN HTML</span>
			<input type="text" id="inputT" />
			<button id="inputB" > Invia Dati </button>
			<div id="one" class="test4">
			Dopo che clicchi invio il testo verra` aggiunto qui sotto:<br/> <br/> <br/>
			</div>
		</div>
		<script> 
			
			// Quando il documento e` pronto esegui:
			$(document).ready(function(){
				get_JSON();
				// E se uno di questi elementi DOM viene cliccato esegui:
				$("#inputB").click(function(){
					 get_HTML();
					});
				$("#animmovdx").click(function(){				
					$("#soggetto").css({"right":(parseInt($("#soggetto").css("right").substr(0,($("#soggetto").css("right").length-2))) + 25)});
				});
				
				$("#animmovsx").click(function(){
					$("#soggetto").css({"right": (parseInt($("#soggetto").css("right").substr(0, ($("#soggetto").css("right").length-2) ) )- 25)});
				});
				
				
				
				$("#redcol").click(function(){
					$("#soggetto").css({"background-color":"red"});
				});
				
				$("#yellowcol").click(function(){
					$("#soggetto").css({"background-color":"yellow"});
				});
				
				$("#toggleJSON").click(function(){
					$(".json").toggle(1000);
				});
				
				$("#toggleHTML").click(function(){
					$(".html").toggle(1000);
				});
				
					
			});
			
			function get_JSON(){
				$.ajax({
					type: 'POST',
					url: 'phpRequestJSON.php',
					dataType: 'json',

					/*
					+ In Case Of Success...
					*/
					success: function(data){
						$(".test2").append("<br/><br/>-- Answer Start --<br/><br/><br/>");
						var arrKeys = data.chiavi;
						var arrValues = data.valori;
						for(var i=0;i<5;i++){
							$(".test2").append((i+1) + "] <font style=\"color:red;position:relative;left:10px;\">" + arrKeys[i] + "</font> <font style=\"color:blue;position:relative;left:50px;\">" + arrValues[i] + "</font><br/>" );
						}
						$(".test2").append("<br/><br/>-- Answer Stop --<br/><br/><br/><br/>");
					},
					
					/*
					+ In Case Of Error...
					*/
					error: function(wrong){
						$(".test3").append("Ops, qualcosa non ha funzionato");
					}
				});
		}
			
		function functionstart(val){
			alert("Funzione Avviata, Passato Dato: " + val);
		}
			
		function get_HTML(){
			alert($("#inputT").val());
				/*
				+ Invio Della Richiesta POST Ajax...
				*/
				$.ajax({
					type: 'POST',
					url: 'phpRequestHTML.php',
					data: { "Dati": $("#inputT").val() },
					dataType: 'html',

					/*
					+ In Case Of Success...
					*/
					success: function(data){
						$(".test4").append(data);
						// Le operazioni su elementi ricevuti dalla pagina caricata in maniera asincrona vengono qui specificate:
						$("#bttfun").css({"background-color":"red","color":"white"});
					},
					
					/*
					+ In Case Of Error...
					*/
					error: function(wrong){
						$(".test4").append("Ops, Qualcosa non ha funziona:" + wrong.status);
					}
				});
		}
		</script>
		
	</body>
</html>

<!--

	contentType: 'application/json; charset=utf-8',
	context: document.head.title.innerHTML,
	contentType: "text/plain"
	var jsonData = json_encode(data);
	$(".test").html(jsonData);
	var obj = JSON.parse(data);

-->
