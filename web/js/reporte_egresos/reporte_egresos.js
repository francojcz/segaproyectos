var largo_panel=450;

var crud_egreso_datastore = new Ext.data.Store({
id: 'crud_egreso_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_egresos','listarEgreso'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'egr_concepto', type: 'string'},
                {name: 'egr_valor', type: 'string'},
                {name: 'egr_fecha', type: 'string'},                
                {name: 'egr_fecha_registro', type: 'string'},
                {name: 'egr_proyecto', type: 'string'},
                {name: 'egr_usuario', type: 'string'}
        ])
});
crud_egreso_datastore.load();

var crud_egreso_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
            { header: "Nombre del Concepto", width: 270, dataIndex: 'egr_concepto'},
            { header: "Valor", width: 110, dataIndex: 'egr_valor'},
            { header: "Nombre del Proyecto", width: 300, dataIndex: 'egr_proyecto'},
            { header: "Fecha del Egreso", width: 150, dataIndex: 'egr_fecha'},
            { header: "Creado por", width: 230, dataIndex: 'egr_usuario'},
            { header: "Fecha de Registro", width: 150, dataIndex: 'egr_fecha_registro'}
]
});

var conceptos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_egresos', 'listarConcepto'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'concep_codigo',
            type: 'string'
        }, {
            name: 'concep_nombre',
            type: 'string'
        }])
    });
conceptos_datastore_combo.load({
    callback: function(){ 
        conceptos_datastore_combo.loadData({
            data: [{
                'concep_codigo': '-1',
                'concep_nombre': 'TODOS'
            }]
        }, true);
        conceptos_combobox.setValue('-1');
        recargarDatosEgresos();
    }
});

var proyectos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_egresos', 'listarProyecto'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'proyecto_codigo',
            type: 'string'
        }, {
            name: 'proyecto_nombre',
            type: 'string'
        }])
    });
proyectos_datastore_combo.load({
    callback: function(){ 
        proyectos_datastore_combo.loadData({
            data: [{
                'proyecto_codigo': '-1',
                'proyecto_nombre': 'TODOS'
            }]
        }, true);
        proyectos_combobox.setValue('-1');
        recargarDatosEgresos();
    }
});

var anos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_egresos', 'listarAno'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'ano',
            type: 'string'
        }])
    });
anos_datastore_combo.load({
    callback: function(){ 
        anos_datastore_combo.loadData({
            data: [{
                'ano': 'TODOS'
            }]
        }, true);
        anos_combobox.setValue('TODOS');
        recargarDatosEgresos();
    }
});

var meses_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_egresos', 'listarMes'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'mes_codigo',
            type: 'string'
        }, {
            name: 'mes_nombre',
            type: 'string'
        }])
    });
meses_datastore_combo.load({
    callback: function(){ 
        meses_datastore_combo.loadData({
            data: [{
                'mes_codigo': '-1',
                'mes_nombre': 'TODOS'
            }]
        }, true);
        meses_combobox.setValue('-1');
        recargarDatosEgresos();
    }
});

var conceptos_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Concepto',
    store: conceptos_datastore_combo,
    mode: 'local',
    displayField: 'concep_nombre',
    valueField: 'concep_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 250,
    listeners: {
        select: function(){
            recargarDatosEgresos();
        }
    }
});

var proyectos_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Proyecto',
    store: proyectos_datastore_combo,
    mode: 'local',
    displayField: 'proyecto_nombre',
    valueField: 'proyecto_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 250,
    listeners: {
        select: function(){
            recargarDatosEgresos();
        }
    }
});

var anos_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'A&ntilde;o',
    store: anos_datastore_combo,
    mode: 'local',
    displayField: 'ano',
    valueField: 'ano',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width:80,
    listeners: {
        select: function(){
            recargarDatosEgresos();
        }
    }
});

var meses_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Mes',
    store: meses_datastore_combo,
    mode: 'local',
    displayField: 'mes_nombre',
    valueField: 'mes_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width:120,
    listeners: {
        select: function(){
            recargarDatosEgresos();
        }
    }
});

var recargarDatosEgresos = function(callback){    
    redirigirSiSesionExpiro();
    if (proyectos_combobox.isValid() && conceptos_combobox.isValid() && anos_combobox.isValid() && meses_combobox.isValid()
) {
        crud_egreso_datastore.load({
            callback: callback,
            params: {
                'codigo_con': conceptos_combobox.getValue(),
                'codigo_proy': proyectos_combobox.getValue(),
                'ano': anos_combobox.getValue(),
                'mes': meses_combobox.getValue()
            }
        });
    }
}
	
var crud_egreso_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_egreso_gridpanel',            
            region:'center',
            title:'Egresos registrados',
//            stripeRows:true,
            frame: true,
            ds: crud_egreso_datastore,
            cm: crud_egreso_colmodel,
            height: largo_panel-85,
            loadMask: {
            msg: 'Cargando...'
            }
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_egreso_contenedor_panel = new Ext.Panel({
        id: 'crud_egreso_contenedor_panel',
        title:'Configuración Egresos',
        height: largo_panel,
        autoWidth: true,
        frame: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede visualizar los egresos registrados',
        monitorResize:true,
        layout:'form',
        items: 
        [
        {
            border: false,
//            frame: true,
            height: 33,
            items: [{
                height: 50,
                layout: 'column',
                items: [
                {
                    layout: 'form',
                    labelWidth: 60,
                    footer: false,
                    items: [proyectos_combobox]
                }, {
                    layout: 'form',
                    labelWidth: 70,
                    footer: false,
                    style: 'padding: 0px 0px 0px 15px',
                    items: [conceptos_combobox]
                }, {
                    layout: 'form',
                    labelWidth: 40,
                    footer: false,
                    style: 'padding: 0px 0px 0px 15px',
                    items: [anos_combobox]
                }, {
                    layout: 'form',
                    labelWidth: 40,
                    footer: false,
                    style: 'padding: 0px 0px 0px 15px',
                    items: [meses_combobox]
                }, {
                    xtype: 'button',
                    iconCls : 'exportar_pdf',
                    text : 'Exportar a PDF',
                    tooltip: 'Descargar la información en formato PDF',                    
                    style: 'padding: 0px 0px 0px 20px',
                    handler : function() {
                          Ext.Ajax.request({
                          url : getAbsoluteUrl('reporte_egresos', 'exportarReporte'),
                          failure : function()
                          {
                              Ext.Msg.show(
                              {
                                title : 'Información',
                                msg : 'Error al exportar el reporte.',
                                buttons : Ext.Msg.OK,
                                icon : Ext.MessageBox.INFO
                              });
                          },
                          success : function(result)
                          {
                              window.open(urlWeb + 'Reporte.pdf');
                          },
                          params : 
                          {
                            'codigo_con': conceptos_combobox.getValue(),
                            'codigo_proy': proyectos_combobox.getValue(),
                            'ano': anos_combobox.getValue(),
                            'mes': meses_combobox.getValue()
                          }
                        });
                    }
                }
                ]
            }]
        },
                crud_egreso_gridpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_reporte_egresos'
});