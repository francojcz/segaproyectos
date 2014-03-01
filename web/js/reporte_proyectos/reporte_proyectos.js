var largo_panel=500;

var crud_proyecto_datastore = new Ext.data.Store({
id: 'crud_proyecto_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_proyectos','listarProyecto'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'pro_nombre', type: 'string'},
                {name: 'pro_codigo_contable', type: 'string'},
                {name: 'pro_coord_persona_nombre', type: 'string'},
                {name: 'pro_estado_nombre', type: 'string'},
                {name: 'pro_acumulado_ingresos', type: 'string'},
                {name: 'pro_acumulado_egresos', type: 'string'},
                {name: 'pro_disponible', type: 'string'},
                {name: 'pro_descripcion', type: 'string'},
                {name: 'pro_valor', type: 'string'},
                {name: 'pro_fecha_inicio', type: 'string'},
                {name: 'pro_fecha_fin', type: 'string'},
                {name: 'pro_observaciones', type: 'string'},
                {name: 'pro_fecha_registro', type: 'string'},
                {name: 'pro_usuario_nombre', type: 'string'},
                {name: 'pro_presupuesto_url', type: 'string'}
        ])
});

var crud_proyecto_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
            { header: "Nombre del Proyecto", width: 250, dataIndex: 'pro_nombre'},
            { header: "Centro de Costo", width: 100, dataIndex: 'pro_codigo_contable'},
            { header: "Coordinador", width: 220, dataIndex: 'pro_coord_persona_nombre'},
            { header: "Estado del Proyecto", width: 130, dataIndex: 'pro_estado_nombre'},
            { header: "Valor del Proyecto", width: 100, dataIndex: 'pro_valor'},
            { header: "Ingresos Acumulados", width: 130, dataIndex: 'pro_acumulado_ingresos'},
            { header: "Egresos Acumulados", width: 130, dataIndex: 'pro_acumulado_egresos'},
            { header: "Total Disponible", width: 100, dataIndex: 'pro_disponible'},
            { header: "Descripci&oacute;n del Proyecto", width: 300, dataIndex: 'pro_descripcion'},            
            { header: "Fecha de inicio", width: 100, dataIndex: 'pro_fecha_inicio'},
            { header: "Fecha de finalizaci&oacute;n", width: 130, dataIndex: 'pro_fecha_fin'},
            { header: "Observaciones", width: 300, dataIndex: 'pro_observaciones'},
            { header: "Creado por", width: 220, dataIndex: 'pro_usuario_nombre'},
            { header: "Fecha de registro", width: 130, dataIndex: 'pro_fecha_registro'}            
]
});

var personas_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_proyectos', 'listarCoordinador'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'coord_codigo',
            type: 'string'
        }, {
            name: 'coord_nombre',
            type: 'string'
        }])
    });
personas_datastore_combo.load({
    callback: function(){ 
        personas_datastore_combo.loadData({
            data: [{
                'coord_codigo': '-1',
                'coord_nombre': 'TODOS'
            }]
        }, true);
        personas_combobox.setValue('-1');
        recargarDatosProyectos();
    }
});

var estados_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_proyectos', 'listarEstadoProyecto'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'est_proy_codigo',
            type: 'string'
        }, {
            name: 'est_proy_nombre',
            type: 'string'
        }])
    });
estados_datastore_combo.load({
    callback: function(){ 
        estados_datastore_combo.loadData({
            data: [{
                'est_proy_codigo': '-1',
                'est_proy_nombre': 'TODOS'
            }]
        }, true);
        estados_combobox.setValue('-1');
        recargarDatosProyectos();
    }
});

var personas_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Coordinador',
    store: personas_datastore_combo,
    mode: 'local',
    displayField: 'coord_nombre',
    valueField: 'coord_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 200,
    listeners: {
        select: function(){
            recargarDatosProyectos();
        }
    }
});

var estados_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Estado del Proyecto',
    store: estados_datastore_combo,
    mode: 'local',
    displayField: 'est_proy_nombre',
    valueField: 'est_proy_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    width: 200,
    listeners: {
        select: function(){
            recargarDatosProyectos();
        }
    }
});

var recargarDatosProyectos = function(callback){    
    redirigirSiSesionExpiro();
    if (personas_combobox.isValid() && estados_combobox.isValid()) {
        crud_proyecto_datastore.load({
            callback: callback,
            params: {
                'codigo_pers': personas_combobox.getValue(),
                'codigo_est_proy': estados_combobox.getValue()
            }
        });
    }
}
	
var crud_proyecto_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_proyecto_gridpanel',            
            region:'center',
            title:'Proyectos registrados',
//            stripeRows:true,
            frame: true,
            ds: crud_proyecto_datastore,
            cm: crud_proyecto_colmodel,
            height: largo_panel-85,
            loadMask: {
            msg: 'Cargando...'
            }
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_proyecto_contenedor_panel = new Ext.Panel({
        id: 'crud_proyecto_contenedor_panel',
        title:'Configuración Proyectos',
        height: largo_panel,
        autoWidth: true,
        frame: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede visualizar los proyectos registrados',
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
                    labelWidth: 90,
                    footer: false,
                    items: [personas_combobox]
                }, {
                    layout: 'form',
                    labelWidth: 130,
                    footer: false,
                    style: 'padding: 0px 0px 0px 10px',
                    items: [estados_combobox]
                }, {
                    xtype: 'button',
                    iconCls : 'abrir_manual',
                    text : 'Archivo con Presupuesto',
                    tooltip: 'Descargar el archivo con presupuesto del proyecto',                    
                    style: 'padding: 0px 0px 0px 20px',
                    handler : function()
                    {
                        var sm = crud_proyecto_gridpanel.getSelectionModel();
                        if(sm.hasSelection())
                        {
                            var registro = sm.getSelected();
                            if(registro.get('pro_presupuesto_url') === ''){
                                Ext.Msg.show(
                                  {
                                    title : 'Información',
                                    msg : 'No se ha registrado ningún archivo',
                                    buttons : Ext.Msg.OK,
                                    icon : Ext.MessageBox.INFO
                                  });
                            }else {
                                window.open(urlWeb + registro.get('pro_presupuesto_url'));
                            }  
                        } else
                        {
                          Ext.Msg.show(
                          {
                            title : 'Información',
                            msg : 'Primero debe seleccionar un proyecto',
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
                          url : getAbsoluteUrl('reporte_proyectos', 'exportarReporte'),
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
                            'codigo_est_proy': estados_combobox.getValue()
                          }
                        });
                    }
                }
                ]
            }]
        },
                crud_proyecto_gridpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_reporte_proyectos'
});