Ext.BLANK_IMAGE_URL = urlPrefix + '../css/extjs/resources/images/default/s.gif';

var form_interfazdministrador = function(){

    return {
        init: function(){
        
            var salida_interfaz_administracion = '<div style="text-align:center;">';
            salida_interfaz_administracion += '<font face="arial" size=6 color=#4E79B2><br/><br/>Gracias por usar TPM-QLabs </font>';
            salida_interfaz_administracion += '<font face="arial" size=5 color=#4E79B2><br/> El sistema clave para incrementar la eficiencia de su laboratorio de análisis.</font><br/><br/><br/>';
            salida_interfaz_administracion += '<img   height=157 width=501  src="' + urlPrefix + '../images/logo-quantar-color-horizontal.jpg" alt="logo Quantar"/>';
            salida_interfaz_administracion += '</div>';
            
            var viewport = new Ext.Viewport({
                layout: 'border',
                items: [{
                    frame: true,
                    region: 'north',
                    baseCls: 'x-bubble',
                    layout: 'fit',
                    padding: 0,
                    margins: '10 10 0 10',
                    height: 75,
                    items: [{
                        layout: 'fit',
                        border: false,
                        el: 'titulo_interfaz_admin'
                    }]
                }, {
                    region: 'center',
                    xtype: 'grouptabpanel',
                    deferredRender: false,
                    tabWidth: 170,
                    activeGroup: 0,
                    items: [{
                        expanded: true,
                        items: [{
                            title: 'Manejo de informaci&oacute;n',
                            border: false,
                            style: 'padding: 10px;',
                            html: '<div style="text-align:center;"> <font face="arial" size=6 color=#4E79B2><br/><br/>Manejo de informaci&oacute;n </font><br/><br/><img height=128 width=128  src="' + urlPrefix + '../images/iconos/data_128.png" alt="datos"/></div>'
                        }, {
                            title: 'Usuarios',
                            tabTip: 'Manejo de usuarios del sistema',
                            border: false,
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('crud_empleadousuario', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                           title: 'Grupo equipo',
                            tabTip: 'Grupo equipo, utilice esto para categorizar los equipos',
                            border: false,
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('maestra_categoriaequipo', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'Periodo Mantenimiento',
                            tabTip: 'Manejo de periodos de mantenimiento',
                            border: false,
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('crud_periodomantenimiento', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'Equipos',
                            tabTip: 'Manejo de equipos &oacute; m&aacute;quinas',
                            border: false,
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('crud_maquina', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'Repuestos / Consumibles',
                            tabTip: 'Manejo de repuestos y consumibles',
                            border: false,
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('crud_repuesto', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                           title: 'Mantenimiento',
                            tabTip: 'Mantenimiento Autónomo, utilice esto para realiar el mantenimiento',
                            border: false,
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('mantenimiento_autonomo', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        },{
                            title: 'Categor&iacute;a evento',
                            tabTip: 'Categor&iacute;a evento, utilice esto para categorizar los eventos',
                            border: false,
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('maestra_categoriaevento', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'Evento',
                            tabTip: 'Evento, utilice esto para registrar los eventos que pueden causar perdidas',
                            border: false,
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('maestra_evento', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'M&eacute;todos',
                            tabTip: ' manejo de m&eacute;todos',
                            border: false,
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('crud_metodo', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'Metas',
                            tabTip: 'manejo de metas',
                            border: false,
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('gestion_metaanual', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }]
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Tipo de <br/>identificaci&oacute;n',
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            border: false,
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('maestra_tipoidentificacion', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }]
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Datos originales',
                            style: 'padding: 10px;',
                            border: false,
                            html: '<div style="text-align:center;"> <font face="arial" size=6 color=#4E79B2><br/><br/>Datos brutos</font><br/><br/><img height=128 width=128  src="' + urlPrefix + '../images/iconos/data_128.png" alt="reportes"/> </div>'
                        
                        }],
                        listeners: {
                            activate: function(c){
                                redirigirSiSesionExpiro();
                                window.open(getAbsoluteUrl('ingreso_datos', 'index'));
                            }
                        }
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Reportes',
                            style: 'padding: 10px;',
                            border: false,
                            html: '<div style="text-align:center;"> <font face="arial" size=6 color=#4E79B2><br/><br/>Reportes</font><br/><br/><img height=128 width=128  src="' + urlPrefix + '../images/iconos/reporte_128.png" alt="reportes"/> </div>'
                        }],
                        listeners: {
                            activate: function(c){
                                redirigirSiSesionExpiro();
                                window.open(getAbsoluteUrl('interfaz_reporte', 'index'));
                            }
                        }
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Manual',
                            style: 'padding: 10px;',
                            border: false,
                            html: '<div style="text-align:center;"> <font face="arial" size=6 color=#4E79B2><br/><br/>Manual </font><br/><br/><img height=128 width=128  src="' + urlPrefix + '../images/iconos/manual_128.png" alt="manual"/> </div>'
                        }],
                        listeners: {
                            activate: function(c){
                                window.open(urlWeb + 'manual-tpm/main.html');
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
                                html: salida_interfaz_administracion
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

Ext.onReady(form_interfazdministrador.init, form_interfazdministrador);

