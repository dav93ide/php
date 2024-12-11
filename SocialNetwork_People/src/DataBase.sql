CREATE DATABASE IF NOT EXISTS people_db CHARSET=utf8;

USE people_db;				

CREATE TABLE IF NOT EXISTS people_db.secretquestions(
	id			 INT(10)			 NOT NULL	 AUTO_INCREMENT,
	question	 VARCHAR(250)	 NOT NULL,
	PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS people_db.usertab(
	id 				INT(10) 			NOT NULL 	 AUTO_INCREMENT, 
	username		 VARCHAR(30) 	 NOT NULL, 
	password		 VARCHAR(255) 	 NOT NULL,
	email			 VARCHAR(50)		 NOT NULL,
	first_name		 VARCHAR(30)		 NOT NULL,
	last_name		 VARCHAR(30)		 NOT NULL,
	birth_date		 DATE			 NOT NULL,
	sex				 ENUM('m','f')	 NOT NULL,
	id_secret		 INT(10)			 NOT NULL,
	secret_answer	 VARCHAR(50)		 NOT NULL,
	registrazione 	 DATETIME 		 NOT NULL 	 DEFAULT CURRENT_TIMESTAMP,
	lastlog			 DATETIME 		 NOT NULL 	 DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(id), 
	UNIQUE(username,email),
	FOREIGN KEY(id_secret) REFERENCES secretquestions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO people_db.secretquestions(question) VALUES ("Nome Del Tuo Animale Domestico");
INSERT INTO people_db.secretquestions(question) VALUES ("Nome Del Tuo Libro Preferito");
INSERT INTO people_db.secretquestions(question) VALUES ("Data Dell Evento Pi&ugrave Importante Della Tua Vita");
INSERT INTO people_db.secretquestions(question) VALUES ("Nome Della Tua Canzone Preferita");
INSERT INTO people_db.secretquestions(question) VALUES ("Un Codice PIN Alfanumerico Segreto");
INSERT INTO people_db.secretquestions(question) VALUES ("Nome Del Tuo Film Preferito");

INSERT INTO people_db.usertab(username,password,email,first_name,last_name,birth_date,sex,id_secret,secret_answer) VALUES ('4dMiN','LoL','admin@admin.it','admin','admin',CURRENT_DATE(),'m',1,"AsD");

CREATE TABLE IF NOT EXISTS people_db.imglist(
	id 			 INT(10) 		 NOT NULL 	 AUTO_INCREMENT,
	img_link 	 VARCHAR(100) 	 NOT NULL,
	PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO people_db.imglist(img_link) VALUES("data/default.jpg");

CREATE TABLE IF NOT EXISTS people_db.userimg(
	id_img 		 INT(10) 		 NOT NULL,
	id_user		 INT(10)			 NOT NULL, 
	img_name	 VARCHAR(30)		 NOT NULL,
	profile		 ENUM('y','n')	 NOT NULL,
	mex			 VARCHAR(500)	 NOT NULL,
	upload_date	 DATETIME		 NOT NULL	 DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(id_user) REFERENCES people_db.usertab(id),
	FOREIGN KEY(id_img) REFERENCES people_db.imglist(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS people_db.followertab(
	id_usr 		 INT(10)			 NOT NULL, 
	id_fllwr 	 INT(10)			 NOT NULL, 
	added 		 DATETIME 		 DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(id_usr) REFERENCES people_db.usertab(id),
	FOREIGN KEY(id_fllwr) REFERENCES people_db.usertab(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS people_db.locked(
	id_usr		 INT(10)			 NOT NULL,
	id_lckd		 INT(10)			 NOT NULL,
	lock_date 	 DATETIME 		 DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(id_usr) REFERENCES people_db.usertab(id),
	FOREIGN KEY(id_lckd) REFERENCES people_db.usertab(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS people_db.mextab(
	id 				 INT(10) 		 NOT NULL 	 AUTO_INCREMENT,  
	message 		 VARCHAR(1000) 	 NOT NULL, 
	id_mittente		 INT(10) 		 NOT NULL,
	id_destinatario	 INT(10) 		 NOT NULL,
	data_invio 		 DATETIME 		 NOT NULL 	DEFAULT CURRENT_TIMESTAMP,
	mex_group		 INT(10)			 NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY(id_mittente) REFERENCES people_db.usertab(id),
	FOREIGN KEY(id_destinatario) REFERENCES people_db.usertab(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO people_db.mextab(message,id_destinatario,id_mittente,mex_group) VALUES("Benvenuto Nel Social Network People!",1,1,0);


CREATE TABLE IF NOT EXISTS people_db.storedmex(
	id_user			 INT(10)			 NOT NULL,
	id_mex			 INT(10)			 NOT NULL,
	FOREIGN KEY(id_user) REFERENCES people_db.usertab(id),
	FOREIGN KEY(id_mex) REFERENCES people_db.mextab(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS people_db.posts(
	id					 INT(10)			 NOT NULL	 AUTO_INCREMENT,
	post				 VARCHAR(1000)	 NOT NULL,
	id_destinatario		 INT(10) 		 NOT NULL,
	id_mittente 		 INT(10) 		 NOT NULL,
	data_invio 			 DATETIME 		 NOT NULL 	 DEFAULT CURRENT_TIMESTAMP,
	eliminato			 ENUM('s','n')	 NOT NULL	 DEFAULT 'n',
	post_group			 INT(10)			 NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY(id_destinatario) REFERENCES people_db.usertab(id),
	FOREIGN KEY(id_mittente) REFERENCES people_db.usertab(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS chattab(
	id 	INT(10) 	NOT NULL AUTO_INCREMENT,
	id_dest INT(10) 	NOT NULL,
	id_mitt INT(10) 	NOT NULL,
	mex_content 	VARCHAR(250) 	NOT NULL,
	mex_group 	INT(10) 	NOT NULL,
	date_sent	 DATETIME	 NOT NULL 	 DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(id),
	FOREIGN KEY(id_dest) REFERENCES usertab(id),
	FOREIGN KEY(id_mitt) REFERENCES	usertab(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS useronline(
	id INT NOT NULL AUTO_INCREMENT,
	name VARCHAR(20) NOT NULL,
	connessione DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY(id),
	UNIQUE(name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
