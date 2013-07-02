/*
crud metodos - tpm labs
Desarrollado maryit sanchez
2010
*/

	var  ayuda_met_codigo='';
	var  ayuda_met_nombre='Escriba el nombre del método'; 
	var  ayuda_met_quitar_columna=''; 
	var  ayuda_met_lavado_equipo='Cuanto se demora en lavado de equipo con agua'; //tipo
	var  ayuda_met_instalacion_columna='Cuanto se demora en instalación de columna'; 
	var  ayuda_met_lavado_sistema='Cuanto se demora en lavado de sistema'; 
	var  ayuda_met_estabilizacion_fase_movil='Cuanto se demora en estabilizacion de fase movil'; 
	var  ayuda_met_purga_sistema_fase_movil='Cuanto se demora en purga sistema fase movil'; 
	var  ayuda_met_purga_sistema_agua='Cuanto se demora en purga sistema agua'; 
	var  ayuda_met_purga_sistema_sin_almacena='Cuanto se demora en purga sistema sin almacenamiento';
	var  ayuda_met_tiempo_estandar='Tiempo estándar alistamiento sistema es la suma de la información de analisis y postanalisis';
	var  ayuda_met_tiempo_corrida_muestra='Tiempo de corrida para muestras';
	var  ayuda_met_tiempo_corrida_sistema='Tiempo de corrida solucion System';
	var  ayuda_met_tiempo_corrida_curvas='Tiempo de corrida para estandares (niveles de calibración)';
	var  ayuda_met_numero_inyeccion_estandar='Cuantas inyecciones se realizan con el sistema(system suitability)';
	var  ayuda_met_num_inyeccion_estandar_1='Cuantas inyecciones se realizan con el estandar 1 ';
	var  ayuda_met_num_inyeccion_estandar_2='Cuantas inyecciones se realizan con el estandar 2 ';
	var  ayuda_met_num_inyeccion_estandar_3='Cuantas inyecciones se realizan con el estandar 3 ';
	var  ayuda_met_num_inyeccion_estandar_4='Cuantas inyecciones se realizan con el estandar 4 ';
	var  ayuda_met_num_inyeccion_estandar_5='Cuantas inyecciones se realizan con el estandar 5 ';
	var  ayuda_met_num_inyeccion_estandar_6='Cuantas inyecciones se realizan con el estandar 6 ';
	var  ayuda_met_num_inyeccion_estandar_7='Cuantas inyecciones se realizan con el estandar 7 ';
	var  ayuda_met_num_inyeccion_estandar_8='Cuantas inyecciones se realizan con el estandar 8';

	var ayuda_met_num_inyec_x_mu_producto='Número de inyecciones  por muestras de producto terminado';
	var ayuda_met_num_inyec_x_mu_estabilidad='Número de inyecciones  por muestras de estabilidad';
	var ayuda_met_num_inyec_x_mu_materi_pri='Número de inyecciones  por muestras de materia prima';
	var ayuda_met_num_inyec_x_mu_estandar='Número de inyecciones  por muestras estandar';
	var ayuda_met_tiempo_cambio_modelo='Número de inyecciones  por muestras estandar';
   
	var largo_panel=500;


	var crud_metodo_datastore = new Ext.data.Store({
        id: 'crud_metodo_datastore',
        proxy: new Ext.data.HttpProxy({
                url: getAbsoluteUrl('crud_metodo','listarMetodo'),
                method: 'POST'
        }),
        baseParams:{start:0,limit:15}, 
        reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
                },[ 
			{name: 'met_codigo', type: 'int'},
			{name: 'met_nombre', type: 'string'},
			{name: 'met_quitar_columna', type: 'float'},
			{name: 'met_lavado_equipo', type: 'float'},
			{name: 'met_instalacion_columna', type: 'float'},
			{name: 'met_lavado_sistema', type: 'float'},
			{name: 'met_estabilizacion_fase_movil', type: 'float'},
			{name: 'met_purga_sistema_fase_movil', type: 'float'},
			{name: 'met_purga_sistema_agua', type: 'float'},
			{name: 'met_purga_sistema_sin_almacena', type: 'float'},
			{name: 'met_tiempo_estandar', type: 'float'},
			{name: 'met_tiempo_corrida_muestra', type: 'float'},
			{name: 'met_tiempo_corrida_sistema', type: 'float'},
			{name: 'met_tiempo_corrida_curvas', type: 'float'},
			{name: 'met_tiempo_cambio_modelo', type: 'float'},
			{name: 'met_numero_inyeccion_estandar', type: 'int'},			
			{name: 'met_num_inyeccion_estandar_1', type: 'int'},
			{name: 'met_num_inyeccion_estandar_2', type: 'int'},
			{name: 'met_num_inyeccion_estandar_3', type: 'int'},
			{name: 'met_num_inyeccion_estandar_4', type: 'int'},
			{name: 'met_num_inyeccion_estandar_5', type: 'int'},
			{name: 'met_num_inyeccion_estandar_6', type: 'int'},
			{name: 'met_num_inyeccion_estandar_7', type: 'int'},
			{name: 'met_num_inyeccion_estandar_8', type: 'int'},
			
			{name: 'met_num_inyec_x_mu_producto', type: 'int'},
			{name: 'met_num_inyec_x_mu_estabilidad', type: 'int'},
			{name: 'met_num_inyec_x_mu_materi_pri', type: 'int'},
			{name: 'met_num_inyec_x_mu_estandar', type: 'int'},
			{name: 'met_fecha_registro_sistema', type: 'string'},
			{name: 'met_fecha_actualizacion',type: 'string'},
			{name: 'met_usu_crea_nombre',type: 'string'},
			{name: 'met_usu_actualiza_nombre',type: 'string'}
		])
        });
    crud_metodo_datastore.load();
	
	var met_codigo=new Ext.form.NumberField({
	   xtype: 'numberfield',
	   labelStyle: 'text-align:right;',
	   maxLength : 100,
	   name: 'met_codigo',
	   id: 'met_codigo',
	   hideLabel:true,
	   hidden:true,
		//readOnly:true,
	   listeners:
	   {
			'render': function() {
					ayuda('met_codigo', ayuda_met_codigo);
					}
	   }
	});
	

	var met_nombre=new Ext.form.TextField({
		xtype: 'textfield',
		labelStyle: 'text-align:right;',
		maxLength : 100,
		name: 'met_nombre',
		id: 'met_nombre',
		fieldLabel: 'Nombre del m&eacute;todo',
		allowBlank: false,
		listeners:
		{
			'render': function() {
					ayuda('met_nombre', ayuda_met_nombre);
					}
		}
	});
	
	var met_quitar_columna=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: ' text-align:right;',
	   name: 'met_quitar_columna',
	   id: 'met_quitar_columna',
	   fieldLabel: 'Quitar columna y poner puente (Min.)',
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
			ayuda('met_quitar_columna', ayuda_met_quitar_columna);
			},
            'keyup': function(){
				crud_metodo_calculartiempoestandar();
            }
		}
	});

	var met_instalacion_columna=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: ' text-align:right;',
	   name: 'met_instalacion_columna',
	   id: 'met_instalacion_columna',
	   fieldLabel: 'Instalación de columna (Min.)',
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
				ayuda('met_instalacion_columna', ayuda_met_instalacion_columna);
			},
            'keyup': function(){
				crud_metodo_calculartiempoestandar();
            }
		}
	});

	var met_lavado_equipo=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_lavado_equipo',
	   id: 'met_lavado_equipo',
	   fieldLabel: 'Lavado de equipo con agua (Min.)',
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
				ayuda('met_lavado_equipo', ayuda_met_lavado_equipo);
			},
            'keyup': function(){
				crud_metodo_calculartiempoestandar();
            }
		}
	});

	var met_lavado_sistema=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: ' text-align:right;',
	   name: 'met_lavado_sistema',
	   id: 'met_lavado_sistema',
	   fieldLabel: 'Lavado de sistema con agua (Min.)',
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
				ayuda('met_lavado_sistema', ayuda_met_lavado_sistema);
			},
            'keyup': function(){
				crud_metodo_calculartiempoestandar();
            }
		}
	});

	
	var met_estabilizacion_fase_movil=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: ' text-align:right;',
	   name: 'met_estabilizacion_fase_movil',
	   id: 'met_estabilizacion_fase_movil',
	   fieldLabel: 'Estabilización de sistema con Fase Móvil (Min.)',
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
				ayuda('met_estabilizacion_fase_movil', ayuda_met_estabilizacion_fase_movil);
			},
            'keyup': function(){
				crud_metodo_calculartiempoestandar();
            }
		}
	});

	var met_purga_sistema_fase_movil=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: ' text-align:right;',
	   name: 'met_purga_sistema_fase_movil',
	   id: 'met_purga_sistema_fase_movil',
	   fieldLabel: 'Purga de sistema con Fase Móvil (Min.)',
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
					ayuda('met_purga_sistema_fase_movil', ayuda_met_purga_sistema_fase_movil);
			},
            'keyup': function(){
				crud_metodo_calculartiempoestandar();
            }
		}
	});

	
	
	var met_purga_sistema_agua=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: ' text-align:right;',
	   name: 'met_purga_sistema_agua',
	   id: 'met_purga_sistema_agua',
	   fieldLabel: 'Purga de sistema con agua (Min.)',
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
				ayuda('met_purga_sistema_agua', ayuda_met_purga_sistema_agua);
			},
            'keyup': function(){
				crud_metodo_calculartiempoestandar();
            }
		}
	});
	
	
	var met_purga_sistema_sin_almacena=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: ' text-align:right;',
	   name: 'met_purga_sistema_sin_almacena',
	   id: 'met_purga_sistema_sin_almacena',
	   fieldLabel: 'Purga de Sistema sln almacenmto. de columna (Min.)',
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
				ayuda('met_purga_sistema_sin_almacena', ayuda_met_purga_sistema_sin_almacena);
			},
            'keyup': function(){
				crud_metodo_calculartiempoestandar();
            }
		}
	});

	var met_tiempo_estandar=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: ' text-align:right;',
	   name: 'met_tiempo_estandar',
	   id: 'met_tiempo_estandar',
	   fieldLabel: '<b>Tiempo estándar alistamiento sistema(Min.)</b>',
	   allowDecimals: true,
	   allowNegative: false,
	   maxValue : '100000000',
	   decimalPrecision:'4',
	   maxLength:'13',
	   value:'0',
	   readOnly:true,
	   listeners:
	   {
			'render': function() {
				ayuda('met_tiempo_estandar', ayuda_met_tiempo_estandar);
			}
		}
	});

	
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

	
	var met_tiempo_corrida_sistema=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: ' text-align:right;',
	   name: 'met_tiempo_corrida_sistema',
	   id: 'met_tiempo_corrida_sistema',
	   fieldLabel: 'Tiempo corrida sln. Test (Min.)',
	   allowDecimals: true,
	   allowNegative: false,
	   maxValue : '10000000',
	   decimalPrecision:'4',
	   maxLength:'12',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_tiempo_corrida_sistema', ayuda_met_tiempo_corrida_sistema);
					}
		}
	});

	var met_tiempo_corrida_curvas=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: ' text-align:right;',
	   name: 'met_tiempo_corrida_curvas',
	   id: 'met_tiempo_corrida_curvas',
	   fieldLabel: 'Tiempo corrida est&aacute;ndares de calibraci&oacute;n (Min.)',
	   allowDecimals: true,
	   allowNegative: false,
	   maxValue : '99999999',
	   decimalPrecision:'4',
	   maxLength:'12',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_tiempo_corrida_curvas', ayuda_met_tiempo_corrida_curvas);
					}
		}
	});

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

	var met_numero_inyeccion_estandar=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_numero_inyeccion_estandar',
	   id: 'met_numero_inyeccion_estandar',
	   fieldLabel: 'N&deg;. inyecciones sln. Test',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_numero_inyeccion_estandar', ayuda_met_numero_inyeccion_estandar);
					}
		}
	});
	
	
	var met_num_inyeccion_estandar_1=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_num_inyeccion_estandar_1',
	   id: 'met_num_inyeccion_estandar_1',
	   fieldLabel: 'N&deg;. inyecciones std 1',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_num_inyeccion_estandar_1', ayuda_met_num_inyeccion_estandar_1);
					}
		}
	});
	
	
	var met_num_inyeccion_estandar_2=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_num_inyeccion_estandar_2',
	   id: 'met_num_inyeccion_estandar_2',
	   fieldLabel: 'N&deg;. inyecciones std 2',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_num_inyeccion_estandar_2', ayuda_met_num_inyeccion_estandar_2);
					}
		}
	});
	
	var met_num_inyeccion_estandar_3=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_num_inyeccion_estandar_3',
	   id: 'met_num_inyeccion_estandar_3',
	   fieldLabel: 'N&deg;. inyecciones std  3',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_num_inyeccion_estandar_3', ayuda_met_num_inyeccion_estandar_3);
					}
		}
	});

	var met_num_inyeccion_estandar_4=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_num_inyeccion_estandar_4',
	   id: 'met_num_inyeccion_estandar_4',
	   fieldLabel: 'N&deg;. inyecciones std 4',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_num_inyeccion_estandar_4', ayuda_met_num_inyeccion_estandar_4);
					}
		}
	});

	var met_num_inyeccion_estandar_5=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_num_inyeccion_estandar_5',
	   id: 'met_num_inyeccion_estandar_5',
	   fieldLabel: 'N&deg;. inyecciones std 5',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_num_inyeccion_estandar_5', ayuda_met_num_inyeccion_estandar_5);
					}
		}
	});

	var met_num_inyeccion_estandar_6=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_num_inyeccion_estandar_6',
	   id: 'met_num_inyeccion_estandar_6',
	   fieldLabel: 'N&deg;. inyecciones std 6',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_num_inyeccion_estandar_6', ayuda_met_num_inyeccion_estandar_6);
					}
		}
	});

	var met_num_inyeccion_estandar_7=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_num_inyeccion_estandar_7',
	   id: 'met_num_inyeccion_estandar_7',
	   fieldLabel: 'N&deg;. inyecciones std 7',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_num_inyeccion_estandar_7', ayuda_met_num_inyeccion_estandar_7);
					}
		}
	});

	var met_num_inyeccion_estandar_8=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_num_inyeccion_estandar_8',
	   id: 'met_num_inyeccion_estandar_8',
	   fieldLabel: 'N&deg;. inyecciones std 8',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_num_inyeccion_estandar_8', ayuda_met_num_inyeccion_estandar_8);
					}
		}
	});
	
	var met_fecha_registro_sistema=new Ext.form.TextField({
	   xtype: 'textfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_fecha_registro_sistema',
	   id: 'met_fecha_registro_sistema',
	   fieldLabel: '<html>Fecha registro</html>',
	   maxLength : 100,
	   disabled:true
	});
	
	var met_num_inyec_x_mu_producto=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_num_inyec_x_mu_producto',
	   id: 'met_num_inyec_x_mu_producto',
	   fieldLabel: 'N&deg;. inyecciones producto terminado',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_num_inyec_x_mu_producto', ayuda_met_num_inyec_x_mu_producto);
					}
		}
	});
	
	var met_num_inyec_x_mu_estabilidad=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_num_inyec_x_mu_estabilidad',
	   id: 'met_num_inyec_x_mu_estabilidad',
	   fieldLabel: 'N&deg;. inyecciones muestra estabilidad',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_num_inyec_x_mu_estabilidad', ayuda_met_num_inyec_x_mu_estabilidad);
					}
		}
	});
	
	var met_num_inyec_x_mu_materi_pri=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_num_inyec_x_mu_materi_pri',
	   id: 'met_num_inyec_x_mu_materi_pri',
	   fieldLabel: 'N&deg;. inyecciones muestra materia prima',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_num_inyec_x_mu_materi_pri', ayuda_met_num_inyec_x_mu_materi_pri);
					}
		}
	});
	
	var met_num_inyec_x_mu_estandar=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'met_num_inyec_x_mu_estandar',
	   id: 'met_num_inyec_x_mu_estandar',
	   fieldLabel: 'N&deg;. inyecciones muestra otra',
	   allowDecimals: false,
	   allowNegative: false,
	   maxValue : '1000000000',
	   maxLength:'9',
	   value:'0',
	   listeners:
	   {
			'render': function() {
					ayuda('met_num_inyec_x_mu_estandar', ayuda_met_num_inyec_x_mu_estandar);
					}
		}
	});
	
	
	
	var crud_metodo_formpanel_info_analisis = new Ext.form.FormPanel({
		title:'Información an&aacute;lisis',
		id:'crud_metodo_formpanel_info_analisis',
		layout:'form',
		labelWidth:270,
		padding : 10,
		defaults:{anchor:'100%'},
		items:[
			{xtype:'label',html:'<b>Prean&aacute;lisis </b>'},
			met_quitar_columna,
			met_lavado_equipo,
			met_instalacion_columna,
			met_lavado_sistema,
			met_estabilizacion_fase_movil,
			{xtype:'label',html:'<b>Posan&aacute;lisis </b>'},
			met_purga_sistema_fase_movil,
			met_purga_sistema_agua,
			met_purga_sistema_sin_almacena,
			met_tiempo_estandar
		]
	});

	
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
			met_tiempo_corrida_curvas,
			met_tiempo_cambio_modelo
		]
	});
	
	var crud_metodo_formpanel_info_inyec = new Ext.form.FormPanel({
		title:'N&deg;. inyecciones de std',//est&aacute;ndares
		id:'crud_metodo_formpanel_info_inyec',
		layout:'form',
		labelWidth:200,
		padding : 10,
		defaults:{anchor:'100%'},
		items:[
			{xtype:'label',html:'<b>N&uacute;mero de inyecciones del system suitability </b><br/>'},
			{xtype:'label',html:'<br/>'},
			met_numero_inyeccion_estandar,
			{xtype:'label',html:'<b>N&uacute;mero de inyecciones por estandar </b><br/>'},
			{xtype:'label',html:'<br/>'},
			met_num_inyeccion_estandar_1,
			met_num_inyeccion_estandar_2,
			met_num_inyeccion_estandar_3,
			met_num_inyeccion_estandar_4,
			met_num_inyeccion_estandar_5,
			met_num_inyeccion_estandar_6,
			met_num_inyeccion_estandar_7,
			met_num_inyeccion_estandar_8
		]
	});
	
	
	
	var crud_metodo_formpanel_info_inyec_x_mu = new Ext.form.FormPanel({
		title:'N&deg;. inyecciones de muestra',
		id:'crud_metodo_formpanel_info_inyec_x_mu',
		layout:'form',
		labelWidth:260,
		padding : 10,
		defaults:{anchor:'100%'},
		items:[
			{xtype:'label',html:'<b>Informaci&oacute;n n&uacute;mero de inyecciones por muestras </b><br/>'},
			{xtype:'label',html:'<br/>'},
			met_num_inyec_x_mu_producto,
			met_num_inyec_x_mu_estabilidad,
			met_num_inyec_x_mu_materi_pri,
			met_num_inyec_x_mu_estandar
		]
	});
	
	var crud_metodo_formpanel = new Ext.Panel({
		id:'crud_metodo_formpanel',
		frame: true,
		region:'east',
		split:true,
		collapsible:true,
		width:530,
		border:true,
		title:'M&eacute;todo detalle',
		//autoWidth: true,
		columnWidth: '0.6',
		height: 500,
		layout:'form',
		bodyStyle: 'padding:10px;',
		defaults:{  anchor:'98%'},
		labelWidth:150,
		items:
		[   
			met_codigo,
			met_nombre,
			{xtype:'label',html:'<br/>'},
			{xtype:'label',html:'<b>DETERMINACIÓN  DE TIEMPOS ESTÁNDAR DE PROCESO</b><br/>'},
			{xtype:'label',html:'<br/>'},
			{
				xtype:'tabpanel',
				activeTab:0,
				deferredRender: false,
				height: 340,
				items:
				[
					crud_metodo_formpanel_info_analisis,
					crud_metodo_formpanel_info_tc,
					crud_metodo_formpanel_info_inyec,
					crud_metodo_formpanel_info_inyec_x_mu
				]
			}
			
		],
		buttons:
		[
			{
				text: 'Guardar',
				iconCls:'guardar',
				id:'crud_metodo_actualizar_boton',
				handler: crud_metodo_actualizar
			}
		]
	});

	var crud_metodo_columnHeaderGroup = new Ext.ux.grid.ColumnHeaderGroup({
        rows: [[{
            header: '<h3>M&eacute;todo</h3>',
            colspan: 2,
            align: 'center'
        }, {
            header: '<h3>Pre an&aacute;lisis</h3>',
            colspan: 5,
            align: 'center'
        }, {
            header: '<h3>Pos an&aacute;lisis</h3>',
            colspan: 3,
            align: 'center'
        }, {
            header: '<h3>Tiempo estandar</h3>',
            colspan: 2,
            align: 'center'
        }, {
            header: '<h3>Tiempos de corrida</h3>',
            colspan: 3,
            align: 'center'
        }, {
            header: '<h3>N&uacute;mero inyecciones por estandar</h3>',
            colspan: 8,
            align: 'center'
        }, {
            header: '<h3>N&uacute;mero inyecciones por muestra</h3>',
            colspan: 4,
            align: 'center'
        }, {
            header: '<h3>Registro</h3>',
            colspan: 4,
            align: 'center'
        }]]
    });
  
	function crud_metodo_renderizar_gris(value){
		var renderer_gris = '<div style="background-color:#d3daed;border:0;">'+value+'</div>';
		return renderer_gris;
	}
	
	function crud_metodo_renderizar_hora_gris(value){//FFFFCC amarillo
		var renderer_gris = '<div style="background-color:#d3daed;border:0;">'+(value/60)+'</div>';
		return renderer_gris;
	}
	
	var crud_metodo_colmodel = new Ext.grid.ColumnModel({
	defaults:{sortable: true, locked: false, resizable: true},
	columns:[
		{ header: "Id", width: 30, dataIndex: 'met_codigo'},
		{ header: "<center>Nombre</center>", width: 110, dataIndex: 'met_nombre',renderer:crud_metodo_renderizar_gris},
			{ header: "<center>Quitar<br/>columna y<br/>poner puente<br/> (Min.)</center>", width: 70, dataIndex: 'met_quitar_columna'},
			{ header: "<center>Lavado<br/>de equipo<br/>con agua<br/>(Min.)</center>", width: 70, dataIndex: 'met_lavado_equipo'},
			{ header: "<center>Instalación<br/>de<br/>columna <br/> (Min.)</center>", width: 70, dataIndex: 'met_instalacion_columna'},
			{ header: "<center>Lavado de<br/> sistema<br/> con agua  <br/> (Min.)</center>", width: 70, dataIndex: 'met_lavado_sistema'},
			{ header: "<center>Estabilización<br/>de sistema<br/> con Fase<br/>Móvil (Min.)</center>", width: 70, dataIndex: 'met_estabilizacion_fase_movil'},
			{ header: "<center>Purga de <br/> sistema <br/>con Fase <br/>Móvil (Min.)</center>", width: 70, dataIndex: 'met_purga_sistema_fase_movil'},
			{ header: "<center>Purga de <br/> sistema <br/>con agua <br/> (Min.)</center>", width: 70, dataIndex: 'met_purga_sistema_agua'},
			{ header: "<center>Purga de <br/>sistema sin<br/>almacenmto. de<br/> columna (Min.)</center>", width: 70, dataIndex: 'met_purga_sistema_sin_almacena'},
			{ header: "<center>Tiempo<br/> estandar<br/>(Min)</center>", width: 70, dataIndex: 'met_tiempo_estandar',renderer:crud_metodo_renderizar_gris},
			{ header: "<center>Tiempo<br/> estandar<br/>(Horas)</center>", width: 60, dataIndex: 'met_tiempo_estandar',renderer:crud_metodo_renderizar_hora_gris},
			{ header: "<center>Tiempo<br/> corrida<br/> muestra<br/> (Min.)</center>", width: 60, dataIndex: 'met_tiempo_corrida_muestra'},
			{ header: "<center>Tiempo<br/> corrida<br/> sistema<br/> (Min.)</center>", width: 60, dataIndex: 'met_tiempo_corrida_sistema'},
			{ header: "<center>Tiempo<br/> corrida<br/> curvas<br/> (Min.)</center>", width: 60, dataIndex: 'met_tiempo_corrida_curvas'},
			{ header: "<center>Estnd. 1</center>", width: 52, dataIndex: 'met_num_inyeccion_estandar_1'},
			{ header: "<center>Estnd. 2</center>", width: 52, dataIndex: 'met_num_inyeccion_estandar_2'},
			{ header: "<center>Estnd. 3</center>", width: 52, dataIndex: 'met_num_inyeccion_estandar_3'},
			{ header: "<center>Estnd. 4</center>", width: 52, dataIndex: 'met_num_inyeccion_estandar_4'},
			{ header: "<center>Estnd. 5</center>", width: 52, dataIndex: 'met_num_inyeccion_estandar_5'},
			{ header: "<center>Estnd. 6</center>", width: 52, dataIndex: 'met_num_inyeccion_estandar_6'},
			{ header: "<center>Estnd. 7</center>", width: 52, dataIndex: 'met_num_inyeccion_estandar_7'},
			{ header: "<center>Estnd. 8</center>", width: 52, dataIndex: 'met_num_inyeccion_estandar_8'},
			{ header: "<center>Muestra <br/>Producto</center>", width: 52, dataIndex: 'met_num_inyec_x_mu_producto'},
			{ header: "<center>Muestra <br/>Estabilidad</center>", width: 52, dataIndex: 'met_num_inyec_x_mu_estabilidad'},
			{ header: "<center>Muestra <br/>Materia<br/>Prima</center>", width: 52, dataIndex: 'met_num_inyec_x_mu_materi_pri'},
			{ header: "<center>Muestra <br/>Estandar</center>", width: 52, dataIndex: 'met_num_inyec_x_mu_estandar'},
			
			{ header: "<center>Creado por</center>", width: 120, dataIndex: 'met_usu_crea_nombre'},
			{ header: "<center>Fecha de creaci&oacute;n</center>", width: 100, dataIndex: 'met_fecha_registro_sistema'},
			{ header: "<center>Actualizado por</center>", width: 120, dataIndex: 'met_usu_actualiza_nombre'},
			{ header: "<center>Fecha de actualizaci&oacute;n</center>", width: 120, dataIndex: 'met_fecha_actualizacion'}
	]
	});
	
	var crud_metodo_gridpanel = new Ext.grid.GridPanel({
		id: 'crud_metodo_gridpanel',
		title:'M&eacute;todos en el sistema',
		columnWidth: '.4',
		region:'center',
		stripeRows:true,
		frame: true,
		ds: crud_metodo_datastore,
		cm: crud_metodo_colmodel,
		plugins: crud_metodo_columnHeaderGroup,
		selModel: new Ext.grid.RowSelectionModel({
			singleSelect: true,
			listeners: {
				rowselect: function(sm, row, record) {
					//Ext.getCmp('crud_metodo_formpanel').getForm().loadRecord(record);
					Ext.getCmp('met_codigo').setValue(record.data.met_codigo);
					Ext.getCmp('met_nombre').setValue(record.data.met_nombre);
					Ext.getCmp('crud_metodo_formpanel_info_analisis').getForm().loadRecord(record);
					Ext.getCmp('crud_metodo_formpanel_info_tc').getForm().loadRecord(record);
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
		tbar:
		[
			{	
				id:'crud_metodo_agregar_boton',
				text:'Agregar',
				tooltip:'Agregar',
				iconCls:'agregar',
				handler:crud_metodo_agregar
			},'-',
			{
				text:'Eliminar',
				tooltip:'Eliminar',
				iconCls:'eliminar',
				handler:crud_metodo_eliminar
			},'-'
		]
    });
	

	/*INTEGRACION AL CONTENEDOR*/
	var crud_metodo_contenedor_panel = new Ext.Panel({
		id: 'crud_metodo_contenedor_panel',
		height: largo_panel,
		autoWidth: true,
		//width:1000,
		border: false,
		tabTip :'Aqui puedes ver, agregar, eliminar m&eacute;todos',
		monitorResize:true,
		layout:'border',
		items: 
		[
			crud_metodo_gridpanel,
			crud_metodo_formpanel
		],
		buttonAlign :'left',
		renderTo:'div_form_crud_metodo'
	});
	
	function crud_metodo_actualizar(btn){
		
		var valido=crud_metodo_verificarcampos();
		
		if(valido){
			subirDatosAjax(
				getAbsoluteUrl('crud_metodo','actualizarMetodo'),
				{
					met_codigo: met_codigo.getValue(),
					met_nombre: met_nombre.getValue(),

					met_quitar_columna: met_quitar_columna.getValue(),
					met_instalacion_columna:met_instalacion_columna.getValue(),
					met_lavado_equipo:met_lavado_equipo.getValue(),
					met_lavado_sistema:met_lavado_sistema.getValue(),
					met_estabilizacion_fase_movil:met_estabilizacion_fase_movil.getValue(),
					met_purga_sistema_fase_movil:met_purga_sistema_fase_movil.getValue(),
					met_purga_sistema_agua:met_purga_sistema_agua.getValue(),
					met_purga_sistema_sin_almacena:met_purga_sistema_sin_almacena.getValue(),
					met_tiempo_estandar:met_tiempo_estandar.getValue(),

					met_tiempo_corrida_muestra:met_tiempo_corrida_muestra.getValue(),
					met_tiempo_corrida_sistema:met_tiempo_corrida_sistema.getValue(),
					met_tiempo_corrida_curvas:met_tiempo_corrida_curvas.getValue(),
					met_tiempo_cambio_modelo:met_tiempo_cambio_modelo.getValue(),
					met_numero_inyeccion_estandar: met_numero_inyeccion_estandar.getValue(),
					met_num_inyeccion_estandar_1: met_num_inyeccion_estandar_1.getValue(),
					met_num_inyeccion_estandar_2: met_num_inyeccion_estandar_2.getValue(),
					met_num_inyeccion_estandar_3: met_num_inyeccion_estandar_3.getValue(),
					met_num_inyeccion_estandar_4: met_num_inyeccion_estandar_4.getValue(),
					met_num_inyeccion_estandar_5: met_num_inyeccion_estandar_5.getValue(),
					met_num_inyeccion_estandar_6: met_num_inyeccion_estandar_6.getValue(),
					met_num_inyeccion_estandar_7: met_num_inyeccion_estandar_7.getValue(),
					met_num_inyeccion_estandar_8: met_num_inyeccion_estandar_8.getValue(),
					
					met_num_inyec_x_mu_producto: met_num_inyec_x_mu_producto.getValue(),
					met_num_inyec_x_mu_estabilidad: met_num_inyec_x_mu_estabilidad.getValue(),
					met_num_inyec_x_mu_materi_pri: met_num_inyec_x_mu_materi_pri.getValue(),
					met_num_inyec_x_mu_estandar: met_num_inyec_x_mu_estandar.getValue(),
			
				},
				function(){
					
					Ext.getCmp('met_codigo').setValue('');
					Ext.getCmp('met_nombre').setValue('');

					Ext.getCmp('crud_metodo_formpanel_info_analisis').getForm().reset();
					Ext.getCmp('crud_metodo_formpanel_info_tc').getForm().reset();
					Ext.getCmp('crud_metodo_formpanel_info_inyec').getForm().reset();
					Ext.getCmp('crud_metodo_formpanel_info_inyec_x_mu').getForm().reset();
					
					crud_metodo_datastore.reload(); 					
				},
				function(){
				
				}
				);
		}
	}
        
	function crud_metodo_eliminar()
	{
		var cant_record=crud_metodo_gridpanel.getSelectionModel().getCount();
		
		if(cant_record > 0){
			var record = crud_metodo_gridpanel.getSelectionModel().getSelected();
			if(record.get('met_codigo')!='')
			{
				Ext.Msg.confirm('Eliminar m&eacute;todo', "Realmente desea eliminar este m&eacute;todo?", function(btn){
					if (btn == 'yes') {
					
						Ext.Msg.prompt('Eliminar m&eacute;todo', 
							'Digite la causa de la eliminaci&oacute;n de este m&eacute;todo', 
							function(btn2, text){
								if (btn2 == 'ok') {
									subirDatosAjax(
										getAbsoluteUrl('crud_metodo','eliminarMetodo'),
										{
										met_codigo:record.get('met_codigo'),
										met_causa_eliminacion:text
										},
										function(){
										crud_metodo_datastore.reload(); 
										}
									);
								}
							}
						);
					}
				});
			}
		}
		else{
			mostrarMensajeConfirmacion('Error',"Seleccione un m&eacute;todo a eliminar");
		}
	}
	
	function crud_metodo_agregar(btn, ev) {
		//crud_metodo_formpanel.getForm().reset();
		Ext.getCmp('met_codigo').setValue('');
		Ext.getCmp('met_nombre').setValue('');

		Ext.getCmp('crud_metodo_formpanel_info_analisis').getForm().reset();
		Ext.getCmp('crud_metodo_formpanel_info_tc').getForm().reset();
		Ext.getCmp('crud_metodo_formpanel_info_inyec').getForm().reset();

		Ext.getCmp('crud_metodo_actualizar_boton').setText('Guardar');
	
	}
	
	function crud_metodo_calculartiempoestandar(){
	
		var tmp_minutos_totales = 0.0;
		var tiempos_estandares_preparacion_array= new Array(met_quitar_columna, met_instalacion_columna, met_lavado_equipo, met_lavado_sistema, met_estabilizacion_fase_movil, met_purga_sistema_fase_movil, met_purga_sistema_agua, met_purga_sistema_sin_almacena);
		for (contar = 0; contar < tiempos_estandares_preparacion_array.length; contar++) {
		  tmp_minutos_totales = tmp_minutos_totales + tiempos_estandares_preparacion_array[contar].getValue();
        }
		met_tiempo_estandar.setValue(tmp_minutos_totales);
	
	}
	
	
function crud_metodo_verificarcampos(){
    var valido = true;
    
    if (!(Ext.getCmp('met_nombre').isValid() )){
        mostrarMensajeRapido('Aviso', 'Faltan campos por llenar');
        return false;
    }
    
    return valido;
}
	
