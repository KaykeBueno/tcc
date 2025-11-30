-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema igym
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema igym
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `igym` DEFAULT CHARACTER SET utf8 ;
USE `igym` ;

-- -----------------------------------------------------
-- Table `igym`.`admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `igym`.`admin` (
  `idAdmin` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`idAdmin`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `igym`.`empresa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `igym`.`empresa` (
  `id_empresa` INT(11) NOT NULL AUTO_INCREMENT,
  `cnpj` VARCHAR(18) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `nomeFantasia` VARCHAR(100) NOT NULL,
  `telefone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `atividadeEconomica` VARCHAR(100) NOT NULL,
  `porteEmpresarial` VARCHAR(50) NOT NULL,
  `status` BIT(1) NOT NULL DEFAULT b'1',
  PRIMARY KEY (`id_empresa`),
  UNIQUE INDEX `cnpj_UNIQUE` (`cnpj` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `igym`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `igym`.`usuario` (
  `id_usuario` INT(11) NOT NULL AUTO_INCREMENT,
  `cpf` VARCHAR(14) NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  `telefone` VARCHAR(20) NOT NULL,
  `dataNascimento` DATE NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `sexo` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE INDEX `cpf_UNIQUE` (`cpf` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `igym`.`avaliações`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `igym`.`avaliações` (
  `idAvaliações` INT(11) NOT NULL AUTO_INCREMENT,
  `nota` INT(11) NOT NULL,
  `comentario` VARCHAR(255) NULL DEFAULT NULL,
  `dataAvaliacao` DATE NOT NULL,
  `Usuario_id_usuario` INT(11) NOT NULL,
  `Empresa_id_empresa` INT(11) NOT NULL,
  PRIMARY KEY (`idAvaliações`),
  INDEX `fk_Avaliações_Usuario_idx` (`Usuario_id_usuario` ASC),
  INDEX `fk_Avaliações_Empresa1_idx` (`Empresa_id_empresa` ASC),
  CONSTRAINT `fk_Avaliações_Empresa1`
    FOREIGN KEY (`Empresa_id_empresa`)
    REFERENCES `igym`.`empresa` (`id_empresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Avaliações_Usuario`
    FOREIGN KEY (`Usuario_id_usuario`)
    REFERENCES `igym`.`usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `igym`.`treino`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `igym`.`treino` (
  `idTreino` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`idTreino`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `igym`.`portifolio`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `igym`.`portifolio` (
  `idPortifolio` INT(11) NOT NULL AUTO_INCREMENT,
  `empresa_id_empresa` INT(11) NOT NULL,
  `treino_idTreino` INT(11) NOT NULL,
  `descricao` VARCHAR(45) NOT NULL,
  `metodologia` VARCHAR(45) NOT NULL,
  `dificuldade` VARCHAR(45) NOT NULL,
  INDEX `fk_empresa_has_treino_treino1_idx` (`treino_idTreino` ASC),
  INDEX `fk_empresa_has_treino_empresa1_idx` (`empresa_id_empresa` ASC),
  PRIMARY KEY (`idPortifolio`),
  CONSTRAINT `fk_empresa_has_treino_empresa1`
    FOREIGN KEY (`empresa_id_empresa`)
    REFERENCES `igym`.`empresa` (`id_empresa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_empresa_has_treino_treino1`
    FOREIGN KEY (`treino_idTreino`)
    REFERENCES `igym`.`treino` (`idTreino`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `igym`.`PlanoTreino`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `igym`.`PlanoTreino` (
  `idPlano` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id_usuario` INT(11) NOT NULL,
  `portifolio_idPortifolio` INT(11) NOT NULL,
  INDEX `fk_usuario_has_ofertaTreino_ofertaTreino1_idx` (`portifolio_idPortifolio` ASC),
  INDEX `fk_usuario_has_ofertaTreino_usuario1_idx` (`usuario_id_usuario` ASC),
  PRIMARY KEY (`idPlano`),
  CONSTRAINT `fk_usuario_has_ofertaTreino_usuario1`
    FOREIGN KEY (`usuario_id_usuario`)
    REFERENCES `igym`.`usuario` (`id_usuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_ofertaTreino_ofertaTreino1`
    FOREIGN KEY (`portifolio_idPortifolio`)
    REFERENCES `igym`.`portifolio` (`idPortifolio`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
