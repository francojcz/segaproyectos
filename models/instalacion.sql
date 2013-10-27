/*==============================================================*/
/* Database name:  tpmlabs_db                                   */
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     04/05/2011 10:54:31 a.m.                     */
/*==============================================================*/


drop database if exists tpmqlabs;

/*==============================================================*/
/* Database: tpmlabs_db                                         */
/*==============================================================*/
create database tpmqlabs;

use tpmqlabs;

/*==============================================================*/
/* Table: perfil                                                */
/*==============================================================*/
create table perfil
(
   per_codigo          int not null auto_increment,
   per_nombre           varchar(30),
   per_fecha_registro_sistema datetime,
   per_fecha_actualizacion datetime,
   per_eliminado        smallint,
   per_causa_eliminacion varchar(250),
   per_causa_actualizacion varchar(250),
   per_usu_crea         varchar(20),
   per_usu_actualiza    varchar(20),
   primary key (per_codigo)
)
;

/*==============================================================*/
/* Table: usuario                                               */
/*==============================================================*/
create table usuario
(
   usu_codigo           int not null auto_increment,
   usu_per_codigo       int,
   usu_login            varchar(200),
   usu_password         varchar(200),
   usu_habilitado       smallint,
   usu_fecha_registro_sistema datetime,
   usu_fecha_actualizacion datetime,
   usu_causa_actualizacion varchar(250),
   usu_crea             varchar(20),
   usu_actualiza        varchar(20),
   primary key (usu_codigo),
   constraint FK_reference_4 foreign key (usu_per_codigo)
      references perfil (per_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: categoria_evento                                      */
/*==============================================================*/
create table categoria_evento
(
   cat_codigo           int not null auto_increment,
   cat_nombre           VARCHAR(200),
   cat_fecha_registro_sistema datetime,
   cat_usu_crea         int,
   cat_usu_actualiza    int,
   cat_fecha_actualizacion datetime,
   cat_eliminado        smallint,
   cat_causa_eliminacion varchar(250),
   cat_causa_actualizacion varchar(250),
   primary key (cat_codigo),
   constraint FK_reference_31 foreign key (cat_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_32 foreign key (cat_usu_actualiza)
      references usuario (usu_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: categoria_equipo                                      */
/*==============================================================*/
create table categoria_equipo
(
   cat_codigo           int not null auto_increment,
   cat_nombre           VARCHAR(200),
   cat_fecha_registro_sistema datetime,
   cat_usu_crea         int,
   cat_usu_actualiza    int,
   cat_fecha_actualizacion datetime,
   cat_eliminado        smallint,
   cat_causa_eliminacion varchar(250),
   cat_causa_actualizacion varchar(250),
   primary key (cat_codigo),
   constraint FK_reference_31 foreign key (cat_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_32 foreign key (cat_usu_actualiza)
      references usuario (usu_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: computador                                            */
/*==============================================================*/
create table computador
(
   com_certificado      varchar(40) not null,
   com_nombre           varchar(255),
   primary key (com_certificado)
)
;

/*==============================================================*/
/* Table: estado                                                */
/*==============================================================*/
create table estado
(
   est_codigo           int not null auto_increment,
   est_nombre           varchar(200),
   est_fecha_registro_sistema datetime,
   est_usu_crea         int,
   est_usu_actualiza    int,
   est_fecha_actualizacion datetime,
   est_eliminado        smallint,
   est_causa_eliminacion varchar(250),
   est_causa_actualizacion varchar(250),
   primary key (est_codigo),
   constraint FK_reference_41 foreign key (est_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_42 foreign key (est_usu_actualiza)
      references usuario (usu_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: maquina                                               */
/*==============================================================*/
create table maquina
(
   maq_codigo           int not null auto_increment,
   maq_est_codigo       int,
   maq_com_certificado  varchar(40),
   maq_nombre           varchar(200),
   maq_marca            varchar(200),
   maq_modelo           varchar(200),
   maq_fecha_adquisicion date,
   maq_foto_url         varchar(200),
   maq_tiempo_inyeccion numeric(8,4),
   maq_fecha_registro_sistema datetime,
   maq_codigo_inventario varchar(20),
   maq_usu_crea         int,
   maq_usu_actualiza    int,
   maq_fecha_actualizacion datetime,
   maq_eliminado        smallint,
   maq_causa_eliminacion varchar(250),
   maq_causa_actualizacion varchar(250),
   maq_indicadores       smallint,
   primary key (maq_codigo),
   constraint FK_reference_21 foreign key (maq_com_certificado)
      references computador (com_certificado) on delete set null on update set null,
   constraint FK_reference_43 foreign key (maq_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_44 foreign key (maq_usu_actualiza)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_10 foreign key (maq_est_codigo)
      references estado (est_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: consumible_maquina                                    */
/*==============================================================*/
create table consumible_maquina
(
   com_codigo           int not null auto_increment,
   com_maq_codigo       int,
   com_fecha_cambio     datetime,
   com_item             varchar(200),
   com_numero_parte     varchar(200),
   com_periodicidad     int,
   com_proximo_mantenimiento datetime,
   com_fecha_registro_sistema datetime,
   primary key (com_codigo),
   constraint FK_reference_17 foreign key (com_maq_codigo)
      references maquina (maq_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: empresa                                               */
/*==============================================================*/
create table empresa
(
   emp_codigo           int not null auto_increment,
   emp_nombre           varchar(200),
   emp_logo_url         varchar(200),
   emp_nit              varchar(200),
   emp_fecha_limite_licencia date,
   emp_fecha_inicio_licencia date,
   emp_inyect_estandar_promedio int,
   emp_tiempo_alerta_consumible int,
   emp_fecha_registro_sistema datetime,
   emp_usu_crea         int,
   emp_usu_actualiza    int,
   emp_fecha_actualizacion datetime,
   emp_eliminado        smallint,
   emp_causa_eliminacion varchar(250),
   emp_causa_actualizacion varchar(250),
   primary key (emp_codigo),
   constraint FK_reference_37 foreign key (emp_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_38 foreign key (emp_usu_actualiza)
      references usuario (usu_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: tipo_identificacion                                   */
/*==============================================================*/
create table tipo_identificacion
(
   tid_codigo           int not null auto_increment,
   tid_nombre           varchar(30),
   tid_fecha_registro_sistema datetime,
   tid_usu_crea         int,
   tid_usu_actualiza    int,
   tid_fecha_actualizacion datetime,
   tid_eliminado        smallint,
   tid_causa_eliminacion varchar(250),
   tid_causa_actualizacion varchar(250),
   primary key (tid_codigo),
   constraint FK_reference_33 foreign key (tid_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_34 foreign key (tid_usu_actualiza)
      references usuario (usu_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: empleado                                              */
/*==============================================================*/
create table empleado
(
   empl_codigo          int not null auto_increment,
   empl_emp_codigo      int,
   empl_tid_codigo      int,
   empl_usu_codigo      int,
   empl_nombres         varchar(200),
   empl_apellidos       varchar(200),
   empl_numero_identificacion int,
   empl_url_foto        varchar(200),
   empl_fecha_registro_sistema datetime,
   empl_usu_crea        int,
   empl_usu_actualiza   int,
   empl_fecha_actualizacion datetime,
   empl_eliminado       smallint,
   empl_causa_eliminacion varchar(250),
   empl_causa_actualizacion varchar(250),
   primary key (empl_codigo),
   constraint FK_reference_18 foreign key (empl_emp_codigo)
      references empresa (emp_codigo) on delete restrict on update restrict,
   constraint FK_reference_19 foreign key (empl_tid_codigo)
      references tipo_identificacion (tid_codigo) on delete restrict on update restrict,
   constraint FK_reference_20 foreign key (empl_usu_codigo)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_35 foreign key (empl_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_36 foreign key (empl_usu_actualiza)
      references usuario (usu_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: evento                                                */
/*==============================================================*/
create table evento
(
   eve_codigo           int not null auto_increment,
   eve_nombre           varchar(200) not null,
   eve_fecha_registro_sistema datetime,
   eve_usu_crea         int,
   eve_usu_actualiza    int,
   eve_fecha_actualizacion datetime,
   eve_eliminado        smallint,
   eve_causa_eliminacion varchar(250),
   eve_causa_actualizacion varchar(250),
   primary key (eve_codigo),
   constraint FK_reference_29 foreign key (eve_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_30 foreign key (eve_usu_actualiza)
      references usuario (usu_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: metodo                                                */
/*==============================================================*/
create table metodo
(
   met_codigo           int not null auto_increment,
   met_nombre           varchar(200),
   met_tiempo_alistamiento numeric(12,4),
   met_tiempo_acondicionamiento numeric(12,4),
   met_tiempo_estabilizacion numeric(12,4),
   met_tiempo_estandar  numeric(12,4),
   met_tiempo_corrida_sistema numeric(12,4),
   met_tiempo_corrida_curvas numeric(12,4),
   met_num_inyeccion_estandar_1 int,
   met_num_inyeccion_estandar_2 int,
   met_num_inyeccion_estandar_3 int,
   met_num_inyeccion_estandar_4 int,
   met_num_inyeccion_estandar_5 int,
   met_num_inyeccion_estandar_6 int,
   met_num_inyeccion_estandar_7 int,
   met_num_inyeccion_estandar_8 int,
   met_fecha_registro_sistema datetime,
   met_num_inyec_x_mu_producto integer,
   met_num_inyec_x_mu_estabilidad integer,
   met_num_inyec_x_mu_materia_pri integer,
   met_num_inyec_x_mu_pureza integer,
   met_num_inyec_x_mu_disolucion integer,
   met_num_inyec_x_mu_uniformidad integer,
   met_numero_inyeccion_estandar int,
   met_usu_crea         int,
   met_usu_actualiza    int,
   met_fecha_actualizacion datetime,
   met_eliminado        smallint,
   met_causa_eliminacion varchar(250),
   met_causa_actualizacion varchar(250),
   met_tc_producto_terminado numeric(12,4),
   met_tc_estabilidad   numeric(12,4),
   met_tc_materia_prima numeric(12,4),
   met_tc_pureza        numeric(12,4),
   met_tc_disolucion    numeric(12,4),
   met_tc_uniformidad   numeric(12,4),
   primary key (met_codigo),
   constraint FK_reference_27 foreign key (met_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_28 foreign key (met_usu_actualiza)
      references usuario (usu_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: registro_uso_maquina                                  */
/*==============================================================*/
create table registro_uso_maquina
(
   rum_codigo           int not null auto_increment,
   rum_maq_codigo       int,
   rum_met_codigo       int,
   rum_usu_codigo       int,
   rum_usu_codigo_elimino int,
   rum_hora_inicio_trabajo TIME,
   rum_hora_fin_trabajo TIME,
   rum_tiempo_entre_modelo TIME,
   rum_tiempo_cambio_modelo numeric(12,4),
   rum_tiempo_corrida_sistema numeric(12,4),
   rum_tiempo_corrida_curvas numeric(12,4),
   rum_tiempo_corrida_sistema_est numeric(12,4),
   rum_tiempo_corrida_curvas_esta numeric(12,4),
   rum_num_inyeccion_estandar_per numeric(12,4),
   rum_numero_inyeccion_estandar numeric(12,4),
   rum_tiempo_preparacion numeric(12,4),
   rum_tiempo_duracion_ciclo TIME,
   rum_numero_corridas  int,
   rum_numero_inyeccion_estandar1 numeric(12,4),
   rum_numero_inyeccion_estandar2 numeric(12,4),
   rum_numero_inyeccion_estandar3 numeric(12,4),
   rum_numero_inyeccion_estandar4 numeric(12,4),
   rum_numero_inyeccion_estandar5 numeric(12,4),
   rum_numero_inyeccion_estandar6 numeric(12,4),
   rum_numero_inyeccion_estandar7 numeric(12,4),
   rum_numero_inyeccion_estandar8 numeric(12,4),
   rum_num_inyeccion_estandar1_pe numeric(12,4),
   rum_num_inyeccion_estandar2_pe numeric(12,4),
   rum_num_inyeccion_estandar3_pe numeric(12,4),
   rum_num_inyeccion_estandar4_pe numeric(12,4),
   rum_num_inyeccion_estandar5_pe numeric(12,4),
   rum_num_inyeccion_estandar6_pe numeric(12,4),
   rum_num_inyeccion_estandar7_pe numeric(12,4),
   rum_num_inyeccion_estandar8_pe numeric(12,4),
   rum_num_muestras_producto numeric(12,4),
   rum_num_muestras_estabilidad numeric(12,4),
   rum_num_muestras_materia_prima numeric(12,4),
   rum_num_muestras_pureza numeric(12,4),
   rum_num_muestras_disolucion numeric(12,4),
   rum_num_muestras_uniformidad numeric(12,4),
   rum_num_mu_producto_perdida numeric(12,4),
   rum_num_mu_estabilidad_perdida numeric(12,4),
   rum_num_mu_materia_prima_perdi numeric(12,4),
   rum_num_muestras_pureza_perdid numeric(12,4),
   rum_num_muestras_disolucion_pe numeric(12,4),
   rum_num_muestras_uniformidad_p numeric(12,4),
   rum_num_inyec_x_muestra_estabi numeric(12,4),
   rum_num_inyec_x_muestra_produc numeric(12,4),
   rum_num_inyec_x_muestra_materi numeric(12,4),
   rum_num_inyec_x_muestra_pureza numeric(12,4),
   rum_num_inyec_x_muestra_disolu numeric(12,4),
   rum_num_inyec_x_muestra_unifor numeric(12,4),
   rum_num_inyec_x_mu_estabi_perd numeric(12,4),
   rum_num_inyec_x_mu_produc_perd numeric(12,4),
   rum_num_inyec_x_mu_materi_perd numeric(12,4),
   rum_num_inyec_x_mu_pureza_perd numeric(12,4),
   rum_num_inyec_x_mu_disolu_perd numeric(12,4),
   rum_num_inyec_x_mu_unifor_perd numeric(12,4),
   rum_fallas           numeric(12,4),
   rum_observaciones    varchar(300),
   rum_fecha            date,
   rum_fecha_hora_reg_sistema datetime,
   rum_causa_eliminacion varchar(300),
   rum_fecha_hora_elim_sistema datetime,
   rum_eliminado        boolean,
   rum_tc_producto_terminado numeric(12,4),
   rum_tc_estabilidad   numeric(12,4),
   rum_tc_materia_prima numeric(12,4),
   rum_tc_pureza        numeric(12,4),
   rum_tc_disolucion    numeric(12,4),
   rum_tc_uniformidad   numeric(12,4),
   rum_tc_producto_terminado_esta numeric(12,4),
   rum_tc_estabilidad_estandar numeric(12,4),
   rum_tc_materia_prima_estandar numeric(12,4),
   rum_tc_pureza_estandar numeric(12,4),
   rum_tc_disolucion_estandar numeric(12,4),
   rum_tc_uniformidad_estandar numeric(12,4),
   primary key (rum_codigo),
   constraint FK_reference_22 foreign key (rum_usu_codigo_elimino)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_6 foreign key (rum_maq_codigo)
      references maquina (maq_codigo) on delete restrict on update restrict,
   constraint FK_reference_7 foreign key (rum_met_codigo)
      references metodo (met_codigo) on delete restrict on update restrict,
   constraint FK_reference_8 foreign key (rum_usu_codigo)
      references usuario (usu_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: evento_en_registro                                    */
/*==============================================================*/
create table evento_en_registro
(
   evrg_codigo          int not null auto_increment,
   evrg_rum_codigo      int,
   evrg_eve_codigo      int,
   evrg_observaciones   varchar(200),
   evrg_hora_ocurrio    TIME,
   evrg_hora_registro   TIME,
   evrg_fecha_registro_sistema datetime,
   evrg_usu_crea        int,
   evrg_usu_actualiza   int,
   evrg_fecha_actualizacion datetime,
   evrg_eliminado       smallint,
   evrg_causa_eliminacion varchar(250),
   evrg_causa_actualizacion varchar(250),
   evrg_duracion        numeric(10,4),
   primary key (evrg_codigo),
   constraint FK_reference_49 foreign key (evrg_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_50 foreign key (evrg_usu_actualiza)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_ref_eventoenregistro_registrouso foreign key (evrg_rum_codigo)
      references registro_uso_maquina (rum_codigo) on delete restrict on update restrict,
   constraint FK_ref_eventoenregistro_evento foreign key (evrg_eve_codigo)
      references evento (eve_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: evento_por_categoria                                  */
/*==============================================================*/
create table evento_por_categoria
(
   evca_cat_codigo      int not null,
   evca_eve_codigo      int not null,
   evca_usu_crea        int,
   evca_fecha_registro_sistema datetime,
   primary key (evca_cat_codigo, evca_eve_codigo),
   constraint FK_reference_51 foreign key (evca_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_23 foreign key (evca_cat_codigo)
      references categoria_evento (cat_codigo) on delete restrict on update restrict,
   constraint FK_reference_24 foreign key (evca_eve_codigo)
      references evento (eve_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: indicador                                             */
/*==============================================================*/
create table indicador
(
   ind_codigo           int not null auto_increment,
   ind_sigla            varchar(30),
   ind_nombre           varchar(200),
   ind_fecha_registro_sistema datetime,
   ind_unidad           varchar(20),
   ind_usu_crea         int,
   ind_usu_actualiza    int,
   ind_fecha_actualizacion datetime,
   ind_eliminado        smallint,
   ind_causa_eliminacion varchar(250),
   ind_causa_actualizacion varchar(250),
   primary key (ind_codigo),
   constraint FK_reference_39 foreign key (ind_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_40 foreign key (ind_usu_actualiza)
      references usuario (usu_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: meta_anual_x_indicador                                */
/*==============================================================*/
create table meta_anual_x_indicador
(
   mea_ind_codigo       int not null,
   mea_emp_codigo       int not null,
   mea_anio             int not null,
   mea_valor            numeric(8,2),
   mea_fecha_registro_sistema datetime,
   mea_usu_crea         int,
   mea_usu_actualiza    int,
   mea_fecha_actualizacion datetime,
   mea_eliminado        smallint,
   mea_causa_eliminacion varchar(250),
   mea_causa_actualizacion varchar(250),
   primary key (mea_ind_codigo, mea_emp_codigo, mea_anio),
   constraint FK_reference_45 foreign key (mea_usu_crea)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_46 foreign key (mea_usu_actualiza)
      references usuario (usu_codigo) on delete restrict on update restrict,
   constraint FK_reference_14 foreign key (mea_ind_codigo)
      references indicador (ind_codigo) on delete restrict on update restrict,
   constraint FK_reference_16 foreign key (mea_emp_codigo)
      references empresa (emp_codigo) on delete restrict on update restrict
)
;

/*==============================================================*/
/* Table: registro_modificacion                                 */
/*==============================================================*/
create table registro_modificacion
(
   rem_id               int not null auto_increment,
   rem_rum_codigo       int,
   rem_usu_codigo       int,
   rem_fecha_hora       datetime,
   rem_nombre_campo     varchar(255),
   rem_valor_antiguo    varchar(255),
   rem_valor_nuevo      varchar(255),
   rem_causa            varchar(255),
   primary key (rem_id),
   constraint FK_reference_25 foreign key (rem_rum_codigo)
      references registro_uso_maquina (rum_codigo) on delete restrict on update restrict,
   constraint FK_reference_26 foreign key (rem_usu_codigo)
      references usuario (usu_codigo) on delete restrict on update restrict
)
;

insert into perfil (per_codigo,per_nombre,per_fecha_registro_sistema,per_fecha_actualizacion,per_eliminado) values (1,"Super Administrador",now(),now(),0);
insert into perfil (per_codigo,per_nombre,per_fecha_registro_sistema,per_fecha_actualizacion,per_eliminado) values (2,"Administrador",now(),now(),0);
insert into perfil (per_codigo,per_nombre,per_fecha_registro_sistema,per_fecha_actualizacion,per_eliminado) values (3,"Analista",now(),now(),0);
insert into perfil (per_codigo,per_nombre,per_fecha_registro_sistema,per_fecha_actualizacion,per_eliminado) values (4,"Coordinador o Supervisor",now(),now(),0);

insert into usuario (usu_codigo,usu_login,usu_password,usu_per_codigo,usu_habilitado,usu_fecha_registro_sistema) values (1,'quantar',md5('quantar'),1,1,now());

insert into empresa (emp_codigo, emp_nombre, emp_nit, emp_fecha_limite_licencia, emp_fecha_inicio_licencia, emp_inyect_estandar_promedio, emp_tiempo_alerta_consumible, emp_fecha_registro_sistema,emp_fecha_actualizacion,emp_eliminado,emp_usu_crea,emp_usu_actualiza) VALUES ( '1', 'Nombre Empresa Ltda.', '123456.1', '2017-12-02', '2010-12-18', '6', '4', now(),now(),0,1,1);

insert into tipo_identificacion (tid_codigo,tid_nombre,tid_fecha_registro_sistema,tid_fecha_actualizacion,tid_eliminado,tid_usu_crea,tid_usu_actualiza) values (1,"Cédula de ciudadanía",now(),now(),0,1,1);
insert into tipo_identificacion (tid_codigo,tid_nombre,tid_fecha_registro_sistema,tid_fecha_actualizacion,tid_eliminado,tid_usu_crea,tid_usu_actualiza) values (2,"Cédula de extranjería",now(),now(),0,1,1);
insert into tipo_identificacion (tid_codigo,tid_nombre,tid_fecha_registro_sistema,tid_fecha_actualizacion,tid_eliminado,tid_usu_crea,tid_usu_actualiza) values (3,"Tarjeta de identidad",now(),now(),0,1,1);

insert into estado (est_codigo,est_nombre,est_fecha_registro_sistema,est_fecha_actualizacion,est_eliminado,est_usu_crea,est_usu_actualiza) values (1,"Bueno",now(),now(),0,1,1);
insert into estado (est_codigo,est_nombre,est_fecha_registro_sistema,est_fecha_actualizacion,est_eliminado,est_usu_crea,est_usu_actualiza) values (2,"Dado de baja",now(),now(),0,1,1);

insert into tipo_periodo (tp_codigo,tp_nombre,tp_fecha_registro_sistema,tp_fecha_actualizacion,tp_eliminado,tp_usu_crea,tp_usu_actualiza) values (1,"Día",now(),now(),0,1,1);
insert into tipo_periodo (tp_codigo,tp_nombre,tp_fecha_registro_sistema,tp_fecha_actualizacion,tp_eliminado,tp_usu_crea,tp_usu_actualiza) values (2,"Mes",now(),now(),0,1,1);

INSERT INTO `maquina` (`maq_codigo`, `maq_est_codigo`, `maq_nombre`, `maq_marca`, `maq_modelo`, `maq_fecha_adquisicion`, `maq_foto_url`, `maq_tiempo_inyeccion`, `maq_fecha_registro_sistema`, `maq_codigo_inventario`, `maq_usu_crea`, `maq_usu_actualiza`, `maq_fecha_actualizacion`, `maq_eliminado`, `maq_causa_eliminacion`,  `maq_indicadores`) VALUES
(1,1, 'HPLC Q-EL-004', 'HEWLLET PACKARD', 'Rop-34AGILENT 1100', '2011-01-27', NULL, '0.5800', '2011-01-27 12:35:38', 'Q-EL-004',1,1, '2011-01-27 21:49:06',0, NULL, 1),
(2,1, 'HPLC Q-EL-005', 'SHIMADZU', 'LC2010-AHT', '2011-01-27', NULL, '0.5800', '2011-01-27 12:35:38', 'Q-EL-005',1,1, '2011-01-27 21:50:00',0, NULL, 1),
(3,1, 'HPLC Q-EL-006', 'SHIMADZU', 'LC2010-AHT', '2011-01-20', NULL, '0.5800', '2011-01-27 18:32:03', 'Q-EL-006',1,1, '2011-01-27 21:50:12',0, NULL, 0);

insert into categoria_evento (cat_codigo,cat_nombre,cat_fecha_registro_sistema,cat_fecha_actualizacion,cat_eliminado,cat_usu_crea,cat_usu_actualiza) values(1,'Problemas de Presión',now(),now(),0,1,1); 
insert into categoria_evento (cat_codigo,cat_nombre,cat_fecha_registro_sistema,cat_fecha_actualizacion,cat_eliminado,cat_usu_crea,cat_usu_actualiza) values(2,'Problemas con Fugas',now(),now(),0,1,1);
insert into categoria_evento (cat_codigo,cat_nombre,cat_fecha_registro_sistema,cat_fecha_actualizacion,cat_eliminado,cat_usu_crea,cat_usu_actualiza) values(3,'Problemas con la Retención de Picos',now(),now(),0,1,1);
insert into categoria_evento (cat_codigo,cat_nombre,cat_fecha_registro_sistema,cat_fecha_actualizacion,cat_eliminado,cat_usu_crea,cat_usu_actualiza) values(4,'Problemas con la forma de los picos',now(),now(),0,1,1);
insert into categoria_evento (cat_codigo,cat_nombre,cat_fecha_registro_sistema,cat_fecha_actualizacion,cat_eliminado,cat_usu_crea,cat_usu_actualiza) values(5,'Problemas con la Línea Base',now(),now(),0,1,1);
insert into categoria_evento (cat_codigo,cat_nombre,cat_fecha_registro_sistema,cat_fecha_actualizacion,cat_eliminado,cat_usu_crea,cat_usu_actualiza) values(6,'Problemas con el Automuestreador',now(),now(),0,1,1);
insert into categoria_evento (cat_codigo,cat_nombre,cat_fecha_registro_sistema,cat_fecha_actualizacion,cat_eliminado,cat_usu_crea,cat_usu_actualiza) values(7,'Problemas con el equipo Controlador',now(),now(),0,1,1);
insert into categoria_evento (cat_codigo,cat_nombre,cat_fecha_registro_sistema,cat_fecha_actualizacion,cat_eliminado,cat_usu_crea,cat_usu_actualiza) values(8,'Problemas Generales',now(),now(),0,1,1);

insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(1,'Presión del sistema alta', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(2,'Presión del sistema baja', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(3,'Presión inestable', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(4,'Otros', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(5,'Fuga en la entrada/salida de la columna', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(6,'Fuga en la entrada al detector', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(7,'Fuga en la entrada/salida del filtro en línea', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(8,'Fuga en la entrada/salida de las bombas', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(9,'Fuga en el asiento del inyector', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(10,'Fuga en el inyector', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(11,'Falta reproducibilidad en los tiempos de retención de picos', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(12,'Aumento en el tiempo de retención de picos', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(13,'Disminución en el tiempo de retención de picos', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(14,'Pérdida de resolución de picos', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(15,'Picos anchos', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(16,'Picos Fantasma', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(17,'Picos negativos', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(18,'Picos divididos', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(19,'Picos con cola', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(20,'Picos con frente', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(21,'Picos cortados en la punta', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(22,'Picos cortados en la base', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(23,'Línea base con ruido', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(24,'Línea base espigada', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(25,'Línea base variable', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(26,'Atascamiento del automuestreador', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(27,'Mal posicionamiento del automuestreador', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(28,'El equipo no reconoce la bandeja de muestras', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(29,'El computador se bloquea', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(30,'El computador no carga el software', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(31,'El software se bloquea', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(32,'El software se interrumpe en medio de una corrida analítica', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(33,'Uso de fase móvil no correspondiente con el método', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(34,'Se carga  metodología de análisis no correspondiente', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(35,'Se utiliza columna no correspondiente con el método', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(36,'Inadecuada preparación de la fase móvil', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(37,'Inadecuada preparación de soluciones estándar', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(38,'Inadecuada preparación de solución test', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(39,'Inadecuada preparación de muestra', now(),now(),0,1,1);
insert into evento (eve_codigo,eve_nombre,eve_fecha_registro_sistema,eve_fecha_actualizacion,eve_eliminado,eve_usu_crea,eve_usu_actualiza) values(40,'Ubicación errónea de muestras en automuestreador', now(),now(),0,1,1);

insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(1,1,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(2,1,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(3,1,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(4,1,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(5,2,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(6,2,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(7,2,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(8,2,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(9,2,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(10,2,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(4,2,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(11,3,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(12,3,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(13,3,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(14,3,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(4,3,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(15,4,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(16,4,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(17,4,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(18,4,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(19,4,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(20,4,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(21,4,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(22,4,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(4,4,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(23,5,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(24,5,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(25,5,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(4,5,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(26,6,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(27,6,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(28,6,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(4,6,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(29,7,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(30,7,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(31,7,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(32,7,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(4,7,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(33,8,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(34,8,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(35,8,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(36,8,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(37,8,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(38,8,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(39,8,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(40,8,now(),1);
insert into evento_por_categoria (evca_eve_codigo,evca_cat_codigo,evca_fecha_registro_sistema,evca_usu_crea) values(4,8,now(),1);

insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (1,'TP','Tiempo Programado','Hrs',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (2,'TNP','Tiempo No Programado','Hrs',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (3,'TPP','Tiempo Parada Programada','Hrs',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (4,'TPNP','Tiempo Paradas No Programadas','Hrs',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (5,'TF','Tiempo Funcionamiento','Hrs',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (6,'TO','Tiempo Operativo','Hrs',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (7,'D','Disponibilidad','%',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (8,'E','Eficiencia','%',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (9,'C','Calidad','%',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (10,'A','Aprovechamiento','%',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (11,'OEE','Efectividad Global del Equipo','%',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (12,'Fallas','Fallas','%',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (13,'Paros','Paros menores /Reajustes','%',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (14,'Retrabajos','Retrabajos','%',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (15,'Perdida_rendimiento','Pérdida de rendimiento','%',now(),now(),0,1,1);
insert into indicador (ind_codigo,ind_sigla,ind_nombre,ind_unidad,ind_fecha_registro_sistema,ind_fecha_actualizacion,ind_eliminado,ind_usu_crea,ind_usu_actualiza) values (16,'PTEE','productividad Global del Equipo','%',now(),now(),0,1,1);

INSERT INTO `metodo` (`met_codigo`, `met_nombre`, `met_tiempo_alistamiento`, `met_tiempo_acondicionamiento`, `met_tiempo_estabilizacion`, `met_tiempo_estandar`, `met_tiempo_corrida_sistema`, `met_tiempo_corrida_curvas`, `met_num_inyeccion_estandar_1`, `met_num_inyeccion_estandar_2`, `met_num_inyeccion_estandar_3`, `met_num_inyeccion_estandar_4`, `met_num_inyeccion_estandar_5`, `met_num_inyeccion_estandar_6`, `met_num_inyeccion_estandar_7`, `met_num_inyeccion_estandar_8`, `met_fecha_registro_sistema`, `met_num_inyec_x_mu_producto`, `met_num_inyec_x_mu_estabilidad`, `met_num_inyec_x_mu_materia_pri`, `met_num_inyec_x_mu_pureza`, `met_num_inyec_x_mu_disolucion`, `met_num_inyec_x_mu_uniformidad`, `met_numero_inyeccion_estandar`, `met_usu_crea`, `met_usu_actualiza`, `met_fecha_actualizacion`, `met_eliminado`, `met_causa_eliminacion`, `met_causa_actualizacion`, `met_tc_producto_terminado`, `met_tc_estabilidad`, `met_tc_materia_prima`, `met_tc_pureza`, `met_tc_disolucion`, `met_tc_uniformidad`) VALUES
(1, 'Praziquantel', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '4.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-10 16:03:48', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2013-05-08 11:35:13', 0, 'ENSAYO', '', '4.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(2, 'Ivermectina', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '10.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-13 15:15:27', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-10-01 09:21:57', 0, NULL, '', '10.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(3, 'Ibuprofeno Limite Compuestos Relacionados C ', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '13.0000', 2, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-14 13:34:51', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-14 13:34:51', 0, NULL, '', '13.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(4, 'Albendazol', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '9.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:38:53', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 08:38:53', 0, NULL, '', '9.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(5, 'Albendazol EDOPetit Purga', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '17.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:40:18', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 08:40:18', 0, NULL, '', '17.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(6, 'ATP', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '6.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:42:06', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-11-26 11:27:02', 0, NULL, '', '10.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(7, 'Boldenona Undecilato', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '9.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:42:58', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 08:42:58', 0, NULL, '', '9.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(8, 'Dipirona Sódica', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '8.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:44:29', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 08:44:29', 0, NULL, '', '8.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(9, 'Doramectina', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '6.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:45:54', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 08:45:54', 0, NULL, '', '7.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(10, 'Enrofloxacino HCl', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '11.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:47:25', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-11-13 10:50:15', 0, NULL, '', '11.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(11, 'Fenbendazol', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '6.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:49:37', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 08:49:37', 0, NULL, '', '6.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(12, 'Fenilbutazona Sodica', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '5.5000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:50:39', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 08:50:39', 0, NULL, '', '5.5000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(13, 'Glucosamina Sulfato Derivatización', '30.0000', '30.0000', '0.0000', '60.0000', '0.0000', '18.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:51:44', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 08:51:56', 0, NULL, '', '18.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(14, 'Oxitetraciclina HCl', '15.0000', '20.0000', '0.0000', '35.0000', '0.0000', '8.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:54:07', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 08:54:07', 0, NULL, '', '8.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(15, 'Oxitetraciclina HCl EDOAntripan', '15.0000', '20.0000', '0.0000', '35.0000', '0.0000', '6.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:55:18', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-11-06 11:54:54', 0, NULL, '', '6.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(16, 'Pamoato de Pirantel', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '5.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 08:56:26', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 08:56:26', 0, NULL, '', '5.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(17, 'Vitaminas Hidrosolubles Otros', '15.0000', '45.0000', '0.0000', '60.0000', '0.0000', '15.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 11:27:25', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 14:14:11', 0, NULL, '', '15.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(18, 'Vitaminas Hidrosolubles EDOBonapel', '15.0000', '45.0000', '0.0000', '60.0000', '0.0000', '15.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 11:28:59', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 14:13:39', 0, NULL, '', '18.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(19, 'Vitaminas Liposolubles D y E', '15.0000', '45.0000', '0.0000', '60.0000', '0.0000', '18.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 11:32:32', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 14:14:53', 0, NULL, '', '18.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(20, 'Vitaminas Liposolubles D y E Farvical', '15.0000', '30.0000', '0.0000', '45.0000', '0.0000', '13.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 11:33:18', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 14:24:24', 0, NULL, '', '13.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(21, 'Aminoacidos ', '15.0000', '135.0000', '0.0000', '150.0000', '0.0000', '45.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 11:37:10', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 14:21:00', 0, NULL, '', '45.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(22, 'Aminoacidos (Taurina)', '15.0000', '20.0000', '0.0000', '35.0000', '0.0000', '7.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 11:38:13', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 14:22:48', 0, NULL, '', '7.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(23, 'AcidosOrgánicos', '15.0000', '30.0000', '0.0000', '45.0000', '0.0000', '15.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 14:29:08', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-10-01 09:21:17', 0, NULL, '', '15.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(24, 'Triclosan(Irgasan)', '15.0000', '40.0000', '0.0000', '55.0000', '0.0000', '20.0000', 5, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-15 14:33:57', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-15 14:33:57', 0, NULL, '', '20.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(25, 'Trazas de Ivermectina', '30.0000', '0.0000', '0.0000', '30.0000', '0.0000', '10.0000', 1, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-27 09:40:49', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-27 09:40:49', 0, NULL, '', '10.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(26, 'Fallo', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', 0, 0, 0, 0, 0, 0, NULL, NULL, '2012-08-29 15:09:37', 0, 0, 0, 0, 0, 0, 0, 1, 1, '2012-08-29 15:09:37', 0, NULL, '', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000'),
(27, 'Impurezas Comunes ', '15.0000', '0.0000', '0.0000', '15.0000', '0.0000', '15.0000', 1, 0, 0, 0, 0, 0, NULL, NULL, '2012-12-11 15:09:28', 0, 0, 2, 0, 0, 0, 0, 1, 1, '2012-12-11 15:14:11', 0, NULL, '', '0.0000', '0.0000', '15.0000', '0.0000', '0.0000', '0.0000'),
(28, 'Trazas Boldenona Undecilato', '15.0000', '15.0000', '0.0000', '30.0000', '0.0000', '9.0000', 1, 0, 0, 0, 0, 0, NULL, NULL, '2013-01-28 17:29:05', 1, 0, 0, 0, 0, 0, 0, 1, 1, '2013-01-28 17:29:48', 0, NULL, '', '9.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000');