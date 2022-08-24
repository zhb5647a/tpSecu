create database tpsecu;
use tpsecu;
create table evenement(
    id_event int auto_increment primary key not null,
    nom_event varchar(50),
    desc_event text,
    date_event datetime
);
create table utilisateur(
	id_util int primary key auto_increment not null,
    name_util varchar(50) not null,
    first_name_util varchar(50) not null,
    mail_util varchar(50) not null,
	pwd_util varchar(100) not null,
    id_role int,
    valide_util tinyint(1),
    token_util varchar(100)
)Engine=InnoDB;
create table role(
	id_role int primary key auto_increment not null,
    name_role varchar(50) not null
)Engine=InnoDB;
alter table utilisateur
add constraint fk_attribuer_role
foreign key(id_role)
references role(id_role);


insert into role(name_role)values("utilisateur"),("administatreur"); 

CREATE USER 'utilisateur1'@'%' identified by '1234';
GRANT SELECT, INSERT ON tpsecu.* TO 'utilisateur1'@'%';
FLUSH PRIVILEGES;
CREATE USER 'admin1'@'%' identified by '1234';
GRANT All privileges ON tpsecu.* TO 'admin1'@'%';