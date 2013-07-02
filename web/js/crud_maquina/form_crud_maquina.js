
/*
 gestion maquinas - tpm labs
 Desarrollado maryit sanchez
 2010
 */
var ayuda_maq_codigo = '';
var ayuda_maq_codigo_inventario = 'Escriba el código de inventario';
var ayuda_maq_nombre = 'Escriba el nombre del equipo';
var ayuda_maq_est_nombre = 'Seleccione el estado del equipo';
var ayuda_maq_marca = 'Escriba la marca del equipo';
var ayuda_maq_modelo = 'Escriba el modelo del equipo';
var ayuda_maq_tiempo_inyeccion = 'Escriba cual es el tiempo estandar de inyección del equipo';
var ayuda_maq_fecha_adquisicion = 'Escoja la fecha en que adquirió el equipo';
//var ayuda_maq_tiempo_inyeccion_actual = 'Escriba cual es el tiempo de inyección actual del equipo'; 

var largo_panel = 500;


var crud_maquina_estado_datastore = new Ext.data.Store({
    id: 'crud_maquina_estado_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('crud_maquina', 'listarEstado'),
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

var crud_maquina_datastore = new Ext.data.Store({
    id: 'crud_maquina_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('crud_maquina', 'listarMaquina'),
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
        name: 'maq_codigo',
        type: 'int'
    }, {
        name: 'maq_codigo_inventario',
        type: 'string'
    }, {
        name: 'maq_nombre',
        type: 'string'
    }, {
        name: 'maq_est_codigo',
        type: 'int'
    }, {
        name: 'maq_marca',
        type: 'string'
    }, {
        name: 'maq_modelo',
        type: 'string'
    }, {
        name: 'maq_tiempo_inyeccion',
        type: 'float'
    },    /*{
     name: 'maq_tiempo_inyeccion_actual',
     type: 'float'
     },*/
    {
        name: 'maq_fecha_adquisicion',
        type: 'string'
    }, {
        name: 'certificado',
        type: 'string'
    }, {
        name: 'maq_fecha_registro_sistema',
        type: 'string'
    }, {
        name: 'maq_fecha_actualizacion',
        type: 'string'
    }, {
        name: 'maq_usu_crea_nombre',
        type: 'string'
    }, {
        name: 'maq_usu_actualiza_nombre',
        type: 'string'
    }, {
        name: 'maq_causa_eliminacion',
        type: 'string'
    }, {
        name: 'maq_causa_actualizacion',
        type: 'string'
    }, {
        name: 'maq_eliminado',
        type: 'string'
    }])
});

var maq_eliminado = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    maxLength: 20,
    name: 'maq_eliminado',
    id: 'maq_eliminado',
    hideLabel: true,
    hidden: true
});

var maq_codigo = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    maxLength: 20,
    name: 'maq_codigo',
    id: 'maq_codigo',
    hideLabel: true,
    hidden: true,
    listeners: {
        'render': function(){
            ayuda('maq_codigo', ayuda_maq_codigo);
        }
    }
});

var maq_codigo_inventario = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    name: 'maq_codigo_inventario',
    id: 'maq_codigo_inventario',
    fieldLabel: '<html>C&oacute;digo inventario</html>',
    allowBlank: false,
    maxLength: 100,
    listeners: {
        'render': function(){
            ayuda('maq_codigo_inventario', ayuda_maq_codigo_inventario);
        }
    }
});


var maq_nombre = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    maxLength: 100,
    name: 'maq_nombre',
    id: 'maq_nombre',
    fieldLabel: 'Nombre del equipo',
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('maq_nombre', ayuda_maq_nombre);
        }
    }
});

var maq_est_codigo = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'maq_est_nombre',
    hiddenName: 'maq_est_codigo',
    name: 'maq_est_codigo',
    fieldLabel: 'Estado',
    store: crud_maquina_estado_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'est_nombre',
    valueField: 'est_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('maq_est_nombre', ayuda_maq_est_nombre);
        },
        focus: function(){
            crud_maquina_estado_datastore.reload();
        }
    }
});


var maq_marca = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    name: 'maq_marca',
    id: 'maq_marca',
    fieldLabel: '<html>Marca</html>',
    allowBlank: false,
    maxLength: 100,
    listeners: {
        'render': function(){
            ayuda('maq_marca', ayuda_maq_marca);
        }
    }
});


var maq_modelo = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    name: 'maq_modelo',
    id: 'maq_modelo',
    fieldLabel: '<html>Modelo</html>',
    allowBlank: false,
    maxLength: 100,
    listeners: {
        'render': function(){
            ayuda('maq_modelo', ayuda_maq_modelo);
        }
    }
});


var maq_tiempo_inyeccion = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'maq_tiempo_inyeccion',
    id: 'maq_tiempo_inyeccion',
    fieldLabel: 'Tiempo inyección',
    allowDecimals: true,
    allowNegative: false,
    maxLength: 100,
    listeners: {
        'render': function(){
            ayuda('maq_tiempo_inyeccion', ayuda_maq_tiempo_inyeccion);
        }
    }
});
/*agregado el 16 y eliminado el 18 de febrero sugerencias de karl
 var maq_tiempo_inyeccion_actual = new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: ' text-align:right;',
 name: 'maq_tiempo_inyeccion_actual',
 id: 'maq_tiempo_inyeccion_actual',
 fieldLabel: 'Tiempo inyección actual',
 allowDecimals: true,
 allowNegative: false,
 maxLength: 100,
 listeners: {
 'render': function(){
 ayuda('maq_tiempo_inyeccion_actual', ayuda_maq_tiempo_inyeccion_actual);
 }
 }
 });*/
var maq_fecha_adquisicion = new Ext.form.DateField({
    xtype: 'datefield',
    labelStyle: 'text-align:right;',
    name: 'maq_fecha_adquisicion',
    id: 'maq_fecha_adquisicion',
    format: 'Y-m-d',
    fieldLabel: 'Fecha adquisici&oacute;n',
    allowBlank: false,
    maxLength: 100,
    listeners: {
        'render': function(){
            ayuda('maq_fecha_adquisicion', ayuda_maq_fecha_adquisicion);
        }
    }
});

var maq_fecha_registro_sistema = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    name: 'maq_fecha_registro_sistema',
    id: 'maq_fecha_registro_sistema',
    fieldLabel: 'Fecha registro',
    maxLength: 100,
    readOnly: true
});

var certificados_datastore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('crud_maquina', 'listarComputadores'),
        method: 'POST'
    }),
    reader: new Ext.data.JsonReader({
        root: 'data',
    }, [{
        name: 'certificado',
        type: 'string'
    }, {
        name: 'nombre',
        type: 'string'
    }])
});

certificados_datastore.load({
    callback: function(){
        crud_maquina_estado_datastore.load({
            callback: function(){
                crud_maquina_datastore.load();
            }
        });
    }
});

var certificado_combo = new Ext.form.ComboBox({
    hiddenName: 'certificado',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Computador',
    store: certificados_datastore,
    displayField: 'nombre',
    valueField: 'certificado',
    mode: 'local',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false
});

var crud_maquina_formpanel = new Ext.FormPanel({
    id: 'crud_maquina_formpanel',
    frame: true,
    region: 'east',
    split: true,
    collapsible: true,
    width: 450,
    border: true,
    title: 'Equipo detalle',
    //autoWidth: true,
    columnWidth: '0.6',
    height: 450,
    layout: 'form',
    bodyStyle: 'padding:10px;',
    defaults: {
        anchor: '98%'
    },
    items: [maq_eliminado, maq_codigo, maq_codigo_inventario, maq_nombre, maq_est_codigo, maq_tiempo_inyeccion, /*maq_tiempo_inyeccion_actual,*/ maq_marca, maq_modelo, maq_fecha_adquisicion, certificado_combo, maq_fecha_registro_sistema],
    buttons: [{
        text: 'Guardar',
        iconCls: 'guardar',
        id: 'crud_maquina_actualizar_boton',
        handler: function(formulario, accion){
        
            if (Ext.getCmp('crud_maquina_actualizar_boton').getText() == 'Actualizar') {
                if (maq_eliminado.getValue() == 0) {
                    Ext.Msg.prompt('Equipo', 'Digite la causa de la actualizaci&oacute;n de este equipo', function(btn, text, op){
                        if (btn == 'ok') {
                            crud_maquina_actualizar(text);
                        }
                    });
                }
                else {
                    crud_maquina_actualizar('');
                }
            }
            else {
                crud_maquina_actualizar('');
            }
        }
    }]
});

function maquinaRenderComboColumn(value, meta, record){
    return ComboRenderer(value, maq_est_codigo);
}

var crud_maquina_colmodel = new Ext.grid.ColumnModel({
    defaults: {
        sortable: true,
        locked: false,
        resizable: true
    },
    columns: [{
        id: 'maq_codigo',
        header: "Id",
        width: 30,
        dataIndex: 'maq_codigo'
    }, {
        header: "Nombre",
        width: 100,
        dataIndex: 'maq_nombre'
    }, {
        header: "Código inventario",
        width: 100,
        dataIndex: 'maq_codigo_inventario'
    }, {
        header: "Estado",
        width: 100,
        dataIndex: 'maq_est_codigo',
        renderer: maquinaRenderComboColumn
    }, {
        header: "Marca",
        width: 100,
        dataIndex: 'maq_marca'
    }, {
        header: "Modelo",
        width: 100,
        dataIndex: 'maq_modelo'
    }, {
        header: "Computador",
        width: 100,
        dataIndex: 'certificado',
        renderer: function(value){
            var index = certificados_datastore.find('certificado', value);
            if (index != -1) {
                var record = certificados_datastore.getAt(index);
                return record.get('nombre');
            }
            else {
                return '';
            }
        }
    }, {
        header: "Creado por",
        width: 120,
        dataIndex: 'maq_usu_crea_nombre'
    }, {
        header: "Fecha de creaci&oacute;n",
        width: 120,
        dataIndex: 'maq_fecha_registro_sistema'
    }, {
        header: "Actualizado por",
        width: 120,
        dataIndex: 'maq_usu_actualiza_nombre'
    }, {
        header: "Fecha de actualizaci&oacute;n",
        width: 120,
        dataIndex: 'maq_fecha_actualizacion'
    }, {
        header: "Causa actualizaci&oacute;n",
        width: 120,
        dataIndex: 'maq_causa_actualizacion'
    }, {
        header: "Causa eliminaci&oacute;n",
        width: 120,
        dataIndex: 'maq_causa_eliminacion'
    }]
});

var crud_maquina_gridpanel = new Ext.grid.GridPanel({
    id: 'crud_maquina_gridpanel',
    title: 'Equipos en el sistema',
    columnWidth: '.4',
    region: 'center',
    stripeRows: true,
    frame: true,
    ds: crud_maquina_datastore,
    cm: crud_maquina_colmodel,
    selModel: new Ext.grid.RowSelectionModel({
        singleSelect: true,
        listeners: {
            rowselect: function(sm, row, record){
                Ext.getCmp('crud_maquina_formpanel').getForm().loadRecord(record);
                Ext.getCmp('crud_maquina_actualizar_boton').setText('Actualizar');
            }
        }
    }),
    //autoExpandColumn: 'maq_nombre',
    //autoExpandMin: 100,
    height: largo_panel,
    bbar: new Ext.PagingToolbar({
        pageSize: 15,
        store: crud_maquina_datastore,
        displayInfo: true,
        displayMsg: 'Equipos {0} - {1} de {2}',
        emptyMsg: "No hay equipos aun"
    }),
    tbar: [{
        id: 'crud_maquina_agregar_boton',
        text: 'Agregar',
        tooltip: 'Agregar',
        iconCls: 'agregar',
        handler: crud_maquina_agregar
    }, '-', {
        text: 'Eliminar',
        tooltip: 'Eliminar',
        iconCls: 'eliminar',
        handler: crud_maquina_eliminar
    }, '-', {
        text: '',
        iconCls: 'activos',
        tooltip: 'Equipos activos',
        handler: function(){
            crud_maquina_datastore.baseParams.maq_eliminado = '0';
            crud_maquina_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, {
        text: '',
        iconCls: 'eliminados',
        tooltip: 'Equipos eliminados',
        handler: function(){
            crud_maquina_datastore.baseParams.maq_eliminado = '1';
            crud_maquina_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, '-', {
        text: 'Restablecer',
        iconCls: 'restablece',
        tooltip: 'Restablecer un equipo eliminado',
        handler: function(){
            var cant_record = crud_maquina_gridpanel.getSelectionModel().getCount();
            
            if (cant_record > 0) {
                var record = crud_maquina_gridpanel.getSelectionModel().getSelected();
                if (record.get('maq_codigo') != '') {
                
                    Ext.Msg.prompt('Restablecer equipo', 'Digite la causa de restablecimiento', function(btn, text){
                        if (btn == 'ok') {
                            subirDatosAjax(getAbsoluteUrl('crud_maquina', 'restablecerMaquina'), {
                                maq_codigo: record.get('maq_codigo'),
                                maq_causa_restablece: text
                            }, function(){
                                crud_maquina_datastore.reload();
                            }, function(){
                            });
                        }
                    });
                }
            }
            else {
                mostrarMensajeConfirmacion('Error', "Seleccione un equipo eliminado");
            }
        }
    }    /*{
     text: 'Consumibles',
     tooltip: 'Consumibles de maquina',
     iconCls: 'consumibles'//,
     //handler:crud_maquina_mantenimiento
     }, '-'*/
    ]
});


/*INTEGRACION AL CONTENEDOR*/
var crud_maquina_contenedor_panel = new Ext.Panel({
    id: 'crud_maquina_contenedor_panel',
    height: largo_panel,
    autoWidth: true,
    //width:1000,
    border: false,
    tabTip: 'Aqui puedes ver, agregar, eliminar equipos',
    monitorResize: true,
    layout: 'border',
    items: [crud_maquina_gridpanel, crud_maquina_formpanel],
    buttonAlign: 'left',
    renderTo: 'div_form_crud_maquina'
});

function crud_maquina_actualizar(text){

    if (crud_maquina_formpanel.getForm().isValid()) {
        subirDatos(crud_maquina_formpanel, getAbsoluteUrl('crud_maquina', 'actualizarMaquina'), {
            maq_causa_actualizacion: text
        }, function(){
            crud_maquina_formpanel.getForm().reset();
            crud_maquina_datastore.reload();
        }, function(){
        });
    }
}

function crud_maquina_eliminar(){
    var cant_record = crud_maquina_gridpanel.getSelectionModel().getCount();
    
    if (cant_record > 0) {
        var record = crud_maquina_gridpanel.getSelectionModel().getSelected();
        if (record.get('maq_codigo') != '') {
        
            Ext.Msg.confirm('Eliminar equipo', "Realmente desea eliminar esta equipo?", function(btn){
                if (btn == 'yes') {
                
                    Ext.Msg.prompt('Eliminar equipo', 'Digite la causa de la eliminaci&oacute;n de este equipo', function(btn2, text){
                        if (btn2 == 'ok') {
                            subirDatosAjax(getAbsoluteUrl('crud_maquina', 'eliminarMaquina'), {
                                maq_codigo: record.get('maq_codigo'),
                                maq_causa_eliminacion: text
                            }, function(){
                                crud_maquina_datastore.reload();
                            });
                        }
                    });
                }
            });
        }
    }
    else {
        mostrarMensajeConfirmacion('Error', "Seleccione un equipo a eliminar");
    }
}

function crud_maquina_agregar(btn, ev){
    crud_maquina_formpanel.getForm().reset();
    Ext.getCmp('crud_maquina_actualizar_boton').setText('Guardar');
    
}


