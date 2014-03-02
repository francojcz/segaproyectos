var largo_panel=450;

var crud_participante_datastore = new Ext.data.Store({
id: 'crud_participante_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_participantes','listarParticipante'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'par_persona', type: 'string'},
                {name: 'par_proyecto', type: 'string'},
                {name: 'par_usuario', type: 'string'},                
                {name: 'par_fecha_registro', type: 'string'}
        ])
});

var crud_participante_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
            { header: "Nombre de la Persona", width: 300, dataIndex: 'par_persona'},
            { header: "Nombre del Proyecto", width: 500, dataIndex: 'par_proyecto'},
            { header: "Asignado por", width: 230, dataIndex: 'par_usuario'},
            { header: "Fecha de asignaci&oacute;n", width: 150, dataIndex: 'par_fecha_registro'}            
]
});

var personas_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_participantes', 'listarPersona'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'pers_codigo',
            type: 'string'
        }, {
            name: 'pers_nombre',
            type: 'string'
        }])
    });
personas_datastore_combo.load({
    callback: function(){ 
        personas_datastore_combo.loadData({
            data: [{
                'pers_codigo': '-1',
                'pers_nombre': 'TODOS'
            }]
        }, true);
        personas_combobox.setValue('-1');
        recargarDatosParticipantes();
    }
});

var proyectos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_participantes', 'listarProyecto'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'proyec_codigo',
            type: 'string'
        }, {
            name: 'proyec_nombre',
            type: 'string'
        }])
    });
proyectos_datastore_combo.load({
    callback: function(){ 
        proyectos_datastore_combo.loadData({
            data: [{
                'proyec_codigo': '-1',
                'proyec_nombre': 'TODOS'
            }]
        }, true);
        proyectos_combobox.setValue('-1');
        recargarDatosParticipantes();
    }
});

var personas_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Persona',
    store: personas_datastore_combo,
    mode: 'local',
    displayField: 'pers_nombre',
    valueField: 'pers_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 250,
    listeners: {
        select: function(){
            recargarDatosParticipantes();
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
    displayField: 'proyec_nombre',
    valueField: 'proyec_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 250,
    listeners: {
        select: function(){
            recargarDatosParticipantes();
        }
    }
});

var recargarDatosParticipantes = function(callback){    
    redirigirSiSesionExpiro();
    if (personas_combobox.isValid() && proyectos_combobox.isValid()) {
        crud_participante_datastore.load({
            callback: callback,
            params: {
                'codigo_pers': personas_combobox.getValue(),
                'codigo_proy': proyectos_combobox.getValue()
            }
        });
    }
}
	
var crud_participante_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_participante_gridpanel',            
            region:'center',
            title:'Participantes asignados',
//            stripeRows:true,
            frame: true,
            ds: crud_participante_datastore,
            cm: crud_participante_colmodel,
            height: largo_panel-85,
            loadMask: {
            msg: 'Cargando...'
            }
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_participante_contenedor_panel = new Ext.Panel({
        id: 'crud_participante_contenedor_panel',
        title:'Configuración Participantes',
        height: largo_panel,
        autoWidth: true,
        frame: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede visualizar los participantes registrados',
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
                    items: [personas_combobox]
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
                          url : getAbsoluteUrl('reporte_participantes', 'exportarReporte'),
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
                            'codigo_pers': personas_combobox.getValue(),
                            'codigo_proy': proyectos_combobox.getValue()
                          }
                        });
                    }
                }
                ]
            }]
        },
                crud_participante_gridpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_reporte_participantes'
});