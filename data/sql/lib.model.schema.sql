
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- categoria_evento
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `categoria_evento`;


CREATE TABLE `categoria_evento`
(
	`cat_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`cat_nombre` VARCHAR(200),
	`cat_fecha_registro_sistema` DATETIME,
	`cat_usu_crea` INTEGER(11),
	`cat_usu_actualiza` INTEGER(11),
	`cat_fecha_actualizacion` DATETIME,
	`cat_eliminado` SMALLINT(6),
	`cat_causa_eliminacion` VARCHAR(250),
	`cat_causa_actualizacion` VARCHAR(250),
	PRIMARY KEY (`cat_codigo`),
	KEY `FK_reference_31`(`cat_usu_crea`),
	KEY `FK_reference_32`(`cat_usu_actualiza`),
	CONSTRAINT `categoria_evento_FK_1`
		FOREIGN KEY (`cat_usu_crea`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `categoria_evento_FK_2`
		FOREIGN KEY (`cat_usu_actualiza`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- computador
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `computador`;


CREATE TABLE `computador`
(
	`com_certificado` VARCHAR(40)  NOT NULL,
	`com_nombre` VARCHAR(255),
	PRIMARY KEY (`com_certificado`)
);

#-----------------------------------------------------------------------------
#-- consumible_maquina
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `consumible_maquina`;


CREATE TABLE `consumible_maquina`
(
	`com_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`com_maq_codigo` INTEGER(11),
	`com_fecha_cambio` DATETIME,
	`com_item` VARCHAR(200),
	`com_numero_parte` VARCHAR(200),
	`com_periodicidad` INTEGER(11),
	`com_proximo_mantenimiento` DATETIME,
	`com_fecha_registro_sistema` DATETIME,
	PRIMARY KEY (`com_codigo`),
	KEY `FK_reference_17`(`com_maq_codigo`),
	CONSTRAINT `consumible_maquina_FK_1`
		FOREIGN KEY (`com_maq_codigo`)
		REFERENCES `maquina` (`maq_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- empleado
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `empleado`;


CREATE TABLE `empleado`
(
	`empl_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`empl_emp_codigo` INTEGER(11),
	`empl_tid_codigo` INTEGER(11),
	`empl_usu_codigo` INTEGER(11),
	`empl_nombres` VARCHAR(200),
	`empl_apellidos` VARCHAR(200),
	`empl_numero_identificacion` INTEGER(11),
	`empl_url_foto` VARCHAR(200),
	`empl_fecha_registro_sistema` DATETIME,
	`empl_usu_crea` INTEGER(11),
	`empl_usu_actualiza` INTEGER(11),
	`empl_fecha_actualizacion` DATETIME,
	`empl_eliminado` SMALLINT(6),
	`empl_causa_eliminacion` VARCHAR(250),
	`empl_causa_actualizacion` VARCHAR(250),
	PRIMARY KEY (`empl_codigo`),
	KEY `FK_reference_18`(`empl_emp_codigo`),
	KEY `FK_reference_19`(`empl_tid_codigo`),
	KEY `FK_reference_20`(`empl_usu_codigo`),
	KEY `FK_reference_35`(`empl_usu_crea`),
	KEY `FK_reference_36`(`empl_usu_actualiza`),
	CONSTRAINT `empleado_FK_1`
		FOREIGN KEY (`empl_emp_codigo`)
		REFERENCES `empresa` (`emp_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `empleado_FK_2`
		FOREIGN KEY (`empl_tid_codigo`)
		REFERENCES `tipo_identificacion` (`tid_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `empleado_FK_3`
		FOREIGN KEY (`empl_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `empleado_FK_4`
		FOREIGN KEY (`empl_usu_crea`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `empleado_FK_5`
		FOREIGN KEY (`empl_usu_actualiza`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- empresa
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `empresa`;


CREATE TABLE `empresa`
(
	`emp_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`emp_nombre` VARCHAR(200),
	`emp_logo_url` VARCHAR(200),
	`emp_nit` VARCHAR(200),
	`emp_fecha_limite_licencia` DATE,
	`emp_fecha_inicio_licencia` DATE,
	`emp_inyect_estandar_promedio` INTEGER(11),
	`emp_tiempo_alerta_consumible` INTEGER(11),
	`emp_fecha_registro_sistema` DATETIME,
	`emp_usu_crea` INTEGER(11),
	`emp_usu_actualiza` INTEGER(11),
	`emp_fecha_actualizacion` DATETIME,
	`emp_eliminado` SMALLINT(6),
	`emp_causa_eliminacion` VARCHAR(250),
	`emp_causa_actualizacion` VARCHAR(250),
	PRIMARY KEY (`emp_codigo`),
	KEY `FK_reference_37`(`emp_usu_crea`),
	KEY `FK_reference_38`(`emp_usu_actualiza`),
	CONSTRAINT `empresa_FK_1`
		FOREIGN KEY (`emp_usu_crea`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `empresa_FK_2`
		FOREIGN KEY (`emp_usu_actualiza`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- estado
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `estado`;


CREATE TABLE `estado`
(
	`est_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`est_nombre` VARCHAR(200),
	`est_fecha_registro_sistema` DATETIME,
	`est_usu_crea` INTEGER(11),
	`est_usu_actualiza` INTEGER(11),
	`est_fecha_actualizacion` DATETIME,
	`est_eliminado` SMALLINT(6),
	`est_causa_eliminacion` VARCHAR(250),
	`est_causa_actualizacion` VARCHAR(250),
	PRIMARY KEY (`est_codigo`),
	KEY `FK_reference_41`(`est_usu_crea`),
	KEY `FK_reference_42`(`est_usu_actualiza`),
	CONSTRAINT `estado_FK_1`
		FOREIGN KEY (`est_usu_crea`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `estado_FK_2`
		FOREIGN KEY (`est_usu_actualiza`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- evento
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `evento`;


CREATE TABLE `evento`
(
	`eve_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`eve_nombre` VARCHAR(200)  NOT NULL,
	`eve_fecha_registro_sistema` DATETIME,
	`eve_usu_crea` INTEGER(11),
	`eve_usu_actualiza` INTEGER(11),
	`eve_fecha_actualizacion` DATETIME,
	`eve_eliminado` SMALLINT(6),
	`eve_causa_eliminacion` VARCHAR(250),
	`eve_causa_actualizacion` VARCHAR(250),
	PRIMARY KEY (`eve_codigo`),
	KEY `FK_reference_29`(`eve_usu_crea`),
	KEY `FK_reference_30`(`eve_usu_actualiza`),
	CONSTRAINT `evento_FK_1`
		FOREIGN KEY (`eve_usu_crea`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `evento_FK_2`
		FOREIGN KEY (`eve_usu_actualiza`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- evento_en_registro
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `evento_en_registro`;


CREATE TABLE `evento_en_registro`
(
	`evrg_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`evrg_rum_codigo` INTEGER(11),
	`evrg_eve_codigo` INTEGER(11),
	`evrg_observaciones` VARCHAR(200),
	`evrg_hora_ocurrio` TIME,
	`evrg_hora_registro` TIME,
	`evrg_fecha_registro_sistema` DATETIME,
	`evrg_usu_crea` INTEGER(11),
	`evrg_usu_actualiza` INTEGER(11),
	`evrg_fecha_actualizacion` DATETIME,
	`evrg_eliminado` SMALLINT(6),
	`evrg_causa_eliminacion` VARCHAR(250),
	`evrg_causa_actualizacion` VARCHAR(250),
	`evrg_duracion` DECIMAL(10,4),
	PRIMARY KEY (`evrg_codigo`),
	KEY `FK_reference_49`(`evrg_usu_crea`),
	KEY `FK_reference_50`(`evrg_usu_actualiza`),
	KEY `FK_ref_eventoenregistro_registrouso`(`evrg_rum_codigo`),
	KEY `FK_ref_eventoenregistro_evento`(`evrg_eve_codigo`),
	CONSTRAINT `evento_en_registro_FK_1`
		FOREIGN KEY (`evrg_rum_codigo`)
		REFERENCES `registro_uso_maquina` (`rum_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `evento_en_registro_FK_2`
		FOREIGN KEY (`evrg_eve_codigo`)
		REFERENCES `evento` (`eve_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `evento_en_registro_FK_3`
		FOREIGN KEY (`evrg_usu_crea`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `evento_en_registro_FK_4`
		FOREIGN KEY (`evrg_usu_actualiza`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- evento_por_categoria
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `evento_por_categoria`;


CREATE TABLE `evento_por_categoria`
(
	`evca_cat_codigo` INTEGER(11)  NOT NULL,
	`evca_eve_codigo` INTEGER(11)  NOT NULL,
	`evca_usu_crea` INTEGER(11),
	`evca_fecha_registro_sistema` DATETIME,
	PRIMARY KEY (`evca_cat_codigo`,`evca_eve_codigo`),
	KEY `FK_reference_51`(`evca_usu_crea`),
	KEY `FK_reference_24`(`evca_eve_codigo`),
	CONSTRAINT `evento_por_categoria_FK_1`
		FOREIGN KEY (`evca_cat_codigo`)
		REFERENCES `categoria_evento` (`cat_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `evento_por_categoria_FK_2`
		FOREIGN KEY (`evca_eve_codigo`)
		REFERENCES `evento` (`eve_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `evento_por_categoria_FK_3`
		FOREIGN KEY (`evca_usu_crea`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- indicador
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `indicador`;


CREATE TABLE `indicador`
(
	`ind_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`ind_sigla` VARCHAR(30),
	`ind_nombre` VARCHAR(200),
	`ind_fecha_registro_sistema` DATETIME,
	`ind_unidad` VARCHAR(20),
	`ind_usu_crea` INTEGER(11),
	`ind_usu_actualiza` INTEGER(11),
	`ind_fecha_actualizacion` DATETIME,
	`ind_eliminado` SMALLINT(6),
	`ind_causa_eliminacion` VARCHAR(250),
	`ind_causa_actualizacion` VARCHAR(250),
	PRIMARY KEY (`ind_codigo`),
	KEY `FK_reference_39`(`ind_usu_crea`),
	KEY `FK_reference_40`(`ind_usu_actualiza`),
	CONSTRAINT `indicador_FK_1`
		FOREIGN KEY (`ind_usu_crea`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `indicador_FK_2`
		FOREIGN KEY (`ind_usu_actualiza`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- maquina
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `maquina`;


CREATE TABLE `maquina`
(
	`maq_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`maq_est_codigo` INTEGER(11),
	`maq_com_certificado` VARCHAR(40),
	`maq_nombre` VARCHAR(200),
	`maq_marca` VARCHAR(200),
	`maq_modelo` VARCHAR(200),
	`maq_fecha_adquisicion` DATE,
	`maq_foto_url` VARCHAR(200),
	`maq_tiempo_inyeccion` DECIMAL(8,4),
	`maq_fecha_registro_sistema` DATETIME,
	`maq_codigo_inventario` VARCHAR(20),
	`maq_usu_crea` INTEGER(11),
	`maq_usu_actualiza` INTEGER(11),
	`maq_fecha_actualizacion` DATETIME,
	`maq_eliminado` SMALLINT(6),
	`maq_causa_eliminacion` VARCHAR(250),
	`maq_causa_actualizacion` VARCHAR(250),
	PRIMARY KEY (`maq_codigo`),
	KEY `FK_reference_21`(`maq_com_certificado`),
	KEY `FK_reference_43`(`maq_usu_crea`),
	KEY `FK_reference_44`(`maq_usu_actualiza`),
	KEY `FK_reference_10`(`maq_est_codigo`),
	CONSTRAINT `maquina_FK_1`
		FOREIGN KEY (`maq_est_codigo`)
		REFERENCES `estado` (`est_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `maquina_FK_2`
		FOREIGN KEY (`maq_com_certificado`)
		REFERENCES `computador` (`com_certificado`)
		ON UPDATE SET NULL
		ON DELETE SET NULL,
	CONSTRAINT `maquina_FK_3`
		FOREIGN KEY (`maq_usu_crea`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `maquina_FK_4`
		FOREIGN KEY (`maq_usu_actualiza`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- meta_anual_x_indicador
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `meta_anual_x_indicador`;


CREATE TABLE `meta_anual_x_indicador`
(
	`mea_ind_codigo` INTEGER(11)  NOT NULL,
	`mea_emp_codigo` INTEGER(11)  NOT NULL,
	`mea_anio` INTEGER(11)  NOT NULL,
	`mea_valor` DECIMAL(8,2),
	`mea_fecha_registro_sistema` DATETIME,
	`mea_usu_crea` INTEGER(11),
	`mea_usu_actualiza` INTEGER(11),
	`mea_fecha_actualizacion` DATETIME,
	`mea_eliminado` SMALLINT(6),
	`mea_causa_eliminacion` VARCHAR(250),
	`mea_causa_actualizacion` VARCHAR(250),
	PRIMARY KEY (`mea_ind_codigo`,`mea_emp_codigo`,`mea_anio`),
	KEY `FK_reference_45`(`mea_usu_crea`),
	KEY `FK_reference_46`(`mea_usu_actualiza`),
	KEY `FK_reference_16`(`mea_emp_codigo`),
	CONSTRAINT `meta_anual_x_indicador_FK_1`
		FOREIGN KEY (`mea_ind_codigo`)
		REFERENCES `indicador` (`ind_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `meta_anual_x_indicador_FK_2`
		FOREIGN KEY (`mea_emp_codigo`)
		REFERENCES `empresa` (`emp_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `meta_anual_x_indicador_FK_3`
		FOREIGN KEY (`mea_usu_crea`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `meta_anual_x_indicador_FK_4`
		FOREIGN KEY (`mea_usu_actualiza`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- metodo
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `metodo`;


CREATE TABLE `metodo`
(
	`met_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`met_nombre` VARCHAR(200),
	`met_tiempo_alistamiento` DECIMAL(12,4),
	`met_tiempo_acondicionamiento` DECIMAL(12,4),
	`met_tiempo_estabilizacion` DECIMAL(12,4),
	`met_tiempo_estandar` DECIMAL(12,4),
	`met_tiempo_corrida_sistema` DECIMAL(12,4),
	`met_tiempo_corrida_curvas` DECIMAL(12,4),
	`met_num_inyeccion_estandar_1` INTEGER(11),
	`met_num_inyeccion_estandar_2` INTEGER(11),
	`met_num_inyeccion_estandar_3` INTEGER(11),
	`met_num_inyeccion_estandar_4` INTEGER(11),
	`met_num_inyeccion_estandar_5` INTEGER(11),
	`met_num_inyeccion_estandar_6` INTEGER(11),
	`met_num_inyeccion_estandar_7` INTEGER(11),
	`met_num_inyeccion_estandar_8` INTEGER(11),
	`met_fecha_registro_sistema` DATETIME,
	`met_num_inyec_x_mu_producto` INTEGER(11),
	`met_num_inyec_x_mu_estabilidad` INTEGER(11),
	`met_num_inyec_x_mu_materia_pri` INTEGER(11),
	`met_num_inyec_x_mu_pureza` INTEGER(11),
	`met_num_inyec_x_mu_disolucion` INTEGER(11),
	`met_num_inyec_x_mu_uniformidad` INTEGER(11),
	`met_numero_inyeccion_estandar` INTEGER(11),
	`met_usu_crea` INTEGER(11),
	`met_usu_actualiza` INTEGER(11),
	`met_fecha_actualizacion` DATETIME,
	`met_eliminado` SMALLINT(6),
	`met_causa_eliminacion` VARCHAR(250),
	`met_causa_actualizacion` VARCHAR(250),
	`met_tc_producto_terminado` DECIMAL(12,4),
	`met_tc_estabilidad` DECIMAL(12,4),
	`met_tc_materia_prima` DECIMAL(12,4),
	`met_tc_pureza` DECIMAL(12,4),
	`met_tc_disolucion` DECIMAL(12,4),
	`met_tc_uniformidad` DECIMAL(12,4),
	PRIMARY KEY (`met_codigo`),
	KEY `FK_reference_27`(`met_usu_crea`),
	KEY `FK_reference_28`(`met_usu_actualiza`),
	CONSTRAINT `metodo_FK_1`
		FOREIGN KEY (`met_usu_crea`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `metodo_FK_2`
		FOREIGN KEY (`met_usu_actualiza`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- perfil
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `perfil`;


CREATE TABLE `perfil`
(
	`per_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`per_nombre` VARCHAR(30),
	`per_fecha_registro_sistema` DATETIME,
	`per_fecha_actualizacion` DATETIME,
	`per_eliminado` SMALLINT(6),
	`per_causa_eliminacion` VARCHAR(250),
	`per_causa_actualizacion` VARCHAR(250),
	`per_usu_crea` VARCHAR(20),
	`per_usu_actualiza` VARCHAR(20),
	PRIMARY KEY (`per_codigo`)
);

#-----------------------------------------------------------------------------
#-- registro_modificacion
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `registro_modificacion`;


CREATE TABLE `registro_modificacion`
(
	`rem_id` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`rem_rum_codigo` INTEGER(11),
	`rem_usu_codigo` INTEGER(11),
	`rem_fecha_hora` DATETIME,
	`rem_nombre_campo` VARCHAR(255),
	`rem_valor_antiguo` VARCHAR(255),
	`rem_valor_nuevo` VARCHAR(255),
	`rem_causa` VARCHAR(255),
	PRIMARY KEY (`rem_id`),
	KEY `FK_reference_25`(`rem_rum_codigo`),
	KEY `FK_reference_26`(`rem_usu_codigo`),
	CONSTRAINT `registro_modificacion_FK_1`
		FOREIGN KEY (`rem_rum_codigo`)
		REFERENCES `registro_uso_maquina` (`rum_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `registro_modificacion_FK_2`
		FOREIGN KEY (`rem_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- registro_uso_maquina
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `registro_uso_maquina`;


CREATE TABLE `registro_uso_maquina`
(
	`rum_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`rum_maq_codigo` INTEGER(11),
	`rum_met_codigo` INTEGER(11),
	`rum_usu_codigo` INTEGER(11),
	`rum_usu_codigo_elimino` INTEGER(11),
	`rum_hora_inicio_trabajo` TIME,
	`rum_hora_fin_trabajo` TIME,
	`rum_tiempo_entre_modelo` TIME,
	`rum_tiempo_cambio_modelo` DECIMAL(12,4),
	`rum_tiempo_corrida_sistema` DECIMAL(12,4),
	`rum_tiempo_corrida_curvas` DECIMAL(12,4),
	`rum_tiempo_corrida_sistema_est` DECIMAL(12,4),
	`rum_tiempo_corrida_curvas_esta` DECIMAL(12,4),
	`rum_num_inyeccion_estandar_per` DECIMAL(12,4),
	`rum_numero_inyeccion_estandar` DECIMAL(12,4),
	`rum_tiempo_preparacion` DECIMAL(12,4),
	`rum_tiempo_duracion_ciclo` TIME,
	`rum_numero_corridas` INTEGER(11),
	`rum_numero_inyeccion_estandar1` DECIMAL(12,4),
	`rum_numero_inyeccion_estandar2` DECIMAL(12,4),
	`rum_numero_inyeccion_estandar3` DECIMAL(12,4),
	`rum_numero_inyeccion_estandar4` DECIMAL(12,4),
	`rum_numero_inyeccion_estandar5` DECIMAL(12,4),
	`rum_numero_inyeccion_estandar6` DECIMAL(12,4),
	`rum_numero_inyeccion_estandar7` DECIMAL(12,4),
	`rum_numero_inyeccion_estandar8` DECIMAL(12,4),
	`rum_num_inyeccion_estandar1_pe` DECIMAL(12,4),
	`rum_num_inyeccion_estandar2_pe` DECIMAL(12,4),
	`rum_num_inyeccion_estandar3_pe` DECIMAL(12,4),
	`rum_num_inyeccion_estandar4_pe` DECIMAL(12,4),
	`rum_num_inyeccion_estandar5_pe` DECIMAL(12,4),
	`rum_num_inyeccion_estandar6_pe` DECIMAL(12,4),
	`rum_num_inyeccion_estandar7_pe` DECIMAL(12,4),
	`rum_num_inyeccion_estandar8_pe` DECIMAL(12,4),
	`rum_num_muestras_producto` DECIMAL(12,4),
	`rum_num_muestras_estabilidad` DECIMAL(12,4),
	`rum_num_muestras_materia_prima` DECIMAL(12,4),
	`rum_num_muestras_pureza` DECIMAL(12,4),
	`rum_num_muestras_disolucion` DECIMAL(12,4),
	`rum_num_muestras_uniformidad` DECIMAL(12,4),
	`rum_num_mu_producto_perdida` DECIMAL(12,4),
	`rum_num_mu_estabilidad_perdida` DECIMAL(12,4),
	`rum_num_mu_materia_prima_perdi` DECIMAL(12,4),
	`rum_num_muestras_pureza_perdid` DECIMAL(12,4),
	`rum_num_muestras_disolucion_pe` DECIMAL(12,4),
	`rum_num_muestras_uniformidad_p` DECIMAL(12,4),
	`rum_num_inyec_x_muestra_estabi` DECIMAL(12,4),
	`rum_num_inyec_x_muestra_produc` DECIMAL(12,4),
	`rum_num_inyec_x_muestra_materi` DECIMAL(12,4),
	`rum_num_inyec_x_muestra_pureza` DECIMAL(12,4),
	`rum_num_inyec_x_muestra_disolu` DECIMAL(12,4),
	`rum_num_inyec_x_muestra_unifor` DECIMAL(12,4),
	`rum_num_inyec_x_mu_estabi_perd` DECIMAL(12,4),
	`rum_num_inyec_x_mu_produc_perd` DECIMAL(12,4),
	`rum_num_inyec_x_mu_materi_perd` DECIMAL(12,4),
	`rum_num_inyec_x_mu_pureza_perd` DECIMAL(12,4),
	`rum_num_inyec_x_mu_disolu_perd` DECIMAL(12,4),
	`rum_num_inyec_x_mu_unifor_perd` DECIMAL(12,4),
	`rum_fallas` DECIMAL(12,4),
	`rum_observaciones` VARCHAR(300),
	`rum_fecha` DATE,
	`rum_fecha_hora_reg_sistema` DATETIME,
	`rum_causa_eliminacion` VARCHAR(300),
	`rum_fecha_hora_elim_sistema` DATETIME,
	`rum_eliminado` TINYINT(1),
	`rum_tc_producto_terminado` DECIMAL(12,4),
	`rum_tc_estabilidad` DECIMAL(12,4),
	`rum_tc_materia_prima` DECIMAL(12,4),
	`rum_tc_pureza` DECIMAL(12,4),
	`rum_tc_disolucion` DECIMAL(12,4),
	`rum_tc_uniformidad` DECIMAL(12,4),
	`rum_tc_producto_terminado_esta` DECIMAL(12,4),
	`rum_tc_estabilidad_estandar` DECIMAL(12,4),
	`rum_tc_materia_prima_estandar` DECIMAL(12,4),
	`rum_tc_pureza_estandar` DECIMAL(12,4),
	`rum_tc_disolucion_estandar` DECIMAL(12,4),
	`rum_tc_uniformidad_estandar` DECIMAL(12,4),
	PRIMARY KEY (`rum_codigo`),
	KEY `FK_reference_22`(`rum_usu_codigo_elimino`),
	KEY `FK_reference_6`(`rum_maq_codigo`),
	KEY `FK_reference_7`(`rum_met_codigo`),
	KEY `FK_reference_8`(`rum_usu_codigo`),
	CONSTRAINT `registro_uso_maquina_FK_1`
		FOREIGN KEY (`rum_maq_codigo`)
		REFERENCES `maquina` (`maq_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `registro_uso_maquina_FK_2`
		FOREIGN KEY (`rum_met_codigo`)
		REFERENCES `metodo` (`met_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `registro_uso_maquina_FK_3`
		FOREIGN KEY (`rum_usu_codigo`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `registro_uso_maquina_FK_4`
		FOREIGN KEY (`rum_usu_codigo_elimino`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

#-----------------------------------------------------------------------------
#-- tipo_identificacion
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `tipo_identificacion`;


CREATE TABLE `tipo_identificacion`
(
	`tid_codigo` INTEGER(11)  NOT NULL AUTO_INCREMENT,
	`tid_nombre` VARCHAR(30),
	`tid_fecha_registro_sistema` DATETIME,
	`tid_usu_crea` INTEGER(11),
	`tid_usu_actualiza` INTEGER(11),
	`tid_fecha_actualizacion` DATETIME,
	`tid_eliminado` SMALLINT(6),
	`tid_causa_eliminacion` VARCHAR(250),
	`tid_causa_actualizacion` VARCHAR(250),
	PRIMARY KEY (`tid_codigo`),
	KEY `FK_reference_33`(`tid_usu_crea`),
	KEY `FK_reference_34`(`tid_usu_actualiza`),
	CONSTRAINT `tipo_identificacion_FK_1`
		FOREIGN KEY (`tid_usu_crea`)
		REFERENCES `usuario` (`usu_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT,
	CONSTRAINT `tipo_identificacion_FK_2`
		FOREIGN KEY (`tid_usu_actualiza`)
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
	`usu_password` VARCHAR(200),
	`usu_habilitado` SMALLINT(6),
	`usu_fecha_registro_sistema` DATETIME,
	`usu_fecha_actualizacion` DATETIME,
	`usu_causa_actualizacion` VARCHAR(250),
	`usu_crea` VARCHAR(20),
	`usu_actualiza` VARCHAR(20),
	PRIMARY KEY (`usu_codigo`),
	KEY `FK_reference_4`(`usu_per_codigo`),
	CONSTRAINT `usuario_FK_1`
		FOREIGN KEY (`usu_per_codigo`)
		REFERENCES `perfil` (`per_codigo`)
		ON UPDATE RESTRICT
		ON DELETE RESTRICT
);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
