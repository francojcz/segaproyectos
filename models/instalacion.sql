INSERT INTO perfil (per_codigo,per_nombre) VALUES (1,"Administrador");
INSERT INTO perfil (per_codigo,per_nombre) VALUES (2,"Coordinador");

INSERT INTO estadoproyecto (est_pro_codigo,est_pro_nombre) VALUES (1,"Activo");
INSERT INTO estadoproyecto (est_pro_codigo,est_pro_nombre) VALUES (2,"Prorrogado");
INSERT INTO estadoproyecto (est_pro_codigo,est_pro_nombre) VALUES (3,"Reanudado");
INSERT INTO estadoproyecto (est_pro_codigo,est_pro_nombre) VALUES (4,"Suspendido");
INSERT INTO estadoproyecto (est_pro_codigo,est_pro_nombre) VALUES (5,"Terminado");

INSERT INTO ejecutorproyecto (eje_pro_codigo,eje_pro_nombre) VALUES (1,"Fundación Cinara");
INSERT INTO ejecutorproyecto (eje_pro_codigo,eje_pro_nombre) VALUES (2,"Instituto Cinara");

INSERT INTO tipoproyecto (tipp_codigo,tipp_nombre) VALUES (1,"Consultoría");
INSERT INTO tipoproyecto (tipp_codigo,tipp_nombre) VALUES (2,"Contrato");
INSERT INTO tipoproyecto (tipp_codigo,tipp_nombre) VALUES (3,"Convenio");
INSERT INTO tipoproyecto (tipp_codigo,tipp_nombre) VALUES (4,"Otro");

INSERT INTO estadoproducto (est_prod_codigo,est_prod_nombre) VALUES (1,"Pendiente");
INSERT INTO estadoproducto (est_prod_codigo,est_prod_nombre) VALUES (2,"Entregado");

INSERT INTO tipoorganizacion (tipo_codigo,tipo_nombre) VALUES (1,"Privada Nacional");
INSERT INTO tipoorganizacion (tipo_codigo,tipo_nombre) VALUES (2,"Privada Internacional");
INSERT INTO tipoorganizacion (tipo_codigo,tipo_nombre) VALUES (3,"Pública Nacional");
INSERT INTO tipoorganizacion (tipo_codigo,tipo_nombre) VALUES (4,"Pública Internacional");

INSERT INTO tipodocumento (tipd_codigo,tipd_nombre) VALUES (1,"Acta de Inicio");
INSERT INTO tipodocumento (tipd_codigo,tipd_nombre) VALUES (2,"Acta de Reinicio");
INSERT INTO tipodocumento (tipd_codigo,tipd_nombre) VALUES (3,"Acta de Suspensión");
INSERT INTO tipodocumento (tipd_codigo,tipd_nombre) VALUES (4,"Acta de Terminación");
INSERT INTO tipodocumento (tipd_codigo,tipd_nombre) VALUES (5,"Contrato");
INSERT INTO tipodocumento (tipd_codigo,tipd_nombre) VALUES (6,"Factura");
INSERT INTO tipodocumento (tipd_codigo,tipd_nombre) VALUES (7,"Póliza");
INSERT INTO tipodocumento (tipd_codigo,tipd_nombre) VALUES (8,"Otro");

INSERT INTO persona (pers_codigo,pers_nombres,pers_apellidos,pers_numero_identificacion,pers_cargo,pers_correo,pers_telefono,pers_celular,pers_fecha_registro,pers_eliminado,pers_usu_codigo)
 VALUES (1,'Cinara','Cinara','15972248','Administrador','admin@univalle.edu.co','8548961','3127584787',now(),0,1);

INSERT INTO usuario (usu_codigo,usu_login,usu_contrasena,usu_per_codigo,usu_habilitado,usu_fecha_registro,usu_pers_codigo)
 VALUES (1,'administrador','administrador',1,1,now(),1);

INSERT INTO concepto (con_codigo, con_nombre, con_fecha_registro, con_usu_codigo)
VALUES (1, "Impuestos", now(), 1);
INSERT INTO concepto (con_codigo, con_nombre, con_fecha_registro, con_usu_codigo)
VALUES (2, "Papelería", now(), 1);
INSERT INTO concepto (con_codigo, con_nombre, con_fecha_registro, con_usu_codigo)
VALUES (3, "Depreciaciones", now(), 1);
INSERT INTO concepto (con_codigo, con_nombre, con_fecha_registro, con_usu_codigo)
VALUES (4, "Arrendamiento", now(), 1);