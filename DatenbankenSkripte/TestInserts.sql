use mitfahrerdb;

insert into mitfahrerdb.tuser(cVorname,cNachname,cEmail,bIsSchueler,bRaucher,bTierhaare,bMaskenpflicht,bGeschlecht,cFreiInfo,cHeimatstreffpunkt)
Values ('Gandalf','Der Weiße', 'gandalf.d@gso.schule.koeln', true,true,false,false,'M','You shall not pass','shire');

insert into mitfahrerdb.tuser(cVorname,cNachname,cEmail,bIsSchueler,bRaucher,bTierhaare,bMaskenpflicht,bGeschlecht,cFreiInfo,cHeimatstreffpunkt)
Values ('Lightning','McQueen', 'l.mcqueen@gso.schule.koeln', false, false, false, true,'D','KAAACHOOOWWW', 'Autobahn A1');

insert into mitfahrerdb.tuser(cVorname,cNachname,cEmail,bIsSchueler,bRaucher,bTierhaare,bMaskenpflicht,bGeschlecht,cFreiInfo,cHeimatstreffpunkt)
Values ('Ahsoka', 'Tano', 'ahsoka.t@gso.schule.koeln', true, false, true, false, 'W', 'Watch this Skyguy', 'Shili');

insert into mitfahrerdb.tPasswort(cPassword,kUser) Value ('AlterToby', 1);

insert into mitfahrerdb.tPasswort(cPassword,kUser) Value ('Benzin', 2);

insert into mitfahrerdb.tPasswort(cPassword,kUser) Value ('JediOrden', 3);

insert into mitfahrerdb.trating(nRating,kUserGetRating,kUserGiveRating) Value (5,1,2);

insert into mitfahrerdb.trating(nRating,kUserGetRating,kUserGiveRating) Value (3,2,3);

insert into mitfahrerdb.trating(nRating,kUserGetRating,kUserGiveRating) Value (5,3,1);

insert into mitfahrerdb.tpostedrides(dDatumUhrzeit, cStartOrt, cZielOrt, nSitzplaetze, kErsteller, nPreis)
Values ('2022-11-07 08:30:00', 'Wienerplatz', 'GSO', 4, 1,5);

insert into mitfahrerdb.tpostedrides(dDatumUhrzeit, cStartOrt, cZielOrt, nSitzplaetze, kErsteller, nPreis)
Values ('2022-10-24 07:00:00', 'Neumarkt', 'GSO', 2,2,6);

insert into mitfahrerdb.tpostedrides(dDatumUhrzeit, cStartOrt, cZielOrt, nSitzplaetze, kErsteller, nPreis)
Values ('2022-8-01 16:35:00', 'GSO', 'Frechen Einkaufscenter', 5, 3,7);

insert into mitfahrerdb.tuserrides(kRide, kUser) Values (1,2);
insert into mitfahrerdb.tuserrides(kRide,kUser) Values (1,3);

insert into mitfahrerdb.tuserrides(kRide, kUser) Values (2,1);
insert into mitfahrerdb.tuserrides(kRide,kUser) Values (2,3);

insert into mitfahrerdb.tuserrides(kRide, kUser) Values (3,2);
insert into mitfahrerdb.tuserrides(kRide,kUser) Values (3,1);


