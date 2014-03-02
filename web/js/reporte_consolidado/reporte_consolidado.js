var largo_panel=450;

var crud_consolidado_datastore = new Ext.data.Store({
id: 'crud_consolidado_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_consolidado','listarConceptosIngreso'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'concepto_nombre', type: 'string'},
                {name: 'total_ingresos', type: 'string'},
                {name: 'total_egresos', type: 'string'},
                {name: 'total_disponible', type: 'string'}
        ])
});
//crud_consolidado_datastore.load();

var crud_consolidado_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
            { header: "Nombre del Concepto", width: 350, dataIndex: 'concepto_nombre'},
            { header: "Total Ingresos", width: 200, dataIndex: 'total_ingresos'},
            { header: "Total Egresos", width: 200, dataIndex: 'total_egresos'},
            { header: "Total Disponible", width: 200, dataIndex: 'total_disponible'}
]
});

var proyectos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_consolidado', 'listarProyecto'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'proyect_codigo',
            type: 'string'
        }, {
            name: 'proyect_nombre',
            type: 'string'
        }])
    });
proyectos_datastore_combo.load({
    callback: function(){ 
        proyectos_datastore_combo.loadData({
            data: [{
                'proyect_codigo': '-1',
                'proyect_nombre': 'TODOS'
            }]
        }, true);
        proyectos_combobox.setValue('-1');
        recargarDatosConsolidado();
    }
});

var anos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_consolidado', 'listarAno'),
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
        recargarDatosConsolidado();
    }
});

var meses_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_consolidado', 'listarMes'),
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
        recargarDatosConsolidado();
    }
});

var proyectos_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Proyecto',
    store: proyectos_datastore_combo,
    mode: 'local',
    displayField: 'proyect_nombre',
    valueField: 'proyect_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 250,
    listeners: {
        select: function(){
            recargarDatosConsolidado();
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
            recargarDatosConsolidado();
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
            recargarDatosConsolidado();
        }
    }
});

var recargarDatosConsolidado = function(callback){    
    redirigirSiSesionExpiro();
    if (proyectos_combobox.isValid() && anos_combobox.isValid() && meses_combobox.isValid()) {
        crud_consolidado_datastore.load({
            callback: callback,
            params: {
                'codigo_proy': proyectos_combobox.getValue(),
                'ano': anos_combobox.getValue(),
                'mes': meses_combobox.getValue()
            }
        });
    }
}
	
var crud_consolidado_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_consolidado_gridpanel',            
            region:'center',
            title:'Presupuesto Consolidado',
//            stripeRows:true,
            frame: true,
            ds: crud_consolidado_datastore,
            cm: crud_consolidado_colmodel,
            height: largo_panel-85,
            loadMask: {
            msg: 'Cargando...'
            }
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_consolidado_contenedor_panel = new Ext.Panel({
        id: 'crud_consolidado_contenedor_panel',
        title:'Configuración Presupuesto Consolidado',
        height: largo_panel,
        autoWidth: true,
        frame: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede visualizar el presupuesto consolidado',
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
                items: [{
                    layout: 'form',
                    labelWidth: 60,
                    footer: false,
                    items: [proyectos_combobox]
                }, {
                    layout: 'form',
                    labelWidth: 40,
                    footer: false,
                    style: 'padding: 0px 0px 0px 25px',
                    items: [anos_combobox]
                }, {
                    layout: 'form',
                    labelWidth: 40,
                    footer: false,
                    style: 'padding: 0px 0px 0px 25px',
                    items: [meses_combobox]
                }, {
                    xtype: 'button',
                    iconCls : 'exportar_pdf',
                    text : 'Exportar a PDF',
                    tooltip: 'Descargar la información en formato PDF',                    
                    style: 'padding: 0px 0px 0px 20px',
                    handler : function() {
                          Ext.Ajax.request({
                          url : getAbsoluteUrl('reporte_consolidado', 'exportarReporte'),
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
                crud_consolidado_gridpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_reporte_consolidado'
});