Ext.BLANK_IMAGE_URL = urlPrefix + '../css/extjs/resources/images/default/s.gif';

var form_administrador = function(){

    return {
        init: function(){
            var titulo_administrador = '<div style="padding:8px;float:left;width:570px;">';
            titulo_administrador += '<font face="arial" size=6 color=#4E79B2>Seguimiento a Proyectos</font>';
            titulo_administrador += '</div>';
            titulo_administrador += '<div  style="float:right;width:125px;" >';
            titulo_administrador += '<img height=54  src="' + urlPrefix + '../images/logo-cinara.jpg" alt="Logo Cinara"/>';
            titulo_administrador += '</div>';
            
            var salida_administrador = '<div style="text-align:center;">';
            salida_administrador += '<font face="arial" size=6 color=#4E79B2><br/><br/><br/><br/>Gracias por utilizar el sistema para realizar el</font>';
            salida_administrador += '<font face="arial" size=10 color=#4E79B2><br/>Seguimiento a Proyectos</font><br/><br/>';
//            salida_administrador += '<img   height=157 width=350  src="' + urlPrefix + '../images/logo-cinara.jpg" alt="Logo Cinara"/>';
            salida_administrador += '</div>';
            
            var viewport = new Ext.Viewport({
                layout: 'border',
                items: [{
                    frame: true,
                    region: 'north',
                    baseCls: 'x-bubble',
                    layout: 'fit',
                    padding: 0,
                    margins: '10 10 0 10',
                    html: titulo_administrador
                }, {
                    region: 'center',
                    xtype: 'grouptabpanel',
                    deferredRender: false,
                    tabWidth: 190,
                    activeGroup: 0,
                    items: [{
                        expanded: true,
                        items: [{
                            title: 'Alarmas',
                            tabTip: 'Visualización de Alarmas',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('alarmas', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }]
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Personas',
                            tabTip: 'Gestión de Personas',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('personas', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }]
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Usuarios',
                            tabTip: 'Gestión de Usuarios',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('usuarios', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }]
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Proyectos',
                            tabTip: 'Gestión de Proyectos',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('proyectos', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }]
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Participantes',
                            tabTip: 'Gestión de Participantes en un Proyecto',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('participantes', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }]
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Organizaciones',
                            tabTip: 'Gestión de Organizaciones',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('organizaciones', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }]
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Productos',
                            tabTip: 'Gestión de Productos',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('productos', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }]
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Documentos',
                            tabTip: 'Gestión de Documentos',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('documentos', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }]
                    },  {
                        expanded: true,
                        items: [{
                            title: 'Presupuesto',
                            border: false,
                            style: 'padding: 10px;',
                            html: '<div style="text-align:center;"> <font face="arial" size=6 color=#4E79B2><br/><br/>Presupuesto</font><br/><br/><img src="' + urlPrefix + '../images/iconos/presupuestos.png" alt="datos"/></div>'
                        }, {
                            title: 'Conceptos',
                            tabTip: 'Gestión de Conceptos',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('conceptos', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'Ingresos',
                            tabTip: 'Gestión de Ingresos',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('ingresos', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'Egresos',
                            tabTip: 'Gestión de Egresos',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('egresos', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }]
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Reportes',
                            style: 'padding: 10px;',
                            border: false,
                            html: '<div style="text-align:center;"> <font face="arial" size=6 color=#4E79B2><br/><br/>Reportes</font><br/><br/><img src="' + urlPrefix + '../images/iconos/reportes.png" alt="reportes"/> </div>'
                        }],
                        listeners: {
                            activate: function(c){
                                redirigirSiSesionExpiro();
                                window.open(getAbsoluteUrl('reportes', 'index'));
                            }
                        }
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Salir',
                            tabTip: 'Pulse para salir',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                border: false,
                                html: salida_administrador
                            }]
                        }],
                        listeners: {
                            activate: function(c){
                                cerrarSesion();
                            }
                        }
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

Ext.onReady(form_administrador.init, form_administrador);

