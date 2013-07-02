/*
manejo Estados - tpm labs
Desarrollado maryit sanchez
2010
*/
	
	
	//ayudas
	var ayuda_maestra_est_codigo='C&oacute;digo identificador en el sistema';
	var ayuda_maestra_est_nombre='Nombre estado';
	
	var maestra_estado_datastore = new Ext.data.Store({
        id: 'maestra_estado_datastore',
        proxy: new Ext.data.HttpProxy({
                url: getAbsoluteUrl('maestra_estado','listarEstado'),
                method: 'POST'
        }),
        baseParams:{start:0, limit:20}, 
        reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
                },[ 
			{name: 'maestra_est_codigo', type: 'int'},
			{name: 'maestra_est_nombre', type: 'string'},
			{name: 'maestra_est_fecha_registro_sistema', type: 'string'},
			{name: 'maestra_est_fecha_actualizacion',type: 'string'},
			{name: 'maestra_est_usu_crea_nombre',type: 'string'},
			{name: 'maestra_est_usu_actualiza_nombre',type: 'string'},
			{name: 'maestra_est_causa_eliminacion',type: 'string'},
			{name: 'maestra_est_causa_actualizacion',type: 'string'}
			])
        });
    maestra_estado_datastore.load();
	

	var maestra_est_codigo=new Ext.form.NumberField({
	   xtype: 'numberfield',
	   maxLength : 100,
	   name: 'maestra_est_codigo',
	   id: 'maestra_est_codigo',
	   fieldLabel: 'C&ooacute;digo estado',
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_est_codigo', ayuda_maestra_est_codigo);
					}
	   }
	});
	

	var maestra_est_nombre=new Ext.form.TextField({
	   xtype: 'textfield',
	   maxLength : 100,
	   name: 'maestra_est_nombre',
	   id: 'maestra_est_nombre',
	   fieldLabel: 'Nombre estado',
	   allowBlank: false,
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_est_nombre', ayuda_maestra_est_nombre);
					}
	   }
	});

	var maestra_estado_colmodel = new Ext.grid.ColumnModel({
		defaults:{sortable: true, locked: false, resizable: true},
		columns:[
			{ id: 'maestra_est_codigo_column', header: "Id", width: 30, dataIndex: 'maestra_est_codigo'},
			{ id: 'maestra_est_nombre_column', header: "Nombre", width: 100, dataIndex: 'maestra_est_nombre', editor:maestra_est_nombre},
			{ header: "Creado por", width: 120, dataIndex: 'maestra_est_usu_crea_nombre'},
			{ header: "Fecha de creaci&oacute;n", width: 120, dataIndex: 'maestra_est_fecha_registro_sistema'},
			{ header: "Actualizado por", width: 120, dataIndex: 'maestra_est_usu_actualiza_nombre'},
			{ header: "Fecha de actualizaci&oacute;n", width: 120, dataIndex: 'maestra_est_fecha_actualizacion'},
			{ header: "Causa actualizaci&oacute;n", width: 120, dataIndex: 'maestra_est_causa_actualizacion'},
			{ header: "Causa eliminaci&oacute;n", width: 120, dataIndex: 'maestra_est_causa_eliminacion'}
		]
	});
	

	var maestra_estado_roweditor = new Ext.ux.grid.RowEditor({
		saveText: 'Guardar',
		cancelText: 'Cancelar',
		showTooltip: function(msg){},
		listeners:
		{
			'afteredit': function(gr,obj,record,num){
				
				if(record.get('maestra_est_codigo')!=''){
				
					Ext.Msg.prompt(
					'Estado',
					'Digite la causa de la actualizaci&oacute;n de este estado',
					function(btn, text,op){
							if (btn == 'ok') {
							maestra_estado_actualizar(record,text);
							}
						}
					);
				}
				else{
					maestra_estado_actualizar(record,'');
				}
			},
			'canceledit': function(){}
		}
	});

    //CREACION DE LA GRILLA

	var maestra_estado_gridpanel = new Ext.grid.GridPanel({
		id: 'maestra_estado_gridpanel',
		title:'Estados del equipo',
		stripeRows:true,
		frame: true,
		ds: maestra_estado_datastore,
		cm: maestra_estado_colmodel,
		selModel: new Ext.grid.RowSelectionModel({
			singleSelect:true,	
			moveEditorOnEnter :false
		}),
		autoExpandColumn: 'maestra_est_nombre_column',
		height: largo_panel,
		bbar: new Ext.PagingToolbar({
			pageSize: 20,
			store: maestra_estado_datastore,
			displayInfo: true,
			displayMsg: 'Estados {0} - {1} de {2}',
			emptyMsg: "No hay estados aun"
		}),
		tbar:
		[
			{	
				id:'maestra_estado_agregar_boton',
				text:'Agregar',
				tooltip:'Agregar',
				iconCls:'agregar',
				handler:maestra_estado_agregar
			},'-',
			{
				text:'Eliminar',
				tooltip:'Eliminar',
				iconCls:'eliminar',
				handler:maestra_estado_eliminar
			},'-',{
				text:'',
				iconCls:'activos',
				tooltip:'Estado activos',
				handler:function(){
					maestra_estado_datastore.baseParams.est_eliminado = '0';
					maestra_estado_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},{
				text:'',
				iconCls:'eliminados',
				tooltip:'Estado eliminados',
				handler:function(){
					maestra_estado_datastore.baseParams.est_eliminado = '1';
					maestra_estado_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},'-',{
				text:'Restablecer',
				iconCls:'restablece',
				tooltip:'Restablecer un estado eliminado',
				handler:function(){
					 var cant_record = maestra_estado_gridpanel.getSelectionModel().getCount();
			
					if(cant_record > 0){
					var record = maestra_estado_gridpanel.getSelectionModel().getSelected();
						if (record.get('maestra_est_codigo') != '') {
					
							Ext.Msg.prompt('Restablecer estado', 
								'Digite la causa de restablecimiento', 
								function(btn, text){
									if (btn == 'ok')  {
										subirDatosAjax( 
											getAbsoluteUrl('maestra_estado', 'restablecerEstado'), 
											{
											maestra_est_codigo:record.get('maestra_est_codigo'),
											maestra_est_causa_restablece:text
											}, 
											function(){
												maestra_estado_datastore.reload();
											}, 
											function(){}
										);
									}
								}
							);
						}
					}
					else {
						mostrarMensajeConfirmacion('Error', "Seleccione un estado eliminado");
					}
				}
			}
		],
		plugins:[maestra_estado_roweditor,
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
	var maestra_estado_contenedor_panel = new Ext.Panel({
		id: 'maestra_estado_contenedor_panel',
		height: largo_panel,
		autoWidth: true,
		border: false,
		tabTip :'Aqu&iacute; puedes ver, agregar, eliminar estados',
		monitorResize:true,
		items: 
		[
			maestra_estado_gridpanel
		],
		renderTo:'div_form_maestra_estado'
	});
	

	function maestra_estado_actualizar(record,text){
		//var record = maestra_estado_gridpanel.getSelectionModel().getSelected();

		subirDatosAjax(
			getAbsoluteUrl('maestra_estado','actualizarEstado'),
			{
				maestra_est_codigo: record.get('maestra_est_codigo'),
				maestra_est_nombre: record.get('maestra_est_nombre'),
				maestra_est_causa_actualizacion: text
			},
			function(){
				maestra_estado_datastore.reload(); 
			}
		);
	}
        
	function maestra_estado_eliminar()
	{
		var cant_record = maestra_estado_gridpanel.getSelectionModel().getCount();
		
		if(cant_record > 0){
			var record = maestra_estado_gridpanel.getSelectionModel().getSelected();
			
			if(record.get('maestra_est_codigo')!='')
			{
				Ext.Msg.confirm('Eliminar estado', "Realmente desea eliminar este estado?", function(btn){
					if (btn == 'yes') {
					
						Ext.Msg.prompt('Eliminar estado', 
							'Digite la causa de la eliminaci&oacute;n de este estado', 
							function(btn2, text){
								if (btn2 == 'ok') {
									subirDatosAjax(
										getAbsoluteUrl('maestra_estado','eliminarEstado'),
										{
										maestra_est_codigo:record.get('maestra_est_codigo'),
										maestra_est_causa_eliminacion:text
										},
										function(){
										maestra_estado_datastore.reload(); 
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
			mostrarMensajeConfirmacion('Error',"Seleccione un estado a eliminar");
		}
	}

	function maestra_estado_agregar(btn, ev) {
		var row = new maestra_estado_gridpanel.store.recordType({
			maestra_est_codigo : '',
			maestra_est_nombre: ''
		});

		maestra_estado_gridpanel.getSelectionModel().clearSelections();
		maestra_estado_roweditor.stopEditing();
		maestra_estado_gridpanel.store.insert(0, row);
		maestra_estado_gridpanel.getSelectionModel().selectRow(0);
		maestra_estado_roweditor.startEditing(0);
	}
	
