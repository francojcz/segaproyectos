
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- perfil
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `perfil`;


CREATE TABLE `perfil`
(
	`per_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`per_nombre` VARCHAR(30),	
	PRIMARY KEY (`per_codigo`)
);

#-----------------------------------------------------------------------------
#-- estadoproyecto
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `estadoproyecto`;


CREATE TABLE `estadoproyecto`
(
	`est_pro_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`est_pro_nombre` VARCHAR(30),	
	PRIMARY KEY (`est_pro_codigo`)
);

#-----------------------------------------------------------------------------
#-- ejecutorproyecto
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ejecutorproyecto`;


CREATE TABLE `ejecutorproyecto`
(
	`eje_pro_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`eje_pro_nombre` VARCHAR(100),	
	PRIMARY KEY (`eje_pro_codigo`)
);

#-----------------------------------------------------------------------------
#-- tipoproyecto
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tipoproyecto`;


CREATE TABLE `tipoproyecto`
(
	`tipp_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`tipp_nombre` VARCHAR(30),	
	PRIMARY KEY (`tipp_codigo`)
);

#-----------------------------------------------------------------------------
#-- estadoproducto
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `estadoproducto`;


CREATE TABLE `estadoproducto`
(
	`est_prod_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`est_prod_nombre` VARCHAR(30),	
	PRIMARY KEY (`est_prod_codigo`)
);

#-----------------------------------------------------------------------------
#-- tipoorganizacion
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tipoorganizacion`;


CREATE TABLE `tipoorganizacion`
(
	`tipo_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`tipo_nombre` VARCHAR(30),	
	PRIMARY KEY (`tipo_codigo`)
);

#-----------------------------------------------------------------------------
#-- tipodocumento
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tipodocumento`;


CREATE TABLE `tipodocumento`
(
	`tipd_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`tipd_nombre` VARCHAR(30),	
	PRIMARY KEY (`tipd_codigo`)
);

#-----------------------------------------------------------------------------
#-- concepto
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `concepto`;


CREATE TABLE `concepto`
(
	`con_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`con_nombre` VARCHAR(30),
        `con_fecha_registro` DATETIME,
        `con_usu_codigo` INTEGER(11), 
	PRIMARY KEY (`con_codigo`),
        KEY `FK_reference_1`(`con_usu_codigo`),
        CONSTRAINT `tipoconcepto_FK_1`
		FOREIGN KEY (`con_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- producto
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `producto`;


CREATE TABLE `producto`
(
	`prod_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
        `prod_nombre` VARCHAR(300),        
	`prod_fecha_entrega` DATE,
        `prod_documento_url` VARCHAR(300),        
        `prod_fecha_registro` DATETIME,
	`prod_eliminado` SMALLINT(6), 
        `prod_pro_codigo` INTEGER(11),
        `prod_usu_codigo` INTEGER(11),  
        `prod_est_codigo` INTEGER(11),     
	PRIMARY KEY (`prod_codigo`),
        KEY `FK_reference_1`(`prod_pro_codigo`),
        KEY `FK_reference_2`(`prod_usu_codigo`),
        KEY `FK_reference_3`(`prod_est_codigo`),
        CONSTRAINT `producto_FK_1`
		FOREIGN KEY (`prod_pro_codigo`)
		REFERENCES `proyecto` (`pro_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `producto_FK_2`
		FOREIGN KEY (`prod_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `producto_FK_3`
		FOREIGN KEY (`prod_est_codigo`)
		REFERENCES `estadoproducto` (`est_prod_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- documento
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `documento`;


CREATE TABLE `documento`
(
	`doc_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,	
        `doc_documento_url` VARCHAR(300),        
        `doc_fecha_registro` DATETIME,
	`doc_eliminado` SMALLINT(6),        
	`doc_tipd_codigo` INTEGER(11),
        `doc_usu_codigo` INTEGER(11),
        `doc_pro_codigo` INTEGER(11),
	PRIMARY KEY (`doc_codigo`),
        KEY `FK_reference_1`(`doc_tipd_codigo`),
        KEY `FK_reference_2`(`doc_usu_codigo`),
        KEY `FK_reference_3`(`doc_pro_codigo`),
        CONSTRAINT `documento_FK_1`
		FOREIGN KEY (`doc_tipd_codigo`)
		REFERENCES `tipodocumento` (`tipd_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `documento_FK_1`
		FOREIGN KEY (`doc_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `documento_FK_2`
		FOREIGN KEY (`doc_pro_codigo`)
		REFERENCES `proyecto` (`pro_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- ingreso
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `ingreso`;


CREATE TABLE `ingreso`
(
	`ing_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
        `ing_concepto` VARCHAR(500),	
        `ing_valor` DOUBLE, 
        `ing_fecha` DATE,       
        `ing_fecha_registro` DATETIME,
	`ing_eliminado` SMALLINT(6),    
        `ing_usu_codigo` INTEGER(11),
        `ing_pro_codigo` INTEGER(11),         
	PRIMARY KEY (`ing_codigo`),  
        KEY `FK_reference_11`(`ing_usu_codigo`),
        KEY `FK_reference_12`(`ing_pro_codigo`),
        CONSTRAINT `ingreso_FK_2`
		FOREIGN KEY (`ing_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `ingreso_FK_3`
		FOREIGN KEY (`ing_pro_codigo`)
		REFERENCES `proyecto` (`pro_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT       
);

#-----------------------------------------------------------------------------
#-- egreso
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `egreso`;


CREATE TABLE `egreso`
(
	`egr_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
        `egr_con_codigo` INTEGER(11), 
        `egr_valor` DOUBLE, 
        `egr_fecha` DATE,       
        `egr_fecha_registro` DATETIME,
	`egr_eliminado` SMALLINT(6),    
        `egr_usu_codigo` INTEGER(11),
        `egr_pro_codigo` INTEGER(11),         
	PRIMARY KEY (`egr_codigo`),  
        KEY `FK_reference_11`(`egr_usu_codigo`),
        KEY `FK_reference_12`(`egr_pro_codigo`),
        KEY `FK_reference_13`(`egr_con_codigo`),
        CONSTRAINT `egreso_FK_1`
		FOREIGN KEY (`egr_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `egreso_FK_2`
		FOREIGN KEY (`egr_pro_codigo`)
		REFERENCES `proyecto` (`pro_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `egreso_FK_3`
		FOREIGN KEY (`egr_con_codigo`)
		REFERENCES `concepto` (`con_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- proyecto
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `proyecto`;


CREATE TABLE `proyecto`
(
	`pro_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`pro_codigo_contable` VARCHAR(200),
        `pro_nombre` VARCHAR(500),
        `pro_descripcion` VARCHAR(2000),
        `pro_valor` DOUBLE,
        `pro_acumulado_ingresos` DOUBLE,
        `pro_acumulado_egresos` DOUBLE,
	`pro_fecha_inicio` DATE,
        `pro_fecha_fin` DATE,
        `pro_observaciones` VARCHAR(2000),
        `pro_presupuesto_url` VARCHAR(300),
        `pro_pers_codigo` INTEGER(11),
        `pro_est_codigo` INTEGER(11),
        `pro_fecha_registro` DATETIME,
        `pro_eje_codigo` INTEGER(11),
        `pro_tipp_codigo` INTEGER(11),
        `pro_otro_tipo` VARCHAR(200),
	`pro_eliminado` SMALLINT(6),
        `pro_usu_codigo` INTEGER(11),        
	PRIMARY KEY (`pro_codigo`),
        KEY `FK_reference_1`(`pro_pers_codigo`),
        KEY `FK_reference_2`(`pro_est_codigo`),
        KEY `FK_reference_3`(`pro_usu_codigo`),
        KEY `FK_reference_4`(`pro_eje_codigo`),
        KEY `FK_reference_5`(`pro_tipp_codigo`),
        CONSTRAINT `proyecto_FK_1`
		FOREIGN KEY (`pro_pers_codigo`)
		REFERENCES `persona` (`pers_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `proyecto_FK_2`
		FOREIGN KEY (`pro_est_codigo`)
		REFERENCES `estadoproyecto` (`est_pro_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `proyecto_FK_3`
		FOREIGN KEY (`pro_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `proyecto_FK_4`
		FOREIGN KEY (`pro_eje_codigo`)
		REFERENCES `ejecutorproyecto` (`eje_pro_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `proyecto_FK_5`
		FOREIGN KEY (`pro_tipp_codigo`)
		REFERENCES `tipoproyecto` (`tipp_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- organizacion
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `organizacion`;


CREATE TABLE `organizacion`
(
	`org_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`org_nombre_completo` VARCHAR(200),
        `org_nombre_corto` VARCHAR(200),
        `org_nit` VARCHAR(100),
        `org_direccion` VARCHAR(200),
        `org_correo` VARCHAR(200), 
        `org_nombre_contacto` VARCHAR(200), 
        `org_telefono` VARCHAR(100),	       
        `org_fecha_registro` DATETIME,
	`org_eliminado` SMALLINT(6),  
        `org_tip_codigo` INTEGER(11),
        `org_usu_codigo` INTEGER(11),
	PRIMARY KEY (`org_codigo`),
        KEY `FK_reference_8`(`org_tip_codigo`),  
        KEY `FK_reference_15`(`org_usu_codigo`),
        CONSTRAINT `organizacion_FK_1`
		FOREIGN KEY (`org_tip_codigo`)
		REFERENCES `tipoorganizacion` (`tipo_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `organizacion_FK_2`
		FOREIGN KEY (`org_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- persona
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `persona`;


CREATE TABLE `persona`
(
	`pers_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`pers_nombres` VARCHAR(200),
        `pers_apellidos` VARCHAR(200),
        `pers_numero_identificacion` VARCHAR(200),
        `pers_cargo` VARCHAR(200),
        `pers_correo` VARCHAR(100),
        `pers_telefono` VARCHAR(100),	
        `pers_celular` VARCHAR(100),
	`pers_fecha_registro` DATETIME,
	`pers_eliminado` SMALLINT(6),
        `pers_usu_codigo` INTEGER(11),
	PRIMARY KEY (`pers_codigo`),
        KEY `FK_reference_13`(`pers_usu_codigo`),
        CONSTRAINT `persona_FK_1`
		FOREIGN KEY (`pers_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- usuario
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `usuario`;


CREATE TABLE `usuario`
(
	`usu_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`usu_per_codigo` INTEGER(11),
	`usu_login` VARCHAR(200),
	`usu_contrasena` VARCHAR(200),
	`usu_habilitado` SMALLINT(6),
        `usu_pers_codigo` INTEGER(11),
	`usu_fecha_registro` DATETIME,	        
	PRIMARY KEY (`usu_codigo`),
	KEY `FK_reference_4`(`usu_per_codigo`),
        KEY `FK_reference_5`(`usu_pers_codigo`),        
	CONSTRAINT `usuario_FK_1`
		FOREIGN KEY (`usu_per_codigo`)
		REFERENCES `perfil` (`per_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `usuario_FK_2`
		FOREIGN KEY (`usu_pers_codigo`)
		REFERENCES `persona` (`pers_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- participante
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `participante`;


CREATE TABLE `participante`
(
	`par_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`par_pers_codigo` INTEGER(11),
	`par_pro_codigo` INTEGER(11),
        `par_usu_codigo` INTEGER(11),  
        `par_fecha_registro` DATETIME,
	PRIMARY KEY (`par_codigo`),
	KEY `FK_reference_1`(`par_pers_codigo`),
        KEY `FK_reference_2`(`par_pro_codigo`),        
        KEY `FK_reference_3`(`par_usu_codigo`),
	CONSTRAINT `participante_FK_1`
		FOREIGN KEY (`par_pers_codigo`)
		REFERENCES `persona` (`pers_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `participante_FK_2`
		FOREIGN KEY (`par_pro_codigo`)
		REFERENCES `proyecto` (`pro_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `participante_FK_2`
		FOREIGN KEY (`par_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- organizacionproyecto
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `organizacionproyecto`;


CREATE TABLE `organizacionproyecto`
(
	`orpy_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`orpy_org_codigo` INTEGER(11),
	`orpy_pro_codigo` INTEGER(11),
        `orpy_usu_codigo` INTEGER(11),  
        `orpy_fecha_registro` DATETIME,
	PRIMARY KEY (`orpy_codigo`),
	KEY `FK_reference_1`(`orpy_org_codigo`),
        KEY `FK_reference_2`(`orpy_pro_codigo`),        
        KEY `FK_reference_3`(`orpy_usu_codigo`),        
	CONSTRAINT `organizacionproyecto_FK_1`
		FOREIGN KEY (`orpy_org_codigo`)
		REFERENCES `organizacion` (`org_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `organizacionproyecto_FK_2`
		FOREIGN KEY (`orpy_pro_codigo`)
		REFERENCES `proyecto` (`pro_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `organizacionproyecto_FK_3`
		FOREIGN KEY (`orpy_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- asignaciondetiempo
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `asignaciondetiempo`;


CREATE TABLE `asignaciondetiempo`
(
	`adt_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
        `adt_mes` VARCHAR(100),
        `adt_ano` VARCHAR(100),
        `adt_asignacion` DOUBLE,
	`adt_pers_codigo` INTEGER(11),
	`adt_pro_codigo` INTEGER(11),
        `adt_pers_reg_codigo` INTEGER(11),
        `adt_fecha_registro` DATETIME,
	PRIMARY KEY (`adt_codigo`),
	KEY `FK_reference_1`(`adt_pers_codigo`),
        KEY `FK_reference_2`(`adt_pro_codigo`), 
        KEY `FK_reference_3`(`adt_pers_reg_codigo`),       
	CONSTRAINT `asignaciondetiempo_FK_1`
		FOREIGN KEY (`adt_pers_codigo`)
		REFERENCES `persona` (`pers_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `asignaciondetiempo_FK_2`
		FOREIGN KEY (`adt_pro_codigo`)
		REFERENCES `proyecto` (`pro_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `asignaciondetiempo_FK_3`
		FOREIGN KEY (`adt_pers_reg_codigo`)
		REFERENCES `persona` (`pers_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- conceptosingreso
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `conceptosingreso`;


CREATE TABLE `conceptosingreso`
(
	`csi_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,        
        `csi_valor` DOUBLE,
        `csi_fecha` DATE,	
        `csi_usu_codigo` INTEGER(11),
        `csi_con_codigo` INTEGER(11),   
        `csi_ing_codigo` INTEGER(11),
        `csi_pro_codigo` INTEGER(11),
	PRIMARY KEY (`csi_codigo`),  
        KEY `FK_reference_1`(`csi_usu_codigo`),
        KEY `FK_reference_2`(`csi_con_codigo`),
        KEY `FK_reference_3`(`csi_ing_codigo`),
        KEY `FK_reference_4`(`csi_pro_codigo`),
        CONSTRAINT `conceptosingreso_FK_1`
		FOREIGN KEY (`csi_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `conceptosingreso_FK_2`
		FOREIGN KEY (`csi_con_codigo`)
		REFERENCES `concepto` (`con_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `conceptosingreso_FK_3`
		FOREIGN KEY (`csi_ing_codigo`)
		REFERENCES `ingreso` (`ing_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
        CONSTRAINT `conceptosingreso_FK_4`
		FOREIGN KEY (`csi_pro_codigo`)
		REFERENCES `proyecto` (`pro_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- alarma
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `alarma`;


CREATE TABLE `alarma`
(
	`ala_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,    
        `ala_concepto` VARCHAR(200),
        `ala_con_codigo` VARCHAR(200),
        `ala_descripcion` VARCHAR(500),
        `ala_enviado` SMALLINT(6),
	PRIMARY KEY (`ala_codigo`)       
);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;