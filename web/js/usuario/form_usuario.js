var ayuda_usu_codigo='';
var ayuda_usu_login='Login de usuario, mínimo 6 caracteres'; 
var ayuda_usu_contrasena='Contrasena de usuario, mínimo 6 caracteres'; 
var ayuda_usu_recontrasena='Repetir contrasena de usuario, mínimo 6 caracteres'; 
var ayuda_usu_perfil='Seleccione el perfil de usuario';
var ayuda_usu_persona='Seleccione la persona a la cual le asignará el usuario';
var ayuda_usu_habilitado = 'Seleccione si el usuario se encuentra habilitado';
var largo_panel=450;

var crud_usuario_datastore = new Ext.data.Store({
id: 'crud_usuario_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('usuarios','listarUsuario'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'usu_codigo', type: 'int'},
                {name: 'usu_login', type: 'string'},
                {name: 'usu_contrasena', type: 'string'},
                {name: 'usu_fecha_registro', type: 'string'},
                {name: 'usu_perfil', type: 'int'},
                {name: 'usu_perfil_nombre', type: 'string'},
                {name: 'usu_persona', type: 'int'},
                {name: 'usu_persona_nombre', type: 'string'},
                {name: 'usu_habilitado',type: 'int'}
        ])
});
crud_usuario_datastore.load();

var crud_perfil_datastore = new Ext.data.Store({
    id: 'crud_perfil_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('usuarios', 'listarPerfil'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'per_codigo'
    }, {
        name: 'per_nombre'
    }])
});
crud_perfil_datastore.load();

var crud_persona_datastore = new Ext.data.Store({
    id: 'crud_persona_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('usuarios', 'listarPersona'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'persona_codigo'
    }, {
        name: 'persona_nombre'
    }])
});
crud_persona_datastore.load();
	
var usu_codigo=new Ext.form.NumberField({
   xtype: 'numberfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'usu_codigo',
   id: 'usu_codigo',
   hideLabel:true,
   hidden:true,
   listeners:
   {
        'render': function() {
                        ayuda('usu_codigo', ayuda_usu_codigo);
                        }
   }
});
	
var usu_login=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'usu_login',
   id: 'usu_login',
   fieldLabel: 'Login',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('usu_login', ayuda_usu_login);
                        }
   }
});

var usu_contrasena=new Ext.form.TextField({
   inputType: 'password',
   labelStyle: 'text-align:right;',
   maxLength : 100,
//   minLength : 6,
   name: 'usu_contrasena',
   id: 'usu_contrasena',
   fieldLabel: 'Contrase&ntilde;a',
   allowBlank: true,
   listeners:
   {
        'render': function() {
                        ayuda('usu_contrasena', ayuda_usu_contrasena);
                        }
   }
});

var usu_recontrasena=new Ext.form.TextField({
   inputType: 'password',
   labelStyle: 'text-align:right;',
   maxLength : 100,
//   minLength : 6,
   name: 'usu_recontrasena',
   id: 'usu_recontrasena',
   fieldLabel: 'Verificaci&oacute;n Contrase&ntilde;a',
   allowBlank: true,
   listeners:
   {
        'render': function() {
                        ayuda('usu_recontrasena', ayuda_usu_recontrasena);
                        }
   }
});

var usu_fecha_registro=new Ext.form.TextField({
   xtype: 'textfield',		 
   labelStyle: 'text-align:right;',
   name: 'usu_fecha_registro',
   id: 'usu_fecha_registro',
   fieldLabel: 'Fecha de registro',
   maxLength : 100,
   readOnly:true
});

var usu_perfil = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'per_nombre',
    hiddenName: 'usu_perfil',
    name: 'usu_perfil',
    fieldLabel: 'Perfil',
    store: crud_perfil_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'per_nombre',
    valueField: 'per_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('per_nombre', ayuda_usu_perfil);
        },
        'change': function() {
            crud_perfil_datastore.reload();
        }
    }
});

var usu_persona = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'persona_nombre',
    hiddenName: 'usu_persona',
    name: 'usu_persona',
    fieldLabel: 'Persona',
    store: crud_persona_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'persona_nombre',
    valueField: 'persona_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('persona_nombre', ayuda_usu_persona);
        },
        'change': function() {
            crud_persona_datastore.reload();
        }
    }
});

var usu_habilitado = new Ext.form.RadioGroup({
	xtype: 'radiogroup',
	fieldLabel: 'Habilitado',
	id:'usu_habilitado',
	allowBlank: false,
        labelStyle: 'text-align:right;',
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
        id:'crud_usuario_formpanel',
        frame: true,
        region:'east',
        split:true,
        collapsible:true,
        width:400,
        border:true,
        title:'Registro de Usuario',
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
                usu_codigo,
                usu_login,
                usu_contrasena,
                usu_recontrasena,
                usu_perfil,                
                usu_persona,
		usu_habilitado
        ],
        buttons:
        [
            {
                text: 'Guardar',
                iconCls:'guardar',
                id:'crud_usuario_actualizar_boton',
                handler: crud_usuario_actualizar
            }
        ]
});

var crud_usuario_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
        { id: 'usu_codigo', header: "Id", width: 30, dataIndex: 'usu_codigo'},
        { header: "Login", width: 120, dataIndex: 'usu_login'},  
        { header: "Perfil", width: 100, dataIndex: 'usu_perfil_nombre'}, 
        { header: "Persona", width: 150, dataIndex: 'usu_persona_nombre'}, 
        {header: 'Habilitado',dataIndex: 'usu_habilitado',width: 80,
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
        { header: "Fecha de registro", width: 120, dataIndex: 'usu_fecha_registro'}
]
});
	
var crud_usuario_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_usuario_gridpanel',
            title:'Usuarios registrados en el sistema',
//            columnWidth: '.6',
            region:'center',
            stripeRows:true,
            frame: true,
            ds: crud_usuario_datastore,
            cm: crud_usuario_colmodel,
            selModel: new Ext.grid.RowSelectionModel({
                    singleSelect: true,
                    listeners: {
                            rowselect: function(sm, row, record) {
                                    Ext.getCmp('crud_usuario_formpanel').getForm().loadRecord(record);
                                    Ext.getCmp('crud_usuario_actualizar_boton').setText('Actualizar');
//                                    Ext.getCmp('usu_contrasena').setValue('');
                                    Ext.getCmp('usu_recontrasena').setValue(Ext.getCmp('usu_contrasena').getValue());
                            }
                    }
            }),
            height: largo_panel,
            bbar: new Ext.PagingToolbar({
                    pageSize: 15,
                    store: crud_usuario_datastore,
                    displayInfo: true,
                    displayMsg: 'Usuarios {0} - {1} de {2}',
                    emptyMsg: "No hay usuarios aun"
            }),
            tbar:
            [
                    {	
                            id:'crud_usuario_agregar_boton',
                            text:'Agregar',
                            tooltip:'Agregar',
                            iconCls:'agregar',
                            handler:crud_usuario_agregar
                    }
            ]
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_usuario_contenedor_panel = new Ext.Panel({
        id: 'crud_usuario_contenedor_panel',
        height: largo_panel,
        autoWidth: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede ver, agregar y deshatilitar usuarios',
        monitorResize:true,
        layout:'border',
        items: 
        [
                crud_usuario_gridpanel,
                crud_usuario_formpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_usuario'
});
		
function crud_usuario_actualizar(btn){
        if(crud_usuario_formpanel.getForm().isValid())
        {
            var resultado = usuario_verificarcampos();
            if( resultado === true){
                subirDatos(
                        crud_usuario_formpanel,
                        getAbsoluteUrl('usuarios','actualizarUsuario'),
                        {},
                        function(){
                        crud_usuario_formpanel.getForm().reset();
                        crud_usuario_datastore.reload(); 
                        },
                        function(){}
                        );
                }
        }         
}    
	
function crud_usuario_agregar(btn, ev) {
        crud_usuario_formpanel.getForm().reset();
        Ext.getCmp('crud_usuario_actualizar_boton').setText('Guardar');
        Ext.getCmp('usu_habilitado_si').setValue(true); 
        Ext.getCmp('usu_habilitado_no').setValue(false);
}

function usuario_verificarcampos(){
    var valido = true;
        
    if (!(Ext.getCmp('usu_contrasena').getValue() == Ext.getCmp('usu_recontrasena').getValue())) {
        mostrarMensajeConfirmacion('Aviso', 'La contrase&ntilde;a y su verificaci&oacute;n deben ser iguales');
        return false;
    }       
    
    if ((Ext.getCmp('usu_contrasena').getValue()).length < 6) {
        mostrarMensajeConfirmacion('Aviso', 'La contrase&ntilde;a debe tener al menos 6 caracteres');
        return false;
    }
    
    return valido;
}