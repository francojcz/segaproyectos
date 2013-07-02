/*
 manejo reporte x indicador - tpm labs
 Desarrollado maryit sanchez
 2010
 */
var rdpi_analista_codigo_datastore = new Ext.data.JsonStore({
    id: 'rdpi_analista_codigo_datastore',
    url: getAbsoluteUrl('reporte_diarioporindicador', 'listarAnalistas'),
    root: 'results',
    totalProperty: 'total',
    fields: [{
        name: 'empl_usu_codigo',
        type: 'string'
    }, {
        name: 'empl_nombre_completo',
        type: 'string'
    }, ]
});

rdpi_analista_codigo_datastore.load();


var rdpi_analista_codigo_combobox = new Ext.form.ComboBox({
    xtype: 'combo',
    store: rdpi_analista_codigo_datastore,
    hiddenName: 'analista_codigo',
    name: 'rdpi_analista_codigo_combobox',
    id: 'rdpi_analista_codigo_combobox',
    mode: 'local',
    valueField: 'empl_usu_codigo',
    forceSelection: true,
    displayField: 'empl_nombre_completo',
    triggerAction: 'all',
    emptyText: 'Seleccione un analista...',
    selectOnFocus: true,
    fieldLabel: 'Analista',
});


var rdpi_maquina_codigo_datastore = new Ext.data.JsonStore({
    id: 'rdpi_maquina_codigo_datastore',
    url: getAbsoluteUrl('reporte_diarioporindicador', 'listarMaquinas'),
    root: 'results',
    totalProperty: 'total',
    fields: [{
        name: 'maq_codigo',
        type: 'string'
    }, {
        name: 'maq_nombre',
        type: 'string'
    }, ]
});
rdpi_maquina_codigo_datastore.load();


var rdpi_maquina_codigo_combobox = new Ext.form.ComboBox({
    xtype: 'combo',
    store: rdpi_maquina_codigo_datastore,
    hiddenName: 'maquina_codigo',
    name: 'rdpi_maquina_codigo_combobox',
    id: 'rdpi_maquina_codigo_combobox',
    mode: 'local',
    valueField: 'maq_codigo',
    forceSelection: true,
    displayField: 'maq_nombre',
    triggerAction: 'all',
    emptyText: 'Seleccione un equipo ó máquina...',
    selectOnFocus: true,
    fieldLabel: 'Equipo',
});


var rdpi_metodo_codigo_datastore = new Ext.data.JsonStore({
    id: 'rdpi_metodo_codigo_datastore',
    url: getAbsoluteUrl('reporte_diarioporindicador', 'listarMetodos'),
    root: 'results',
    totalProperty: 'total',
    fields: [{
        name: 'met_codigo',
        type: 'string'
    }, {
        name: 'met_nombre',
        type: 'string'
    }, ]
});
rdpi_metodo_codigo_datastore.load();


var rdpi_metodo_codigo_combobox = new Ext.form.ComboBox({
    xtype: 'combo',
    store: rdpi_metodo_codigo_datastore,
    hiddenName: 'metodo_codigo',
    name: 'rdpi_metodo_codigo_combobox',
    id: 'rdpi_metodo_codigo_combobox',
    mode: 'local',
    valueField: 'met_codigo',
    forceSelection: true,
    displayField: 'met_nombre',
    triggerAction: 'all',
    emptyText: 'Seleccione un método...',
    selectOnFocus: true,
    fieldLabel: 'M&eacute;todo'
});


var reporte_diarioporindicador_configuracion = new Ext.FormPanel({
    layout: 'form',
    frame: true,
    padding: 10,
    labelWidth: 150,
    items: [{
        xtype: 'compositefield',
        fieldLabel: 'Entre las fechas que van',
        items: [{
            xtype: 'displayfield',
            value: 'Desde'
        }, {
            id: 'rdpi_desde_fecha',
            xtype: 'datefield',
            fieldLabel: 'Desde',
            format: 'Y-m-d'
        }, {
            xtype: 'displayfield',
            value: 'Hasta'
        }, {
            id: 'rdpi_hasta_fecha',
            xtype: 'datefield',
            fieldLabel: 'Hasta',
            format: 'Y-m-d'
        }]
    }, {
        xtype: 'compositefield',
        fieldLabel: 'Informaci&oacute;n Adicional',
        items: [{
            xtype: 'displayfield',
            value: 'Analista'
        }, rdpi_analista_codigo_combobox, {
            xtype: 'displayfield',
            value: 'Equipo'
        }, rdpi_maquina_codigo_combobox, {
            xtype: 'displayfield',
            value: 'M&eacute;todo'
        }, rdpi_metodo_codigo_combobox, {
            text: 'Buscar',
            iconCls: 'filtrar',
            xtype: 'button',
            handler: function(){
                Ext.getCmp('reporte_diario_contenido_table').removeAll(true);
                Ext.getCmp('reporte_diario_contenido_table').destroy();
                
                reporte_diarioporindicador_datastore.removeAll();
                
                reporte_diarioporindicador_datastore.baseParams.desde_fecha = '';
                reporte_diarioporindicador_datastore.baseParams.hasta_fecha = '';
                reporte_diarioporindicador_datastore.baseParams.maquina_codigo = rdpi_maquina_codigo_combobox.getValue();
                reporte_diarioporindicador_datastore.baseParams.metodo_codigo = rdpi_metodo_codigo_combobox.getValue();
                reporte_diarioporindicador_datastore.baseParams.analista_codigo = rdpi_analista_codigo_combobox.getValue();
                
                
                if (Ext.getCmp('rdpi_desde_fecha').getValue() != '') {
                    reporte_diarioporindicador_datastore.baseParams.desde_fecha = Ext.getCmp('rdpi_desde_fecha').getValue().format('Y-m-d');
                }
                if (Ext.getCmp('rdpi_hasta_fecha').getValue() != '') {
                    reporte_diarioporindicador_datastore.baseParams.hasta_fecha = Ext.getCmp('rdpi_hasta_fecha').getValue().format('Y-m-d');
                }
                
                
                reporte_diarioporindicador_cargar_datastore();
                
            }
        }]
    }],
    renderTo: 'div_form_reporte_diarioporindicador'
});


var largo_panel = 500;
var reporte_diarioporindicador_datastore = new Ext.data.Store({
    id: 'reporte_diarioporindicador_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_diarioporindicador', 'listarReportediarioporindicador'),
        method: 'POST'
    }),
    baseParams: {
        desde_fecha: '',
        hasta_fecha: '',
        maquina_codigo: '',
        metodo_codigo: '',
        analista_codigo: ''
    },
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'rdpi_fecha',
        type: 'string'
    }, {
        name: 'rdpi_maquina',
        type: 'string'
    }, {
        name: 'rdpi_analista',
        type: 'string'
    }, {
        name: 'rdpi_metodo',
        type: 'string'
    }, {
        name: 'rdpi_tp_metodo',
        type: 'float'
    }, {
        name: 'rdpi_tp_dia',
        type: 'float'
    }, {
        name: 'rdpi_tnp_metodo',
        type: 'float'
    }, {
        name: 'rdpi_tnp_dia',
        type: 'float'
    }, {
        name: 'rdpi_tpp_metodo',
        type: 'float'
    }, {
        name: 'rdpi_tpp_dia',
        type: 'float'
    }, {
        name: 'rdpi_tpnp_metodo',
        type: 'float'
    }, {
        name: 'rdpi_tpnp_dia',
        type: 'float'
    }, {
        name: 'rdpi_tf_metodo',
        type: 'float'
    }, {
        name: 'rdpi_tf_dia',
        type: 'float'
    }, {
        name: 'rdpi_to_metodo',
        type: 'float'
    }, {
        name: 'rdpi_to_dia',
        type: 'float'
    }, {
        name: 'rdpi_d_metodo',
        type: 'float'
    }, {
        name: 'rdpi_d_dia',
        type: 'float'
    }, {
        name: 'rdpi_e_metodo',
        type: 'float'
    }, {
        name: 'rdpi_e_dia',
        type: 'float'
    }, {
        name: 'rdpi_c_metodo',
        type: 'float'
    }, {
        name: 'rdpi_c_dia',
        type: 'float'
    }, {
        name: 'rdpi_ae_dia',
        type: 'float'
    }, {
        name: 'rdpi_oee_metodo',
        type: 'float'
    }, {
        name: 'rdpi_oee_dia',
        type: 'float'
    }, {
        name: 'rdpi_cantidad_dia',
        type: 'int'
    }])
});

function reporte_diarioporindicador_cargar_datastore(){
    reporte_diarioporindicador_datastore.load({
        callback: function(){
            var reporte_diarioporindicador_contenedor_panel = new Ext.Panel({
                id: 'reporte_diario_contenido_table',
                layout: 'table',
                bodyStyle: 'background-color:#EFF5FB;',
                layoutConfig: {
                    tableAttrs: {
                        style: {
                            width: '100%'
                        }
                    },
                    columns: 25
                },
                defaults: {
                    width: 36,
                    bodyStyle: 'padding:2px 2px'
                }
            });
            
            var cantidad_record = reporte_diarioporindicador_datastore.getCount();
            var contador = 0;
            if (cantidad_record == 0) {
                var mensaje_sin_datos = '<center><font size=5>No hay datos para la configuraci&oacute;n realizada<font><center>';
                reporte_diarioporindicador_contenedor_panel.add({
                    html: mensaje_sin_datos,
                    bodyCssClass: 'poner_pastel_base',
                    width: 900,
                    colspan: 25
                });
                
            }
            reporte_diarioporindicador_datastore.each(function(record){
                var cant_rowspan = record.data.rdpi_cantidad_dia;
                reporte_diarioporindicador_contenedor_panel.add({
                    html: '' + record.data.rdpi_fecha,
                    width: 60,
                    bodyCssClass: 'poner_pastel_base'
                });
                reporte_diarioporindicador_contenedor_panel.add({
                    html: '' + record.data.rdpi_maquina,
                    width: 60,
                    bodyCssClass: 'poner_pastel_base'
                });
                reporte_diarioporindicador_contenedor_panel.add({
                    html: '' + record.data.rdpi_analista,
                    width: 60,
                    bodyCssClass: 'poner_pastel_base'
                });
                reporte_diarioporindicador_contenedor_panel.add({
                    html: '' + record.data.rdpi_metodo,
                    width: 60,
                    bodyCssClass: 'poner_pastel_base'
                });
                
                if (contador == 0) {
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tp_metodo,
                        bodyCssClass: 'poner_azul'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tp_dia,
                        bodyCssClass: 'poner_azul',
                        rowspan: cant_rowspan
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tnp_metodo,
                        bodyCssClass: 'poner_amarillo'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tnp_dia,
                        bodyCssClass: 'poner_amarillo',
                        rowspan: cant_rowspan
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tpp_metodo,
                        bodyCssClass: 'poner_verde'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tpp_dia,
                        bodyCssClass: 'poner_verde',
                        rowspan: cant_rowspan
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tpnp_metodo,
                        bodyCssClass: 'poner_rojo'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tpnp_dia,
                        bodyCssClass: 'poner_rojo',
                        rowspan: cant_rowspan
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tf_metodo,
                        bodyCssClass: 'poner_naranja'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tf_dia,
                        bodyCssClass: 'poner_naranja',
                        rowspan: cant_rowspan
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_to_metodo,
                        bodyCssClass: 'poner_azul'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_to_dia,
                        bodyCssClass: 'poner_azul',
                        rowspan: cant_rowspan
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_d_metodo,
                        bodyCssClass: 'poner_cafe'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_d_dia,
                        bodyCssClass: 'poner_cafe',
                        rowspan: cant_rowspan
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_e_metodo,
                        bodyCssClass: 'poner_cafe'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_e_dia,
                        bodyCssClass: 'poner_cafe',
                        rowspan: cant_rowspan
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_c_metodo,
                        bodyCssClass: 'poner_cafe'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_c_dia,
                        bodyCssClass: 'poner_cafe',
                        rowspan: cant_rowspan
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_ae_dia,
                        bodyCssClass: 'poner_cafe',
                        rowspan: cant_rowspan
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_oee_metodo,
                        bodyCssClass: 'poner_zapote'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_oee_dia,
                        bodyCssClass: 'poner_zapote',
                        rowspan: cant_rowspan
                    });
                }
                else {
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tp_metodo,
                        bodyCssClass: 'poner_azul'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tnp_metodo,
                        bodyCssClass: 'poner_amarillo'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tpp_metodo,
                        bodyCssClass: 'poner_verde'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tpnp_metodo,
                        bodyCssClass: 'poner_rojo'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_tf_metodo,
                        bodyCssClass: 'poner_naranja'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_to_metodo,
                        bodyCssClass: 'poner_azul'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_d_metodo,
                        bodyCssClass: 'poner_cafe'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_e_metodo,
                        bodyCssClass: 'poner_cafe'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_c_metodo,
                        bodyCssClass: 'poner_cafe'
                    });
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '' + record.data.rdpi_oee_metodo,
                        bodyCssClass: 'poner_zapote'
                    });
                }
                contador++;
                if (record.data.rdpi_cantidad_dia == contador) {
                    contador = 0;
                    reporte_diarioporindicador_contenedor_panel.add({
                        html: '<br/>',
                        colspan: 25,
                        anchor: '100%',
                        border: false,
                        bodyStyle: 'background-color:#EFF5FB;'
                    });
                }
                
            });
            
            reporte_diarioporindicador_contenedor_panel.render('div_form_reporte_diarioporindicador');
            reporte_diarioporindicador_contenedor_panel.doLayout();
        }
    });
    
}


var reporte_diarioporindicador_panel_title = new Ext.Panel({
    id: 'reporte_diario_title_table',
    layout: 'table',
    bodyStyle: 'background-color:#FBFBEF;',
    layoutConfig: {
        tableAttrs: {
            style: {
                width: '100%'
            }
        },
        columns: 25
    },
    defaults: {
        width: 36,
        bodyStyle: 'padding:2px 2px',
        ctCls: 'x-panel-mc',
        bodyCssClass: 'x-panel-mc'
    },
    items: [{
        html: 'FECHA',
        border: false,
        rowspan: 2,
        width: 60
    }, {
        html: 'M&Aacute;QUINA',
        border: false,
        rowspan: 2,
        width: 60
    }, {
        html: 'ANALISTA',
        border: false,
        rowspan: 2,
        width: 60
    }, {
        html: 'M&Eacute;TODO',
        border: false,
        rowspan: 2,
        width: 60
    }, {
        html: 'TP (Hrs)',
        border: false,
        colspan: 2,
        width: 60
    }, {
        html: 'TNP (Hrs)',
        border: false,
        colspan: 2,
        width: 60
    }, {
        html: 'TPP (Hrs)',
        border: false,
        colspan: 2,
        width: 60
    }, {
        html: 'TPNP (Hrs)',
        border: false,
        colspan: 2,
        width: 60
    }, {
        html: 'TF (Hrs)',
        border: false,
        colspan: 2,
        width: 60
    }, {
        html: 'TO (Hrs)',
        border: false,
        colspan: 2,
        width: 60
    }, {
        html: 'D (%)',
        border: false,
        colspan: 2,
        width: 60
    }, {
        html: 'E (%)',
        border: false,
        colspan: 2,
        width: 60
    }, {
        html: 'C (%)',
        border: false,
        colspan: 2,
        width: 60
    }, {
        html: 'AE(%)',
        border: false,
        colspan: 1
    }, {
        html: 'OEE (%)',
        border: false,
        colspan: 2,
        width: 60
    }, {
        html: 'M&eacute;todo',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'D&iacute;a',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'M&eacute;todo',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'D&iacute;a',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'M&eacute;todo',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'D&iacute;a',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'M&eacute;todo',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'D&iacute;a',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'M&eacute;todo',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'D&iacute;a',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'M&eacute;todo',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'D&iacute;a',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'M&eacute;todo',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'D&iacute;a',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'M&eacute;todo',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'D&iacute;a',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'M&eacute;todo',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'D&iacute;a',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'D&iacute;a',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'M&eacute;todo',
        bodyCssClass: 'poner_fondo'
    }, {
        html: 'D&iacute;a',
        bodyCssClass: 'poner_fondo'
    }],
    renderTo: 'div_form_reporte_diarioporindicador'
});


reporte_diarioporindicador_cargar_datastore();




