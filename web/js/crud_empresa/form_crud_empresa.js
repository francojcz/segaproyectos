
/*
gestion empresas - tpm labs
Desarrollado maryit sanchez
2010
*/


var  ayuda_emp_codigo='';
var  ayuda_emp_nombre='Escriba el nombre de la empresa'; 
var  ayuda_emp_nit='Escriba el número de identificación tributario de la empresa'; 
//--var  ayuda_emp_tli_codigo='Seleccione el tipolicencia del recurso'; 
var  ayuda_emp_fecha_limite_licencia='Seleccione o escriba la fecha limite licencia'; 
var  ayuda_emp_fecha_inicio_licencia='Seleccione o escriba la fecha inicio licencia'; 
//--var  ayuda_emp_inyect_estandar_promedio='Escriba cual es el numero de inyecciones estandar en su empresa'; 
//--var  ayuda_emp_tiempo_alerta_consumible='Escoja la fecha en que adquirió la máquina'; 


	var largo_panel=500;

/*--
	var crud_empresa_tipolicencia_datastore = new Ext.data.Store({
        id: 'crud_empresa_tipolicencia_datastore',
        proxy: new Ext.data.HttpProxy({
                url: getAbsoluteUrl('crud_empresa','listarTipoLicencia'),
                method: 'POST'
        }),
        baseParams:{}, 
        reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
                },[ 
			{name: 'tli_codigo'},
			{name: 'tli_nombre'}
		])
	});
	crud_empresa_tipolicencia_datastore.load();
*/

	var crud_empresa_datastore = new Ext.data.Store({
        id: 'crud_empresa_datastore',
        proxy: new Ext.data.HttpProxy({
                url: getAbsoluteUrl('crud_empresa','listarEmpresa'),
                method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
                root: 'results',
                totalProperty: 'total',
                id: 'id'
                },[ 
			{name: 'emp_codigo', type: 'int'},
			{name: 'emp_nombre', type: 'string'},
			{name: 'emp_nit', type: 'string'},
			{name: 'emp_logo_url', type: 'string'},
			//--{name: 'emp_tli_codigo', type: 'int'},
			//--{name: 'emp_tli_nombre', type: 'string'},
			
			{name: 'emp_fecha_limite_licencia', type: 'string'},
			{name: 'emp_fecha_inicio_licencia', type: 'string'},
			{name: 'emp_inyect_estandar_promedio', type: 'int'},
			//--{name: 'emp_tiempo_alerta_consumible', type: 'string'},
			{name: 'emp_fecha_registro_sistema', type: 'string'},
			{name: 'emp_fecha_actualizacion',type: 'string'},
			{name: 'emp_usu_crea_nombre',type: 'string'},
			{name: 'emp_usu_actualiza_nombre',type: 'string'},
			{name: 'emp_causa_eliminacion',type: 'string'},
			{name: 'emp_causa_actualizacion',type: 'string'}
		])
        });
    crud_empresa_datastore.load();
	
	var emp_codigo=new Ext.form.NumberField({
	   xtype: 'numberfield',
	   labelStyle: 'text-align:right;',
	   maxLength : 100,
	   name: 'emp_codigo',
	   id: 'emp_codigo',
	   hideLabel:true,
	   hidden:true,
	   listeners:
	   {
			'render': function() {
					ayuda('emp_codigo', ayuda_emp_codigo);
					}
	   }
	});
	
	var emp_nombre=new Ext.form.TextField({
	   xtype: 'textfield',
	   labelStyle: 'text-align:right;',
	   maxLength : 100,
	   name: 'emp_nombre',
	   id: 'emp_nombre',
	   fieldLabel: 'Nombre de la empresa',
	   allowBlank: false,
	   listeners:
	   {
			'render': function() {
					ayuda('emp_nombre', ayuda_emp_nombre);
					}
	   }
	});
	
	var emp_nit=new Ext.form.TextField({
	   xtype: 'textfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'emp_nit',
	   id: 'emp_nit',
	   fieldLabel: 'Nit',
	   allowBlank: false,
	   maxLength : 100,
	   listeners:
	   {
			'render': function() {
					ayuda('emp_nit', ayuda_emp_nit);
					}
		}
	});
	
	
	var emp_logo_url=new Ext.ux.form.FileUploadField({
		xtype: 'fileuploadfield',		 
		labelStyle: 'text-align:right;',
		name: 'emp_logo',
		id: 'emp_logo_url',
		fieldLabel: '<html>Escoger Archivo</html>',
		emptyText: 'Seleccione una imagen', 
		buttonText: '',
		disabled:false,
		buttonCfg: {iconCls: 'archivo'},  	
		allowBlank: true/*,
		listeners:
		{
			'render': function() {
		//			ayuda('emp_logo_url', ayuda_emp_logo_url);
			}
		}*/
	});
	
	/*--
	var emp_tli_codigo=new Ext.form.ComboBox({
		xtype: 'combobox',
		labelStyle: 'text-align:right;',
		id:'emp_tli_codigo',
		hiddenName:'tli_codigo',
		name:'emp_tli_codigo',
		fieldLabel:'Tipo Licencia',
		store:crud_empresa_tipolicencia_datastore,
		mode:'local',
		emptyText:'Seleccione ...',
		displayField:'tli_nombre',
		valueField:'tli_codigo',
		triggerAction:'all',
		forceSelection:true,
		allowBlank: false,
		listeners:
		{
			'render': function() {
					ayuda('emp_tli_codigo', ayuda_emp_tli_codigo);
					}
		}
	});*/
	
	
	var emp_fecha_inicio_licencia=new Ext.form.DateField({
	   xtype: 'datefield',		 
	   labelStyle: 'text-align:right;',
	   name: 'emp_fecha_inicio_licencia',
	   id: 'emp_fecha_inicio_licencia',
	   fieldLabel: 'Fecha inicio licencia',
	   allowBlank: false,
	   format:'Y-m-d',
	   anchor:'98%',
	   listeners:
	   {
			'render': function() {
					ayuda('emp_fecha_inicio_licencia', ayuda_emp_fecha_inicio_licencia);
					}
		}
	});

	
	var emp_fecha_limite_licencia=new Ext.form.DateField({
	   xtype: 'datefield',		 
	   labelStyle: 'text-align:right;',
	   name: 'emp_fecha_limite_licencia',
	   id: 'emp_fecha_limite_licencia',
	   fieldLabel: 'Fecha limite licencia',
	   allowBlank: false,
	   format:'Y-m-d',
	   anchor:'98%',
	   listeners:
	   {
			'render': function() {
					ayuda('emp_fecha_limite_licencia', ayuda_emp_fecha_limite_licencia);
					}
		}
	});
/*--
	var emp_inyect_estandar_promedio=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'emp_inyect_estandar_promedio',
	   id: 'emp_inyect_estandar_promedio',
	   fieldLabel: 'Número de inyecciones estandar',
	   allowDecimals: false,
	   allowNegative: false,
	   maxLength : 1,
	   maxValue:8,
	   value:'3',
	   listeners:
	   {
			'render': function() {
					ayuda('emp_inyect_estandar_promedio', ayuda_emp_inyect_estandar_promedio);
					}
		}
	});
*/
	/*--
	var emp_tiempo_alerta_consumible=new Ext.form.NumberField({
	   xtype: 'numberfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'emp_tiempo_alerta_consumible',
	   id: 'emp_tiempo_alerta_consumible',
	   allowDecimal:false,
	   allowNegative:false,
	   fieldLabel: 'Alerta cambio de consumibles(d&iacute;as)',
	   allowBlank: false,
	   maxLength : 8,
	   value:'15',
	   listeners:
	   {
			'render': function() {
					ayuda('emp_tiempo_alerta_consumible', ayuda_emp_tiempo_alerta_consumible);
					}
		}
	});
*/
	var emp_fecha_registro_sistema=new Ext.form.TextField({
	   xtype: 'textfield',		 
	   labelStyle: 'text-align:right;',
	   name: 'emp_fecha_registro_sistema',
	   id: 'emp_fecha_registro_sistema',
	   fieldLabel: '<html>Fecha registro</html>',
	   maxLength : 100,
	   readOnly:true
	  // disabled:true
	});
	
	 var emp_image_logo = new Ext.Panel({   
		 height:110,  
		 bodyStyle:'text-align:center;margin-left:auto;',
		 html: '<img id="emp_image_logo" width=100 heigth=100 align=center />'
	 });  
	 
	var crud_empresa_formpanel = new Ext.FormPanel({
		id:'crud_empresa_formpanel',
		frame: true,
		region:'east',
		split:true,
		collapsible:true,
		width:500,
		border:true,
		title:'Empresa detalle',
		//autoWidth: true,
		columnWidth: '0.6',
		height: 500,
		layout:'form',
		//bodyStyle: 'padding:10px;',
		padding:10,
		defaults:{  anchor:'98%'},
		labelWidth: 150,
		fileUpload: true,
		items:
		[   
			emp_image_logo,
			emp_codigo,
			emp_nombre,
			emp_nit,
			emp_logo_url,
			//--emp_tli_codigo,
			//--emp_inyect_estandar_promedio,
			emp_fecha_inicio_licencia,
			emp_fecha_limite_licencia,
			//--emp_tiempo_alerta_consumible,
			emp_fecha_registro_sistema				
		],
		buttons:
		[
			{
				text: 'Guardar',
				iconCls:'guardar',
				id:'crud_empresa_actualizar_boton',
				handler: crud_empresa_actualizar
			}
		]
	});

	function empresaRenderComboColumn(value, meta, record){
		return ComboRenderer(value, emp_tli_codigo);
	}

	var crud_empresa_colmodel = new Ext.grid.ColumnModel({
	defaults:{sortable: true, locked: false, resizable: true},
	columns:[
		{id: 'emp_codigo', header: "Id", width: 30, dataIndex: 'emp_codigo'},
		{ header: "Nombre", width: 150, dataIndex: 'emp_nombre'},
		{ header: "Nit", width: 100, dataIndex: 'emp_nit'},
		//--{ header: "Tipo Licencia", width: 100,  dataIndex: 'emp_tli_codigo',renderer:empresaRenderComboColumn},
		
		{ header: "Creado por", width: 120, dataIndex: 'emp_usu_crea_nombre'},
		{ header: "Fecha de creaci&oacute;n", width: 120, dataIndex: 'emp_fecha_registro_sistema'},
		{ header: "Actualizado por", width: 120, dataIndex: 'emp_usu_actualiza_nombre'},
		{ header: "Fecha de actualizaci&oacute;n", width: 120, dataIndex: 'emp_fecha_actualizacion'},
		{ header: "Causa actualizaci&oacute;n", width: 120, dataIndex: 'emp_causa_actualizacion'},
		{ header: "Causa eliminaci&oacute;n", width: 120, dataIndex: 'emp_causa_eliminacion'}
		
	]
	});
	
	var crud_empresa_gridpanel = new Ext.grid.GridPanel({
		id: 'crud_empresa_gridpanel',
		title:'Empresas en el sistema',
		columnWidth: '.4',
		region:'center',
		stripeRows:true,
		frame: true,
		ds: crud_empresa_datastore,
		cm: crud_empresa_colmodel,
		selModel: new Ext.grid.RowSelectionModel({
			singleSelect: true,
			listeners: {
				rowselect: function(sm, row, record) {
			
					if(record.data.emp_logo_url != ''){
						Ext.get('emp_image_logo').dom.src = urlPrefix +'../'+record.data.emp_logo_url; 
					}
					else{
						Ext.get('emp_image_logo').dom.src = urlPrefix +'../images/vacio.png'; 
					}
					Ext.getCmp('crud_empresa_formpanel').getForm().loadRecord(record);
					Ext.getCmp('crud_empresa_actualizar_boton').setText('Actualizar');
				}
			}
		}),
		height: largo_panel,
		bbar: new Ext.PagingToolbar({
			pageSize: 15,
			store: crud_empresa_datastore,
			displayInfo: true,
			displayMsg: 'Empresas {0} - {1} de {2}',
			emptyMsg: "No hay empresas aun"
		}),
		tbar:
		[
			{	
				id:'crud_empresa_agregar_boton',
				text:'Agregar',
				tooltip:'Agregar',
				iconCls:'agregar',
				handler:crud_empresa_agregar
			},'-',
			{
				text:'Eliminar',
				tooltip:'Eliminar',
				iconCls:'eliminar',
				handler:crud_empresa_eliminar
			},'-',
			{
				text:'',
				iconCls:'activos',
				tooltip:'Empresa activos',
				handler:function(){
					crud_empresa_datastore.baseParams.emp_eliminado = '0';
					crud_empresa_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},{
				text:'',
				iconCls:'eliminados',
				tooltip:'Empresa eliminados',
				handler:function(){
					crud_empresa_datastore.baseParams.emp_eliminado = '1';
					crud_empresa_datastore.load({
						params: {
							start: 0,
							limit: 20
						}
					});
				}
			},'-',{
				text:'Restablecer',
				iconCls:'restablece',
				tooltip:'Restablecer un empresa eliminado',
				handler:function(){
					 var cant_record = crud_empresa_gridpanel.getSelectionModel().getCount();
			
					if(cant_record > 0){
					var record = crud_empresa_gridpanel.getSelectionModel().getSelected();
						if (record.get('emp_codigo') != '') {
					
							Ext.Msg.prompt('Restablecer empresa', 
								'Digite la causa de restablecimiento', 
								function(btn, text){
									if (btn == 'ok')  {
										subirDatosAjax( 
											getAbsoluteUrl('crud_empresa', 'restablecerEmpresa'), 
											{
											emp_codigo:record.get('emp_codigo'),
											emp_causa_restablece:text
											}, 
											function(){
												crud_empresa_datastore.reload();
											}, 
											function(){}
										);
									}
								}
							);
						}
					}
					else {
						mostrarMensajeConfirmacion('Error', "Seleccione un empresa eliminado");
					}
				}
			}
		]
    });
	

	/*INTEGRACION AL CONTENEDOR*/
	var crud_empresa_contenedor_panel = new Ext.Panel({
		id: 'crud_empresa_contenedor_panel',
		height: largo_panel,
		autoWidth: true,
		//width:1000,
		border: false,
		tabTip :'Aqui puedes ver, agregar , eliminar empresas',
		monitorResize:true,
		layout:'border',
		items: 
		[
			crud_empresa_gridpanel,
			crud_empresa_formpanel
		],
		buttonAlign :'left',
		renderTo:'div_form_crud_empresa'
	});
	Ext.get('emp_image_logo').dom.src = urlPrefix +'../images/vacio.png'; 
		
	function crud_empresa_actualizar(btn){
		if(crud_empresa_formpanel.getForm().isValid())
		{
			subirDatos(
				crud_empresa_formpanel,
				getAbsoluteUrl('crud_empresa','actualizarEmpresa'),
				{},
				function(){
				crud_empresa_formpanel.getForm().reset();
				crud_empresa_datastore.reload(); 
				},
				function(){}
				);
		}
	}
        
	function crud_empresa_eliminar()
	{
		var cant_record = crud_empresa_gridpanel.getSelectionModel().getCount();
		
		if(cant_record > 0){
			var record = crud_empresa_gridpanel.getSelectionModel().getSelected();
			if(record.get('emp_codigo')!='')
			{
				Ext.Msg.confirm('Eliminar empresa', 
				"Realmente desea eliminar esta empresa?", 
				function(btn){
					if (btn == 'yes') {
					
						Ext.Msg.prompt('Eliminar empresa', 
							'Digite la causa de la eliminaci&oacute;n de esta empresa', 
							function(btn2, text){
								if (btn2 == 'ok') {
									subirDatosAjax(
										getAbsoluteUrl('crud_empresa','eliminarEmpresa'),
										{
										emp_codigo:record.get('emp_codigo'),
										emp_causa_eliminacion:text
										},
										function(){
										crud_empresa_datastore.reload(); 
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
			mostrarMensajeConfirmacion('Error',"Seleccione una empresa a eliminar");
		}
	}
	
	function crud_empresa_agregar(btn, ev) {
		crud_empresa_formpanel.getForm().reset();
		Ext.getCmp('crud_empresa_actualizar_boton').setText('Guardar');
	
	}
	
	
