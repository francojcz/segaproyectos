//maryit
var ayuda_empl_codigo = 'Id del empleado';



var ayuda_empl_nombres = 'Escriba el nombre del empleado';
var ayuda_empl_apellidos = 'Escriba el apellido del empleado';
var ayuda_empl_numero_identificacion = 'Escriba el número de identificación';
var ayuda_empl_tid_codigo = 'Escoja el tipo de identificación';
var ayuda_empl_emp_codigo = 'Seleccione la empresa';
var ayuda_empl_url_foto = 'Seleccione la foto del empleado';
var ayuda_empl_usu_codigo = 'Seleccione el login usuario que usará el empleado';
var largo_panel=500;


var crud_empleado_datastore = new Ext.data.JsonStore({
    id: 'crud_empleado_datastore',
    url: getAbsoluteUrl('crud_empleado', 'cargar'),
    root: 'results',
    baseParams: {
        task: 'LISTARUSUARIOS'
    },
    totalProperty: 'total',
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }),
    fields: [
		{name: 'empl_codigo', type: 'int' }, 
		{name: 'empl_nombres', type: 'string' }, 
		{name: 'empl_apellidos',type: 'string'},    
		{name: 'empl_numero_identificacion',type: 'string'}, 
		{name: 'empl_tid_codigo',type: 'int'}, 
		{name: 'empl_tid_nombre',type: 'string'}, 
		
		{name: 'empl_emp_codigo',type: 'int'},
		{name: 'empl_usu_codigo',type: 'int'},
		{name: 'empl_url_foto',type: 'string'},
		{name: 'empl_fecha_registro_sistema',type: 'string'},
		{name: 'empl_fecha_actualizacion',type: 'string'},
		{name: 'empl_usu_crea_nombre',type: 'string'},
		{name: 'empl_usu_actualiza_nombre',type: 'string'},
		{name: 'empl_causa_eliminacion',type: 'string'},
		{name: 'empl_causa_actualizacion',type: 'string'}
		],
    sortInfo: {
        field: 'empl_apellidos',
        direction: 'ASC'
    }
});
crud_empleado_datastore.load({
    params: {
        start: 0,
        limit: 20
    }
});

var crud_empleado_columnmodel = new Ext.grid.ColumnModel({
    columns: [
		{header: 'Nombres',dataIndex: 'empl_nombres',width: 150}, 
		{header: 'Apellidos',dataIndex: 'empl_apellidos',width: 150}, 
		{header: 'Tipo identificación',dataIndex: 'empl_tid_nombre',width: 100}, 
		{header: 'Número identificación',dataIndex: 'empl_numero_identificacion',width: 100},
	//	{header: 'Fecha registro',dataIndex: 'empl_fecha_registro_sistema',width: 100},
		
		{ header: "Creado por", width: 120, dataIndex: 'empl_usu_crea_nombre'},
		{ header: "Fecha de creaci&oacute;n", width: 120, dataIndex: 'empl_fecha_registro_sistema'},
		{ header: "Actualizado por", width: 120, dataIndex: 'empl_usu_actualiza_nombre'},
		{ header: "Fecha de actualizaci&oacute;n", width: 120, dataIndex: 'empl_fecha_actualizacion'},
		{ header: "Causa actualizaci&oacute;n", width: 120, dataIndex: 'empl_causa_actualizacion'},
		{ header: "Causa eliminaci&oacute;n", width: 120, dataIndex: 'empl_causa_eliminacion'}
    ],
    defaults: {sortable: true}
});

var crud_empleado_gridpanel = new Ext.grid.GridPanel({
    columnWidth: '.5',
    frame: true,
    region: 'center',
    //height: largo_panel ,
    id: 'crud_empleado_gridpanel',
    cm: crud_empleado_columnmodel,
    stripeRows: true,
    ds: crud_empleado_datastore,
    title: 'Lista de empleados del sistema',
    border: true,
    bbar: new Ext.PagingToolbar({
        pageSize: 10,
        store: crud_empleado_datastore,
        displayInfo: true,
        displayMsg: 'Empleados {0} - {1} de {2}',
        emptyMsg: "No hay Empleados"
    }),
    listeners: {
        render: function(g){
            g.getSelectionModel().selectRow(0);
        },
        delay: 10
    },
    tbar: [{
        text: 'Agregar',
        tooltip: 'Agregar un empleado',
        iconCls: 'agregar',
        handler: empleado_agregar
    }, {
        text: 'Borrar',
        tooltip: 'Borra un empleado',
        iconCls: 'eliminar',
        handler: empleado_confirmar_borrado
    },'-',{
		text:'',
		iconCls:'activos',
		tooltip:'Empleados activos',
		handler:function(){
			crud_empleado_datastore.baseParams.empl_eliminado = '0';
            crud_empleado_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
		}
	},{
		text:'',
		iconCls:'eliminados',
		tooltip:'Empleados eliminados',
		handler:function(){
			crud_empleado_datastore.baseParams.empl_eliminado = '1';
            crud_empleado_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
		}
	},'-',{
		text:'Restablecer',
		iconCls:'restablece',
		tooltip:'Restablecer un empleado eliminado',
		handler:function(){
			 var cant_record = crud_empleado_gridpanel.getSelectionModel().getCount();
	
			if(cant_record > 0){
			var record = crud_empleado_gridpanel.getSelectionModel().getSelected();
				if (record.get('empl_codigo') != '') {
			
					Ext.Msg.prompt('Restablecer empleado', 
						'Digite la causa de restablecimiento', 
						function(btn, text){
							if (btn == 'ok')  {
								subirDatosAjax( 
									getAbsoluteUrl('crud_empleado', 'restablecerEmpleado'), 
									{
									empl_codigo:record.get('empl_codigo'),
									empl_causa_restablece:text
									}, 
									function(){
										crud_empleado_datastore.reload();
									}, 
									function(){}
								);
							}
						}
					);
				}
			}
			else {
				mostrarMensajeConfirmacion('Error', "Seleccione un empleado eliminado");
			}
		}
	}
	],
    selModel: new Ext.grid.RowSelectionModel({
        singleSelect: true,
        listeners: {
            rowselect: function(sm, row, record){
            
                crud_empleado_formpanel.getForm().loadRecord(record);
                var nombre_empleado = Ext.getCmp('empl_nombres').getValue();
                titulo = 'Actualizar datos de ' + nombre_empleado;
                Ext.getCmp('crud_empleado_guardar_boton').setText('Actualizar');
                Ext.getCmp('crud_empleado_formpanel').setTitle(titulo);
               
				if(record.data.empl_url_foto != ''){
					Ext.get('empl_image_foto').dom.src = urlPrefix +'../'+record.data.empl_url_foto; 
				}
				else{
					Ext.get('empl_image_foto').dom.src = urlPrefix +'../images/vacio.png'; 
				}
            }
        }
    })
});


var empl_nombres = new Ext.form.TextField({
	fieldLabel: 'Nombres',
	name: 'empl_nombres',
	id: 'empl_nombres',
	allowBlank: false,
	blankText: 'Escribir nombres',
	maxLength:200,
	listeners: {
		render: function(){
			ayuda('empl_nombres', ayuda_empl_nombres);
		}
	}
});

var empl_apellidos = new Ext.form.TextField({
	fieldLabel: 'Apellidos',
	name: 'empl_apellidos',
	id: 'empl_apellidos',
	allowBlank: false,
	blankText: 'Escribir apellidos',
	maxLength:200,
	listeners: {
		render: function(){
			ayuda('empl_apellidos', ayuda_empl_apellidos);
		}
	}
});


var empl_numero_identificacion = new Ext.form.NumberField({
	fieldLabel: 'Número Identificación',
	name: 'empl_numero_identificacion',
	id: 'empl_numero_identificacion',
	allowBlank: false,
	blankText: 'Escribir número identificación',
	allowDecimal:false,
	allowNegative:false,
	maxLength:10,
	listeners: {
		render: function(){
			ayuda('empl_numero_identificacion', ayuda_empl_numero_identificacion);
		}
	}
});

var crud_empleado_tipo_identificacion_datastore = new Ext.data.JsonStore({
    id: 'crud_empleado_tipo_identificacion_datastore',
    url: getAbsoluteUrl('crud_empleado', 'listarTipoidentificacion'),
    root: 'results',
    totalProperty: 'total',
    fields: [{
        name: 'tid_codigo',
        type: 'int'
    }, {
        name: 'tid_nombre',
        type: 'string'
    }, ],
    sortInfo: {
        field: 'tid_nombre',
        direction: 'ASC'
    }
});
crud_empleado_tipo_identificacion_datastore.load();


var empl_tid_codigo = new Ext.form.ComboBox({
	xtype: 'combo',
	store: crud_empleado_tipo_identificacion_datastore,
	hiddenName: 'tid_codigo',
	name: 'empl_tid_codigo',
	id: 'empl_tid_codigo',
	mode: 'local',
	valueField: 'tid_codigo',
	forceSelection: true,
	displayField: 'tid_nombre',
	triggerAction: 'all',
	emptyText: 'Seleccione un tipo identificación...',
	selectOnFocus: true,
	fieldLabel: 'Tipo identificación',
	allowBlank: false,
	listeners: {
		render: function(){
			ayuda('empl_tid_codigo', ayuda_empl_tid_codigo);
		},
		focus : function(){
			crud_empleado_tipo_identificacion_datastore.reload();
		} 
	}
});


var crud_empleado_empresa_datastore = new Ext.data.JsonStore({
    id: 'crud_empleado_empresa_datastore',
    url: getAbsoluteUrl('crud_empleado', 'listarEmpresa'),
    root: 'results',
    totalProperty: 'total',
    fields: [{
        name: 'emp_codigo',
        type: 'string'
    }, {
        name: 'emp_nombre',
        type: 'string'
    }, ],
    sortInfo: {
        field: 'emp_nombre',
        direction: 'ASC'
    }
});
crud_empleado_empresa_datastore.load();


var empl_emp_codigo = new Ext.form.ComboBox({
	xtype: 'combo',
	store: crud_empleado_empresa_datastore,
	hiddenName: 'emp_codigo',
	name: 'empl_emp_codigo',
	id: 'empl_emp_codigo',
	mode: 'local',
	valueField: 'emp_codigo',
	forceSelection: true,
	displayField: 'emp_nombre',
	triggerAction: 'all',
	emptyText: 'Seleccione un empresa...',
	selectOnFocus: true,
	fieldLabel: 'Empresa',
	allowBlank: false,
	listeners: {
		render: function(){
			ayuda('empl_emp_codigo', ayuda_empl_emp_codigo);
		},
		focus : function(){
			crud_empleado_empresa_datastore.reload();
		} 
	}
});



var crud_empleado_usuario_datastore = new Ext.data.JsonStore({
    id: 'crud_empleado_usuario_datastore',
    url: getAbsoluteUrl('crud_empleado', 'listarLogin'),
    root: 'results',
    totalProperty: 'total',
    fields: [{
        name: 'usu_codigo',
        type: 'string'
    }, {
        name: 'usu_login',
        type: 'string'
    }, ],
    sortInfo: {
        field: 'usu_login',
        direction: 'ASC'
    }
});
crud_empleado_usuario_datastore.load();


var empl_usu_codigo = new Ext.form.ComboBox({
	xtype: 'combo',
	store: crud_empleado_usuario_datastore,
	hiddenName: 'usu_codigo',
	name: 'empl_usu_codigo',
	id: 'empl_usu_codigo',
	mode: 'local',
	valueField: 'usu_codigo',
	forceSelection: true,
	displayField: 'usu_login',
	triggerAction: 'all',
	emptyText: 'Seleccione un login...',
	selectOnFocus: true,
	fieldLabel: 'Login',
	allowBlank: false,
	listeners: {
		render: function(){
			ayuda('empl_usu_codigo', ayuda_empl_usu_codigo);
		},
		focus : function(){
			crud_empleado_usuario_datastore.reload();
		} 
	}
});

	
	var empl_url_foto=new Ext.ux.form.FileUploadField({
		xtype: 'fileuploadfield',		 
		labelStyle: 'text-align:right;',
		name: 'empl_foto',
		id: 'empl_url_foto',
		fieldLabel: '<html>Escoger Foto</html>',
		emptyText: 'Seleccione una imagen', 
		buttonText: '',
		disabled:false,
		buttonCfg: {iconCls: 'archivo'},  	
		allowBlank: true,
		listeners:
		{
			'render': function() {
					ayuda('empl_url_foto', ayuda_empl_url_foto);
			}
		}
	});
	


var empl_fecha_registro_sistema = new Ext.form.TextField({
	fieldLabel: 'Fecha registro sistema',
	name: 'empl_fecha_registro_sistema',
	id: 'empl_fecha_registro_sistema'
});

 var empl_image_foto = new Ext.Panel({  
     height:110,  
	 bodyStyle:'text-align:center;margin-left:auto;',
     html: '<img id="empl_image_foto" width=100 heigth=100 align=center />'
 });  

var crud_empleado_formpanel = new Ext.FormPanel({
    id: 'crud_empleado_formpanel',
    columnWidth: '.5',
	region:'east',
	split:true,
	collapsible:true,
	width:450,
    url: getAbsoluteUrl('crud_empleado', 'cargar'),
    frame: true,
    labelAlign: 'left',
    title: 'Datos de empleado',
    //autoHeight: true,
    bodyStyle: 'padding:5px',
    autoScroll: true,
	labelWidth: 140,
	fileUpload: true,
    items: [
		empl_image_foto,	
		{
		columnWidth:'1',
		height: 260,
		title:'Datos de la persona',
		bodyStyle: 'padding:10px;',
		xtype:'fieldset',
		defaults: {
			allowBlank: true,
			anchor: '98%',
			labelStyle: 'text-align:right;'
		},
		items:[
			{
				xtype: 'label',
				html: '<center>Todos los campos son obligatorios<br/><br/></center>',
				style: 'font-size:8.5pt; color:#484848;font-weight: bold;'
			},
			{
				xtype:'textfield',
				fieldLabel: 'Id de empleado',
				hidden:true,
				hiddenLabel :true,
				name: 'empl_codigo',
				id: 'empl_codigo'
			},
			empl_nombres,
			empl_apellidos,
			empl_tid_codigo,
			empl_numero_identificacion,
			empl_usu_codigo,
			empl_emp_codigo,
			empl_url_foto
		]},
	],
    buttons: [{
        text: 'Crear',
        align: 'center',
        id: 'crud_empleado_guardar_boton',
        iconCls: 'guardar',
        handler: function(formu, accion){
				
					if(Ext.getCmp('crud_empleado_guardar_boton').getText()=='Actualizar'){
						Ext.Msg.prompt(
							'Empleado',
							'Digite la causa de la actualizaci&oacute;n de este empleado',
							function(btn, text,op){
									if (btn == 'ok') {
									empleado_actualizar(formu, accion,text);
									}
								}
						);	
					}
					else{
						empleado_actualizar(formu, accion,'');
					}
				}	
    }]
});

var crud_empleado_contenedor = new Ext.Panel({
    id: 'crud_empleado_contenedor',
    url: getAbsoluteUrl('crud_empleado', 'cargar'),
    monitorResize: true,
    labelAlign: 'left',
    border: false,
    bodyStyle: 'padding:5px',
    autoScroll: true,
	height: largo_panel,
    layout: 'border',
    items: [crud_empleado_gridpanel, crud_empleado_formpanel],
    renderTo: 'div_form_crud_empleado'
});

Ext.get('empl_image_foto').dom.src = urlPrefix +'../images/vacio.png'; 

/*************************************/
/*Aqui tenemos el manejo de eventos tanto de crear , actualizar, eliminar*/
/*************************************/
function empleado_agregar(formulario, accion){
    crud_empleado_maskara = new Ext.LoadMask(crud_empleado_formpanel.getEl(), {
        msg: 'Cargando...',
        removeMask: true
    });
    crud_empleado_maskara.show();
    setTimeout('crud_empleado_maskara.hide()', 500);
    var empleado_titulo_Panel = 'Nuevo empleado';
    Ext.getCmp('crud_empleado_formpanel').setTitle(empleado_titulo_Panel);
    crud_empleado_formpanel.getForm().reset();
    cargaEmpresa();
    Ext.getCmp('crud_empleado_guardar_boton').setText('Crear');
}

function empleado_actualizar(formulario, accion, text){

    var verificacion = empleado_verificarcampos(Ext.getCmp('crud_empleado_guardar_boton').getText());
    
    if (verificacion) {
        if (Ext.getCmp('crud_empleado_guardar_boton').getText() == 'Actualizar') {
            task = 'ACTUALIZARUSUARIO';
        }
        else {
            task = 'CREARUSUARIO';
        }
        crud_empleado_formpanel.getForm().submit({
            method: 'POST',
            url: getAbsoluteUrl('crud_empleado', 'cargar'),
            params: {
                task: task,
				empl_causa_actualizacion: text
            },
            waitTitle: 'Enviando',
            waitMsg: 'Enviando datos...',
            success: function(response, action){
                obj = Ext.util.JSON.decode(action.response.responseText);
                mostrarMensajeRapido('Aviso', obj.mensaje);
                crud_empleado_datastore.baseParams.empl_habilitado = '';
                crud_empleado_datastore.load({
                    params: {
                        start: 0,
                        limit: 20
                    }
                });
            },
            failure: function(form, action, response){
                if (action.failureType == 'server') {
                    obj = Ext.util.JSON.decode(action.response.responseText);
                    mostrarMensajeConfirmacion(obj.errors.reason);
                }
                
            }
        });
        
        
    }
}

function empleado_confirmar_borrado(){
    if (crud_empleado_gridpanel.selModel.getCount() == 1) {
        Ext.MessageBox.confirm('Confirmacion', 'Desea borrar este Empleado?', empleado_borrar);
    }
    else 
        if (crud_empleado_gridpanel.selModel.getCount() > 1) {
            Ext.MessageBox.confirm('Confirmacion', 'Borrar estos Empleados?', empleado_borrar);
        }
        else {
            Ext.MessageBox.alert('Advertencia', 'Seleccione un empleado a eliminar');
        }
}

function empleado_borrar(boton){
    if (boton == 'yes') {
		Ext.Msg.prompt('Eliminar empleado', 
		'Digite la causa de la eliminaci&oacute;n de este empleado', 
		function(button, text){
			if (button == 'ok') {
			
				var selections = crud_empleado_gridpanel.selModel.getSelections();
				var empleadosSelecionados = [];
				var fila = crud_empleado_gridpanel.selModel.getSelected();
				
				var identificador = fila.get('empl_codigo');
				empleadosSelecionados.push(identificador);
				var encoded_array = Ext.encode(empleadosSelecionados);
				
				Ext.Ajax.request({
					waitMsg: 'Por Favor Espere...',
					url: getAbsoluteUrl('crud_empleado', 'cargar'),
					params: {
						task: "ELIMINARUSUARIO",
						ids_empleados: encoded_array,
						empl_causa_eliminacion:text
					},
					success: function(response){
						obj = Ext.util.JSON.decode(response.responseText);
						if (obj.success) {
							mostrarMensajeRapido('Aviso', obj.mensaje);
							crud_empleado_datastore.reload();
						}
						else 
							if (obj.success == false) {
								mostrarMensajeRapido('Aviso', obj.errors.reason);
							}
					},
					failure: function(response){
						var result = response.responseText;
						mostrarMensajeConfirmacion('Error', 'No se pudo conectar con la base de datos');
					}
				});
			}
		});
		
    }
}

function empleado_verificarcampos(accion){
    var valido = true;
    if (Ext.getCmp('empl_usu_codigo').getValue()=='') {
        mostrarMensajeConfirmacion('Aviso', 'Debe selecionar un login');
        return false;
    }
    if (Ext.getCmp('empl_nombres').getValue()=='') {
        mostrarMensajeConfirmacion('Aviso', 'Debe llenar el nombre');
        return false;
    }
	if (Ext.getCmp('empl_numero_identificacion').getValue()=='') {
        mostrarMensajeConfirmacion('Aviso', 'Debe llenar el n&uacute;mero de identificación');
        return false;
    }
	if (Ext.getCmp('empl_tid_codigo').getValue()=='') {
        mostrarMensajeConfirmacion('Aviso', 'Debe seleccionar el tipo de identificación');
        return false;
    }
    return valido;
}


function cargaEmpresa(){
	//seleccionar la primera empresa de la lista cuando se de click en nuevo
	//
	if(crud_empleado_empresa_datastore.getCount()>0){
		var record_emp = crud_empleado_empresa_datastore.getAt(0);
		empl_emp_codigo.setValue(record_emp.data.emp_codigo);
	}
}
