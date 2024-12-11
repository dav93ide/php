<html>
	<head>
		<title> 
			Quering
		</title>
	</head>
	<body style="background-color:black;">
		<center>
		<div style="position:absolute;top:2%;right:10%;left:10%; bottom:75%;background-color:#0F0;">
			<font style="font-size:100px;font-family:'arial'color:#F0F"> Qu3rY ?! </font>
		</div>
		<form action="PHPQueries.php" method="GET">
			<div style="position:absolute; left:50%; right:1%; top:30%; bottom:10%;background-color:#0FF;border-radius:50px;border:2px solid white">
				<div style="position:absolute; top:35%;right:30%; bottom:40%;background-color:#F0F;border:5px solid black">
					<font color=white> Nome DB: </font><br/><br/>
					<input  type="text" name="dbName" placeholder="Inserire Nome DB" />
				</div>
			</div>
			<div style="position:absolute; left:15%; right:50%; top:30%; bottom:10%;background-color:#F0F;border-radius:50px;border:2px solid white">
				<font color=#FFF> Put (ABOVE) Ur Queries Up In The Air </font>
				<textarea id="txtar" name="txtArea" style="overflow-y:scroll;width:340;height:350;border-radius:10px;"></textarea>
			</div>
			<input style="font-size:23px;font-family:'arial';width:20%;height:5%;position:absolute;right:40%;bottom:5%;top:92%;background-color:yellow;color:blue;border-radius:50px;" type="SUBMIT" name="invia" value="Ex Query" />
		</form>
		<div style="position:absolute;left:1%;top:25%;">
				<font color=white style="position:absolute;left:1%;font-family='arial';"> Basic Queries: </font> <br/>
				<li><button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("bquery")'> QUERY </button><br/>
				<li><button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("bsubquery")'> SUBQUERY </button> </li><br/>
				<font color=white style="position:absolute;left:1%;font-family='arial';"> Basic Keywords: </font> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("select")'> SELECT </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("from")'> FROM </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("where")'> WHERE </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("having")'> HAVING </button> <br/> 
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("limit")'> LIMIT </button> <br/> <br/>
				<font color=white style="position:absolute;left:1%;font-family='arial';"> Join(t)s: </font> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("ijoin")'> INNER JOIN </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("ljoin")'> LEFT JOIN </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("rjoin")'> RIGHT JOIN </button><br/>
				<li><button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("on")'> ON </button></li><br/>
				<font color=white style="position:absolute;left:1%;font-family='arial';"> Order & Group: </font> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("groupby")'> GROUP BY </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("orderby")'> ORDER BY </button><br/>
				<li><button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("desc")'> DESC </button></li> <br/>
				<font color=white style="position:absolute;left:1%;font-family='arial';"> Basic Functions: </font> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("count")'> COUNT() </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("avg")'> AVG() </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("max")'> MAX() </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("min")'> MIN() </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("len")'> LEN() </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("first")'> FIST() </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("last")'> LAST() </button> <br/>
				<button style="width:100px;height:20px;background-color:yellow; border-radius:25px;color:blue;" onclick='ButtonFunction("sum")'> SUM() </button> <br/>
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
				case 'select':
					str = " SELECT ";
				break;
				case 'from':
					str = " FROM ";
				break;
				case 'where':
					str = " WHERE ";
				break;
				case 'groupby':
					str = " GROUP BY ";
				break;
				case 'having':
					str = " HAVING ";
				break;
				case 'ijoin':
					str = " INNER JOIN ";
				break;
				case 'ljoin':
					str = " LEFT JOIN ";
				break;
				case 'rjoin':
					str = " RIGHT JOIN ";
				break;
				case 'on':
					str = " ON ";
				break;
				case 'orderby':
					str = " ORDER BY ";
				break;
				case 'desc':
					str = " DESC ";
				break;
				case 'limit':
					str = " LIMIT ";
				break;
				case 'bquery':
					str = "SELECT * \n FROM tableName \n WHERE conditions";
				break;
				case 'bsubquery':
					str = "SELECT * \n FROM tableName \n WHERE value \n IN \n (SELECT column FROM tableName2 WHERE conditions)";
				break;
				case 'count':
					str = "COUNT(*)";
				break;
				case 'max':
					str = "MAX()";
				break;
				case 'min':
					str = "MIN()";
				break;
				case 'avg':
					str = "AVG()";
				break;
				case 'first':
					str = "FIRST()";
				break;
				case 'last':
					str = "LAST()";
				break;
				case 'sum':
					str = "SUM()";
				break;
				case 'len':
					str = "LEN()";
				break;
				default:
					str = "Uknown";
				break;
			}
			ntxt = txtArea + str;
			document.getElementById("txtar").value = ntxt; 
		}
		
		function TwoPlayers(){
		document.getElementById("stamp1").innerHTML = "AAAAAAAAAAAAAAAAAAAAAA";
	}
</script>