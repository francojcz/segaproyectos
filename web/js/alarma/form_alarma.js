var largo_panel=500;

var crud_alarma_datastore = new Ext.data.Store({
id: 'crud_alarma_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('alarmas','listarAlarma'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'pro_concepto_s', type: 'string'},
                {name: 'alarma', type: 'string'},
        ])
});

var tipos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('alarmas', 'listarTipoAlarma'),
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
tipos_datastore_combo.load({
    callback: function(){ 
        tipos_datastore_combo.loadData({
            data: [{
                'codigo': '-1',
                'nombre': 'TODAS'
            }]
        }, true);
        tipos_combobox.setValue('-1');
        recargarDatosAlarmas();
    }
});

var tipos_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Tipo de Alarma',
    store: tipos_datastore_combo,
    mode: 'local',
    displayField: 'nombre',
    valueField: 'codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 200,
    listeners: {
        select: function(){
            recargarDatosAlarmas();
        }
    }
});

var recargarDatosAlarmas = function(callback){    
    redirigirSiSesionExpiro();
    if (tipos_combobox.isValid()) {
        crud_alarma_datastore.load({
            callback: callback,
            params: {
                'codigo_tipo': tipos_combobox.getValue()
            }
        });
    }
}    


var crud_alarma_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
            { header: "Tipo", width: 170, dataIndex: 'pro_concepto_s'},
            { header: "Descripci&oacute;n de la Alarma", width: 2500, dataIndex: 'alarma'},
]
});
	
var crud_alarma_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_alarma_gridpanel',            
            region:'center',
            title:'Alarmas Generadas',
//            stripeRows:true,
            frame: true,
            ds: crud_alarma_datastore,
            cm: crud_alarma_colmodel,
            height: largo_panel-85,
            loadMask: {
            msg: 'Cargando...'
            },
            bbar: new Ext.PagingToolbar({
                    pageSize: 15,
                    store: crud_alarma_datastore,
                    displayInfo: true,
                    displayMsg: 'Alarmas {0} - {1} de {2}',
                    emptyMsg: "No hay alarmas aun"
            })
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_alarma_contenedor_panel = new Ext.Panel({
        id: 'crud_alarma_contenedor_panel',
        title:'Configuración Alarmas Generadas',
        height: largo_panel,
        autoWidth: true,
        frame: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede visualizar las alarmas generadas',
        monitorResize:true,
        layout:'form',
        items: 
        [{
            border: false,
//            frame: true,
            height: 33,
            items: [{
                height: 50,
                layout: 'column',
                items: [{
                    layout: 'form',
                    labelWidth: 90,
                    footer: false,
                    items: [tipos_combobox]
                }, {
                    xtype: 'button',
                    iconCls: 'usuario_todos_icono',
                    text: 'Enviar Correo Electrónico',
                    style: 'padding: 0px 0px 0px 30px',
                    handler : function() {
                          Ext.Ajax.request({
                          url : getAbsoluteUrl('alarmas', 'enviarCorreoElectronicoAdmin'),
                          failure : function()
                          {
                              Ext.Msg.show(
                              {
                                title : 'Información',
                                msg : 'Error al enviar los correos electrónicos.',
                                buttons : Ext.Msg.OK,
                                icon : Ext.MessageBox.INFO
                              });
                          },
                          success : function(result)
                          {  
                                var mensaje = null;
                                switch(result.responseText)
                                {
                                  case 'Ok': mensaje = 'Los correos electrónicos fueron enviados correctamente.';
                                    break;                                  
                                  default: mensaje = 'Error al enviar los correos electrónicos.';
                                    break;
                                }
                                if(mensaje != null)
                                {
                                  Ext.Msg.show(
                                  {
                                    title : 'Información',
                                    msg : mensaje,
                                    buttons : Ext.Msg.OK,
                                    icon : Ext.MessageBox.INFO
                                  });
                                }                          
                          },
                          params : { }
                        });
                    }
                }]
            }]
        },
                crud_alarma_gridpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_alarma'
});