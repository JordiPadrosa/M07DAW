CREATE SCHEMA `dwes_jordipadrosa_concursgossos`;
GRANT ALL PRIVILEGES ON dwes_jordipadrosa_concursgossos.* TO 'dwes-user'@'%';
use `dwes_jordipadrosa_concursgossos`;
create table users ( `usuari` varchar(30) primary key, `password` varchar(32));
insert into users values ('admin', md5('admin'));

create table vots ( `id` int primary key auto_increment, `numVots` int, `fase` int, `gos` varchar(100));

create table fases ( `num` int(1) primary key, `dataInici` date, `dataFinal` date);
insert into fases values ( 1, '2023-01-01', '2023-01-31'); 
insert into fases values ( 2, '2023-02-01', '2023-02-28'); 
insert into fases values ( 3, '2023-03-01', '2023-03-31'); 
insert into fases values ( 4, '2023-04-01', '2023-04-30'); 
insert into fases values ( 5, '2023-05-01', '2023-05-31'); 
insert into fases values ( 6, '2023-06-01', '2023-06-30'); 
insert into fases values ( 7, '2023-07-01', '2023-07-31'); 
insert into fases values ( 8, '2023-08-01', '2023-08-30'); 

create table gossos ( `nom` varchar(100) primary key , `img` varchar(100), `amo` varchar(100), `raça` varchar(100));
insert into gossos values ('Musclo', 'Img/g1.png', 'Jordi', 'Husky');
insert into gossos values ('Jingo', 'Img/g2.png', 'Pep', '2');
insert into gossos values ('Xuia', 'Img/g3.png', 'Josep', '3');
insert into gossos values ('Bruc', 'Img/g4.png', 'Raul', '4');
insert into gossos values ('Mango', 'Img/g5.png', 'Liu', '5');