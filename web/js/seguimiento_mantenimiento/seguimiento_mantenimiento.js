Ext.onReady(function(){
    Ext.BLANK_IMAGE_URL = urlPrefix + '../css/extjs/resources/images/default/s.gif';

var fechaActual = new Date();
var mesActual = fechaActual.getMonth();
var anoActual = fechaActual.getYear();

var cantidadDias = 31;
    
fields = [
    {
        type: 'string',
        name: 'nombre_equipo'
    }];

for (var i=1;i<=cantidadDias;i++)
{ 
    fields.push({ type: 'string', name: 'dia ' + i });
}
    
    var columnHeaderGroup = new Ext.ux.grid.ColumnHeaderGroup({
        rows: [[{
            header: '',
            colspan: 1,
            align: 'center'
        }, {
            header: '<h3>D&iacute;as del Mes</h3>',
            colspan: cantidadDias,
            align: 'center'
        }]]
    });
    
    var datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('seguimiento_mantenimiento', 'listarRegistrosPeriodoMaquina'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, fields)
    });
    
    var meses_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('seguimiento_mantenimiento', 'listarMeses'),
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
    
    meses_datastore.load({
        callback: function(){            
            meses_combobox.setValue(mesActual+1);
            recargarDatosMetodos();
        }
    });
    
    var meses_combobox = new Ext.form.ComboBox({
        fieldLabel: 'Mes',
        store: meses_datastore,
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
    
    var anos_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('seguimiento_mantenimiento', 'listarAnos'),
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
    
    anos_datastore.load({
        callback: function(){            
            anos_combobox.setValue((anoActual-113)+1);
            recargarDatosMetodos();
        }
    });
    
    var anos_combobox = new Ext.form.ComboBox({
        fieldLabel: 'Año',
        store: anos_datastore,
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
            url: getAbsoluteUrl('seguimiento_mantenimiento', 'listarEquiposActivos'),
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
    
    maquinas_datastore.load({
        callback: function(){
            maquinas_datastore.loadData({
                data: [{
                    'codigo': '-1',
                    'nombre': 'TODOS'
                }]
            }, true);
            maquinas_combobox.setValue('1');
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
        if (meses_combobox.isValid() && maquinas_combobox.isValid()) {
            datastore.load({
                callback: callback,
                params: {
                    'codigo_mes': meses_combobox.getValue(),
                    'codigo_maquina': maquinas_combobox.getValue(),
                    'codigo_ano': anos_combobox.getValue()
                }
            });
        }
    }    
    recargarDatosMetodos();
       
    var coloresDia = [];
    coloresDia['1 Día'] = randomColor();
    for(var j=2; j<=31; j++) {
        coloresDia[j+' Días'] = randomColor();
    }
    var coloresMes = [];
    coloresMes['1 Mes'] = randomColor();
    for(var k=2; k<=60; k++) {
        coloresMes[k+' Meses'] = randomColor();
    }
    
    var generarRenderer = function(colorFondoPar, colorFuentePar, colorFondoImpar, colorFuenteImpar)
    {
        return function(valor, metaData, record, rowIndex, colIndex, store)
        {
            if(valor == '1 Día')
                return '<div style="background-color: ' + coloresDia['1 Día'] + '; color: ' + colorFuentePar + '">' + valor + '</div>';
            if(valor == '1 Mes')
                return '<div style="background-color: ' + coloresMes['1 Mes'] + '; color: ' + colorFuentePar + '">' + valor + '</div>';
            for(var j=2; j<=31; j++) {                
                if(valor == (j+' Días'))
                    return '<div style="background-color: ' + coloresDia[valor] + '; color: ' + colorFuentePar + '">' + valor + '</div>';
            }            
            for(var k=2; k<=60; k++) {                
                if(valor == (k+' Meses'))
                    return '<div style="background-color: ' + coloresMes[valor] + '; color: ' + colorFuentePar + '">' + valor + '</div>';
            }            
        }
    }
        
    var columns = [
    {
        dataIndex: 'nombre_equipo',
        header: 'Nombre de Equipo',
        tooltip: 'Nombre del Equipo',
        width: 150,
        align: 'center'
    }];    
    
    for (var i = 1; i <= cantidadDias; i++) {
        columns.push({
            dataIndex: 'dia ' + i,
            header: 'D&iacute;a ' + i,
            tooltip: 'D&iacute;a ' + i,
            width: 57,
            align: 'center',
//            editor: {
//                xtype: 'numberfield',
//                allowNegative: false,
//                maxValue: 100000
//            },            
            renderer : generarRenderer('#ffdc44', '#000000', '#ffdc44', '#000000')
        });
    }

    var grid = new Ext.grid.EditorGridPanel({
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
//        plugins: columnHeaderGroup,
        columns: columns
    });
    
    var panelPrincipal = new Ext.FormPanel({
        title: 'Seguimiento a Mantenimientos',
        renderTo: 'panel_principal_seguimiento',
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
                    labelWidth: 50,
                    footer: false,
                    items: [meses_combobox, maquinas_combobox]
                }, {
                    width: '220',
                    layout: 'form',
                    labelWidth: 45,
                    items: [anos_combobox]
                }, {
                    xtype: 'button',
                    iconCls: 'exportar_excel',
                    text: 'Guardar en formato Excel',
                    handler: function(){
                        redirigirSiSesionExpiro();
                        
                        if (maquinas_combobox.isValid()) {
                            var params = 'codigo_maquina=' + maquinas_combobox.getValue() + '&codigo_ano=' + anos_combobox.getValue() + '&codigo_mes=' + meses_combobox.getValue();
                            window.location = getAbsoluteUrl('seguimiento_mantenimiento', 'exportar') + '?' + params;
                        }                       
                    }
                }]
            }]
        }, grid]
    });
});

function getDias(codigomes){
    var cantidadDias = 0;
    if((codigomes==1)||(codigomes==3)||(codigomes==5)||(codigomes==7)||(codigomes==8)||(codigomes==10)||(codigomes==12)){
        cantidadDias += 31;
    }
    if((codigomes==4)||(codigomes==6)||(codigomes==9)||(codigomes==11)){
        cantidadDias += 30;
    }
    if(codigomes==2){
        var fecha = new Date();
        var ano = fecha.getYear();
        if((ano%4)!=0)
            cantidadDias += 28;
        else
            cantidadDias += 29;
    }
    return cantidadDias;
}

function randomColor() {
    var str = '#';
    for(var i = 0 ; i < 6 ; i++) {
        var randNum = Math.floor(Math.random() * 16);
        switch (randNum) {
            case 10: randNum = 'A'; break;
            case 11: randNum = 'B'; break;
            case 12: randNum = 'C'; break;
            case 13: randNum = 'D'; break;
            case 14: randNum = 'E'; break;
            case 15: randNum = 'F'; break;
        }
        str += randNum;
    }
    return str;
}