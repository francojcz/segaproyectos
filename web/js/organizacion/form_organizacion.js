var ayuda_org_codigo='';
var ayuda_org_nombre_completo='Ingrese el nombre completo de la organización'; 
var ayuda_org_nombre_corto='Ingrese el nombre corto de la organización'; 
var ayuda_org_nit='Ingrese el nit de la organización';
var ayuda_org_direccion='Ingrese la dirección de la organización';
var ayuda_org_correo='Ingrese el correo electrónico de la organización';
var ayuda_org_nombre_contacto='Ingrese el nombre de la persona de contacto';
var ayuda_org_telefono='Ingrese el teléfono de la organización';
var ayuda_org_tipo='Seleccione el tipo de organización';
var largo_panel=450;

var crud_organizacion_datastore = new Ext.data.Store({
id: 'crud_organizacion_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('organizaciones','listarOrganizacion'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'org_codigo', type: 'int'},
                {name: 'org_nombre_completo', type: 'string'},
                {name: 'org_nombre_corto', type: 'string'},
                {name: 'org_nit', type: 'string'},
                {name: 'org_direccion', type: 'string'},
                {name: 'org_correo', type: 'string'},
                {name: 'org_nombre_contacto', type: 'string'},
                {name: 'org_telefono', type: 'string'},
                {name: 'org_fecha_registro', type: 'string'},
                {name: 'org_tipo', type: 'int'},
                {name: 'org_tipo_nombre', type: 'string'},
                {name: 'org_usuario', type: 'int'},
                {name: 'org_usuario_nombre', type: 'string'}
        ])
});
crud_organizacion_datastore.load();

var crud_tipo_datastore = new Ext.data.Store({
    id: 'crud_tipo_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('organizaciones', 'listarTipo'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'tip_codigo'
    }, {
        name: 'tip_nombre'
    }])
});
crud_tipo_datastore.load();

var organizacionesporproyecto_datastore = new Ext.data.Store({
    id: 'organizacionesporproyecto_datastore',
    proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('organizaciones','listarOrganizacionesPorProyecto'),
            method: 'POST'
    }),
    reader: new Ext.data.JsonReader({
            root: 'results',
            totalProperty: 'total',
            id: 'id'
            },[ 
                    {name: 'org_pro_codigo', type: 'int'},
                    {name: 'org_pro_nombre', type: 'string'}
        ])
});
organizacionesporproyecto_datastore.load();

var crud_proyecto_datastore = new Ext.data.JsonStore({
        id: 'crud_proyecto_datastore',
        url: getAbsoluteUrl('organizaciones', 'listarProyecto'),
        root: 'results',
        totalProperty: 'total',
        fields: [
                {name: 'pro_codigo',type: 'int'}, 
                {name: 'pro_nombre',type: 'string'}
        ],
        sortInfo: {
                field: 'pro_nombre',
                direction: 'ASC'
        }
});
crud_proyecto_datastore.load();

var proyecto_codigo = new Ext.form.ComboBox({
        xtype: 'combo',
        width: 200,
        store: crud_proyecto_datastore,
        hiddenName: 'pro_codigo',
        name: 'orgpro_pro_codigo',
        mode: 'local',
        valueField: 'pro_codigo',
        forceSelection: true,
        displayField: 'pro_nombre',
        triggerAction: 'all',
        emptyText: 'Seleccione un Proyecto',
        selectOnFocus: true,
        fieldLabel: 'Proyecto',
        listeners: {
                'change': function() {
                        crud_proyecto_datastore.reload();
                } 
        }
});
	
var org_codigo=new Ext.form.NumberField({
   xtype: 'numberfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'org_codigo',
   id: 'org_codigo',
   hideLabel:true,
   hidden:true,
   listeners:
   {
        'render': function() {
                        ayuda('org_codigo', ayuda_org_codigo);
                        }
   }
});
	
var org_nombre_completo=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 200,
   name: 'org_nombre_completo',
   id: 'org_nombre_completo',
   fieldLabel: 'Nombre Completo',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('org_nombre_completo', ayuda_org_nombre_completo);
                        }
   }
});

var org_nombre_corto=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 200,
   name: 'org_nombre_corto',
   id: 'org_nombre_corto',
   fieldLabel: 'Nombre Corto',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('org_nombre_corto', ayuda_org_nombre_corto);
                        }
   }
});

var org_nit=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'org_nit',
   id: 'org_nit',
   fieldLabel: 'Nit',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('org_nit', ayuda_org_nit);
                        }
   }
});

var org_direccion=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'org_direccion',
   id: 'org_direccion',
   fieldLabel: 'Direcci&oacute;n',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('org_direccion', ayuda_org_direccion);
                        }
   }
});

var org_correo=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'org_correo',
   id: 'org_correo',
   fieldLabel: 'Correo Electrónico',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('org_correo', ayuda_org_correo);
                        }
   }
});

var org_telefono=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'org_telefono',
   id: 'org_telefono',
   fieldLabel: 'Teléfono',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('org_telefono', ayuda_org_telefono);
                        }
   }
});

var org_nombre_contacto=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'org_nombre_contacto',
   id: 'org_nombre_contacto',
   fieldLabel: 'Nombre del Contacto',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('org_nombre_contacto', ayuda_org_nombre_contacto);
                        }
   }
});

var org_fecha_registro=new Ext.form.TextField({
   xtype: 'textfield',		 
   labelStyle: 'text-align:right;',
   name: 'org_fecha_registro',
   id: 'org_fecha_registro',
   fieldLabel: 'Fecha de registro',
   maxLength : 100,
   readOnly:true
});

var org_tipo = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'tip_nombre',
    hiddenName: 'org_tipo',
    name: 'org_tipo',
    fieldLabel: 'Tipo de organizaci&oacute;n',
    store: crud_tipo_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'tip_nombre',
    valueField: 'tip_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('tip_nombre', ayuda_org_tipo);
        },
        'change': function() {
            crud_tipo_datastore.reload();
        }
    }
});

var organizacionporproyecto_pertenece_selmodel = new Ext.grid.CheckboxSelectionModel({
        singleSelect:false,	
        listeners: {
                rowselect: function(sm, row, rec) {
                }
        }
});

var organizacionporproyecto_colmodel = new Ext.grid.ColumnModel({
        defaults:{sortable: true, locked: true, resizable: true},
        columns:[
                organizacionporproyecto_pertenece_selmodel,
                { header: "Id", width: 30, dataIndex: 'org_pro_codigo',hidden:true},
                { header: "Nombre", width: 300, dataIndex: 'org_pro_nombre'}
        ]
});

var organizacionesporproyecto_gridpanel = new Ext.grid.GridPanel({
        id: 'organizacionesporproyecto_gridpanel',        
        title:'Asignaci&oacute;n de Proyectos',
        stripeRows:true,
        frame: true,
        ds: organizacionesporproyecto_datastore,
        cm: organizacionporproyecto_colmodel,
        sm: organizacionporproyecto_pertenece_selmodel,
        height: 260,
        tbar:[
                proyecto_codigo,
                {text:'Agregar a Organizaci&oacute;n',iconCls:'agregar',
                        handler:function(){
                                var sm = crud_organizacion_gridpanel.getSelectionModel();
                                var registro = sm.getSelected();
                                if(proyecto_codigo.getValue()!=''){                                
                                        subirDatosAjax(
                                                getAbsoluteUrl('organizaciones', 'guardarProyectoPorOrganizacion'),
                                                {
                                                        pro_codigo: proyecto_codigo.getValue(),
                                                        org_codigo: registro.get('org_codigo')
                                                }, 
                                                function(){
                                                        organizacionesporproyecto_datastore.reload();
                                                },
                                                function(){}
                                        );
                                }
                        }
                }],
        bbar:[
                {text:'Quitar Proyecto',iconCls:'eliminar',
                        handler:function(){
                                var proyectosSeleccionados = organizacionesporproyecto_gridpanel.selModel.getSelections();
                                var proyectosEliminar = [];
                                for(i = 0; i< organizacionesporproyecto_gridpanel.selModel.getCount(); i++){
                                        proyectosEliminar.push(proyectosSeleccionados[i].json.org_pro_codigo);
                                }
                                var encoded_array_proyectos = Ext.encode(proyectosEliminar);
                                var sm = crud_organizacion_gridpanel.getSelectionModel();
                                var registro = sm.getSelected();

                                if(registro.get('org_codigo') != '' ){
                                        subirDatosAjax(
                                                getAbsoluteUrl('organizaciones', 'eliminarOrganizacionPorProyecto'),
                                                {
                                                        pros_codigos: encoded_array_proyectos,
                                                        org_codigo: registro.get('org_codigo')
                                                }, 
                                                function(){
                                                        organizacionesporproyecto_datastore.reload();
                                                },
                                                function(){}
                                        );
                                }				
                        }
                }
        ]
});
	
var crud_organizacion_formpanel = new Ext.FormPanel({
        id:'crud_organizacion_formpanel',
        frame: true,
        region:'east',
        split:true,
        collapsible:true,
        width:450,
        border:true,
        title:'Registro de Organizaci&oacute;n',
        //autoWidth: true,
        columnWidth: '0.6',
        height: 500,
        layout:'form',
        //bodyStyle: 'padding:10px;',
        padding:10,
        defaults:{  anchor:'98%'},
        labelWidth: 150,
        autoScroll: true,
//        fileUpload: true,
        items:
        [   
                org_codigo,
                org_nombre_completo,
                org_nombre_corto,
                org_nit,
                org_tipo,
                org_direccion,
                org_correo,
                org_nombre_contacto,
                org_telefono,
                organizacionesporproyecto_gridpanel
        ],
        buttons:
        [
            {
                text: 'Guardar',
                iconCls:'guardar',
                id:'crud_organizacion_actualizar_boton',
                handler: crud_organizacion_actualizar
            }
        ]
});

var crud_organizacion_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
        {id: 'org_codigo', header: "Id", width: 30, dataIndex: 'org_codigo'},
        { header: "Nombre Completo", width: 150, dataIndex: 'org_nombre_completo'},
        { header: "Nombre Corto", width: 100, dataIndex: 'org_nombre_corto'},
        { header: "Nit", width: 100, dataIndex: 'org_nit'},        
        { header: "Tipo", width: 140, dataIndex: 'org_tipo_nombre'}, 
        { header: "Direcci&oacute;n", width: 150, dataIndex: 'org_direccion'},
        { header: "Correo Electr&oacute;nico", width: 150, dataIndex: 'org_correo'},
        { header: "Nombre del Contacto", width: 170, dataIndex: 'org_nombre_contacto'}, 
        { header: "Tel&eacute;fono", width: 150, dataIndex: 'org_telefono'},       
        { header: "Creado por", width: 160, dataIndex: 'org_usuario_nombre'},
        { header: "Fecha de registro", width: 120, dataIndex: 'org_fecha_registro'}
]
});
	
var crud_organizacion_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_organizacion_gridpanel',
            title:'Organizaciones registradas en el sistema',
//            columnWidth: '.6',
            region:'center',
            stripeRows:true,
            frame: true,
            ds: crud_organizacion_datastore,
            cm: crud_organizacion_colmodel,
            selModel: new Ext.grid.RowSelectionModel({
                    singleSelect: true,
                    listeners: {
                            rowselect: function(sm, row, record) {
                                    Ext.getCmp('crud_organizacion_formpanel').getForm().loadRecord(record);
                                    Ext.getCmp('crud_organizacion_actualizar_boton').setText('Actualizar');
                                    var sm = crud_organizacion_gridpanel.getSelectionModel();
                                    var registro = sm.getSelected();                                    
                                    organizacionesporproyecto_datastore.load({params:{org_codigo:registro.get('org_codigo') }});
                            }
                    }
            }),
            height: largo_panel,
            bbar: new Ext.PagingToolbar({
                    pageSize: 15,
                    store: crud_organizacion_datastore,
                    displayInfo: true,
                    displayMsg: 'Organizaciones {0} - {1} de {2}',
                    emptyMsg: "No hay organizaciones aun"
            }),
            tbar:
            [
                    {	
                            id:'crud_organizacion_agregar_boton',
                            text:'Agregar',
                            tooltip:'Agregar',
                            iconCls:'agregar',
                            handler:crud_organizacion_agregar
                    },'-',
                    {
                            text:'Eliminar',
                            tooltip:'Eliminar',
                            iconCls:'eliminar',
                            handler:crud_organizacion_eliminar
                    },'-',
                    {
                            text:'',
                            iconCls:'activos',
                            tooltip:'Organizaciones activas',
                            handler:function(){
                                    crud_organizacion_datastore.baseParams.org_eliminado = '0';
                                    crud_organizacion_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },{
                            text:'',
                            iconCls:'eliminados',
                            tooltip:'Organizaciones eliminadas',
                            handler:function(){
                                    crud_organizacion_datastore.baseParams.org_eliminado = '1';
                                    crud_organizacion_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },'-',{
                            text:'Restablecer',
                            iconCls:'restablece',
                            tooltip:'Restablecer una organizaci&oacute;n eliminada',
                            handler:function(){
                                     var cant_record = crud_organizacion_gridpanel.getSelectionModel().getCount();

                                    if(cant_record > 0){
                                    var record = crud_organizacion_gridpanel.getSelectionModel().getSelected();
                                            if (record.get('org_codigo') != '') {
                                            Ext.Msg.confirm('Restablecer organizaci&oacute;n', 
                                            "¿Realmente desea restablecer esta organizaci&oacute;n?", 
                                            function(btn){
                                                if (btn == 'yes') {
                                                    subirDatosAjax( 
                                                            getAbsoluteUrl('organizaciones', 'restablecerOrganizacion'),
                                                    {
                                                        org_codigo:record.get('org_codigo'),
                                                    }, 
                                                    function(){
                                                            crud_organizacion_datastore.reload();
                                                    }, 
                                                    function(){}
                                                    );
                                            }
                                            });
                                         }
                                    }
                                    else {
                                            mostrarMensajeConfirmacion('Error', "Seleccione una organizaci&oacute;n eliminada");
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
var crud_organizacion_contenedor_panel = new Ext.Panel({
        id: 'crud_organizacion_contenedor_panel',
        height: largo_panel,
        autoWidth: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede ver, agregar y eliminar organizaciones',
        monitorResize:true,
        layout:'border',
        items: 
        [
                crud_organizacion_gridpanel,
                crud_organizacion_formpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_organizacion'
});
		
function crud_organizacion_actualizar(btn){
        if(crud_organizacion_formpanel.getForm().isValid())
        {
                subirDatos(
                        crud_organizacion_formpanel,
                        getAbsoluteUrl('organizaciones','actualizarOrganizacion'),
                        {},
                        function(){
                        crud_organizacion_formpanel.getForm().reset();
                        crud_organizacion_datastore.reload(); 
                        },
                        function(){}
                        );
        }
}
        
function crud_organizacion_eliminar()
{
        var cant_record = crud_organizacion_gridpanel.getSelectionModel().getCount();

        if(cant_record > 0){
                var record = crud_organizacion_gridpanel.getSelectionModel().getSelected();
                if(record.get('org_codigo')!='')
                {
                        Ext.Msg.confirm('Eliminar organizaci&oacute;n', 
                        "¿Realmente desea eliminar esta organizaci&oacute;n?", 
                        function(btn){
                                if (btn == 'yes') {
                                        subirDatosAjax(
                                                getAbsoluteUrl('organizaciones','eliminarOrganizacion'),
                                                {
                                                    org_codigo:record.get('org_codigo'),
                                                },
                                                function(){
                                                crud_organizacion_datastore.reload(); 
                                                }
                                        );
                                }
                        });
                }
        }
        else{
                mostrarMensajeConfirmacion('Error',"Seleccione una organizaci&oacute;n a eliminar");
        }
}
	
function crud_organizacion_agregar(btn, ev) {
        crud_organizacion_formpanel.getForm().reset();
        Ext.getCmp('crud_organizacion_actualizar_boton').setText('Guardar');

}