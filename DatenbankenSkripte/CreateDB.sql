drop database if exists MitfahrerDB; #zur Sicherheit damit keine Probleme mit schon bestehenden DBs auftreten können
create Database MitfahrerDB;
use MitfahrerDB; # gibt die DB an in der die Tabellen erstellt werden sollen

create table if not exists tUser(
	kID int NOT NULL AUTO_INCREMENT,
    cVorname varchar(255) NOT NULL,
    cNachname varchar(255) NOT NULL,
    cEmail varchar(255) NOT NULL,
    bIsSchueler boolean NOT NULL, #die Email wird auf ein bestimmtes muster geprüft - so wird entschieden ob schüler oder Lehrer
    # Die folgenden Attribute müssen nicht befüllt werden wenn der user es nicht will, deswegene kein NOT NULL
    bRaucher boolean,
    bTierhaare boolean,
    bMaskenpflicht boolean, 
    bGeschlecht enum('M','W','D'),
    cFreiInfo varchar(255),
    cHeimatsTreffpunkt varchar(255),
    Primary Key(kID)
    );

Create table if not exists tPasswort(
	kID int NOT NULL AUTO_INCREMENT,
    cPassword varchar(255) NOT NULL,
    kUser int NOT NULL,
    Primary Key(kID),
    Foreign Key(kUser) references tUser(kID)
);

#Tabelle in der das Rating gespeichert wird und die ID des Users der Bewertet und der Bewertet wird
Create table if not exists tRating(
	kID int NOT NULL AUTO_INCREMENT,
    nRating int NOT NULL,
    kUserGetRating int NOT NULL,
    kUserGiveRating int NOT NULL,
    Foreign Key(kUserGetRating) references tUser(kID),
    Foreign Key(kUserGiveRating) references tUser(kID),
    Primary Key(kID)
);

#Hier werden die Fahrten gespeichert
Create table if not exists tPostedRides(
	kID int NOT NULL AUTO_INCREMENT,
    dDatumUhrzeit dateTime NOT NULL,
    cStartOrt varchar(255) NOT NULL,
    cZielOrt varchar(255) NOT NULL,
    nSitzplaetze int NOT NULL,
    kErsteller int NOT NULL,
    nPreis int NOT NULL,
    bIsStorniert boolean NOT NULL default false,
    Foreign Key(kErsteller) references tUser(kID),
    Primary Key(kID)
);

#Zwischen tabelle von tPostedRides und tUser - hier wird gespeichert welcher user mit welcher fahrt gefahren ist 
Create table if not exists tUserRides(
	kID int NOT NULL AUTO_INCREMENT,
    kRide int NOT NULL,
    kUser int NOT NULL,
    Primary Key(kID),
    Foreign Key(kRide) references tPostedRides(kID),
    Foreign Key(kUser) references tUser(kID)
);
    
    
