//ayudas
    var ayuda_maestra_cat_codigo='C&oacute;digo identificador en el sistema';
    var ayuda_maestra_cat_nombre='Nombre grupo de equipos (Marca Modelo)';

    var maestra_categoriaequipo_datastore = new Ext.data.Store({
    id: 'maestra_categoriaequipo_datastore',
    proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('maestra_categoriaequipo','listarCategoriaEquipo'),
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
    maestra_categoriaequipo_datastore.load();
	

	var maestra_cat_codigo=new Ext.form.NumberField({
	   xtype: 'numberfield',
	   maxLength : 100,
	   name: 'maestra_cat_codigo',
	   id: 'maestra_cat_codigo',
	   fieldLabel: 'C&ooacute;digo equipo',
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
	   fieldLabel: 'Nombre grupo',
	   allowBlank: false,
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_cat_nombre', ayuda_maestra_cat_nombre);
					}
	   }
	});

	var maestra_categoriaequipo_colmodel = new Ext.grid.ColumnModel({
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
	

	var maestra_categoriaequipo_roweditor = new Ext.ux.grid.RowEditor({
		saveText: 'Guardar',
		cancelText: 'Cancelar',
		showTooltip: function(msg){},
		listeners:
		{
			'afteredit': function(gr,obj,record,num){
				
				if(record.get('maestra_cat_codigo')!=''){
				
					Ext.Msg.prompt(
					'Grupo',
					'Digite la causa de la actualizaci&oacute;n de este grupo',
					function(btn, text,op){
							if (btn == 'ok') {
							maestra_categoriaequipo_actualizar(record,text);
							}
						}
					);
				}
				else{
					maestra_categoriaequipo_actualizar(record,'');
				}
			},
			'canceledit': function(){}
		}
	});

    //CREACION DE LA GRILLA

	var maestra_categoriaequipo_gridpanel = new Ext.grid.GridPanel({
		id: 'maestra_categoriaequipo_gridpanel',
		title:'Grupos de Equipos',
		stripeRows:true,
		frame: true,
		ds: maestra_categoriaequipo_datastore,
		cm: maestra_categoriaequipo_colmodel,
		selModel: new Ext.grid.RowSelectionModel({
			singleSelect:true,	
			moveEditorOnEnter :false
		}),
		autoExpandColumn: 'maestra_cat_nombre_column',
		height: largo_panel,
		bbar: new Ext.PagingToolbar({
			pageSize: 20,
			store: maestra_categoriaequipo_datastore,
			displayInfo: true,
			displayMsg: 'Grupos de Equipos {0} - {1} de {2}',
			emptyMsg: "No hay grupos de equipos aun"
		}),
		tbar:
		[
			{	
				id:'maestra_categoriaequipo_agregar_boton',
				text:'Agregar',
				tooltip:'Agregar',
				iconCls:'agregar',
				handler:maestra_categoriaequipo_agregar
			},'-',
			{
				text:'Eliminar',
				tooltip:'Eliminar',
				iconCls:'eliminar',
				handler:maestra_categoriaequipo_eliminar
			},'-',{
				text:'',
				iconCls:'activos',
				tooltip:'Grupos activos',
				handler:function(){
					maestra_categoriaequipo_datastore.baseParams.cat_eliminado = '0';
					maestra_categoriaequipo_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},{
				text:'',
				iconCls:'eliminados',
				tooltip:'Grupos eliminados',
				handler:function(){
					maestra_categoriaequipo_datastore.baseParams.cat_eliminado = '1';
					maestra_categoriaequipo_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},'-',{
				text:'Restablecer',
				iconCls:'restablece',
				tooltip:'Restablecer un grupo eliminado',
				handler:function(){
					 var cant_record = maestra_categoriaequipo_gridpanel.getSelectionModel().getCount();
			
					if(cant_record > 0){
					var record = maestra_categoriaequipo_gridpanel.getSelectionModel().getSelected();
						if (record.get('maestra_cat_codigo') != '') {
					
							Ext.Msg.prompt('Restablecer grupos', 
								'Digite la causa de restablecimiento', 
								function(btn, text){
									if (btn == 'ok')  {
										subirDatosAjax( 
											getAbsoluteUrl('maestra_categoriaequipo', 'restablecerCategoriaEquipo'), 
											{
											maestra_cat_codigo:record.get('maestra_cat_codigo'),
											maestra_cat_causa_restablece:text
											}, 
											function(){
												maestra_categoriaequipo_datastore.reload();
											}, 
											function(){}
										);
									}
								}
							);
						}
					}
					else {
						mostrarMensajeConfirmacion('Error', "Seleccione un grupo eliminado");
					}
				}
			}
		],
		plugins:[maestra_categoriaequipo_roweditor,
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
	var maestra_categoriaequipo_contenedor_panel = new Ext.Panel({
		id: 'maestra_categoriaequipo_contenedor_panel',
		height: largo_panel,
		autoWidth: true,
		border: false,
		tabTip :'Aqu&iacute; puede ver, agregar, eliminar grupos de equipos',
		monitorResize:true,
		items: 
		[
			maestra_categoriaequipo_gridpanel
		],
		renderTo:'div_form_maestra_categoriaequipo'
	});
	

	function maestra_categoriaequipo_actualizar(record,text){
	//	var record = maestra_categoriaequipo_gridpanel.getSelectionModel().getSelected();

		subirDatosAjax(
			getAbsoluteUrl('maestra_categoriaequipo','actualizarCategoriaEquipo'),
			{
				maestra_cat_codigo: record.get('maestra_cat_codigo'),
				maestra_cat_nombre: record.get('maestra_cat_nombre'),
				maestra_cat_causa_actualizacion: text
			},
			function(){
				maestra_categoriaequipo_datastore.reload(); 
			}
		);
	}
        
	function maestra_categoriaequipo_eliminar()
	{
		var cant_record = maestra_categoriaequipo_gridpanel.getSelectionModel().getCount();
		
		if(cant_record > 0){
			var record = maestra_categoriaequipo_gridpanel.getSelectionModel().getSelected();
			if(record.get('maestra_cat_codigo')!='')
			{
				Ext.Msg.confirm('Eliminar grupo', "Realmente desea eliminar este grupo?", function(btn){
					if (btn == 'yes') {
					
						Ext.Msg.prompt('Eliminar grupo', 
							'Digite la causa de la eliminaci&oacute;n de este grupo', 
							function(btn2, text){
								if (btn2 == 'ok') {
									subirDatosAjax(
										getAbsoluteUrl('maestra_categoriaequipo','eliminarCategoriaEquipo'),
										{
										maestra_cat_codigo:record.get('maestra_cat_codigo'),
										maestra_cat_causa_eliminacion:text
										},
										function(){
										maestra_categoriaequipo_datastore.reload(); 
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
			mostrarMensajeConfirmacion('Error',"Seleccione un grupo de equipos a eliminar");
		}
	}

	function maestra_categoriaequipo_agregar(btn, ev) {
		var row = new maestra_categoriaequipo_gridpanel.store.recordType({
			maestra_cat_codigo : '',
			maestra_cat_nombre: ''
		});

		maestra_categoriaequipo_gridpanel.getSelectionModel().clearSelections();
		maestra_categoriaequipo_roweditor.stopEditing();
		maestra_categoriaequipo_gridpanel.store.insert(0, row);
		maestra_categoriaequipo_gridpanel.getSelectionModel().selectRow(0);
		maestra_categoriaequipo_roweditor.startEditing(0);
	}
	
