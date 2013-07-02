Ext.BLANK_IMAGE_URL = urlPrefix + '../css/extjs/resources/images/default/s.gif';

var form_interfazsuperadmin = function(){

    return {
        init: function(){
            var titulo_interfaz_superadmin = '<div style="padding:8px;float:left;width:570px;">';
            titulo_interfaz_superadmin += '<font face="arial" size=6 color=#4E79B2>TPM-QLabs Administraci&oacute;n del sistema</font>';
            titulo_interfaz_superadmin += '</div>';
            titulo_interfaz_superadmin += '<div  style="float:right;width:200px;" onclick="abrirAcercaDe();" >';
            titulo_interfaz_superadmin += '<img   height=60  src="' + urlPrefix + '../images/logo-quantar-color-horizontal.jpg" alt="logo Quantar"/>';
            titulo_interfaz_superadmin += '</div>';
            
            var salida_interfaz_superadmin = '<div style="text-align:center;">';
            salida_interfaz_superadmin += '<font face="arial" size=6 color=#4E79B2><br/><br/>Gracias por usar TPM-QLabs </font>';
            salida_interfaz_superadmin += '<font face="arial" size=5 color=#4E79B2><br/> El sistema clave para incrementar la eficiencia de su laboratorio de análisis.</font><br/><br/><br/>';
            salida_interfaz_superadmin += '<img   height=157 width=501  src="' + urlPrefix + '../images/logo-quantar-color-horizontal.jpg" alt="logo Quantar"/>';
            salida_interfaz_superadmin += '</div>';
            
            var viewport = new Ext.Viewport({
                layout: 'border',
                items: [{
                    frame: true,
                    region: 'north',
                    baseCls: 'x-bubble',
                    layout: 'fit',
                    padding: 0,
                    margins: '10 10 0 10',
                    html: titulo_interfaz_superadmin
                }, {
                    region: 'center',
                    xtype: 'grouptabpanel',
                    deferredRender: false,
                    tabWidth: 190,
                    activeGroup: 0,
                    items: [{
                        expanded: true,
                        items: [{
                            title: 'Manejo de empresas',
                            tabTip: 'Manejo de empresas',
                            border: false,
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('crud_empresa', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'Manejo de superadmin',
                            tabTip: 'Manejo de s&uacute;per administradores  del sistema',
                            border: false,
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('crud_usuario', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'Manejo de administradores',
                            tabTip: 'Manejo de administradores',
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
                        }]
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Certificar computador',
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            items: [{
                                frame: false,
                                autoLoad: {
                                    url: getAbsoluteUrl('certificacion', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }]
                    }, {
                        expanded: true,
                        items: [{
                            title: 'Maestra estado de equipo',
                            tabTip: 'Maestra estado del equipo, utilice esto para especificar los posibles estados de sus equipos',
                            border: false,
                            style: 'padding: 10px;',
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('maestra_estado', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'Maestra indicador',
                            style: 'padding: 10px;',
                            tabTip: 'Maestra indicador, listado de indicadores en reportes',
                            iconCls: 'x-icon-maestra',
                            border: false,
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('maestra_indicador', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'Maestra perfil',
                            style: 'padding: 10px;',
                            iconCls: 'x-icon-maestra',
                            border: false,
                            items: [{
                                frame: true,
                                autoLoad: {
                                    url: getAbsoluteUrl('maestra_perfil', 'index'),
                                    scripts: true,
                                    scope: this
                                }
                            }]
                        }, {
                            title: 'Maestra tipo de identificaci&oacute;n',
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
                            title: 'Manual',
                            style: 'padding: 10px;',
                            border: false,
                            html: '<div style="text-align:center;"> <font face="arial" size=6 color=#4E79B2><br/><br/>Manual </font><br/><br/><img height=128 width=128  src="' + urlPrefix + '../images/iconos/manual_128.png" alt="manual"/> </div>'
                        }],
                        listeners: {
                            activate: function(c){
                                window.open(urlWeb + 'manual-tpm-quantar/main.html');
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
                                html: salida_interfaz_superadmin
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

Ext.onReady(form_interfazsuperadmin.init, form_interfazsuperadmin);

