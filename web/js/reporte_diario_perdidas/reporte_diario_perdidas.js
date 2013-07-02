Ext.onReady(function(){
    Ext.BLANK_IMAGE_URL = urlPrefix + '../css/extjs/resources/images/default/s.gif';
    
    fields = [{
        type: 'string',
        name: 'nombre_operario'
    }, {
        type: 'string',
        name: 'nombre_maquina'
    }, {
        type: 'string',
        name: 'nombre_metodo'
    }, {
        type: 'string',
        name: 'numero_muestras_reanalizadas'
    }, {
        type: 'string',
        name: 'numero_reinyecciones'
    }, {
        type: 'string',
        name: 'paros_menores'
    }, {
        type: 'string',
        name: 'retrabajos'
    }, {
        type: 'string',
        name: 'fallas'
    }, {
        type: 'string',
        name: 'perdidas_velocidad'
    }];
    
    var metodos_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('ingreso_datos', 'listarMetodos'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data',
        }, [{
            name: 'codigo',
            type: 'integer'
        }, {
            name: 'nombre',
            type: 'string'
        }])
    });
    
    var datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_diario_perdidas', 'listarReportePorMetodo'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data',
        }, fields)
    });
    
    var recargarDatosMetodos = function(callback){
        if (operarios_combobox.isValid() && maquinas_combobox.isValid() && fechaField.isValid()) {
            datastore.load({
                callback: callback,
                params: {
                    'codigo_usu_operario': operarios_combobox.getValue(),
                    'codigo_maquina': maquinas_combobox.getValue(),
                    'fecha': fechaField.getValue()
                }
            });
        }
    }
    
    var fechaField = new Ext.form.DateField({
        xtype: 'datefield',
        fieldLabel: 'Fecha',
        allowBlank: false,
        value: new Date(),
        listeners: {
            select: function(){
                recargarDatosMetodos();
            },
            //            blur: function(){
            //                recargarDatosMetodos();
            //            },
            specialkey: function(field, e){
                if (e.getKey() == e.ENTER) {
                    recargarDatosMetodos();
                }
            }
        }
    });
    
    var generarRenderer = function(colorFondo, colorFuente){
        return function(valor){
            if (typeof valor != 'undefined') {
                return '<div style="background-color: ' + colorFondo + '; color: ' + colorFuente + '">' + valor + '</div>';
            }
            else {
                return valor;
            }
        }
    }
    
    var columns = [{
        dataIndex: 'nombre_operario',
        header: 'Operario',
        tooltip: 'Operario que realizó el registro',
        columnWidth: 60,
        align: 'center',
        renderer: generarRenderer('#bfbfbf', '#000000')
    }, {
        dataIndex: 'nombre_maquina',
        header: 'Máquina',
        tooltip: 'Máquina que llevó a cabo el método',
        columnWidth: 60,
        align: 'center',
        renderer: generarRenderer('#bfbfbf', '#000000')
    }, {
        dataIndex: 'nombre_metodo',
        header: 'Método ',
        tooltip: 'Método ',
        columnWidth: 60,
        align: 'center',
        renderer: generarRenderer('#bfbfbf', '#000000')
    }, {
        dataIndex: 'numero_muestras_reanalizadas',
        header: 'Numero de<br>muestras<br>reanalizadas',
        tooltip: 'Numero de muestras reanalizadas',
        width: 75,
        align: 'center',
        renderer: generarRenderer('#ff5454', '#000000')
    }, {
        dataIndex: 'numero_reinyecciones',
        header: 'Numero<br>de<br>reinyecciones',
        tooltip: 'Numero de reinyecciones',
        width: 80,
        align: 'center',
        renderer: generarRenderer('#ff5454', '#000000')
    }, {
        dataIndex: 'paros_menores',
        header: 'Paros<br>menores<br>(Min)',
        tooltip: 'Paros menores (Minutos)',
        width: 60,
        align: 'center',
        renderer: generarRenderer('#ff5454', '#000000')
    }, {
        dataIndex: 'retrabajos',
        header: 'Retrabajos<br>(Min)',
        tooltip: 'Retrabajos (Minutos)',
        width: 70,
        align: 'center',
        renderer: generarRenderer('#ff5454', '#000000')
    }, {
        dataIndex: 'fallas',
        header: 'Fallas<br>(Min)',
        tooltip: 'Fallas (Minutos)',
        width: 70,
        align: 'center',
        renderer: generarRenderer('#ff5454', '#000000')
    }, {
        dataIndex: 'perdidas_velocidad',
        header: 'Perdidas de<br>velocidad (Min)',
        tooltip: 'Perdidas de velocidad (Minutos)',
        width: 90,
        align: 'center',
        renderer: generarRenderer('#ff5454', '#000000')
    }];
    
    var grid = new Ext.grid.GridPanel({
        autoWidth: true,
        height: 320,
        store: datastore,
        stripeRows: true,
        loadMask: {
            msg: 'Cargando...'
        },
        columns: columns
    });
    
    var horaField = new Ext.form.TextField({
        xtype: 'textfield',
        fieldLabel: 'Hora',
        width: 97,
        readOnly: true
    });
    
    var actualizarHora = function(){
        var fechaActual = new Date();
        
        var segundos = '' + fechaActual.getSeconds();
        if (segundos.length == 1) {
            segundos = '0' + segundos;
        }
        var minutos = '' + fechaActual.getMinutes();
        if (minutos.length == 1) {
            minutos = '0' + minutos;
        }
        var horas = '' + fechaActual.getHours();
        if (horas.length == 1) {
            horas = '0' + horas;
        }
        
        horaField.setValue(horas + ':' + minutos + ':' + segundos);
    }
    
    var operarios_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('reporte_diario_perdidas', 'listarOperarios'),
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
            url: getAbsoluteUrl('reporte_diario_perdidas', 'listarMaquinas'),
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
        }
    });
    
    //    maquinas_datastore.load();
    
    var maquinas_combobox = new Ext.form.ComboBox({
        fieldLabel: 'Máquina',
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
    
    var panelPrincipal = new Ext.FormPanel({
        renderTo: 'panel_principal_reporte_diario_perdidas',
        border: true,
        frame: true,
        layout: 'form',
        items: [{
            border: true,
            frame: true,
            items: [            /*{
             height: 50,
             items: [{
             xtype: 'label',
             html: '<img align=left  hspace=10  width=40  src="' + urlLogo + '" alt="empresa logo"/> <p style="font-size:x-large;">' + nombreEmpresa + '</p>'
             }]
             }, */
            {
                height: 70,
                layout: 'column',
                items: [{
                    width: '225',
                    layout: 'form',
                    labelWidth: 75,
                    items: [operarios_combobox, maquinas_combobox]
                }, {
                    width: '250',
                    layout: 'form',
                    labelWidth: 75,
                    items: [fechaField, horaField]
                }]
            }]
        }, {
            height: 340,
            border: true,
            frame: true,
            items: [grid]
        }]
    });
    
    actualizarHora();
    
    window.setInterval(actualizarHora, 1000);
});
