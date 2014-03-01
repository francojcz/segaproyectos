Ext.BLANK_IMAGE_URL = urlPrefix + '../css/extjs/resources/images/default/s.gif';

var form_interfazreporte = function(){

    return {
        init: function(){
                    
            var interfaz_reporte_data = [
                ['boton_reporteproyectos', 'Información de Proyectos', 'Información de Proyectos', 'images/reporte/1.png'],
                ['boton_reporteparticipantes', 'Participantes por  Proyecto', 'Participantes por Proyecto', 'images/reporte/2.png'],
                ['boton_reporteorganizaciones', 'Organizaciones por Proyecto', 'Organizaciones por Proyecto', 'images/reporte/3.png'],
                ['boton_reporteproductos', 'Productos por Proyecto', 'Productos por Proyecto', 'images/reporte/4.png'],
                ['boton_reportedocumentos', 'Documentos por Proyecto', 'Documentos por Proyecto', 'images/reporte/5.png'],
                ['boton_reporteasignaciones', 'Asignación de Tiempos', 'Asignación de Tiempos', 'images/reporte/6.png'],
                ['boton_reporteconsolidado', 'Presupuesto Consolidado', 'Presupuesto Consolidado', 'images/reporte/7.png'],
                ['boton_reporteingresos', 'Ingresos por Proyecto', 'Ingresos por Proyectos', 'images/reporte/8.png'],
                ['boton_reporteegresos', 'Egresos por Proyecto', 'Egresos por Proyectos', 'images/reporte/9.png']];
            
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
                                addTab('Información de Proyectos', getAbsoluteUrl('reporte_proyectos', 'index'));
                                break;
                            case 1:
                                addTab('Participantes', getAbsoluteUrl('reporte_participantes', 'index'));
                                break;
                            case 2:
                                addTab('Organizaciones', getAbsoluteUrl('reporte_organizaciones', 'index'));
                                break;
                            case 3:
                                addTab('Productos', getAbsoluteUrl('reporte_productos', 'index'));
                                break;
                            case 4:
                                addTab('Documentos', getAbsoluteUrl('reporte_documentos', 'index'));
                                break;
                            case 5:
                                addTab('Asignación de Tiempos', getAbsoluteUrl('reporte_asignaciones', 'index'));
                                break;
                            case 6:
                                addTab('Presupuesto Consolidado', getAbsoluteUrl('reporte_consolidado', 'index'));
                                break;
                            case 7:
                                addTab('Ingresos', getAbsoluteUrl('reporte_ingresos', 'index'));
                                break;
                            case 8:
                                addTab('Egresos', getAbsoluteUrl('reporte_egresos', 'index'));
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
                        title: 'Reportes Disponibles',
                        tbar: [
                        '->', {
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
                }]
            });
        }
    }
}();


Ext.onReady(form_interfazreporte.init, form_interfazreporte);

