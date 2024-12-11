<?php
	echo "
	<div id=\"three\"  >
	Questa e` la pagina di risposta. <br/>
	Prende una classe CSS residente nella pagina principale dal quale e` stata fatta la richiesta e permette di avviare una funzione JS residente nella pagina principale. <br/>
	Per avviare la funzione clicca su: <div id=\"bttfun\" style=\"width:150px;height:50px;\" onclick=\"functionstart(". $_POST['Dati'] .")\" > Qui-Quo-Qua </div> <br/>
	Ho appena scoperto che la stessa cosa con un buttone non funziona:  <button id=\"bttfun\" onclick=\"functionstart(".$_POST['Dati'].")\" > Qui-Quo-Qua </button><br/>
	Qui invece viene stampato il dato trasmesso: <br/>"
	.(($_POST['Dati'] == "") ? "Non e` stato inserito alcun dato." : $_POST['Dati']).
	"</div>"
?>
