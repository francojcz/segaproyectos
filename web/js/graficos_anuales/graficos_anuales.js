Ext.onReady(function(){
    var renderizarGraficos = function(){
        redirigirSiSesionExpiro();
        if (campoAnho.getValue() != '' && campoMaquina.getValue() != '') {
            var params = '?anho=' + campoAnho.getValue() + '&codigo_maquina=' + campoMaquina.getValue() + '&codigo_operario=' + campoOperario.getValue() + '&codigo_metodo=' + campoMetodo.getValue();
            
            var so = new SWFObject(urlWeb + "flash/amline/amline.swf", "amline", "500", "400", "8", "#FFFFFF");
            so.addVariable("path", urlWeb + "flash/amline/");
            so.addParam("wmode", "opaque");
            so.addVariable("settings_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarConfiguracionGraficoTiemposLineas')));
            so.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarDatosGraficoTiemposLineas' + params)));
            if (Ext.get('flashcontent1')) {
                so.write("flashcontent1");
            }
            
            var so = new SWFObject(urlWeb + "flash/ampie/ampie.swf", "ampie", "500", "400", "8", "#FFFFFF");
            so.addVariable("path", urlWeb + "flash/ampie/");
            so.addParam("wmode", "opaque");
            so.addVariable("settings_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarConfiguracionTortaTiempos')));
            so.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarDatosTortaTiempos' + params)));
            if (Ext.get('flashcontent2')) {
                so.write("flashcontent2");
            }
            
            var so = new SWFObject(urlWeb + "flash/amline/amline.swf", "amline", "500", "400", "8", "#FFFFFF");
            so.addVariable("path", urlWeb + "flash/amline/");
            so.addParam("wmode", "opaque");
            so.addVariable("settings_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarConfiguracionGraficoIndicadoresLineas')));
            so.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarDatosGraficoIndicadoresLineas' + params)));
            if (Ext.get('flashcontent3')) {
                so.write("flashcontent3");
            }
            
            var so = new SWFObject(urlWeb + "flash/amcolumn/amcolumn.swf", "amcolumn", "500", "400", "8", "#FFFFFF");
            so.addVariable("path", urlWeb + "flash/amcolumn/");
            so.addParam("wmode", "opaque");
            so.addVariable("settings_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarConfiguracionGraficoIndicadoresColumnas')));
            so.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarDatosGraficoIndicadoresColumnas' + params)));
            if (Ext.get('flashcontent4')) {
                so.write("flashcontent4");
            }
            
            var so = new SWFObject(urlWeb + "flash/amline/amline.swf", "amline", "500", "400", "8", "#FFFFFF");
            so.addVariable("path", urlWeb + "flash/amline/");
            so.addParam("wmode", "opaque");
            so.addVariable("settings_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarConfiguracionGraficoPerdidasLineas')));
            so.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarDatosGraficoPerdidasLineas' + params)));
            if (Ext.get('flashcontent5')) {
                so.write("flashcontent5");
            }
            
            var so = new SWFObject(urlWeb + "flash/ampie/ampie.swf", "ampie", "500", "400", "8", "#FFFFFF");
            so.addVariable("path", urlWeb + "flash/ampie/");
            so.addParam("wmode", "opaque");
            so.addVariable("settings_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarConfiguracionTortaPerdidas')));
            so.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarDatosTortaPerdidas' + params)));
            if (Ext.get('flashcontent6')) {
                so.write("flashcontent6");
            }
            
            var so = new SWFObject(urlWeb + "flash/amline/amline.swf", "amline", "500", "400", "8", "#FFFFFF");
            so.addVariable("path", urlWeb + "flash/amline/");
            so.addParam("wmode", "opaque");
            so.addVariable("settings_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarConfiguracionGraficoMuestrasLineas')));
            so.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarDatosGraficoMuestrasLineas' + params)));
            if (Ext.get('flashcontent7')) {
                so.write("flashcontent7");
            }
            
            var so = new SWFObject(urlWeb + "flash/amline/amline.swf", "amline", "500", "400", "8", "#FFFFFF");
            so.addVariable("path", urlWeb + "flash/amline/");
            so.addParam("wmode", "opaque");
            so.addVariable("settings_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarConfiguracionGraficoInyeccionesLineas')));
            so.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('graficos_anuales', 'generarDatosGraficoInyeccionesLineas' + params)));
            if (Ext.get('flashcontent8')) {
                so.write("flashcontent8");
            }
        }
    }
    
    var maquinas_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('graficos_anuales', 'listarEquiposActivos'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data',
        }, [{
            name: 'codigo',
            type: 'string'
        }, {
            name: 'nombre',
            type: 'string'
        }])
    });
    
    maquinas_datastore.load({
        callback: function(){
            maquinas_datastore.loadData({
                data: [{
                    'codigo': '-1',
                    'nombre': 'TODOS'
                }]
            }, true);
            campoMaquina.setValue('-1');
            renderizarGraficos();
        }
    });
    
    var metodos_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('graficos_anuales', 'listarMetodos'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data',
        }, [{
            name: 'codigo',
            type: 'string'
        }, {
            name: 'nombre',
            type: 'string'
        }])
    });
    
    metodos_datastore.load({
        callback: function(){
            metodos_datastore.loadData({
                data: [{
                    'codigo': '-1',
                    'nombre': 'TODOS'
                }]
            }, true);
            campoMetodo.setValue('-1');
            renderizarGraficos();
        }
    });
    
    var operarios_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('graficos_anuales', 'listarOperarios'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data',
        }, [{
            name: 'codigo',
            type: 'string'
        }, {
            name: 'nombre',
            type: 'string'
        }])
    });
    
    operarios_datastore.load({
        callback: function(){
            operarios_datastore.loadData({
                data: [{
                    'codigo': '-1',
                    'nombre': 'TODOS'
                }]
            }, true);
            campoOperario.setValue('-1');
            renderizarGraficos();
        }
    });
    
    //    var campoAnho = new Ext.form.NumberField({
    //        fieldLabel: 'Año'
    //    });
    
    var campoAnho = new Ext.ux.form.SpinnerField({
        fieldLabel: 'Año',
        value: (new Date()).getFullYear(),
        minValue: 0,
        maxValue: 9999,
        incrementValue: 1,
        width: 60
    });
    
    var campoMaquina = new Ext.form.ComboBox({
        fieldLabel: 'Equipo',
        store: maquinas_datastore,
        displayField: 'nombre',
        valueField: 'codigo',
        mode: 'local',
        triggerAction: 'all',
        forceSelection: true,
        allowBlank: false,
        width: 130
        //        ,
        //        listeners: {
        //            select: function(){
        //                renderizarGraficos();
        //            }
        //        }
    });
    
    var campoOperario = new Ext.form.ComboBox({
        fieldLabel: 'Analista',
        store: operarios_datastore,
        displayField: 'nombre',
        valueField: 'codigo',
        mode: 'local',
        triggerAction: 'all',
        forceSelection: true,
        allowBlank: false,
        width: 130
    });
    
    var campoMetodo = new Ext.form.ComboBox({
        fieldLabel: 'Método',
        store: metodos_datastore,
        displayField: 'nombre',
        valueField: 'codigo',
        mode: 'local',
        triggerAction: 'all',
        forceSelection: true,
        allowBlank: false,
        width: 130
    });
    
    var panelGraficoTiemposAnual = new Ext.FormPanel({
        renderTo: 'panel_graficos_anuales',
        border: true,
        frame: true,
        items: [{
            layout: 'column',
            bodyStyle: 'padding-top:10px; padding-bottom:10px;',
            items: [{
                layout: 'form',
                labelWidth: 30,
                bodyStyle: 'padding-right:50px;',
                items: [campoAnho]
            }, {
                layout: 'form',
                labelWidth: 50,
                bodyStyle: 'padding-right:50px;',
                items: [campoMaquina]
            }, {
                layout: 'form',
                labelWidth: 50,
                bodyStyle: 'padding-right:50px;',
                items: [campoOperario]
            }, {
                layout: 'form',
                labelWidth: 50,
                bodyStyle: 'padding-right:50px;',
                items: [campoMetodo]
            }, {
                layout: 'form',
                items: [{
                    xtype: 'button',
                    text: 'Generar gr&aacute;ficos',
                    iconCls: 'reload',
                    handler: function(){
                        renderizarGraficos();
                    }
                }]
            }]
        }, new Ext.TabPanel({
            activeTab: 0,
            items: [{
                title: 'Tiempos',
                layout: 'column',
                items: [{
                    id: 'flashcontent1',
                    border: true
                }, {
                    id: 'flashcontent2',
                    border: true
                }]
            }, {
                title: 'Indicadores',
                layout: 'column',
                items: [{
                    id: 'flashcontent3',
                    border: true
                }, {
                    id: 'flashcontent4',
                    border: true
                }]
            }, {
                title: 'Pérdidas',
                layout: 'column',
                items: [{
                    id: 'flashcontent5',
                    border: true
                }, {
                    id: 'flashcontent6',
                    border: true
                }]
            }, {
                title: 'Muestras e inyecciones',
                layout: 'column',
                items: [{
                    id: 'flashcontent7',
                    border: true
                }, {
                    id: 'flashcontent8',
                    border: true
                }]
            }],
            listeners: {
                tabchange: function(){
                    renderizarGraficos();
                }
            }
        })]
    });
});
