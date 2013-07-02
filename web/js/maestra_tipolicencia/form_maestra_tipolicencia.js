/*
manejo Tipo licencia - tpm labs
Desarrollado maryit sanchez
2010
*/
	
	
	//ayudas
	var ayuda_maestra_tli_codigo='C&oacute;digo identificador en el sistema';
	var ayuda_maestra_tli_nombre='Nombre tipolicencia';
	
	var maestra_tipolicencia_datastore = new Ext.data.Store({
        id: 'maestra_tipolicencia_datastore',
        proxy: new Ext.data.HttpProxy({
                url: getAbsoluteUrl('maestra_tipolicencia','listarTipolicencia'),
                method: 'POST'
        }),
        baseParams:{start:0, limit:20}, 
        reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
                },[ 
			{name: 'maestra_tli_codigo', type: 'int'},
			{name: 'maestra_tli_nombre', type: 'string'},
			{name: 'maestra_tli_fecha_registro_sistema', type: 'string'},
			{name: 'maestra_tli_fecha_actualizacion',type: 'string'},
			{name: 'maestra_tli_usu_crea_nombre',type: 'string'},
			{name: 'maestra_tli_usu_actualiza_nombre',type: 'string'}
			])
        });
    maestra_tipolicencia_datastore.load();
	

	var maestra_tli_codigo=new Ext.form.NumberField({
	   xtype: 'numberfield',
	   maxLength : 100,
	   name: 'maestra_tli_codigo',
	   id: 'maestra_tli_codigo',
	   fieldLabel: 'C&ooacute;digo tipolicencia',
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_tli_codigo', ayuda_maestra_tli_codigo);
					}
	   }
	});
	

	var maestra_tli_nombre=new Ext.form.TextField({
	   xtype: 'textfield',
	   maxLength : 20,
	   name: 'maestra_tli_nombre',
	   id: 'maestra_tli_nombre',
	   fieldLabel: 'Nombre tipolicencia',
	   allowBlank: false,
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_tli_nombre', ayuda_maestra_tli_nombre);
					}
	   }
	});

	var maestra_tipolicencia_colmodel = new Ext.grid.ColumnModel({
		defaults:{sortable: true, locked: false, resizable: true},
		columns:[
			{ id: 'maestra_tli_codigo_column', header: "Id", width: 30, dataIndex: 'maestra_tli_codigo'},
			{ id: 'maestra_tli_nombre_column', header: "Nombre", width: 100, dataIndex: 'maestra_tli_nombre', editor:maestra_tli_nombre},
			{ header: "Creado por", width: 120, dataIndex: 'maestra_tli_usu_crea_nombre'},
			{ header: "Fecha de creaci&oacute;n", width: 120, dataIndex: 'maestra_tli_fecha_registro_sistema'},
			{ header: "Actualizado por", width: 120, dataIndex: 'maestra_tli_usu_actualiza_nombre'},
			{ header: "Fecha de actualizaci&oacute;n", width: 120, dataIndex: 'maestra_tli_fecha_actualizacion'}
		]
	});
	

	var maestra_tipolicencia_roweditor = new Ext.ux.grid.RowEditor({
		saveText: 'Guardar',
		cancelText: 'Cancelar',
		showTooltip: function(msg){},
		listeners:
		{
			'afteredit': function(){
				maestra_tipolicencia_actualizar();
			},
			'canceledit': function(){}
		}
	});

    //CREACION DE LA GRILLA

	var maestra_tipolicencia_gridpanel = new Ext.grid.GridPanel({
		id: 'maestra_tipolicencia_gridpanel',
		title:'Tipos de licencias',
		stripeRows:true,
		frame: true,
		ds: maestra_tipolicencia_datastore,
		cm: maestra_tipolicencia_colmodel,
		selModel: new Ext.grid.RowSelectionModel({
			singleSelect:true,	
			moveEditorOnEnter :false
		}),
		autoExpandColumn: 'maestra_tli_nombre_column',
		height: largo_panel,
		bbar: new Ext.PagingToolbar({
			pageSize: 20,
			store: maestra_tipolicencia_datastore,
			displayInfo: true,
			displayMsg: 'Tipos de licencias {0} - {1} de {2}',
			emptyMsg: "No hay tipos de licencias aun"
		}),
		tbar:
		[
			{	
				id:'maestra_tipolicencia_agregar_boton',
				text:'Agregar',
				tooltip:'Agregar',
				iconCls:'agregar',
				handler:maestra_tipolicencia_agregar
			},'-',
			{
				text:'Eliminar',
				tooltip:'Eliminar',
				iconCls:'eliminar',
				handler:maestra_tipolicencia_eliminar
			}
		],
		plugins:[maestra_tipolicencia_roweditor,
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
	var maestra_tipolicencia_contenedor_panel = new Ext.Panel({
		id: 'maestra_tipolicencia_contenedor_panel',
		height: largo_panel,
		autoWidth: true,
		border: false,
		tabTip :'Aqu&iacute; puedes ver, agregar, eliminar tipos de licencias',
		monitorResize:true,
		items: 
		[
			maestra_tipolicencia_gridpanel
		],
		renderTo:'div_form_maestra_tipolicencia'
	});
	

	function maestra_tipolicencia_actualizar(){
		var record = maestra_tipolicencia_gridpanel.getSelectionModel().getSelected();

		subirDatosAjax(
			getAbsoluteUrl('maestra_tipolicencia','actualizarTipolicencia'),
			{
				maestra_tli_codigo: record.get('maestra_tli_codigo'),
				maestra_tli_nombre: record.get('maestra_tli_nombre')
			},
			function(){
				maestra_tipolicencia_datastore.reload(); 
			}
		);
	}
        
	function maestra_tipolicencia_eliminar()
	{
		var cant_record = maestra_tipolicencia_gridpanel.getSelectionModel().getCount();

		if(cant_record > 0){
			var record = maestra_tipolicencia_gridpanel.getSelectionModel().getSelected();
			if(record.get('maestra_tli_codigo')!='')
			{
				subirDatosAjax(
					getAbsoluteUrl('maestra_tipolicencia','eliminarTipolicencia'),
					{
					maestra_tli_codigo:record.get('maestra_tli_codigo')
					},
					function(){
					maestra_tipolicencia_datastore.reload(); 
					}
				);
			}
		}
		else{
			mostrarMensajeConfirmacion('Error',"Seleccione un tipo de licencia a eliminar");
		}
	}

	function maestra_tipolicencia_agregar(btn, ev) {
		var row = new maestra_tipolicencia_gridpanel.store.recordType({
			maestra_tli_codigo : '',
			maestra_tli_nombre: ''
		});

		maestra_tipolicencia_gridpanel.getSelectionModel().clearSelections();
		maestra_tipolicencia_roweditor.stopEditing();
		maestra_tipolicencia_gridpanel.store.insert(0, row);
		maestra_tipolicencia_gridpanel.getSelectionModel().selectRow(0);
		maestra_tipolicencia_roweditor.startEditing(0);
	}
	
