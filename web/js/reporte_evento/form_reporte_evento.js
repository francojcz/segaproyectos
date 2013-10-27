Ext.onReady(function(){

    var reporevent_analista_codigo_datastore = new Ext.data.JsonStore({
        id: 'reporevent_analista_codigo_datastore',
        url: getAbsoluteUrl('reporte_evento', 'listarAnalistas'),
        root: 'results',
        totalProperty: 'total',
        fields: [{
            name: 'empl_usu_codigo',
            type: 'string'
        }, {
            name: 'empl_nombre_completo',
            type: 'string'
        }, ]
    });
    reporevent_analista_codigo_datastore.load();
    
    
    var reporevent_analista_codigo_combobox = new Ext.form.ComboBox({
        xtype: 'combo',
        store: reporevent_analista_codigo_datastore,
        hiddenName: 'analista_codigo',
        name: 'reporevent_analista_codigo_combobox',
        id: 'reporevent_analista_codigo_combobox',
        mode: 'local',
        valueField: 'empl_usu_codigo',
        forceSelection: true,
        displayField: 'empl_nombre_completo',
        triggerAction: 'all',
        emptyText: 'Seleccione un analista...',
        selectOnFocus: true
    });
    
    
    var reporevent_maquina_codigo_datastore = new Ext.data.JsonStore({
        id: 'reporevent_maquina_codigo_datastore',
        url: getAbsoluteUrl('reporte_evento', 'listarEquiposActivos'),
        root: 'results',
        totalProperty: 'total',
        fields: [{
            name: 'maq_codigo',
            type: 'string'
        }, {
            name: 'maq_nombre',
            type: 'string'
        }, ]
    });
    reporevent_maquina_codigo_datastore.load();
    
    
    var reporevent_maquina_codigo_combobox = new Ext.form.ComboBox({
        xtype: 'combo',
        store: reporevent_maquina_codigo_datastore,
        hiddenName: 'maquina_codigo',
        name: 'reporevent_maquina_codigo_combobox',
        id: 'reporevent_maquina_codigo_combobox',
        mode: 'local',
        valueField: 'maq_codigo',
        forceSelection: true,
        displayField: 'maq_nombre',
        triggerAction: 'all',
        emptyText: 'Seleccione un equipo ó máquina...',
        selectOnFocus: true
    });
    
    
    var reporevent_metodo_codigo_datastore = new Ext.data.JsonStore({
        id: 'reporevent_metodo_codigo_datastore',
        url: getAbsoluteUrl('reporte_evento', 'listarMetodos'),
        root: 'results',
        totalProperty: 'total',
        fields: [{
            name: 'met_codigo',
            type: 'string'
        }, {
            name: 'met_nombre',
            type: 'string'
        }, ]
    });
    reporevent_metodo_codigo_datastore.load();
    
    
    var reporevent_metodo_codigo_combobox = new Ext.form.ComboBox({
        xtype: 'combo',
        store: reporevent_metodo_codigo_datastore,
        hiddenName: 'metodo_codigo',
        name: 'reporevent_metodo_codigo_combobox',
        id: 'reporevent_metodo_codigo_combobox',
        mode: 'local',
        valueField: 'met_codigo',
        forceSelection: true,
        displayField: 'met_nombre',
        triggerAction: 'all',
        emptyText: 'Seleccione un método...',
        selectOnFocus: true
    });
    
    
    var reporevent_categoriaevento_codigo_datastore = new Ext.data.JsonStore({
        url: getAbsoluteUrl('reporte_evento', 'listarCategoriaEventos'),
        root: 'results',
        totalProperty: 'total',
        fields: [{
            name: 'cat_codigo',
            type: 'string'
        }, {
            name: 'cat_nombre',
            type: 'string'
        }, ]
    });
    reporevent_categoriaevento_codigo_datastore.load();
    
    
    var reporevent_categoriaevento_codigo_combobox = new Ext.form.ComboBox({
        xtype: 'combo',
        store: reporevent_categoriaevento_codigo_datastore,
        hiddenName: 'categoriaevento_codigo',
        name: 'reporevent_categoriaevento_codigo_combobox',
        id: 'reporevent_categoriaevento_codigo_combobox',
        mode: 'local',
        valueField: 'cat_codigo',
        forceSelection: true,
        displayField: 'cat_nombre',
        triggerAction: 'all',
        emptyText: 'Seleccione un categoría...',
        selectOnFocus: true
    });
    
    var reporevent_desde_fecha_datefield = new Ext.form.DateField({
        xtype: 'datefield',
        format: 'Y-m-d'
    });
    
    var reporevent_hasta_fecha_datefield = new Ext.form.DateField({
        xtype: 'datefield',
        format: 'Y-m-d'
    });
    
    var reporevent_configuracion = new Ext.FormPanel({
        title: 'CONFIGURACI&Oacute;N DE REPORTE EVENTO',
        layout: 'form',
        monitorResize: true,
        frame: true,
        padding: 10,
        labelWidth: 0,
        items: [{
            xtype: 'compositefield',
            fieldLabel: '',
            hideLabel: true,
            items: [{
                xtype: 'displayfield',
                value: 'Desde'
            }, reporevent_desde_fecha_datefield, {
                xtype: 'displayfield',
                value: 'Hasta'
            }, reporevent_hasta_fecha_datefield, {
                xtype: 'displayfield',
                value: 'Categor&iacute;a'
            }, reporevent_categoriaevento_codigo_combobox]
        }, {
            xtype: 'compositefield',
            fieldLabel: '',
            hideLabel: true,
            items: [{
                xtype: 'displayfield',
                value: 'Analista'
            }, reporevent_analista_codigo_combobox, {
                xtype: 'displayfield',
                value: 'Equipo'
            }, reporevent_maquina_codigo_combobox, {
                xtype: 'displayfield',
                value: 'M&eacute;todo'
            }, reporevent_metodo_codigo_combobox, {
                text: 'Buscar',
                xtype: 'button',
                iconCls: 'filtrar',
                handler: function(){
                
                    var desde = '';
                    if (reporevent_desde_fecha_datefield.getValue() != '') {
                        desde = reporevent_desde_fecha_datefield.getValue().format('Y-m-d');
                    }
                    var hasta = '';
                    if (reporevent_hasta_fecha_datefield.getValue() != '') {
                        hasta = reporevent_hasta_fecha_datefield.getValue().format('Y-m-d');
                    }
                    
                    reporevent_datastore.reload({
                        params: {
                            maquina_codigo: reporevent_maquina_codigo_combobox.getValue(),
                            metodo_codigo: reporevent_metodo_codigo_combobox.getValue(),
                            analista_codigo: reporevent_analista_codigo_combobox.getValue(),
                            categoria_codigo: reporevent_categoriaevento_codigo_combobox.getValue(),
                            desde_fecha: desde,
                            hasta_fecha: hasta
                        }
                    });
                    
                    reporevent_cargardatosreportes();
                }
            }]
        }],
        renderTo: 'div_form_reporte_evento'
    });
    
    
    
    var reporevent_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_evento', 'listarReporteEventoEnRegistro'),
            method: 'POST'
        }),
        baseParams: {},
        reader: new Ext.data.JsonReader({
            root: 'results',
            totalProperty: 'total'
        }, [{
            name: 'evrg_maquina',
            type: 'string'
        }, {
            name: 'evrg_analista',
            type: 'string'
        }, {
            name: 'evrg_metodo',
            type: 'string'
        }, {
            name: 'evrg_fecha',
            type: 'string'
        }, {
            name: 'evrg_eve_nombre',
            type: 'string'
        }, {
            name: 'evrg_duracion',
            type: 'string'
        }, {
            name: 'evrg_observaciones',
            type: 'string'
        }, {
            name: 'evrg_hora_ocurrio',
            type: 'string'
        }, {
            name: 'evrg_hora_registro',
            type: 'string'
        }])
    });
    reporevent_datastore.load();
    
    
    var reporevent_colmodel = new Ext.grid.ColumnModel({
        defaults: {
            sortable: true,
            locked: false,
            resizable: true
        },
        columns: [{
            header: "M&aacute;quina",
            width: 110,
            align : 'center',
            dataIndex: 'evrg_maquina'
        }, {
            header: "Analista",
            width: 110,
            align : 'center',
            dataIndex: 'evrg_analista'
        }, {
            header: "M&eacute;todo",
            width: 100,
            align : 'center',
            dataIndex: 'evrg_metodo'
        }, {
            header: "Fecha",
            width: 70,
            align : 'center',
            dataIndex: 'evrg_fecha'
        }, {
            header: "Evento",
            width: 100,
            align : 'center',
            dataIndex: 'evrg_eve_nombre'
        },{
            header: "Hora",
            width: 70,
            align : 'center',
            dataIndex: 'evrg_hora_ocurrio'
        },{
            header: "Duraci&oacute;n (min.)",
            width: 100,
            align : 'center',
            dataIndex: 'evrg_duracion'
        },{
            header: "Hora registro",
            width: 90,
            align : 'center',
            dataIndex: 'evrg_hora_registro'
        },{
            header: "Observaci&oacute;n",
            width: 170,
            align : 'center',
            dataIndex: 'evrg_observaciones'
        }]
    });
    
    var reporevent_gridpanel = new Ext.grid.GridPanel({
        title: 'Eventos Ocurridos',
        columnWidth: '.6',
        region: 'center',
        stripeRows: true,
        frame: true,
        ds: reporevent_datastore,
        cm: reporevent_colmodel,
        height: 400
    });
    
    var reporevent_panel = new Ext.Panel({
        frame: true,
        monitorResize: true,
        layout: 'border',
        monitorResize: true,
        items: [reporevent_gridpanel, {
            xtype: 'tabpanel',
            activeTab: 0,
            title: 'Gr&aacute;ficos estadisticos',
            region: 'east',
            width: 500,
            minWidth: 500,
            collapsible: true,
            split: true,
            deferredRender: false,
            items: [{
                xtype: 'panel',
                title: 'Eventos / Cantidad',
                contentEl: 'div_reporte_evento_barra'
            }, {
                xtype: 'panel',
                title: 'Eventos / Tiempo',
                contentEl: 'div_reporte_evento_torta'
            }],
            listeners: {
                tabchange: function(){
                    redirigirSiSesionExpiro();
                }
            }
        }]  ,
        height: 450,
        renderTo: 'div_form_reporte_evento'
    });
    
    function reporevent_cargardatosreportes(){
        redirigirSiSesionExpiro();
        
        var maquina_codigo = reporevent_maquina_codigo_combobox.getValue();
        var metodo_codigo = reporevent_metodo_codigo_combobox.getValue();
        var analista_codigo = reporevent_analista_codigo_combobox.getValue();
        var categoria_codigo = reporevent_categoriaevento_codigo_combobox.getValue();
        
        
        var desde = '';
        if (reporevent_desde_fecha_datefield.getValue() != '') {
            desde = reporevent_desde_fecha_datefield.getValue().format('Y-m-d');
        }
        var hasta = '';
        if (reporevent_hasta_fecha_datefield.getValue() != '') {
            hasta = reporevent_hasta_fecha_datefield.getValue().format('Y-m-d');
        }
        
        var params = '?maquina_codigo=' + maquina_codigo + '&metodo_codigo=' + metodo_codigo + '&analista_codigo=' + analista_codigo;
        params += '&desde_fecha=' + desde + '&hasta_fecha=' + hasta + '&categoria_codigo=' + categoria_codigo;
        
        //tiempos
        
        var reporevent_tiempo_torta = new SWFObject(urlWeb + "flash/amcolumn/amcolumn.swf", "ampie", "520", "400", "8", "#FFFFFF");
        reporevent_tiempo_torta.addVariable("path", urlWeb + "flash/amcolumn/");
        reporevent_tiempo_torta.addParam("wmode", "opaque");
        reporevent_tiempo_torta.addVariable("settings_file", urlWeb + 'js/reporte_evento/amcolumn_st_grafico_evento_tiempo.php');
        reporevent_tiempo_torta.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('reporte_evento', 'generarDatosOcurrenciaEventosTiempo') + params));
        reporevent_tiempo_torta.write("div_reporte_evento_torta");
        
        
        var reporevent_tiempo_barra = new SWFObject(urlWeb + "flash/amcolumn/amcolumn.swf", "amcolumn", "520", "400", "8", "#FFFFFF");
        reporevent_tiempo_barra.addVariable("path", urlWeb + "flash/amcolumn/");
        reporevent_tiempo_barra.addParam("wmode", "opaque");
        reporevent_tiempo_barra.addVariable("settings_file", urlWeb + 'js/reporte_evento/amcolumn_st_grafico_evento_barras.php');
        reporevent_tiempo_barra.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('reporte_evento', 'generarDatosOcurrenciaEventosBarra') + params));
        reporevent_tiempo_barra.write("div_reporte_evento_barra");
        
    }
    
    reporevent_cargardatosreportes();
    
    
    function obtenerAncho(cmp, v_default){
        var ancho = cmp.getWidth();
        //alert(ancho);
        if (ancho > v_default) {
            return ancho;
        }
        else {
            return v_default;
        }
    }
    
    function reporevent_ajustarTamano(){
        var anchor1 = obtenerAncho(reporevent_panel, 800);
        if (anchor1 == 800) {
            reporevent_panel.setWidth(anchor1);
            reporevent_panel.doLayout();
            reporevent_configuracion.setWidth(anchor1);
            reporevent_configuracion.doLayout();
        }
    }
    reporevent_ajustarTamano();
    
});
