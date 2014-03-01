var largo_panel=500;

var crud_ingreso_datastore = new Ext.data.Store({
id: 'crud_ingreso_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_ingresos','listarIngreso'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'csi_concepto', type: 'string'},
                {name: 'csi_valor', type: 'string'},
                {name: 'csi_ingreso', type: 'string'},                
                {name: 'csi_fecha_registro', type: 'string'},
                {name: 'csi_fecha_ingreso', type: 'string'},
                {name: 'csi_usuario', type: 'string'},
                {name: 'csi_proyecto', type: 'string'}
        ])
});
crud_ingreso_datastore.load();

var crud_ingreso_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
            { header: "Nombre del Concepto", width: 250, dataIndex: 'csi_concepto'},
            { header: "Valor", width: 110, dataIndex: 'csi_valor'},
            { header: "Descripci&oacute;n del Ingreso", width: 300, dataIndex: 'csi_ingreso'},
            { header: "Nombre del Proyecto", width: 250, dataIndex: 'csi_proyecto'},
            { header: "Fecha del Ingreso", width: 150, dataIndex: 'csi_fecha_ingreso'},
            { header: "Creado por", width: 200, dataIndex: 'csi_usuario'},
            { header: "Fecha de Registro", width: 150, dataIndex: 'csi_fecha_registro'}
]
});

var conceptos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_ingresos', 'listarConcepto'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'conc_codigo',
            type: 'string'
        }, {
            name: 'conc_nombre',
            type: 'string'
        }])
    });
conceptos_datastore_combo.load({
    callback: function(){ 
        conceptos_datastore_combo.loadData({
            data: [{
                'conc_codigo': '-1',
                'conc_nombre': 'TODOS'
            }]
        }, true);
        conceptos_combobox.setValue('-1');
        recargarDatosIngresos();
    }
});

var proyectos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_ingresos', 'listarProyecto'),
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
        recargarDatosIngresos();
    }
});

var anos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_ingresos', 'listarAno'),
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
        recargarDatosIngresos();
    }
});

var meses_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_ingresos', 'listarMes'),
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
        recargarDatosIngresos();
    }
});

var conceptos_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Concepto',
    store: conceptos_datastore_combo,
    mode: 'local',
    displayField: 'conc_nombre',
    valueField: 'conc_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 250,
    listeners: {
        select: function(){
            recargarDatosIngresos();
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
    displayField: 'proyect_nombre',
    valueField: 'proyect_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 250,
    listeners: {
        select: function(){
            recargarDatosIngresos();
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
            recargarDatosIngresos();
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
            recargarDatosIngresos();
        }
    }
});

var recargarDatosIngresos = function(callback){    
    redirigirSiSesionExpiro();
    if (proyectos_combobox.isValid() && conceptos_combobox.isValid() && anos_combobox.isValid() && meses_combobox.isValid()) {
        crud_ingreso_datastore.load({
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
	
var crud_ingreso_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_ingreso_gridpanel',            
            region:'center',
            title:'Ingresos registrados',
//            stripeRows:true,
            frame: true,
            ds: crud_ingreso_datastore,
            cm: crud_ingreso_colmodel,
            height: largo_panel-85,
            loadMask: {
            msg: 'Cargando...'
            }
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_ingreso_contenedor_panel = new Ext.Panel({
        id: 'crud_ingreso_contenedor_panel',
        title:'Configuración Ingresos',
        height: largo_panel,
        autoWidth: true,
        frame: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede visualizar los ingresos registrados',
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
                          url : getAbsoluteUrl('reporte_ingresos', 'exportarReporte'),
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
                crud_ingreso_gridpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_reporte_ingresos'
});