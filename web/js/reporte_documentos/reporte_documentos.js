var largo_panel=500;

var crud_documento_datastore = new Ext.data.Store({
id: 'crud_documento_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_documentos','listarDocumento'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'doc_documento_url', type: 'string'},
                {name: 'doc_fecha_registro', type: 'string'},
                {name: 'doc_tipo_nombre', type: 'string'},
                {name: 'doc_proyeto_nombre', type: 'string'},
                {name: 'doc_usuario_nombre', type: 'string'}
        ])
});

var crud_documento_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
            { header: "Tipo de Documento", width: 200, dataIndex: 'doc_tipo_nombre'},
            { header: "Nombre del Proyecto", width: 400, dataIndex: 'doc_proyeto_nombre'},            
            { header: "Creado por", width: 280, dataIndex: 'doc_usuario_nombre'},
            { header: "Fecha de registro", width: 130, dataIndex: 'doc_fecha_registro'}            
]
});

var proyectos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_documentos', 'listarProyecto'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'pro_codigo',
            type: 'string'
        }, {
            name: 'pro_nombre',
            type: 'string'
        }])
    });
proyectos_datastore_combo.load({
    callback: function(){ 
        proyectos_datastore_combo.loadData({
            data: [{
                'pro_codigo': '-1',
                'pro_nombre': 'TODOS'
            }]
        }, true);
        proyectos_combobox.setValue('-1');
        recargarDatosDocumentos();
    }
});

var tipos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_documentos', 'listarTipoDocumento'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'tip_doc_codigo',
            type: 'string'
        }, {
            name: 'tip_doc_nombre',
            type: 'string'
        }])
    });
tipos_datastore_combo.load({
    callback: function(){ 
        tipos_datastore_combo.loadData({
            data: [{
                'tip_doc_codigo': '-1',
                'tip_doc_nombre': 'TODOS'
            }]
        }, true);
        tipos_combobox.setValue('-1');
        recargarDatosDocumentos();
    }
});

var proyectos_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Proyecto',
    store: proyectos_datastore_combo,
    mode: 'local',
    displayField: 'pro_nombre',
    valueField: 'pro_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 250,
    listeners: {
        select: function(){
            recargarDatosDocumentos();
        }
    }
});

var tipos_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Tipo de Documento',
    store: tipos_datastore_combo,
    mode: 'local',
    displayField: 'tip_doc_nombre',
    valueField: 'tip_doc_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 200,
    listeners: {
        select: function(){
            recargarDatosDocumentos();
        }
    }
});

var recargarDatosDocumentos = function(callback){    
    redirigirSiSesionExpiro();
    if (proyectos_combobox.isValid() && tipos_combobox.isValid()) {
        crud_documento_datastore.load({
            callback: callback,
            params: {
                'codigo_proy': proyectos_combobox.getValue(),
                'codigo_tip_doc': tipos_combobox.getValue()
            }
        });
    }
}
	
var crud_documento_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_documento_gridpanel',            
            region:'center',
            title:'Documentos registrados',
//            stripeRows:true,
            frame: true,
            ds: crud_documento_datastore,
            cm: crud_documento_colmodel,
            height: largo_panel-85,
            loadMask: {
            msg: 'Cargando...'
            }
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_documento_contenedor_panel = new Ext.Panel({
        id: 'crud_documento_contenedor_panel',
        title:'Configuración Documentos',
        height: largo_panel,
        autoWidth: true,
        frame: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede visualizar los documentos registrados',
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
                    items: [tipos_combobox]
                }, {
                    xtype: 'button',
                    iconCls : 'abrir_manual',
                    text : 'Archivo del Documento',
                    tooltip: 'Descargar el archivo con la descripci&oacute;n del documento',                    
                    style: 'padding: 0px 0px 0px 20px',
                    handler : function()
                    {
                        var sm = crud_documento_gridpanel.getSelectionModel();
                        if(sm.hasSelection())
                        {
                            var registro = sm.getSelected();
                            if(registro.get('doc_documento_url') === ''){
                                Ext.Msg.show(
                                  {
                                    title : 'Información',
                                    msg : 'No se ha registrado ningún archivo',
                                    buttons : Ext.Msg.OK,
                                    icon : Ext.MessageBox.INFO
                                  });
                            }else {
                                window.open(urlWeb + registro.get('doc_documento_url'));
                            }  
                        } else
                        {
                          Ext.Msg.show(
                          {
                            title : 'Información',
                            msg : 'Primero debe seleccionar un documento',
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
                          url : getAbsoluteUrl('reporte_documentos', 'exportarReporte'),
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
                            'codigo_tip_doc': tipos_combobox.getValue()
                          }
                        });
                    }
                }
                ]
            }]
        },
                crud_documento_gridpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_reporte_documentos'
});