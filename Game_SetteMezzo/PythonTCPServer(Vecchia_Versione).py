'''
	x----------------------x----------------------x
    x      									      x
    x      |7eMMeZZo|   |------|   |Phyton| 	  x
    x      									      x
    x----------------------x----------------------x
'''
# L'Unica Differenza Tra La "Vecchia" E La "Nuova" Versione  E' Quella Di Aver Integrato Il Dizionario "giocPuntate" All'Interno Del Dizionario "dictGame" Rendendo Quest'Ultimo Un Dizionario Di Dizionari.
import socket
import threading

def clientHandler(clientSock,num):
	request = clientSock.recv(1024);
	print "[%d] Received: %s \n" % (num, request)
	# Gestisce Richiesta Username-IDGame
	if(request[0] == "1"):
		print "[%d] Username & IDGame Request... \n" % num
		if(request[1:] in dict.keys()):
			playerName = request[1:]
			clientSock.send("0" + dict[playerName])
			print "[%d] Sent Answer Name[2]: %s \n" % (num,dict[playerName])
			del dict[playerName]
			if clientSock.recv(1024) == "ACK":
				print "[%d] ACK From client..." % num
				for n in dictGame.keys():
					if dictGame[n] == playerName:
						clientSock.send(str(n))
						print "[%d] ID Game Sent: %d \n" % (num,n)
						dictGame[n] = None		
			n = int(n)
			giocPuntate[n][playerName] = int(clientSock.recv(1024))
			print "[%d] %s's Moneys Added, Value: %s \n" % (num,playerName,giocPuntate[n][playerName])
			clientSock.send("ACK")
			clientSock.close()
			return
		for n in list2:
			if n != request[1:]:
				namePlayer = request[1:]
				if n in list2:
					list2.remove(n)
				if namePlayer in list2:
					list2.remove(namePlayer)
				clientSock.send("1" + n)
				print "[%d] Sent Answer Name[1]: %s \n" % (num,n)
				dict[n] = namePlayer
				if clientSock.recv(1024) == "ACK":
					print "[%d] ACK From client... \n" % num
					val = 0
					for v in dictGame.keys():
						if val != v:
							break
						val += 1
					clientSock.send(str(val))
					dictGame[val] = n
					print "[%d] ID Game Made & Sent: %d \n" % (num,val)
				else:
					print "[%d] Wrong Answer From Client In RichiestaGioco, Received: %s \n" % (num,request)
				giocPuntate[val] = {}
				giocPuntate[val][namePlayer] = int(clientSock.recv(1024))
				print "[%d] %s's Moneys Added, Value: %d \n" % (num,namePlayer,giocPuntate[val][namePlayer])
				clientSock.send("ACK")
				print "[%d] Closing Connection... \n" % num
				clientSock.close()
				return
		if not request[1:] in list2:
			list2.append(request[1:])
		clientSock.send("708")
		print "[%d] Sent \"708\" ." % num
	# SetCarte
	elif request[0] == "2":
		idGame = int(request[1:])
		print "[%d] ID Game: %s" % (num,idGame)
		print "[%d] Set Cards Request... \n" % num
		clientSock.send("ACK")
		print "[%d] ACK Sent...\n" % num
		dictGame[idGame] = clientSock.recv(1024)
		print "[%d] playerCard : %s Added To dictGame...\n" % (num,dictGame[idGame])
		clientSock.send("ACK");
		while True:
			pcCard = clientSock.recv(1024)
			if pcCard == "END":
				clientSock.send("ACK");
				break;
 			dictGame[idGame] += "." + pcCard
			print "[%d] Computer's Card Added : %s \n" % (num,pcCard)
			clientSock.send("ACK");
		print "[%d] All Cards Received... \n" % num
	# GetCarte
	elif request[0] == "3":
		idGame = int(request[1:])
		print "[%d] ID Game: %s" % (num,idGame)
		print "[%d] Get Cards Request... \n" % num
		if dictGame[idGame] == "c":
			clientSock.send("Pass")
			dictGame[idGame] = None
			print "[%d] Turn Passed Sent...\n" % num
			return
		elif dictGame[idGame] == "f":
			clientSock.send("Ended")
			print "[%d] Endend Sent..." % num
		else:
			clientSock.send(dictGame[idGame])
			print "[%d] All Cards Sent: %s\n" % (num,dictGame[idGame])
			dictGame[idGame] = None
	# Gestisce Richiesta Di Verifica Presenza Carte Da Recuperare
	elif request[0] == "4":
		print "[%d] Check Cards Request..." % num
		if int(request[1:]) in dictGame.keys():
			if dictGame[int(request[1:])]:
				print "[%d] Check Cards True... \n" % num
				clientSock.send("1")
			else:
				print "[%d] Check Cards False... \n" % num
				clientSock.send("0")
	# Fine Partita
	elif request[0] == "5":
		idGame = int(request[1:])
		print "[%d] Received Game Ended ID: %s\n" % (num,idGame)
		del dictGame[idGame]
		del giocPuntate[int(idGame)]		
	# Riceve Un "Passa Turno"
	elif request[0] == "6":
		print "[%d] Pass Turn Received..." % num
		if not dictGame[int(request[1:])] == "f":
			dictGame[int(request[1:])] = "c"
	# Riceve Un "Lasciato Partita In Corso"
	elif request[0] == "7":
		print "[%d] End Game Button Pressed... \n" % num
		if dictGame[int(request[1:])] == "f":
			print "[%d] Double End Button Pressed, Ending Game...\n" % num
			del dictGame[int(request[1:])]
			del giocPuntate[int(request[1:])]
		else:
			dictGame[int(request[1:])] = "f"
	# Riceve Una Richiesta Valore Puntata Utente2
	elif request[0] == "8":
		print "[%d] Other Player's Money Request... \n" % num
		idGame = int(request[1:])
		print "[%d] Game ID: %d" % (num,idGame)
		clientSock.send("ACK")
		print "[%d] ACK Sent... \n" % num
		otherPlayerName = clientSock.recv(1024)
		print "[%d] Other Player's Name: %s" % (num,otherPlayerName)
		clientSock.send(str(giocPuntate[idGame][otherPlayerName]))
		print "[%d] Other Player's Moneys Value Sent: %s\n" % (num,str(giocPuntate[idGame][otherPlayerName]))
	print "[%d] Closing Connection... \n" % num
	clientSock.close()
	return
		
ssock = None
n = 0;
list2 = [];
dict = {};
dictGame = {};
giocPuntate = {};

try:
	print "\t\t|**************************************|"
	print "\t\t[*] 7eMMeZZo - TCP Server Python 2.7 [*]"
	print "\t\t|**************************************|\n\n\n"
	print "[*] Creating Socket..."
	ssock = socket.socket(socket.AF_INET,socket.SOCK_STREAM,0)
	try:
		print "[*] Trying To Bind Socket..." 
		ssock.bind(('127.0.0.1',666))
		try:
			ssock.listen(10)
			print "[*] Socket Listening..."
			while True:
				try:
					print "[*] Waiting For Connections...\n"
					con,addr = ssock.accept()
					print "[*] Connected To: %s/%d \n" % (addr[0],addr[1])
					print "[*] Starting Thread...\n"
					n+=1
					handler = threading.Thread(target=clientHandler, args=(con,n))
					handler.start()
				except socket.error,msg:
					print "[*] Failed To Accept Connection, Error Code: %s - Message: %s \n" % (msg[0],msg[1])
		except socket.error,msg:
			print "[*] Failed To Listen, Error Code: %s - Message: %s \n" % (msg[0],msg[1])
	except socket.error,msg:
		print "[*] Failed To Bind Socket, Error Code: %s - Message: %s \n" % (msg[0],msg[1])
except socket.error, msg:
	print "[*] Failed To Create Socket, Error Code: %s - Message: %s \n" % (msg[0],msg[1])