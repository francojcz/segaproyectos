
/*
gestion maquinas - tpm labs
Desarrollado maryit sanchez
2010
*/
	/*
create table META_ANUAL_X_INDICADOR
(
   MEA_IND_CODIGO       int not null,
   MEA_EMP_CODIGO       int not null,
   MEA_ANIO             int not null,
   MEA_VALOR            numeric(8,2),
   MEA_FECHA_REGISTRO_SISTEMA datetime
);*/
var largo_panel=500;
	var ayuda_mea_valor='Ingrese la meta que se desea tener este a&ntilde;o para este indicador';
	var ayuda_mea_anio='Seleccione un a&ntilde;o';
	var mea_fecha = new Date();
	
	var gestion_metaanual_datastore = new Ext.data.Store({
        id: 'gestion_metaanual_datastore',
        proxy: new Ext.data.HttpProxy({
                url: getAbsoluteUrl('gestion_metaanual','listarMetaanual'),
                method: 'POST'
        }),
        baseParams:{start:0, limit:20,mea_anio:mea_fecha.getFullYear()}, 
        reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
                },[ 
			{name: 'mea_ind_codigo', type: 'int'},
			{name: 'mea_emp_codigo', type: 'int'},
			{name: 'mea_anio', type: 'int'},
			{name: 'mea_ind_nombre', type: 'string'},
			{name: 'mea_valor', type: 'string'},
			{name: 'mea_fecha_registro_sistema', type: 'string'},
			{name: 'mea_fecha_actualizacion',type: 'string'},
			{name: 'mea_usu_crea_nombre',type: 'string'},
			{name: 'mea_usu_actualiza_nombre',type: 'string'}
			])
        });
    gestion_metaanual_datastore.load();
	

	var mea_valor=new Ext.form.NumberField({
	   xtype: 'numberfield',
	   maxLength: 8,
	   allowDecimal: true,
	   //decimalPrecision: 2,
	   allowDecimals :true,
	   allowNegative :false,
	   decimalPrecision :2,
	   name: 'mea_valor',
	   id: 'mea_valor',
	   fieldLabel: 'Valor',
	   emptyText: '-',
	   listeners:
	   {
			'render': function() {
				ayuda('mea_valor', ayuda_mea_valor);
			}
	   }
	});
	
	
	
	var mea_anio = new Ext.form.SpinnerField({
		xtype: 'spinnerfield',
		fieldLabel: 'A&ntilde;o',
		id: 'mea_anio',
		name: 'mea_anio',
		minValue: mea_fecha.getFullYear()-10,
		maxValue: mea_fecha.getFullYear(),
		value: mea_fecha.getFullYear(),
		width: 80,
		accelerate: true,
		listeners:
		{
			'spin':function(){ 
				
				gestion_metaanual_datastore.baseParams.mea_anio = mea_anio.getValue();
				gestion_metaanual_datastore.load({
					params: {
						start: 0,
						limit: 20
					}
				});
			}
		}
	});
	
	var gestion_metaanual_colmodel = new Ext.grid.ColumnModel({
		defaults:{sortable: true, locked: false, resizable: true},
		columns:[
			//{ header: "ind codigo", width: 30, dataIndex: 'mea_ind_codigo', type: 'int'},
			//{ header: "empresa codigo", width: 30, dataIndex: 'mea_emp_codigo', type: 'int'},
			{ header: "A&ntilde;o", width: 50, dataIndex: 'mea_anio', type: 'int'},
			{ id:'mea_ind_nombre_column', header: "Indicador", width: 200, dataIndex: 'mea_ind_nombre', type: 'string'},
			{ header: "Valor meta", width: 100, dataIndex: 'mea_valor', type: 'float',editor:mea_valor},
			{ header: "Creado por", width: 120, dataIndex: 'mea_usu_crea_nombre'},
			{ header: "Fecha de creaci&oacute;n", width: 120, dataIndex: 'mea_fecha_registro_sistema'},
			{ header: "Actualizado por", width: 120, dataIndex: 'mea_usu_actualiza_nombre'},
			{ header: "Fecha de actualizaci&oacute;n", width: 120, dataIndex: 'mea_fecha_actualizacion'}
		]
	});
	

	var gestion_metaanual_roweditor = new Ext.ux.grid.RowEditor({
		saveText: 'Guardar',
		cancelText: 'Cancelar',
		showTooltip: function(msg){},
		listeners:
		{
			'afteredit': function(){
				gestion_metaanual_actualizar();
			},
			'canceledit': function(){}
		}
	});

    //CREACION DE LA GRILLA

	var gestion_metaanual_gridpanel = new Ext.grid.GridPanel({
		id: 'gestion_metaanual_gridpanel',
		title:'Metas anuales',
		stripeRows:true,
		frame: true,
		ds: gestion_metaanual_datastore,
		cm: gestion_metaanual_colmodel,
		selModel: new Ext.grid.RowSelectionModel({
			singleSelect:true,	
			moveEditorOnEnter :false
		}),
		height: largo_panel,
		bbar: new Ext.PagingToolbar({
			pageSize: 20,
			store: gestion_metaanual_datastore,
			displayInfo: true,
			displayMsg: 'Metas anuales {0} - {1} de {2}',
			emptyMsg: "No hay metas aun"
		}),
		tbar:
		[
			{xtype:'button',text:'A&ntilde;o:'},
			mea_anio
		],
		plugins:[gestion_metaanual_roweditor,
		    new Ext.ux.grid.Search({
				mode:          'local',
				position:      top,
				searchText:    'Filtrar',
				iconCls:  'filtrar',
				selectAllText: 'Seleccionar todos',
				searchTipText: 'Escriba el texto que desea buscar y presione la tecla enter',
				width:         100
			})
		]
    });
	

	/*INTEGRACION AL CONTENEDOR*/
	var gestion_metaanual_contenedor_panel = new Ext.Panel({
		id: 'gestion_metaanual_contenedor_panel',
		height: largo_panel,
		autoWidth: true,
		border: false,
		tabTip :'Aqu&iacute; puedes ver, agregar,y modificar las metas',
		monitorResize:true,
		items: 
		[
			gestion_metaanual_gridpanel
		],
		buttonAlign :'left',
		renderTo:'div_form_gestion_metaanual'
	});
	

	function gestion_metaanual_actualizar(){
		var record = gestion_metaanual_gridpanel.getSelectionModel().getSelected();

		subirDatosAjax(
			getAbsoluteUrl('gestion_metaanual','actualizarMeta'),
			{
				mea_ind_codigo : record.data.mea_ind_codigo,//.getValue(),
				mea_emp_codigo : record.data.mea_emp_codigo,//.getValue(),
				mea_anio : mea_anio.getValue(),
				mea_ind_nombre : record.data.mea_ind_nombre,//.getValue(),
				mea_valor : mea_valor.getValue()
			},
			function(){
				gestion_metaanual_datastore.reload(); 
			}
		);
	}
        
