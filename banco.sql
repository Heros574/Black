drop database if exists Black;
create database Black ;
use Black;



CREATE TABLE `usuario` (
  `nome` varchar(100) NOT NULL,
  `email` varchar(254) NOT NULL,
  `senha` varchar(200) NOT NULL,
  PRIMARY KEY (`email`),
  UNIQUE KEY `nome` (`nome`),
  UNIQUE KEY `email` (`email`)
);

CREATE TABLE `post` (
  `Titulo` varchar(100) NOT NULL,
  `conteudo` varchar(254) NOT NULL,
  `nomeU` varchar(100) NOT NULL,
  `id` varchar(10) NOT NULL,
  `quanti_Likes` int DEFAULT '0',
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_Post_User` (`nomeU`),
  CONSTRAINT `FK_Post_User` FOREIGN KEY (`nomeU`) REFERENCES `usuario` (`nome`)
);

CREATE TABLE `postcom` (
  `conteudo` varchar(254) NOT NULL,
  `NomeUR` varchar(100) NOT NULL,
  `NomeU` varchar(100) NOT NULL,
  `id` varchar(10) NOT NULL,
  `idPM` varchar(10) NOT NULL,
  `quanti_Likes` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_PostC_User` (`NomeU`),
  KEY `FK_Post_rM` (`idPM`),
  CONSTRAINT `FK_Post_rM` FOREIGN KEY (`idPM`) REFERENCES `post` (`id`),
  CONSTRAINT `FK_PostC_User` FOREIGN KEY (`NomeU`) REFERENCES `usuario` (`nome`)
) ;


CREATE TABLE `likesp` (
  `id` varchar(10) NOT NULL,
  `NomeU` varchar(100) NOT NULL,
  UNIQUE KEY `id` (`id`,`NomeU`),
  KEY `FK_User_L` (`NomeU`),
  CONSTRAINT `FK_Post_L` FOREIGN KEY (`id`) REFERENCES `post` (`id`),
  CONSTRAINT `FK_User_L` FOREIGN KEY (`NomeU`) REFERENCES `usuario` (`nome`)
) ;

CREATE TABLE `likespc` (
  `id` varchar(10) NOT NULL,
  `NomeU` varchar(100) NOT NULL,
  UNIQUE KEY `id` (`id`,`NomeU`),
  KEY `FK_User_LC` (`NomeU`),
  CONSTRAINT `FK_Post_LC` FOREIGN KEY (`id`) REFERENCES `postcom` (`id`),
  CONSTRAINT `FK_User_LC` FOREIGN KEY (`NomeU`) REFERENCES `usuario` (`nome`)
);


DELIMITER $$  
CREATE TRIGGER atulizal_post 
AFTER INSERT ON likesp 
FOR EACH ROW 
BEGIN 
    UPDATE post 
    SET quanti_likes = (SELECT COUNT(*) FROM likesp WHERE id = NEW.id) 
    WHERE id = (NEW.id); 
END $$  -- Termina o bloco corretamente

DELIMITER ;  -- Restaura o delimitador padrão

DELIMITER $$  
CREATE TRIGGER atulizal_postDeslike 
AFTER delete ON likesp 
FOR EACH ROW 
BEGIN 
    UPDATE post 
    SET quanti_likes = (SELECT COUNT(*) FROM likesp WHERE id = old.id) 
    WHERE id = (old.id); 
END $$  -- Termina o bloco corretamente

DELIMITER ;  -- Restaura o delimitador padrão


DELIMITER $$  
CREATE TRIGGER atulizal_postC 
AFTER INSERT ON likespC 
FOR EACH ROW 
BEGIN 
    UPDATE postCom 
    SET quanti_likes = (SELECT COUNT(*) FROM likespc WHERE id = new.id) 
    WHERE id = (NEW.id); 
END $$  -- Termina o bloco corretamente

DELIMITER ;  -- Restaura o delimitador padrão

DELIMITER $$  
CREATE TRIGGER atulizal_postDeslikeC
AFTER delete ON likespC 
FOR EACH ROW 
BEGIN 
    UPDATE postCom
    SET quanti_likes = (SELECT COUNT(*) FROM likespc WHERE id = old.id) 
    WHERE id = (old.id); 
END $$  -- Termina o bloco corretamente

DELIMITER ;  -- Restaura o delimitador padrão