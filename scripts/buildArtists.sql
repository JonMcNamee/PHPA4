drop database if exists musicdb;
create database musicdb;
use musicdb;

create table Artists
(
    artistID int(3) not null,
    artistName varchar(255) not null,
    genre varchar(255) not null,
    monthlySpotifyListeners int(10),
    primary key (artistID)
);

-- Artists
insert into Artists values(1,'Avenged Sevenfold','Metal',9371344);
insert into Artists values(2,'Tom Waits','Other',1635744);
insert into Artists values(3,'System of a Down','Metal',17510046);
insert into Artists values(4,'Johnny Cash','Country',11233837);
insert into Artists values(5,'Red Hot Chili Peppers','Funk Rock',30363793);
insert into Artists values(6,'Propagandhi','Punk',172047);
insert into Artists values(7,'Stan Rogers','Folk',190562);
insert into Artists values(8,'The Beatles','Rock',30129295);
insert into Artists values(9,'John Coltrane','Jazz',2451188);
insert into Artists values(10,'Fela Kuti','Jazz',526294);
insert into Artists values(11,'fIREHOSE','Punk',26429);
insert into Artists values(12,'The Mountain Goats','Indie Rock',787432);
insert into Artists values(13,'The Motorleague','Rock',18112);
insert into Artists values(14,'Frank Zappa','Other',1167627);
insert into Artists values(15,'AJJ','Folk-punk',584965);
insert into Artists values(16,'The Clash','Punk',11078025);
insert into Artists values(17,'Ren','Rap',848672);
insert into Artists values(18,'Haken','Prog Metal', 182803);
insert into Artists values(19,'Tenacious D','Comedy/Metal',2873345);
insert into Artists values(20,'Merle Haggard','Country',1952168);