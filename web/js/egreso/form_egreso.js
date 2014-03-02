var ayuda_egr_codigo='';
var ayuda_egr_concepto='Seleccione el concepto del egreso'; 
var ayuda_egr_valor='Ingrese el valor del egreso';
var ayuda_egr_fecha='Ingrese la fecha del egreso';
var ayuda_egr_proyecto='Seleccione el proyecto';
var largo_panel=450;

var crud_egreso_datastore = new Ext.data.Store({
id: 'crud_egreso_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('egresos','listarEgreso'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'egr_codigo', type: 'int'},
                {name: 'egr_valor', type: 'string'},
                {name: 'egr_fecha', type: 'string'},
                {name: 'egr_fecha_registro', type: 'string'},                
                {name: 'egr_usuario', type: 'int'},
                {name: 'egr_usuario_nombre', type: 'string'},
                {name: 'egr_proyecto', type: 'int'},
                {name: 'egr_proyecto_nombre', type: 'string'},
                {name: 'egr_concepto', type: 'int'},
                {name: 'egr_concepto_nombre', type: 'string'},
                {name: 'egr_acumulado_ingresos', type: 'string'},
                {name: 'egr_acumulado_egresos', type: 'string'},
                {name: 'egr_disponible', type: 'string'}
        ])
});
crud_egreso_datastore.load();

var crud_concepto_datastore = new Ext.data.Store({
    id: 'crud_concepto_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('egresos', 'listarConcepto'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'con_egr_codigo'
    }, {
        name: 'con_egr_nombre'
    }])
});
crud_concepto_datastore.load();

var crud_proyecto_datastore = new Ext.data.Store({
    id: 'crud_proyecto_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('egresos', 'listarProyecto'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'pro_egr_codigo'
    }, {
        name: 'pro_egr_nombre'
    }])
});
crud_proyecto_datastore.load();
	
var egr_codigo=new Ext.form.NumberField({
   xtype: 'numberfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'egr_codigo',
   id: 'egr_codigo',
   hideLabel:true,
   hidden:true,
   listeners:
   {
        'render': function() {
                        ayuda('egr_codigo', ayuda_egr_codigo);
                        }
   }
});

var egr_valor=new Ext.form.NumberField({
   xtype: 'numberfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   decimalPrecision: 10,
   name: 'egr_valor',
   id: 'egr_valor',
   fieldLabel: 'Valor del egreso',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('egr_valor', ayuda_egr_valor);
                        }
   }
});

var egr_fecha=new Ext.form.DateField({
    xtype: 'datefield',		 
    labelStyle: 'text-align:right;',
    name: 'egr_fecha',
    id: 'egr_fecha',
    fieldLabel: 'Fecha del egreso',
    allowBlank: false,
    format:'Y-m-d',
    anchor:'98%',
    listeners:
    {
        'render': function() {
                        ayuda('egr_fecha', ayuda_egr_fecha);
                        }
    }
});

var egr_fecha_registro=new Ext.form.TextField({
   xtype: 'textfield',		 
   labelStyle: 'text-align:right;',
   name: 'egr_fecha_registro',
   id: 'egr_fecha_registro',
   fieldLabel: 'Fecha de registro',
   maxLength : 100,
   readOnly:true
});

var egr_concepto = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'con_egr_nombre',
    hiddenName: 'egr_concepto',
    name: 'egr_concepto',
    fieldLabel: 'Concepto del egreso',
    store: crud_concepto_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'con_egr_nombre',
    valueField: 'con_egr_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('con_egr_nombre', ayuda_egr_concepto);
        },
        focus: function(){
            crud_concepto_datastore.reload();
        }
    }
});

var egr_proyecto = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'pro_egr_nombre',
    hiddenName: 'egr_proyecto',
    name: 'egr_proyecto',
    fieldLabel: 'Nombre del Proyecto',
    store: crud_proyecto_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'pro_egr_nombre',
    valueField: 'pro_egr_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('pro_egr_nombre', ayuda_egr_proyecto);
        },
        focus: function(){
            crud_proyecto_datastore.reload();
        }
    }
});

var egr_acumulado_ingresos=new Ext.form.TextField({   
   labelStyle: 'text-align:right;',
   maxLength : 1000,
   width: 150,
   name: 'egr_acumulado_ingresos',
   id: 'egr_acumulado_ingresos',
   fieldLabel: '<b>Total Acumulado Ingresos</b>',
   disabled: true,
   readOnly: true,
   style: 'font-weight: bold'
});

var egr_acumulado_egresos=new Ext.form.TextField({   
   labelStyle: 'text-align:right;',
   maxLength : 1000,
   width: 150,
   name: 'egr_acumulado_egresos',
   id: 'egr_acumulado_egresos',
   fieldLabel: '<b>Total Acumulado Egresos</b>',
   disabled: true,
   readOnly: true,
   style: 'font-weight: bold'
});

var egr_disponible=new Ext.form.TextField({   
   labelStyle: 'text-align:right;',
   maxLength : 1000,
   width: 150,
   name: 'egr_disponible',
   id: 'egr_disponible',
   fieldLabel: '<b>Total Disponible</b>',
   disabled: true,
   readOnly: true,
   style: 'font-weight: bold'
});
	
var crud_egreso_formpanel = new Ext.FormPanel({
        id:'crud_egreso_formpanel',
        frame: true,
        region:'east',
        split:true,
        collapsible:true,
        width:450,
        border:true,
        title:'Registro de Egresos',
        //autoWidth: true,
        columnWidth: '0.6',
        height: 500,
        layout:'form',
        //bodyStyle: 'padding:10px;',
        padding:10,
        defaults:{  anchor:'98%'},
        labelWidth: 180,
        fileUpload: true,
        items:
        [   
                egr_codigo,
                egr_concepto,
                egr_valor,   
                egr_fecha,
                egr_proyecto,
                egr_acumulado_ingresos,
                egr_acumulado_egresos,
                egr_disponible
        ],
        buttons:
        [
            {
                text: 'Guardar',
                iconCls:'guardar',
                id:'crud_egreso_actualizar_boton',
                handler: crud_egreso_actualizar
            }
        ]
});

var crud_egreso_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
        {id: 'egr_codigo', header: "Id", width: 30, dataIndex: 'egr_codigo'}, 
        { header: "Concepto", width: 220, dataIndex: 'egr_concepto_nombre'},
        { header: "Valor", width: 120, dataIndex: 'egr_valor'},
        { header: "Fecha del egreso", width: 120, dataIndex: 'egr_fecha'},
        { header: "Nombre del Proyecto", width: 220, dataIndex: 'egr_proyecto_nombre'},
        { header: "Creado por", width: 150, dataIndex: 'egr_usuario_nombre'},
        { header: "Fecha de registro", width: 120, dataIndex: 'egr_fecha_registro'}
]
});

var crud_egreso_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_egreso_gridpanel',
            title:'Egresos registrados en el sistema',
            region:'center',
            stripeRows:true,
            frame: true,
            ds: crud_egreso_datastore,
            cm: crud_egreso_colmodel,
            selModel: new Ext.grid.RowSelectionModel({
                    singleSelect: true,
                    listeners: {
                            rowselect: function(sm, row, record) {
                                    Ext.getCmp('crud_egreso_formpanel').getForm().loadRecord(record);
                                    Ext.getCmp('crud_egreso_actualizar_boton').setText('Actualizar');
                                    Ext.getCmp('egr_acumulado_ingresos').setDisabled(false);
                                    Ext.getCmp('egr_acumulado_egresos').setDisabled(false);
                                    Ext.getCmp('egr_disponible').setDisabled(false);
                                    Ext.getCmp('egr_valor').setDisabled(true);
                            }
                    }
            }),
            height: largo_panel,
            bbar: new Ext.PagingToolbar({
                    pageSize: 15,
                    store: crud_egreso_datastore,
                    displayInfo: true,
                    displayMsg: 'Egresos {0} - {1} de {2}',
                    emptyMsg: "No hay egresos aun"
            }),
            tbar:
            [
                    {	
                            id:'crud_egreso_agregar_boton',
                            text:'Agregar',
                            tooltip:'Agregar',
                            iconCls:'agregar',
                            handler:crud_egreso_agregar
                    },'-',
                    {
                            text:'Eliminar',
                            tooltip:'Eliminar',
                            iconCls:'eliminar',
                            handler:crud_egreso_eliminar
                    },'-',
                    {
                            text:'',
                            iconCls:'activos',
                            tooltip:'Egresos activos',
                            handler:function(){
                                    crud_egreso_datastore.baseParams.egr_eliminado = '0';
                                    crud_egreso_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },{
                            text:'',
                            iconCls:'eliminados',
                            tooltip:'Egresos eliminados',
                            handler:function(){
                                    crud_egreso_datastore.baseParams.egr_eliminado = '1';
                                    crud_egreso_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },'-',{
                            text:'Restablecer',
                            iconCls:'restablece',
                            tooltip:'Restablecer un egreso eliminado',
                            handler:function(){
                                     var cant_record = crud_egreso_gridpanel.getSelectionModel().getCount();

                                    if(cant_record > 0){
                                    var record = crud_egreso_gridpanel.getSelectionModel().getSelected();
                                            if (record.get('egr_codigo') != '') {
                                            Ext.Msg.confirm('Restablecer egreso', 
                                            "¿Realmente desea restablecer este egreso?", 
                                            function(btn){
                                                if (btn == 'yes') {
                                                    subirDatosAjax( 
                                                            getAbsoluteUrl('egresos', 'restablecerEgreso'),
                                                    {
                                                        egr_codigo:record.get('egr_codigo')
                                                    }, 
                                                    function(){
                                                            crud_egreso_datastore.reload();
                                                    }, 
                                                    function(){}
                                                    );
                                            }
                                            });
                                         }
                                    }
                                    else {
                                            mostrarMensajeConfirmacion('Error', "Seleccione un egreso eliminado");
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
var crud_egreso_contenedor_panel = new Ext.Panel({
        id: 'crud_egreso_contenedor_panel',
        height: largo_panel,
        autoWidth: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede ver, agregar y eliminar egresos',
        monitorResize:true,
        layout:'border',
        items: 
        [
                crud_egreso_gridpanel,
                crud_egreso_formpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_egreso'
});
		
function crud_egreso_actualizar(btn){
        if(crud_egreso_formpanel.getForm().isValid())
        {
                subirDatos(
                        crud_egreso_formpanel,
                        getAbsoluteUrl('egresos','actualizarEgreso'),
                        {},
                        function(){
                        crud_egreso_formpanel.getForm().reset();
                        crud_egreso_datastore.reload(); 
                        },
                        function(){}
                        );
        }
}
        
function crud_egreso_eliminar()
{
        var cant_record = crud_egreso_gridpanel.getSelectionModel().getCount();

        if(cant_record > 0){
                var record = crud_egreso_gridpanel.getSelectionModel().getSelected();
                if(record.get('egr_codigo')!='')
                {
                        Ext.Msg.confirm('Eliminar egreso', 
                        "¿Realmente desea eliminar este egreso?", 
                        function(btn){
                                if (btn == 'yes') {
                                        subirDatosAjax(
                                                getAbsoluteUrl('egresos','eliminarEgreso'),
                                                {
                                                    egr_codigo:record.get('egr_codigo')
                                                },
                                                function(){
                                                crud_egreso_datastore.reload(); 
                                                }
                                        );
                                }
                        });
                }
        }
        else{
                mostrarMensajeConfirmacion('Error',"Seleccione un egreso a eliminar");
        }
}
	
function crud_egreso_agregar(btn, ev) {    
        crud_egreso_formpanel.getForm().reset();
        Ext.getCmp('crud_egreso_actualizar_boton').setText('Guardar');
        Ext.getCmp('egr_acumulado_ingresos').setDisabled(true);
        Ext.getCmp('egr_acumulado_egresos').setDisabled(true);
        Ext.getCmp('egr_disponible').setDisabled(true);
        Ext.getCmp('egr_valor').setDisabled(false);
}