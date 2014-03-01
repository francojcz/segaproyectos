var largo_panel=500;

var crud_producto_datastore = new Ext.data.Store({
id: 'crud_producto_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_productos','listarProducto'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'prod_nombre', type: 'string'},
                {name: 'prod_fecha_entrega', type: 'string'},
                {name: 'prod_documento_url', type: 'string'},
                {name: 'prod_fecha_registro', type: 'string'},
                {name: 'prod_proyeto_nombre', type: 'string'},
                {name: 'prod_usuario_nombre', type: 'string'},
                {name: 'prod_estado_nombre', type: 'string'}
        ])
});
crud_producto_datastore.load();

var crud_producto_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
            { header: "Nombre del Producto", width: 300, dataIndex: 'prod_nombre'},
            { header: "Fecha de Entrega", width: 130, dataIndex: 'prod_fecha_entrega'},
            { header: "Estado del Producto", width: 130, dataIndex: 'prod_estado_nombre'},
            { header: "Nombre del Proyecto", width: 350, dataIndex: 'prod_proyeto_nombre'},            
            { header: "Creado por", width: 220, dataIndex: 'prod_usuario_nombre'},
            { header: "Fecha de registro", width: 130, dataIndex: 'prod_fecha_registro'}            
]
});

var proyectos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_productos', 'listarProyecto'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'proy_codigo',
            type: 'string'
        }, {
            name: 'proy_nombre',
            type: 'string'
        }])
    });
proyectos_datastore_combo.load({
    callback: function(){ 
        proyectos_datastore_combo.loadData({
            data: [{
                'proy_codigo': '-1',
                'proy_nombre': 'TODOS'
            }]
        }, true);
        proyectos_combobox.setValue('-1');
        recargarDatosProductos();
    }
});

var estados_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_productos', 'listarEstadoProducto'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'est_prod_codigo',
            type: 'string'
        }, {
            name: 'est_prod_nombre',
            type: 'string'
        }])
    });
estados_datastore_combo.load({
    callback: function(){ 
        estados_datastore_combo.loadData({
            data: [{
                'est_prod_codigo': '-1',
                'est_prod_nombre': 'TODOS'
            }]
        }, true);
        estados_combobox.setValue('-1');
        recargarDatosProductos();
    }
});

var proyectos_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Proyecto',
    store: proyectos_datastore_combo,
    mode: 'local',
    displayField: 'proy_nombre',
    valueField: 'proy_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 250,
    listeners: {
        select: function(){
            recargarDatosProductos();
        }
    }
});

var estados_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Estado del Producto',
    store: estados_datastore_combo,
    mode: 'local',
    displayField: 'est_prod_nombre',
    valueField: 'est_prod_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 200,
    listeners: {
        select: function(){
            recargarDatosProductos();
        }
    }
});

var recargarDatosProductos = function(callback){    
    redirigirSiSesionExpiro();
    if (proyectos_combobox.isValid() && estados_combobox.isValid()) {
        crud_producto_datastore.load({
            callback: callback,
            params: {
                'codigo_proy': proyectos_combobox.getValue(),
                'codigo_est_prod': estados_combobox.getValue()
            }
        });
    }
}
	
var crud_producto_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_producto_gridpanel',            
            region:'center',
            title:'Productos registrados',
//            stripeRows:true,
            frame: true,
            ds: crud_producto_datastore,
            cm: crud_producto_colmodel,
            height: largo_panel-85,
            loadMask: {
            msg: 'Cargando...'
            }
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_producto_contenedor_panel = new Ext.Panel({
        id: 'crud_producto_contenedor_panel',
        title:'Configuración Productos',
        height: largo_panel,
        autoWidth: true,
        frame: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede visualizar los productos registrados',
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
                    labelWidth: 130,
                    footer: false,
                    style: 'padding: 0px 0px 0px 10px',
                    items: [estados_combobox]
                }, {
                    xtype: 'button',
                    iconCls : 'abrir_manual',
                    text : 'Archivo del Producto',
                    tooltip: 'Descargar el archivo con la descripci&oacute;n del producto',                    
                    style: 'padding: 0px 0px 0px 20px',
                    handler : function()
                    {
                        var sm = crud_producto_gridpanel.getSelectionModel();
                        if(sm.hasSelection())
                        {
                            var registro = sm.getSelected();
                            if(registro.get('prod_documento_url') === ''){
                                Ext.Msg.show(
                                  {
                                    title : 'Información',
                                    msg : 'No se ha registrado ningún archivo',
                                    buttons : Ext.Msg.OK,
                                    icon : Ext.MessageBox.INFO
                                  });
                            }else {
                                window.open(urlWeb + registro.get('prod_documento_url'));
                            }  
                        } else
                        {
                          Ext.Msg.show(
                          {
                            title : 'Información',
                            msg : 'Primero debe seleccionar un producto',
                            buttons : Ext.Msg.OK,
                            icon : Ext.MessageBox.INFO
                          });
                        }
                    }
                }, {
                    xtype: 'button',
                    iconCls : 'exportar_pdf',
                    text : 'Exportar a PDF',
                    tooltip: 'Descargar la información en formato PDF',                    
                    style: 'padding: 0px 0px 0px 20px',
                    handler : function() {
                          Ext.Ajax.request({
                          url : getAbsoluteUrl('reporte_productos', 'exportarReporte'),
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
                            'codigo_est_prod': estados_combobox.getValue()
                          }
                        });
                    }
                }
                ]
            }]
        },
                crud_producto_gridpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_reporte_productos'
});