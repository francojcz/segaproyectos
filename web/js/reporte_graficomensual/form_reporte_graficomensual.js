Ext.onReady(function(){

    var reporgrafmens_analista_codigo_datastore = new Ext.data.JsonStore({
        id: 'reporgrafmens_analista_codigo_datastore',
        url: getAbsoluteUrl('reporte_graficomensual', 'listarAnalistas'),
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
    reporgrafmens_analista_codigo_datastore.load();
    
    
    var reporgrafmens_analista_codigo_combobox = new Ext.form.ComboBox({
        xtype: 'combo',
        store: reporgrafmens_analista_codigo_datastore,
        hiddenName: 'analista_codigo',
        name: 'reporgrafmens_analista_codigo_combobox',
        id: 'reporgrafmens_analista_codigo_combobox',
        mode: 'local',
        valueField: 'empl_usu_codigo',
        forceSelection: true,
        displayField: 'empl_nombre_completo',
        triggerAction: 'all',
        emptyText: 'Seleccione un analista...',
        selectOnFocus: true,
        //        fieldLabel: 'Analista',
        hideLabel: true
    });
    
    
    var reporgrafmens_maquina_codigo_datastore = new Ext.data.JsonStore({
        id: 'reporgrafmens_maquina_codigo_datastore',
        url: getAbsoluteUrl('reporte_graficomensual', 'listarEquiposActivos'),
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
    reporgrafmens_maquina_codigo_datastore.load();
    
    
    var reporgrafmens_maquina_codigo_combobox = new Ext.form.ComboBox({
        xtype: 'combo',
        store: reporgrafmens_maquina_codigo_datastore,
        hiddenName: 'maquina_codigo',
        name: 'reporgrafmens_maquina_codigo_combobox',
        id: 'reporgrafmens_maquina_codigo_combobox',
        mode: 'local',
        valueField: 'maq_codigo',
        forceSelection: true,
        displayField: 'maq_nombre',
        triggerAction: 'all',
        emptyText: 'Seleccione un equipo ó máquina...',
        selectOnFocus: true
        //        fieldLabel: 'Equipo',
    });
    
    
    var reporgrafmens_metodo_codigo_datastore = new Ext.data.JsonStore({
        id: 'reporgrafmens_metodo_codigo_datastore',
        url: getAbsoluteUrl('reporte_graficomensual', 'listarMetodos'),
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
    reporgrafmens_metodo_codigo_datastore.load();
    
    
    var reporgrafmens_metodo_codigo_combobox = new Ext.form.ComboBox({
        xtype: 'combo',
        store: reporgrafmens_metodo_codigo_datastore,
        hiddenName: 'metodo_codigo',
        name: 'reporgrafmens_metodo_codigo_combobox',
        id: 'reporgrafmens_metodo_codigo_combobox',
        mode: 'local',
        valueField: 'met_codigo',
        forceSelection: true,
        displayField: 'met_nombre',
        triggerAction: 'all',
        emptyText: 'Seleccione un método...',
        selectOnFocus: true,
        //        fieldLabel: 'M&eacute;todo'
        hideLabel: true
    });
    
    var reporgrafmens_fecha = new Date();
    var reporgrafmens_anio = new Ext.form.SpinnerField({
        xtype: 'spinnerfield',
        //        fieldLabel: 'A&ntilde;o',
        id: 'reporgrafmens_anio',
        name: 'reporgrafmens_anio',
        minValue: reporgrafmens_fecha.getFullYear() - 10,
        maxValue: reporgrafmens_fecha.getFullYear(),
        value: reporgrafmens_fecha.getFullYear(),
        width: 80,
        accelerate: true,
        listeners: {
            'spin': function(){
            }
        },
        hideLabel: true
    });
    
    
    var reporgrafmens_mes_data = [['01', 'Enero'], ['02', 'Febrero'], ['03', 'Marzo'], ['04', 'Abril'], ['05', 'Mayo'], ['06', 'Junio'], ['07', 'Julio'], ['08', 'Agosto'], ['09', 'Septiembre'], ['10', 'Octubre'], ['11', 'Noviembre'], ['12', 'Diciembre']];
    
    var reporgrafmens_mes_store = new Ext.data.ArrayStore({
        fields: [{
            name: 'mes_codigo'
        }, {
            name: 'mes_nombre'
        }]
    });
    reporgrafmens_mes_store.loadData(reporgrafmens_mes_data);
    
    function obtenerMesActual(){
        if (reporgrafmens_fecha.getMonth() < 9) {
            return '0' + (reporgrafmens_fecha.getMonth() + 1)
        }
        else {
            return (reporgrafmens_fecha.getMonth() + 1);
        }
    }
    var reporgrafmens_mes_combobox = new Ext.form.ComboBox({
        xtype: 'combo',
        store: reporgrafmens_mes_store,
        hiddenName: 'mes',
        name: 'reporgrafmens_mes_combobox',
        id: 'reporgrafmens_mes_combobox',
        mode: 'local',
        valueField: 'mes_codigo',
        forceSelection: true,
        displayField: 'mes_nombre',
        triggerAction: 'all',
        emptyText: 'Seleccione un mes...',
        selectOnFocus: true,
        //        fieldLabel: 'Mes',
        value: obtenerMesActual(),
        hideLabel: true
    });
    
    
    var reporgrafmens_configuracion = new Ext.FormPanel({
        //        title: 'CONFIGURACI&Oacute;N DE REPORTE MENSUAL',
        layout: 'form',
        frame: true,
        padding: 10,
        labelWidth: 100,
        items: [{
            xtype: 'compositefield',
            //            fieldLabel: 'Periodo',
            hideLabel: true,
            items: [{
                xtype: 'displayfield',
                html: 'A&ntilde;o'
            }, reporgrafmens_anio, {
                xtype: 'displayfield',
                html: 'Mes'
            }, reporgrafmens_mes_combobox]
        }, {
            xtype: 'compositefield',
            //            fieldLabel: 'Otra Informaci&oacute;n',
            hideLabel: true,
            items: [{
                xtype: 'displayfield',
                value: 'Analista'
            }, reporgrafmens_analista_codigo_combobox, {
                xtype: 'displayfield',
                value: 'Equipo'
            }, reporgrafmens_maquina_codigo_combobox, {
                xtype: 'displayfield',
                value: 'M&eacute;todo'
            }, reporgrafmens_metodo_codigo_combobox, {
                text: 'Generar gr&aacute;ficos',
                xtype: 'button',
                iconCls: 'reload',
                handler: function(){
                    //recargar charts
                    reporgrafmens_cargardatosreportes();
                }
            }]
        }],
        renderTo: 'div_form_reporte_graficomensual'
    });
    
    var reporgrafmens_reportes_tabpanel = new Ext.TabPanel({
        frame: true,
        items: [{
            xtype: 'panel',
            title: 'Tiempos',
            layout: 'column',
            autoScroll: true,
            monitorResize: true,
            items: [{
                columnWidth: '.5',
                contentEl: 'div_reporte_graficomensual_tiempos_dispersion'
            }, {
                columnWidth: '.5',
                contentEl: 'div_reporte_graficomensual_tiempos_torta'
            }]
        }, {
            xtype: 'panel',
            title: 'Indicadores',
            layout: 'column',
            autoScroll: true,
            monitorResize: true,
            items: [{
                columnWidth: '.5',
                contentEl: 'div_reporte_graficomensual_indicadores_dispersion'
            }, {
                columnWidth: '.5',
                contentEl: 'div_reporte_graficomensual_indicadores_barras'
            }]
        }, {
            xtype: 'panel',
            title: 'P&eacute;rdidas',
            layout: 'column',
            autoScroll: true,
            monitorResize: true,
            items: [{
                columnWidth: '.5',
                contentEl: 'div_reporte_graficomensual_perdidas_dispersion'
            }, {
                columnWidth: '.5',
                contentEl: 'div_reporte_graficomensual_perdidas_torta'
            }]
        }, {
            xtype: 'panel',
            title: 'Muestras e inyecciones',
            layout: 'column',
            autoScroll: true,
            monitorResize: true,
            items: [{
                columnWidth: '.5',
                contentEl: 'div_reporte_graficomensual_muestras_dispersion'
            }, {
                columnWidth: '.5',
                contentEl: 'div_reporte_graficomensual_inyecciones_dispersion'
            }]
        }],
        activeTab: 0,
        height: 430,
        deferredRender: false,
        renderTo: 'div_form_reporte_graficomensual',
        listeners: {
            tabchange: function(){
                redirigirSiSesionExpiro();
            }
        }
    });
    
    function reporgrafmens_cargardatosreportes(){
        redirigirSiSesionExpiro();
        
        var mes = reporgrafmens_mes_combobox.getValue();
        var anio = reporgrafmens_anio.getValue();
        var maquina_codigo = reporgrafmens_maquina_codigo_combobox.getValue();
        var metodo_codigo = reporgrafmens_metodo_codigo_combobox.getValue();
        var analista_codigo = reporgrafmens_analista_codigo_combobox.getValue();
        
        var params = '?mes=' + mes + '&anio=' + anio + '&maquina_codigo=' + maquina_codigo + '&metodo_codigo=' + metodo_codigo + '&analista_codigo=' + analista_codigo;
        //tiempos
        var reporgrafmens_tiempos_dispersion = new SWFObject(urlWeb + "flash/amline/amline.swf", "amline", "520", "400", "8", "#FFFFFF");
        reporgrafmens_tiempos_dispersion.addVariable("path", urlWeb + "flash/amline/");
        reporgrafmens_tiempos_dispersion.addParam("wmode", "opaque");
        reporgrafmens_tiempos_dispersion.addVariable("settings_file", urlWeb + 'js/reporte_graficomensual/amline_st_grafico_tiempos.php');
        reporgrafmens_tiempos_dispersion.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('reporte_graficomensual', 'generarDatosGraficoTiempos') + params));
        reporgrafmens_tiempos_dispersion.addVariable("loading_data", "... CARGANDO ...");
        reporgrafmens_tiempos_dispersion.write("div_reporte_graficomensual_tiempos_dispersion");
        
        var reporgrafmens_tiempos_torta = new SWFObject(urlWeb + "flash/ampie/ampie.swf", "ampie", "520", "400", "8");
        reporgrafmens_tiempos_torta.addVariable("path", urlWeb + "flash/ampie/");
        reporgrafmens_tiempos_torta.addParam("wmode", "opaque");
        reporgrafmens_tiempos_torta.addVariable("settings_file", urlWeb + 'js/reporte_graficomensual/ampie_st_grafico_tiempos_torta.php');
        reporgrafmens_tiempos_torta.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('reporte_graficomensual', 'generarDatosGraficoTiemposTorta') + params));
        reporgrafmens_tiempos_torta.addVariable("loading_data", "... CARGANDO ...");
        reporgrafmens_tiempos_torta.write("div_reporte_graficomensual_tiempos_torta");
        
        //indicadores
        var reporgrafmens_indicadores_dispersion = new SWFObject(urlWeb + "flash/amline/amline.swf", "amline", "520", "400", "8", "#FFFFFF");
        reporgrafmens_indicadores_dispersion.addVariable("path", urlWeb + "flash/amline/");
        reporgrafmens_indicadores_dispersion.addParam("wmode", "opaque");
        reporgrafmens_indicadores_dispersion.addVariable("settings_file", urlWeb + 'js/reporte_graficomensual/amline_st_grafico_indicadores.php');
        reporgrafmens_indicadores_dispersion.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('reporte_graficomensual', 'generarDatosGraficoIndicadores') + params));
        reporgrafmens_indicadores_dispersion.addVariable("loading_data", "... CARGANDO ...");
        reporgrafmens_indicadores_dispersion.write("div_reporte_graficomensual_indicadores_dispersion");
        
        
        var reporgrafmens_indicadores_barras = new SWFObject(urlWeb + "flash/amcolumn/amcolumn.swf", "amcolumn", "520", "400", "8", "#FFFFFF");
        reporgrafmens_indicadores_barras.addVariable("path", urlWeb + "flash/amcolumn/");
        reporgrafmens_indicadores_barras.addParam("wmode", "opaque");
        reporgrafmens_indicadores_barras.addVariable("settings_file", urlWeb + 'js/reporte_graficomensual/amcolumn_st_grafico_indicadores_barras.php');
        reporgrafmens_indicadores_barras.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('reporte_graficomensual', 'generarDatosGraficoIndicadoresBarras') + params));
        reporgrafmens_indicadores_barras.addVariable("loading_data", "... CARGANDO ...");
        reporgrafmens_indicadores_barras.write("div_reporte_graficomensual_indicadores_barras");
        
        //perdidas
        var reporgrafmens_perdida_dispersion = new SWFObject(urlWeb + "flash/amline/amline.swf", "amline", "520", "400", "8", "#FFFFFF");
        reporgrafmens_perdida_dispersion.addVariable("path", urlWeb + "flash/amline/");
        reporgrafmens_perdida_dispersion.addParam("wmode", "opaque");
        reporgrafmens_perdida_dispersion.addVariable("settings_file", urlWeb + 'js/reporte_graficomensual/amline_st_grafico_perdidas.php');
        reporgrafmens_perdida_dispersion.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('reporte_graficomensual', 'generarDatosGraficoPerdidas') + params));
        reporgrafmens_perdida_dispersion.addVariable("loading_data", "... CARGANDO ...");
        reporgrafmens_perdida_dispersion.write("div_reporte_graficomensual_perdidas_dispersion");
        
        var reporgrafmens_tiempo_torta = new SWFObject(urlWeb + "flash/ampie/ampie.swf", "ampie", "520", "400", "8", "#FFFFFF");
        reporgrafmens_tiempo_torta.addVariable("path", urlWeb + "flash/ampie/");
        reporgrafmens_tiempo_torta.addParam("wmode", "opaque");//turco ee
        reporgrafmens_tiempo_torta.addVariable("settings_file", urlWeb + 'js/reporte_graficomensual/ampie_st_grafico_perdidas_torta.php');
        reporgrafmens_tiempo_torta.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('reporte_graficomensual', 'generarDatosGraficoPerdidasTorta') + params));
        reporgrafmens_tiempo_torta.write("div_reporte_graficomensual_perdidas_torta");
        
        //muestras
        var reporgrafmens_muestras_dispersion = new SWFObject(urlWeb + "flash/amline/amline.swf", "amline", "520", "400", "8", "#FFFFFF");
        reporgrafmens_muestras_dispersion.addVariable("path", urlWeb + "flash/amline/");
        reporgrafmens_muestras_dispersion.addParam("wmode", "opaque");
        reporgrafmens_muestras_dispersion.addVariable("settings_file", urlWeb + 'js/reporte_graficomensual/amline_st_grafico_muestras.php');
        reporgrafmens_muestras_dispersion.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('reporte_graficomensual', 'generarDatosGraficoMuestras') + params));
        reporgrafmens_muestras_dispersion.write("div_reporte_graficomensual_muestras_dispersion");
        
        var reporgrafmens_inyecciones_dispersion = new SWFObject(urlWeb + "flash/amline/amline.swf", "amline", "520", "400", "8", "#FFFFFF");
        reporgrafmens_inyecciones_dispersion.addVariable("path", urlWeb + "flash/amline/");
        reporgrafmens_inyecciones_dispersion.addParam("wmode", "opaque");
        reporgrafmens_inyecciones_dispersion.addVariable("settings_file", urlWeb + 'js/reporte_graficomensual/amline_st_grafico_inyecciones.php');
        reporgrafmens_inyecciones_dispersion.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('reporte_graficomensual', 'generarDatosGraficoInyecciones') + params));
        reporgrafmens_inyecciones_dispersion.write("div_reporte_graficomensual_inyecciones_dispersion");
    }
    
    reporgrafmens_cargardatosreportes();
    
    
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
    
    function reporgrafmens_reajustar(){
        var ancho = obtenerAncho(reporgrafmens_configuracion, 1024);
        if (ancho == 1024) {
            reporgrafmens_configuracion.setWidth(ancho);
            reporgrafmens_reportes_tabpanel.setWidth(ancho);
            
            reporgrafmens_configuracion.doLayout();
            reporgrafmens_reportes_tabpanel.doLayout();
        }
    }
    
    reporgrafmens_reajustar();
});
