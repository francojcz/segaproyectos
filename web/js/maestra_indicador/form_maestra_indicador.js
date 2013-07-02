/*
manejo Indicadores - tpm labs
Desarrollado maryit sanchez
2010
*/
	
	
	//ayudas
	var ayuda_maestra_ind_codigo='C&oacute;digo identificador en el sistema';
	var ayuda_maestra_ind_nombre='Nombre indicador';
	var ayuda_maestra_ind_sigla='Sigla indicador';
	var ayuda_maestra_ind_unidad='Unidad en que se mide el indicador, ejemplo: unidad de tiempo(Hrs,Min), porcentual (%),monetaria ($)';
	
	
	var maestra_indicador_datastore = new Ext.data.Store({
        id: 'maestra_indicador_datastore',
        proxy: new Ext.data.HttpProxy({
                url: getAbsoluteUrl('maestra_indicador','listarIndicador'),
                method: 'POST'
        }),
        baseParams:{start:0, limit:20}, 
        reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
                },[ 
			{name: 'maestra_ind_codigo', type: 'int'},
			{name: 'maestra_ind_nombre', type: 'string'},
			{name: 'maestra_ind_sigla', type: 'string'},
			{name: 'maestra_ind_unidad', type: 'string'},
			{name: 'maestra_ind_fecha_registro_sistema', type: 'string'},
			{name: 'maestra_ind_fecha_actualizacion',type: 'string'},
			{name: 'maestra_ind_usu_crea_nombre',type: 'string'},
			{name: 'maestra_ind_usu_actualiza_nombre',type: 'string'},
			{name: 'maestra_ind_causa_eliminacion',type: 'string'},
			{name: 'maestra_ind_causa_actualizacion',type: 'string'}
			])
        });
    maestra_indicador_datastore.load();
	

	var maestra_ind_codigo=new Ext.form.NumberField({
	   xtype: 'numberfield',
	   maxLength : 100,
	   name: 'maestra_ind_codigo',
	   id: 'maestra_ind_codigo',
	   fieldLabel: 'C&ooacute;digo indicador',
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_ind_codigo', ayuda_maestra_ind_codigo);
					}
	   }
	});
	

	var maestra_ind_nombre=new Ext.form.TextField({
	   xtype: 'textfield',
	   maxLength : 100,
	   name: 'maestra_ind_nombre',
	   id: 'maestra_ind_nombre',
	   fieldLabel: 'Nombre indicador',
	   allowBlank: false,
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_ind_nombre', ayuda_maestra_ind_nombre);
					}
	   }
	});
	/*
	var maestra_ind_sigla=new Ext.form.TextField({
	   xtype: 'textfield',
	   maxLength : 100,
	   name: 'maestra_ind_sigla',
	   id: 'maestra_ind_sigla',
	   fieldLabel: 'Sigla',
	   allowBlank: false,
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_ind_sigla', ayuda_maestra_ind_sigla);
					}
	   }
	});*/
		
	var maestra_ind_unidad=new Ext.form.TextField({
	   xtype: 'textfield',
	   maxLength : 100,
	   name: 'maestra_ind_unidad',
	   id: 'maestra_ind_unidad',
	   fieldLabel: 'Unidad',
	   allowBlank: false,
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_ind_unidad', ayuda_maestra_ind_unidad);
					}
	   }
	});

	var maestra_indicador_colmodel = new Ext.grid.ColumnModel({
		defaults:{sortable: true, locked: false, resizable: true},
		columns:[
			{ id: 'maestra_ind_codigo_column', header: "Id", width: 30, dataIndex: 'maestra_ind_codigo'},
			{ id: 'maestra_ind_nombre_column', header: "Nombre", width: 120, dataIndex: 'maestra_ind_nombre', editor:maestra_ind_nombre},
			{ header: "Sigla", width: 100, dataIndex: 'maestra_ind_sigla'},//, editor:maestra_ind_sigla},
			{ header: "Unidad", width: 100, dataIndex: 'maestra_ind_unidad', editor:maestra_ind_unidad},
			{ header: "Creado por", width: 120, dataIndex: 'maestra_ind_usu_crea_nombre'},
			{ header: "Fecha de creaci&oacute;n", width: 120, dataIndex: 'maestra_ind_fecha_registro_sistema'},
			{ header: "Actualizado por", width: 120, dataIndex: 'maestra_ind_usu_actualiza_nombre'},
			{ header: "Fecha de actualizaci&oacute;n", width: 120, dataIndex: 'maestra_ind_fecha_actualizacion'},
			{ header: "Causa actualizaci&oacute;n", width: 120, dataIndex: 'maestra_ind_causa_actualizacion'},
			{ header: "Causa eliminaci&oacute;n", width: 120, dataIndex: 'maestra_ind_causa_eliminacion'}
		]
	});
	

	var maestra_indicador_roweditor = new Ext.ux.grid.RowEditor({
		saveText: 'Guardar',
		cancelText: 'Cancelar',
		showTooltip: function(msg){},
		listeners:
		{
			'afteredit': function(gr,obj,record,num){
				
				if(record.get('maestra_maestra_ind_codigo')!=''){
				
					Ext.Msg.prompt(
					'Indicador',
					'Digite la causa de la actualizaci&oacute;n de este indicador',
					function(btn, text,op){
							if (btn == 'ok') {
							maestra_indicador_actualizar(record,text);
							}
						}
					);
				}
				else{
					maestra_indicador_actualizar(record,'');
				}
			},
			'canceledit': function(){}
		}
	});

    //CREACION DE LA GRILLA

	var maestra_indicador_gridpanel = new Ext.grid.GridPanel({
		id: 'maestra_indicador_gridpanel',
		title:'Indicadores',
		stripeRows:true,
		frame: true,
		ds: maestra_indicador_datastore,
		cm: maestra_indicador_colmodel,
		selModel: new Ext.grid.RowSelectionModel({
			singleSelect:true,	
			moveEditorOnEnter :false
		}),
		autoExpandColumn: 'maestra_ind_nombre_column',
		autoExpandMin :180,
		height: largo_panel,
		bbar: new Ext.PagingToolbar({
			pageSize: 20,
			store: maestra_indicador_datastore,
			displayInfo: true,
			displayMsg: 'Indicadores {0} - {1} de {2}',
			emptyMsg: "No hay indicadores aun"
		}),
		tbar:
		[
			/*{	
				id:'maestra_indicador_agregar_boton',
				text:'Agregar',
				tooltip:'Agregar',
				iconCls:'agregar',
				handler:maestra_indicador_agregar
			},'-',
			{
				text:'Eliminar',
				tooltip:'Eliminar',
				iconCls:'eliminar',
				handler:maestra_indicador_eliminar
			},'-',{
				text:'',
				iconCls:'activos',
				tooltip:'Indicador activos',
				handler:function(){
					maestra_indicador_datastore.baseParams.ind_eliminado = '0';
					maestra_indicador_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},{
				text:'',
				iconCls:'eliminados',
				tooltip:'Indicador eliminados',
				handler:function(){
					maestra_indicador_datastore.baseParams.ind_eliminado = '1';
					maestra_indicador_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},'-',{
				text:'Restablecer',
				iconCls:'restablece',
				tooltip:'Restablecer un indicador eliminado',
				handler:function(){
					 var cant_record = maestra_indicador_gridpanel.getSelectionModel().getCount();
			
					if(cant_record > 0){
					var record = maestra_indicador_gridpanel.getSelectionModel().getSelected();
						if (record.get('maestra_ind_codigo') != '') {
					
							Ext.Msg.prompt('Restablecer indicador', 
								'Digite la causa de reestablecimiento', 
								function(btn, text){
									if (btn == 'ok')  {
										subirDatosAjax( 
											getAbsoluteUrl('maestra_indicador', 'restablecerIndicador'), 
											{
											maestra_ind_codigo:record.get('maestra_ind_codigo'),
											maestra_ind_causa_restablece:text
											}, 
											function(){
												maestra_indicador_datastore.reload();
											}, 
											function(){}
										);
									}
								}
							);
						}
					}
					else {
						mostrarMensajeConfirmacion('Error', "Seleccione un indicador eliminado");
					}
				}
			}*/
		],
		plugins:[maestra_indicador_roweditor,
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
	var maestra_indicador_contenedor_panel = new Ext.Panel({
		id: 'maestra_indicador_contenedor_panel',
		height: largo_panel,
		autoWidth: true,
		border: false,
		tabTip :'Aqu&iacute; puedes ver, agregar, eliminar indicadores',
		monitorResize:true,
		items: 
		[
			maestra_indicador_gridpanel
		],
		renderTo:'div_form_maestra_indicador'
	});
	

	function maestra_indicador_actualizar(record,text){
	//	var record = maestra_indicador_gridpanel.getSelectionModel().getSelected();

		subirDatosAjax(
			getAbsoluteUrl('maestra_indicador','actualizarIndicador'),
			{
				maestra_ind_codigo: record.get('maestra_ind_codigo'),
				maestra_ind_nombre: record.get('maestra_ind_nombre'),
				maestra_ind_sigla: record.get('maestra_ind_sigla'),
				maestra_ind_unidad: record.get('maestra_ind_unidad'),
				maestra_ind_causa_actualizacion: text
			},
			function(){
				maestra_indicador_datastore.reload(); 
			}
		);
	}
       /*
	function maestra_indicador_eliminar()
	{
		var cant_record = maestra_indicador_gridpanel.getSelectionModel().getCount();
		
		if(cant_record > 0){
			var record = maestra_indicador_gridpanel.getSelectionModel().getSelected();
			
			if(record.get('maestra_ind_codigo')!='')
			{
			
				Ext.Msg.confirm('Eliminar indicador', "Realmente desea eliminar este indicador?", function(btn){
					if (btn == 'yes') {
					
						Ext.Msg.prompt('Eliminar indicador', 
							'Digite la causa de la eliminaci&oacute;n de este indicador', 
							function(btn2, text){
								if (btn2 == 'ok') {
									subirDatosAjax(
										getAbsoluteUrl('maestra_indicador','eliminarIndicador'),
										{
										maestra_ind_codigo:record.get('maestra_ind_codigo'),
										maestra_ind_causa_eliminacion:text
										},
										function(){
										maestra_indicador_datastore.reload(); 
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
			mostrarMensajeConfirmacion('Error',"Seleccione un indicador a eliminar");
		}
	}

	function maestra_indicador_agregar(btn, ev) {
		var row = new maestra_indicador_gridpanel.store.recordType({
			maestra_ind_codigo : '',
			maestra_ind_nombre: '',
			maestra_ind_sigla: '',
			maestra_ind_unidad: ''
		});

		maestra_indicador_gridpanel.getSelectionModel().clearSelections();
		maestra_indicador_roweditor.stopEditing();
		maestra_indicador_gridpanel.store.insert(0, row);
		maestra_indicador_gridpanel.getSelectionModel().selectRow(0);
		maestra_indicador_roweditor.startEditing(0);
	}
	*/
