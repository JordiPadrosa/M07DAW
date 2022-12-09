CREATE SCHEMA `dwes_jordipadrosa_autpdo`;
CREATE USER 'dwes-user'@'%' IDENTIFIED BY 'dwes-pass';
GRANT ALL PRIVILEGES ON dwes_jordipadrosa_autpdo.* TO 'dwes-user'@'%';

use `dwes_jordipadrosa_autpdo`;
create table users ( `email` varchar(30) primary key, `password` varchar(32),  `name` varchar(20));
create table connexions ( id int primary key auto_increment, ip varchar(15), `user` varchar(30), `time` varchar(30), `status` varchar(20));