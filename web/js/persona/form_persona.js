var ayuda_pers_codigo='';
var ayuda_pers_nombres='Ingrese los nombres de la persona';
var ayuda_pers_apellidos='Ingrese los apellidos de la persona';
var ayuda_pers_numero_identificacion='Ingrese el número de identificación de la persona sin puntos ni comas'; 
var ayuda_pers_cargo='Ingrese el cargo de la persona';
var ayuda_pers_correo='Ingrese el correo electrónico de la persona';
var ayuda_pers_telefono='Ingrese el teléfono fijo de la persona'; 
var ayuda_pers_celular='Ingrese el teléfono celular de la persona'; 
var largo_panel=450;

var crud_persona_datastore_principal = new Ext.data.Store({
id: 'crud_persona_datastore_principal',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('personas','listarPersona'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'pers_codigo', type: 'int'},
                {name: 'pers_nombres', type: 'string'},
                {name: 'pers_apellidos', type: 'string'},
                {name: 'pers_numero_identificacion', type: 'string'},
                {name: 'pers_cargo', type: 'string'},
                {name: 'pers_correo', type: 'string'},
                {name: 'pers_telefono', type: 'string'},                
                {name: 'pers_celular', type: 'string'},                
                {name: 'pers_fecha_registro', type: 'string'},
                {name: 'pers_usuario', type: 'int'},
                {name: 'pers_usuario_nombre', type: 'string'},
        ])
});
crud_persona_datastore_principal.load();
	
var pers_codigo=new Ext.form.NumberField({
   xtype: 'numberfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'pers_codigo',
   id: 'pers_codigo',
   hideLabel:true,
   hidden:true,
   listeners:
   {
        'render': function() {
                        ayuda('pers_codigo', ayuda_pers_codigo);
                        }
   }
});
	
var pers_nombres=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'pers_nombres',
   id: 'pers_nombres',
   fieldLabel: 'Nombres de la Persona',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('pers_nombres', ayuda_pers_nombres);
                        }
   }
});

var pers_apellidos=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'pers_apellidos',
   id: 'pers_apellidos',
   fieldLabel: 'Apellidos de la Persona',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('pers_apellidos', ayuda_pers_apellidos);
                        }
   }
});

var pers_numero_identificacion=new Ext.form.NumberField({
   xtype: 'numberfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'pers_numero_identificacion',
   id: 'pers_numero_identificacion',
   fieldLabel: 'Número de Identificación',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('pers_numero_identificacion', ayuda_pers_numero_identificacion);
                        }
   }
});

var pers_cargo=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'pers_cargo',
   id: 'pers_cargo',
   fieldLabel: 'Cargo',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('pers_cargo', ayuda_pers_cargo);
                        }
   }
});

var pers_correo=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'pers_correo',
   id: 'pers_correo',
   fieldLabel: 'Correo Electrónico',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('pers_correo', ayuda_pers_correo);
                        }
   }
});

var pers_telefono=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'pers_telefono',
   id: 'pers_telefono',
   fieldLabel: 'Teléfono Fijo',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('pers_telefono', ayuda_pers_telefono);
                        }
   }
});

var pers_celular=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'pers_celular',
   id: 'pers_celular',
   fieldLabel: 'Teléfono Celular',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('pers_celular', ayuda_pers_celular);
                        }
   }
});

var pers_fecha_registro=new Ext.form.TextField({
   xtype: 'textfield',		 
   labelStyle: 'text-align:right;',
   name: 'pers_fecha_registro',
   id: 'pers_fecha_registro',
   fieldLabel: 'Fecha de registro',
   maxLength : 100,
   readOnly:true
});
	
var crud_persona_formpanel = new Ext.FormPanel({
        id:'crud_persona_formpanel',
        frame: true,
        region:'east',
        split:true,
        collapsible:true,
        width:400,
        border:true,
        title:'Registro de Persona',
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
                pers_codigo,
                pers_nombres,
                pers_apellidos,
                pers_numero_identificacion,
                pers_cargo,
                pers_correo,
                pers_telefono,
                pers_celular
        ],
        buttons:
        [
            {
                text: 'Guardar',
                iconCls:'guardar',
                id:'crud_persona_actualizar_boton',
                handler: crud_persona_actualizar
            }
        ]
});

var crud_persona_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
        {id: 'pers_codigo', header: "Id", width: 30, dataIndex: 'pers_codigo'},
        { header: "Nombres", width: 150, dataIndex: 'pers_nombres'},
        { header: "Apellidos", width: 150, dataIndex: 'pers_apellidos'},
        { header: "No. Identificaci&oacute;n", width: 150, dataIndex: 'pers_numero_identificacion'},
        { header: "Cargo", width: 150, dataIndex: 'pers_cargo'},
        { header: "Correo Electr&oacute;nico", width: 150, dataIndex: 'pers_correo'},
        { header: "Tel&eacute;fono Fijo", width: 150, dataIndex: 'pers_telefono'},     
        { header: "Tel&eacute;fono Celular", width: 150, dataIndex: 'pers_celular'},
        { header: "Creado por", width: 160, dataIndex: 'pers_usuario_nombre'},
        { header: "Fecha de registro", width: 120, dataIndex: 'pers_fecha_registro'}
]
});
	
var crud_persona_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_persona_gridpanel',
            title:'Personas registradas en el sistema',
            region:'center',
            stripeRows:true,
            frame: true,
            ds: crud_persona_datastore_principal,
            cm: crud_persona_colmodel,
            selModel: new Ext.grid.RowSelectionModel({
                    singleSelect: true,
                    listeners: {
                            rowselect: function(sm, row, record) {
                                    Ext.getCmp('crud_persona_formpanel').getForm().loadRecord(record);
                                    Ext.getCmp('crud_persona_actualizar_boton').setText('Actualizar');
                            }
                    }
            }),
            height: largo_panel,
            bbar: new Ext.PagingToolbar({
                    pageSize: 15,
                    store: crud_persona_datastore_principal,
                    displayInfo: true,
                    displayMsg: 'Personas {0} - {1} de {2}',
                    emptyMsg: "No hay personas aun"
            }),
            tbar:
            [
                    {	
                            id:'crud_persona_agregar_boton',
                            text:'Agregar',
                            tooltip:'Agregar',
                            iconCls:'agregar',
                            handler:crud_persona_agregar
                    },'-',
                    {
                            text:'Eliminar',
                            tooltip:'Eliminar',
                            iconCls:'eliminar',
                            handler:crud_persona_eliminar
                    },'-',
                    {
                            text:'',
                            iconCls:'activos',
                            tooltip:'Personas activas',
                            handler:function(){
                                    crud_persona_datastore_principal.baseParams.pers_eliminado = '0';
                                    crud_persona_datastore_principal.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },{
                            text:'',
                            iconCls:'eliminados',
                            tooltip:'Personas eliminadas',
                            handler:function(){
                                    crud_persona_datastore_principal.baseParams.pers_eliminado = '1';
                                    crud_persona_datastore_principal.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },'-',{
                            text:'Restablecer',
                            iconCls:'restablece',
                            tooltip:'Restablecer una persona eliminada',
                            handler:function(){
                                     var cant_record = crud_persona_gridpanel.getSelectionModel().getCount();

                                    if(cant_record > 0){
                                    var record = crud_persona_gridpanel.getSelectionModel().getSelected();
                                            if (record.get('pers_codigo') != '') {
                                            Ext.Msg.confirm('Restablecer persona', 
                                            "¿Realmente desea restablecer esta persona?", 
                                            function(btn){
                                                if (btn == 'yes') {
                                                    subirDatosAjax( 
                                                            getAbsoluteUrl('personas', 'restablecerPersona'),
                                                    {
                                                        pers_codigo:record.get('pers_codigo')
                                                    }, 
                                                    function(){
                                                            crud_persona_datastore_principal.reload();
                                                    }, 
                                                    function(){}
                                                    );
                                            }
                                            });
                                         }
                                    }
                                    else {
                                            mostrarMensajeConfirmacion('Error', "Seleccione una persona eliminada");
                                    }
                            }
                    }
            ],
		plugins:[ new Ext.ux.grid.Search({
				mode:          'local',
				position:      top,
				searchText:    'Filtrar',
				iconCls:  'filtrar',
				selectAllText: 'Seleccionar todos',
				searchTipText: 'Escriba el texto que desea buscar y presione la tecla enter',
				width:         150
			})
		]
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_persona_contenedor_panel = new Ext.Panel({
        id: 'crud_persona_contenedor_panel',
        height: largo_panel,
        autoWidth: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede ver, agregar y eliminar personas',
        monitorResize:true,
        layout:'border',
        items: 
        [
                crud_persona_gridpanel,
                crud_persona_formpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_persona'
});
		
function crud_persona_actualizar(btn){
        if(crud_persona_formpanel.getForm().isValid())
        {
                subirDatos(
                        crud_persona_formpanel,
                        getAbsoluteUrl('personas','actualizarPersona'),
                        {},
                        function(){
                        crud_persona_formpanel.getForm().reset();
                        crud_persona_datastore_principal.load(); 
                        },
                        function(){}
                        );
        }
}
        
function crud_persona_eliminar()
{
        var cant_record = crud_persona_gridpanel.getSelectionModel().getCount();

        if(cant_record > 0){
                var record = crud_persona_gridpanel.getSelectionModel().getSelected();
                if(record.get('pers_codigo')!='')
                {
                        Ext.Msg.confirm('Eliminar persona', 
                        "¿Realmente desea eliminar esta persona?", 
                        function(btn){
                                if (btn == 'yes') {
                                        subirDatosAjax(
                                                getAbsoluteUrl('personas','eliminarPersona'),
                                                {
                                                    pers_codigo:record.get('pers_codigo')
                                                },
                                                function(){
                                                crud_persona_datastore_principal.reload(); 
                                                }
                                        );
                                }
                        });
                }
        }
        else{
                mostrarMensajeConfirmacion('Error',"Seleccione una persona a eliminar");
        }
}
	
function crud_persona_agregar(btn, ev) {
        crud_persona_formpanel.getForm().reset();
        Ext.getCmp('crud_persona_actualizar_boton').setText('Guardar');
}