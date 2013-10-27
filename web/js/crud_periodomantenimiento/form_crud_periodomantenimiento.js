var ayuda_pmto_codigo = '';
var ayuda_pmto_periodo = 'Escriba el número del periodo';
var ayuda_tp_nombre = 'Escriba el nombre del periodo';
var largo_panel = 500;

var crud_periodomantenimiento_datastore = new Ext.data.Store({
    id: 'crud_periodomantenimiento_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('crud_periodomantenimiento', 'listarPeriodoMantenimiento'),
        method: 'POST'
    }),
    baseParams: {
        start: 0,
        limit: 20
    },
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'pmto_codigo',
        type: 'int'
    }, {
        name: 'pmto_periodo',
        type: 'string'
    }, {
        name: 'pmto_fecha_registro_sistema',
        type: 'string'
    }, {
        name: 'pmto_fecha_actualizacion',
        type: 'string'
    }, {
        name: 'pmto_usu_crea_nombre',
        type: 'string'
    }, {
        name: 'pmto_usu_actualiza_nombre',
        type: 'string'
    }, {
        name: 'pmto_causa_eliminacion',
        type: 'string'
    }, {
        name: 'pmto_causa_actualizacion',
        type: 'string'
    }, {
        name: 'pmto_eliminado',
        type: 'string'
    }, {
        name: 'pmto_tipo',
        type: 'int'
    }])
});
crud_periodomantenimiento_datastore.load();

var crud_tipo_periodo_datastore = new Ext.data.Store({
    id: 'crud_tipo_periodo_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('crud_periodomantenimiento', 'listarTipoPeriodo'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'tp_codigo'
    }, {
        name: 'tp_nombre'
    }])
});
crud_tipo_periodo_datastore.load();

var pmto_periodo = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    maxLength: 100,
    name: 'pmto_periodo',
    id: 'pmto_periodo',
    fieldLabel: 'Periodo',
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('pmto_periodo', ayuda_pmto_periodo);
        }
    }
});

var pmto_codigo = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    maxLength: 20,
    name: 'pmto_codigo',
    id: 'pmto_codigo',
    hideLabel: true,
    hidden: true,
    listeners: {
        'render': function(){
            ayuda('pmto_codigo', ayuda_pmto_codigo);
        }
    }
});

var pmto_eliminado = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    maxLength: 20,
    name: 'pmto_eliminado',
    id: 'pmto_eliminado',
    hideLabel: true,
    hidden: true
});
    
var pmto_tipo = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'pmto_nombre',
    hiddenName: 'pmto_tipo',
    name: 'pmto_tipo',
    fieldLabel: 'Tipo',
    store: crud_tipo_periodo_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'tp_nombre',
    valueField: 'tp_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('pmto_nombre', ayuda_tp_nombre);
        },
        focus: function(){
            crud_tipo_periodo_datastore.reload();
        }
    }
});


var pmto_fecha_registro_sistema = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    name: 'pmto_fecha_registro_sistema',
    id: 'pmto_fecha_registro_sistema',
    fieldLabel: 'Fecha registro',
    maxLength: 100,
    readOnly: true
});

var crud_periodomantenimiento_formpanel = new Ext.FormPanel({
    id: 'crud_periodomantenimiento_formpanel',
    frame: true,
    region: 'east',
    split: true,
    collapsible: true,
    width: 350,
    border: true,
    title: 'Periodo Mantenimiento detalle',
    //autoWidth: true,
    columnWidth: '0.6',
    height: 400,
    layout: 'form',
    bodyStyle: 'padding:10px;',
    labelWidth: 120,
    defaults: {
        anchor: '98%'
    },
    items: [pmto_eliminado, pmto_codigo, pmto_periodo, pmto_tipo, pmto_fecha_registro_sistema],
    buttons: [{
        text: 'Guardar',
        iconCls: 'guardar',
        id: 'crud_periodomantenimiento_actualizar_boton',
        handler: function(formulario, accion){
        
            if (Ext.getCmp('crud_periodomantenimiento_actualizar_boton').getText() == 'Actualizar') {
                if (pmto_eliminado.getValue() == 0) {
                    Ext.Msg.prompt('Periodo', 'Digite la causa de la actualizaci&oacute;n de este periodo', function(btn, text, op){
                        if (btn == 'ok') {
                            crud_periodomantenimiento_actualizar(text);
                        }
                    });
                }
                else {
                    crud_periodomantenimiento_actualizar('');
                }
            }
            else {
                crud_periodomantenimiento_actualizar('');
            }
        }
    }]
});

function periodoRenderComboColumn(value, meta, record){
    return ComboRenderer(value, pmto_tipo);
}

var crud_periodomantenimiento_colmodel = new Ext.grid.ColumnModel({
    defaults: {
        sortable: true,
        locked: false,
        resizable: true
    },
    columns: [{
        id: 'pmto_codigo',
        header: "Id",
        width: 30,
        dataIndex: 'pmto_codigo'
    }, {
        header: "Periodo",
        width: 100,
        dataIndex: 'pmto_periodo'
    }, {
        header: "Tipo",
        width: 100,
        dataIndex: 'pmto_tipo',
        renderer: periodoRenderComboColumn        
    }, {
        header: "Creado por",
        width: 120,
        dataIndex: 'pmto_usu_crea_nombre'
    }, {
        header: "Fecha de creaci&oacute;n",
        width: 120,
        dataIndex: 'pmto_fecha_registro_sistema'
    }, {
        header: "Actualizado por",
        width: 120,
        dataIndex: 'pmto_usu_actualiza_nombre'
    }, {
        header: "Fecha de actualizaci&oacute;n",
        width: 120,
        dataIndex: 'pmto_fecha_actualizacion'
    }, {
        header: "Causa actualizaci&oacute;n",
        width: 120,
        dataIndex: 'pmto_causa_actualizacion'
    }, {
        header: "Causa eliminaci&oacute;n",
        width: 120,
        dataIndex: 'pmto_causa_eliminacion'
    }]
});

var crud_periodomantenimiento_gridpanel = new Ext.grid.GridPanel({
    id: 'crud_periodomantenimiento_gridpanel',
    title: 'Periodos en el sistema',
    columnWidth: '.4',
    region: 'center',
    stripeRows: true,
    frame: true,
    ds: crud_periodomantenimiento_datastore,
    cm: crud_periodomantenimiento_colmodel,
    selModel: new Ext.grid.RowSelectionModel({
        singleSelect: true,
        listeners: {
            rowselect: function(sm, row, record){
                Ext.getCmp('crud_periodomantenimiento_formpanel').getForm().loadRecord(record);
                Ext.getCmp('crud_periodomantenimiento_actualizar_boton').setText('Actualizar');
            }
        }
    }),
    //autoExpandColumn: 'tp_codigo',
    //autoExpandMin: 100,
    height: largo_panel,
    bbar: new Ext.PagingToolbar({
        pageSize: 15,
        store: crud_periodomantenimiento_datastore,
        displayInfo: true,
        displayMsg: 'Periodos {0} - {1} de {2}',
        emptyMsg: "No hay periodos de mantenimiento aun"
    }),
    tbar: [{
        id: 'crud_periodomantenimiento_agregar_boton',
        text: 'Agregar',
        tooltip: 'Agregar',
        iconCls: 'agregar',
        handler: crud_periodomantenimiento_agregar
    }, '-', {
        text: 'Eliminar',
        tooltip: 'Eliminar',
        iconCls: 'eliminar',
        handler: crud_periodomantenimiento_eliminar
    }, '-', {
        text: '',
        iconCls: 'activos',
        tooltip: 'Periodos activos',
        handler: function(){
            crud_periodomantenimiento_datastore.baseParams.pmto_eliminado = '0';
            crud_periodomantenimiento_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, {
        text: '',
        iconCls: 'eliminados',
        tooltip: 'Periodos eliminados',
        handler: function(){
            crud_periodomantenimiento_datastore.baseParams.pmto_eliminado = '1';
            crud_periodomantenimiento_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, '-', {
        text: 'Restablecer',
        iconCls: 'restablece',
        tooltip: 'Restablecer un periodo eliminado',
        handler: function(){
            var cant_record = crud_periodomantenimiento_gridpanel.getSelectionModel().getCount();
            
            if (cant_record > 0) {
                var record = crud_periodomantenimiento_gridpanel.getSelectionModel().getSelected();
                if (record.get('pmto_codigo') != '') {
                
                    Ext.Msg.prompt('Restablecer periodo', 'Digite la causa de restablecimiento', function(btn, text){
                        if (btn == 'ok') {
                            subirDatosAjax(getAbsoluteUrl('crud_periodomantenimiento', 'restablecerPeriodoMantenimiento'), {
                                pmto_codigo: record.get('pmto_codigo'),
                                pmto_causa_restablece: text
                            }, function(){
                                crud_periodomantenimiento_datastore.reload();
                            }, function(){
                            });
                        }
                    });
                }
            }
            else {
                mostrarMensajeConfirmacion('Error', "Seleccione un periodo eliminado");
            }
        }
    }
    ]
});


/*INTEGRACION AL CONTENEDOR*/
var crud_periodomantenimiento_contenedor_panel = new Ext.Panel({
    id: 'crud_periodomantenimiento_contenedor_panel',
    height: largo_panel,
    autoWidth: true,
    //width:1000,
    border: false,
    tabTip: 'Aqui puedes ver, agregar, eliminar periodos de mantenimientos',
    monitorResize: true,
    layout: 'border',
    items: [crud_periodomantenimiento_gridpanel, crud_periodomantenimiento_formpanel],
    buttonAlign: 'left',
    renderTo: 'div_form_crud_periodomantenimiento'
});

function crud_periodomantenimiento_actualizar(text){

    if (crud_periodomantenimiento_formpanel.getForm().isValid()) {
        subirDatos(crud_periodomantenimiento_formpanel, getAbsoluteUrl('crud_periodomantenimiento', 'actualizarPeriodoMantenimiento'), {
            pmto_causa_actualizacion: text
        }, function(){
            crud_periodomantenimiento_formpanel.getForm().reset();
            crud_periodomantenimiento_datastore.reload();
        }, function(){
        });
    }
}

function crud_periodomantenimiento_eliminar(){
    var cant_record = crud_periodomantenimiento_gridpanel.getSelectionModel().getCount();
    
    if (cant_record > 0) {
        var record = crud_periodomantenimiento_gridpanel.getSelectionModel().getSelected();
        if (record.get('pmto_codigo') != '') {
        
            Ext.Msg.confirm('Eliminar periodo', "Realmente desea eliminar este periodo?", function(btn){
                if (btn == 'yes') {
                
                    Ext.Msg.prompt('Eliminar periodo', 'Digite la causa de la eliminaci&oacute;n de este periodo', function(btn2, text){
                        if (btn2 == 'ok') {
                            subirDatosAjax(getAbsoluteUrl('crud_periodomantenimiento', 'eliminarPeriodoMantenimiento'), {
                                pmto_codigo: record.get('pmto_codigo'),
                                pmto_causa_eliminacion: text
                            }, function(){
                                crud_periodomantenimiento_datastore.reload();
                            });
                        }
                    });
                }
            });
        }
    }
    else {
        mostrarMensajeConfirmacion('Error', "Seleccione un periodo a eliminar");
    }
}

function crud_periodomantenimiento_agregar(btn, ev){
    crud_periodomantenimiento_formpanel.getForm().reset();
    Ext.getCmp('crud_periodomantenimiento_actualizar_boton').setText('Guardar');    
}