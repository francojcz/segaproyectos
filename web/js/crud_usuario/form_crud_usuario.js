var usu_password_valor_trae = '';

var ayuda_usu_codigo = 'Id del usuario';
var ayuda_usu_login = 'Login del usuario digitos ';
var ayuda_usu_password = 'Password del usuario, min&iacute;mo 8 digitos ';
var ayuda_usu_repassword = 'Repetir el password del usuario';
var ayuda_usu_per_codigo = 'Seleccione el perfil del usuario';
var ayuda_usu_habilitado = 'Seleccione si el usuario esta habilitado';

var largo_panel=500;
var crud_usuario_datastore = new Ext.data.JsonStore({
    id: 'crud_usuario_datastore',
    url: getAbsoluteUrl('crud_usuario', 'cargar'),
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
		{name: 'usu_codigo', type: 'int' }, 
		{name: 'usu_login',type: 'string'},    
		{name: 'usu_per_codigo',type: 'int'}, 
		{name: 'usu_habilitado',type: 'int'}, 
		{name: 'usu_per_nombre',type: 'string'},
		
		{name: 'usu_fecha_registro_sistema',type: 'string'},
		{name: 'usu_crea_nombre',type: 'string'},
		{name: 'usu_actualiza_nombre',type: 'string'},
		{name: 'usu_fecha_actualizacion',type: 'string'},
		{name: 'usu_causa_actualizacion',type: 'string'}
		],
    sortInfo: {
        field: 'usu_login',
        direction: 'ASC'
    }
});
crud_usuario_datastore.load({
    params: {
        start: 0,
        limit: 20
    }
});

var crud_usuario_columnmodel = new Ext.grid.ColumnModel({
    columns: [
		{header: 'Login',dataIndex: 'usu_login',width: 100}, 
		{header: 'Perfil',dataIndex: 'usu_per_nombre',width: 100}, 
		{header: 'Habilitado',dataIndex: 'usu_habilitado',width: 120,
			renderer:function(val){
				if(val=='1')
				{
				 return '<img src="'+urlPrefix+'../images/iconos/habilitado.png" >';
				}
				else
				{ 
				 return '<img src="'+urlPrefix+'../images/iconos/deshabilitado.png" >';
				}
			}
		},
		{ header: "Creado por", width: 120, dataIndex: 'usu_crea_nombre'},
		{ header: "Fecha de creaci&oacute;n", width: 120, dataIndex: 'usu_fecha_registro_sistema'},
		{ header: "Actualizado por", width: 120, dataIndex: 'usu_actualiza_nombre'},
		{ header: "Fecha de actualizaci&oacute;n", width: 120, dataIndex: 'usu_fecha_actualizacion'},
		{ header: "Causa actualizaci&oacute;n", width: 120, dataIndex: 'usu_causa_actualizacion'}//,
	//++	{ header: "Causa eliminaci&oacute;n", width: 120, dataIndex: 'usu_causa_eliminacion'}
	
    ],
    defaults: {sortable: true}
});

var crud_usuario_gridpanel = new Ext.grid.GridPanel({
    columnWidth: '.5',
    frame: true,
    region: 'center',
    height: largo_panel ,
    id: 'crud_usuario_gridpanel',
    cm: crud_usuario_columnmodel,
    stripeRows: true,
    ds: crud_usuario_datastore,
    title: 'Lista de usuarios del sistema',
    border: true,
    bbar: new Ext.PagingToolbar({
        pageSize: 10,
        store: crud_usuario_datastore,
        displayInfo: true,
        displayMsg: 'Usuarios {0} - {1} de {2}',
        emptyMsg: "No hay Usuarios"
    }),
    listeners: {
        render: function(g){
            g.getSelectionModel().selectRow(0);
        },
        delay: 10
    },
    tbar: [{
        text: 'Agregar',
        tooltip: 'Agregar un usuario',
        iconCls: 'agregar',
        handler: usuario_agregar
    }, /*{
        text: 'Borrar',
        tooltip: 'Borra un usuario',
        iconCls: 'eliminar',
        handler: usuario_confirmar_borrado
    },*/ 
	{
        text: '',
        tooltip: 'Usuarios habilitados',
        iconCls: 'usuario_habilitados_icono',
        handler: function(){
            crud_usuario_datastore.baseParams.usu_habilitado = '1';
            crud_usuario_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, {
        text: '',
        tooltip: 'Usuarios deshabilitados',
        iconCls: 'usuario_deshabilitados_icono',
        handler: function(){
            crud_usuario_datastore.baseParams.usu_habilitado = '0';
            crud_usuario_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, {
        text: '',
        tooltip: 'Todos los usuarios',
        iconCls: 'usuario_todos_icono',
        handler: function(){
            crud_usuario_datastore.baseParams.usu_habilitado = '';
            crud_usuario_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }],
    /*
     plugins:[new Ext.ux.grid.Search({
     mode:          'local',
     position:      top,
     searchText:    'Filtrar',
     iconCls:  'buscar',
     selectAllText: 'Seleccionar todos',
     searchTipText: 'Escriba el texto que desea buscar y presione la tecla enter',
     width:         100
     })],*/
    selModel: new Ext.grid.RowSelectionModel({
        singleSelect: true,
        listeners: {
            rowselect: function(sm, row, rec){
            
                crud_usuario_formpanel.getForm().loadRecord(rec);
                login_usuario = Ext.getCmp('usu_login').getValue();
                titulo = 'Actualizar datos de ' + login_usuario;
                Ext.getCmp('crud_usuario_guardar_boton').setText('Actualizar');
                Ext.getCmp('crud_usuario_formpanel').setTitle(titulo);
                Ext.getCmp('usu_login').setDisabled(true);
                
                Ext.getCmp('usu_password').setValue('');
                Ext.getCmp('usu_repassword').setValue('');
             
            }
        }
    })
});


var usuario_perfil_datastore = new Ext.data.JsonStore({
    id: ' usuario_perfil_datastore',
    url: getAbsoluteUrl('crud_usuario', 'listarPerfil'),
    root: 'results',
    totalProperty: 'total',
    fields: [{
        name: 'per_codigo',
        type: 'string'
    }, {
        name: 'per_nombre',
        type: 'string'
    }, ],
    sortInfo: {
        field: 'per_nombre',
        direction: 'ASC'
    }
});
usuario_perfil_datastore.load();

var usu_login =  new Ext.form.TextField({
	fieldLabel: 'Login de usuario',
	allowBlank: false,
	name: 'usu_login',
	id: 'usu_login',
	blankText: 'El login es obligatorio',
	vtype: 'alphanum',
	maxLength:200,
	minLength : 4,
	listeners: {
		render: function(){
			ayuda('usu_login', ayuda_usu_login);
		}
	}
});

var usu_password = new Ext.form.TextField({
	fieldLabel: 'Password',
	name: 'usu_password',
	id: 'usu_password',
	inputType: 'password',
	blankText: 'La password es obligatorio y debe tener m&iacute;nimo 4 digitos',
	allowBlank: true,
	maxLength:200,
	minLength : 8,
	listeners: {
		render: function(){
			ayuda('usu_password', ayuda_usu_password);
		}
	}
});


var usu_repassword = new Ext.form.TextField({
	fieldLabel: 'Repetir password',
	name: 'usu_repassword',
	id: 'usu_repassword',
	initialPassField: 'usu_repassword',
	allowBlank: true,
	inputType: 'password',
	blankText: 'Repetir el password, es obligatorio',
	maxLength:200,
	minLength : 8,
	listeners: {
		render: function(){
			ayuda('usu_repassword', ayuda_usu_repassword);
		}
	}
});

var usu_per_codigo = new Ext.form.ComboBox({
	xtype: 'combo',
	allowBlank: false,
	store: usuario_perfil_datastore,
	hiddenName: 'per_codigo',
	name: 'usu_per_codigo',
	id: 'usu_per_codigo',
	mode: 'local',
	valueField: 'per_codigo',
	forceSelection: true,
	displayField: 'per_nombre',
	triggerAction: 'all',
	emptyText: 'Seleccione un perfil...',
	selectOnFocus: true,
	fieldLabel: 'Perfil usuario',
	listeners: {
		render: function(){
			ayuda('usu_per_codigo', ayuda_usu_per_codigo);
		},
		focus : function(){
			usuario_perfil_datastore.reload();
		} 
	}
});

var usu_habilitado = new Ext.form.RadioGroup({
	xtype: 'radiogroup',
	fieldLabel: 'Habilitado',
	id:'usu_habilitado',
	allowBlank: false,
	//columns: 1,
	items: [{
		boxLabel: 'Si',
		name: 'usu_habilitado',
		id: 'usu_habilitado_si',
		inputValue: 1
	}, {
		boxLabel: 'No',
		name: 'usu_habilitado',
		id: 'usu_habilitado_no',
		inputValue: 0
	}],
	listeners: {
		render: function(){
			ayuda('usu_habilitado', ayuda_usu_habilitado);
		}
	}
});


var crud_usuario_formpanel = new Ext.FormPanel({
    id: 'crud_usuario_formpanel',
    columnWidth: '.5',
	region:'east',
	width:450,
	split:true,
	collapsible:true,
    url: getAbsoluteUrl('crud_usuario', 'cargar'),
    frame: true,
    labelAlign: 'left',
    title: 'Datos de usuario',
	padding:10,
    autoScroll: true,
    layout: 'form',
	labelWidth: 110,
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
			fieldLabel: 'Id de usuario',
			hidden:true,
			hiddenLabel :true,
			name: 'usu_codigo',
			id: 'usu_codigo'
		},
		usu_login,
		usu_password,
		usu_repassword,
		usu_per_codigo,
		usu_habilitado
	],
    buttons: [{
        text: 'Crear',
        align: 'center',
        id: 'crud_usuario_guardar_boton',
        iconCls: 'guardar',
        handler: function(formulario, accion){
		
			 if (Ext.getCmp('crud_usuario_guardar_boton').getText() == 'Actualizar') {
				Ext.Msg.prompt(
					'Usuario',
					'Digite la causa de la actualizaci&oacute;n de este usuario',
					function(btn, text,op){
							if (btn == 'ok') {
							usuario_actualizar(formulario, accion, text);
							}
						}
				);	
			}
			else{
				usuario_actualizar(formulario, accion, '');
			}
		}
    }]
});

var crud_usuario_contenedor = new Ext.Panel({
    id: 'crud_usuario_contenedor',
    url: getAbsoluteUrl('crud_usuario', 'cargar'),
    monitorResize: true,
    labelAlign: 'left',
    border: false,
    bodyStyle: 'padding:5px',
    autoScroll: true,
	height: largo_panel,
    layout: 'border',
	autoWidth:true,
    items: [crud_usuario_gridpanel, crud_usuario_formpanel],
    renderTo: 'div_form_crud_usuario'
});



/*************************************/
/*Aqui tenemos el manejo de eventos tanto de crear , actualizar, eliminar*/
/*************************************/
function usuario_agregar(formulario, accion){
    crud_usuario_maskara = new Ext.LoadMask(crud_usuario_formpanel.getEl(), {
        msg: 'Cargando...',
        removeMask: true
    });
    crud_usuario_maskara.show();
    setTimeout('crud_usuario_maskara.hide()', 500);
    var usuario_titulo_Panel = 'Nuevo usuario';
    Ext.getCmp('crud_usuario_formpanel').setTitle(usuario_titulo_Panel);
    crud_usuario_formpanel.getForm().reset();
    Ext.getCmp('usu_login').setDisabled(false);
    Ext.getCmp('usu_password').setDisabled(false);
    Ext.getCmp('usu_repassword').setDisabled(false);
    Ext.getCmp('usu_per_codigo').setDisabled(false);
    
    //manejo de habilitado por defecto
    Ext.getCmp('usu_habilitado_si').setDisabled(false);
    Ext.getCmp('usu_habilitado_no').setDisabled(false);
    Ext.getCmp('usu_habilitado_si').setValue(true);
    Ext.getCmp('usu_habilitado_no').setValue(false);
    Ext.getCmp('crud_usuario_guardar_boton').setText('Crear');
}

function usuario_actualizar(formulario, accion, text){

    var verificacion = usuario_verificarcampos(Ext.getCmp('crud_usuario_guardar_boton').getText());
    
    if (verificacion) {
        if (Ext.getCmp('crud_usuario_guardar_boton').getText() == 'Actualizar') {
            task = 'ACTUALIZARUSUARIO';
            Ext.getCmp('usu_codigo').setDisabled(false);
            Ext.getCmp('usu_login').setDisabled(false);
        }
        else {
            task = 'CREARUSUARIO';
        }
        crud_usuario_formpanel.getForm().submit({
            method: 'POST',
            url: getAbsoluteUrl('crud_usuario', 'cargar'),
            params: {
                task: task,
				usu_causa_actualizacion: text
            },
            waitTitle: 'Enviando',
            waitMsg: 'Enviando datos...',
            success: function(response, action){
                obj = Ext.util.JSON.decode(action.response.responseText);
                mostrarMensajeRapido('Aviso', obj.mensaje);
                // crud_usuario_datastore.reload();
                crud_usuario_datastore.baseParams.usu_habilitado = '';
                crud_usuario_datastore.load({
                    params: {
                        start: 0,
                        limit: 20
                    }
                });
                Ext.getCmp('usu_codigo').setDisabled(true);
                if (task == 'ACTUALIZARUSUARIO') {
                    Ext.getCmp('usu_login').setDisabled(true);
                }
                else {
                    crud_usuario_formpanel.getForm().reset();
                }
                //--Ext.getCmp('usu_password').setDisabled(false);
                //--Ext.getCmp('usu_repassword').setDisabled(false);
            
            },
            failure: function(form, action, response){
                if (action.failureType == 'server') {
                    obj = Ext.util.JSON.decode(action.response.responseText);
                    mostrarMensajeConfirmacion(obj.errors.reason);
                }
                
                Ext.getCmp('usu_codigo').setDisabled(true);
                if (task == 'ACTUALIZARUSUARIO') {
                    Ext.getCmp('usu_login').setDisabled(true);
                }
                //--Ext.getCmp('usu_password').setDisabled(false);
                //--Ext.getCmp('usu_repassword').setDisabled(false);
            }
        });
        
        
    }
}

function usuario_confirmar_borrado(){
    if (crud_usuario_gridpanel.selModel.getCount() == 1) {
		Ext.MessageBox.confirm('Confirmacion', 'Realmente desea borrar este Usuario?', usuario_borrar);
    }
    else 
    {
		Ext.MessageBox.alert('Advertencia', 'Seleccione un usuario a eliminar');
    }
}

function usuario_borrar(boton){
    if (boton == 'yes') {
		
		Ext.Msg.prompt('Eliminar usuario', 
		'Digite la causa de la eliminaci&oacute;n de este usuario', 
		function(btn2, text){
			if (btn2 == 'ok') {
					subirDatosAjax(
						getAbsoluteUrl('crud_usuario','eliminarUsuario'),
						{
						usu_codigo:Ext.getCmp('usu_codigo'),
						usu_causa_eliminacion:text
						},
						function(response){
							obj = Ext.util.JSON.decode(response.responseText);
							if (obj.success) {
								mostrarMensajeRapido('Aviso', obj.mensaje);
								crud_usuario_datastore.reload();
							}
							else {
								if (obj.success == false) {
									mostrarMensajeRapido('Aviso', obj.errors.reason);
								}
							}
						},
						function(response){
							var result = response.responseText;
							mostrarMensajeConfirmacion('Error', 'No se pudo conectar con la base de datos');
						}
					);
					
			
			/*
			
			Ext.Ajax.request({
				waitMsg: 'Por Favor Espere...',
				url: getAbsoluteUrl('crud_usuario', 'cargar'),
				params: {
					task: "ELIMINARUSUARIO",
					ids_usuarios: encoded_array
				},
				success: function(response){
					obj = Ext.util.JSON.decode(response.responseText);
					if (obj.success) {
						mostrarMensajeRapido('Aviso', obj.mensaje);
						crud_usuario_datastore.reload();
					}
					else {
						if (obj.success == false) {
							mostrarMensajeRapido('Aviso', obj.errors.reason);
						}
					}
				},
				failure: function(response){
					var result = response.responseText;
					mostrarMensajeConfirmacion('Error', 'No se pudo conectar con la base de datos');
				}
			});*/
			}});
    }
}

function usuario_verificarcampos(accion){
    var valido = true;
    
    if (!(Ext.getCmp('usu_login').isValid() &&
    Ext.getCmp('usu_password').isValid())) {
        mostrarMensajeRapido('Aviso', 'Faltan campos por llenar, por favor verifique el login y password');
        return false;
    }
    
    if (Ext.getCmp('usu_password').getValue() != '' || accion == "Crear") {
        if (!(Ext.getCmp('usu_password').getValue() == Ext.getCmp('usu_repassword').getValue())) {
            mostrarMensajeConfirmacion('Aviso', 'La password y la repassword deben ser iguales');
            return false;
        }
        
        if ((Ext.getCmp('usu_password').getValue()).length < 8) {
            mostrarMensajeConfirmacion('Aviso', 'La password debe tener m&iacute;nimo 8 digitos');
            return false;
        }
        
    }
    if (!(Ext.getCmp('usu_per_codigo').validate())) {
        mostrarMensajeConfirmacion('Aviso', 'Debe selecionar un perfil');
        return false;
    }
    
    return valido;
}



