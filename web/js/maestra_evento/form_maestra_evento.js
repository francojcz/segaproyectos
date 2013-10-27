/*
manejo Eventos - tpm labs
Desarrollado maryit sanchez
2010
*/
	//ayudas
	var ayuda_maestra_eve_codigo='C&oacute;digo identificador en el sistema';
	var ayuda_maestra_eve_nombre='Nombre evento';
	
	var maestra_evento_datastore = new Ext.data.Store({
        id: 'maestra_evento_datastore',
        proxy: new Ext.data.HttpProxy({
                url: getAbsoluteUrl('maestra_evento','listarEvento'),
                method: 'POST'
        }),
        baseParams:{start:0, limit:20}, 
        reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
                },[ 
			{name: 'maestra_eve_codigo', type: 'int'},
			{name: 'maestra_eve_nombre', type: 'string'},
			{name: 'maestra_eve_fecha_registro_sistema', type: 'string'},
			{name: 'maestra_eve_fecha_actualizacion',type: 'string'},
			{name: 'maestra_eve_usu_crea_nombre',type: 'string'},
			{name: 'maestra_eve_usu_actualiza_nombre',type: 'string'},
			{name: 'maestra_eve_causa_eliminacion',type: 'string'},
			{name: 'maestra_eve_causa_actualizacion',type: 'string'}
			])
        });
    maestra_evento_datastore.load();
	

	var maestra_eve_codigo=new Ext.form.NumberField({
	   xtype: 'numberfield',
           labelStyle: 'text-align:right;',
	   maxLength : 100,
	   name: 'maestra_eve_codigo',
	   id: 'maestra_eve_codigo',
	   fieldLabel: 'C&ooacute;digo evento',           
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_eve_codigo', ayuda_maestra_eve_codigo);
					}
	   }
	});
	

	var maestra_eve_nombre=new Ext.form.TextField({
	   xtype: 'textfield',
           labelStyle: 'text-align:right;',
	   maxLength : 100,
	   name: 'maestra_eve_nombre',
	   id: 'maestra_eve_nombre',
	   fieldLabel: 'Nombre evento',
           allowBlank: false,
	   listeners:
	   {
			'render': function() {
					ayuda('maestra_eve_nombre', ayuda_maestra_eve_nombre);
					}
	   }
	});

	var maestra_evento_colmodel = new Ext.grid.ColumnModel({
		defaults:{sortable: true, locked: false, resizable: true},
		columns:[
			{ id: 'maestra_eve_codigo_column', header: "Id", width: 30, dataIndex: 'maestra_eve_codigo'},
			{ id: 'maestra_eve_nombre_column', header: "Nombre", width: 100, dataIndex: 'maestra_eve_nombre', editor:maestra_eve_nombre},
			{ header: "Creado por", width: 120, dataIndex: 'maestra_eve_usu_crea_nombre'},
			{ header: "Fecha de creaci&oacute;n", width: 120, dataIndex: 'maestra_eve_fecha_registro_sistema'},
			{ header: "Actualizado por", width: 120, dataIndex: 'maestra_eve_usu_actualiza_nombre'},
			{ header: "Fecha de actualizaci&oacute;n", width: 120, dataIndex: 'maestra_eve_fecha_actualizacion'},
			{ header: "Causa actualizaci&oacute;n", width: 120, dataIndex: 'maestra_eve_causa_actualizacion'},
			{ header: "Causa eliminaci&oacute;n", width: 120, dataIndex: 'maestra_eve_causa_eliminacion'}
		]
	});
	

	var maestra_evento_roweditor = new Ext.ux.grid.RowEditor({
		saveText: 'Guardar',
		cancelText: 'Cancelar',
		showTooltip: function(msg){},
		listeners:
		{
			'afteredit': function(gr,obj,record,num){
				
				if(record.get('maestra_eve_codigo')!=''){
				
					Ext.Msg.prompt(
					'Evento',
					'Digite la causa de la actualizaci&oacute;n de este evento',
					function(btn, text,op){
							if (btn == 'ok') {
							maestra_evento_actualizar(record,text);
							}
						}
					);
				}
				else{
					maestra_evento_actualizar(record,'');
				}
			},
			'canceledit': function(){}
		}
	});

    //CREACION DE LA GRILLA

	var maestra_evento_gridpanel = new Ext.grid.GridPanel({
		id: 'maestra_evento_gridpanel',
		title:'Eventos',
		stripeRows:true,
		region:'center',
		frame: true,
		ds: maestra_evento_datastore,
		cm: maestra_evento_colmodel,
		selModel: new Ext.grid.RowSelectionModel({
			singleSelect: true,
			moveEditorOnEnter :false,
			listeners: {
				rowselect: function(sm, row, rec){
					
					maestra_evca_eve_codigo.setValue(rec.data.maestra_eve_codigo);
					maestra_evca_eve_nombre.setValue(rec.data.maestra_eve_nombre);
				 
					maestra_eventoporcategoria_datastore.load({params:{maestra_eve_codigo:rec.data.maestra_eve_codigo }});
				}
			}
		}),
		autoExpandColumn: 'maestra_eve_nombre_column',
		autoExpandMin :180,
		height: largo_panel,
		bbar: new Ext.PagingToolbar({
			pageSize: 20,
			store: maestra_evento_datastore,
			displayInfo: true,
			displayMsg: 'Eventos {0} - {1} de {2}',
			emptyMsg: "No hay eventos aun"
		}),
		tbar:
		[
			{	
				id:'maestra_evento_agregar_boton',
				text:'Agregar',
				tooltip:'Agregar',
				iconCls:'agregar',
				handler:maestra_evento_agregar
			},'-',
			{
				text:'Eliminar',
				tooltip:'Eliminar',
				iconCls:'eliminar',
				handler:maestra_evento_eliminar
			},'-',{
				text:'',
				iconCls:'activos',
				tooltip:'Evento activos',
				handler:function(){
					maestra_evento_datastore.baseParams.eve_eliminado = '0';
					maestra_evento_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},{
				text:'',
				iconCls:'eliminados',
				tooltip:'Evento eliminados',
				handler:function(){
					maestra_evento_datastore.baseParams.eve_eliminado = '1';
					maestra_evento_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},'-',{
				text:'Restablecer',
				iconCls:'restablece',
				tooltip:'Restablecer un evento eliminado',
				handler:function(){
					 var cant_record = maestra_evento_gridpanel.getSelectionModel().getCount();
			
					if(cant_record > 0){
					var record = maestra_evento_gridpanel.getSelectionModel().getSelected();
						if (record.get('maestra_eve_codigo') != '') {
					
							Ext.Msg.prompt('Restablecer evento', 
								'Digite la causa de restablecimiento', 
								function(btn, text){
									if (btn == 'ok')  {
										subirDatosAjax( 
											getAbsoluteUrl('maestra_evento', 'restablecerEvento'), 
											{
											maestra_eve_codigo:record.get('maestra_eve_codigo'),
											maestra_eve_causa_restablece:text
											}, 
											function(){
												maestra_evento_datastore.reload();
											}, 
											function(){}
										);
									}
								}
							);
						}
					}
					else {
						mostrarMensajeConfirmacion('Error', "Seleccione un evento eliminado");
					}
				}
			}
		],
		plugins:[maestra_evento_roweditor,
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
	

	var maestra_eventoporcategoria_datastore = new Ext.data.Store({
        id: 'maestra_eventoporcategoria_datastore',
        proxy: new Ext.data.HttpProxy({
                url: getAbsoluteUrl('maestra_evento','listarCategoriasporevento'),
                method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
                },[ 
			{name: 'maestra_evca_cat_codigo', type: 'int'},
			{name: 'maestra_evca_cat_nombre', type: 'string'},
			{name: 'maestra_evca_cat_pertenece', type: 'bool'}
			])
    });

 	var maestra_eventoporcategoria_pertenece_selmodel = new Ext.grid.CheckboxSelectionModel({
		singleSelect:true,	
		listeners: {
			rowselect: function(sm, row, rec) {
			}
		}
	});
   
	var maestra_eventoporcategoria_colmodel = new Ext.grid.ColumnModel({
		defaults:{sortable: true, locked: false, resizable: true},
		columns:[
			maestra_eventoporcategoria_pertenece_selmodel,
			{ header: "Id", width: 30, dataIndex: 'maestra_evca_cat_codigo',hidden:true},
			{ header: "Nombre", width: 200, dataIndex: 'maestra_evca_cat_nombre'}
		]
	});

    //CREACION DE LA GRILLA DE CATEGORIAS
	
	var maestra_eventoporcategoria_cat_datastore = new Ext.data.JsonStore({
		id: 'maestra_eventoporcategoria_cat_datastore',
		url: getAbsoluteUrl('maestra_evento', 'listarCategorias'),
		root: 'results',
		totalProperty: 'total',
		fields: [
			{name: 'cat_codigo',type: 'int'    }, 
			{name: 'cat_nombre',type: 'string'}
		],
		sortInfo: {
			field: 'cat_nombre',
			direction: 'ASC'
		}
	});
	maestra_eventoporcategoria_cat_datastore.load();


	var maestra_eventoporcategoria_cat_codigo = new Ext.form.ComboBox({
		xtype: 'combo',
		store: maestra_eventoporcategoria_cat_datastore,
		hiddenName: 'cat_codigo',
		name: 'evca_cat_codigo',
		mode: 'local',
		valueField: 'cat_codigo',
		forceSelection: true,
		displayField: 'cat_nombre',
		triggerAction: 'all',
		emptyText: 'Seleccione una categoría',
		selectOnFocus: true,
		fieldLabel: 'Categor&iacute;a',
		listeners: {
			focus : function(){
				maestra_eventoporcategoria_cat_datastore.reload();
			} 
		}
	});

	var maestra_eventoporcategoria_gridpanel = new Ext.grid.GridPanel({
		id: 'maestra_eventoporcategoria_gridpanel',
		title:'Categor&iacute;as',
		stripeRows:true,
		frame: true,
		ds: maestra_eventoporcategoria_datastore,
		cm: maestra_eventoporcategoria_colmodel,
		sm: maestra_eventoporcategoria_pertenece_selmodel,
		height: 280,
		tbar:[maestra_eventoporcategoria_cat_codigo,
			{text:'Agregar a categor&iacute;a',iconCls:'agregar',
				handler:function(){				
					if(maestra_eventoporcategoria_cat_codigo.getValue()!='' && maestra_evca_eve_codigo.getValue()!='' ){
						subirDatosAjax(
							getAbsoluteUrl('maestra_evento', 'guardarEventoPorCategoria'),
							{
								cat_codigo: maestra_eventoporcategoria_cat_codigo.getValue(),
								eve_codigo: maestra_evca_eve_codigo.getValue() 
							}, 
							function(){
								maestra_eventoporcategoria_datastore.reload();
							},
							function(){}
						);
					}
				}
			}],
		bbar:[
			{text:'Quitar categor&iacute;a',iconCls:'eliminar',
				handler:function(){
					var categoriasSelecionados = maestra_eventoporcategoria_gridpanel.selModel.getSelections();
					var categoriasEliminar = [];
					for(i = 0; i< maestra_eventoporcategoria_gridpanel.selModel.getCount(); i++){
						categoriasEliminar.push(categoriasSelecionados[i].json.maestra_evca_cat_codigo);
					}
					var encoded_array_categorias = Ext.encode(categoriasEliminar);
				
					if(maestra_evca_eve_codigo.getValue()!='' ){
						subirDatosAjax(
							getAbsoluteUrl('maestra_evento', 'eliminarEventoPorCategoria'),
							{
								cats_codigos: encoded_array_categorias,
								eve_codigo: maestra_evca_eve_codigo.getValue() 
							}, 
							function(){
								maestra_eventoporcategoria_datastore.reload();
							},
							function(){}
						);
					}				
				}
			}
		]
    });
	
	
	var maestra_evca_eve_codigo=new Ext.form.NumberField({
	   xtype: 'numberfield',
           labelStyle: 'text-align:right;',
	   readOnly:true,
	   anchor:'100%',
	   name: 'maestra_evca_eve_codigo',
	   id: 'maestra_evca_eve_codigo',
	   fieldLabel: 'C&oacute;digo evento'
	});
	

	var maestra_evca_eve_nombre=new Ext.form.TextField({
	   xtype: 'textfield',
           labelStyle: 'text-align:right;',
	   readOnly:true,
	   anchor:'100%',
	   name: 'maestra_evca_eve_nombre',
	   id: 'maestra_evca_eve_nombre',
	   fieldLabel: 'Nombre evento'
	});
	
	var maestra_evca_panel=new Ext.FormPanel({
		title:'Evento detalle',
		region:'east',
		frame:true,
		padding:10,
		width:400,
		split:true,
		collapsible:true,
		items:[
			maestra_evca_eve_codigo,
			maestra_evca_eve_nombre,
			maestra_eventoporcategoria_gridpanel
		]
	});
	
	/*INTEGRACION AL CONTENEDOR*/
	var maestra_evento_contenedor_panel = new Ext.Panel({
		id: 'maestra_evento_contenedor_panel',
		height: largo_panel,
		autoWidth: true,
		border: false,
		tabTip :'Aqu&iacute; puedes ver, agregar, eliminar eventos',
		monitorResize:true,
		layout:'border',
		items: 
		[
			maestra_evento_gridpanel,
			maestra_evca_panel
		],
		renderTo:'div_form_maestra_evento'
	});
	

	function maestra_evento_actualizar(record,text){
		//var record = maestra_evento_gridpanel.getSelectionModel().getSelected();

		subirDatosAjax(
			getAbsoluteUrl('maestra_evento','actualizarEvento'),
			{
				maestra_eve_codigo: record.get('maestra_eve_codigo'),
				maestra_eve_nombre: record.get('maestra_eve_nombre'),
				maestra_eve_causa_actualizacion: text
			},
			function(){
				maestra_evento_datastore.reload(); 
			}
		);
	}
        
	function maestra_evento_eliminar()
	{
		var cant_record = maestra_evento_gridpanel.getSelectionModel().getCount();
		
		if(cant_record > 0){
			var record = maestra_evento_gridpanel.getSelectionModel().getSelected();
			if(record.get('maestra_eve_codigo')!='')
			{
			
			Ext.Msg.confirm('Eliminar evento', "Realmente desea eliminar este evento?", function(btn){
					if (btn == 'yes') {
					
						Ext.Msg.prompt('Eliminar evento', 
							'Digite la causa de la eliminaci&oacute;n de este evento', 
							function(btn2, text){
								if (btn2 == 'ok') {
									subirDatosAjax(
										getAbsoluteUrl('maestra_evento','eliminarEvento'),
										{
										maestra_eve_codigo:record.get('maestra_eve_codigo'),
										maestra_eve_causa_eliminacion:text
										},
										function(){
										maestra_evento_datastore.reload(); 
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
			mostrarMensajeConfirmacion('Error',"Seleccione un evento a eliminar");
		}
	}

	function maestra_evento_agregar(btn, ev) {
		var row = new maestra_evento_gridpanel.store.recordType({
			maestra_eve_codigo : '',
			maestra_eve_nombre: ''
		});

		maestra_evento_gridpanel.getSelectionModel().clearSelections();
		maestra_evento_roweditor.stopEditing();
		maestra_evento_gridpanel.store.insert(0, row);
		maestra_evento_gridpanel.getSelectionModel().selectRow(0);
		maestra_evento_roweditor.startEditing(0);
	}
	
