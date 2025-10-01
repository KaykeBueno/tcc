drop database tcc;
use tcc;
select * from empresa;
DELETE FROM Usuario WHERE id_usuario = 4;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema tcc
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `tcc` DEFAULT CHARACTER SET utf8 ;
USE `tcc` ;

-- -----------------------------------------------------
-- Table `tcc`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tcc`.`Usuario` (
  `id_usuario` INT NOT NULL AUTO_INCREMENT,
  `cpf` VARCHAR(14) NOT NULL,
  `nome` VARCHAR(100) NULL,
  `senha` VARCHAR(255) NULL,
  `telefone` VARCHAR(20) NULL,
  `dataNascimento` DATE NULL,
  `email` VARCHAR(100) NULL,
  `sexo` VARCHAR(20) NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE INDEX `cpf_UNIQUE` (`cpf` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tcc`.`Empresa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tcc`.`Empresa` (
  `id_empresa` INT NOT NULL AUTO_INCREMENT,
  `cnpj` VARCHAR(18) NOT NULL,
  `senha` VARCHAR(255) NULL,
  `nomeFantasia` VARCHAR(100) NULL,
  `telefone` VARCHAR(20) NULL,
  `email` VARCHAR(100) NULL,
  `atividadeEconomica` VARCHAR(100) NULL,
  `porteEmpresarial` VARCHAR(50) NULL,
  `status` BIT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_empresa`),
  UNIQUE INDEX `cnpj_UNIQUE` (`cnpj` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tcc`.`Avaliações`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tcc`.`Avaliações` (
  `idAvaliações` INT NOT NULL AUTO_INCREMENT,
  `nota` INT NULL,
  `comentario` VARCHAR(255) NULL,
  `dataAvaliacao` DATE NULL,
  -- ALTERADO: O tipo de dado agora é INT para corresponder a 'id_usuario'.
  `Usuario_id_usuario` INT NOT NULL,
  -- ALTERADO: O tipo de dado agora é INT para corresponder a 'id_empresa'.
  `Empresa_id_empresa` INT NOT NULL,
  PRIMARY KEY (`idAvaliações`),
  INDEX `fk_Avaliações_Usuario_idx` (`Usuario_id_usuario` ASC),
  INDEX `fk_Avaliações_Empresa1_idx` (`Empresa_id_empresa` ASC),
  CONSTRAINT `fk_Avaliações_Usuario`
    FOREIGN KEY (`Usuario_id_usuario`)
    -- ALTERADO: A referência agora aponta para a coluna correta 'id_usuario'.
    REFERENCES `tcc`.`Usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Avaliações_Empresa1`
    FOREIGN KEY (`Empresa_id_empresa`)
    -- ALTERADO: A referência agora aponta para a coluna correta 'id_empresa'.
    REFERENCES `tcc`.`Empresa` (`id_empresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tcc`.`logAcesso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tcc`.`logAcesso` (
  `idlogAcesso` INT NOT NULL AUTO_INCREMENT,
  `dataAcesso` DATETIME NULL,
  -- ALTERADO: O tipo de dado agora é INT.
  `Usuario_id_usuario` INT NULL,
  -- ALTERADO: O tipo de dado agora é INT.
  `Empresa_id_empresa` INT NULL,
  PRIMARY KEY (`idlogAcesso`),
  INDEX `fk_logAcesso_Usuario1_idx` (`Usuario_id_usuario` ASC),
  INDEX `fk_logAcesso_Empresa1_idx` (`Empresa_id_empresa` ASC),
  CONSTRAINT `fk_logAcesso_Usuario1`
    FOREIGN KEY (`Usuario_id_usuario`)
    REFERENCES `tcc`.`Usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_logAcesso_Empresa1`
    FOREIGN KEY (`Empresa_id_empresa`)
    REFERENCES `tcc`.`Empresa` (`id_empresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tcc`.`Treino`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tcc`.`Treino` (
  `idTreino` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NULL,
  `descricao` TEXT NULL,
  `preco` DECIMAL(10,2) NULL,
  -- ALTERADO: O nome e o tipo da coluna foram corrigidos.
  `Empresa_id_empresa` INT NOT NULL,
  PRIMARY KEY (`idTreino`),
  INDEX `fk_Treino_Empresa1_idx` (`Empresa_id_empresa` ASC),
  CONSTRAINT `fk_Treino_Empresa1`
    FOREIGN KEY (`Empresa_id_empresa`)
    REFERENCES `tcc`.`Empresa` (`id_empresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tcc`.`Treino_has_Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tcc`.`Treino_has_Usuario` (
  `Treino_idTreino` INT NOT NULL,
  -- ALTERADO: O nome e o tipo da coluna foram corrigidos.
  `Usuario_id_usuario` INT NOT NULL,
  PRIMARY KEY (`Treino_idTreino`, `Usuario_id_usuario`),
  INDEX `fk_Treino_has_Usuario_Usuario1_idx` (`Usuario_id_usuario` ASC),
  INDEX `fk_Treino_has_Usuario_Treino1_idx` (`Treino_idTreino` ASC),
  CONSTRAINT `fk_Treino_has_Usuario_Treino1`
    FOREIGN KEY (`Treino_idTreino`)
    REFERENCES `tcc`.`Treino` (`idTreino`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Treino_has_Usuario_Usuario1`
    FOREIGN KEY (`Usuario_id_usuario`)
    REFERENCES `tcc`.`Usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `tcc`.`Admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tcc`.`Admin` (
  `idAdmin` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`idAdmin`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;