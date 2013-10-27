Ext.onReady(function(){
    Ext.BLANK_IMAGE_URL = urlPrefix + '../css/extjs/resources/images/default/s.gif';

var fechaActual = new Date();
var anoActual = fechaActual.getYear();
    
fields = [
  {
    type : 'int',
    name : 'id_registro_rep_maquina'
  }, {
    type : 'string',
    name : 'nombre_equipo'
  }, {
    type : 'string',
    name : 'nombre_parte'
  }, {
    type : 'string',
    name : 'numero_parte'
  }, {
    type : 'string',
    name : 'rrm_fecha_cambio'
  }, {
    type : 'string',
    name : 'rrm_fecha_prox_cambio'
  }, {
    type : 'string',
    name : 'rrm_observaciones'
  }, {
    type : 'string',
    name : 'rrm_usu_registra'
  }, {
    type : 'string',
    name : 'rrm_fecha_registro'
  }, {
    type : 'string',
    name : 'rrm_usu_actualiza'
  }, {
    type : 'string',
    name : 'rrm_fecha_actualizacion'
  }, {
    type : 'int',
    name : 'rrm_consumo'
  }, {
    type : 'int',
    name : 'item_codigo'
  }, {
    type : 'string',
    name : 'item_numero'
  }, {
    type : 'string',
    name : 'item_nombre'
  }];
   
    var datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('mantenimientos_realizados', 'listarRegistrosRepuestoMaquina'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, fields)
    });
        
    var anos_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('mantenimientos_realizados', 'listarAnos'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'codigo',
            type: 'string'
        }, {
            name: 'nombre',
            type: 'string'
        }])
    });
    
    anos_datastore.load({
        callback: function(){            
            anos_combobox.setValue((anoActual-113)+1);
            recargarDatosMetodos();
        }
    });
    
    var anos_combobox = new Ext.form.ComboBox({
        fieldLabel: 'Año',
        store: anos_datastore,
        displayField: 'nombre',
        valueField: 'codigo',
        mode: 'local',
        triggerAction: 'all',
        forceSelection: true,
        allowBlank: false,
        width: 130,
        listeners: {
            select: function(){
                recargarDatosMetodos();
            }
        }
    });
    
    var maquinas_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('mantenimientos_realizados', 'listarEquiposActivos'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'codigo',
            type: 'string'
        }, {
            name: 'nombre',
            type: 'string'
        }])
    });
    
    maquinas_datastore.load({
        callback: function(){
            maquinas_datastore.loadData({
                data: [{
                    'codigo': '-1',
                    'nombre': 'TODOS'
                }]
            }, true);
            maquinas_combobox.setValue('-1');
            recargarDatosMetodos();
        }
    });
    
    var maquinas_combobox = new Ext.form.ComboBox({
        fieldLabel: 'Equipo',
        store: maquinas_datastore,
        displayField: 'nombre',
        valueField: 'codigo',
        mode: 'local',
        triggerAction: 'all',
        forceSelection: true,
        allowBlank: false,
        width: 130,
        listeners: {
            select: function(){
                recargarDatosMetodos();
            }
        }
    });
    
    var recargarDatosMetodos = function(callback){
        redirigirSiSesionExpiro();
        if (maquinas_combobox.isValid()) {
            datastore.load({
                callback: callback,
                params: {
                    'codigo_maquina': maquinas_combobox.getValue(),
                    'codigo_ano': anos_combobox.getValue()
                }
            });
        }
    }
    recargarDatosMetodos();
    
    var columns = [
    {
        dataIndex: 'nombre_equipo',
        header: 'Nombre de Equipo',
        tooltip: 'Nombre del Equipo',
        width: 230,
        align: 'center'
    }, {
        dataIndex: 'nombre_parte',
        header: 'Nombre de Parte',
        tooltip: 'Nombre de Parte',
        width: 200,
        align: 'center'
    }, {
        dataIndex: 'numero_parte',
        header: 'Número de Parte',
        tooltip: 'Número de Parte',
        width: 150,
        align: 'center'
    }, {
        dataIndex: 'rrm_consumo',
        header: 'Consumo',
        tooltip: 'Consumo (Salida)',
        width: 150,
        align: 'center'
    }, {
        dataIndex: 'rrm_fecha_cambio',
        header: 'Fecha de Cambio',
        tooltip: 'Fecha de Cambio',
        width: 150,
        align: 'center'
    }, {
        dataIndex: 'rrm_observaciones',
        header: 'Observaciones',
        tooltip: 'Observaciones',
        width: 250,
        align: 'center'
    }];

    var grid = new Ext.grid.GridPanel({
        autoWidth: true,
        height: 400,
        //autoHeight: true,
        store: datastore,
        stripeRows: true,
        border: true,
        frame: true,
        autoScroll: true,
        columnLines: true,
        loadMask: {
            msg: 'Cargando...'
        },
        columns: columns
    });
    
    var panelPrincipal = new Ext.FormPanel({
        title: 'Mantenimientos Realizados',
        renderTo: 'panel_principal_realizados',
        border: true,
        frame: true,
        layout: 'form',
        height: 500,
        items: [{
            border: true,
            frame: true,
            height: 45,
            items: [{
                height: 60,
                layout: 'column',
                items: [{
                    width: '225',
                    layout: 'form',
                    labelWidth: 50,
                    footer: false,
                    items: [maquinas_combobox]
                }, {
                    width: '220',
                    layout: 'form',
                    labelWidth: 45,
                    items: [anos_combobox]
                }, {
                    xtype: 'button',
                    iconCls: 'exportar_excel',
                    text: 'Guardar en formato Excel',
                    handler: function(){
                        redirigirSiSesionExpiro();                        
                        if (maquinas_combobox.isValid()) {
                            var params = 'codigo_maquina=' + maquinas_combobox.getValue() + '&codigo_ano=' + anos_combobox.getValue();
                            window.location = getAbsoluteUrl('mantenimientos_realizados', 'exportar') + '?' + params;
                        }
                    }
                }]
            }]
        }, grid]
    });
});