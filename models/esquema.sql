-- MySQL dump 10.13  Distrib 5.1.58, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: tpmqlabs
-- ------------------------------------------------------
-- Server version	5.1.58-1ubuntu1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categoria_evento`
--

DROP TABLE IF EXISTS `categoria_evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria_evento` (
  `cat_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `cat_nombre` varchar(200) DEFAULT NULL,
  `cat_fecha_registro_sistema` datetime DEFAULT NULL,
  `cat_usu_crea` int(11) DEFAULT NULL,
  `cat_usu_actualiza` int(11) DEFAULT NULL,
  `cat_fecha_actualizacion` datetime DEFAULT NULL,
  `cat_eliminado` smallint(6) DEFAULT NULL,
  `cat_causa_eliminacion` varchar(250) DEFAULT NULL,
  `cat_causa_actualizacion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`cat_codigo`),
  KEY `FK_reference_31` (`cat_usu_crea`),
  KEY `FK_reference_32` (`cat_usu_actualiza`),
  CONSTRAINT `FK_reference_31` FOREIGN KEY (`cat_usu_crea`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_32` FOREIGN KEY (`cat_usu_actualiza`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `computador`
--

DROP TABLE IF EXISTS `computador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `computador` (
  `com_certificado` varchar(40) NOT NULL,
  `com_nombre` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`com_certificado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `consumible_maquina`
--

DROP TABLE IF EXISTS `consumible_maquina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `consumible_maquina` (
  `com_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `com_maq_codigo` int(11) DEFAULT NULL,
  `com_fecha_cambio` datetime DEFAULT NULL,
  `com_item` varchar(200) DEFAULT NULL,
  `com_numero_parte` varchar(200) DEFAULT NULL,
  `com_periodicidad` int(11) DEFAULT NULL,
  `com_proximo_mantenimiento` datetime DEFAULT NULL,
  `com_fecha_registro_sistema` datetime DEFAULT NULL,
  PRIMARY KEY (`com_codigo`),
  KEY `FK_reference_17` (`com_maq_codigo`),
  CONSTRAINT `FK_reference_17` FOREIGN KEY (`com_maq_codigo`) REFERENCES `maquina` (`maq_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empleado`
--

DROP TABLE IF EXISTS `empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleado` (
  `empl_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `empl_emp_codigo` int(11) DEFAULT NULL,
  `empl_tid_codigo` int(11) DEFAULT NULL,
  `empl_usu_codigo` int(11) DEFAULT NULL,
  `empl_nombres` varchar(200) DEFAULT NULL,
  `empl_apellidos` varchar(200) DEFAULT NULL,
  `empl_numero_identificacion` int(11) DEFAULT NULL,
  `empl_url_foto` varchar(200) DEFAULT NULL,
  `empl_fecha_registro_sistema` datetime DEFAULT NULL,
  `empl_usu_crea` int(11) DEFAULT NULL,
  `empl_usu_actualiza` int(11) DEFAULT NULL,
  `empl_fecha_actualizacion` datetime DEFAULT NULL,
  `empl_eliminado` smallint(6) DEFAULT NULL,
  `empl_causa_eliminacion` varchar(250) DEFAULT NULL,
  `empl_causa_actualizacion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`empl_codigo`),
  KEY `FK_reference_18` (`empl_emp_codigo`),
  KEY `FK_reference_19` (`empl_tid_codigo`),
  KEY `FK_reference_20` (`empl_usu_codigo`),
  KEY `FK_reference_35` (`empl_usu_crea`),
  KEY `FK_reference_36` (`empl_usu_actualiza`),
  CONSTRAINT `FK_reference_18` FOREIGN KEY (`empl_emp_codigo`) REFERENCES `empresa` (`emp_codigo`),
  CONSTRAINT `FK_reference_19` FOREIGN KEY (`empl_tid_codigo`) REFERENCES `tipo_identificacion` (`tid_codigo`),
  CONSTRAINT `FK_reference_20` FOREIGN KEY (`empl_usu_codigo`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_35` FOREIGN KEY (`empl_usu_crea`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_36` FOREIGN KEY (`empl_usu_actualiza`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `empresa`
--

DROP TABLE IF EXISTS `empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empresa` (
  `emp_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `emp_nombre` varchar(200) DEFAULT NULL,
  `emp_logo_url` varchar(200) DEFAULT NULL,
  `emp_nit` varchar(200) DEFAULT NULL,
  `emp_fecha_limite_licencia` date DEFAULT NULL,
  `emp_fecha_inicio_licencia` date DEFAULT NULL,
  `emp_inyect_estandar_promedio` int(11) DEFAULT NULL,
  `emp_tiempo_alerta_consumible` int(11) DEFAULT NULL,
  `emp_fecha_registro_sistema` datetime DEFAULT NULL,
  `emp_usu_crea` int(11) DEFAULT NULL,
  `emp_usu_actualiza` int(11) DEFAULT NULL,
  `emp_fecha_actualizacion` datetime DEFAULT NULL,
  `emp_eliminado` smallint(6) DEFAULT NULL,
  `emp_causa_eliminacion` varchar(250) DEFAULT NULL,
  `emp_causa_actualizacion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`emp_codigo`),
  KEY `FK_reference_37` (`emp_usu_crea`),
  KEY `FK_reference_38` (`emp_usu_actualiza`),
  CONSTRAINT `FK_reference_37` FOREIGN KEY (`emp_usu_crea`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_38` FOREIGN KEY (`emp_usu_actualiza`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estado`
--

DROP TABLE IF EXISTS `estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estado` (
  `est_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `est_nombre` varchar(200) DEFAULT NULL,
  `est_fecha_registro_sistema` datetime DEFAULT NULL,
  `est_usu_crea` int(11) DEFAULT NULL,
  `est_usu_actualiza` int(11) DEFAULT NULL,
  `est_fecha_actualizacion` datetime DEFAULT NULL,
  `est_eliminado` smallint(6) DEFAULT NULL,
  `est_causa_eliminacion` varchar(250) DEFAULT NULL,
  `est_causa_actualizacion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`est_codigo`),
  KEY `FK_reference_41` (`est_usu_crea`),
  KEY `FK_reference_42` (`est_usu_actualiza`),
  CONSTRAINT `FK_reference_41` FOREIGN KEY (`est_usu_crea`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_42` FOREIGN KEY (`est_usu_actualiza`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `evento`
--

DROP TABLE IF EXISTS `evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evento` (
  `eve_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `eve_nombre` varchar(200) NOT NULL,
  `eve_fecha_registro_sistema` datetime DEFAULT NULL,
  `eve_usu_crea` int(11) DEFAULT NULL,
  `eve_usu_actualiza` int(11) DEFAULT NULL,
  `eve_fecha_actualizacion` datetime DEFAULT NULL,
  `eve_eliminado` smallint(6) DEFAULT NULL,
  `eve_causa_eliminacion` varchar(250) DEFAULT NULL,
  `eve_causa_actualizacion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`eve_codigo`),
  KEY `FK_reference_29` (`eve_usu_crea`),
  KEY `FK_reference_30` (`eve_usu_actualiza`),
  CONSTRAINT `FK_reference_29` FOREIGN KEY (`eve_usu_crea`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_30` FOREIGN KEY (`eve_usu_actualiza`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `evento_en_registro`
--

DROP TABLE IF EXISTS `evento_en_registro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evento_en_registro` (
  `evrg_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `evrg_rum_codigo` int(11) DEFAULT NULL,
  `evrg_eve_codigo` int(11) DEFAULT NULL,
  `evrg_observaciones` varchar(200) DEFAULT NULL,
  `evrg_hora_ocurrio` time DEFAULT NULL,
  `evrg_hora_registro` time DEFAULT NULL,
  `evrg_fecha_registro_sistema` datetime DEFAULT NULL,
  `evrg_usu_crea` int(11) DEFAULT NULL,
  `evrg_usu_actualiza` int(11) DEFAULT NULL,
  `evrg_fecha_actualizacion` datetime DEFAULT NULL,
  `evrg_eliminado` smallint(6) DEFAULT NULL,
  `evrg_causa_eliminacion` varchar(250) DEFAULT NULL,
  `evrg_causa_actualizacion` varchar(250) DEFAULT NULL,
  `evrg_duracion` decimal(10,4) DEFAULT NULL,
  PRIMARY KEY (`evrg_codigo`),
  KEY `FK_reference_49` (`evrg_usu_crea`),
  KEY `FK_reference_50` (`evrg_usu_actualiza`),
  KEY `FK_ref_eventoenregistro_registrouso` (`evrg_rum_codigo`),
  KEY `FK_ref_eventoenregistro_evento` (`evrg_eve_codigo`),
  CONSTRAINT `FK_reference_49` FOREIGN KEY (`evrg_usu_crea`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_50` FOREIGN KEY (`evrg_usu_actualiza`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_ref_eventoenregistro_evento` FOREIGN KEY (`evrg_eve_codigo`) REFERENCES `evento` (`eve_codigo`),
  CONSTRAINT `FK_ref_eventoenregistro_registrouso` FOREIGN KEY (`evrg_rum_codigo`) REFERENCES `registro_uso_maquina` (`rum_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `evento_por_categoria`
--

DROP TABLE IF EXISTS `evento_por_categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evento_por_categoria` (
  `evca_cat_codigo` int(11) NOT NULL,
  `evca_eve_codigo` int(11) NOT NULL,
  `evca_usu_crea` int(11) DEFAULT NULL,
  `evca_fecha_registro_sistema` datetime DEFAULT NULL,
  PRIMARY KEY (`evca_cat_codigo`,`evca_eve_codigo`),
  KEY `FK_reference_51` (`evca_usu_crea`),
  KEY `FK_reference_24` (`evca_eve_codigo`),
  CONSTRAINT `FK_reference_23` FOREIGN KEY (`evca_cat_codigo`) REFERENCES `categoria_evento` (`cat_codigo`),
  CONSTRAINT `FK_reference_24` FOREIGN KEY (`evca_eve_codigo`) REFERENCES `evento` (`eve_codigo`),
  CONSTRAINT `FK_reference_51` FOREIGN KEY (`evca_usu_crea`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `indicador`
--

DROP TABLE IF EXISTS `indicador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `indicador` (
  `ind_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `ind_sigla` varchar(30) DEFAULT NULL,
  `ind_nombre` varchar(200) DEFAULT NULL,
  `ind_fecha_registro_sistema` datetime DEFAULT NULL,
  `ind_unidad` varchar(20) DEFAULT NULL,
  `ind_usu_crea` int(11) DEFAULT NULL,
  `ind_usu_actualiza` int(11) DEFAULT NULL,
  `ind_fecha_actualizacion` datetime DEFAULT NULL,
  `ind_eliminado` smallint(6) DEFAULT NULL,
  `ind_causa_eliminacion` varchar(250) DEFAULT NULL,
  `ind_causa_actualizacion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`ind_codigo`),
  KEY `FK_reference_39` (`ind_usu_crea`),
  KEY `FK_reference_40` (`ind_usu_actualiza`),
  CONSTRAINT `FK_reference_39` FOREIGN KEY (`ind_usu_crea`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_40` FOREIGN KEY (`ind_usu_actualiza`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `maquina`
--

DROP TABLE IF EXISTS `maquina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `maquina` (
  `maq_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `maq_est_codigo` int(11) DEFAULT NULL,
  `maq_com_certificado` varchar(40) DEFAULT NULL,
  `maq_nombre` varchar(200) DEFAULT NULL,
  `maq_marca` varchar(200) DEFAULT NULL,
  `maq_modelo` varchar(200) DEFAULT NULL,
  `maq_fecha_adquisicion` date DEFAULT NULL,
  `maq_foto_url` varchar(200) DEFAULT NULL,
  `maq_tiempo_inyeccion` decimal(8,4) DEFAULT NULL,
  `maq_fecha_registro_sistema` datetime DEFAULT NULL,
  `maq_codigo_inventario` varchar(20) DEFAULT NULL,
  `maq_usu_crea` int(11) DEFAULT NULL,
  `maq_usu_actualiza` int(11) DEFAULT NULL,
  `maq_fecha_actualizacion` datetime DEFAULT NULL,
  `maq_eliminado` smallint(6) DEFAULT NULL,
  `maq_causa_eliminacion` varchar(250) DEFAULT NULL,
  `maq_causa_actualizacion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`maq_codigo`),
  KEY `FK_reference_21` (`maq_com_certificado`),
  KEY `FK_reference_43` (`maq_usu_crea`),
  KEY `FK_reference_44` (`maq_usu_actualiza`),
  KEY `FK_reference_10` (`maq_est_codigo`),
  CONSTRAINT `FK_reference_10` FOREIGN KEY (`maq_est_codigo`) REFERENCES `estado` (`est_codigo`),
  CONSTRAINT `FK_reference_21` FOREIGN KEY (`maq_com_certificado`) REFERENCES `computador` (`com_certificado`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `FK_reference_43` FOREIGN KEY (`maq_usu_crea`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_44` FOREIGN KEY (`maq_usu_actualiza`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `meta_anual_x_indicador`
--

DROP TABLE IF EXISTS `meta_anual_x_indicador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meta_anual_x_indicador` (
  `mea_ind_codigo` int(11) NOT NULL,
  `mea_emp_codigo` int(11) NOT NULL,
  `mea_anio` int(11) NOT NULL,
  `mea_valor` decimal(8,2) DEFAULT NULL,
  `mea_fecha_registro_sistema` datetime DEFAULT NULL,
  `mea_usu_crea` int(11) DEFAULT NULL,
  `mea_usu_actualiza` int(11) DEFAULT NULL,
  `mea_fecha_actualizacion` datetime DEFAULT NULL,
  `mea_eliminado` smallint(6) DEFAULT NULL,
  `mea_causa_eliminacion` varchar(250) DEFAULT NULL,
  `mea_causa_actualizacion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`mea_ind_codigo`,`mea_emp_codigo`,`mea_anio`),
  KEY `FK_reference_45` (`mea_usu_crea`),
  KEY `FK_reference_46` (`mea_usu_actualiza`),
  KEY `FK_reference_16` (`mea_emp_codigo`),
  CONSTRAINT `FK_reference_14` FOREIGN KEY (`mea_ind_codigo`) REFERENCES `indicador` (`ind_codigo`),
  CONSTRAINT `FK_reference_16` FOREIGN KEY (`mea_emp_codigo`) REFERENCES `empresa` (`emp_codigo`),
  CONSTRAINT `FK_reference_45` FOREIGN KEY (`mea_usu_crea`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_46` FOREIGN KEY (`mea_usu_actualiza`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `metodo`
--

DROP TABLE IF EXISTS `metodo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metodo` (
  `met_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `met_nombre` varchar(200) DEFAULT NULL,
  `met_tiempo_alistamiento` decimal(12,4) DEFAULT NULL,
  `met_tiempo_acondicionamiento` decimal(12,4) DEFAULT NULL,
  `met_tiempo_estabilizacion` decimal(12,4) DEFAULT NULL,
  `met_tiempo_estandar` decimal(12,4) DEFAULT NULL,
  `met_tiempo_corrida_sistema` decimal(12,4) DEFAULT NULL,
  `met_tiempo_corrida_curvas` decimal(12,4) DEFAULT NULL,
  `met_num_inyeccion_estandar_1` int(11) DEFAULT NULL,
  `met_num_inyeccion_estandar_2` int(11) DEFAULT NULL,
  `met_num_inyeccion_estandar_3` int(11) DEFAULT NULL,
  `met_num_inyeccion_estandar_4` int(11) DEFAULT NULL,
  `met_num_inyeccion_estandar_5` int(11) DEFAULT NULL,
  `met_num_inyeccion_estandar_6` int(11) DEFAULT NULL,
  `met_num_inyeccion_estandar_7` int(11) DEFAULT NULL,
  `met_num_inyeccion_estandar_8` int(11) DEFAULT NULL,
  `met_fecha_registro_sistema` datetime DEFAULT NULL,
  `met_num_inyec_x_mu_producto` int(11) DEFAULT NULL,
  `met_num_inyec_x_mu_estabilidad` int(11) DEFAULT NULL,
  `met_num_inyec_x_mu_materia_pri` int(11) DEFAULT NULL,
  `met_num_inyec_x_mu_pureza` int(11) DEFAULT NULL,
  `met_num_inyec_x_mu_disolucion` int(11) DEFAULT NULL,
  `met_num_inyec_x_mu_uniformidad` int(11) DEFAULT NULL,
  `met_numero_inyeccion_estandar` int(11) DEFAULT NULL,
  `met_usu_crea` int(11) DEFAULT NULL,
  `met_usu_actualiza` int(11) DEFAULT NULL,
  `met_fecha_actualizacion` datetime DEFAULT NULL,
  `met_eliminado` smallint(6) DEFAULT NULL,
  `met_causa_eliminacion` varchar(250) DEFAULT NULL,
  `met_causa_actualizacion` varchar(250) DEFAULT NULL,
  `met_tc_producto_terminado` decimal(12,4) DEFAULT NULL,
  `met_tc_estabilidad` decimal(12,4) DEFAULT NULL,
  `met_tc_materia_prima` decimal(12,4) DEFAULT NULL,
  `met_tc_pureza` decimal(12,4) DEFAULT NULL,
  `met_tc_disolucion` decimal(12,4) DEFAULT NULL,
  `met_tc_uniformidad` decimal(12,4) DEFAULT NULL,
  PRIMARY KEY (`met_codigo`),
  KEY `FK_reference_27` (`met_usu_crea`),
  KEY `FK_reference_28` (`met_usu_actualiza`),
  CONSTRAINT `FK_reference_27` FOREIGN KEY (`met_usu_crea`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_28` FOREIGN KEY (`met_usu_actualiza`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil` (
  `per_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `per_nombre` varchar(30) DEFAULT NULL,
  `per_fecha_registro_sistema` datetime DEFAULT NULL,
  `per_fecha_actualizacion` datetime DEFAULT NULL,
  `per_eliminado` smallint(6) DEFAULT NULL,
  `per_causa_eliminacion` varchar(250) DEFAULT NULL,
  `per_causa_actualizacion` varchar(250) DEFAULT NULL,
  `per_usu_crea` varchar(20) DEFAULT NULL,
  `per_usu_actualiza` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`per_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registro_modificacion`
--

DROP TABLE IF EXISTS `registro_modificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registro_modificacion` (
  `rem_id` int(11) NOT NULL AUTO_INCREMENT,
  `rem_rum_codigo` int(11) DEFAULT NULL,
  `rem_usu_codigo` int(11) DEFAULT NULL,
  `rem_fecha_hora` datetime DEFAULT NULL,
  `rem_nombre_campo` varchar(255) DEFAULT NULL,
  `rem_valor_antiguo` varchar(255) DEFAULT NULL,
  `rem_valor_nuevo` varchar(255) DEFAULT NULL,
  `rem_causa` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`rem_id`),
  KEY `FK_reference_25` (`rem_rum_codigo`),
  KEY `FK_reference_26` (`rem_usu_codigo`),
  CONSTRAINT `FK_reference_25` FOREIGN KEY (`rem_rum_codigo`) REFERENCES `registro_uso_maquina` (`rum_codigo`),
  CONSTRAINT `FK_reference_26` FOREIGN KEY (`rem_usu_codigo`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `registro_uso_maquina`
--

DROP TABLE IF EXISTS `registro_uso_maquina`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `registro_uso_maquina` (
  `rum_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `rum_maq_codigo` int(11) DEFAULT NULL,
  `rum_met_codigo` int(11) DEFAULT NULL,
  `rum_usu_codigo` int(11) DEFAULT NULL,
  `rum_usu_codigo_elimino` int(11) DEFAULT NULL,
  `rum_hora_inicio_trabajo` time DEFAULT NULL,
  `rum_hora_fin_trabajo` time DEFAULT NULL,
  `rum_tiempo_entre_modelo` time DEFAULT NULL,
  `rum_tiempo_cambio_modelo` decimal(12,4) DEFAULT NULL,
  `rum_tiempo_corrida_sistema` decimal(12,4) DEFAULT NULL,
  `rum_tiempo_corrida_curvas` decimal(12,4) DEFAULT NULL,
  `rum_tiempo_corrida_sistema_est` decimal(12,4) DEFAULT NULL,
  `rum_tiempo_corrida_curvas_esta` decimal(12,4) DEFAULT NULL,
  `rum_num_inyeccion_estandar_per` decimal(12,4) DEFAULT NULL,
  `rum_numero_inyeccion_estandar` decimal(12,4) DEFAULT NULL,
  `rum_tiempo_preparacion` decimal(12,4) DEFAULT NULL,
  `rum_tiempo_duracion_ciclo` time DEFAULT NULL,
  `rum_numero_corridas` int(11) DEFAULT NULL,
  `rum_numero_inyeccion_estandar1` decimal(12,4) DEFAULT NULL,
  `rum_numero_inyeccion_estandar2` decimal(12,4) DEFAULT NULL,
  `rum_numero_inyeccion_estandar3` decimal(12,4) DEFAULT NULL,
  `rum_numero_inyeccion_estandar4` decimal(12,4) DEFAULT NULL,
  `rum_numero_inyeccion_estandar5` decimal(12,4) DEFAULT NULL,
  `rum_numero_inyeccion_estandar6` decimal(12,4) DEFAULT NULL,
  `rum_numero_inyeccion_estandar7` decimal(12,4) DEFAULT NULL,
  `rum_numero_inyeccion_estandar8` decimal(12,4) DEFAULT NULL,
  `rum_num_inyeccion_estandar1_pe` decimal(12,4) DEFAULT NULL,
  `rum_num_inyeccion_estandar2_pe` decimal(12,4) DEFAULT NULL,
  `rum_num_inyeccion_estandar3_pe` decimal(12,4) DEFAULT NULL,
  `rum_num_inyeccion_estandar4_pe` decimal(12,4) DEFAULT NULL,
  `rum_num_inyeccion_estandar5_pe` decimal(12,4) DEFAULT NULL,
  `rum_num_inyeccion_estandar6_pe` decimal(12,4) DEFAULT NULL,
  `rum_num_inyeccion_estandar7_pe` decimal(12,4) DEFAULT NULL,
  `rum_num_inyeccion_estandar8_pe` decimal(12,4) DEFAULT NULL,
  `rum_num_muestras_producto` decimal(12,4) DEFAULT NULL,
  `rum_num_muestras_estabilidad` decimal(12,4) DEFAULT NULL,
  `rum_num_muestras_materia_prima` decimal(12,4) DEFAULT NULL,
  `rum_num_muestras_pureza` decimal(12,4) DEFAULT NULL,
  `rum_num_muestras_disolucion` decimal(12,4) DEFAULT NULL,
  `rum_num_muestras_uniformidad` decimal(12,4) DEFAULT NULL,
  `rum_num_mu_producto_perdida` decimal(12,4) DEFAULT NULL,
  `rum_num_mu_estabilidad_perdida` decimal(12,4) DEFAULT NULL,
  `rum_num_mu_materia_prima_perdi` decimal(12,4) DEFAULT NULL,
  `rum_num_muestras_pureza_perdid` decimal(12,4) DEFAULT NULL,
  `rum_num_muestras_disolucion_pe` decimal(12,4) DEFAULT NULL,
  `rum_num_muestras_uniformidad_p` decimal(12,4) DEFAULT NULL,
  `rum_num_inyec_x_muestra_estabi` decimal(12,4) DEFAULT NULL,
  `rum_num_inyec_x_muestra_produc` decimal(12,4) DEFAULT NULL,
  `rum_num_inyec_x_muestra_materi` decimal(12,4) DEFAULT NULL,
  `rum_num_inyec_x_muestra_pureza` decimal(12,4) DEFAULT NULL,
  `rum_num_inyec_x_muestra_disolu` decimal(12,4) DEFAULT NULL,
  `rum_num_inyec_x_muestra_unifor` decimal(12,4) DEFAULT NULL,
  `rum_num_inyec_x_mu_estabi_perd` decimal(12,4) DEFAULT NULL,
  `rum_num_inyec_x_mu_produc_perd` decimal(12,4) DEFAULT NULL,
  `rum_num_inyec_x_mu_materi_perd` decimal(12,4) DEFAULT NULL,
  `rum_num_inyec_x_mu_pureza_perd` decimal(12,4) DEFAULT NULL,
  `rum_num_inyec_x_mu_disolu_perd` decimal(12,4) DEFAULT NULL,
  `rum_num_inyec_x_mu_unifor_perd` decimal(12,4) DEFAULT NULL,
  `rum_fallas` decimal(12,4) DEFAULT NULL,
  `rum_observaciones` varchar(300) DEFAULT NULL,
  `rum_fecha` date DEFAULT NULL,
  `rum_fecha_hora_reg_sistema` datetime DEFAULT NULL,
  `rum_causa_eliminacion` varchar(300) DEFAULT NULL,
  `rum_fecha_hora_elim_sistema` datetime DEFAULT NULL,
  `rum_eliminado` tinyint(1) DEFAULT NULL,
  `rum_tc_producto_terminado` decimal(12,4) DEFAULT NULL,
  `rum_tc_estabilidad` decimal(12,4) DEFAULT NULL,
  `rum_tc_materia_prima` decimal(12,4) DEFAULT NULL,
  `rum_tc_pureza` decimal(12,4) DEFAULT NULL,
  `rum_tc_disolucion` decimal(12,4) DEFAULT NULL,
  `rum_tc_uniformidad` decimal(12,4) DEFAULT NULL,
  `rum_tc_producto_terminado_esta` decimal(12,4) DEFAULT NULL,
  `rum_tc_estabilidad_estandar` decimal(12,4) DEFAULT NULL,
  `rum_tc_materia_prima_estandar` decimal(12,4) DEFAULT NULL,
  `rum_tc_pureza_estandar` decimal(12,4) DEFAULT NULL,
  `rum_tc_disolucion_estandar` decimal(12,4) DEFAULT NULL,
  `rum_tc_uniformidad_estandar` decimal(12,4) DEFAULT NULL,
  PRIMARY KEY (`rum_codigo`),
  KEY `FK_reference_22` (`rum_usu_codigo_elimino`),
  KEY `FK_reference_6` (`rum_maq_codigo`),
  KEY `FK_reference_7` (`rum_met_codigo`),
  KEY `FK_reference_8` (`rum_usu_codigo`),
  CONSTRAINT `FK_reference_22` FOREIGN KEY (`rum_usu_codigo_elimino`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_6` FOREIGN KEY (`rum_maq_codigo`) REFERENCES `maquina` (`maq_codigo`),
  CONSTRAINT `FK_reference_7` FOREIGN KEY (`rum_met_codigo`) REFERENCES `metodo` (`met_codigo`),
  CONSTRAINT `FK_reference_8` FOREIGN KEY (`rum_usu_codigo`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipo_identificacion`
--

DROP TABLE IF EXISTS `tipo_identificacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipo_identificacion` (
  `tid_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `tid_nombre` varchar(30) DEFAULT NULL,
  `tid_fecha_registro_sistema` datetime DEFAULT NULL,
  `tid_usu_crea` int(11) DEFAULT NULL,
  `tid_usu_actualiza` int(11) DEFAULT NULL,
  `tid_fecha_actualizacion` datetime DEFAULT NULL,
  `tid_eliminado` smallint(6) DEFAULT NULL,
  `tid_causa_eliminacion` varchar(250) DEFAULT NULL,
  `tid_causa_actualizacion` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`tid_codigo`),
  KEY `FK_reference_33` (`tid_usu_crea`),
  KEY `FK_reference_34` (`tid_usu_actualiza`),
  CONSTRAINT `FK_reference_33` FOREIGN KEY (`tid_usu_crea`) REFERENCES `usuario` (`usu_codigo`),
  CONSTRAINT `FK_reference_34` FOREIGN KEY (`tid_usu_actualiza`) REFERENCES `usuario` (`usu_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `usu_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `usu_per_codigo` int(11) DEFAULT NULL,
  `usu_login` varchar(200) DEFAULT NULL,
  `usu_password` varchar(200) DEFAULT NULL,
  `usu_habilitado` smallint(6) DEFAULT NULL,
  `usu_fecha_registro_sistema` datetime DEFAULT NULL,
  `usu_fecha_actualizacion` datetime DEFAULT NULL,
  `usu_causa_actualizacion` varchar(250) DEFAULT NULL,
  `usu_crea` varchar(20) DEFAULT NULL,
  `usu_actualiza` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`usu_codigo`),
  KEY `FK_reference_4` (`usu_per_codigo`),
  CONSTRAINT `FK_reference_4` FOREIGN KEY (`usu_per_codigo`) REFERENCES `perfil` (`per_codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-07-29 14:42:32
