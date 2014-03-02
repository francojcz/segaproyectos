var largo_panel=450;

var crud_organizacion_datastore = new Ext.data.Store({
id: 'crud_organizacion_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_organizaciones','listarOrganizacionProyecto'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'orpy_organizacion', type: 'string'},
                {name: 'orpy_proyecto', type: 'string'},
                {name: 'orpy_usuario', type: 'string'},                
                {name: 'orpy_fecha_registro', type: 'string'}
        ])
});

var crud_organizacion_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
            { header: "Nombre de la Organizaci&oacute;n", width: 300, dataIndex: 'orpy_organizacion'},
            { header: "Nombre del Proyecto", width: 500, dataIndex: 'orpy_proyecto'},
            { header: "Asignado por", width: 230, dataIndex: 'orpy_usuario'},
            { header: "Fecha de asignaci&oacute;n", width: 150, dataIndex: 'orpy_fecha_registro'}            
]
});

var organizaciones_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_organizaciones', 'listarOrganizacion'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'org_codigo',
            type: 'string'
        }, {
            name: 'org_nombre',
            type: 'string'
        }])
    });
organizaciones_datastore_combo.load({
    callback: function(){ 
        organizaciones_datastore_combo.loadData({
            data: [{
                'org_codigo': '-1',
                'org_nombre': 'TODOS'
            }]
        }, true);
        organizaciones_combobox.setValue('-1');
        recargarDatosOrganizaciones();
    }
});

var proyectos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_organizaciones', 'listarProyecto'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'proye_codigo',
            type: 'string'
        }, {
            name: 'proye_nombre',
            type: 'string'
        }])
    });
proyectos_datastore_combo.load({
    callback: function(){ 
        proyectos_datastore_combo.loadData({
            data: [{
                'proye_codigo': '-1',
                'proye_nombre': 'TODOS'
            }]
        }, true);
        proyectos_combobox.setValue('-1');
        recargarDatosOrganizaciones();
    }
});

var organizaciones_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Organizaci&oacute;n',
    store: organizaciones_datastore_combo,
    mode: 'local',
    displayField: 'org_nombre',
    valueField: 'org_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 250,
    listeners: {
        select: function(){
            recargarDatosOrganizaciones();
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
    displayField: 'proye_nombre',
    valueField: 'proye_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 250,
    listeners: {
        select: function(){
            recargarDatosOrganizaciones();
        }
    }
});

var recargarDatosOrganizaciones = function(callback){    
    redirigirSiSesionExpiro();
    if (organizaciones_combobox.isValid() && proyectos_combobox.isValid()) {
        crud_organizacion_datastore.load({
            callback: callback,
            params: {
                'codigo_org': organizaciones_combobox.getValue(),
                'codigo_proy': proyectos_combobox.getValue()
            }
        });
    }
}
	
var crud_organizacion_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_organizacion_gridpanel',            
            region:'center',
            title:'Organizaciones asignadas',
//            stripeRows:true,
            frame: true,
            ds: crud_organizacion_datastore,
            cm: crud_organizacion_colmodel,
            height: largo_panel-85,
            loadMask: {
            msg: 'Cargando...'
            }
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_organizacion_contenedor_panel = new Ext.Panel({
        id: 'crud_organizacion_contenedor_panel',
        title:'Configuración Organizaciones',
        height: largo_panel,
        autoWidth: true,
        frame: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede visualizar las organizaciones registrados',
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
                    labelWidth: 90,
                    footer: false,
                    items: [organizaciones_combobox]
                }, {
                    layout: 'form',
                    labelWidth: 60,
                    footer: false,
                    style: 'padding: 0px 0px 0px 20px',
                    items: [proyectos_combobox]
                }, {
                    xtype: 'button',
                    iconCls : 'exportar_pdf',
                    text : 'Exportar a PDF',
                    tooltip: 'Descargar la información en formato PDF',                    
                    style: 'padding: 0px 0px 0px 20px',
                    handler : function() {
                          Ext.Ajax.request({
                          url : getAbsoluteUrl('reporte_organizaciones', 'exportarReporte'),
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
                            'codigo_org': organizaciones_combobox.getValue(),
                            'codigo_proy': proyectos_combobox.getValue()
                          }
                        });
                    }
                }
                ]
            }]
        },
                crud_organizacion_gridpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_reporte_organizaciones'
});