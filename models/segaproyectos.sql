-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 25-02-2014 a las 23:16:19
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `segaproyectos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alarma`
--

CREATE TABLE IF NOT EXISTS `alarma` (
  `ala_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `ala_concepto` varchar(200) DEFAULT NULL,
  `ala_con_codigo` varchar(200) DEFAULT NULL,
  `ala_descripcion` varchar(500) DEFAULT NULL,
  `ala_enviado` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`ala_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Volcar la base de datos para la tabla `alarma`
--

INSERT INTO `alarma` (`ala_codigo`, `ala_concepto`, `ala_con_codigo`, `ala_descripcion`, `ala_enviado`) VALUES
(1, 'Entrega de Producto', '1', 'la fecha de entrega del producto "Entregable Mejorando" está vencida.  El producto debió ser entregado el 18 de febrero de 2014', 1),
(2, 'Entrega de Producto', '2', 'la fecha de entrega del producto "Entregable Medio Ambiente" está vencida.  El producto debió ser entregado el 7 de febrero de 2014', 1),
(4, 'Finalización de Proyecto', '2', 'la fecha de finalización del proyecto "Programa de Reciclaje y educación Ambiental" está vencida.  El proyecto debió ser finalizado el 18 de febrero de 2014', 1),
(12, 'Presupuesto de Proyecto', '3', 'se ha superado en $250.000 el valor del presupuesto del proyecto "Proyecto de educación ambiental"', 0),
(11, 'Finalización de Proyecto', '1', 'la fecha de finalización del proyecto "Mejorando nuestro Medio Ambiente" está vencida.  El proyecto debió ser finalizado el 22 de febrero de 2014', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciondetiempo`
--

CREATE TABLE IF NOT EXISTS `asignaciondetiempo` (
  `adt_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `adt_mes` varchar(100) DEFAULT NULL,
  `adt_ano` varchar(100) DEFAULT NULL,
  `adt_asignacion` double DEFAULT NULL,
  `adt_pers_codigo` int(11) DEFAULT NULL,
  `adt_pro_codigo` int(11) DEFAULT NULL,
  `adt_pers_reg_codigo` int(11) DEFAULT NULL,
  `adt_fecha_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`adt_codigo`),
  KEY `FK_reference_1` (`adt_pers_codigo`),
  KEY `FK_reference_2` (`adt_pro_codigo`),
  KEY `FK_reference_3` (`adt_pers_reg_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Volcar la base de datos para la tabla `asignaciondetiempo`
--

INSERT INTO `asignaciondetiempo` (`adt_codigo`, `adt_mes`, `adt_ano`, `adt_asignacion`, `adt_pers_codigo`, `adt_pro_codigo`, `adt_pers_reg_codigo`, `adt_fecha_registro`) VALUES
(1, '2', '2014', 10, 3, 1, 1, '2014-02-16 23:38:22'),
(2, '2', '2014', 23, 1, 1, 1, '2014-02-16 23:38:27'),
(3, '2', '2014', 25, 3, 2, 1, '2014-02-16 23:38:34'),
(4, '2', '2014', 45, 2, 2, 1, '2014-02-16 23:38:39'),
(5, '4', '2014', 1, 3, 3, 1, '2014-02-16 23:38:45'),
(6, '5', '2014', 32, 3, 3, 1, '2014-02-16 23:38:48'),
(7, '6', '2014', 23, 3, 3, 1, '2014-02-16 23:38:50'),
(8, '7', '2014', 11, 3, 3, 1, '2014-02-16 23:38:52'),
(9, '8', '2014', 4, 3, 3, 1, '2014-02-16 23:38:53'),
(10, '9', '2014', 7, 3, 3, 1, '2014-02-16 23:38:56'),
(11, '10', '2014', 12, 3, 3, 1, '2014-02-16 23:38:58'),
(12, '11', '2014', 22, 3, 3, 1, '2014-02-16 23:39:01'),
(13, '12', '2014', 11, 3, 3, 1, '2014-02-16 23:39:04'),
(14, '1', '2015', 34, 3, 3, 1, '2014-02-16 23:39:07'),
(15, '2', '2015', 52, 3, 3, 1, '2014-02-16 23:39:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `concepto`
--

CREATE TABLE IF NOT EXISTS `concepto` (
  `con_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `con_nombre` varchar(30) DEFAULT NULL,
  `con_fecha_registro` datetime DEFAULT NULL,
  `con_usu_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`con_codigo`),
  KEY `FK_reference_1` (`con_usu_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `concepto`
--

INSERT INTO `concepto` (`con_codigo`, `con_nombre`, `con_fecha_registro`, `con_usu_codigo`) VALUES
(1, 'Impuestos', '2014-02-14 11:41:31', 1),
(2, 'Papelería', '2014-02-14 11:41:31', 1),
(3, 'Depreciacione', '2014-02-14 11:41:31', 1),
(4, 'Arrendamiento', '2014-02-14 11:41:31', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptosingreso`
--

CREATE TABLE IF NOT EXISTS `conceptosingreso` (
  `csi_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `csi_valor` double DEFAULT NULL,
  `csi_fecha` date DEFAULT NULL,
  `csi_usu_codigo` int(11) DEFAULT NULL,
  `csi_con_codigo` int(11) DEFAULT NULL,
  `csi_ing_codigo` int(11) DEFAULT NULL,
  `csi_pro_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`csi_codigo`),
  KEY `FK_reference_1` (`csi_usu_codigo`),
  KEY `FK_reference_2` (`csi_con_codigo`),
  KEY `FK_reference_3` (`csi_ing_codigo`),
  KEY `FK_reference_4` (`csi_pro_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Volcar la base de datos para la tabla `conceptosingreso`
--

INSERT INTO `conceptosingreso` (`csi_codigo`, `csi_valor`, `csi_fecha`, `csi_usu_codigo`, `csi_con_codigo`, `csi_ing_codigo`, `csi_pro_codigo`) VALUES
(1, 2000000, '2014-02-16', 1, 1, 1, 1),
(2, 2000000, '2014-02-16', 1, 2, 1, 1),
(3, 1000000, '2014-02-16', 1, 3, 1, 1),
(4, 500000, '2014-02-16', 1, 4, 2, 1),
(5, 5000000, '2014-01-09', 1, 4, 3, 2),
(6, 5000000, '2014-01-09', 1, 2, 3, 2),
(7, 5000000, '2014-01-09', 1, 1, 3, 2),
(8, 2000000, '2014-01-26', 1, 1, 4, 2),
(9, 2000000, '2014-01-26', 1, 2, 4, 2),
(10, 2000000, '2014-03-01', 1, 3, 5, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE IF NOT EXISTS `documento` (
  `doc_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `doc_documento_url` varchar(300) DEFAULT NULL,
  `doc_fecha_registro` datetime DEFAULT NULL,
  `doc_eliminado` smallint(6) DEFAULT NULL,
  `doc_tipd_codigo` int(11) DEFAULT NULL,
  `doc_usu_codigo` int(11) DEFAULT NULL,
  `doc_pro_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`doc_codigo`),
  KEY `FK_reference_1` (`doc_tipd_codigo`),
  KEY `FK_reference_2` (`doc_usu_codigo`),
  KEY `FK_reference_3` (`doc_pro_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `documento`
--

INSERT INTO `documento` (`doc_codigo`, `doc_documento_url`, `doc_fecha_registro`, `doc_eliminado`, `doc_tipd_codigo`, `doc_usu_codigo`, `doc_pro_codigo`) VALUES
(1, 'documentos/1/Graficos.xlsx', '2014-02-15 20:46:29', 0, 1, 1, 1),
(2, 'documentos/2/InformeEjemplo.pdf', '2014-02-15 20:46:48', 0, 2, 1, 2),
(3, 'documentos/3/Graficos.xlsx', '2014-02-16 23:20:46', 0, 5, 1, 2),
(4, 'documentos/4/InfJoha.docx', '2014-02-16 23:21:00', 0, 3, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egreso`
--

CREATE TABLE IF NOT EXISTS `egreso` (
  `egr_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `egr_con_codigo` int(11) DEFAULT NULL,
  `egr_valor` double DEFAULT NULL,
  `egr_fecha` date DEFAULT NULL,
  `egr_fecha_registro` datetime DEFAULT NULL,
  `egr_eliminado` smallint(6) DEFAULT NULL,
  `egr_usu_codigo` int(11) DEFAULT NULL,
  `egr_pro_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`egr_codigo`),
  KEY `FK_reference_11` (`egr_usu_codigo`),
  KEY `FK_reference_12` (`egr_pro_codigo`),
  KEY `FK_reference_13` (`egr_con_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcar la base de datos para la tabla `egreso`
--

INSERT INTO `egreso` (`egr_codigo`, `egr_con_codigo`, `egr_valor`, `egr_fecha`, `egr_fecha_registro`, `egr_eliminado`, `egr_usu_codigo`, `egr_pro_codigo`) VALUES
(1, 1, 1000000, '2014-02-16', '2014-02-16 23:29:40', 0, 1, 1),
(2, 2, 200000, '2014-02-16', '2014-02-16 23:30:06', 0, 1, 1),
(3, 4, 300000, '2014-03-01', '2014-02-16 23:30:21', 0, 1, 1),
(4, 1, 1500000, '2014-02-16', '2014-02-16 23:30:33', 0, 1, 2),
(5, 2, 500000, '2014-01-10', '2014-02-16 23:30:45', 0, 1, 2),
(6, 2, 250000, '2014-03-01', '2014-02-16 23:30:58', 0, 1, 3),
(7, 3, 2000000, '2014-02-27', '2014-02-16 23:31:12', 0, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadoproducto`
--

CREATE TABLE IF NOT EXISTS `estadoproducto` (
  `est_prod_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `est_prod_nombre` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`est_prod_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `estadoproducto`
--

INSERT INTO `estadoproducto` (`est_prod_codigo`, `est_prod_nombre`) VALUES
(1, 'Pendiente'),
(2, 'Entregado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadoproyecto`
--

CREATE TABLE IF NOT EXISTS `estadoproyecto` (
  `est_pro_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `est_pro_nombre` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`est_pro_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `estadoproyecto`
--

INSERT INTO `estadoproyecto` (`est_pro_codigo`, `est_pro_nombre`) VALUES
(1, 'Activo'),
(2, 'Suspendido'),
(3, 'Terminado'),
(4, 'Reanudado'),
(5, 'Prorrogado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingreso`
--

CREATE TABLE IF NOT EXISTS `ingreso` (
  `ing_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `ing_concepto` varchar(500) DEFAULT NULL,
  `ing_valor` double DEFAULT NULL,
  `ing_fecha` date DEFAULT NULL,
  `ing_fecha_registro` datetime DEFAULT NULL,
  `ing_eliminado` smallint(6) DEFAULT NULL,
  `ing_usu_codigo` int(11) DEFAULT NULL,
  `ing_pro_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`ing_codigo`),
  KEY `FK_reference_11` (`ing_usu_codigo`),
  KEY `FK_reference_12` (`ing_pro_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcar la base de datos para la tabla `ingreso`
--

INSERT INTO `ingreso` (`ing_codigo`, `ing_concepto`, `ing_valor`, `ing_fecha`, `ing_fecha_registro`, `ing_eliminado`, `ing_usu_codigo`, `ing_pro_codigo`) VALUES
(1, 'Presupuesto inicial', 5000000, '2014-02-16', '2014-02-16 23:27:32', 0, 1, 1),
(2, 'Aportes Univalle', 500000, '2014-02-16', '2014-02-16 23:28:14', 0, 1, 1),
(3, 'Aportes inversionistas', 15000000, '2014-01-09', '2014-02-16 23:28:50', 0, 1, 2),
(4, 'Presupuesto inicial', 4000000, '2014-01-26', '2014-02-16 23:29:07', 0, 1, 2),
(5, 'Aportes MEN', 2000000, '2014-03-01', '2014-02-16 23:29:25', 0, 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organizacion`
--

CREATE TABLE IF NOT EXISTS `organizacion` (
  `org_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `org_nombre_completo` varchar(200) DEFAULT NULL,
  `org_nombre_corto` varchar(200) DEFAULT NULL,
  `org_nit` varchar(100) DEFAULT NULL,
  `org_direccion` varchar(200) DEFAULT NULL,
  `org_correo` varchar(200) DEFAULT NULL,
  `org_nombre_contacto` varchar(200) DEFAULT NULL,
  `org_telefono` varchar(100) DEFAULT NULL,
  `org_fecha_registro` datetime DEFAULT NULL,
  `org_eliminado` smallint(6) DEFAULT NULL,
  `org_tip_codigo` int(11) DEFAULT NULL,
  `org_usu_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`org_codigo`),
  KEY `FK_reference_8` (`org_tip_codigo`),
  KEY `FK_reference_15` (`org_usu_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `organizacion`
--

INSERT INTO `organizacion` (`org_codigo`, `org_nombre_completo`, `org_nombre_corto`, `org_nit`, `org_direccion`, `org_correo`, `org_nombre_contacto`, `org_telefono`, `org_fecha_registro`, `org_eliminado`, `org_tip_codigo`, `org_usu_codigo`) VALUES
(1, 'Universidad del Valle', 'Univalle', '5321590-2', 'Calle 13 1000-00', 'univalle@univalle.edu.co', 'Carlos Pérez', '3212100', '2014-02-15 17:11:49', 0, 1, 1),
(2, 'Ministerio de Educación', 'MEN', '423417419-1', 'Cra. 34 30-42', 'men@men.gov.co', 'Clara Arias', '9832629', '2014-02-16 23:16:08', 0, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organizacionproyecto`
--

CREATE TABLE IF NOT EXISTS `organizacionproyecto` (
  `orpy_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `orpy_org_codigo` int(11) DEFAULT NULL,
  `orpy_pro_codigo` int(11) DEFAULT NULL,
  `orpy_usu_codigo` int(11) DEFAULT NULL,
  `orpy_fecha_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`orpy_codigo`),
  KEY `FK_reference_1` (`orpy_org_codigo`),
  KEY `FK_reference_2` (`orpy_pro_codigo`),
  KEY `FK_reference_3` (`orpy_usu_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcar la base de datos para la tabla `organizacionproyecto`
--

INSERT INTO `organizacionproyecto` (`orpy_codigo`, `orpy_org_codigo`, `orpy_pro_codigo`, `orpy_usu_codigo`, `orpy_fecha_registro`) VALUES
(1, 1, 1, 1, '2014-02-15 17:11:52'),
(2, 1, 2, 1, '2014-02-16 23:15:05'),
(5, 2, 2, 1, '2014-02-16 23:16:35'),
(4, 2, 3, 1, '2014-02-16 23:16:22'),
(6, 1, 3, 1, '2014-02-18 11:44:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participante`
--

CREATE TABLE IF NOT EXISTS `participante` (
  `par_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `par_pers_codigo` int(11) DEFAULT NULL,
  `par_pro_codigo` int(11) DEFAULT NULL,
  `par_usu_codigo` int(11) DEFAULT NULL,
  `par_fecha_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`par_codigo`),
  KEY `FK_reference_1` (`par_pers_codigo`),
  KEY `FK_reference_2` (`par_pro_codigo`),
  KEY `FK_reference_3` (`par_usu_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcar la base de datos para la tabla `participante`
--

INSERT INTO `participante` (`par_codigo`, `par_pers_codigo`, `par_pro_codigo`, `par_usu_codigo`, `par_fecha_registro`) VALUES
(1, 1, 1, 1, '2014-02-14 13:37:45'),
(2, 3, 1, 1, '2014-02-15 20:54:49'),
(3, 2, 2, 1, '2014-02-15 20:55:04'),
(5, 3, 2, 1, '2014-02-16 23:06:54'),
(6, 3, 3, 1, '2014-02-16 23:11:19'),
(7, 2, 1, 1, '2014-02-18 11:36:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `per_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `per_nombre` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`per_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `perfil`
--

INSERT INTO `perfil` (`per_codigo`, `per_nombre`) VALUES
(1, 'Administrador'),
(2, 'Coordinador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE IF NOT EXISTS `persona` (
  `pers_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `pers_nombres` varchar(200) DEFAULT NULL,
  `pers_apellidos` varchar(200) DEFAULT NULL,
  `pers_numero_identificacion` varchar(200) DEFAULT NULL,
  `pers_cargo` varchar(200) DEFAULT NULL,
  `pers_correo` varchar(100) DEFAULT NULL,
  `pers_telefono` varchar(100) DEFAULT NULL,
  `pers_celular` varchar(100) DEFAULT NULL,
  `pers_fecha_registro` datetime DEFAULT NULL,
  `pers_eliminado` smallint(6) DEFAULT NULL,
  `pers_usu_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`pers_codigo`),
  KEY `FK_reference_13` (`pers_usu_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `persona`
--

INSERT INTO `persona` (`pers_codigo`, `pers_nombres`, `pers_apellidos`, `pers_numero_identificacion`, `pers_cargo`, `pers_correo`, `pers_telefono`, `pers_celular`, `pers_fecha_registro`, `pers_eliminado`, `pers_usu_codigo`) VALUES
(1, 'Cinara', 'Cinara', '15972248', 'Administrador', 'francojcz@gmail.com', '8548961', '3127584787', '2014-02-14 11:41:31', 0, 1),
(2, 'Franco', 'Cundar', '123467654', 'Sistemas', 'francojcz@gmail.com', '3547754', '3128753044', '2014-02-15 20:54:03', 0, 1),
(3, 'Andrea', 'Rios', '2765432345', 'Andrea Rios', 'francojcz@gmail.com', '5320093', '3189024481', '2014-02-15 20:54:21', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE IF NOT EXISTS `producto` (
  `prod_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `prod_nombre` varchar(300) DEFAULT NULL,
  `prod_fecha_entrega` date DEFAULT NULL,
  `prod_documento_url` varchar(300) DEFAULT NULL,
  `prod_fecha_registro` datetime DEFAULT NULL,
  `prod_eliminado` smallint(6) DEFAULT NULL,
  `prod_pro_codigo` int(11) DEFAULT NULL,
  `prod_usu_codigo` int(11) DEFAULT NULL,
  `prod_est_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`prod_codigo`),
  KEY `FK_reference_1` (`prod_pro_codigo`),
  KEY `FK_reference_2` (`prod_usu_codigo`),
  KEY `FK_reference_3` (`prod_est_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `producto`
--

INSERT INTO `producto` (`prod_codigo`, `prod_nombre`, `prod_fecha_entrega`, `prod_documento_url`, `prod_fecha_registro`, `prod_eliminado`, `prod_pro_codigo`, `prod_usu_codigo`, `prod_est_codigo`) VALUES
(1, 'Entregable Mejorando', '2014-02-18', 'productos/1/Graficos.xlsx', '2014-02-15 17:19:08', 0, 1, 1, 1),
(2, 'Entregable Medio Ambiente', '2014-02-07', 'productos/2/InfJoha.docx', '2014-02-16 23:18:18', 0, 1, 1, 1),
(3, 'Entregable Programa', '2014-05-17', 'productos/3/Graficos.xlsx', '2014-02-16 23:18:41', 0, 2, 1, 1),
(4, 'Entregable Proyecto', '2014-08-24', 'productos/4/InformeEjemplo.pdf', '2014-02-16 23:19:07', 0, 3, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyecto`
--

CREATE TABLE IF NOT EXISTS `proyecto` (
  `pro_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `pro_codigo_contable` varchar(200) DEFAULT NULL,
  `pro_nombre` varchar(500) DEFAULT NULL,
  `pro_descripcion` varchar(2000) DEFAULT NULL,
  `pro_valor` double DEFAULT NULL,
  `pro_acumulado_ingresos` double DEFAULT NULL,
  `pro_acumulado_egresos` double DEFAULT NULL,
  `pro_fecha_inicio` date DEFAULT NULL,
  `pro_fecha_fin` date DEFAULT NULL,
  `pro_observaciones` varchar(2000) DEFAULT NULL,
  `pro_presupuesto_url` varchar(300) DEFAULT NULL,
  `pro_pers_codigo` int(11) DEFAULT NULL,
  `pro_est_codigo` int(11) DEFAULT NULL,
  `pro_fecha_registro` datetime DEFAULT NULL,
  `pro_eliminado` smallint(6) DEFAULT NULL,
  `pro_usu_codigo` int(11) DEFAULT NULL,
  PRIMARY KEY (`pro_codigo`),
  KEY `FK_reference_6` (`pro_pers_codigo`),
  KEY `FK_reference_7` (`pro_est_codigo`),
  KEY `FK_reference_14` (`pro_usu_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcar la base de datos para la tabla `proyecto`
--

INSERT INTO `proyecto` (`pro_codigo`, `pro_codigo_contable`, `pro_nombre`, `pro_descripcion`, `pro_valor`, `pro_acumulado_ingresos`, `pro_acumulado_egresos`, `pro_fecha_inicio`, `pro_fecha_fin`, `pro_observaciones`, `pro_presupuesto_url`, `pro_pers_codigo`, `pro_est_codigo`, `pro_fecha_registro`, `pro_eliminado`, `pro_usu_codigo`) VALUES
(1, '4329', 'Mejorando nuestro Medio Ambiente', 'El proyecto Mejorando nuestro Medio Ambiente indica algunas pautas para mejorar el medio ambiente', 50000000, 5500000, 1500000, '2014-02-16', '2014-02-22', '', 'proyectos/1/Seguimiento.docx', 3, 1, '2014-02-14 11:43:10', 0, 1),
(2, '6490', 'Programa de Reciclaje y educación Ambiental', 'El proyecto Programa de Reciclaje y educación Ambiental muestra algunas pautas para incentivar la educación ambiental', 35000000, 19000000, 2000000, '2014-02-16', '2014-02-18', 'La fecha de finalización fue modificada en común acuerdo con los interesados del proyecto', 'proyectos/2/Graficos.xlsx', 1, 1, '2014-02-14 11:43:41', 0, 1),
(3, '8962', 'Proyecto de educación ambiental', 'El proyecto describe las caracteristicas para fomentar la educación ambiental', 19000000, 2000000, 2250000, '2014-04-12', '2015-02-12', '', 'proyectos/3/Graficos.xlsx', 3, 1, '2014-02-16 23:11:09', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumento`
--

CREATE TABLE IF NOT EXISTS `tipodocumento` (
  `tipd_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `tipd_nombre` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`tipd_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcar la base de datos para la tabla `tipodocumento`
--

INSERT INTO `tipodocumento` (`tipd_codigo`, `tipd_nombre`) VALUES
(1, 'Acta de Inicio'),
(2, 'Acta de Reinicio'),
(3, 'Acta de Suspensión'),
(4, 'Acta de Terminación'),
(5, 'Contrato'),
(6, 'Póliza'),
(7, 'Otro'),
(8, 'Factura');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoorganizacion`
--

CREATE TABLE IF NOT EXISTS `tipoorganizacion` (
  `tipo_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_nombre` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`tipo_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcar la base de datos para la tabla `tipoorganizacion`
--

INSERT INTO `tipoorganizacion` (`tipo_codigo`, `tipo_nombre`) VALUES
(1, 'Pública Nacional'),
(2, 'Pública Internacional'),
(3, 'Privada Nacional'),
(4, 'Privada Internacional');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `usu_codigo` int(11) NOT NULL AUTO_INCREMENT,
  `usu_per_codigo` int(11) DEFAULT NULL,
  `usu_login` varchar(200) DEFAULT NULL,
  `usu_contrasena` varchar(200) DEFAULT NULL,
  `usu_habilitado` smallint(6) DEFAULT NULL,
  `usu_pers_codigo` int(11) DEFAULT NULL,
  `usu_fecha_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`usu_codigo`),
  KEY `FK_reference_4` (`usu_per_codigo`),
  KEY `FK_reference_5` (`usu_pers_codigo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usu_codigo`, `usu_per_codigo`, `usu_login`, `usu_contrasena`, `usu_habilitado`, `usu_pers_codigo`, `usu_fecha_registro`) VALUES
(1, 1, 'administrador', 'administrador', 1, 1, '2014-02-14 11:41:31');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
