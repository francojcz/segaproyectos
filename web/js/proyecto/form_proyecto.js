var ayuda_pro_codigo='';
var ayuda_pro_codigo_contable='Ingrese el código contable del proyecto'; 
var ayuda_pro_nombre='Ingrese el nombre del proyecto'; 
var ayuda_pro_descripcion='Ingrese una descripción del proyecto'; 
var ayuda_pro_valor='Ingrese el valor del proyecto';
var ayuda_pro_fecha_inicio='Seleccione la fecha de inicio del proyecto';
var ayuda_pro_fecha_fin='Seleccione la fecha de finalización del proyecto';
var ayuda_pro_observaciones='Ingrese alguna observación sobre el proyecto';
var ayuda_pro_usu_persona='Seleccione el coordinador del proyecto';
var ayuda_pro_estado='Seleccione el estado del proyecto';
var ayuda_pro_ejecutor='Seleccione el ejecutor del proyecto';
var ayuda_pro_tipo='Seleccione el tipo de proyecto';
var ayuda_pro_otro_tipo='Ingrese otro tipo del proyecto'; 

var largo_panel=500;

var crud_proyecto_datastore = new Ext.data.Store({
id: 'crud_proyecto_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('proyectos','listarProyecto'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'pro_codigo', type: 'int'},
                {name: 'pro_codigo_contable', type: 'string'},
                {name: 'pro_nombre', type: 'string'},
                {name: 'pro_descripcion', type: 'string'},
                {name: 'pro_valor', type: 'string'},
                {name: 'pro_fecha_inicio', type: 'string'},
                {name: 'pro_fecha_fin', type: 'string'},
                {name: 'pro_observaciones', type: 'string'},
                {name: 'pro_fecha_registro', type: 'string'},
                {name: 'pro_usu_persona', type: 'int'},
                {name: 'pro_usu_persona_pro_nombre', type: 'string'},
                {name: 'pro_estado', type: 'int'},
                {name: 'pro_estado_nombre', type: 'string'},
                {name: 'pro_ejecutor', type: 'int'},
                {name: 'pro_ejecutor_nombre', type: 'string'},
                {name: 'pro_tipo', type: 'int'},
                {name: 'pro_tipo_nombre', type: 'string'},
                {name: 'pro_otro_tipo', type: 'string'},
                {name: 'pro_presupuesto_url', type: 'string'},
                {name: 'pro_usuario', type: 'string'},
                {name: 'pro_usuario_nombre', type: 'string'}
        ])
});
crud_proyecto_datastore.load();

var crud_persona_datastore = new Ext.data.Store({
    id: 'crud_persona_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('proyectos', 'listarPersona'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'persona_pro_codigo'
    }, {
        name: 'persona_pro_nombre'
    }])
});
crud_persona_datastore.load();

var crud_estado_datastore = new Ext.data.Store({
    id: 'crud_estado_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('proyectos', 'listarEstado'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'est_codigo'
    }, {
        name: 'est_nombre'
    }])
});
crud_estado_datastore.load();

var crud_ejecutor_datastore = new Ext.data.Store({
    id: 'crud_ejecutor_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('proyectos', 'listarEjecutor'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'eje_codigo'
    }, {
        name: 'eje_nombre'
    }])
});
crud_ejecutor_datastore.load();

var crud_tipo_datastore = new Ext.data.Store({
    id: 'crud_tipo_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('proyectos', 'listarTipo'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'tipp_codigo'
    }, {
        name: 'tipp_nombre'
    }])
});
crud_tipo_datastore.load();
	
var pro_codigo=new Ext.form.NumberField({
   xtype: 'numberfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'pro_codigo',
   id: 'pro_codigo',
   hideLabel:true,
   hidden:true,
   listeners:
   {
        'render': function() {
                        ayuda('pro_codigo', ayuda_pro_codigo);
                        }
   }
});

var pro_codigo_contable=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 4,
   minLength : 4,
   name: 'pro_codigo_contable',
   id: 'pro_codigo_contable',
   fieldLabel: 'Centro de Costo',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('pro_codigo_contable', ayuda_pro_codigo_contable);
                        },
        'change': function() {
        if(Ext.getCmp('pro_codigo_contable').isValid()) {
            Ext.getCmp('pro_nombre').setDisabled(false);
            Ext.getCmp('pro_descripcion').setDisabled(false);
            Ext.getCmp('pro_valor').setDisabled(false);
            Ext.getCmp('pro_fecha_inicio').setDisabled(false);
            Ext.getCmp('pro_fecha_fin').setDisabled(false);
            Ext.getCmp('pro_presupuesto_url').setDisabled(false);
            Ext.getCmp('pro_observaciones').setDisabled(false);
        }
        else {
            Ext.getCmp('pro_nombre').setDisabled(true);
            Ext.getCmp('pro_descripcion').setDisabled(true);
            Ext.getCmp('pro_valor').setDisabled(true);
            Ext.getCmp('pro_fecha_inicio').setDisabled(true);
            Ext.getCmp('pro_fecha_fin').setDisabled(true);
            Ext.getCmp('pro_presupuesto_url').setDisabled(true);
            Ext.getCmp('pro_observaciones').setDisabled(true);
        }
      }
   }
});
	
var pro_nombre=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'pro_nombre',
   id: 'pro_nombre',
   fieldLabel: 'Nombre del proyecto',
   allowBlank: false,
   disabled: true,
   listeners:
   {
        'render': function() {
                        ayuda('pro_nombre', ayuda_pro_nombre);
                        }
   }
});

var pro_descripcion=new Ext.form.TextArea({
   xtype: 'textarea',
   labelStyle: 'text-align:right;',
   maxLength : 1000,
   width: 100,
   name: 'pro_descripcion',
   id: 'pro_descripcion',
   fieldLabel: 'Descripci&oacute;n',
   allowBlank: false,
   disabled: true,
   listeners:
   {
    'render': function() {
                ayuda('pro_descripcion', ayuda_pro_descripcion);
                }
   }
});

var pro_valor=new Ext.form.NumberField({
   xtype: 'numberfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   decimalPrecision: 10,
   name: 'pro_valor',
   id: 'pro_valor',
   fieldLabel: 'Valor del proyecto',
   allowBlank: false,
   disabled: true,
   listeners:
   {
        'render': function() {
                        ayuda('pro_valor', ayuda_pro_valor);
                        }
   }
});

var pro_fecha_inicio=new Ext.form.DateField({
    xtype: 'datefield',		 
    labelStyle: 'text-align:right;',
    name: 'pro_fecha_inicio',
    id: 'pro_fecha_inicio',
    fieldLabel: 'Fecha de inicio',
    allowBlank: false,
    format:'Y-m-d',
    anchor:'98%',
    disabled: true,
    listeners:
    {
        'render': function() {
                        ayuda('pro_fecha_inicio', ayuda_pro_fecha_inicio);
                        }
    }
});

var pro_fecha_fin=new Ext.form.DateField({
    xtype: 'datefield',		 
    labelStyle: 'text-align:right;',
    name: 'pro_fecha_fin',
    id: 'pro_fecha_fin',
    fieldLabel: 'Fecha de finalizaci&oacute;n',
    allowBlank: false,
    format:'Y-m-d',
    anchor:'98%',
    disabled: true,
    listeners:
    {
        'render': function() {
                        ayuda('pro_fecha_fin', ayuda_pro_fecha_fin);
                        }
    }
});

var pro_observaciones=new Ext.form.TextArea({
   xtype: 'textarea',
   labelStyle: 'text-align:right;',
   maxLength : 1000,
   width: 100,
   name: 'pro_observaciones',
   id: 'pro_observaciones',
   fieldLabel: 'Observaciones',
   allowBlank: true,
   disabled: true,
   listeners:
   {
        'render': function() {
                        ayuda('pro_observaciones', ayuda_pro_observaciones);
                        }
   }
});

var pro_fecha_registro=new Ext.form.TextField({
   xtype: 'textfield',		 
   labelStyle: 'text-align:right;',
   name: 'pro_fecha_registro',
   id: 'pro_fecha_registro',
   fieldLabel: 'Fecha de registro',
   maxLength : 100,
   readOnly:true
});

var pro_usu_persona = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'persona_pro_nombre',
    hiddenName: 'pro_usu_persona',
    name: 'pro_usu_persona',
    fieldLabel: 'Coordinador del proyecto',
    store: crud_persona_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'persona_pro_nombre',
    valueField: 'persona_pro_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('persona_pro_nombre', ayuda_pro_usu_persona);
        },
        focus: function(){
            crud_persona_datastore.reload();
        }
    }
});

var pro_estado = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'est_nombre',
    hiddenName: 'pro_estado',
    name: 'pro_estado',
    fieldLabel: 'Estado del proyecto',
    store: crud_estado_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'est_nombre',
    valueField: 'est_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('est_nombre', ayuda_pro_estado);
        },
        focus: function(){
            crud_estado_datastore.reload();
        }
    }
});

var pro_ejecutor = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'eje_nombre',
    hiddenName: 'pro_ejecutor',
    name: 'pro_ejecutor',
    fieldLabel: 'Ejecutor del proyecto',
    store: crud_ejecutor_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'eje_nombre',
    valueField: 'eje_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('eje_nombre', ayuda_pro_ejecutor);
        },
        focus: function(){
            crud_ejecutor_datastore.reload();
        }
    }
});

var pro_tipo = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'tipp_nombre',
    hiddenName: 'pro_tipo',
    name: 'pro_tipo',
    fieldLabel: 'Tipo de proyecto',
    store: crud_tipo_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'tipp_nombre',
    valueField: 'tipp_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('tipp_nombre', ayuda_pro_tipo);
        },
        focus: function(){
            crud_tipo_datastore.reload();
        },
       select: function() {
           if(pro_tipo.getValue() == '4') {
               Ext.getCmp('pro_otro_tipo').setDisabled(false);
           }
           else {
               Ext.getCmp('pro_otro_tipo').setDisabled(true);
           }
       }   
    }
});

var pro_otro_tipo=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'pro_otro_tipo',
   id: 'pro_otro_tipo',
   fieldLabel: 'Otro tipo de proyecto',
   allowBlank: true,
   disabled: true,
   listeners:
   {
        'render': function() {
                        ayuda('pro_otro_tipo', ayuda_pro_otro_tipo);
                        }
   }
});

var pro_presupuesto_url=new Ext.ux.form.FileUploadField({
        xtype: 'fileuploadfield',		 
        labelStyle: 'text-align:right;',
        name: 'pro_presupuesto_url',
        id: 'pro_presupuesto_url',
        fieldLabel: 'Archivo con Presupuesto',
        emptyText: 'Seleccione un archivo', 
        buttonText: '',
        buttonCfg: {iconCls: 'archivo'},  	
        allowBlank: false,
        disabled: true
});
	
var crud_proyecto_formpanel = new Ext.FormPanel({
        id:'crud_proyecto_formpanel',
        frame: true,
        region:'east',
        split:true,
        collapsible:true,
        width:450,
        border:true,
        title:'Registro de Proyecto',
        height: 500,
        layout:'form',
        padding:10,
        defaults:{  anchor:'98%'},
        labelWidth: 190,
        fileUpload: true,
        items:
        [       
                pro_codigo,
                pro_codigo_contable,
                pro_nombre,
                pro_descripcion,
                pro_usu_persona,
                pro_estado,
                pro_ejecutor,
                pro_tipo,
                pro_otro_tipo,
                pro_valor,
                pro_fecha_inicio,
                pro_fecha_fin,
                pro_presupuesto_url,
                pro_observaciones
        ],
        buttons:
        [            
            {
              text : 'Archivo con Presupuesto',
              tooltip: 'Descargar el archivo con presupuesto del proyecto',
              iconCls : 'abrir_manual',
              handler : function()
              {
                var sm = crud_proyecto_gridpanel.getSelectionModel();
                if(sm.hasSelection())
                {
                    var registro = sm.getSelected();
                    if(registro.get('pro_presupuesto_url') === ''){
                        Ext.Msg.show(
                          {
                            title : 'Información',
                            msg : 'No se ha registrado ningún archivo',
                            buttons : Ext.Msg.OK,
                            icon : Ext.MessageBox.INFO
                          });
                    }else {
                        window.open(urlWeb + registro.get('pro_presupuesto_url'));
                    }  
                } else
                {
                  Ext.Msg.show(
                  {
                    title : 'Información',
                    msg : 'Primero debe seleccionar un proyecto',
                    buttons : Ext.Msg.OK,
                    icon : Ext.MessageBox.INFO
                  });
                }
              }
            }, '-',{
                text: 'Guardar',
                iconCls:'guardar',
                id:'crud_proyecto_actualizar_boton',
                handler: crud_proyecto_actualizar
            }
        ]
});

var crud_proyecto_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
        {id: 'pro_codigo', header: "Id", width: 30, dataIndex: 'pro_codigo'},
        { header: "Centro de Costo", width: 100, dataIndex: 'pro_codigo_contable'},
        { header: "Nombre", width: 180, dataIndex: 'pro_nombre'},
        { header: "Descripci&oacute;n", width: 250, dataIndex: 'pro_descripcion'},
        { header: "Coordinador", width: 180, dataIndex: 'pro_usu_persona_pro_nombre'}, 
        { header: "Estado", width: 100, dataIndex: 'pro_estado_nombre'}, 
        { header: "Ejecutor", width: 120, dataIndex: 'pro_ejecutor_nombre'},
        { header: "Tipo", width: 170, dataIndex: 'pro_tipo_nombre'}, 
        { header: "Otro tipo de proyecto", width: 170, dataIndex: 'pro_otro_tipo'}, 
        { header: "Valor", width: 100, dataIndex: 'pro_valor'},
        { header: "Fecha de inicio", width: 120, dataIndex: 'pro_fecha_inicio'},
        { header: "Fecha de finalizaci&oacute;n", width: 120, dataIndex: 'pro_fecha_fin'},      
        { header: "Observaciones", width: 200, dataIndex: 'pro_observaciones'},
        { header: "Creado por", width: 160, dataIndex: 'pro_usuario_nombre'},
        { header: "Fecha de registro", width: 120, dataIndex: 'pro_fecha_registro'}
]
});
	
var crud_proyecto_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_proyecto_gridpanel',
            title:'Proyectos registrados en el sistema',
//            columnWidth: '.6',
            region:'center',
            stripeRows:true,
            frame: true,
            ds: crud_proyecto_datastore,
            cm: crud_proyecto_colmodel,
            selModel: new Ext.grid.RowSelectionModel({
                    singleSelect: true,
                    listeners: {
                            rowselect: function(sm, row, record) {
                                    Ext.getCmp('crud_proyecto_formpanel').getForm().loadRecord(record);
                                    Ext.getCmp('crud_proyecto_actualizar_boton').setText('Actualizar');                                    
                                    Ext.getCmp('pro_nombre').setDisabled(false);
                                    Ext.getCmp('pro_descripcion').setDisabled(false);
                                    Ext.getCmp('pro_valor').setDisabled(false);
                                    Ext.getCmp('pro_fecha_inicio').setDisabled(false);
                                    Ext.getCmp('pro_fecha_fin').setDisabled(false);
                                    Ext.getCmp('pro_presupuesto_url').setDisabled(false);
                                    Ext.getCmp('pro_observaciones').setDisabled(false);
                                    Ext.getCmp('pro_otro_tipo').setDisabled(true);
                                    if(pro_tipo.getValue() == '4') {
                                        Ext.getCmp('pro_otro_tipo').setDisabled(false);
                                    }
                            }
                    }
            }),
            height: largo_panel,
            bbar: new Ext.PagingToolbar({
                    pageSize: 15,
                    store: crud_proyecto_datastore,
                    displayInfo: true,
                    displayMsg: 'Proyectos {0} - {1} de {2}',
                    emptyMsg: "No hay proyectos aun"
            }),
            tbar:
            [
                    {	
                            id:'crud_proyecto_agregar_boton',
                            text:'Agregar',
                            tooltip:'Agregar',
                            iconCls:'agregar',
                            handler:crud_proyecto_agregar
                    },'-',
                    {
                            text:'Eliminar',
                            tooltip:'Eliminar',
                            iconCls:'eliminar',
                            handler:crud_proyecto_eliminar
                    },'-',
                    {
                            text:'',
                            iconCls:'activos',
                            tooltip:'Proyectos activos',
                            handler:function(){
                                    crud_proyecto_datastore.baseParams.pro_eliminado = '0';
                                    crud_proyecto_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },{
                            text:'',
                            iconCls:'eliminados',
                            tooltip:'Proyectos eliminados',
                            handler:function(){
                                    crud_proyecto_datastore.baseParams.pro_eliminado = '1';
                                    crud_proyecto_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },'-',{
                            text:'Restablecer',
                            iconCls:'restablece',
                            tooltip:'Restablecer un proyecto eliminado',
                            handler:function(){
                                     var cant_record = crud_proyecto_gridpanel.getSelectionModel().getCount();

                                    if(cant_record > 0){
                                    var record = crud_proyecto_gridpanel.getSelectionModel().getSelected();
                                            if (record.get('pro_codigo') != '') {
                                            Ext.Msg.confirm('Restablecer proyecto', 
                                            "¿Realmente desea restablecer este proyecto?", 
                                            function(btn){
                                                if (btn == 'yes') {
                                                    subirDatosAjax( 
                                                            getAbsoluteUrl('proyectos', 'restablecerProyecto'),
                                                    {
                                                        pro_codigo:record.get('pro_codigo')
                                                    }, 
                                                    function(){
                                                            crud_proyecto_datastore.reload();
                                                    }, 
                                                    function(){}
                                                    );
                                            }
                                            });
                                         }
                                    }
                                    else {
                                            mostrarMensajeConfirmacion('Error', "Seleccione un proyecto eliminado");
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
var crud_proyecto_contenedor_panel = new Ext.Panel({
        id: 'crud_proyecto_contenedor_panel',
        height: largo_panel,
        autoWidth: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede ver, agregar y eliminar proyectos',
        monitorResize:true,
        layout:'border',
        items: 
        [
                crud_proyecto_gridpanel,
                crud_proyecto_formpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_proyecto'
});
		
function crud_proyecto_actualizar(btn){
        if(crud_proyecto_formpanel.getForm().isValid())
        {
                subirDatos(
                        crud_proyecto_formpanel,
                        getAbsoluteUrl('proyectos','actualizarProyecto'),
                        {},
                        function(){
                        crud_proyecto_formpanel.getForm().reset();
                        crud_proyecto_datastore.reload(); 
                        },
                        function(){}
                        );
        }
}
        
function crud_proyecto_eliminar()
{
        var cant_record = crud_proyecto_gridpanel.getSelectionModel().getCount();

        if(cant_record > 0){
                var record = crud_proyecto_gridpanel.getSelectionModel().getSelected();
                if(record.get('pro_codigo')!='')
                {
                        Ext.Msg.confirm('Eliminar proyecto', 
                        "¿Realmente desea eliminar este proyecto?", 
                        function(btn){
                                if (btn == 'yes') {
                                        subirDatosAjax(
                                                getAbsoluteUrl('proyectos','eliminarProyecto'),
                                                {
                                                    pro_codigo:record.get('pro_codigo')
                                                },
                                                function(){
                                                crud_proyecto_datastore.reload(); 
                                                }
                                        );
                                }
                        });
                }
        }
        else{
                mostrarMensajeConfirmacion('Error',"Seleccione un proyeco a eliminar");
        }
}
	
function crud_proyecto_agregar(btn, ev) {
        crud_proyecto_formpanel.getForm().reset();
        Ext.getCmp('crud_proyecto_actualizar_boton').setText('Guardar');
        Ext.getCmp('pro_nombre').setDisabled(true);
        Ext.getCmp('pro_descripcion').setDisabled(true);
        Ext.getCmp('pro_valor').setDisabled(true);
        Ext.getCmp('pro_fecha_inicio').setDisabled(true);
        Ext.getCmp('pro_fecha_fin').setDisabled(true);
        Ext.getCmp('pro_presupuesto_url').setDisabled(true);
        Ext.getCmp('pro_observaciones').setDisabled(true);
        Ext.getCmp('pro_otro_tipo').setDisabled(true);
}

function readTextFile(file)
{
    var rawFile = new XMLHttpRequest();
    rawFile.open("GET", file, true);
    rawFile.onreadystatechange = function ()
    {
        if(rawFile.readyState === 4)
        {
            if(rawFile.status === 200 || rawFile.status == 0)
            {
                var allText = rawFile.responseText;
                alert(allText);
            }
        }
    }
    rawFile.send(null);
}

var windowObjectReference;
var strWindowFeatures = "menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes";

function openRequestedPopup() {
  windowObjectReference = window.open("file:///C:/xampplite/htdocs/segaproyectos/web/texto1.txt", "CNN_WindowName", "resizable,scrollbars,status");
}