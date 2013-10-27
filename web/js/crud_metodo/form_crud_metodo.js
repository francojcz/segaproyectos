/*
 crud metodos - tpm labs
 Desarrollado maryit sanchez
 2010
 
 */
/*cambio de campos
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
 met_num_inyec_x_mu_estandar integer,
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
 met_tiempo_corrida_muestra numeric(12,4),
 */
var ayuda_met_codigo = '';
var ayuda_met_nombre = 'Escriba el nombre del método';

/*eliminados en la version 1.1 maryit
 var  ayuda_met_cambiar_puente_x_columna='';
 var  ayuda_met_lavar_equipo_ana_eluente='Cuanto se demora en lavar de equipo con eluente'; //tipo
 var  ayuda_met_purgar_sistema_con_eluente='Cuanto se demora en purgar el sistema con eluente';
 var  ayuda_met_estabilizar_sis_fase_movil='Cuanto se demora en estabilizar el sistema';
 var  ayuda_met_lavar_sistema_fase_movil='Cuanto se demora en lavar el sistema  fase movil';
 var  ayuda_met_lavar_sistema_con_eluente='Cuanto se demora en lavar el sistema con eluente';
 var  ayuda_met_purgar_sistema_sln_almacen='Cuanto se demora en purgar sistema con sln. Almacenamiento columna';
 var  ayuda_met_cambiar_columna_x_puente='Cuanto se demora en cambiar columna por puente';
 var  ayuda_met_lavar_equipo_pos_eluente='Cuanto se demora en lavar de equipo con eluente despues del analisis';
 
 */
//agregado en la version 1.1
var ayuda_met_tiempo_alistamiento = 'Tiempo de alistamiento';
var ayuda_met_tiempo_acondicionamiento = 'Tiempo de acondicionamiento'; //tipo
var ayuda_met_tiempo_estabilizacion = 'Tiempo de empaque';
//end
var ayuda_met_tiempo_estandar = 'Tiempo estándar alistamiento sistema es la suma de la información de analisis y postanalisis';

//var  ayuda_met_tiempo_corrida_muestra='Tiempo de corrida para muestras'; aliminado en version 1.1 ahora hay un tc para cada tipo de muestra
var ayuda_met_tiempo_corrida_sistema = 'Tiempo de corrida solucion System';
var ayuda_met_tiempo_corrida_curvas = 'Tiempo de corrida para estandares (niveles de calibración)';
var ayuda_met_numero_inyeccion_estandar = 'Cuantas inyecciones se realizan con el sistema(system suitability)';
var ayuda_met_num_inyeccion_estandar_1 = 'Cuantas inyecciones se realizan con el estandar 1 ';
var ayuda_met_num_inyeccion_estandar_2 = 'Cuantas inyecciones se realizan con el estandar 2 ';
var ayuda_met_num_inyeccion_estandar_3 = 'Cuantas inyecciones se realizan con el estandar 3 ';
var ayuda_met_num_inyeccion_estandar_4 = 'Cuantas inyecciones se realizan con el estandar 4 ';
var ayuda_met_num_inyeccion_estandar_5 = 'Cuantas inyecciones se realizan con el estandar 5 ';
var ayuda_met_num_inyeccion_estandar_6 = 'Cuantas inyecciones se realizan con el estandar 6 ';
var ayuda_met_num_inyeccion_estandar_7 = 'Cuantas inyecciones se realizan con el estandar 7 ';
var ayuda_met_num_inyeccion_estandar_8 = 'Cuantas inyecciones se realizan con el estandar 8';

var ayuda_met_num_inyec_x_mu_producto = 'Número de inyecciones por muestras de producto terminado';
var ayuda_met_num_inyec_x_mu_estabilidad = 'Número de inyecciones por muestras de estabilidad';
var ayuda_met_num_inyec_x_mu_materi_pri = 'Número de inyecciones por muestras de materia prima';
//agregado en la version 1.1
var ayuda_met_num_inyec_x_mu_pureza = 'Número de inyecciones por muestras de pureza';
var ayuda_met_num_inyec_x_mu_disolucion = 'Número de inyecciones por muestras de disolución';
var ayuda_met_num_inyec_x_mu_uniformidad = 'Número de inyecciones por muestras de uniformidad';
//end
// var ayuda_met_num_inyec_x_mu_estandar = 'Número de inyecciones por muestras estandar';
//--var ayuda_met_tiempo_cambio_modelo='Número de inyecciones  por muestras estandar';

/* agregado en la version 1.1
 met_tc_producto_terminado numeric(12,4),
 met_tc_estabilidad   numeric(12,4),
 met_tc_materia_prima numeric(12,4),
 met_tc_pureza        numeric(12,4),
 met_tc_disolucion    numeric(12,4),
 met_tc_uniformidad   numeric(12,4),
 met_tc_otro          numeric(12,4),
 */
var ayuda_met_tc_producto_terminado = 'Tiempo de corrida en muestras de producto terminado';
var ayuda_met_tc_estabilidad = 'Tiempo de corrida en muestras de estabiliad';
var ayuda_met_tc_materia_prima = 'Tiempo de corrida en muestras de  materia prima';
var ayuda_met_tc_pureza = 'Tiempo de corrida en muestras de pureza';
var ayuda_met_tc_disolucion = 'Tiempo de corrida en muestras de disolución';
var ayuda_met_tc_uniformidad = 'Tiempo de corrida en muestras de uniformidad';

var largo_panel = 500;


var crud_metodo_datastore = new Ext.data.Store({
    id: 'crud_metodo_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('crud_metodo', 'listarMetodo'),
        method: 'POST'
    }),
    baseParams: {
        start: 0,
        limit: 20
    },
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total'
    }, [{
        name: 'met_codigo',
        type: 'int'
    }, {
        name: 'met_nombre',
        type: 'string'
    },    /*			{name: 'met_cambiar_puente_x_columna', type: 'float'},
     {name: 'met_lavar_equipo_ana_eluente', type: 'float'},
     {name: 'met_purgar_sistema_con_eluente', type: 'float'},
     {name: 'met_estabilizar_sis_fase_movil', type: 'float'},
     {name: 'met_lavar_sistema_fase_movil', type: 'float'},
     {name: 'met_lavar_sistema_con_eluente', type: 'float'},
     {name: 'met_purgar_sistema_sln_almacen', type: 'float'},
     {name: 'met_cambiar_columna_x_puente', type: 'float'},
     {name: 'met_lavar_equipo_pos_eluente', type: 'float'},*/
    {
        name: 'met_tiempo_alistamiento',
        type: 'float'
    }, {
        name: 'met_tiempo_acondicionamiento',
        type: 'float'
    }, {
        name: 'met_tiempo_estabilizacion',
        type: 'float'
    }, {
        name: 'met_tiempo_estandar',
        type: 'float'
    }, //{name: 'met_tiempo_corrida_muestra', type: 'float'},
    {
        name: 'met_tiempo_corrida_sistema',
        type: 'float'
    }, {
        name: 'met_tiempo_corrida_curvas',
        type: 'float'
    }, //--{name: 'met_tiempo_cambio_modelo', type: 'float'},
    {
        name: 'met_numero_inyeccion_estandar',
        type: 'int'
    }, {
        name: 'met_num_inyeccion_estandar_1',
        type: 'int'
    }, {
        name: 'met_num_inyeccion_estandar_2',
        type: 'int'
    }, {
        name: 'met_num_inyeccion_estandar_3',
        type: 'int'
    }, {
        name: 'met_num_inyeccion_estandar_4',
        type: 'int'
    }, {
        name: 'met_num_inyeccion_estandar_5',
        type: 'int'
    }, {
        name: 'met_num_inyeccion_estandar_6',
        type: 'int'
    }, {
        name: 'met_num_inyeccion_estandar_7',
        type: 'int'
    }, {
        name: 'met_num_inyeccion_estandar_8',
        type: 'int'
    }, {
        name: 'met_num_inyec_x_mu_producto',
        type: 'int'
    }, {
        name: 'met_num_inyec_x_mu_estabilidad',
        type: 'int'
    }, {
        name: 'met_num_inyec_x_mu_materi_pri',
        type: 'int'
    }, {
        name: 'met_num_inyec_x_mu_pureza',
        type: 'int'
    }, {
        name: 'met_num_inyec_x_mu_disolucion',
        type: 'int'
    }, {
        name: 'met_num_inyec_x_mu_uniformidad',
        type: 'int'
    }, {
        name: 'met_tc_producto_terminado',
        type: 'float'
    }, {
        name: 'met_tc_estabilidad',
        type: 'float'
    }, {
        name: 'met_tc_materia_prima',
        type: 'float'
    }, {
        name: 'met_tc_pureza',
        type: 'float'
    }, {
        name: 'met_tc_disolucion',
        type: 'float'
    }, {
        name: 'met_tc_uniformidad',
        type: 'float'
    }, //end
    {
        name: 'met_fecha_registro_sistema',
        type: 'string'
    }, {
        name: 'met_fecha_actualizacion',
        type: 'string'
    }, {
        name: 'met_usu_crea_nombre',
        type: 'string'
    }, {
        name: 'met_usu_actualiza_nombre',
        type: 'string'
    }, {
        name: 'met_causa_eliminacion',
        type: 'string'
    }, {
        name: 'met_causa_actualizacion',
        type: 'string'
    }])
});
crud_metodo_datastore.load();

var met_codigo = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    maxLength: 100,
    name: 'met_codigo',
    id: 'met_codigo',
    hideLabel: true,
    hidden: true,
    //readOnly:true,
    listeners: {
        'render': function(){
            ayuda('met_codigo', ayuda_met_codigo);
        }
    }
});


var met_nombre = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    maxLength: 100,
    name: 'met_nombre',
    id: 'met_nombre',
    fieldLabel: 'Nombre del m&eacute;todo',
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('met_nombre', ayuda_met_nombre);
        }
    }
});
/*
 var met_lavar_equipo_ana_eluente=new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: 'text-align:right;',
 name: 'met_lavar_equipo_ana_eluente',
 id: 'met_lavar_equipo_ana_eluente',
 fieldLabel: 'Lavar equipo con eluente (Min.)',
 allowDecimals: false,
 allowNegative: false,
 maxValue : '10000000',
 decimalPrecision:'4',
 maxLength:'12',
 value:'0',
 enableKeyEvents: true,
 listeners:
 {
 'render': function() {
 ayuda('met_lavar_equipo_ana_eluente', ayuda_met_lavar_equipo_ana_eluente);
 },
 'keyup': function(){
 crud_metodo_calculartiempoestandar();
 }
 }
 });
 var met_cambiar_puente_x_columna=new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: ' text-align:right;',
 name: 'met_cambiar_puente_x_columna',
 id: 'met_cambiar_puente_x_columna',
 fieldLabel: 'Cambiar puente por columna (Min.)',
 allowDecimals: true,
 allowNegative: false,
 maxValue : '10000000',
 maxLength:'12',
 decimalPrecision:'4',
 value:'0',
 enableKeyEvents: true,
 listeners:
 {
 'render': function() {
 ayuda('met_cambiar_puente_x_columna', ayuda_met_cambiar_puente_x_columna);
 },
 'keyup': function(){
 crud_metodo_calculartiempoestandar();
 }
 }
 });
 var met_purgar_sistema_con_eluente=new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: ' text-align:right;',
 name: 'met_purgar_sistema_con_eluente',
 id: 'met_purgar_sistema_con_eluente',
 fieldLabel: 'Purgar sistema con eluente (Min.)',
 allowDecimals: true,
 allowNegative: false,
 maxValue : '10000000',
 decimalPrecision:'4',
 maxLength:'12',
 value:'0',
 enableKeyEvents: true,
 listeners:
 {
 'render': function() {
 ayuda('met_purgar_sistema_con_eluente', ayuda_met_purgar_sistema_con_eluente);
 },
 'keyup': function(){
 crud_metodo_calculartiempoestandar();
 }
 }
 });
 var met_estabilizar_sis_fase_movil=new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: ' text-align:right;',
 name: 'met_estabilizar_sis_fase_movil',
 id: 'met_estabilizar_sis_fase_movil',
 fieldLabel: 'Estabilizar sistema con Fase Móvil (Min.)',
 allowDecimals: true,
 allowNegative: false,
 maxValue : '10000000',
 decimalPrecision:'4',
 maxLength:'12',
 value:'0',
 enableKeyEvents: true,
 listeners:
 {
 'render': function() {
 ayuda('met_estabilizar_sis_fase_movil', ayuda_met_estabilizar_sis_fase_movil);
 },
 'keyup': function(){
 crud_metodo_calculartiempoestandar();
 }
 }
 });
 
 var met_lavar_sistema_fase_movil=new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: ' text-align:right;',
 name: 'met_lavar_sistema_fase_movil',
 id: 'met_lavar_sistema_fase_movil',
 fieldLabel: 'Lavar sistema con Fase Móvil (Min.)',
 allowDecimals: true,
 allowNegative: false,
 maxValue : '10000000',
 decimalPrecision:'4',
 maxLength:'12',
 value:'0',
 enableKeyEvents: true,
 listeners:
 {
 'render': function() {
 ayuda('met_lavar_sistema_fase_movil', ayuda_met_lavar_sistema_fase_movil);
 },
 'keyup': function(){
 crud_metodo_calculartiempoestandar();
 }
 }
 });
 var met_lavar_sistema_con_eluente=new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: ' text-align:right;',
 name: 'met_lavar_sistema_con_eluente',
 id: 'met_lavar_sistema_con_eluente',
 fieldLabel: 'Lavar sistema con eluente (Min.)',
 allowDecimals: true,
 allowNegative: false,
 maxValue : '10000000',
 decimalPrecision:'4',
 maxLength:'12',
 value:'0',
 enableKeyEvents: true,
 listeners:
 {
 'render': function() {
 ayuda('met_lavar_sistema_con_eluente', ayuda_met_lavar_sistema_con_eluente);
 },
 'keyup': function(){
 crud_metodo_calculartiempoestandar();
 }
 }
 });
 
 
 var met_purgar_sistema_sln_almacen=new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: ' text-align:right;',
 name: 'met_purgar_sistema_sln_almacen',
 id: 'met_purgar_sistema_sln_almacen',
 fieldLabel: 'Purgar sistema con sln. Almacenamiento columna (Min.)',
 allowDecimals: true,
 allowNegative: false,
 maxValue : '10000000',
 decimalPrecision:'4',
 maxLength:'12',
 value:'0',
 enableKeyEvents: true,
 listeners:
 {
 'render': function() {
 ayuda('met_purgar_sistema_sln_almacen', ayuda_met_purgar_sistema_sln_almacen);
 },
 'keyup': function(){
 crud_metodo_calculartiempoestandar();
 }
 }
 });
 
 
 var met_cambiar_columna_x_puente=new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: ' text-align:right;',
 name: 'met_cambiar_columna_x_puente',
 id: 'met_cambiar_columna_x_puente',
 fieldLabel: 'Cambiar columna por puente (Min.)',
 allowDecimals: true,
 allowNegative: false,
 maxValue : '10000000',
 decimalPrecision:'4',
 maxLength:'12',
 value:'0',
 enableKeyEvents: true,
 listeners:
 {
 'render': function() {
 ayuda('met_cambiar_columna_x_puente', ayuda_met_cambiar_columna_x_puente);
 },
 'keyup': function(){
 crud_metodo_calculartiempoestandar();
 }
 }
 });
 
 var met_lavar_equipo_pos_eluente=new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: ' text-align:right;',
 name: 'met_lavar_equipo_pos_eluente',
 id: 'met_lavar_equipo_pos_eluente',
 fieldLabel: 'Lavar equipo con eluente (Min.)',
 allowDecimals: true,
 allowNegative: false,
 maxValue : '10000000',
 decimalPrecision:'4',
 maxLength:'12',
 value:'0',
 enableKeyEvents: true,
 listeners:
 {
 'render': function() {
 ayuda('met_lavar_equipo_pos_eluente', ayuda_met_lavar_equipo_pos_eluente);
 },
 'keyup': function(){
 crud_metodo_calculartiempoestandar();
 }
 }
 });
 */
var met_tiempo_alistamiento = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_tiempo_alistamiento',
    id: 'met_tiempo_alistamiento',
    fieldLabel: 'Tiempo de alistamiento(Min.)',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '10000000',
    decimalPrecision: '4',
    maxLength: '12',
    value: '0',
    enableKeyEvents: true,
    listeners: {
        'render': function(){
            ayuda('met_tiempo_alistamiento', ayuda_met_tiempo_alistamiento);
        },
        'keyup': function(){
            crud_metodo_calculartiempoestandar();
        }
    }
});


var met_tiempo_acondicionamiento = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_tiempo_acondicionamiento',
    id: 'met_tiempo_acondicionamiento',
    fieldLabel: 'Tiempo de acondicionamiento(Min.)',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '10000000',
    decimalPrecision: '4',
    maxLength: '12',
    value: '0',
    enableKeyEvents: true,
    listeners: {
        'render': function(){
            ayuda('met_tiempo_acondicionamiento', ayuda_met_tiempo_acondicionamiento);
        },
        'keyup': function(){
            crud_metodo_calculartiempoestandar();
        }
    }
});


var met_tiempo_estabilizacion = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_tiempo_estabilizacion',
    id: 'met_tiempo_estabilizacion',
    fieldLabel: 'Tiempo de empaque(Min.)',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '10000000',
    decimalPrecision: '4',
    maxLength: '12',
    value: '0',
    enableKeyEvents: true,
    listeners: {
        'render': function(){
            ayuda('met_tiempo_estabilizacion', ayuda_met_tiempo_estabilizacion);
        },
        'keyup': function(){
            crud_metodo_calculartiempoestandar();
        }
    }
});


var met_tiempo_estandar = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'met_tiempo_estandar',
    id: 'met_tiempo_estandar',
    fieldLabel: '<b>Tiempo estándar alistamiento sistema (Min.)</b>',
    allowDecimals: true,
    allowNegative: false,
    maxValue: '100000000',
    decimalPrecision: '4',
    maxLength: '13',
    value: '0',
    readOnly: true,
    listeners: {
        'render': function(){
            ayuda('met_tiempo_estandar', ayuda_met_tiempo_estandar);
        }
    }
});

/*
 var met_tiempo_corrida_muestra=new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: ' text-align:right;',
 name: 'met_tiempo_corrida_muestra',
 id: 'met_tiempo_corrida_muestra',
 fieldLabel: 'Tiempo corrida muestra (Min.)',
 allowDecimals: true,
 allowNegative: false,
 maxValue : '10000000',
 decimalPrecision:'4',
 maxLength:'12',
 value:'0',
 listeners:
 {
 'render': function() {
 ayuda('met_tiempo_corrida_muestra', ayuda_met_tiempo_corrida_muestra);
 }
 }
 });
 */
var met_tiempo_corrida_sistema = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'met_tiempo_corrida_sistema',
    id: 'met_tiempo_corrida_sistema',
    fieldLabel: 'Tiempo an&aacute;lisis sln. Test (Min.)',
    allowDecimals: true,
    allowNegative: false,
    maxValue: '10000000',
    decimalPrecision: '4',
    maxLength: '12',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_tiempo_corrida_sistema', ayuda_met_tiempo_corrida_sistema);
        }
    }
});

var met_tiempo_corrida_curvas = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'met_tiempo_corrida_curvas',
    id: 'met_tiempo_corrida_curvas',
    fieldLabel: 'Tiempo an&aacute;lisis est&aacute;n. calibraci&oacute;n (Min.)',
    allowDecimals: true,
    allowNegative: false,
    maxValue: '99999999',
    decimalPrecision: '4',
    maxLength: '12',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_tiempo_corrida_curvas', ayuda_met_tiempo_corrida_curvas);
        }
    }
});


var met_tc_producto_terminado = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'met_tc_producto_terminado',
    id: 'met_tc_producto_terminado',
    fieldLabel: 'Tiempo corrida de producto terminado(Min.)',
    allowDecimals: true,
    allowNegative: false,
    maxValue: '99999999',
    decimalPrecision: '4',
    maxLength: '12',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_tc_producto_terminado', ayuda_met_tc_producto_terminado);
        }
    }
});

var met_tc_estabilidad = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'met_tc_estabilidad',
    id: 'met_tc_estabilidad',
    fieldLabel: 'Tiempo corrida de muestras de estabilidad(Min.)',
    allowDecimals: true,
    allowNegative: false,
    maxValue: '99999999',
    decimalPrecision: '4',
    maxLength: '12',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_tc_estabilidad', ayuda_met_tc_estabilidad);
        }
    }
});

var met_tc_materia_prima = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'met_tc_materia_prima',
    id: 'met_tc_materia_prima',
    fieldLabel: 'Tiempo corrida de muestras de materia prima(Min.)',
    allowDecimals: true,
    allowNegative: false,
    maxValue: '99999999',
    decimalPrecision: '4',
    maxLength: '12',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_tc_materia_prima', ayuda_met_tc_materia_prima);
        }
    }
});

var met_tc_pureza = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'met_tc_pureza',
    id: 'met_tc_pureza',
    fieldLabel: 'Tiempo corrida de muestras de pureza(Min.)',
    allowDecimals: true,
    allowNegative: false,
    maxValue: '99999999',
    decimalPrecision: '4',
    maxLength: '12',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_tc_pureza', ayuda_met_tc_pureza);
        }
    }
});

var met_tc_disolucion = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'met_tc_disolucion',
    id: 'met_tc_disolucion',
    fieldLabel: 'Tiempo corrida de muestras de disolución(Min.)',
    allowDecimals: true,
    allowNegative: false,
    maxValue: '99999999',
    decimalPrecision: '4',
    maxLength: '12',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_tc_disolucion', ayuda_met_tc_disolucion);
        }
    }
});

var met_tc_uniformidad = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'met_tc_uniformidad',
    id: 'met_tc_uniformidad',
    fieldLabel: 'Tiempo corrida de muestras de uniformidad(Min.)',
    allowDecimals: true,
    allowNegative: false,
    maxValue: '99999999',
    decimalPrecision: '4',
    maxLength: '12',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_tc_uniformidad', ayuda_met_tc_uniformidad);
        }
    }
});

/*
 var met_tiempo_cambio_modelo=new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: ' text-align:right;',
 name: 'met_tiempo_cambio_modelo',
 id: 'met_tiempo_cambio_modelo',
 fieldLabel: 'Tiempo cambio de modelo (Min.)',
 allowDecimals: true,
 allowNegative: false,
 maxValue : '99999999',
 decimalPrecision:'4',
 maxLength:'12',
 value:'0',
 listeners:
 {
 'render': function() {
 ayuda('met_tiempo_cambio_modelo', ayuda_met_tiempo_cambio_modelo);
 }
 }
 });
 */
var met_numero_inyeccion_estandar = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_numero_inyeccion_estandar',
    id: 'met_numero_inyeccion_estandar',
    fieldLabel: 'N&deg;. inyecciones sln. Test',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_numero_inyeccion_estandar', ayuda_met_numero_inyeccion_estandar);
        }
    }
});


var met_num_inyeccion_estandar_1 = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyeccion_estandar_1',
    id: 'met_num_inyeccion_estandar_1',
    fieldLabel: 'N&deg;. inyecciones std 1',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyeccion_estandar_1', ayuda_met_num_inyeccion_estandar_1);
        }
    }
});


var met_num_inyeccion_estandar_2 = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyeccion_estandar_2',
    id: 'met_num_inyeccion_estandar_2',
    fieldLabel: 'N&deg;. inyecciones std 2',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyeccion_estandar_2', ayuda_met_num_inyeccion_estandar_2);
        }
    }
});

var met_num_inyeccion_estandar_3 = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyeccion_estandar_3',
    id: 'met_num_inyeccion_estandar_3',
    fieldLabel: 'N&deg;. inyecciones std  3',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyeccion_estandar_3', ayuda_met_num_inyeccion_estandar_3);
        }
    }
});

var met_num_inyeccion_estandar_4 = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyeccion_estandar_4',
    id: 'met_num_inyeccion_estandar_4',
    fieldLabel: 'N&deg;. inyecciones std 4',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyeccion_estandar_4', ayuda_met_num_inyeccion_estandar_4);
        }
    }
});

var met_num_inyeccion_estandar_5 = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyeccion_estandar_5',
    id: 'met_num_inyeccion_estandar_5',
    fieldLabel: 'N&deg;. inyecciones std 5',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyeccion_estandar_5', ayuda_met_num_inyeccion_estandar_5);
        }
    }
});

var met_num_inyeccion_estandar_6 = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyeccion_estandar_6',
    id: 'met_num_inyeccion_estandar_6',
    fieldLabel: 'N&deg;. inyecciones std 6',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyeccion_estandar_6', ayuda_met_num_inyeccion_estandar_6);
        }
    }
});

var met_num_inyeccion_estandar_7 = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyeccion_estandar_7',
    id: 'met_num_inyeccion_estandar_7',
    fieldLabel: 'N&deg;. inyecciones std 7',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyeccion_estandar_7', ayuda_met_num_inyeccion_estandar_7);
        }
    }
});

var met_num_inyeccion_estandar_8 = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyeccion_estandar_8',
    id: 'met_num_inyeccion_estandar_8',
    fieldLabel: 'N&deg;. inyecciones std 8',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyeccion_estandar_8', ayuda_met_num_inyeccion_estandar_8);
        }
    }
});

var met_fecha_registro_sistema = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    name: 'met_fecha_registro_sistema',
    id: 'met_fecha_registro_sistema',
    fieldLabel: '<html>Fecha registro</html>',
    maxLength: 100,
    disabled: true
});

var met_num_inyec_x_mu_producto = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyec_x_mu_producto',
    id: 'met_num_inyec_x_mu_producto',
    fieldLabel: 'Producto terminado',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyec_x_mu_producto', ayuda_met_num_inyec_x_mu_producto);
        }
    }
});

var met_num_inyec_x_mu_estabilidad = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyec_x_mu_estabilidad',
    id: 'met_num_inyec_x_mu_estabilidad',
    fieldLabel: 'Muestra estabilidad',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyec_x_mu_estabilidad', ayuda_met_num_inyec_x_mu_estabilidad);
        }
    }
});

var met_num_inyec_x_mu_materi_pri = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyec_x_mu_materi_pri',
    id: 'met_num_inyec_x_mu_materi_pri',
    fieldLabel: 'Muestra materia prima',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyec_x_mu_materi_pri', ayuda_met_num_inyec_x_mu_materi_pri);
        }
    }
});


var met_num_inyec_x_mu_pureza = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyec_x_mu_pureza',
    id: 'met_num_inyec_x_mu_pureza',
    fieldLabel: 'Muestra pureza',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyec_x_mu_pureza', ayuda_met_num_inyec_x_mu_pureza);
        }
    }
});


var met_num_inyec_x_mu_disolucion = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyec_x_mu_disolucion',
    id: 'met_num_inyec_x_mu_disolucion',
    fieldLabel: 'Muestra disolución',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyec_x_mu_disolucion', ayuda_met_num_inyec_x_mu_disolucion);
        }
    }
});


var met_num_inyec_x_mu_uniformidad = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    name: 'met_num_inyec_x_mu_uniformidad',
    id: 'met_num_inyec_x_mu_uniformidad',
    fieldLabel: 'Muestra uniformidad',
    allowDecimals: false,
    allowNegative: false,
    maxValue: '1000000000',
    maxLength: '9',
    value: '0',
    listeners: {
        'render': function(){
            ayuda('met_num_inyec_x_mu_uniformidad', ayuda_met_num_inyec_x_mu_uniformidad);
        }
    }
});

var crud_metodo_formpanel_info_analisis = new Ext.form.FormPanel({
    title: 'Alistamiento',//'Información an&aacute;lisis',
    id: 'crud_metodo_formpanel_info_analisis',
    layout: 'form',
    labelWidth: 300,
    padding: 10,
    defaults: {
        anchor: '100%'
    },
    items: [    /*eliminado version 1.1
     {xtype:'label',html:'<b>Prean&aacute;lisis </b>'},
     met_lavar_equipo_ana_eluente,
     met_cambiar_puente_x_columna,
     met_purgar_sistema_con_eluente,
     met_estabilizar_sis_fase_movil,
     {xtype:'label',html:'<b>Posan&aacute;lisis </b>'},
     met_lavar_sistema_fase_movil,
     met_lavar_sistema_con_eluente,
     met_purgar_sistema_sln_almacen,
     met_cambiar_columna_x_puente,
     met_lavar_equipo_pos_eluente,*/
    met_tiempo_alistamiento, met_tiempo_acondicionamiento, met_tiempo_estabilizacion, met_tiempo_estandar]
});

/*
 var crud_metodo_formpanel_info_tc = new Ext.form.FormPanel({
 title:'Información t<sub>c</sub>',
 id:'crud_metodo_formpanel_info_tc',
 layout:'form',
 labelWidth:240,
 padding : 10,
 defaults:{anchor:'100%'},
 items:[
 {xtype:'label',html:'<b>Informaci&oacute;n tiempos est&aacute;ndar de corrida </b><br/>'},
 {xtype:'label',html:'<br/>'},
 met_tiempo_corrida_muestra,
 met_tiempo_corrida_sistema,
 met_tiempo_corrida_curvas//--,
 //--met_tiempo_cambio_modelo
 ]
 });
 */
var crud_metodo_formpanel_info_inyec = new Ext.form.FormPanel({
    title: 'Informaci&oacute;n de est&aacute;ndares',//est&aacute;ndares
    id: 'crud_metodo_formpanel_info_inyec',
    layout: 'form',
    labelWidth: 240,
    padding: 10,
    defaults: {
        anchor: '100%'
    },
    items: [{
        xtype: 'label',
        html: '<b>N&uacute;mero de inyecciones de system suitability </b><br/>'
    }, {
        xtype: 'label',
        html: '<br/>'
    }, met_numero_inyeccion_estandar, met_tiempo_corrida_sistema, {
        xtype: 'label',
        html: '<b>N&uacute;mero de inyecciones por estandar </b><br/>'
    }, {
        xtype: 'label',
        html: '<br/>'
    }, met_num_inyeccion_estandar_1, met_num_inyeccion_estandar_2, met_num_inyeccion_estandar_3, met_num_inyeccion_estandar_4, met_num_inyeccion_estandar_5, met_num_inyeccion_estandar_6, //	met_num_inyeccion_estandar_7,
    //	met_num_inyeccion_estandar_8
    met_tiempo_corrida_curvas]
});



var crud_metodo_formpanel_info_inyec_x_mu = new Ext.form.FormPanel({
    title: 'Información de muestras',
    id: 'crud_metodo_formpanel_info_inyec_x_mu',
    layout: 'form',
    labelWidth: 250,
    padding: 10,
    defaults: {
        anchor: '100%'
    },
    layout: 'column',
    items: [{
        xtype: 'panel',
        id: 'crud_metodo_formpanel_info_inyec_x_mu_inyecc',
        layout: 'form',
        columnWidth: '1',
        html: '<b>Informaci&oacute;n n&uacute;mero de inyecciones por muestras </b><br/><br/>'
    }, //{xtype:'label',html:'<b>Informaci&oacute;n n&uacute;mero de inyecciones por muestras </b><br/>'},
    //{xtype:'label',html:'<br/>'},
    {
        xtype: 'panel',
        layout: 'form',
        columnWidth: '.7',
        //hideLabels :true,
        defaults: {
            width: 70
        },
        items: [{
            xtype: 'label',
            html: '<div style="text-align:right;"><b>No. inyeciones -</b></div>'
        }, met_num_inyec_x_mu_producto, met_num_inyec_x_mu_estabilidad, met_num_inyec_x_mu_materi_pri, met_num_inyec_x_mu_pureza, met_num_inyec_x_mu_disolucion, met_num_inyec_x_mu_uniformidad]
    }, {
        xtype: 'panel',
        id: 'crud_metodo_formpanel_info_inyec_x_mu_tc',
        layout: 'form',
        columnWidth: '.3',
        hideLabels: true,
        defaults: {
            width: 70
        },
        items: [{
            xtype: 'label',
            html: '<b> Tiempo an&aacute;lisis (min)</b>'
        }, met_tc_producto_terminado, met_tc_estabilidad, met_tc_materia_prima, met_tc_pureza, met_tc_disolucion, met_tc_uniformidad]
    }]
});

var crud_metodo_formpanel = new Ext.Panel({
    id: 'crud_metodo_formpanel',
    frame: true,
    region: 'east',
    split: true,
    collapsible: true,
    width: 530,
    border: true,
    title: 'Informaci&oacute;n del m&eacute;todo',
    //autoWidth: true,
    columnWidth: '0.6',
    height: 500,
    layout: 'form',
    bodyStyle: 'padding:5px;',
    defaults: {
        anchor: '98%'
    },
    labelWidth: 150,
    items: [met_codigo, met_nombre, {
        xtype: 'label',
        html: '<br/>'
    }, {
        xtype: 'label',
        html: '<b>DEFINICIÓN DE TIEMPOS ESTÁNDAR DE PROCESO</b><br/>'
    }, {
        xtype: 'label',
        html: '<br/>'
    }, {
        xtype: 'tabpanel',
        activeTab: 0,
        deferredRender: false,
        height: 340,
        items: [crud_metodo_formpanel_info_analisis, //crud_metodo_formpanel_info_tc,
 crud_metodo_formpanel_info_inyec, crud_metodo_formpanel_info_inyec_x_mu]
    }],
    buttons: [{
        text: 'Guardar',
        iconCls: 'guardar',
        id: 'crud_metodo_actualizar_boton',
        handler: function(formulario, accion){
        
            if (Ext.getCmp('crud_metodo_actualizar_boton').getText() == 'Actualizar') {
                Ext.Msg.prompt('M&eacute;todo', 'Digite la causa de la actualizaci&oacute;n de este m&eacute;todo', function(btn, text, op){
                    if (btn == 'ok') {
                        crud_metodo_actualizar(text);
                    }
                });
            }
            else {
                crud_metodo_actualizar('');
            }
        }
    }]
});

var crud_metodo_columnHeaderGroup = new Ext.ux.grid.ColumnHeaderGroup({
    rows: [[{
        header: '<h3>M&eacute;todo</h3>',
        colspan: 2,
        align: 'center'
    },    /*{
     header: '<h3>Pre an&aacute;lisis</h3>',
     colspan: 4,
     align: 'center'
     }, {
     header: '<h3>Pos an&aacute;lisis</h3>',
     colspan: 5,
     align: 'center'
     }, */
    {
        header: '<h3>Alistamiento</h3>',
        colspan: 3,
        align: 'center'
    }, {
        header: '<h3>Tiempo estandar</h3>',
        colspan: 2,
        align: 'center'
    }, {
        header: '<h3>Tiempos de análisis</h3>',
        colspan: 2,
        align: 'center'
    }, {
        header: '<h3>N&uacute;mero inyecciones por estandar</h3>',
        colspan: 6,//8
        align: 'center'
    }, {
        header: '<h3>N&uacute;mero inyecciones por muestra</h3>',
        colspan: 6,
        align: 'center'
    }, {
        header: '<h3>Tiempos de análisis por muestra</h3>',
        colspan: 6,
        align: 'center'
    }, {
        header: '<h3>Registro</h3>',
        colspan: 6,
        align: 'center'
    }]]
});

function crud_metodo_renderizar_gris(value){
    var renderer_gris = '<div style="background-color:#d3daed;border:0;">' + value + '</div>';
    return renderer_gris;
}

function crud_metodo_renderizar_hora_gris(value){//FFFFCC amarillo
    var renderer_gris = '<div style="background-color:#d3daed;border:0;">' + Math.round((value / 60) * 100) / 100 + '</div>';
    return renderer_gris;
}

var crud_metodo_colmodel = new Ext.grid.ColumnModel({
    defaults: {
        sortable: true,
        locked: false,
        resizable: true,
        align: 'center'
    },
    columns: [{
        header: "Id",
        width: 30,
        dataIndex: 'met_codigo'
    }, {
        header: "Nombre",
        width: 110,
        dataIndex: 'met_nombre',
        renderer: crud_metodo_renderizar_gris
    },    /*	{ header: "Lavar<br/>de equipo<br/>con eluente<br/>(Min.)", width: 70, dataIndex: 'met_lavar_equipo_ana_eluente'},
     { header: "Cambiar<br/>puente<br/> por columna<br/>(Min.)", width: 70, dataIndex: 'met_cambiar_puente_x_columna'},
     { header: "Purgar<br/>sistema<br/>con eluente<br/> (Min.)", width: 70, dataIndex: 'met_purgar_sistema_con_eluente'},
     { header: "Estabilizar<br/>sistema con<br/> Fase Móvil<br/> (Min.)", width: 70, dataIndex: 'met_estabilizar_sis_fase_movil'},
     
     { header: "Lavar<br/> sistema con<br/>Fase Móvil<br/> (Min.)", width: 70, dataIndex: 'met_lavar_sistema_fase_movil'},
     { header: "Lavar<br/> sistema <br/>con eluente<br/>(Min.)", width: 70, dataIndex: 'met_lavar_sistema_con_eluente'},
     { header: "Purgar <br/> sistema con <br/> sln. Almacena.<br/> columna (Min.)", width: 70, dataIndex: 'met_purgar_sistema_sln_almacen'},
     { header: "Cambiar  <br/>columna <br/>por puente<br/>(Min.)", width: 70, dataIndex: 'met_cambiar_columna_x_puente'},
     { header: "Lavar<br/>de equipo<br/>con eluente<br/>(Min.)", width: 70, dataIndex: 'met_lavar_equipo_pos_eluente'},*/
    {
        header: "Tiempo <br/> alistamiento <br/>(Min.)",
        width: 70,
        dataIndex: 'met_tiempo_alistamiento'
    }, {
        header: "Tiempo <br/> acondicionamiento <br/>(Min.)",
        width: 70,
        dataIndex: 'met_tiempo_acondicionamiento'
    }, {
        header: "Tiempo <br/> empaque <br/>(Min.)",
        width: 70,
        dataIndex: 'met_tiempo_estabilizacion'
    }, {
        header: "Tiempo<br/> estandar<br/>(Min)",
        width: 70,
        dataIndex: 'met_tiempo_estandar',
        renderer: crud_metodo_renderizar_gris
    }, {
        header: "Tiempo<br/> estandar<br/>(Horas)",
        width: 60,
        dataIndex: 'met_tiempo_estandar',
        renderer: crud_metodo_renderizar_hora_gris
    }, //--	{ header: "Tiempo<br/> corrida<br/> muestra<br/> (Min.)", width: 60, dataIndex: 'met_tiempo_corrida_muestra'},
    {
        header: "Sln. Test<br/>Sistema<br/>(Min.)",
        width: 60,
        dataIndex: 'met_tiempo_corrida_sistema'
    }, {
        header: "Estándares<br/>(Min.)",
        width: 70,
        dataIndex: 'met_tiempo_corrida_curvas'
    }, {
        header: "Std. 1",
        width: 52,
        dataIndex: 'met_num_inyeccion_estandar_1'
    }, {
        header: "Std. 2",
        width: 52,
        dataIndex: 'met_num_inyeccion_estandar_2'
    }, {
        header: "Std. 3",
        width: 52,
        dataIndex: 'met_num_inyeccion_estandar_3'
    }, {
        header: "Std. 4",
        width: 52,
        dataIndex: 'met_num_inyeccion_estandar_4'
    }, {
        header: "Std. 5",
        width: 52,
        dataIndex: 'met_num_inyeccion_estandar_5'
    }, {
        header: "Std. 6",
        width: 52,
        dataIndex: 'met_num_inyeccion_estandar_6'
    }, //{ header: "Estnd. 7", width: 52, dataIndex: 'met_num_inyeccion_estandar_7'},
    //{ header: "Estnd. 8", width: 52, dataIndex: 'met_num_inyeccion_estandar_8'},
    {
        header: "Producto",
        width: 62,
        dataIndex: 'met_num_inyec_x_mu_producto'
    }, {
        header: "Estabilidad",
        width: 62,
        dataIndex: 'met_num_inyec_x_mu_estabilidad'
    }, {
        header: "Materia<br/>Prima",
        width: 52,
        dataIndex: 'met_num_inyec_x_mu_materi_pri'
    }, {
        header: "Pureza",
        width: 52,
        dataIndex: 'met_num_inyec_x_mu_pureza'
    }, {
        header: "Disolución",
        width: 62,
        dataIndex: 'met_num_inyec_x_mu_disolucion'
    }, {
        header: "Uniformidad",
        width: 72,
        dataIndex: 'met_num_inyec_x_mu_uniformidad'
    }, {
        header: "Producto<br/>(Min.)",
        width: 62,
        dataIndex: 'met_tc_producto_terminado'
    }, {
        header: "Estabilidad<br/>(Min.)",
        width: 62,
        dataIndex: 'met_tc_estabilidad'
    }, {
        header: "Materia<br/>Prima<br/>(Min.)",
        width: 52,
        dataIndex: 'met_tc_materia_prima'
    }, {
        header: "Pureza<br/>(Min.)",
        width: 52,
        dataIndex: 'met_tc_pureza'
    }, {
        header: "Disolución<br/>(Min.)",
        width: 62,
        dataIndex: 'met_tc_disolucion'
    }, {
        header: "Uniformidad<br/>(Min.)",
        width: 72,
        dataIndex: 'met_tc_uniformidad'
    }, {
        header: "Creado por",
        width: 120,
        dataIndex: 'met_usu_crea_nombre'
    }, {
        header: "Fecha de creaci&oacute;n",
        width: 120,
        dataIndex: 'met_fecha_registro_sistema'
    }, {
        header: "Actualizado por",
        width: 120,
        dataIndex: 'met_usu_actualiza_nombre'
    }, {
        header: "Fecha de actualizaci&oacute;n",
        width: 120,
        dataIndex: 'met_fecha_actualizacion'
    }, {
        header: "Causa actualizaci&oacute;n",
        width: 120,
        dataIndex: 'met_causa_actualizacion'
    }, {
        header: "Causa eliminaci&oacute;n",
        width: 120,
        dataIndex: 'met_causa_eliminacion'
    }]
});

var crud_metodo_gridpanel = new Ext.grid.GridPanel({
    title: 'M&eacute;todos de an&aacute;lisis',
    columnWidth: '.4',
    region: 'center',
    stripeRows: true,
    frame: true,
    ds: crud_metodo_datastore,
    cm: crud_metodo_colmodel,
    plugins: crud_metodo_columnHeaderGroup,
    selModel: new Ext.grid.RowSelectionModel({
        singleSelect: true,
        listeners: {
            rowselect: function(sm, row, record){
                //Ext.getCmp('crud_metodo_formpanel').getForm().loadRecord(record);
                Ext.getCmp('met_codigo').setValue(record.data.met_codigo);
                Ext.getCmp('met_nombre').setValue(record.data.met_nombre);
                Ext.getCmp('crud_metodo_formpanel_info_analisis').getForm().loadRecord(record);
                //--Ext.getCmp('crud_metodo_formpanel_info_tc').getForm().loadRecord(record);
                Ext.getCmp('crud_metodo_formpanel_info_inyec').getForm().loadRecord(record);
                Ext.getCmp('crud_metodo_formpanel_info_inyec_x_mu').getForm().loadRecord(record);
                
                
                
                Ext.getCmp('crud_metodo_actualizar_boton').setText('Actualizar');
            }
        }
    }),
    height: largo_panel,
    bbar: new Ext.PagingToolbar({
        pageSize: 15,
        store: crud_metodo_datastore,
        displayInfo: true,
        displayMsg: 'M&eacute;todos {0} - {1} de {2}',
        emptyMsg: "No hay m&eacute;todos aun"
    }),
    tbar: [{
        id: 'crud_metodo_agregar_boton',
        text: 'Agregar',
        tooltip: 'Agregar',
        iconCls: 'agregar',
        handler: crud_metodo_agregar
    }, '-', {
        text: 'Eliminar',
        tooltip: 'Eliminar',
        iconCls: 'eliminar',
        handler: crud_metodo_eliminar
    }, '-', {
        text: '',
        iconCls: 'activos',
        tooltip: 'M&eacute;todos activos',
        handler: function(){
            crud_metodo_datastore.baseParams.met_eliminado = '0';
            crud_metodo_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, {
        text: '',
        iconCls: 'eliminados',
        tooltip: 'M&eacute;todos eliminados',
        handler: function(){
            crud_metodo_datastore.baseParams.met_eliminado = '1';
            crud_metodo_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, '-', {
        text: 'Restablecer',
        iconCls: 'restablece',
        tooltip: 'Restablecer un m&eacute;todo eliminado',
        handler: function(){
            var cant_record = crud_metodo_gridpanel.getSelectionModel().getCount();
            
            if (cant_record > 0) {
                var record = crud_metodo_gridpanel.getSelectionModel().getSelected();
                if (record.get('met_codigo') != '') {
                
                    Ext.Msg.prompt('Restablecer m&eacute;todo', 'Digite la causa de restablecimiento', function(btn, text){
                        if (btn == 'ok') {
                            subirDatosAjax(getAbsoluteUrl('crud_metodo', 'restablecerMetodo'), {
                                met_codigo: record.get('met_codigo'),
                                met_causa_restablece: text
                            }, function(){
                                crud_metodo_datastore.reload();
                            }, function(){
                            });
                        }
                    });
                }
            }
            else {
                mostrarMensajeConfirmacion('Error', "Seleccione un m&eacute;todo eliminado");
            }
        }
    }]
});


/*INTEGRACION AL CONTENEDOR*/
var crud_metodo_contenedor_panel = new Ext.Panel({
    id: 'crud_metodo_contenedor_panel',
    height: largo_panel,
    autoWidth: true,
    //width:1000,
    border: false,
    tabTip: 'Aqui puedes ver, agregar, eliminar m&eacute;todos',
    monitorResize: true,
    layout: 'border',
    items: [crud_metodo_gridpanel, crud_metodo_formpanel],
    buttonAlign: 'left',
    renderTo: 'div_form_crud_metodo'
});

function crud_metodo_actualizar(text){

    var valido = crud_metodo_verificarcampos();
    
    if (valido) {
        subirDatosAjax(getAbsoluteUrl('crud_metodo', 'actualizarMetodo'), {
            met_codigo: met_codigo.getValue(),
            met_nombre: met_nombre.getValue(),
            /*
             met_cambiar_puente_x_columna: met_cambiar_puente_x_columna.getValue(),
             met_purgar_sistema_con_eluente:met_purgar_sistema_con_eluente.getValue(),
             met_lavar_equipo_ana_eluente:met_lavar_equipo_ana_eluente.getValue(),
             met_estabilizar_sis_fase_movil:met_estabilizar_sis_fase_movil.getValue(),
             met_lavar_sistema_fase_movil:met_lavar_sistema_fase_movil.getValue(),
             met_lavar_sistema_con_eluente:met_lavar_sistema_con_eluente.getValue(),
             met_purgar_sistema_sln_almacen:met_purgar_sistema_sln_almacen.getValue(),
             met_cambiar_columna_x_puente:met_cambiar_columna_x_puente.getValue(),
             met_lavar_equipo_pos_eluente:met_lavar_equipo_pos_eluente.getValue(),*/
            met_tiempo_alistamiento: met_tiempo_alistamiento.getValue(),
            met_tiempo_acondicionamiento: met_tiempo_acondicionamiento.getValue(),
            met_tiempo_estabilizacion: met_tiempo_estabilizacion.getValue(),
            met_tiempo_estandar: met_tiempo_estandar.getValue(),
            
            //--met_tiempo_corrida_muestra:met_tiempo_corrida_muestra.getValue(),
            met_tiempo_corrida_sistema: met_tiempo_corrida_sistema.getValue(),
            met_tiempo_corrida_curvas: met_tiempo_corrida_curvas.getValue(),
            //--met_tiempo_cambio_modelo:met_tiempo_cambio_modelo.getValue(),
            met_numero_inyeccion_estandar: met_numero_inyeccion_estandar.getValue(),
            met_num_inyeccion_estandar_1: met_num_inyeccion_estandar_1.getValue(),
            met_num_inyeccion_estandar_2: met_num_inyeccion_estandar_2.getValue(),
            met_num_inyeccion_estandar_3: met_num_inyeccion_estandar_3.getValue(),
            met_num_inyeccion_estandar_4: met_num_inyeccion_estandar_4.getValue(),
            met_num_inyeccion_estandar_5: met_num_inyeccion_estandar_5.getValue(),
            met_num_inyeccion_estandar_6: met_num_inyeccion_estandar_6.getValue(),
            //met_num_inyeccion_estandar_7: met_num_inyeccion_estandar_7.getValue(),
            //met_num_inyeccion_estandar_8: met_num_inyeccion_estandar_8.getValue(),
            
            met_num_inyec_x_mu_producto: met_num_inyec_x_mu_producto.getValue(),
            met_num_inyec_x_mu_estabilidad: met_num_inyec_x_mu_estabilidad.getValue(),
            met_num_inyec_x_mu_materi_pri: met_num_inyec_x_mu_materi_pri.getValue(),
            met_num_inyec_x_mu_pureza: met_num_inyec_x_mu_pureza.getValue(),
            met_num_inyec_x_mu_disolucion: met_num_inyec_x_mu_disolucion.getValue(),
            met_num_inyec_x_mu_uniformidad: met_num_inyec_x_mu_uniformidad.getValue(),
            met_causa_actualizacion: text,
            
            //add
            met_tc_producto_terminado: met_tc_producto_terminado.getValue(),
            met_tc_estabilidad: met_tc_estabilidad.getValue(),
            met_tc_materia_prima: met_tc_materia_prima.getValue(),
            met_tc_pureza: met_tc_pureza.getValue(),
            met_tc_disolucion: met_tc_disolucion.getValue(),
            met_tc_uniformidad: met_tc_uniformidad.getValue()
        
        }, function(){
        
            Ext.getCmp('met_codigo').setValue('');
            Ext.getCmp('met_nombre').setValue('');
            
            Ext.getCmp('crud_metodo_formpanel_info_analisis').getForm().reset();
            //--Ext.getCmp('crud_metodo_formpanel_info_tc').getForm().reset();
            Ext.getCmp('crud_metodo_formpanel_info_inyec').getForm().reset();
            Ext.getCmp('crud_metodo_formpanel_info_inyec_x_mu').getForm().reset();
            
            
            
            crud_metodo_datastore.reload();
        }, function(){
        
        });
    }
}

function crud_metodo_eliminar(){
    var cant_record = crud_metodo_gridpanel.getSelectionModel().getCount();
    
    if (cant_record > 0) {
        var record = crud_metodo_gridpanel.getSelectionModel().getSelected();
        if (record.get('met_codigo') != '') {
            Ext.Msg.confirm('Eliminar m&eacute;todo', "Realmente desea eliminar este m&eacute;todo?", function(btn){
                if (btn == 'yes') {
                
                    Ext.Msg.prompt('Eliminar m&eacute;todo', 'Digite la causa de la eliminaci&oacute;n de este m&eacute;todo', function(btn2, text){
                        if (btn2 == 'ok') {
                            subirDatosAjax(getAbsoluteUrl('crud_metodo', 'eliminarMetodo'), {
                                met_codigo: record.get('met_codigo'),
                                met_causa_eliminacion: text
                            }, function(){
                                crud_metodo_datastore.reload();
                            });
                        }
                    });
                }
            });
        }
    }
    else {
        mostrarMensajeConfirmacion('Error', "Seleccione un m&eacute;todo a eliminar");
    }
}

function crud_metodo_agregar(btn, ev){
    //crud_metodo_formpanel.getForm().reset();
    Ext.getCmp('met_codigo').setValue('');
    Ext.getCmp('met_nombre').setValue('');
    
    Ext.getCmp('crud_metodo_formpanel_info_analisis').getForm().reset();
    //--Ext.getCmp('crud_metodo_formpanel_info_tc').getForm().reset();
    Ext.getCmp('crud_metodo_formpanel_info_inyec').getForm().reset();
    Ext.getCmp('crud_metodo_formpanel_info_inyec_x_mu').getForm().reset();
    
    Ext.getCmp('crud_metodo_actualizar_boton').setText('Guardar');
    
}

function crud_metodo_calculartiempoestandar(){

    var tmp_minutos_totales = 0.0;
    //var tiempos_estandares_preparacion_array= new Array(met_cambiar_puente_x_columna, met_purgar_sistema_con_eluente, met_lavar_equipo_ana_eluente, met_estabilizar_sis_fase_movil, met_lavar_sistema_fase_movil, met_lavar_sistema_con_eluente, met_purgar_sistema_sln_almacen, met_cambiar_columna_x_puente,met_lavar_equipo_pos_eluente);
    
    var tiempos_estandares_preparacion_array = new Array(met_tiempo_alistamiento, met_tiempo_acondicionamiento, met_tiempo_estabilizacion);
    for (contar = 0; contar < tiempos_estandares_preparacion_array.length; contar++) {
        tmp_minutos_totales = tmp_minutos_totales + tiempos_estandares_preparacion_array[contar].getValue();
    }
    met_tiempo_estandar.setValue(tmp_minutos_totales);
    
}


function crud_metodo_verificarcampos(){
    var valido = true;
    
    if (!(Ext.getCmp('met_nombre').isValid())) {
        mostrarMensajeRapido('Aviso', 'Faltan campos por llenar');
        return false;
    }
    
    return valido;
}

