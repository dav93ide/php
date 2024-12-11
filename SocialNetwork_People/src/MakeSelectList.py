

f = open("SelectOptionsList.txt","w");
f.write("DAY LIST: \n\n")
for i in range(1,32):
	if i < 10:
		d = "0%d" % i
		f.write("<option value=\"%s\">%s</option>\n" % (d,d))
	else:
		f.write("<option value=\"%d\">%d</option>\n" % (i,i))
f.close()
f = open("SelectOptionsList.txt","a")
f.write("\n\n\n\nMONTH LIST: \n\n")
for i in range(1,13):
	if i < 10:
		d = "0%d" % i
		f.write("<option value=\"%s\">%s</option>\n" % (d,d))
	else:
		f.write("<option value=\"%d\">%d</option>\n" % (i,i))
f.write("\n\n\n\nYEAR LIST: \n\n")
for i in range(1900,2017):
	f.write("<option value=\"%d\">%d</option>\n" % (i,i))
f.write("\n\n\n\nLIST FOR CHANGE DAY LIST ON THE FLY (JAVASCRIPT): \n\n")
for i in range(1,32):
	if i < 10:
		d = "0%d" % i
		f.write("document.form01.day.options[0] = new Option(\"%s\",\"%s\",false,false)" % (d,d))
	else:
		f.write("document.form01.day.options[0] = new Option(\"%d\",\"%d\",false,false)" % (i,i))
f.close()