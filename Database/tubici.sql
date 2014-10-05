# --------------------------------------------------------
# Host:                         127.0.0.1
# Database:                     tubici
# Server version:               4.1.12a
# Server OS:                    Win32
# HeidiSQL version:             5.0.0.3031
# Date/time:                    2010-03-09 00:48:13
# --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
# Dumping database structure for tubici
CREATE DATABASE IF NOT EXISTS `tubici` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `tubici`;


# Dumping structure for table tubici.bic
CREATE TABLE IF NOT EXISTS `bic` (
  `cod_bic` int(4) NOT NULL auto_increment COMMENT 'El código de cada bicicleta',
  `cod_pos` int(4) default NULL COMMENT 'El código del poste donde esta aparcada la bicicleta',
  `cod_par` int(4) default NULL COMMENT 'El código del parking del poste donde esta aparcada la bicicleta',
  `l_act` varchar(25) NOT NULL default '' COMMENT 'Si la bicicleta esta activa o no',
  KEY `pk_bic` (`cod_bic`),
  KEY `fk_bic_par` (`cod_pos`,`cod_par`),
  CONSTRAINT `fk_bic_par` FOREIGN KEY (`cod_pos`) REFERENCES `pos` (`cod_pos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='bic(icletas) de la aplicación TuBici';

# Dumping data for table tubici.bic: 15 rows
/*!40000 ALTER TABLE `bic` DISABLE KEYS */;
INSERT INTO `bic` (`cod_bic`, `cod_pos`, `cod_par`, `l_act`) VALUES (18, 17, 3, 'S'), (20, NULL, NULL, 'S'), (21, 16, 7, 'S'), (24, NULL, NULL, 'S'), (26, NULL, NULL, 'S'), (27, 16, 2, 'S'), (28, 16, 3, 'S'), (29, 16, 4, 'S'), (30, NULL, NULL, 'S'), (31, 16, 9, 'S'), (32, NULL, NULL, 'S'), (33, 15, 3, 'S'), (34, 16, 8, 'S'), (35, 16, 5, 'S'), (36, 16, 6, 'S');
/*!40000 ALTER TABLE `bic` ENABLE KEYS */;


# Dumping structure for table tubici.par
CREATE TABLE IF NOT EXISTS `par` (
  `cod_pos` int(4) NOT NULL default '0' COMMENT 'El código de cada poste para aparcar bicicletas',
  `cod_par` int(4) NOT NULL default '0' COMMENT 'El código de la plaza dentro del poste.',
  `l_act` char(1) default NULL COMMENT 'Si el poste esta activo S o no N.',
  KEY `pk_par` (`cod_pos`,`cod_par`),
  CONSTRAINT `FK_par_pos` FOREIGN KEY (`cod_pos`) REFERENCES `pos` (`cod_pos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='par(kings) de cada poste de la aplicación TuBici';

# Dumping data for table tubici.par: 3 rows
/*!40000 ALTER TABLE `par` DISABLE KEYS */;
INSERT INTO `par` (`cod_pos`, `cod_par`, `l_act`) VALUES (3, 1, 'S'), (3, 2, 'S'), (3, 3, 'S');
/*!40000 ALTER TABLE `par` ENABLE KEYS */;


# Dumping structure for table tubici.pos
CREATE TABLE IF NOT EXISTS `pos` (
  `cod_pos` int(4) NOT NULL auto_increment COMMENT 'El código de cada poste para aparcar bicicletas',
  `dir_pos` varchar(50) NOT NULL default '' COMMENT 'Dir(ección) donde se encuentra el poste.',
  `num_par` int(11) default '1' COMMENT 'Num(ero) de par(king) del poste.',
  `l_act` char(1) default NULL COMMENT 'Si el poste esta activo S o no N.',
  KEY `pk_pos` (`cod_pos`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='pos(tes) de la aplicación TuBici';

# Dumping data for table tubici.pos: 5 rows
/*!40000 ALTER TABLE `pos` DISABLE KEYS */;
INSERT INTO `pos` (`cod_pos`, `dir_pos`, `num_par`, `l_act`) VALUES (3, 'San Jose', 10, 'S'), (15, 'Movera', 10, 'S'), (16, 'Arrabal', 10, 'S'), (17, 'Las Fuentes', 8, 'S'), (18, 'Delicias', 8, 'S');
/*!40000 ALTER TABLE `pos` ENABLE KEYS */;


# Dumping structure for table tubici.usu
CREATE TABLE IF NOT EXISTS `usu` (
  `cod_usu` int(4) NOT NULL auto_increment COMMENT 'El código de cada usuario',
  `nom_usu` varchar(25) NOT NULL default '' COMMENT 'El nombre de cada usuario',
  `fec_ini` date default NULL COMMENT 'La fecha de alta de cada usuario',
  `l_act` char(1) default NULL COMMENT 'Si el usuario esta activo S o no N',
  `cod_bic` int(4) default NULL COMMENT 'El código de la bici que alquilo el usuario',
  PRIMARY KEY  (`cod_usu`),
  KEY `fk_cod_usu_bic` (`cod_bic`),
  CONSTRAINT `fk_cod_usu_bic` FOREIGN KEY (`cod_bic`) REFERENCES `bic` (`cod_bic`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='usu(arios) de la aplicación TuBici';

# Dumping data for table tubici.usu: 10 rows
/*!40000 ALTER TABLE `usu` DISABLE KEYS */;
INSERT INTO `usu` (`cod_usu`, `nom_usu`, `fec_ini`, `l_act`, `cod_bic`) VALUES (1, 'maria', '2010-02-28', 'S', NULL), (2, 'javier', '2010-02-28', 'S', 24), (3, 'pilar', '2009-01-04', 'S', 26), (4, 'titin', '2010-03-03', 'S', 32), (5, 'alba', '2010-03-06', 'N', NULL), (41, 'Sofia', '2010-03-06', 'S', 30), (42, 'alfredo', '2010-03-09', 'S', NULL), (43, 'paco', '2010-03-09', 'S', NULL), (44, 'Fernando', '2010-03-09', 'S', 20), (45, 'Ricardo', '2010-03-09', 'S', NULL);
/*!40000 ALTER TABLE `usu` ENABLE KEYS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
