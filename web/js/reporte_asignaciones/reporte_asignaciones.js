var largo_panel=450;

var crud_asignacion_datastore = new Ext.data.Store({
id: 'crud_asignacion_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_asignaciones','listarAsignacionTiempo'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'adt_proyecto_nombre', type: 'string'},
                {name: 'adt_enero', type: 'string'},
                {name: 'adt_febrero', type: 'string'},
                {name: 'adt_marzo', type: 'string'},
                {name: 'adt_abril', type: 'string'},
                {name: 'adt_mayo', type: 'string'},
                {name: 'adt_junio', type: 'string'},
                {name: 'adt_julio', type: 'string'},
                {name: 'adt_agosto', type: 'string'},
                {name: 'adt_septiembre', type: 'string'},
                {name: 'adt_octubre', type: 'string'},
                {name: 'adt_noviembre', type: 'string'},
                {name: 'adt_diciembre', type: 'string'}
        ])
});

var crud_asignacion_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
            { header: "Nombre del Proyecto", width: 300, dataIndex: 'adt_proyecto_nombre'},
            { header: "Enero", width: 65, dataIndex: 'adt_enero'},
            { header: "Febrero", width: 65, dataIndex: 'adt_febrero'},
            { header: "Marzo", width: 65, dataIndex: 'adt_marzo'},
            { header: "Abril", width: 65, dataIndex: 'adt_abril'},
            { header: "Mayo", width: 65, dataIndex: 'adt_mayo'},
            { header: "Junio", width: 65, dataIndex: 'adt_junio'},
            { header: "Julio", width: 65, dataIndex: 'adt_julio'},
            { header: "Agosto", width: 75, dataIndex: 'adt_agosto'},
            { header: "Septiembre", width: 75, dataIndex: 'adt_septiembre'},
            { header: "Octubre", width: 75, dataIndex: 'adt_octubre'},
            { header: "Noviembre", width: 75, dataIndex: 'adt_noviembre'},
            { header: "Diciembre", width: 75, dataIndex: 'adt_diciembre'}
]
});

var anos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_asignaciones', 'listarAno'),
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
        anos_combobox.setValue('2014');
        recargarDatosAsignaciones();
    }
});

var personas_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_asignaciones', 'listarPersona'),
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
//        personas_combobox.setValue('1');
        recargarDatosAsignaciones();
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
            recargarDatosAsignaciones();
        }
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
    emptyText: 'Seleccione una Persona',
    selectOnFocus: true,
    forceSelection: true,
    width: 250,
    listeners: {
        select: function(){
            recargarDatosAsignaciones();
        }
    }
});

var recargarDatosAsignaciones = function(callback){    
    redirigirSiSesionExpiro();
    if (anos_combobox.isValid() && personas_combobox.isValid()) {
        crud_asignacion_datastore.load({
            callback: callback,
            params: {
                'ano': anos_combobox.getValue(),
                'codigo_pers': personas_combobox.getValue()
            }
        });
    }
}
	
var crud_asignacion_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_asignacion_gridpanel',            
            region:'center',
            title:'Asignaci&oacute;n de tiempos registrado',
//            stripeRows:true,
            frame: true,
            ds: crud_asignacion_datastore,
            cm: crud_asignacion_colmodel,
            height: largo_panel-85,
            loadMask: {
            msg: 'Cargando...'
            }
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_asignacion_contenedor_panel = new Ext.Panel({
        id: 'crud_asignacion_contenedor_panel',
        title:'Configuración Asignación de Tiempos',
        height: largo_panel,
        autoWidth: true,
        frame: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede visualizar lass asignaciones registradas',
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
                    labelWidth: 40,
                    footer: false,
                    items: [anos_combobox]
                }, {
                    layout: 'form',
                    labelWidth: 90,
                    footer: false,
                    style: 'padding: 0px 0px 0px 30px',
                    items: [personas_combobox]
                }, {
                    xtype: 'button',
                    iconCls : 'exportar_pdf',
                    text : 'Exportar a PDF',
                    tooltip: 'Descargar la información en formato PDF',                    
                    style: 'padding: 0px 0px 0px 20px',
                    handler : function() {
                          Ext.Ajax.request({
                          url : getAbsoluteUrl('reporte_asignaciones', 'exportarReporte'),
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
                            'ano': anos_combobox.getValue(),
                            'codigo_pers': personas_combobox.getValue()
                          }
                        });
                    }
                }
                ]
            }]
        },
                crud_asignacion_gridpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_reporte_asignaciones'
});