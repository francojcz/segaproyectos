// Esta funcion es de Pedro 
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


var fechaField = new Ext.form.DateField({
    xtype: 'datefield',
    fieldLabel: 'Fecha',
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


var metodos_datastore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('ingreso_datos', 'listarMetodos'),
        method: 'POST'
    }),
    reader: new Ext.data.JsonReader({
        root: 'data'
    }, [{
        name: 'codigo',
        type: 'integer'
    }, {
        name: 'nombre',
        type: 'string'
    }])
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
        url: getAbsoluteUrl('reporte_diario', 'listarOperarios'),
        method: 'POST'
    }),
    reader: new Ext.data.JsonReader({
        root: 'data'
    }, [{
        name: 'codigo',
        type: 'string'
    }, {
        name: 'nombre',
        type: 'string'
    }])
});

var operarios_combobox = new Ext.form.ComboBox({
    fieldLabel: 'Analista',
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

var maquinas_datastore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_diario', 'listarEquiposActivos'),
        method: 'POST'
    }),
    reader: new Ext.data.JsonReader({
        root: 'data'
    }, [{
        name: 'codigo',
        type: 'string'
    }, {
        name: 'nombre',
        type: 'string'
    }])
});

//    maquinas_datastore.load();

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

maquinas_datastore.load({
    callback: function(){
        maquinas_datastore.loadData({
            data: [{
                'codigo': '-1',
                'nombre': 'TODOS'
            }]
        }, true);
        maquinas_combobox.setValue('-1');
        recargarDatosMetodos();
    }
});

var recargarDatosMetodos = function(callback){
    redirigirSiSesionExpiro();
    
    if (operarios_combobox.isValid() && maquinas_combobox.isValid() && fechaField.isValid()) {
    
        var maq = maquinas_combobox.getValue();
        var ope = operarios_combobox.getValue();
        var fecha = fechaField.getValue();
        
        rdtiemp_datastore.load({
            callback: callback,
            params: {
                'codigo_usu_operario': ope,
                'codigo_maquina': maq,
                'fecha': fecha
            }
        });
        
        rdindic_datastore.load({
            callback: callback,
            params: {
                'codigo_usu_operario': ope,
                'codigo_maquina': maq,
                'fecha': fecha
            }
        });
        
        rdperdi_datastore.load({
            callback: callback,
            params: {
                'codigo_usu_operario': ope,
                'codigo_maquina': maq,
                'fecha': fecha
            }
        });
        
        rdmuin_datastore.load({
            callback: callback,
            params: {
                'codigo_usu_operario': ope,
                'codigo_maquina': maq,
                'fecha': fecha
            }
        });
        
    }
}
///////////REPORTE TIEMPO
var rdtiemp_datastore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_diario', 'listarReporteTiemposMetodo'),
        method: 'POST'
    }),
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total'
    }, [{
        name: 'rdtiemp_analista',
        type: 'string'
    }, {
        name: 'rdtiemp_maquina',
        type: 'string'
    }, {
        name: 'rdtiemp_metodo',
        type: 'string'
    }, {
        name: 'rdtiemp_fecha',
        type: 'string'
    }, {
        name: 'rdtiemp_TP_metodo',
        type: 'string'
    }, {
        name: 'rdtiemp_TNP_metodo',
        type: 'string'
    }, {
        name: 'rdtiemp_TPP_metodo',
        type: 'string'
    }, {
        name: 'rdtiemp_TPNP_metodo',
        type: 'string'
    }, {
        name: 'rdtiemp_TF_metodo',
        type: 'string'
    }, {
        name: 'rdtiemp_TO_metodo',
        type: 'string'
    }, {
        name: 'rdtiemp_TP_dia',
        type: 'string'
    }, {
        name: 'rdtiemp_TNP_dia',
        type: 'string'
    }, {
        name: 'rdtiemp_TPP_dia',
        type: 'string'
    }, {
        name: 'rdtiemp_TPNP_dia',
        type: 'string'
    }, {
        name: 'rdtiemp_TF_dia',
        type: 'string'
    }, {
        name: 'rdtiemp_TO_dia',
        type: 'string'
    }, {
        name: 'observaciones',
        type: 'string'
    }])
});
//rdtiemp_datastore.load();


var rdtiemp_colmodel = new Ext.grid.ColumnModel({
    defaults: {
        sortable: true,
        locked: false,
        resizable: true,
        align: 'center'
    },
    columns: [{
        dataIndex: 'rdtiemp_analista',
        header: 'Analista',
        tooltip: 'Analista que realiz&oacute; el registro',
        width: 100,
        renderer: generarRenderer('#bfbfbf', '#000000')
    }, {
        dataIndex: 'rdtiemp_maquina',
        header: 'Equipo',
        tooltip: 'Equipo que llev&oacute; a cabo el m&eacute;todo',
        width: 100,
        renderer: generarRenderer('#bfbfbf', '#000000')
    }, {
        dataIndex: 'rdtiemp_metodo',
        header: 'M&eacute;todo ',
        tooltip: 'M&eacute;todo ',
        width: 100,
        renderer: generarRenderer('#bfbfbf', '#000000')
    }, {
        dataIndex: 'rdtiemp_fecha',
        header: 'Fecha ',
        tooltip: 'Fecha ',
        width: 70,
        hidden: true,
        renderer: generarRenderer('#bfbfbf', '#000000')
    }, {
        dataIndex: 'rdtiemp_TP_metodo',
        header: 'TP <br/>(Hrs.)<br/>M&eacute;todo',
        tooltip: 'Tiempo programado m&eacute;todo',
        width: 60,
        renderer: generarRenderer('#72a8cd', '#000000')
    }, {
        dataIndex: 'rdtiemp_TP_dia',
        header: 'TP <br/>(Hrs.)<br/>D&iacute;a',
        tooltip: 'Tiempo programado d&iacute;a',
        width: 60,
        renderer: generarRenderer('#72a8cd', '#000000')
    }, {
        dataIndex: 'rdtiemp_TNP_metodo',
        header: 'TNP <br/>(Hrs.)<br/>M&eacute;todo',
        tooltip: 'Tiempo no programado m&eacute;todo',
        width: 60,
        renderer: generarRenderer('#ffdc44', '#000000')
    }, {
        dataIndex: 'rdtiemp_TNP_dia',
        header: 'TNP <br/>(Hrs.)<br/>D&iacute;a',
        tooltip: 'Tiempo no programado d&iacute;a',
        width: 60,
        renderer: generarRenderer('#ffdc44', '#000000')
    }, {
        dataIndex: 'rdtiemp_TPP_metodo',
        header: 'TPP <br/>(Hrs.)<br/>M&eacute;todo',
        tooltip: 'Tiempo parardas programadas M&eacute;todo',
        width: 60,
        renderer: generarRenderer('#47d552', '#000000')
    }, {
        dataIndex: 'rdtiemp_TPP_dia',
        header: 'TPP <br/>(Hrs.)<br/>D&iacute;a',
        tooltip: 'Tiempo paradas programadas d&iacute;a',
        width: 60,
        renderer: generarRenderer('#47d552', '#000000')
    }, {
        dataIndex: 'rdtiemp_TPNP_metodo',
        header: 'TPNP<br/> (Hrs.)<br/>M&eacute;todo',
        tooltip: 'Tiempo de paradas no programadas m&eacute;todo',
        width: 60,
        renderer: generarRenderer('#ff5454', '#000000')
    }, {
        dataIndex: 'rdtiemp_TPNP_dia',
        header: 'TPNP <br/>(Hrs.)<br/>D&iacute;a',
        tooltip: 'Tiempo de paradas no programadas d&iacute;a',
        width: 60,
        renderer: generarRenderer('#ff5454', '#000000')
    }, {
        dataIndex: 'rdtiemp_TF_metodo',
        header: 'TF<br/> (Hrs)<br/>M&eacute;todo',
        tooltip: 'Tiempo de funcionamiento m&eacute;todo',
        width: 60,
        renderer: generarRenderer('#f0a05f', '#000000')
    }, {
        dataIndex: 'rdtiemp_TF_dia',
        header: 'TF <br/>(Hrs.)<br/>D&iacute;a',
        tooltip: 'Tiempo de funcionamiento d&iacute;a',
        width: 60,
        renderer: generarRenderer('#f0a05f', '#000000')
    }, {
        dataIndex: 'rdtiemp_TO_metodo',
        header: 'TO <br/>(Hrs.)<br/>M&eacute;todo',
        tooltip: 'Tiempo operativo m&eacute;todo',
        width: 60,
        renderer: generarRenderer('#72a8cd', '#000000')
    }, {
        dataIndex: 'rdtiemp_TO_dia',
        header: 'TO<br/> (Hrs.)<br/>D&iacute;a',
        tooltip: 'Tiempo de operativo d&iacute;a',
        width: 60,
        renderer: generarRenderer('#72a8cd', '#000000')
    },
    {
        dataIndex: 'observaciones',
        header: 'Observaciones',
        tooltip: 'Observaciones',
        width: 220,
        renderer: generarRenderer('#d2b48c', '#000000')
    }]});

var rdtiemp_grid = new Ext.grid.GridPanel({
    autoWidth: true,
    frame: true,
    border: true,
    ds: rdtiemp_datastore,
    cm: rdtiemp_colmodel,
    stripeRows: true,
    loadMask: {
        msg: 'Cargando...'
    },
    height: 320
});



///////////REPORTE INDICADOR
var rdindic_datastore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_diario', 'listarReporteIndicadoresMetodo'),
        method: 'POST'
    }),
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total'
    }, [{
        name: 'rdindic_analista',
        type: 'string'
    }, {
        name: 'rdindic_maquina',
        type: 'string'
    }, {
        name: 'rdindic_metodo',
        type: 'string'
    }, {
        name: 'rdindic_fecha',
        type: 'string'
    }, {
        name: 'rdindic_D_metodo',
        type: 'string'
    }, {
        name: 'rdindic_E_metodo',
        type: 'string'
    }, {
        name: 'rdindic_C_metodo',
        type: 'string'
    }, {
        name: 'rdindic_OEE_metodo',
        type: 'string'
    }, {
        name: 'rdindic_D_dia',
        type: 'string'
    }, {
        name: 'rdindic_E_dia',
        type: 'string'
    }, {
        name: 'rdindic_C_dia',
        type: 'string'
    }, {
        name: 'rdindic_AE_dia',
        type: 'string'
    }, {
        name: 'rdindic_OEE_dia',
        type: 'string'
    }, {
        name: 'rdindic_PTEE_dia',
        type: 'string'
    }])
});

//$indicadores_tiempo=array(      'D',     'E',     'C',    'A',   'OEE',   'PTEE');
//$indicadores_colores=array('ff5454','47d552','f0a05f','ffdc44','72a8cd','b97a57');
var rdindic_colmodel = new Ext.grid.ColumnModel({
    defaults: {
        sortable: true,
        locked: false,
        resizable: true,
        align: 'center'
    },
    columns: [{
        dataIndex: 'rdindic_analista',
        header: 'Analista',
        tooltip: 'Analista que realiz&oacute; el registro',
        width: 100,
        renderer: generarRenderer('#bfbfbf', '#000000')
    }, {
        dataIndex: 'rdindic_maquina',
        header: 'Equipo',
        tooltip: 'Equipo que llev&oacute; a cabo el m&eacute;todo',
        width: 100,
        renderer: generarRenderer('#bfbfbf', '#000000')
    }, {
        dataIndex: 'rdindic_metodo',
        header: 'M&eacute;todo ',
        tooltip: 'M&eacute;todo ',
        width: 100,
        renderer: generarRenderer('#bfbfbf', '#000000')
    }, {
        dataIndex: 'rdindic_fecha',
        header: 'Fecha ',
        tooltip: 'Fecha ',
        width: 70,
        hidden: true,
        renderer: generarRenderer('#bfbfbf', '#000000')
    }, {
        dataIndex: 'rdindic_D_metodo',
        header: 'D (%)<br/>M&eacute;todo',
        tooltip: 'Disponibilidad  en el m&eacute;todo',
        width: 65,
        renderer: generarRenderer('#e1de98', '#000000')
    }, {
        dataIndex: 'rdindic_D_dia',
        header: 'D (%)<br/> D&iacute;a',
        tooltip: 'Disponibilidad  en el d&iacute;a',
        width: 65,
        renderer: generarRenderer('#e1de98', '#000000')
    }, {
        dataIndex: 'rdindic_E_metodo',
        header: 'E (%)<br/> M&eacute;todo',
        tooltip: 'Eficiencia  en el m&eacute;todo',
        width: 65,
        renderer: generarRenderer('#e1de98', '#000000')
    }, {
        dataIndex: 'rdindic_E_dia',
        header: 'E (%)<br/> D&iacute;a',
        tooltip: 'Eficiencia  en el dia',
        width: 65,
        renderer: generarRenderer('#e1de98', '#000000')
    }, {
        dataIndex: 'rdindic_C_metodo',
        header: 'C (%)<br/> M&eacute;todo',
        tooltip: 'Calidad  en el m&eacute;todo',
        width: 65,
        renderer: generarRenderer('#e1de98', '#000000')
    }, {
        dataIndex: 'rdindic_C_dia',
        header: 'C (%)<br/> D&iacute;a',
        tooltip: 'Calidad  en el d&iacute;a',
        width: 65,
        renderer: generarRenderer('#e1de98', '#000000')
    }, {
        dataIndex: 'rdindic_OEE_metodo',
        header: 'OEE (%)<br/>M&eacute;todo',
        tooltip: 'Efectividad global del equipo en el m&eacute;todo',
        width: 65,
        renderer: generarRenderer('#e1de98', '#000000')
    }, {
        dataIndex: 'rdindic_OEE_dia',
        header: 'OEE (%)<br/>D&iacute;a',
        tooltip: 'Efectividad global del equipo en el d&iacute;a',
        width: 65,
        renderer: generarRenderer('#e1de98', '#000000')
    }, {
        dataIndex: 'rdindic_AE_dia',
        header: 'A (%)<br/> D&iacute;a',
        tooltip: 'Aprovechamiento en el  d&iacute;a',
        width: 65,
        renderer: generarRenderer('#e1de98', '#000000')
    }, {
        dataIndex: 'rdindic_PTEE_dia',
        header: 'PTEE (%)<br/>D&iacute;a',
        tooltip: 'Productividad total efectiva del equipo en el d&iacute;a',
        width: 65,
        renderer: generarRenderer('#e1de98', '#000000')
    }]
});

var rdindic_grid = new Ext.grid.GridPanel({
    autoWidth: true,
    frame: true,
    border: true,
    ds: rdindic_datastore,
    cm: rdindic_colmodel,
    stripeRows: true,
    loadMask: {
        msg: 'Cargando...'
    },
    height: 320
});



//REPORTE PERDIDA

var rdperdi_fields = [{
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
    name: 'paros_menores'
}, {
    type: 'string',
    name: 'paros_menores_dia'
}, {
    type: 'string',
    name: 'retrabajos'
}, {
    type: 'string',
    name: 'retrabajos_dia'
}, {
    type: 'string',
    name: 'fallas'
}, {
    type: 'string',
    name: 'fallas_dia'
}, {
    type: 'string',
    name: 'perdidas_velocidad'
}, {
    type: 'string',
    name: 'perdidas_velocidad_dia'
}];

var rdperdi_datastore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_diario', 'listarReportePerdidasPorMetodo'),
        method: 'POST'
    }),
    reader: new Ext.data.JsonReader({
        root: 'data'
    }, rdperdi_fields)
});

var rdperdi_columns = [{
    dataIndex: 'nombre_operario',
    header: 'Analista',
    tooltip: 'Analista que realiz&oacute; el registro',
    columnWidth: 60,
    align: 'center',
    renderer: generarRenderer('#bfbfbf', '#000000')
}, {
    dataIndex: 'nombre_maquina',
    header: 'Equipo',
    tooltip: 'Equipo que llev&oacute; a cabo el m&eacute;todo',
    width: 150,
    align: 'center',
    renderer: generarRenderer('#bfbfbf', '#000000')
}, {
    dataIndex: 'nombre_metodo',
    header: 'M&eacute;todo ',
    tooltip: 'M&eacute;todo ',
    columnWidth: 60,
    align: 'center',
    renderer: generarRenderer('#bfbfbf', '#000000')
}, {
    dataIndex: 'paros_menores',
    header: 'Paros<br>menores<br>(Min)<br>Método',
    tooltip: 'Paros menores (Minutos)',
    width: 60,
    align: 'center',
    renderer: generarRenderer('#ff5454', '#000000')
}, {
    dataIndex: 'paros_menores_dia',
    header: 'Paros<br>menores<br>(Min)<br>Día',
    tooltip: 'Paros menores (Minutos) del día',
    width: 60,
    align: 'center',
    renderer: generarRenderer('#ff5454', '#000000')
}, {
    dataIndex: 'retrabajos',
    header: 'Retrabajos<br>(Min)<br>Método',
    tooltip: 'Retrabajos (Minutos)',
    width: 70,
    align: 'center',
    renderer: generarRenderer('#ff5454', '#000000')
}, {
    dataIndex: 'retrabajos_dia',
    header: 'Retrabajos<br>(Min)<br>Día',
    tooltip: 'Retrabajos (Minutos) del día',
    width: 70,
    align: 'center',
    renderer: generarRenderer('#ff5454', '#000000')
}, {
    dataIndex: 'fallas',
    header: 'Fallas<br>(Min)<br>Método',
    tooltip: 'Fallas (Minutos)',
    width: 70,
    align: 'center',
    renderer: generarRenderer('#ff5454', '#000000')
}, {
    dataIndex: 'fallas_dia',
    header: 'Fallas<br>(Min)<br>Día',
    tooltip: 'Fallas (Minutos) del día',
    width: 70,
    align: 'center',
    renderer: generarRenderer('#ff5454', '#000000')
}, {
    dataIndex: 'perdidas_velocidad',
    header: 'Pérdidas de<br>velocidad (Min)<br>Método',
    tooltip: 'Pérdidas de velocidad (Minutos)',
    width: 110,
    align: 'center',
    renderer: generarRenderer('#ff5454', '#000000')
}, {
    dataIndex: 'perdidas_velocidad_dia',
    header: 'Perdidas de<br>rendimiento (Min)<br>Día',
    tooltip: 'Perdidas de rendimiento (Minutos) en el día',
    width: 110,
    align: 'center',
    renderer: generarRenderer('#ff5454', '#000000')
}];

var rdperdi_grid = new Ext.grid.GridPanel({
    autoWidth: true,
    height: 320,
    border: true,
    frame: true,
    store: rdperdi_datastore,
    stripeRows: true,
    loadMask: {
        msg: 'Cargando...'
    },
    columns: rdperdi_columns
});


//REPORTE MUESTRAS INYECCION
rdmuin_fields = [{
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
    name: 'numero_muestras'
}, {
    type: 'string',
    name: 'numero_muestras_dia'
}, {
    type: 'string',
    name: 'numero_muestras_reanalizadas'
}, {
    type: 'string',
    name: 'numero_muestras_reanalizadas_dia'
}, {
    type: 'string',
    name: 'numero_inyecciones'
}, {
    type: 'string',
    name: 'numero_inyecciones_dia'
}, {
    type: 'string',
    name: 'numero_reinyecciones'
}, {
    type: 'string',
    name: 'numero_reinyecciones_dia'
}];

var rdmuin_datastore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('reporte_diario', 'listarReporteInyeccionMuestraPorMetodo'),
        method: 'POST'
    }),
    reader: new Ext.data.JsonReader({
        root: 'data'
    }, rdmuin_fields)
});


var rdmuin_columns = [{
    dataIndex: 'nombre_operario',
    header: 'Analista',
    tooltip: 'Analista que realiz&oacute; el registro',
    columnWidth: 60,
    align: 'center',
    renderer: generarRenderer('#bfbfbf', '#000000')
}, {
    dataIndex: 'nombre_maquina',
    header: 'Equipo',
    tooltip: 'Equipo que llev&oacute; a cabo el m&eacute;todo',
    width: 150,
    align: 'center',
    renderer: generarRenderer('#bfbfbf', '#000000')
}, {
    dataIndex: 'nombre_metodo',
    header: 'M&eacute;todo ',
    tooltip: 'M&eacute;todo ',
    columnWidth: 60,
    align: 'center',
    renderer: generarRenderer('#bfbfbf', '#000000')
}, {
    dataIndex: 'numero_muestras',
    header: 'No. Muestras <br>analizadas<br>Método',
    tooltip: 'N&uacute;mero de muestras analizadas',
    width: 80,
    align: 'center',
    renderer: generarRenderer('#e1de98', '#000000')
}, {
    dataIndex: 'numero_muestras_dia',
    header: 'No. Muestras <br>analizadas<br>Día',
    tooltip: 'N&uacute;mero de muestras analizadas en el día',
    width: 80,
    align: 'center',
    renderer: generarRenderer('#e1de98', '#000000')
}, {
    dataIndex: 'numero_muestras_reanalizadas',
    header: 'No. Muestras<br>reanalizadas<br>Método',
    tooltip: 'N&uacute;mero de muestras reanalizadas',
    width: 80,
    align: 'center',
    renderer: generarRenderer('#ff5454', '#000000')
}, {
    dataIndex: 'numero_muestras_reanalizadas_dia',
    header: 'No. Muestras<br>reanalizadas<br>Día',
    tooltip: 'N&uacute;mero de muestras reanalizadas en el día',
    width: 80,
    align: 'center',
    renderer: generarRenderer('#ff5454', '#000000')
}, {
    dataIndex: 'numero_inyecciones',
    header: 'No.<br>Inyecciones<br>Método',
    tooltip: 'N&uacute;mero de inyecciones',
    width: 80,
    align: 'center',
    renderer: generarRenderer('#e1de98', '#000000')
}, {
    dataIndex: 'numero_inyecciones_dia',
    header: 'No.<br>Inyecciones<br>Día',
    tooltip: 'N&uacute;mero de inyecciones en el día',
    width: 80,
    align: 'center',
    renderer: generarRenderer('#e1de98', '#000000')
}, {
    dataIndex: 'numero_reinyecciones',
    header: 'No.<br>Reinyecciones<br>Método',
    tooltip: 'N&uacute;mero de reinyecciones',
    width: 90,
    align: 'center',
    renderer: generarRenderer('#ff5454', '#000000')
}, {
    dataIndex: 'numero_reinyecciones_dia',
    header: 'No.<br>Reinyecciones<br>Día',
    tooltip: 'N&uacute;mero de reinyecciones en el día',
    width: 90,
    align: 'center',
    renderer: generarRenderer('#ff5454', '#000000')
}];

var rdmuin_grid = new Ext.grid.GridPanel({
    autoWidth: true,
    height: 320,
    border: true,
    frame: true,
    store: rdmuin_datastore,
    stripeRows: true,
    loadMask: {
        msg: 'Cargando...'
    },
    columns: rdmuin_columns
});

    


var reportediario_contenedor = new Ext.Panel({
    renderTo: 'panel_principal_reporte_diario',
    border: true,
    frame: true,
    //height: 520,
    items: [{
        border: true,
        frame: true,
        items: [{
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
        xtype: 'tabpanel',
        activeTab: 0,
        items: [{
            title: 'Tiempos',
            border: false,
            items: [rdtiemp_grid]
        }, {
            title: 'Indicadores',
            border: false,
            items: [rdindic_grid]
        }, {
            title: 'P&eacute;rdidas',
            border: false,
            items: [rdperdi_grid]
        }, {
            title: 'Muestras e inyecciones',
            border: false,
            items: [rdmuin_grid]
        }],
        listeners: {
            tabchange: function(){
                redirigirSiSesionExpiro();
            }
        }
    }]
});

actualizarHora();

window.setInterval(actualizarHora, 1000);
