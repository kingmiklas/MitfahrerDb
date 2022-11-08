drop database if exists MitfahrerDB;
create Database MitfahrerDB;
use MitfahrerDB;

create table if not exists tUser(
	kID int NOT NULL AUTO_INCREMENT,
    cVorname varchar(255) NOT NULL,
    cNachname varchar(255) NOT NULL,
    cEmail varchar(255) NOT NULL,
    bIsSchueler boolean NOT NULL, #die Email wird auf ein bestimmtes muster geprüft - so wird entschieden ob schüler oder Lehrer
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

Create table if not exists tRating(
	kID int NOT NULL AUTO_INCREMENT,
    nRating int NOT NULL,
    kUserGetRating int NOT NULL,
    kUserGiveRating int NOT NULL,
    Foreign Key(kUserGetRating) references tUser(kID),
    Foreign Key(kUserGiveRating) references tUser(kID),
    Primary Key(kID)
);

Create table if not exists tPostedRides(
	kID int NOT NULL AUTO_INCREMENT,
    dDatumUhrzeit dateTime NOT NULL,
    cStartOrt varchar(255) NOT NULL,
    cZielOrt varchar(255) NOT NULL,
    nSitzplaetze int NOT NULL,
    kErsteller int NOT NULL,
    nPreis decimal(6,2),
    Foreign Key(kErsteller) references tUser(kID),
    Primary Key(kID)
);

Create table if not exists tUserRides(
	kID int NOT NULL AUTO_INCREMENT,
    kRide int NOT NULL,
    kUser int NOT NULL,
    Primary Key(kID),
    Foreign Key(kRide) references tPostedRides(kID),
    Foreign Key(kUser) references tUser(kID)
);
    
    
