/*
manejo Tipo identificacion - tpm labs
Desarrollado maryit sanchez
2010
*/
	
	
	//ayudas
	var ayuda_maestra_tid_codigo='C&oacute;digo identificador en el sistema';
	var ayuda_maestra_tid_nombre='Tipo identificaci&oacute;n';
	
	var maestra_tipoidentificacion_datastore = new Ext.data.Store({
        id: 'maestra_tipoidentificacion_datastore',
        proxy: new Ext.data.HttpProxy({
                url: getAbsoluteUrl('maestra_tipoidentificacion','listarTipoidentificacion'),
                method: 'POST'
        }),
        baseParams:{start:0, limit:20}, 
        reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
                },[ 
			{name: 'maestra_tid_codigo', type: 'int'},
			{name: 'maestra_tid_nombre', type: 'string'},
			{name: 'maestra_tid_fecha_registro_sistema', type: 'string'},
			{name: 'maestra_tid_fecha_actualizacion',type: 'string'},
			{name: 'maestra_tid_usu_crea_nombre',type: 'string'},
			{name: 'maestra_tid_usu_actualiza_nombre',type: 'string'},
			{name: 'maestra_tid_causa_eliminacion',type: 'string'},
			{name: 'maestra_tid_causa_actualizacion',type: 'string'}
			])
        });
    maestra_tipoidentificacion_datastore.load();
	

	var maestra_tid_codigo=new Ext.form.NumberField({
	   xtype: 'numberfield',
	   maxLength : 100,
	   name: 'maestra_tid_codigo',
	   id: 'maestra_tid_codigo',
	   fieldLabel: 'C&ooacute;digo tipo identificacion',
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_tid_codigo', ayuda_maestra_tid_codigo);
					}
	   }
	});
	

	var maestra_tid_nombre=new Ext.form.TextField({
	   xtype: 'textfield',
	   maxLength : 100,
	   name: 'maestra_tid_nombre',
	   id: 'maestra_tid_nombre',
	   fieldLabel: 'Nombre tipo identificacion',
	   allowBlank: false,
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_tid_nombre', ayuda_maestra_tid_nombre);
					}
	   }
	});

	var maestra_tipoidentificacion_colmodel = new Ext.grid.ColumnModel({
		defaults:{sortable: true, locked: false, resizable: true},
		columns:[
			{ id: 'maestra_tid_codigo_column', header: "Id", width: 30, dataIndex: 'maestra_tid_codigo'},
			{ id: 'maestra_tid_nombre_column', header: "Nombre", width: 100, dataIndex: 'maestra_tid_nombre', editor:maestra_tid_nombre},
			{ header: "Creado por", width: 120, dataIndex: 'maestra_tid_usu_crea_nombre'},
			{ header: "Fecha de creaci&oacute;n", width: 120, dataIndex: 'maestra_tid_fecha_registro_sistema'},
			{ header: "Actualizado por", width: 120, dataIndex: 'maestra_tid_usu_actualiza_nombre'},
			{ header: "Fecha de actualizaci&oacute;n", width: 120, dataIndex: 'maestra_tid_fecha_actualizacion'},
			{ header: "Causa actualizaci&oacute;n", width: 120, dataIndex: 'maestra_tid_causa_actualizacion'},
			{ header: "Causa eliminaci&oacute;n", width: 120, dataIndex: 'maestra_tid_causa_eliminacion'}
		]
	});
	

	var maestra_tipoidentificacion_roweditor = new Ext.ux.grid.RowEditor({
		saveText: 'Guardar',
		cancelText: 'Cancelar',
		showTooltip: function(msg){},
		listeners:
		{
			'afteredit': function(gr,obj,record,num){
				
				if(record.get('maestra_tid_codigo')!=''){
				
					Ext.Msg.prompt(
					'Tipo de identificaci&oacute;n',
					'Digite la causa de la actualizaci&oacute;n de este tipo de identificaci&oacute;n',
					function(btn, text,op){
							if (btn == 'ok') {
							maestra_tipoidentificacion_actualizar(record,text);
							}
						}
					);
				}
				else{
					maestra_tipoidentificacion_actualizar(record,'');
				}
				
			},
			'canceledit': function(){}
		}
	});

    //CREACION DE LA GRILLA

	var maestra_tipoidentificacion_gridpanel = new Ext.grid.GridPanel({
		id: 'maestra_tipoidentificacion_gridpanel',
		title:'Tipos de identificaci&oacute;n',
		stripeRows:true,
		frame: true,
		ds: maestra_tipoidentificacion_datastore,
		cm: maestra_tipoidentificacion_colmodel,
		selModel: new Ext.grid.RowSelectionModel({
			singleSelect:true,	
			moveEditorOnEnter :false
		}),
		autoExpandColumn: 'maestra_tid_nombre_column',
		height: largo_panel,
		bbar: new Ext.PagingToolbar({
			pageSize: 20,
			store: maestra_tipoidentificacion_datastore,
			displayInfo: true,
			displayMsg: 'Tipos de identificaci&oacute;n {0} - {1} de {2}',
			emptyMsg: "No hay tipos de identificaci&oacute;n aun"
		}),
		tbar:
		[
			{	
				id:'maestra_tipoidentificacion_agregar_boton',
				text:'Agregar',
				tooltip:'Agregar',
				iconCls:'agregar',
				handler:maestra_tipoidentificacion_agregar
			},'-',
			{
				text:'Eliminar',
				tooltip:'Eliminar',
				iconCls:'eliminar',
				handler:maestra_tipoidentificacion_eliminar
			},'-',{
				text:'',
				iconCls:'activos',
				tooltip:'Tipos de identificaci&oacute;n activos',
				handler:function(){
					maestra_tipoidentificacion_datastore.baseParams.tid_eliminado = '0';
					maestra_tipoidentificacion_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},{
				text:'',
				iconCls:'eliminados',
				tooltip:'Tipos de identificaci&oacute;n eliminados',
				handler:function(){
					maestra_tipoidentificacion_datastore.baseParams.tid_eliminado = '1';
					maestra_tipoidentificacion_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},'-',{
				text:'Restablecer',
				iconCls:'restablece',
				tooltip:'Restablecer un tipo identificaci&oacute;n eliminado',
				handler:function(){
					 var cant_record = maestra_tipoidentificacion_gridpanel.getSelectionModel().getCount();
			
					if(cant_record > 0){
					var record = maestra_tipoidentificacion_gridpanel.getSelectionModel().getSelected();
						if (record.get('maestra_tid_codigo') != '') {
					
							Ext.Msg.prompt('Restablecer tipo identificaci&oacute;n', 
								'Digite la causa de restablecimiento', 
								function(btn, text){
									if (btn == 'ok')  {
										subirDatosAjax( 
											getAbsoluteUrl('maestra_tipoidentificacion', 'restablecerTipoIdentificacion'), 
											{
											maestra_tid_codigo:record.get('maestra_tid_codigo'),
											maestra_tid_causa_restablece:text
											}, 
											function(){
												maestra_tipoidentificacion_datastore.reload();
											}, 
											function(){}
										);
									}
								}
							);
						}
					}
					else {
						mostrarMensajeConfirmacion('Error', "Seleccione un tipo identificaci&oacute;n eliminado");
					}
				}
			}
		],
		plugins:[maestra_tipoidentificacion_roweditor,
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
	var maestra_tipoidentificacion_contenedor_panel = new Ext.Panel({
		id: 'maestra_tipoidentificacion_contenedor_panel',
		height: largo_panel,
		autoWidth: true,
		border: false,
		tabTip :'Aqu&iacute; puedes ver, agregar, eliminar tipos de identificaci&oacute;n',
		monitorResize:true,
		items: 
		[
			maestra_tipoidentificacion_gridpanel
		],
		renderTo:'div_form_maestra_tipoidentificacion'
	});
	

	function maestra_tipoidentificacion_actualizar(record,text){
			//--var record = maestra_tipoidentificacion_gridpanel.getSelectionModel().getSelected();

			subirDatosAjax(
				getAbsoluteUrl('maestra_tipoidentificacion','actualizarTipoidentificacion'),
				{
					maestra_tid_codigo: record.get('maestra_tid_codigo'),
					maestra_tid_nombre: record.get('maestra_tid_nombre'),
					maestra_tid_causa_actualizacion: text
				},
				function(){
					maestra_tipoidentificacion_datastore.reload(); 
				}
			);
		
	}
        
	function maestra_tipoidentificacion_eliminar()
	{
		var cant_record = maestra_tipoidentificacion_gridpanel.getSelectionModel().getCount();
		if(cant_record > 0){
			var record = maestra_tipoidentificacion_gridpanel.getSelectionModel().getSelected();
			
			if(record.get('maestra_tid_codigo')!='')
			{
				Ext.Msg.confirm('Eliminar tipo de identificaci&oacute;n', "Realmente desea eliminar este tipo de identificaci&oacute;n?", function(btn){
					if (btn == 'yes') {
					
						Ext.Msg.prompt('Eliminar tipo de identificaci&oacute;n', 
							'Digite la causa de la eliminaci&oacute;n de este tipo de identificaci&oacute;n', 
							function(btn2, text){
								if (btn2 == 'ok') {
									subirDatosAjax(
										getAbsoluteUrl('maestra_tipoidentificacion','eliminarTipoidentificacion'),
										{
										maestra_tid_codigo:record.get('maestra_tid_codigo'),
										maestra_tid_causa_eliminacion:text
										},
										function(){
										maestra_tipoidentificacion_datastore.reload(); 
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
			mostrarMensajeConfirmacion('Error',"Seleccione un tipo de identificaci&oacute;n a eliminar");
		}
	}

	function maestra_tipoidentificacion_agregar(btn, ev) {
		var row = new maestra_tipoidentificacion_gridpanel.store.recordType({
			maestra_tid_codigo : '',
			maestra_tid_nombre: ''
		});

		maestra_tipoidentificacion_gridpanel.getSelectionModel().clearSelections();
		maestra_tipoidentificacion_roweditor.stopEditing();
		maestra_tipoidentificacion_gridpanel.store.insert(0, row);
		maestra_tipoidentificacion_gridpanel.getSelectionModel().selectRow(0);
		maestra_tipoidentificacion_roweditor.startEditing(0);
	}
	
