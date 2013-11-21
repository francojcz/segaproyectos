Ext.BLANK_IMAGE_URL = urlPrefix + '../css/extjs/resources/images/default/s.gif';

var form_interfazreporte = function(){

    return {
        init: function(){
        
            var salida_interfaz_reporte = '<div style="text-align:center;">';
            salida_interfaz_reporte += '<font face="arial" size=6 color=#4E79B2><br/><br/>Gracias por usar TPM Labs </font>';
            salida_interfaz_reporte += '<font face="arial" size=6 color=#4E79B2><br/><br/>Sistema de Mantenimiento Productivo Total</font><br/><br/><br/>';
            salida_interfaz_reporte += '<img   height=90%  src="' + urlPrefix + '../images/logo-quantar-color-horizontal.jpg" alt="logo Quantar"/>';
            salida_interfaz_reporte += '</div>';
            
            var interfaz_reporte_data = [['boton_reportediario', 'Reporte diario indicadores', 'Reporte diario indicadores', 'images/reporte/1.png'], ['boton_reportemensual', 'Reporte mensual indicadores', 'Reporte mensual indicadores', 'images/reporte/3.png'], ['boton_reporteanual', 'Reporte anual indicadores', 'Reporte anual indicadores', 'images/reporte/4.png'], ['boton_reporteevento', 'Reporte eventos indicadores', 'Reporte eventos indicadores', 'images/reporte/5.png'], ['boton_reporteexportar', 'Exportar datos indicadores', 'Exportar datos indicadores', 'images/reporte/xls.png'],
            ['boton_seguimiento', 'Seguimiento a Mantenimientos', 'Seguimiento a Mantenimientos', 'images/reporte/seguimiento.png'], ['boton_realizados', 'Mantenimientos Realizados', 'Mantenimientos Realizados', 'images/reporte/realizados.png'], ['boton_proximos', 'Próximos Mantenimientos', 'Próximos Mantenimientos', 'images/reporte/proximos.png']];
            
            var interfaz_reporte_store = new Ext.data.ArrayStore({
                fields: [{
                    name: 'nombre'
                }, {
                    name: 'descripcion_titulo'
                }, {
                    name: 'descripcion_larga'
                }, {
                    name: 'url'
                }, {
                    name: 'url_reporte'
                }]
            });
            interfaz_reporte_store.loadData(interfaz_reporte_data);
            
            var interfaz_reporte_tpl = new Ext.XTemplate('<tpl for=".">', '<div class="thumb-wrap" id="{nombre}">', '<div class="thumb" align="center"><img src="' + urlPrefix + '../' + '{url}" title="{descripcion_larga}"></div>', '<span >{descripcion_titulo}</span></div>', '</tpl>', '<div class="x-clear"></div>');
            
            function addTab(tabTitle, targetUrl){
                var tabactivo = Ext.getCmp('intefaz_reporte_panel_central').getItem(tabTitle);
                var tabpanel = Ext.getCmp('intefaz_reporte_panel_central');
                
                if (tabactivo) {
                    tabpanel.setActiveTab(tabactivo);
                }
                else {
                    tabpanel.add({
                        id: tabTitle,
                        title: tabTitle,
                        frame: true,
                        margins: '5 5 5 5',
                        autoScroll: true,
                        autoLoad: {
                            url: targetUrl,
                            scripts: true,
                            scope: this
                        },
                        closable: true
                    }).show();
                    
                }
            }
            
            
            var interfaz_reporte_dataview = new Ext.DataView({
                id: 'images-view',
                title: 'Reportes',
                store: interfaz_reporte_store,
                tpl: interfaz_reporte_tpl,
                autoHeight: true,
                multiSelect: false,
                overClass: 'x-view-over',
                itemSelector: 'div.thumb-wrap',
                emptyText: 'No hay reportes disponibles',
                prepareData: function(data){
                    data.shortName = Ext.util.Format.ellipsis(data.name, 15);
                    return data;
                },
                listeners: {
                    'click': function(dataview, index, node, event){
                    
                        switch (index) {
                            case 0:
                                addTab('Reporte Diario', getAbsoluteUrl('reporte_diario', 'index'));
                                break;
                            case 1:
                                addTab('Reporte Mensual', getAbsoluteUrl('reporte_graficomensual', 'index'));
                                break;
                            case 2:
                                addTab('Reporte Anual', getAbsoluteUrl('graficos_anuales', 'index'));
                                break;
                            case 3:
                                addTab('Reporte Evento', getAbsoluteUrl('reporte_evento', 'index'));
                                break;
                            case 4:
                                addTab('Exportar datos', getAbsoluteUrl('exportacion_datos', 'index'));
                                break;
                            case 5:
                                addTab('Seguimiento a Mantenimientos', getAbsoluteUrl('seguimiento_mantenimiento', 'index'));
                                break;
                            case 6:
                                addTab('Mantenimientos Realizados', getAbsoluteUrl('mantenimientos_realizados', 'index'));
                                break;
                            case 7:
                                addTab('Próximos Mantenimientos', getAbsoluteUrl('proximos_mantenimientos', 'index'));
                                break;
                        }
                    }
                }
            });
            
            var viewport = new Ext.Viewport({
                layout: 'border',
                items: [{
                    region: 'north',
                    layout: 'fit',
                    height: 60,
                    padding: 10,
                    border: false,
                    el: 'titulo_interfaz_reporte'
                }, {
                    region: 'center',
                    id: 'intefaz_reporte_panel_central',
                    xtype: 'tabpanel',
                    activeTab: 0,
                    items: [{
                        xtype: 'panel',
                        title: 'Reportes de TPM-QLabs',
                        tbar: [{
                            text: 'Tema'
                        }, genral_tema_combobox, '-', {
                            xtype: 'button',
                            text: 'Manual',
                            tooltip: 'Pulse este para abrir en una nueva pesta&ntilde;a el manual',
                            width: 60,
                            iconCls: 'abrir_manual',
                            handler: function(){
                                window.open(urlWeb + 'manual-tpm/main.html');
                            }
                        }, '->', {
                            xtype: 'button',
                            text: 'Salir',
                            tooltip: 'Pulse este bot&oacute;n para salir del sistema',
                            width: 60,
                            iconCls: 'salir',
                            handler: function(){
                                cerrarSesion();
                            }
                        }],
                        items: [interfaz_reporte_dataview]
                    
                    }],
                    listeners: {
                        tabchange: function(){
                            redirigirSiSesionExpiro();
                        }
                    }
                }, {
                    region: 'east',
                    title: 'Ayudas',
                    width: 300,
                    border: true,
                    collapsible: true,
                    collapsed: true,
                    split: true,
                    layout: 'accordion',
                    items: [{
                        title: 'Conceptos B&aacute;sicos',
                        frame: true,
                        autoScroll: true,
                        autoLoad: {
                            url: urlWeb + 'ayudas/Ayuda-Conceptual.html',
                            scripts: true,
                            scope: this
                        }
                    }, {
                        title: 'P&eacute;rdidas',
                        frame: true,
                        autoScroll: true,
                        autoLoad: {
                            url: urlWeb + 'ayudas/Ayuda-Perdidas.html',
                            scripts: true,
                            scope: this
                        }
                    }, {
                        title: 'Indicadores',
                        frame: true,
                        autoScroll: true,
                        autoLoad: {
                            url: urlWeb + 'ayudas/Ayuda-Indicadores.html',
                            scripts: true,
                            scope: this
                        }
                    }]
                }]
            });
        }
    }
}();


Ext.onReady(form_interfazreporte.init, form_interfazreporte);

