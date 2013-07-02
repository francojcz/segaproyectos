/*
manejo Eventos - tpm labs
Desarrollado maryit sanchez
2010
*/
	
	
	//ayudas
	var ayuda_maestra_cat_codigo='C&oacute;digo identificador en el sistema';
	var ayuda_maestra_cat_nombre='Nombre categor&iacute;a evento';
	
	var maestra_categoriaevento_datastore = new Ext.data.Store({
        id: 'maestra_categoriaevento_datastore',
        proxy: new Ext.data.HttpProxy({
                url: getAbsoluteUrl('maestra_categoriaevento','listarCategoriaEvento'),
                method: 'POST'
        }),
        baseParams:{start:0, limit:20}, 
        reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
                },[ 
			{name: 'maestra_cat_codigo', type: 'int'},
			{name: 'maestra_cat_nombre', type: 'string'},
			{name: 'maestra_cat_fecha_registro_sistema', type: 'string'},
			{name: 'maestra_cat_fecha_actualizacion',type: 'string'},
			{name: 'maestra_cat_usu_crea_nombre',type: 'string'},
			{name: 'maestra_cat_usu_actualiza_nombre',type: 'string'},
			{name: 'maestra_cat_causa_eliminacion',type: 'string'},
			{name: 'maestra_cat_causa_actualizacion',type: 'string'}
			])
        });
    maestra_categoriaevento_datastore.load();
	

	var maestra_cat_codigo=new Ext.form.NumberField({
	   xtype: 'numberfield',
	   maxLength : 100,
	   name: 'maestra_cat_codigo',
	   id: 'maestra_cat_codigo',
	   fieldLabel: 'C&ooacute;digo evento',
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_cat_codigo', ayuda_maestra_cat_codigo);
					}
	   }
	});
	

	var maestra_cat_nombre=new Ext.form.TextField({
	   xtype: 'textfield',
	   maxLength : 100,
	   name: 'maestra_cat_nombre',
	   id: 'maestra_cat_nombre',
	   fieldLabel: 'Nombre categor&iacute;a',
	   allowBlank: false,
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_cat_nombre', ayuda_maestra_cat_nombre);
					}
	   }
	});

	var maestra_categoriaevento_colmodel = new Ext.grid.ColumnModel({
		defaults:{sortable: true, locked: false, resizable: true},
		columns:[
			{id: 'maestra_cat_codigo_column', header: "Id", width: 30, dataIndex: 'maestra_cat_codigo'},
			{ id: 'maestra_cat_nombre_column', header: "Nombre", width: 100, dataIndex: 'maestra_cat_nombre', editor:maestra_cat_nombre},
			{ header: "Creado por", width: 120, dataIndex: 'maestra_cat_usu_crea_nombre'},
			{ header: "Fecha de creaci&oacute;n", width: 120, dataIndex: 'maestra_cat_fecha_registro_sistema'},
			{ header: "Actualizado por", width: 120, dataIndex: 'maestra_cat_usu_actualiza_nombre'},
			{ header: "Fecha de actualizaci&oacute;n", width: 120, dataIndex: 'maestra_cat_fecha_actualizacion'},
			{ header: "Causa actualizaci&oacute;n", width: 120, dataIndex: 'maestra_cat_causa_actualizacion'},
			{ header: "Causa eliminaci&oacute;n", width: 120, dataIndex: 'maestra_cat_causa_eliminacion'}
		]
	});
	

	var maestra_categoriaevento_roweditor = new Ext.ux.grid.RowEditor({
		saveText: 'Guardar',
		cancelText: 'Cancelar',
		showTooltip: function(msg){},
		listeners:
		{
			'afteredit': function(gr,obj,record,num){
				
				if(record.get('maestra_cat_codigo')!=''){
				
					Ext.Msg.prompt(
					'Categor&iacute;a',
					'Digite la causa de la actualizaci&oacute;n de esta categor&iacute;a',
					function(btn, text,op){
							if (btn == 'ok') {
							maestra_categoriaevento_actualizar(record,text);
							}
						}
					);
				}
				else{
					maestra_categoriaevento_actualizar(record,'');
				}
			},
			'canceledit': function(){}
		}
	});

    //CREACION DE LA GRILLA

	var maestra_categoriaevento_gridpanel = new Ext.grid.GridPanel({
		id: 'maestra_categoriaevento_gridpanel',
		title:'Categor&iacute;as de Eventos',
		stripeRows:true,
		frame: true,
		ds: maestra_categoriaevento_datastore,
		cm: maestra_categoriaevento_colmodel,
		selModel: new Ext.grid.RowSelectionModel({
			singleSelect:true,	
			moveEditorOnEnter :false
		}),
		autoExpandColumn: 'maestra_cat_nombre_column',
		height: largo_panel,
		bbar: new Ext.PagingToolbar({
			pageSize: 20,
			store: maestra_categoriaevento_datastore,
			displayInfo: true,
			displayMsg: 'Categor&iacute;as de Eventos {0} - {1} de {2}',
			emptyMsg: "No hay categor&iacute;as de eventos aun"
		}),
		tbar:
		[
			{	
				id:'maestra_categoriaevento_agregar_boton',
				text:'Agregar',
				tooltip:'Agregar',
				iconCls:'agregar',
				handler:maestra_categoriaevento_agregar
			},'-',
			{
				text:'Eliminar',
				tooltip:'Eliminar',
				iconCls:'eliminar',
				handler:maestra_categoriaevento_eliminar
			},'-',{
				text:'',
				iconCls:'activos',
				tooltip:'Categor&iacute;as activas',
				handler:function(){
					maestra_categoriaevento_datastore.baseParams.cat_eliminado = '0';
					maestra_categoriaevento_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},{
				text:'',
				iconCls:'eliminados',
				tooltip:'Categor&iacute;as eliminadas',
				handler:function(){
					maestra_categoriaevento_datastore.baseParams.cat_eliminado = '1';
					maestra_categoriaevento_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},'-',{
				text:'Restablecer',
				iconCls:'restablece',
				tooltip:'Restablecer un categor&iacute;a eliminada',
				handler:function(){
					 var cant_record = maestra_categoriaevento_gridpanel.getSelectionModel().getCount();
			
					if(cant_record > 0){
					var record = maestra_categoriaevento_gridpanel.getSelectionModel().getSelected();
						if (record.get('maestra_cat_codigo') != '') {
					
							Ext.Msg.prompt('Restablecer categor&iacute;as', 
								'Digite la causa de restablecimiento', 
								function(btn, text){
									if (btn == 'ok')  {
										subirDatosAjax( 
											getAbsoluteUrl('maestra_categoriaevento', 'restablecerCategoriaEvento'), 
											{
											maestra_cat_codigo:record.get('maestra_cat_codigo'),
											maestra_cat_causa_restablece:text
											}, 
											function(){
												maestra_categoriaevento_datastore.reload();
											}, 
											function(){}
										);
									}
								}
							);
						}
					}
					else {
						mostrarMensajeConfirmacion('Error', "Seleccione un categoriaevento eliminado");
					}
				}
			}
		],
		plugins:[maestra_categoriaevento_roweditor,
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
	var maestra_categoriaevento_contenedor_panel = new Ext.Panel({
		id: 'maestra_categoriaevento_contenedor_panel',
		height: largo_panel,
		autoWidth: true,
		border: false,
		tabTip :'Aqu&iacute; puedes ver, agregar, eliminar categor&iacute;a de eventos',
		monitorResize:true,
		items: 
		[
			maestra_categoriaevento_gridpanel
		],
		renderTo:'div_form_maestra_categoriaevento'
	});
	

	function maestra_categoriaevento_actualizar(record,text){
	//	var record = maestra_categoriaevento_gridpanel.getSelectionModel().getSelected();

		subirDatosAjax(
			getAbsoluteUrl('maestra_categoriaevento','actualizarCategoriaEvento'),
			{
				maestra_cat_codigo: record.get('maestra_cat_codigo'),
				maestra_cat_nombre: record.get('maestra_cat_nombre'),
				maestra_cat_causa_actualizacion: text
			},
			function(){
				maestra_categoriaevento_datastore.reload(); 
			}
		);
	}
        
	function maestra_categoriaevento_eliminar()
	{
		var cant_record = maestra_categoriaevento_gridpanel.getSelectionModel().getCount();
		
		if(cant_record > 0){
			var record = maestra_categoriaevento_gridpanel.getSelectionModel().getSelected();
			if(record.get('maestra_cat_codigo')!='')
			{
				Ext.Msg.confirm('Eliminar categor&iacute;a', "Realmente desea eliminar esta categor&iacute;a?", function(btn){
					if (btn == 'yes') {
					
						Ext.Msg.prompt('Eliminar categor&iacute;a', 
							'Digite la causa de la eliminaci&oacute;n de esta categor&iacute;a', 
							function(btn2, text){
								if (btn2 == 'ok') {
									subirDatosAjax(
										getAbsoluteUrl('maestra_categoriaevento','eliminarCategoriaEvento'),
										{
										maestra_cat_codigo:record.get('maestra_cat_codigo'),
										maestra_cat_causa_eliminacion:text
										},
										function(){
										maestra_categoriaevento_datastore.reload(); 
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
			mostrarMensajeConfirmacion('Error',"Seleccione una categor&iacute;a de eventos a eliminar");
		}
	}

	function maestra_categoriaevento_agregar(btn, ev) {
		var row = new maestra_categoriaevento_gridpanel.store.recordType({
			maestra_cat_codigo : '',
			maestra_cat_nombre: ''
		});

		maestra_categoriaevento_gridpanel.getSelectionModel().clearSelections();
		maestra_categoriaevento_roweditor.stopEditing();
		maestra_categoriaevento_gridpanel.store.insert(0, row);
		maestra_categoriaevento_gridpanel.getSelectionModel().selectRow(0);
		maestra_categoriaevento_roweditor.startEditing(0);
	}
	
