var largo_panel = 500;

Ext.onReady(function()
{
  fields = [
  {
    type : 'int',
    name : 'id_registro_rep_maquina'
  }, {
    type : 'int',
    name : 'rrm_maq_codigo'
  }, {
    type : 'int',
    name : 'rrm_rep_codigo'
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

  var repuestos_datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('mantenimiento_autonomo', 'listarRepuestos'),
      method : 'POST'
    }),
    reader : new Ext.data.JsonReader(
    {
      root : 'data'
    }, [
    {
      name : 'codigo',
      type : 'integer'
    },
    {
      name : 'nombre',
      type : 'string'
    },
    {
      name : 'numero',
      type : 'integer'
    },
    {
      name : 'cantidad',
      type : 'integer'
    }])
  });
  
  var datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('mantenimiento_autonomo', 'listarRegistrosRepuestoMaquina'),
      method : 'POST'
    }),
    reader : new Ext.data.JsonReader(
    {
      root : 'data'
    }, fields)
  });
  
  var items_datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('mantenimiento_autonomo', 'listarItemsPorEquipo'),
      method : 'POST'
    }),
    reader : new Ext.data.JsonReader(
    {
      root : 'data'
    }, fields)
  });  

  var maquinas_datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('mantenimiento_autonomo', 'listarEquiposPorCategoria'),
      method : 'POST'
    }),
    reader : new Ext.data.JsonReader(
    {
      root : 'data'
    }, [
    {
      name : 'codigo',
      type : 'string'
    },
    {
      name : 'nombre',
      type : 'string'
    },
    {
      name : 'codigo_inventario',
      type : 'string'
    }])
  });
  maquinas_datastore.load();
   
  var grupoequipo_field = null;
  var grupoequipos_datastore = new Ext.data.Store(
  {
      proxy : new Ext.data.HttpProxy(
      {
        url : getAbsoluteUrl('mantenimiento_autonomo','listarCategoriasEquipo'),
        method : 'POST'
      }),
      reader : new Ext.data.JsonReader(
      {
        root : 'data'
      }, [
      {
        name : 'codigo',
        type : 'string'
      },
      {
        name : 'nombre',
        type : 'string'
      }])
  });
    
  grupoequipo_field = new Ext.form.ComboBox(
  {
      labelStyle: 'text-align:right;',
      fieldLabel : 'Grupo equipo',
      store : grupoequipos_datastore,
      displayField : 'nombre',
      valueField : 'codigo',
      mode : 'local',
      triggerAction : 'all',
      forceSelection : true,
      emptyText: 'Seleccione ...',
      allowBlank : false,
      listeners :
      {
      select : function()
      {
        var myMask = new Ext.LoadMask(Ext.get('crud_mantenimiento_autonomo_panel', 'grid_items'),
        {
          msg : "Por favor, espere..."         
        });
        maquinas_datastore.load(
        {
          params :
          {
            'codigo_categoria' : grupoequipo_field.getValue()
          },
          callback : function()
          {
            myMask.hide();
          }
        });
        items_datastore.load(
        {
          params :
          {
            'codigo_categoria' : grupoequipo_field.getValue()
          },
          callback : function()
          {
            myMask.hide();
          }
        });
        myMask.show();
      }
      }
  });
  grupoequipos_datastore.load();
    
  var codigo_maquina = new Ext.form.TextField(
  {
    fieldLabel : 'Código',
    readOnly : true
  });  

  var maquina_combobox = new Ext.form.ComboBox(
  {
    labelStyle: 'text-align:right;',
    fieldLabel : 'Equipo',
    store : maquinas_datastore,
    displayField : 'nombre',
    valueField : 'codigo',
    mode : 'local',
    triggerAction : 'all',
    emptyText: 'Seleccione ...',
    forceSelection : true,
    allowBlank : false,
    listeners :
    {
      select : function(combo, registro, indice)
      {
        codigo_maquina.setValue(registro.get('codigo_inventario'));
        recargarDatosMetodos(function()
        {
          grid.enable();
          grid_items.enable();
        });
      }
    }
  });

var fechaField = new Ext.form.DateField(
 {    
    xtype : 'datefield',
    fieldLabel : 'Fecha de Registro',
    allowBlank : false,
    value : new Date(),
    labelStyle: 'text-align:right;',
    listeners :
    {
      select : function()
      {
        recargarDatosMetodos();
      },
      specialkey : function(field, e)
      {
        if(e.getKey() == e.ENTER)
        {
          recargarDatosMetodos();
        }
      }
    }
});
  
 var recargarDatosMetodos = function(callback)
  {
    redirigirSiSesionExpiro();
    if(maquina_combobox.isValid() && fechaField.isValid())
    {
      repuestos_datastore.load(
      {
        callback : function()
        {
            var params =
            {
              'codigo_maquina' : maquina_combobox.getValue(),
              'fecha_registro' : fechaField.getValue()
            };
            datastore.load(
            {
              callback : callback,
              params : params
            });
        }
      });
    }
  }
  recargarDatosMetodos();

  var generarRenderer = function(colorFondoPar, colorFuentePar, colorFondoImpar, colorFuenteImpar)
  {
    return function(valor, metaData, record, rowIndex, colIndex, store)
    {
      if(valor != '0.00') {
          return '<div style="background-color: ' + colorFondoPar + '; color: ' + colorFuentePar + '">' + valor + '</div>';
      }         
    }
  }

  var columns = [
  {
    dataIndex : 'rrm_rep_codigo',
    header : 'Nombre Parte',
    tooltip : 'Nombre Parte ',
    width : 160,
    align : 'center',
    renderer : function(value)
    {
      var index = repuestos_datastore.find('codigo', value);
      var record = repuestos_datastore.getAt(index);
      return record.get('nombre');      
    }      
  },{
    dataIndex : 'rrm_rep_codigo',
    header : 'Número Parte',
    tooltip : 'Número Parte ',
    width : 80,
    align : 'center',
    renderer : function(value)
    {
      var index = repuestos_datastore.find('codigo', value);
      var record = repuestos_datastore.getAt(index);
      return record.get('numero');      
    }      
  },{
    dataIndex : 'rrm_rep_codigo',
    header : 'Existencias',
    tooltip : 'Existencias (Cantidad)',
    width : 70,
    align : 'center',
    renderer : function(value)
    {
      var index = repuestos_datastore.find('codigo', value);
      var record = repuestos_datastore.getAt(index);
      return record.get('cantidad');      
    }       
  },{
    dataIndex : 'rrm_consumo',
    header : '<b>Consumo</b>',
    tooltip : 'Consumo (Salida)',
    width : 75,
    align : 'center',
    editor :
    {    
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#ffdc44', '#000000', '#ffdc44', '#000000')
  },{
    dataIndex : 'rrm_fecha_cambio',
    header : '<b>Fecha Cambio</b>',
    tooltip : 'Fecha de Cambio',
    width : 100,
    align : 'center',
    editor :
    { 
        xtype : 'datefield',
        allowBlank : false
    },
    renderer : generarRenderer('#ffdc44', '#000000', '#ffdc44', '#000000')
  },{
    dataIndex : 'rrm_fecha_prox_cambio',
    header : 'Fecha Próx. Cambio',
    tooltip : 'Fecha de Próximo Cambio',
    width : 110,
    align : 'center'
  },{
    dataIndex : 'rrm_observaciones',
    header : '<b>Observaciones</b>',
    tooltip : 'Observaciones',
    width : 165,
    align : 'center',
    editor : new Ext.form.TextField(),
    renderer : generarRenderer('#ffdc44', '#000000', '#ffdc44', '#000000')
  },{
    dataIndex : 'rrm_usu_registra',
    header : 'Registrador por',
    tooltip : 'Registrado por',
    width : 130,
    align : 'center'
  },{
    dataIndex : 'rrm_fecha_registro',
    header : 'Fecha de registro',
    tooltip : 'Fecha de registro',
    width : 120,
    align : 'center'
  },{
    dataIndex : 'rrm_usu_actualiza',
    header : 'Actualizado por',
    tooltip : 'Actualizado por',
    width : 130,
    align : 'center'
  },{
    dataIndex : 'rrm_fecha_actualizacion',
    header : 'Fecha de actualización',
    tooltip : 'Fecha de actualización',
    width : 140,
    align : 'center'
  }]; 

  var modificarRegistroUsoMaquina = function(idRegistro, nombreCampo, valorCampo, par, params, callback)
  {
      params['id_registro_rep_maquina'] = idRegistro;
      params[nombreCampo] = valorCampo;
      Ext.Ajax.request(
      {
        url : getAbsoluteUrl('mantenimiento_autonomo', 'modificarRegistroUsoMaquina'),
        failure : function()
        {
          recargarDatosMetodos(callback);
        },
        success : function(result)
        {
            recargarDatosMetodos(callback);
            var mensaje = null;
            switch(result.responseText)
            {
              case '1':
                mensaje = 'La cantidad ingresada en Consumo es mayor a la cantidad disponible en Existencias';
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
        params : params
      });
  }  
  
  var grid = new Ext.grid.EditorGridPanel(
  {
    autoWidth : true,
    region : 'center',
    //height: 340,
    //autoHeight: true,
    store : datastore,
    stripeRows : true,
    frame : true,
    border : true,
    autoScroll : true,
    columnLines : true,
    disabled : true,
    loadMask :
    {
      msg : 'Cargando...'
    },
    columns : columns,
    listeners :
    {
      afteredit : function(e)
      {
        var row = null;
        var column = null;
        var sm = grid.getSelectionModel();
        if(sm.hasSelection())
        {
          var cell = sm.getSelectedCell();
          var row = cell[0];
          var column = cell[1];
          var cm = grid.getColumnModel();
          if(column == (cm.getColumnCount() - 1))
          {
            if(row == (datastore.getCount() - 1))
            {
              column = 0;
              row = 0;
            } else
            {
              column = 0;
              row++;
            }
          } else
          {
            column++;
          }
        }
        var callback = function()
        {
          sm.select(row, column);
        }
        var par = (e.row % 2) == 0;
        var params =
        {
        };        
          modificarRegistroUsoMaquina(e.record.get('id_registro_rep_maquina'), e.field, e.value, par, params, callback);
      }
    },
    bbar: new Ext.PagingToolbar({
        pageSize: 15,
        store: datastore,
        displayInfo: true,
        displayMsg: 'Ítems {0} - {1} de {2}',
        emptyMsg: "No se le han asignado ítems al equipo aún"
    })
  }); 
   
  var itemporequipo_selmodel = new Ext.grid.CheckboxSelectionModel({
        singleSelect:false,	
        listeners: {
                rowselect: function(sm, row, rec) {
                }
        }
  });
   
  var itemporequipo_colmodel = new Ext.grid.ColumnModel({
        defaults:{sortable: true, locked: true, resizable: true},
        columns:[
                itemporequipo_selmodel,
                { header: "Id", width: 30, dataIndex: 'item_codigo', hidden: true},
                { header: "Nombre Parte", width: 230, dataIndex: 'item_nombre'}
        ]
  });
    
  var grid_items = new Ext.grid.GridPanel(
  {
    id: 'grid_items',
    title: 'Repuestos / Consumibles',
    stripeRows: true,      
    autoWidth : true,
    region : 'center',
    height: 375,
    ds: items_datastore,
    cm: itemporequipo_colmodel,
    sm: itemporequipo_selmodel,
    frame : true,
    border : true,
    autoScroll : true,
    columnLines : true,
    disabled : true,
    bbar:[{
      text:'Agregar Parte',iconCls:'agregar',
      handler:function(){
          var itemsSelecionados = grid_items.selModel.getSelections();
          var itemsAgregar = [];
          for(i = 0; i< grid_items.selModel.getCount(); i++) {
              itemsAgregar.push(itemsSelecionados[i].json.item_codigo);
          }
          var encoded_array_items = Ext.encode(itemsAgregar);
          
          if(maquina_combobox.getValue()!='') {
              subirDatosAjax(
                   getAbsoluteUrl('mantenimiento_autonomo', 'agregarItemsPorEquipo'),
                   {
                       items_codigos: encoded_array_items,
                       equipo_codigo: maquina_combobox.getValue() 
                   },
                   function(){
                       recargarDatosMetodos();
                   },
                   function(){}
              );
          }
      }
    }]    
  }); 
 
//Paneles
var items_formpanel = new Ext.FormPanel({
    id: 'items_formpanel',
    frame: true,
    region: 'east',
    split: true,
    collapsible: true,
    width: 350,
    border: true,
    title: 'Equipo detalle',    
    columnWidth: '0.6',
    height: 470,
    layout: 'form',
    bodyStyle: 'padding:10px;',
    labelWidth: 105,
    defaults: { anchor: '98%' },
    items: [grupoequipo_field, maquina_combobox, fechaField, grid_items]
});

  var panelPrincipal = new Ext.FormPanel(
  {
    id: 'panelPrincipal',
    title: 'Mantenimiento Autónomo',
    columnWidth: '.4',
    region: 'center',
    stripeRows: true,    
    frame: true,
    layout : 'border',
    items : [grid],
    tbar: 
    [{
        text:'Eliminar Registro',
        tooltip:'Eliminar Registro',
        iconCls:'eliminar',
        handler : function()
        {
        var sm = grid.getSelectionModel();
        if(sm.hasSelection())
        {
          Ext.Msg.confirm('Eliminar registro', "Esta operación es irreversible. ¿Está seguro(a) de querer eliminar este registro?", function(idButton)
          {
            if(idButton == 'yes')
            {
              var cell = sm.getSelectedCell();
              var index = cell[0];
              var registro = datastore.getAt(index);
              Ext.Ajax.request(
              {
                url : getAbsoluteUrl('mantenimiento_autonomo', 'eliminarRegistroRepuestoEquipo'),
                failure : function()
                {
                  recargarDatosMetodos();
                },
                success : function(result)
                {
                  recargarDatosMetodos();
                  if(result.responseText != 'Ok')
                  {
                    alert(result.responseText);
                  }
                },
                params :
                {
                  'id_registro_rep_maquina' : registro.get('id_registro_rep_maquina')
                }
              });
            }
          });
        } else
        {
          Ext.Msg.show(
          {
            title : 'Información',
            msg : 'Primero debe seleccionar un método',
            buttons : Ext.Msg.OK,
            icon : Ext.MessageBox.INFO
          });
        }
      }
    }]
  });
      
  var crud_mantenimiento_autonomo_panel = new Ext.Panel({
    id: 'crud_mantenimiento_autonomo_panel',
    height: largo_panel,
    autoWidth: true,
    border: false,
    tabTip: 'Aqui puedes ver, agregar, eliminar equipos',
    monitorResize: true,
    layout: 'border',
    items: [panelPrincipal, items_formpanel],
    buttonAlign: 'left',
    renderTo: 'div_form_mantenimiento_autonomo'
});
});
