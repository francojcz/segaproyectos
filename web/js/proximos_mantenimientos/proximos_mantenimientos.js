Ext.onReady(function(){
    Ext.BLANK_IMAGE_URL = urlPrefix + '../css/extjs/resources/images/default/s.gif';

var fechaActual = new Date();
var mesActual = fechaActual.getMonth();
var anoActual = fechaActual.getYear();
    
fields = [
  {
    type : 'int',
    name : 'id_registro_rep_maquina'
  }, {
    type : 'string',
    name : 'nombre_equipo'
  }, {
    type : 'string',
    name : 'nombre_parte'
  }, {
    type : 'string',
    name : 'numero_parte'
  }, {
    type : 'string',
    name : 'rrm_fecha_cambio'
  }, {
    type : 'string',
    name : 'rrm_fecha_prox_cambio'
  }, {
    type : 'string',
    name : 'rrm_observaciones'
  }, {
    type : 'string',
    name : 'rrm_usu_registra'
  }, {
    type : 'string',
    name : 'rrm_fecha_registro'
  }, {
    type : 'string',
    name : 'rrm_usu_actualiza'
  }, {
    type : 'string',
    name : 'rrm_fecha_actualizacion'
  }, {
    type : 'int',
    name : 'rrm_consumo'
  }, {
    type : 'int',
    name : 'item_codigo'
  }, {
    type : 'string',
    name : 'item_numero'
  }, {
    type : 'string',
    name : 'item_nombre'
  }];
   
    var datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('proximos_mantenimientos', 'listarRegistrosRepuestoMaquina'),
            method: 'POST'
        }),
        reader: new Ext.data.JsonReader({
            root: 'data'
        }, fields)
    });
    
    var estados_datastore_combo = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('proximos_mantenimientos', 'listarEstados'),
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
    estados_datastore_combo.load();
    
    var estado_para_agregar_combobox = new Ext.form.ComboBox(
    {
        xtype: 'combobox',
        labelStyle: 'text-align:right;',
        fieldLabel: 'Estado',
        store: estados_datastore_combo,
        mode: 'local',
        emptyText: 'Seleccione un Estado',
        displayField: 'nombre',
        valueField: 'codigo',
        triggerAction: 'all',
        forceSelection: true,
        allowBlank: false
    });
    
    var observacion_campo_texto = new Ext.form.TextField(
    {
        emptyText: 'Ingrese una observación',
        allowBlank: true,
        width: 200
    });
    
    var registros_estadosproximos_datastore = new Ext.data.Store(
    {
        proxy : new Ext.data.HttpProxy(
        {
          url : getAbsoluteUrl('proximos_mantenimientos', 'listarEstadosProximos'),
          method : 'POST'
        }),
        reader : new Ext.data.JsonReader(
        {
          root : 'data'
        }, 
        [
            {
              name : 'codigo',
              type : 'integer'
            },
            {
              name : 'fecha',
              type : 'string'
            },
            {
              name : 'estado',
              type : 'string'
            },
            {
              name : 'observacion',
              type : 'string'
            },
            {
              name : 'usu_registra',
              type : 'string'
            }
        ])
    }); 
    
    var meses_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('proximos_mantenimientos', 'listarMeses'),
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
            recargarDatosMantenimientos();
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
                recargarDatosMantenimientos();
            }
        }
    });
        
    var anos_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('proximos_mantenimientos', 'listarAnos'),
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
            recargarDatosMantenimientos();
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
                recargarDatosMantenimientos();
            }
        }
    });
    
    var maquinas_datastore = new Ext.data.Store({
        proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('proximos_mantenimientos', 'listarEquiposActivos'),
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
            maquinas_combobox.setValue('-1');
            recargarDatosMantenimientos();
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
                recargarDatosMantenimientos();
            }
        }
    });
    
    var recargarDatosMantenimientos = function(callback){
        redirigirSiSesionExpiro();
        if (maquinas_combobox.isValid()) {
            datastore.load({
                callback: callback,
                params: {                    
                    'codigo_maquina': maquinas_combobox.getValue(),
                    'codigo_mes': meses_combobox.getValue(),
                    'codigo_ano': anos_combobox.getValue()
                }
            });
        }
    }
    recargarDatosMantenimientos();
    
    var generarRenderer = function(colorFondoPar, colorNegro, colorRojo, colorBlanco)
    {
        return function(valor, metaData, record, rowIndex, colIndex, store)
        {
            if((valor.charAt(valor.length-4))==='e')
                return '<div style="background-color: ' + colorBlanco + '; color: ' + colorNegro + '">' + valor.substring(0,valor.length-10) + '</div>';
            if((valor.charAt(valor.length-4))==='z')
                return '<div style="background-color: ' + colorBlanco + '; color: ' + colorBlanco + '">' + valor.substring(0,valor.length-10) + '</div>';
            if((valor.charAt(valor.length-4))==='c')
                return '<div style="background-color: ' + colorBlanco + '; color: ' + colorRojo + '">' + valor.substring(0,valor.length-8) + '</div>';
        }
    }
    
    var columns = [
        {
            dataIndex: 'nombre_equipo',
            header: 'Nombre de Equipo',
            tooltip: 'Nombre del Equipo',
            width: 240,
            align: 'center',
            renderer : generarRenderer('#ffdc44', '#000000', '#FF0000', '#FFFFFF')
        }, {
            dataIndex: 'nombre_parte',
            header: 'Nombre de Parte',
            tooltip: 'Nombre de Parte',
            width: 210,
            align: 'center',
            renderer : generarRenderer('#ffdc44', '#000000', '#FF0000', '#FFFFFF')
        }, {
            dataIndex: 'numero_parte',
            header: 'Número de Parte',
            tooltip: 'Número de Parte',
            width: 170,
            align: 'center',
            renderer : generarRenderer('#ffdc44', '#000000', '#FF0000', '#FFFFFF')
        }, {
            dataIndex: 'rrm_fecha_cambio',
            header: 'Fecha Próximo Cambio',
            tooltip: 'Fecha Próximo Cambio',
            width: 200,
            align: 'center',
            renderer : generarRenderer('#ffdc44', '#000000', '#FF0000', '#FFFFFF')
        }
    ];

    var grid = new Ext.grid.GridPanel({
        autoWidth: true,
        height: 400,
        store: datastore,
        stripeRows: true,
        border: true,
        frame: true,
        autoScroll: true,
        columnLines: true,
        loadMask: {
            msg: 'Cargando...'
        },
        columns: columns
    });
    
    var recargarDatosEstados = function(callback)
    {
        var record = grid.getSelectionModel().getSelected();
        registros_estadosproximos_datastore.load(
        {
          params :
          {
            'cod_registro' : record.get('id_registro_rep_maquina')
          }
        });
    }  
    
    var grillaEstados = new Ext.grid.GridPanel(
    {
        autoWidth : true,
        height : 400,
        store : registros_estadosproximos_datastore,
        stripeRows : true,
        clicksToEdit : 1,
        loadMask :
        {
          msg : 'Cargando...'
        },
        sm : new Ext.grid.RowSelectionModel(
        {
          singleSelect : true
        }),
        tbar : [
        estado_para_agregar_combobox, '-', observacion_campo_texto, '-',
        {
          text : 'Agregar estado',
          iconCls : 'agregar',
          handler : function()
          { 
            var record = grid.getSelectionModel().getSelected();
            var id_estado = estado_para_agregar_combobox.getValue();
            if(id_estado === '')
            {
              alert('Primero debe seleccionar un estado'); 
              estado_para_agregar_combobox.focus();
            }
            else { 
                Ext.Ajax.request({
                  url : getAbsoluteUrl('proximos_mantenimientos', 'registrarEstado'),
                  failure : function()
                  {
                    recargarDatosEstados();
                  },
                  success : function(result)
                  {                
                    var mensaje = null;
                    switch(result.responseText)
                    {
                      case 'Ok': recargarDatosEstados();
                        break;
                      case '1':
                        mensaje = 'No es posible agregar un estado a una fecha pasada';
                        break;
                      case '2':
                        mensaje = 'Solo puede registrar un estado por día';
                        break;
                    }
                    if(mensaje != null)
                    {
                      Ext.Msg.show(
                      {
                        title : 'Información',
                        msg : mensaje,
                        buttons : Ext.Msg.OK,
                        icon : Ext.MessageBox.INFO
                      });
                    }
                  },
                  params :
                  {
                    'cod_prox' : record.get('id_registro_rep_maquina'),
                    'estado_prox' : estado_para_agregar_combobox.getValue(),
                    'observacion_prox' : observacion_campo_texto.getValue()
                  }
                });
            }
          }
        }, '-',
        {
          text : 'Eliminar estado',
          iconCls : 'eliminar',
          handler : function()
          {
                var record1 = grid.getSelectionModel().getSelected();
                var record2 = grillaEstados.getSelectionModel().getSelected();
                Ext.Ajax.request({
                  url : getAbsoluteUrl('proximos_mantenimientos', 'eliminarEstado'),
                  failure : function()
                  {
                    recargarDatosEstados();
                  },
                  success : function(result)
                  {
                    var mensaje = null;
                    switch(result.responseText)
                    {
                      case 'Ok': recargarDatosEstados();
                        break;
                      case '1':
                        mensaje = 'No es posible eliminar un estado de una fecha pasada';
                        break;
                    }
                    if(mensaje != null)
                    {
                      Ext.Msg.show(
                      {
                        title : 'Información',
                        msg : mensaje,
                        buttons : Ext.Msg.OK,
                        icon : Ext.MessageBox.INFO
                      });
                    }
                  },
                  params :
                  {
                    'codigo' : record2.get('codigo'),
                    'codigo_prox' : record1.get('id_registro_rep_maquina'),
                  }
                });
          }
        }
        ]
        ,
        columns : [
        {
          dataIndex : 'fecha',
          header : 'Fecha',
          tooltip : 'Fecha',
          width : 100,
          align : 'center',
          editor :
          { 
              xtype : 'datefield',
              allowBlank : false
          }
        },
        {
          dataIndex : 'estado',
          header : 'Estado',
          tooltip : 'Estado',
          width : 150,
          align : 'center',
          editor : new Ext.form.TextField()
        },
        {
          dataIndex : 'observacion',
          header : 'Observación',
          tooltip : 'Observación',
          width : 300,
          align : 'center',
          editor : new Ext.form.TextField()
        },
        {
          dataIndex : 'usu_registra',
          header : 'Creado por',
          tooltip : 'Creado por',
          width : 180,
          align : 'center',
          editor : new Ext.form.TextField()
        }]
      });
      
      var win = new Ext.Window(
    {
        layout : 'fit',
        width : 800,
        height : 300,
        closeAction : 'hide',
        plain : true,
        title : 'Estados',
        items : grillaEstados,
        buttons : [
        {
          text : 'Aceptar',
          handler : function()
          {
            win.hide();
            recargarDatosMantenimientos();
          }
        }],
        listeners :
        {
          hide : function()
          {
            Ext.getBody().unmask();
          }
        }
    }); 
    
    var panelPrincipal = new Ext.FormPanel({
        title: 'Próximos Mantenimientos',
        renderTo: 'panel_principal_proximos',
        border: true,
        frame: true,
        layout: 'form',
        height: 500,
        items: [{
            border: true,
            frame: true,
            height: 40,
            items: [{
                height: 40,
                layout: 'column',
                items: [{
                    width: '220',
                    layout: 'form',
                    labelWidth: 40,
                    footer: false,
                    items: [meses_combobox]
                }, {
                    width: '220',
                    layout: 'form',
                    labelWidth: 45,
                    footer: false,
                    items: [anos_combobox]
                }, {
                    width: '220',
                    layout: 'form',
                    labelWidth: 40,
                    footer: false,
                    items: [maquinas_combobox]
                }, {
                    xtype: 'button',
                    text : 'Editar estado',
                    tooltip: 'Editar estado de Mantenimiento',
                    iconCls : 'evento',
                    style: 'padding: 0px 25px 0px 0px',
                    handler : function()      
                    {           
                        redirigirSiSesionExpiro();
                        var sm = grid.getSelectionModel();
                        if(sm.hasSelection())
                        {
                            recargarDatosEstados();
                            Ext.getBody().mask();
                            win.show();                         
                        } else
                        {
                          Ext.Msg.show(
                          {
                            title : 'Información',
                            msg : 'Primero debe seleccionar un registro',
                            buttons : Ext.Msg.OK,
                            icon : Ext.MessageBox.INFO
                          });
                        }
                      }
                 }, {
                    xtype: 'button',
                    iconCls: 'exportar_excel',
                    text: 'Guardar en formato Excel',
                    handler: function(){
                        redirigirSiSesionExpiro();                        
                        if (maquinas_combobox.isValid()) {
                            var params = 'codigo_maquina=' + maquinas_combobox.getValue() + '&codigo_ano=' + anos_combobox.getValue() + '&codigo_mes=' + meses_combobox.getValue();
                            window.location = getAbsoluteUrl('proximos_mantenimientos', 'exportar') + '?' + params;
                        }         
                    }
                }]
            }]
        }, grid]
    });
});