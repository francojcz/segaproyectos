Ext.onReady(function(){
    Ext.BLANK_IMAGE_URL = urlPrefix + '../css/extjs/resources/images/default/s.gif';
    
    fields = [{
		type:'string',
		name:'fecha_metodo'
	}, {
        type: 'string',
        name: 'nombre_metodo'
    }, {
        type: 'string',
        name: 'tiempo_entre_metodos'
    }, {
        type: 'string',
        name: 'cambio_metodo_ajuste'
    }, {
        type: 'string',
        name: 'tiempo_corrida_ss'
    }, {
        type: 'string',
        name: 'numero_inyecciones_ss'
    }, {
        type: 'string',
        name: 'tiempo_corrida_cc'
    }, {
        type: 'string',
        name: 'numero_inyecciones_estandar1'
    }, {
        type: 'string',
        name: 'numero_inyecciones_estandar2'
    }, {
        type: 'string',
        name: 'numero_inyecciones_estandar3'
    }, {
        type: 'string',
        name: 'numero_inyecciones_estandar4'
    }, {
        type: 'string',
        name: 'numero_inyecciones_estandar5'
    }, {
        type: 'string',
        name: 'numero_inyecciones_estandar6'
    }, {
        type: 'string',
        name: 'numero_inyecciones_estandar7'
    }, {
        type: 'string',
        name: 'numero_inyecciones_estandar8'
    }, {
        type: 'string',
        name: 'tiempo_corrida_producto'
    }, {
        type: 'string',
        name: 'tiempo_corrida_estabilidad'
    }, {
        type: 'string',
        name: 'tiempo_corrida_materia_prima'
    }, {
        type: 'string',
        name: 'tiempo_corrida_pureza'
    }, {
        type: 'string',
        name: 'tiempo_corrida_disolucion'
    }, {
        type: 'string',
        name: 'tiempo_corrida_uniformidad'
    }, {
        type: 'string',
        name: 'numero_muestras_producto'
    }, {
        type: 'string',
        name: 'numero_muestras_estabilidad'
    }, {
        type: 'string',
        name: 'numero_muestras_materia_prima'
    }, {
        type: 'string',
        name: 'numero_muestras_pureza'
    }, {
        type: 'string',
        name: 'numero_muestras_disolucion'
    }, {
        type: 'string',
        name: 'numero_muestras_uniformidad'
    }, {
        type: 'string',
        name: 'numero_inyecciones_x_muestra_producto'
    }, {
        type: 'string',
        name: 'numero_inyecciones_x_muestra_materia_prima'
    }, {
        type: 'string',
        name: 'numero_inyecciones_x_muestra_estabilidad'
    }, {
        type: 'string',
        name: 'numero_inyecciones_x_muestra_pureza'
    }, {
        type: 'string',
        name: 'numero_inyecciones_x_muestra_disolucion'
    }, {
        type: 'string',
        name: 'numero_inyecciones_x_muestra_uniformidad'
    }, {
        type: 'string',
        name: 'hora_inicio_corrida'
    }, {
        type: 'string',
        name: 'hora_fin_corrida'
    }, {
        type: 'string',
        name: 'fallas'
    }, {
        type: 'string',
        name: 'observaciones'
    }];
    
    var columnHeaderGroup = new Ext.ux.grid.ColumnHeaderGroup({
        rows: [[{
            header: '<h3>Informaci&oacute;n de<br>cambio de m&eacute;todo</h3>',
            colspan: 4,
            align: 'center'
        }, {
            header: '<h3>Info. de<br>system<br>suitability</h3>',
            colspan: 2,
            align: 'center'
        }, {
            header: '<h3>Informaci&oacute;n<br>de curva de<br>calibraci&oacute;n</h3>',
            colspan: (inyeccionesEstandarPromedio + 1),
            align: 'center'
        }, {
            header: '<h3>Informaci&oacute;n de muestras</h3>',
            colspan: 18,
            align: 'center'
        }, {
            header: '<h3>Informaci&oacute;n<br>de corrida<br>anal&iacute;tica</h3>',
            colspan: 2,
            align: 'center'
        }, {
            header: '',
            colspan: 1,
            align: 'center'
        }]]
    });
    
    var datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('exportacion_datos', 'listarRegistrosUsoMaquina'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, fields)
    });
    
    var fechaInicioField = new Ext.form.DateField({
        xtype: 'datefield',
        fieldLabel: 'Desde',
        allowBlank: false,
        value: new Date(),
        listeners: {
            select: function(){
                recargarDatosMetodos();
            },
            specialkey: function(field, e){
                if (e.getKey() == e.ENTER) {
                    recargarDatosMetodos();
                }
            }
        }
    });
    
    var fechaFinField = new Ext.form.DateField({
        xtype: 'datefield',
        fieldLabel: 'Hasta',
        allowBlank: false,
        value: new Date(),
        listeners: {
            select: function(){
                recargarDatosMetodos();
            },
            specialkey: function(field, e){
                if (e.getKey() == e.ENTER) {
                    recargarDatosMetodos();
                }
            }
        }
    });
    
    var operarios_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('exportacion_datos', 'listarOperarios'),
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
            operarios_combobox.setValue('-1');
            recargarDatosMetodos();
        }
    });
    
    var operarios_combobox = new Ext.form.ComboBox({
        fieldLabel: 'Operario',
        store: operarios_datastore,
        displayField: 'nombre',
        valueField: 'codigo',
        mode: 'local',
        triggerAction: 'all',
        forceSelection: true,
        allowBlank: false,
        width: 130,
        listeners: {
            select: function(){
                recargarDatosMetodos();
            }
        }
    });
    
    var maquinas_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('exportacion_datos', 'listarEquiposActivos'),
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
                    'nombre': 'TODAS'
                }]
            }, true);
            maquinas_combobox.setValue('-1');
            recargarDatosMetodos();
        }
    });
    
    var maquinas_combobox = new Ext.form.ComboBox({
        fieldLabel: 'Equipo',
        store: maquinas_datastore,
        displayField: 'nombre',
        valueField: 'codigo',
        mode: 'local',
        triggerAction: 'all',
        forceSelection: true,
        allowBlank: false,
        width: 130,
        listeners: {
            select: function(){
                recargarDatosMetodos();
            }
        }
    });
    
    var recargarDatosMetodos = function(callback){
        redirigirSiSesionExpiro();
        
        if (operarios_combobox.isValid() && maquinas_combobox.isValid() && fechaInicioField.isValid() && fechaFinField.isValid()) {
            datastore.load({
                callback: callback,
                params: {
                    'codigo_operario': operarios_combobox.getValue(),
                    'codigo_maquina': maquinas_combobox.getValue(),
                    'fecha_inicio': fechaInicioField.getValue(),
                    'fecha_fin': fechaFinField.getValue()
                }
            });
        }
    }
    
    recargarDatosMetodos();
    
    var generarRenderer = function(colorFondoPar, colorFuentePar, colorFondoImpar, colorFuenteImpar){
        return function(valor, metaData, record, rowIndex, colIndex, store){
            if (typeof valor != 'undefined') {
                if ((rowIndex % 2) == 0) {
                    return '<div style="background-color: ' + colorFondoPar + '; color: ' + colorFuentePar + '">' + valor + '</div>';
                }
                else {
                    return '<div style="background-color: ' + colorFondoImpar + '; color: ' + colorFuenteImpar + '">' + valor + '</div>';
                }
            }
            else {
                return valor;
            }
        }
    }
    
    var registros_eventos_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('exportacion_datos', 'listarRegistrosEventos'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, [{
            name: 'codigo',
            type: 'integer'
        }, {
            name: 'id_evento',
            type: 'string'
        }, {
            name: 'hora_ocurrio',
            type: 'string'
        }, {
            name: 'observaciones',
            type: 'string'
        }])
    });
    
    var eventos_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('exportacion_datos', 'listarEventos'),
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
    
    var eventos_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('exportacion_datos', 'listarEventos'),
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
    
    var eventos_datastore_renderer = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('exportacion_datos', 'listarEventos'),
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
    
    var recargarDatosEventos = function(callback){
        eventos_datastore_combo.load();
        eventos_datastore_renderer.load({
            callback: function(){
                var sm = grid.getSelectionModel();
                var cell = sm.getSelectedCell();
                var index = cell[0];
                var registro = datastore.getAt(index);
                registros_eventos_datastore.load({
                    params: {
                        'codigo_rum': registro.get('id_registro_uso_maquina')
                    }
                });
            }
        });
    }
    
    var grillaEventos = new Ext.grid.GridPanel({
        autoWidth: true,
        height: 440,
        //autoHeight: true,
        store: registros_eventos_datastore,
        stripeRows: true,
        clicksToEdit: 1,
        loadMask: {
            msg: 'Cargando...'
        },
        sm: new Ext.grid.RowSelectionModel({
            singleSelect: true
        }),
        columns: [{
            dataIndex: 'id_evento',
            header: 'Nombre del evento',
            tooltip: 'Nombre del evento',
            width: 300,
            align: 'left',
            renderer: function(valor){
                var index = eventos_datastore_renderer.find('codigo', valor);
                if (index != -1) {
                    var record = eventos_datastore_renderer.getAt(index);
                    return record.get('nombre');
                }
                else {
                    return '';
                }
            }
        }, {
            dataIndex: 'hora_ocurrio',
            header: 'Hora',
            tooltip: 'Hora en la cual ocurrió el evento',
            width: 70,
            align: 'center'
        }, {
            dataIndex: 'observaciones',
            header: 'Observaciones',
            tooltip: 'Cualquier detalle adicional',
            width: 300,
            align: 'left'
        }]
    });
    
    var win = new Ext.Window({
        applyTo: 'ventana_flotante',
        layout: 'fit',
        width: 800,
        height: 300,
        closeAction: 'hide',
        plain: true,
        title: 'Editar eventos...',
        items: grillaEventos,
        buttons: [{
            text: 'Aceptar',
            handler: function(){
                win.hide();
            }
        }],
        listeners: {
            hide: function(){
                Ext.getBody().unmask();
            }
        }
    });
    
    var columns = [{
		dataIndex:'fecha_metodo',
		header:'Fecha',
		width: 90,
		align: 'center'//,
		//renderer: generarRenderer('#bfbfbf', '#000000', '#bfbfbf', '#000000')
	},{
        dataIndex: 'nombre_metodo',
        header: 'Método ',
        tooltip: 'Método ',
        columnWidth: 60,
        align: 'center',
        renderer: generarRenderer('#bfbfbf', '#000000', '#bfbfbf', '#000000')
    }, {
        dataIndex: 'tiempo_entre_metodos',
        header: 'Tiempo<br>entre<br>métodos<br>(Hrs)',
        tooltip: 'Tiempo entre métodos (Horas)',
        width: 70,
        align: 'center',
        editor: new Ext.form.TimeField({
            format: 'H:i:s',
            minValue: '00:00',
            maxValue: '23:59',
            increment: 30
        }),
        renderer: generarRenderer('#ffdc44', '#000000', '#ffdc44', '#000000')
    }, {
        dataIndex: 'cambio_metodo_ajuste',
        header: 'Cambio<br>método y<br>ajuste<br>(Min)',
        tooltip: 'Cambio de método y ajustes',
        width: 55,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#47d552', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'tiempo_corrida_ss',
        header: 'T. C.<br>(Min)',
        tooltip: 'Tiempo de corrida',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_inyecciones_ss',
        header: 'No.<br>de<br>iny.',
        tooltip: 'N&uacute;mero de inyecciones',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'tiempo_corrida_cc',
        header: 'T. C.<br>(Min)',
        tooltip: 'Tiempo de corrida',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }];
    
    for (var i = 1; i <= inyeccionesEstandarPromedio; i++) {
        columns.push({
            dataIndex: 'numero_inyecciones_estandar' + i,
            header: 'No.<br>inye.<br>stnd.<br>No. ' + i,
            tooltip: 'N&uacute;mero de inyecciones del estándar No. ' + i,
            width: 44,
            align: 'center',
            editor: {
                xtype: 'numberfield',
                allowNegative: false,
                maxValue: 100000
            },
            renderer: function(valor, metaData, record, rowIndex, colIndex, store){
                if (valor == '0') {
                    return '';
                }
                else {
                    var renderer = generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000');
                    return renderer(valor, metaData, record, rowIndex, colIndex, store);
                }
            }
        });
    }
    
    columns.push({
        dataIndex: 'tiempo_corrida_producto',
        header: 'T. C.<br>prod.<br>(Min)',
        tooltip: 'Tiempo de corrida',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_muestras_producto',
        header: 'No.<br>mue.<br>del<br>prod.',
        tooltip: 'N&uacute;mero de muestras del producto',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_inyecciones_x_muestra_producto',
        header: 'No.<br>inye.<br>x<br>mue.',
        tooltip: 'N&uacute;mero de muestras del producto',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'tiempo_corrida_estabilidad',
        header: 'T. C.<br>estb.<br>(Min)',
        tooltip: 'Tiempo de corrida',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_muestras_estabilidad',
        header: 'No.<br>mue.<br>de<br>estb.',
        tooltip: 'N&uacute;mero de muestras de estabilidad',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_inyecciones_x_muestra_estabilidad',
        header: 'No.<br>inye.<br>x<br>mue.',
        tooltip: 'N&uacute;mero de muestras de estabilidad',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'tiempo_corrida_materia_prima',
        header: 'T. C.<br>Mo.<br>Po.<br>(Min)',
        tooltip: 'Tiempo de corrida',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_muestras_materia_prima',
        header: 'No.<br>mue.<br>Mo.<br>Po.',
        tooltip: 'N&uacute;mero de muestras de materia prima',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_inyecciones_x_muestra_materia_prima',
        header: 'No.<br>inye.<br>x<br>mue.',
        tooltip: 'N&uacute;mero de muestras de materia prima',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'tiempo_corrida_pureza',
        header: 'T. C.<br>pureza<br>(Min)',
        tooltip: 'Tiempo de corrida',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_muestras_pureza',
        header: 'No.<br>mue.<br>pur.',
        tooltip: 'N&uacute;mero de muestras pureza',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_inyecciones_x_muestra_pureza',
        header: 'No.<br>inye.<br>x<br>mue.',
        tooltip: 'N&uacute;mero de muestras de pureza',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'tiempo_corrida_disolucion',
        header: 'T. C.<br>diso.<br>(Min)',
        tooltip: 'Tiempo de corrida',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_muestras_disolucion',
        header: 'No.<br>mue.<br>diso.',
        tooltip: 'N&uacute;mero de muestras disolucion',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_inyecciones_x_muestra_disolucion',
        header: 'No.<br>inye.<br>x<br>mue.',
        tooltip: 'N&uacute;mero de muestras de disolucion',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'tiempo_corrida_uniformidad',
        header: 'T. C.<br>uni.<br>(Min)',
        tooltip: 'Tiempo de corrida',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_muestras_uniformidad',
        header: 'No.<br>mue.<br>uni.',
        tooltip: 'N&uacute;mero de muestras uniformidad',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'numero_inyecciones_x_muestra_uniformidad',
        header: 'No.<br>inye.<br>x<br>mue.',
        tooltip: 'N&uacute;mero de muestras de uniformidad',
        width: 44,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'hora_inicio_corrida',
        header: 'Hora<br>inicio<br>corrida',
        tooltip: 'Hora de inicio de corrida',
        width: 70,
        align: 'center',
        editor: new Ext.form.TimeField({
            format: 'H:i:s',
            minValue: '00:00',
            maxValue: '23:59',
            increment: 30
        }),
        renderer: generarRenderer('#f0a05f', '#000000', '#f0a05f', '#000000')
    }, {
        dataIndex: 'hora_fin_corrida',
        header: 'Hora<br>fin<br>corrida',
        tooltip: 'Hora de inicio de corrida',
        width: 70,
        align: 'center',
        editor: new Ext.form.TimeField({
            format: 'H:i:s',
            minValue: '00:00',
            maxValue: '23:59',
            increment: 30
        }),
        renderer: generarRenderer('#f0a05f', '#000000', '#f0a05f', '#000000')
    }, {
        dataIndex: 'fallas',
        header: 'Fallas<br>(Hrs)',
        tooltip: 'Fallas (Hrs)',
        width: 59,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#ff5454', '#000000', '#ff5454', '#000000')
    }, {
        dataIndex: 'observaciones',
        header: 'Observaciones',
        tooltip: 'Observaciones',
        width: 180,
        align: 'center',
        editor: {
            xtype: 'numberfield',
            allowNegative: false,
            maxValue: 100000
        },
        renderer: generarRenderer('#d2b48c', '#000000', '#d2b48c', '#000000')
    });
    
    var grid = new Ext.grid.GridPanel({
        autoWidth: true,
        height: 400,
        //autoHeight: true,
        store: datastore,
        stripeRows: true,
        border: true,
        frame: true,
        autoScroll: true,
        columnLines: true,
        loadMask: {
            msg: 'Cargando...'
        },
        plugins: columnHeaderGroup,
        columns: columns,
        listeners: {
            afteredit: function(e){
                var row = null;
                var column = null;
                var sm = grid.getSelectionModel();
                if (sm.hasSelection()) {
                    var cell = sm.getSelectedCell();
                    var row = cell[0];
                    var column = cell[1];
                    var cm = grid.getColumnModel();
                    if (column == (cm.getColumnCount() - 1)) {
                        if (row == (datastore.getCount() - 1)) {
                            column = 0;
                            row = 0;
                        }
                        else {
                            column = 0;
                            row++;
                        }
                    }
                    else {
                        column++;
                    }
                }
                var callback = function(){
                    sm.select(row, column);
                }
                var par = (e.row % 2) == 0;
                if (par) {
                    var params = {};
                    params['id_registro_uso_maquina'] = e.record.get('id_registro_uso_maquina');
                    params[e.field] = e.value;
                    Ext.Ajax.request({
                        url: getAbsoluteUrl('exportacion_datos', 'modificarRegistroUsoMaquina'),
                        failure: function(){
                            recargarDatosMetodos(callback);
                        },
                        success: function(result){
                            recargarDatosMetodos(callback);
                            if (result.responseText != 'Ok') {
                                alert(result.responseText);
                            }
                        },
                        params: params
                    });
                }
                else {
                    var params = {};
                    params['id_registro_uso_maquina'] = e.record.get('id_registro_uso_maquina');
                    params[e.field + '_perdida'] = e.value;
                    Ext.Ajax.request({
                        url: getAbsoluteUrl('exportacion_datos', 'modificarRegistroUsoMaquina'),
                        failure: function(){
                            recargarDatosMetodos(callback);
                        },
                        success: function(){
                            recargarDatosMetodos(callback);
                        },
                        params: params
                    });
                }
            }
        }
    });
    
    var panelPrincipal = new Ext.FormPanel({
        renderTo: 'panel_principal_exportacion',
        border: true,
        frame: true,
        layout: 'form',
        height: 500,
        items: [{
            border: true,
            frame: true,
            height: 70,
            items: [{
                height: 60,
                layout: 'column',
                items: [{
                    width: '225',
                    layout: 'form',
                    labelWidth: 75,
                    footer: false,
                    items: [operarios_combobox, maquinas_combobox]
                }, {
                    width: '220',
                    layout: 'form',
                    labelWidth: 75,
                    items: [fechaInicioField, fechaFinField]
                }, {
                    xtype: 'button',
                    iconCls: 'exportar_excel',
                    text: 'Guardar en formato Excel',
                    handler: function(){
                        redirigirSiSesionExpiro();
                        
                        if (operarios_combobox.isValid() && maquinas_combobox.isValid() && fechaInicioField.isValid() && fechaFinField.isValid()) {
                            var dateInicio = fechaInicioField.getValue();
                            var mesInicio = '' + (dateInicio.getMonth() + 1);
                            if (mesInicio.length == 1) {
                                mesInicio = '0' + mesInicio;
                            }
                            var diaInicio = '' + dateInicio.getDate();
                            if (diaInicio.length == 1) {
                                diaInicio = '0' + diaInicio;
                            }
                            var fechaInicio = dateInicio.getFullYear() + '-' + mesInicio + '-' + diaInicio;
                            var dateFin = fechaFinField.getValue();
                            var mesFin = '' + (dateFin.getMonth() + 1);
                            if (mesFin.length == 1) {
                                mesFin = '0' + mesFin;
                            }
                            var diaFin = '' + dateFin.getDate();
                            if (diaFin.length == 1) {
                                diaFin = '0' + diaFin;
                            }
                            var fechaFin = dateFin.getFullYear() + '-' + mesFin + '-' + diaFin;
                            var params = 'codigo_operario=' + operarios_combobox.getValue() + '&codigo_maquina=' + maquinas_combobox.getValue() + '&fecha_inicio=' + fechaInicio + '&fecha_fin=' + fechaFin;
                            window.location = getAbsoluteUrl('exportacion_datos', 'exportar') + '?' + params;
                        }
                        //                        win = window.open(getAbsoluteUrl('exportacion_datos', 'exportar'), 'Exportar datos...', 'height=400,width=400,resizable=1,scrollbars=1, menubar=1');
                    }
                }]
            }]
        }, grid]
    });
});
