var ayuda_rep_codigo = '';
var ayuda_rep_numero = 'Escriba el número del repuesto';
var ayuda_rep_nombre = 'Escriba el nombre del repuesto';
var ayuda_rep_cantidad = 'Escriba la cantidad del repuesto';
var ayuda_rep_periodicidad = 'Escriba la periocidad del repuesto';
var ayuda_rep_cat_nombre = 'Seleccione el nombre del grupo del equipo';
var largo_panel = 500;

var crud_repuesto_categoria_datastore = new Ext.data.Store({
    id: 'crud_repuesto_categoria_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('crud_repuesto', 'listarCategoria'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'cat_codigo'
    }, {
        name: 'cat_nombre'
    }])
});
crud_repuesto_categoria_datastore.load();


var crud_repuesto_datastore = new Ext.data.Store({
    id: 'crud_repuesto_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('crud_repuesto', 'listarRepuesto'),
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
        name: 'rep_codigo',
        type: 'int'
    }, {
        name: 'rep_numero',
        type: 'string'
    }, {
        name: 'rep_nombre',
        type: 'string'
    }, {
        name: 'rep_cantidad',
        type: 'int'
    }, {
        name: 'rep_periodicidad',
        type: 'int'
    }, {
        name: 'rep_fecha_registro_sistema',
        type: 'string'
    }, {
        name: 'rep_fecha_actualizacion',
        type: 'string'
    }, {
        name: 'rep_usu_crea_nombre',
        type: 'string'
    }, {
        name: 'rep_usu_actualiza_nombre',
        type: 'string'
    }, {
        name: 'rep_causa_eliminacion',
        type: 'string'
    }, {
        name: 'rep_causa_actualizacion',
        type: 'string'
    }, {
        name: 'rep_eliminado',
        type: 'string'
    }, {
        name: 'rep_cat_codigo',
        type: 'int'
    }])
});

crud_repuesto_datastore.load();

var rep_eliminado = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    maxLength: 20,
    name: 'rep_eliminado',
    id: 'rep_eliminado',
    hideLabel: true,
    hidden: true
});

var rep_codigo = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    maxLength: 20,
    name: 'rep_codigo',
    id: 'rep_codigo',
    hideLabel: true,
    hidden: true,
    listeners: {
        'render': function(){
            ayuda('rep_codigo', ayuda_rep_codigo);
        }
    }
});

var rep_numero= new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    maxLength: 100,
    name: 'rep_numero',
    id: 'rep_numero',
    fieldLabel: 'Número parte',
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('rep_numero', ayuda_rep_numero);
        }
    }
});
    
var rep_nombre = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    maxLength: 100,
    name: 'rep_nombre',
    id: 'rep_nombre',
    fieldLabel: 'Nombre parte',
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('rep_nombre', ayuda_rep_nombre);
        }
    }
});

var rep_cat_codigo = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'rep_cat_nombre',
    hiddenName: 'rep_cat_codigo',
    name: 'rep_cat_codigo',
    fieldLabel: 'Grupo equipo',
    store: crud_repuesto_categoria_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'cat_nombre',
    valueField: 'cat_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('rep_cat_nombre', ayuda_rep_cat_nombre);
        },
        focus: function(){
            crud_repuesto_categoria_datastore.reload();
        }
    }
});

var rep_cantidad = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'rep_cantidad',
    id: 'rep_cantidad',
    fieldLabel: 'Cantidad (existencia)',
    allowDecimals: false,
    allowNegative: false,
    allowBlank: false,
    maxLength: 100,
    listeners: {
        'render': function(){
            ayuda('rep_cantidad', ayuda_rep_cantidad);
        }
    }
});

var rep_periodicidad = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'rep_periodicidad',
    id: 'rep_periodicidad',
    fieldLabel: 'Periocidad de Cambio (meses)',
    allowDecimals: false,
    allowNegative: false,
    allowBlank: false,
    maxLength: 100,
    listeners: {
        'render': function(){
            ayuda('rep_periodicidad', ayuda_rep_periodicidad);
        }
    }
});

var rep_fecha_registro_sistema = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    name: 'rep_fecha_registro_sistema',
    id: 'rep_fecha_registro_sistema',
    fieldLabel: 'Fecha registro',
    maxLength: 100,
    readOnly: true
});

var crud_repuesto_formpanel = new Ext.FormPanel({
    id: 'crud_repuesto_formpanel',
    frame: true,
    region: 'east',
    split: true,
    collapsible: true,
    width: 400,
    border: true,
    title: 'Repuesto detalle',
    //autoWidth: true,
    columnWidth: '0.6',
    height: 470,
    layout: 'form',
    bodyStyle: 'padding:10px;',
    labelWidth: 170,
    defaults: {
        anchor: '98%'
    },
    items: [rep_eliminado, rep_codigo, rep_numero, rep_nombre, rep_cantidad, rep_periodicidad, rep_cat_codigo, rep_fecha_registro_sistema],
    buttons: [{
        text: 'Guardar',
        iconCls: 'guardar',
        id: 'crud_repuesto_actualizar_boton',
        handler: function(formulario, accion){
        
            if (Ext.getCmp('crud_repuesto_actualizar_boton').getText() == 'Actualizar') {
                if (rep_eliminado.getValue() == 0) {
                    Ext.Msg.prompt('Repuesto', 'Digite la causa de la actualizaci&oacute;n de este repuesto', function(btn, text, op){
                        if (btn == 'ok') {
                            crud_repuesto_actualizar(text);
                        }
                    });
                }
                else {
                    crud_repuesto_actualizar('');
                }
            }
            else {
                crud_repuesto_actualizar('');
            }
        }
    }]
});

function repuestoRenderComboColumn2(value, meta, record){
    return ComboRenderer(value, rep_cat_codigo);
}

var crud_repuesto_colmodel = new Ext.grid.ColumnModel({
    defaults: {
        sortable: true,
        locked: false,
        resizable: true
    },
    columns: [{
        id: 'rep_codigo',
        header: "Id",
        width: 30,
        dataIndex: 'rep_codigo'
    }, {
        header: "Nombre",
        width: 100,
        dataIndex: 'rep_nombre'
    }, {
        header: "Número",
        width: 100,
        dataIndex: 'rep_numero'
    }, {
        header: "Cantidad",
        width: 100,
        dataIndex: 'rep_cantidad'
    }, {
        header: "Frecuencia",
        width: 100,
        dataIndex: 'rep_periodicidad'
    }, {
        header: "Categoría",
        width: 100,
        dataIndex: 'rep_cat_codigo',
        renderer: repuestoRenderComboColumn2
    }, {
        header: "Creado por",
        width: 120,
        dataIndex: 'rep_usu_crea_nombre'
    }, {
        header: "Fecha de creaci&oacute;n",
        width: 120,
        dataIndex: 'rep_fecha_registro_sistema'
    }, {
        header: "Actualizado por",
        width: 120,
        dataIndex: 'rep_usu_actualiza_nombre'
    }, {
        header: "Fecha de actualizaci&oacute;n",
        width: 120,
        dataIndex: 'rep_fecha_actualizacion'
    }, {
        header: "Causa actualizaci&oacute;n",
        width: 120,
        dataIndex: 'rep_causa_actualizacion'
    }, {
        header: "Causa eliminaci&oacute;n",
        width: 120,
        dataIndex: 'rep_causa_eliminacion'
    }]
});

var crud_repuesto_gridpanel = new Ext.grid.GridPanel({
    id: 'crud_repuesto_gridpanel',
    title: 'Repuestos en el sistema',
    columnWidth: '.4',
    region: 'center',
    stripeRows: true,
    frame: true,
    ds: crud_repuesto_datastore,
    cm: crud_repuesto_colmodel,
    selModel: new Ext.grid.RowSelectionModel({
        singleSelect: true,
        listeners: {
            rowselect: function(sm, row, record){
                Ext.getCmp('crud_repuesto_formpanel').getForm().loadRecord(record);
                Ext.getCmp('crud_repuesto_actualizar_boton').setText('Actualizar');
            }
        }
    }),
    //autoExpandColumn: 'rep_nombre',
    //autoExpandMin: 100,
    height: largo_panel,
    bbar: new Ext.PagingToolbar({
        pageSize: 15,
        store: crud_repuesto_datastore,
        displayInfo: true,
        displayMsg: 'Repuestos {0} - {1} de {2}',
        emptyMsg: "No hay repuestos aun"
    }),
    tbar: [{
        id: 'crud_repuesto_agregar_boton',
        text: 'Agregar',
        tooltip: 'Agregar',
        iconCls: 'agregar',
        handler: crud_repuesto_agregar
    }, '-', {
        text: 'Eliminar',
        tooltip: 'Eliminar',
        iconCls: 'eliminar',
        handler: crud_repuesto_eliminar
    }, '-', {
        text: '',
        iconCls: 'activos',
        tooltip: 'Repuestos activos',
        handler: function(){
            crud_repuesto_datastore.baseParams.rep_eliminado = '0';
            crud_repuesto_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, {
        text: '',
        iconCls: 'eliminados',
        tooltip: 'Repuestos eliminados',
        handler: function(){
            crud_repuesto_datastore.baseParams.rep_eliminado = '1';
            crud_repuesto_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, '-', {
        text: 'Restablecer',
        iconCls: 'restablece',
        tooltip: 'Restablecer un repuesto eliminado',
        handler: function(){
            var cant_record = crud_repuesto_gridpanel.getSelectionModel().getCount();
            
            if (cant_record > 0) {
                var record = crud_repuesto_gridpanel.getSelectionModel().getSelected();
                if (record.get('rep_codigo') != '') {
                
                    Ext.Msg.prompt('Restablecer repuesto', 'Digite la causa de restablecimiento', function(btn, text){
                        if (btn == 'ok') {
                            subirDatosAjax(getAbsoluteUrl('crud_repuesto', 'restablecerRepuesto'), {
                                rep_codigo: record.get('rep_codigo'),
                                rep_causa_restablece: text
                            }, function(){
                                crud_repuesto_datastore.reload();
                            }, function(){
                            });
                        }
                    });
                }
            }
            else {
                mostrarMensajeConfirmacion('Error', "Seleccione un repuesto eliminado");
            }
        }
    }    /*{
     text: 'Consumibles',
     tooltip: 'Consumibles de repuesto',
     iconCls: 'consumibles'//,
     //handler:crud_repuesto_mantenimiento
     }, '-'*/
    ]
});


/*INTEGRACION AL CONTENEDOR*/
var crud_repuesto_contenedor_panel = new Ext.Panel({
    id: 'crud_repuesto_contenedor_panel',
    height: largo_panel,
    autoWidth: true,
    //width:1000,
    border: false,
    tabTip: 'Aqui puedes ver, agregar, eliminar repuestos',
    monitorResize: true,
    layout: 'border',
    items: [crud_repuesto_gridpanel, crud_repuesto_formpanel],
    buttonAlign: 'left',
    renderTo: 'div_form_crud_repuesto'
});

function crud_repuesto_actualizar(text){

    if (crud_repuesto_formpanel.getForm().isValid()) {
        subirDatos(crud_repuesto_formpanel, getAbsoluteUrl('crud_repuesto', 'actualizarRepuesto'), {
            rep_causa_actualizacion: text
        }, function(){
            crud_repuesto_formpanel.getForm().reset();
            crud_repuesto_datastore.reload();
        }, function(){
        });
    }
}

function crud_repuesto_eliminar(){
    var cant_record = crud_repuesto_gridpanel.getSelectionModel().getCount();
    
    if (cant_record > 0) {
        var record = crud_repuesto_gridpanel.getSelectionModel().getSelected();
        if (record.get('rep_codigo') != '') {
        
            Ext.Msg.confirm('Eliminar repuesto', "Realmente desea eliminar esta repuesto?", function(btn){
                if (btn == 'yes') {
                
                    Ext.Msg.prompt('Eliminar repuesto', 'Digite la causa de la eliminaci&oacute;n de este repuesto', function(btn2, text){
                        if (btn2 == 'ok') {
                            subirDatosAjax(getAbsoluteUrl('crud_repuesto', 'eliminarRepuesto'), {
                                rep_codigo: record.get('rep_codigo'),
                                rep_causa_eliminacion: text
                            }, function(){
                                crud_repuesto_datastore.reload();
                            });
                        }
                    });
                }
            });
        }
    }
    else {
        mostrarMensajeConfirmacion('Error', "Seleccione un repuesto a eliminar");
    }
}

function crud_repuesto_agregar(btn, ev){
    crud_repuesto_formpanel.getForm().reset();
    Ext.getCmp('crud_repuesto_actualizar_boton').setText('Guardar');    
}