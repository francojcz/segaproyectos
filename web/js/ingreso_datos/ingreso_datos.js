var tiempoInyeccion = 0;
Ext.onReady(function()
{
  Ext.BLANK_IMAGE_URL = urlPrefix + '../css/extjs/resources/images/default/s.gif';
  fields = [
  {
    type : 'int',
    name : 'id_registro_uso_maquina'
  },
  {
    type : 'string',
    name : 'id_metodo'
  },
  {
    type : 'string',
    name : 'tiempo_entre_metodos'
  },
  {
    type : 'string',
    name : 'cambio_metodo_ajuste'
  },
  {
    type : 'string',
    name : 'tiempo_corrida_ss'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_ss'
  },
  {
    type : 'string',
    name : 'tiempo_corrida_cc'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_estandar1'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_estandar2'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_estandar3'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_estandar4'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_estandar5'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_estandar6'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_estandar7'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_estandar8'
  },
  {
    type : 'string',
    name : 'tiempo_corrida_producto'
  },
  {
    type : 'string',
    name : 'tiempo_corrida_estabilidad'
  },
  {
    type : 'string',
    name : 'tiempo_corrida_materia_prima'
  },
  {
    type : 'string',
    name : 'tiempo_corrida_pureza'
  },
  {
    type : 'string',
    name : 'tiempo_corrida_disolucion'
  },
  {
    type : 'string',
    name : 'tiempo_corrida_uniformidad'
  },
  {
    type : 'string',
    name : 'numero_muestras_producto'
  },
  {
    type : 'string',
    name : 'numero_muestras_estabilidad'
  },
  {
    type : 'string',
    name : 'numero_muestras_materia_prima'
  },
  {
    type : 'string',
    name : 'numero_muestras_pureza'
  },
  {
    type : 'string',
    name : 'numero_muestras_disolucion'
  },
  {
    type : 'string',
    name : 'numero_muestras_uniformidad'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_x_muestra_producto'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_x_muestra_materia_prima'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_x_muestra_estabilidad'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_x_muestra_pureza'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_x_muestra_disolucion'
  },
  {
    type : 'string',
    name : 'numero_inyecciones_x_muestra_uniformidad'
  },
  {
    type : 'string',
    name : 'hora_inicio_corrida'
  },
  {
    type : 'string',
    name : 'hora_fin_corrida'
  },
  {
    type : 'string',
    name : 'fallas'
  },
  {
    type : 'string',
    name : 'observaciones'
  }];

  var columnHeaderGroup = new Ext.ux.grid.ColumnHeaderGroup(
  {
    rows : [[
    {
      header : '<h3>Informaci&oacute;n de<br>cambio de m&eacute;todo</h3>',
      colspan : 3,
      align : 'center'
    },
    {
      header : '<h3>Informaci&oacute;n<br>system<br>suitability</h3>',
      colspan : 2,
      align : 'center'
    },
    {
      header : '<h3>Informaci&oacute;n<br>estándares de<br>calibraci&oacute;n</h3>',
      colspan : (inyeccionesEstandarPromedio + 1),
      align : 'center'
    },
    {
      header : '<h3>Informaci&oacute;n de muestras</h3>',
      colspan : 18,
      align : 'center'
    },
    {
      header : '<h3>Inicio y fin<br>de an&aacute;lisis</h3>',
      colspan : 2,
      align : 'center'
    },
    {
      header : '',
      colspan : 1,
      align : 'center'
    },
    {
      header : '',
      colspan : 1,
      align : 'center'
    }]]
  });

  var metodosinorden_datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('ingreso_datos', 'listarMetodosSinOrden'),
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
    }])
  });
  
  var metodos_datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('ingreso_datos', 'listarMetodos'),
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
    }])
  });
  

  var datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('ingreso_datos', 'listarRegistrosUsoMaquina'),
      method : 'POST'
    }),
    reader : new Ext.data.JsonReader(
    {
      root : 'data'
    }, fields)
  });

  var fechaField = new Ext.form.DateField(
  {
    xtype : 'datefield',
    fieldLabel : 'Fecha',
    allowBlank : false,
    value : new Date(),
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

  var codigo_maquina = new Ext.form.TextField(
  {
    fieldLabel : 'Código',
    readOnly : true
  });

  var maquinas_datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('ingreso_datos', 'listarEquiposPorComputador'),
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

  var maquina_combobox = new Ext.form.ComboBox(
  {
    fieldLabel : 'Equipo',
    store : maquinas_datastore,
    displayField : 'nombre',
    valueField : 'codigo',
    mode : 'local',
    triggerAction : 'all',
    forceSelection : true,
    allowBlank : false,
    width : 130,
    listeners :
    {
      select : function(combo, registro, indice)
      {
        codigo_maquina.setValue(registro.get('codigo_inventario'));
        recargarDatosMetodos(function()
        {
          grid.enable();
        });
      }
    }
  });

  var recargarDatosMetodos = function(callback)
  {
    redirigirSiSesionExpiro();
    if(maquina_combobox.isValid() && fechaField.isValid())
    {
      metodosinorden_datastore.load(
      {
        callback : function()
        {
          if(maquina_combobox.getValue() != '')
          {
            var params =
            {
              'codigo_maquina' : maquina_combobox.getValue(),
              'fecha' : fechaField.getValue()
            };
            if(esAdministrador && operario_field.getValue != '')
            {
              params['codigo_operario'] = operario_field.getValue();
            }
            datastore.load(
            {
              callback : callback,
              params : params
            });
          }
        }
      });
      metodos_datastore.load(
      {
        callback : function()
        {
          if(maquina_combobox.getValue() != '')
          {
            var params =
            {
              'codigo_maquina' : maquina_combobox.getValue(),
              'fecha' : fechaField.getValue()
            };
            if(esAdministrador && operario_field.getValue != '')
            {
              params['codigo_operario'] = operario_field.getValue();
            }
            datastore.load(
            {
              callback : callback,
              params : params
            });
          }
        }
      });
      var flashContent = document.getElementById("flashcontent");
      if(flashContent)
      {
        var so = new SWFObject(urlWeb + "flash/amcolumn/amcolumn.swf", "amcolumn", "100%", "160", "8", "#FFFFFF");
        so.addVariable("path", urlWeb + "flash/amcolumn/");
        so.addParam("wmode", "opaque");
        so.addVariable("settings_file", encodeURIComponent(getAbsoluteUrl('ingreso_datos', 'generarConfiguracionGrafico?codigo_maquina=' + maquina_combobox.getValue() + '&fecha=' + Ext.util.Format.date(fechaField.getValue(), 'Y-m-d'))));
        so.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('ingreso_datos', 'generarDatosGrafico?codigo_maquina=' + maquina_combobox.getValue() + '&fecha=' + Ext.util.Format.date(fechaField.getValue(), 'Y-m-d'))));
        so.addVariable("preloader_color", "#999999");
        so.write("flashcontent");
      }
      
      var flashContent1 = document.getElementById("flashcontent1");
      if(flashContent1)
      {
        var so1 = new SWFObject(urlWeb + "flash/amcolumn/amcolumn.swf", "amcolumn", "100%", "160", "8", "#FFFFFF");
        so1.addVariable("path", urlWeb + "flash/amcolumn/");
        so1.addParam("wmode", "opaque");
        so1.addVariable("settings_file", encodeURIComponent(getAbsoluteUrl('ingreso_datos', 'generarConfiguracionGrafico1?codigo_maquina=' + maquina_combobox.getValue() + '&fecha=' + Ext.util.Format.date(fechaField.getValue(), 'Y-m-d'))));
        so1.addVariable("data_file", encodeURIComponent(getAbsoluteUrl('ingreso_datos', 'generarDatosGrafico1?codigo_maquina=' + maquina_combobox.getValue() + '&fecha=' + Ext.util.Format.date(fechaField.getValue(), 'Y-m-d'))));
        so1.addVariable("preloader_color", "#999999");
        so1.write("flashcontent1");
      }
      Ext.Ajax.request(
      {
        url : getAbsoluteUrl('ingreso_datos', 'calcularTiempoDisponibleDia'),
        failure : function()
        {
        },
        success : function(result)
        {
          var registro = datastore_calculadora1.getAt(0);
          registro.set('tiempo_disponible_horas', result.responseText);
          actualizarCalculadora();
        },
        params :
        {
          'codigo_maquina' : maquina_combobox.getValue(),
          'fecha' : fechaField.getValue()
        }
      });
      Ext.Ajax.request(
      {
        url : getAbsoluteUrl('ingreso_datos', 'consultarTiempoInyeccionMaquina'),
        failure : function()
        {
        },
        success : function(result)
        {
          tiempoInyeccion = result.responseText;
          actualizarCalculadora();
        },
        params :
        {
          'codigo_maquina' : maquina_combobox.getValue()
        }
      });
    }
  }
  recargarDatosMetodos();

  var generarRenderer = function(colorFondoPar, colorFuentePar, colorFondoImpar, colorFuenteImpar)
  {
    return function(valor, metaData, record, rowIndex, colIndex, store)
    {
      if( typeof valor != 'undefined')
      {
        if(valor == '0.00')
        {
          return valor;
        } else if((rowIndex % 2) == 0)
        {
          return '<div style="background-color: ' + colorFondoPar + '; color: ' + colorFuentePar + '">' + valor + '</div>';
        } else
        {
          return '<div style="background-color: ' + colorFondoImpar + '; color: ' + colorFuenteImpar + '">' + valor + '</div>';
        }
      } else
      {
        return valor;
      }
    }
  }
  var registros_eventos_datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('ingreso_datos', 'listarRegistrosEventos'),
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
      name : 'id_evento',
      type : 'string'
    },
    {
      name : 'hora_ocurrio',
      type : 'string'
    },
    {
      name : 'evrg_duracion',
      type : 'string'
    },
    {
      name : 'observaciones',
      type : 'string'
    }])
  });

  var rowEditor = new Ext.ux.grid.RowEditor(
  {
    saveText : 'Guardar',
    cancelText : 'Cancelar',
    errorSummary : false,
    onKey : function(f, e)
    {
      if(e.getKey() === e.ENTER && this.isValid())
      {
        this.stopEditing(true);
        e.stopPropagation();
      }
    },
    listeners :
    {
      'afteredit' : function()
      {
        var record = grillaEventos.getSelectionModel().getSelected();
        var sm = grid.getSelectionModel();
        var cell = sm.getSelectedCell();
        var index = cell[0];
        var registro = datastore.getAt(index);
        Ext.Ajax.request(
        {
          url : getAbsoluteUrl('ingreso_datos', 'modificarRegistroEvento'),
          failure : function()
          {
            recargarDatosEventos();
          },
          success : function(result)
          {
            recargarDatosEventos();
            if(result.responseText != 'Ok')
            {
              alert(result.responseText);
            }
          },
          params :
          {
            'codigo' : record.get('codigo'),
            'id_evento' : record.get('id_evento'),
            'hora_ocurrio' : record.get('hora_ocurrio'),
            'observaciones' : record.get('observaciones'),
            'evrg_duracion' : record.get('evrg_duracion'),
            'codigo_rum' : registro.get('id_registro_uso_maquina')
          }
        });
      },
      'canceledit' : function()
      {
        recargarDatosEventos();
      }
    }
  });

  var eventos_datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('ingreso_datos', 'listarEventos'),
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

  var eventos_datastore_combo = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('ingreso_datos', 'listarEventos'),
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

  var eventos_datastore_renderer = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('ingreso_datos', 'listarEventos'),
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

  var eventos_por_categoria_datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('ingreso_datos', 'listarEventosPorCategoria'),
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

  var categorias_datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('ingreso_datos', 'listarCategoriasEventos'),
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

  var recargarDatosEventos = function(callback)
  {
    eventos_datastore_combo.load();
    categorias_datastore.load();
    eventos_datastore_renderer.load(
    {
      callback : function()
      {
        var sm = grid.getSelectionModel();
        var cell = sm.getSelectedCell();
        var index = cell[0];
        var registro = datastore.getAt(index);
        registros_eventos_datastore.load(
        {
          params :
          {
            'codigo_rum' : registro.get('id_registro_uso_maquina')
          }
        });
      }
    });
  }
  
  var categoria_evento_combobox = new Ext.form.ComboBox(
  {
    store : categorias_datastore,
    displayField : 'nombre',
    valueField : 'codigo',
    mode : 'local',
    triggerAction : 'all',
    forceSelection : true,
    allowBlank : false,
    emptyText : 'Seleccione una categoría',
    listeners :
    {
      select : function()
      {
        var myMask = new Ext.LoadMask(Ext.get('ventana_flotante'),
        {
          msg : "Por favor, espere..."
        });
        eventos_por_categoria_datastore.load(
        {
          params :
          {
            'codigo_categoria' : categoria_evento_combobox.getValue()
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

  var evento_para_agregar_combobox = new Ext.form.ComboBox(
  {
    store : eventos_por_categoria_datastore,
    displayField : 'nombre',
    valueField : 'codigo',
    mode : 'local',
    triggerAction : 'all',
    forceSelection : true,
    allowBlank : false,
    emptyText : 'Seleccione un evento'
  });

  var grillaEventos = new Ext.grid.GridPanel(
  {
    autoWidth : true,
    height : 400,
    //autoHeight: true,
    store : registros_eventos_datastore,
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
    plugins : [rowEditor],
    tbar : [categoria_evento_combobox, '-', evento_para_agregar_combobox,
    {
      text : 'Agregar evento',
      iconCls : 'agregar',
      handler : function()
      {
        var id_evento = evento_para_agregar_combobox.getValue();
        if(id_evento == '')
        {
          alert('Primero debe seleccionar un evento');
          evento_para_agregar_combobox.focus();
        } else
        {
          var row = new grillaEventos.store.recordType(
          {
            'id_evento' : id_evento
          });
          grillaEventos.getSelectionModel().clearSelections();
          rowEditor.stopEditing();
          grillaEventos.store.insert(0, row);
          grillaEventos.getSelectionModel().selectRow(0);
          rowEditor.startEditing(0);
        }
      }
    }, '-',
    {
      text : 'Eliminar evento',
      iconCls : 'eliminar',
      handler : function()
      {
        var record = grillaEventos.getSelectionModel().getSelected();
        Ext.Ajax.request(
        {
          url : getAbsoluteUrl('ingreso_datos', 'eliminarRegistroEvento'),
          failure : function()
          {
            recargarDatosEventos();
          },
          success : function(result)
          {
            recargarDatosEventos();
            if(result.responseText != 'Ok')
            {
              alert(result.responseText);
            }
          },
          params :
          {
            'codigo' : record.get('codigo')
          }
        });
      }
    }],
    columns : [
    {
      dataIndex : 'id_evento',
      header : 'Nombre del evento',
      tooltip : 'Nombre del evento',
      width : 300,
      align : 'center',
      editor : new Ext.form.ComboBox(
      {
        store : eventos_datastore_combo,
        displayField : 'nombre',
        valueField : 'codigo',
        mode : 'local',
        triggerAction : 'all',
        forceSelection : true,
        allowBlank : false
      }),
      renderer : function(valor)
      {
        var index = eventos_datastore_renderer.find('codigo', valor);
        if(index != -1)
        {
          var record = eventos_datastore_renderer.getAt(index);
          return record.get('nombre');
        } else
        {
          return '';
        }
      }
    },
    {
      dataIndex : 'hora_ocurrio',
      header : 'Hora',
      tooltip : 'Hora en la cual ocurrió el evento',
      width : 70,
      align : 'center',
      editor : new Ext.form.TimeField(
      {
        format : 'H:i',
        minValue : '00:00',
        maxValue : '23:59',
        increment : 30
      })
    },
    {
      dataIndex : 'evrg_duracion',
      header : 'Duración<br>(min.)',
      tooltip : 'Duración del evento (min.)',
      width : 70,
      align : 'center',
      editor : new Ext.form.NumberField(
      {
        xtype : 'numberfield',
        allowDecimals : true,
        allowNegative : false,
        maxValue : '1000000000',
        maxLength : '9',
        value : '0'
      })
    },
    {
      dataIndex : 'observaciones',
      header : 'Observaciones',
      tooltip : 'Cualquier detalle adicional',
      width : 300,
      align : 'center',
      editor : new Ext.form.TextField()
    }]
  });

  var win = new Ext.Window(
  {
    applyTo : 'ventana_flotante',
    layout : 'fit',
    width : 800,
    height : 300,
    closeAction : 'hide',
    plain : true,
    title : 'Editar eventos...',
    items : grillaEventos,
    buttons : [
    {
      text : 'Aceptar',
      handler : function()
      {
        win.hide();
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

  var datastore_calculadora1 = new Ext.data.Store(
  {
    reader : new Ext.data.JsonReader(
    {
      root : 'data'
    }, [
    {
      name : 'tiempo_disponible_horas',
      type : 'float'
    },
    {
      name : 'tiempo_disponible_minutos',
      type : 'float'
    }])
  });

  datastore_calculadora1.loadData(
  {
    data : [
    {
      'tiempo_disponible_horas' : '',
      'tiempo_disponible_minutos' : ''
    }]
  });

  var grid_calculadora1 = new Ext.grid.EditorGridPanel(
  {
    store : datastore_calculadora1,
    stripeRows : true,
    border : true,
    frame : true,
    autoScroll : true,
    columnLines : true,
    height : 100,
    width : 200,
    columns : [
    {
      dataIndex : 'tiempo_disponible_horas',
      header : 'Tiempo<br>disponible<br>(Hrs)',
      tooltip : 'Tiempo disponible en horas',
      width : 80,
      align : 'center'
    },
    {
      dataIndex : 'tiempo_disponible_minutos',
      header : 'Tiempo<br>disponible<br>(Min)',
      tooltip : 'Tiempo disponible en minutos',
      width : 80,
      align : 'center'
    }]
  });

  var datastore_calculadora2 = new Ext.data.Store(
  {
    reader : new Ext.data.JsonReader(
    {
      root : 'data'
    }, [
    {
      name : 'tiempo_corrida',
      type : 'float'
    },
    {
      name : 'numero_inyecciones_muestra',
      type : 'float'
    }])
  });

  datastore_calculadora2.loadData(
  {
    data : [
    {
      'tiempo_corrida' : '',
      'numero_inyecciones_muestra' : ''
    }]
  });

  var grid_calculadora2 = new Ext.grid.EditorGridPanel(
  {
    store : datastore_calculadora2,
    stripeRows : true,
    border : true,
    frame : true,
    autoScroll : true,
    columnLines : true,
    height : 100,
    width : 200,
    columns : [
    {
      dataIndex : 'tiempo_corrida',
      header : 'Tiempo<br>de corrida<br>(Min)',
      tooltip : 'Tiempo de corrida',
      width : 80,
      align : 'center',
      editor :
      {
        xtype : 'numberfield',
        allowNegative : false,
        maxValue : 100000
      }
    },
    {
      dataIndex : 'numero_inyecciones_muestra',
      header : 'No.<br>inyecc./<br>Muestra',
      tooltip : 'N�mero inyecciones por muestra',
      width : 80,
      align : 'center',
      editor :
      {
        xtype : 'numberfield',
        allowNegative : false,
        allowDecimals : false,
        maxValue : 100000
      }
    }],
    listeners :
    {
      afteredit : function(e)
      {
        actualizarCalculadora();
      }
    }
  });

  var datastore_calculadora3 = new Ext.data.Store(
  {
    reader : new Ext.data.JsonReader(
    {
      root : 'data'
    }, [
    {
      name : 'numero_muestras_dia1',
      type : 'float'
    }])
  });

  datastore_calculadora3.loadData(
  {
    data : [
    {
      'numero_muestras_dia1' : ''
    }]
  });

  var grid_calculadora3 = new Ext.grid.EditorGridPanel(
  {
    store : datastore_calculadora3,
    stripeRows : true,
    border : true,
    frame : true,
    autoScroll : true,
    columnLines : true,
    height : 100,
    width : 200,
    columns : [
    {
      dataIndex : 'numero_muestras_dia1',
      header : 'No. muestras<br>a ingresar<br>dia 1',
      width : 80,
      align : 'center'
    }]
  });

  var datastore_calculadora4 = new Ext.data.Store(
  {
    reader : new Ext.data.JsonReader(
    {
      root : 'data'
    }, [
    {
      name : 'fraccion_muestra_dia1',
      type : 'float'
    },
    {
      name : 'fraccion_muestra_dia2',
      type : 'float'
    }])
  });

  datastore_calculadora4.loadData(
  {
    data : [
    {
      'fraccion_muestra_dia1' : '',
      'fraccion_muestra_dia2' : ''
    }]
  });

  var grid_calculadora4 = new Ext.grid.EditorGridPanel(
  {
    store : datastore_calculadora4,
    stripeRows : true,
    border : true,
    frame : true,
    autoScroll : true,
    columnLines : true,
    height : 100,
    width : 200,
    columns : [
    {
      dataIndex : 'fraccion_muestra_dia1',
      header : 'Fracci&oacute;n<br>muestra a<br>ingresar dia 1',
      width : 80,
      align : 'center'
    },
    {
      dataIndex : 'fraccion_muestra_dia2',
      header : 'Fracci&oacute;n<br>muestra a<br>ingresar dia 2',
      width : 80,
      align : 'center'
    }]
  });

  var win_calculadora = new Ext.Window(
  {
    applyTo : 'ventana_calculadora',
    layout : 'form',
    width : 200,
    height : 480,
    closeAction : 'hide',
    plain : true,
    title : 'Tiempo disponible',
    items : [grid_calculadora1, grid_calculadora2, grid_calculadora3, grid_calculadora4],
    buttons : [
    {
      text : 'Ocultar',
      handler : function()
      {
        win_calculadora.hide();
      }
    }]
  });

  function clipFloat(num, dec)
  {
    var t = num + "";
    var index = t.indexOf(".");
    if(index != -1)
    {
      num = parseFloat(t.substring(0, (index + dec + 1)));
    } else
    {
      num = parseFloat(num);
    }
    return (num)
  }

  function actualizarCalculadora()
  {
    var registro1 = datastore_calculadora1.getAt(0);
    registro1.set('tiempo_disponible_minutos', registro1.get('tiempo_disponible_horas') * 60);
    var registro2 = datastore_calculadora2.getAt(0);
    var registro3 = datastore_calculadora3.getAt(0);
    var tiempo_disponible_minutos = registro1.get('tiempo_disponible_minutos');
    var denominador = (parseFloat(registro2.get('tiempo_corrida')) + parseFloat(tiempoInyeccion)) * registro2.get('numero_inyecciones_muestra');
    var numero_muestras_dia1 = 0;
    if(denominador != 0)
    {
      numero_muestras_dia1 = tiempo_disponible_minutos / denominador;
    }
    numero_muestras_dia1 = clipFloat(numero_muestras_dia1, 3);
    registro3.set('numero_muestras_dia1', numero_muestras_dia1);
    var registro4 = datastore_calculadora4.getAt(0);
    var fraccion_muestra_dia1 = clipFloat(numero_muestras_dia1 - clipFloat(numero_muestras_dia1, 0), 3);
    registro4.set('fraccion_muestra_dia1', fraccion_muestra_dia1);
    registro4.set('fraccion_muestra_dia2', clipFloat(1 - fraccion_muestra_dia1, 3));
  };

  var columns = [
  {
    dataIndex : 'id_metodo',
    header : 'Método ',
    tooltip : 'Método ',
    columnWidth : 60,
    align : 'center',
    renderer : function(value)
    {
      var index = metodosinorden_datastore.find('codigo', value);
      if(index != -1)
      {
        var record = metodosinorden_datastore.getAt(index);
        var renderer = generarRenderer('#bfbfbf', '#000000', '#bfbfbf', '#000000');
        return renderer(record.get('nombre'));
      } else
      {
        return '';
      }
    }
    // ,
    // editor: new Ext.form.ComboBox({
    // store: metodosinorden_datastore,
    // displayField: 'nombre',
    // valueField: 'codigo',
    // mode: 'local',
    // triggerAction: 'all',
    // forceSelection: true,
    // allowBlank: false
    // })
  },
  {
    dataIndex : 'tiempo_entre_metodos',
    header : 'Tiempo<br>entre<br>métodos<br>(Hrs)',
    tooltip : 'Tiempo entre métodos (Horas)',
    width : 70,
    align : 'center',
    editor : new Ext.form.TimeField(
    {
      format : 'H:i:s',
      minValue : '00:00',
      maxValue : '23:59',
      increment : 30
    }),
    renderer : generarRenderer('#ffdc44', '#000000', '#ffdc44', '#000000')
  },
  {
    dataIndex : 'cambio_metodo_ajuste',
    header : 'Cambio<br>método<br>(alistamiento)<br>(Min)',
    tooltip : 'Cambio de método y ajustes',
    width : 75,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#47d552', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'tiempo_corrida_ss',
    header : 'T. C.<br>(Min)',
    tooltip : 'Tiempo de corrida',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_inyecciones_ss',
    header : 'No.<br>inyec.',
    tooltip : 'N&uacute;mero de inyecciones',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'tiempo_corrida_cc',
    header : 'T. C.<br>(Min)',
    tooltip : 'Tiempo de corrida',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  }];

  for(var i = 1; i <= inyeccionesEstandarPromedio; i++)
  {
    columns.push(
    {
      dataIndex : 'numero_inyecciones_estandar' + i,
      header : 'No.<br>inyec.<br>Std. ' + i,
      tooltip : 'N&uacute;mero de inyecciones del estándar No. ' + i,
      width : 44,
      align : 'center',
      editor :
      {
        xtype : 'numberfield',
        allowNegative : false,
        maxValue : 100000
      },
      renderer : function(valor, metaData, record, rowIndex, colIndex, store)
      {
        if(valor == '0')
        {
          return '';
        } else
        {
          var renderer = generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000');
          return renderer(valor, metaData, record, rowIndex, colIndex, store);
        }
      }
    });
  }

  columns.push(
  {
    dataIndex : 'tiempo_corrida_producto',
    header : '<a style="color:#B80000;">T. C.<br>Prod.<br>(Min)</a>',
    tooltip : 'Tiempo de corrida',    
    width : 44,
    align : 'center',
    editor :
    {    
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_muestras_producto',
    header : '<a style="color:#B80000;">No.<br>mues.<br>del<br>Prod.</a>',
    tooltip : 'N&uacute;mero de muestras del producto',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_inyecciones_x_muestra_producto',
    header : '<a style="color:#B80000;">No.<br>inyec.<br>x<br>mues.</a>',
    tooltip : 'N&uacute;mero de muestras del producto',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'tiempo_corrida_estabilidad',
    header : '<a style="color:#0033CC;">T. C.<br>Estb.<br>(Min)</a>',
    tooltip : 'Tiempo de corrida',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_muestras_estabilidad',
    header : '<a style="color:#0033CC;">No.<br>mues.<br>de<br>Estb.</a>',
    tooltip : 'N&uacute;mero de muestras de estabilidad',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_inyecciones_x_muestra_estabilidad',
    header : '<a style="color:#0033CC;">No.<br>inyec.<br>x<br>mues.</a>',
    tooltip : 'N&uacute;mero de muestras de estabilidad',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'tiempo_corrida_materia_prima',
    header : '<a style="color:#004C00;">T. C.<br>Mo.<br>Po.<br>(Min)</a>',
    tooltip : 'Tiempo de corrida',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_muestras_materia_prima',
    header : '<a style="color:#004C00;">No.<br>mues.<br>Mo.<br>Po.</a>',
    tooltip : 'N&uacute;mero de muestras de materia prima',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_inyecciones_x_muestra_materia_prima',
    header : '<a style="color:#004C00;">No.<br>inyec.<br>x<br>mues.</a>',
    tooltip : 'N&uacute;mero de muestras de materia prima',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'tiempo_corrida_pureza',
    header : '<a style="color:#8B4513;">T. C.<br>Pureza<br>Crom.<br>(Min)</a>',
    tooltip : 'Tiempo de corrida',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_muestras_pureza',
    header : '<a style="color:#8B4513;">No.<br>mues.<br>Pureza<br>Crom.</a>',
    tooltip : 'N&uacute;mero de muestras pureza',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_inyecciones_x_muestra_pureza',
    header : '<a style="color:#8B4513;">No.<br>inyec.<br>x<br>mues.</a>',
    tooltip : 'N&uacute;mero de muestras de pureza',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'tiempo_corrida_disolucion',
    header : '<a style="color:#006666;">T. C.<br>Disol.<br>(Min)</a>',
    tooltip : 'Tiempo de corrida',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_muestras_disolucion',
    header : '<a style="color:#006666;">No.<br>mues.<br>Disol.</a>',
    tooltip : 'N&uacute;mero de muestras disolucion',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_inyecciones_x_muestra_disolucion',
    header : '<a style="color:#006666;">No.<br>inyec.<br>x<br>mues.</a>',
    tooltip : 'N&uacute;mero de muestras de disolucion',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'tiempo_corrida_uniformidad',
    header : '<a style="color:#E63E00;">T. C.<br>Unif.<br>Cont.<br>(Min)</a>',
    tooltip : 'Tiempo de corrida',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_muestras_uniformidad',
    header : '<a style="color:#E63E00;">No.<br>mues.<br>Unif.<br>Cont.</a>',
    tooltip : 'N&uacute;mero de muestras uniformidad',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'numero_inyecciones_x_muestra_uniformidad',
    header : '<a style="color:#E63E00;">No.<br>inyec.<br>x<br>mues.</a>',
    tooltip : 'N&uacute;mero de muestras de uniformidad',
    width : 44,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#72a8cd', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'hora_inicio_corrida',
    header : 'Hora<br>inicio<br>corrida',
    tooltip : 'Hora de inicio de corrida',
    width : 70,
    align : 'center',
    editor : new Ext.form.TimeField(
    {
      format : 'H:i:s',
      minValue : '00:00',
      maxValue : '23:59',
      increment : 30
    }),
    renderer : generarRenderer('#f0a05f', '#000000', '#f0a05f', '#000000')
  },
  {
    dataIndex : 'hora_fin_corrida',
    header : 'Hora<br>fin<br>corrida',
    tooltip : 'Hora de inicio de corrida',
    width : 70,
    align : 'center',
    editor : new Ext.form.TimeField(
    {
      format : 'H:i:s',
      minValue : '00:00',
      maxValue : '23:59:59',
      increment : 30
    }),
    renderer : generarRenderer('#f0a05f', '#000000', '#f0a05f', '#000000')
  },
  {
    dataIndex : 'fallas',
    header : 'Fallas<br>(Hrs)',
    tooltip : 'Fallas (Hrs)',
    width : 59,
    align : 'center',
    editor :
    {
      xtype : 'numberfield',
      allowNegative : false,
      maxValue : 100000
    },
    renderer : generarRenderer('#ff5454', '#000000', '#ff5454', '#000000')
  },
  {
    dataIndex : 'observaciones',
    header : 'Observaciones',
    tooltip : 'Observaciones',
    width : 180,
    align : 'center',
    editor :
    {
      xtype : 'textfield'
    },
    renderer : generarRenderer('#d2b48c', '#000000', '#d2b48c', '#000000')
  });

  var metodo_para_agregar_combobox = new Ext.form.ComboBox(
  {
    store : metodos_datastore,
    emptyText : 'Seleccione un método',
    displayField : 'nombre',
    valueField : 'codigo',
    mode : 'local',
    triggerAction : 'all',
    forceSelection : true,
    allowBlank : false,
    width : 140
  });

  var crearRegistroUsoMaquina = function(params)
  {
    Ext.Ajax.request(
    {
      url : getAbsoluteUrl('ingreso_datos', 'crearRegistroUsoMaquina'),
      failure : function()
      {
        recargarDatosMetodos();
      },
      success : function(result)
      {
        recargarDatosMetodos();
        var mensaje = null;
        switch(result.responseText)
        {
          case 'Ok':
            break;
          case '1':
            mensaje = 'Debe digitar toda la información del método actual para poder adicionar nuevos registros';
            break;
          case '2':
            mensaje = 'No es posible realizar un registro con una fecha pasada';
            break;
          case '3':
            mensaje = 'Su perfil de usuario no está autorizado para crear registros';
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
  var historial_datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('ingreso_datos', 'listarRegistrosHistorial'),
      method : 'POST'
    }),
    reader : new Ext.data.JsonReader(
    {
      root : 'data'
    }, [
    {
      name : 'username',
      type : 'string'
    },
    {
      name : 'nombre_campo',
      type : 'string'
    },
    {
      name : 'valor_antiguo',
      type : 'string'
    },
    {
      name : 'valor_nuevo',
      type : 'string'
    },
    {
      name : 'causa',
      type : 'string'
    },
    {
      name : 'fecha',
      type : 'string'
    },
    {
      name : 'hora',
      type : 'string'
    }])
  });

  var recargarDatosHistorial = function()
  {
    var sm = grid.getSelectionModel();
    var cell = sm.getSelectedCell();
    var index = cell[0];
    var registro = datastore.getAt(index);
    historial_datastore.load(
    {
      params :
      {
        'codigo_rum' : registro.get('id_registro_uso_maquina')
      }
    });
  }
  var grillaHistorial = new Ext.grid.GridPanel(
  {
    autoWidth : true,
    height : 400,
    //autoHeight: true,
    store : historial_datastore,
    stripeRows : true,
    loadMask :
    {
      msg : 'Cargando...'
    },
    sm : new Ext.grid.RowSelectionModel(
    {
      singleSelect : true
    }),
    columns : [
    {
      dataIndex : 'username',
      header : 'Nombre de usuario',
      tooltip : 'Nombre de usuario',
      width : 120,
      align : 'left'
    },
    {
      dataIndex : 'nombre_campo',
      header : 'Nombre del campo',
      tooltip : 'Nombre del campo',
      width : 140,
      align : 'left'
    },
    {
      dataIndex : 'valor_antiguo',
      header : 'Valor anterior',
      tooltip : 'Valor anterior',
      width : 80,
      align : 'center'
    },
    {
      dataIndex : 'valor_nuevo',
      header : 'Valor nuevo',
      tooltip : 'Valor nuevo',
      width : 80,
      align : 'center'
    },
    {
      dataIndex : 'fecha',
      header : 'Fecha',
      tooltip : 'Fecha',
      width : 70,
      align : 'center'
    },
    {
      dataIndex : 'hora',
      header : 'Hora',
      tooltip : 'Hora',
      width : 70,
      align : 'center'
    },
    {
      dataIndex : 'causa',
      header : 'Causa',
      tooltip : 'Causa',
      width : 140,
      align : 'left'
    }]
  });

  var winHistorial = new Ext.Window(
  {
    applyTo : 'ventana_flotante_historial',
    layout : 'fit',
    width : 800,
    height : 300,
    closeAction : 'hide',
    plain : true,
    title : 'Historial de cambios',
    items : grillaHistorial,
    buttons : [
    {
      text : 'Aceptar',
      handler : function()
      {
        winHistorial.hide();
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

  var mostrarMensajeModificarRegistro = function(respuesta)
  {
    var mensaje = null;
    switch(respuesta)
    {
      case 'Ok':
        break;
      case '1':
        mensaje = 'Su perfil no está autorizado para modificar registros con antigüedad superior a un (1) día';
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
  }
  var modificarRegistroUsoMaquina = function(idRegistro, nombreCampo, valorCampo, par, params, callback)
  {
    if(par)
    {
      params['id_registro_uso_maquina'] = idRegistro;
      params[nombreCampo] = valorCampo;
      Ext.Ajax.request(
      {
        url : getAbsoluteUrl('ingreso_datos', 'modificarRegistroUsoMaquina'),
        failure : function()
        {
          recargarDatosMetodos(callback);
        },
        success : function(result)
        {
          recargarDatosMetodos(callback);
          mostrarMensajeModificarRegistro(result.responseText);
        },
        params : params
      });
    } else
    {
      params['id_registro_uso_maquina'] = idRegistro;
      params[nombreCampo + '_perdida'] = valorCampo;
      Ext.Ajax.request(
      {
        url : getAbsoluteUrl('ingreso_datos', 'modificarRegistroUsoMaquina'),
        failure : function()
        {
          recargarDatosMetodos(callback);
        },
        success : function(result)
        {
          recargarDatosMetodos(callback);
          mostrarMensajeModificarRegistro(result.responseText);
        },
        params : params
      });
    }
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
    plugins : columnHeaderGroup,
    tbar : [metodo_para_agregar_combobox,
    {
      text : 'Agregar registro',
      iconCls : 'agregar',
      handler : function()
      {
        var codigo_metodo = metodo_para_agregar_combobox.getValue();
        if(codigo_metodo == '')
        {
          alert('Primero debe seleccionar un método');
          metodo_para_agregar_combobox.focus();
        } else
        {
          var params =
          {
            'id_metodo' : codigo_metodo,
            'codigo_maquina' : maquina_combobox.getValue(),
            'fecha' : fechaField.getValue()
          };
          crearRegistroUsoMaquina(params);
        }
      }
    }, '-',
    {
      text : 'Eliminar registro',
      iconCls : 'eliminar',
      handler : function()
      {
        var sm = grid.getSelectionModel();
        if(sm.hasSelection())
        {
          Ext.Msg.confirm('Eliminar método', "Esta operación es irreversible. ¿Está seguro(a) de querer eliminar este método?", function(idButton)
          {
            if(idButton == 'yes')
            {
              var cell = sm.getSelectedCell();
              var index = cell[0];
              var registro = datastore.getAt(index);
              Ext.Msg.prompt('Eliminar método', 'Digite la causa de la eliminación de este método', function(idButton, text)
              {
                if(idButton == 'ok')
                {
                  Ext.Ajax.request(
                  {
                    url : getAbsoluteUrl('ingreso_datos', 'eliminarRegistroUsoMaquina'),
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
                      'id_registro_uso_maquina' : registro.get('id_registro_uso_maquina'),
                      'causa' : text
                    }
                  });
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
    }, '-',
    {
      text : 'Editar eventos',
      iconCls : 'evento',
      handler : function()
      {
        var sm = grid.getSelectionModel();
        if(sm.hasSelection())
        {
          recargarDatosEventos();
          Ext.getBody().mask();
          win.show();
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
    }, '-',
    {
      text : 'Historial',
      iconCls : 'historial',
      handler : function()
      {
        var sm = grid.getSelectionModel();
        if(sm.hasSelection())
        {
          recargarDatosHistorial();
          Ext.getBody().mask();
          winHistorial.show();
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
    },'-',
{
            xtype : 'button',
            text : 'Dividir registro',
iconCls : 'calcular',
            tooltip : 'Pulse este botón para dividir el último registro del día',
            width : 70,
            handler : function()
            {
              if(maquina_combobox.isValid() && fechaField.isValid() && maquina_combobox.getValue() != '')
              {
                Ext.Ajax.request(
                {
                  url : getAbsoluteUrl('ingreso_datos', 'dividirRegistro'),
                  failure : function()
                  {
                  },
                  success : function(result)
                  {
                    var mensaje = '';
                    switch(result.responseText)
                    {
                      case 'Ok':
                        mensaje = 'El último registro del día fue divido exitosamente';
                        break;
                      case '1':
                        mensaje = 'No es necesario ejecutar el proceso de división debido a que ningún registro ha excedido el tiempo diario';
                        break;
                    }
                    recargarDatosMetodos(function()
                    {
                    });
                    alert(mensaje);
                  },
                  params :
                  {
                    'codigo_maquina' : maquina_combobox.getValue(),
                    'fecha' : fechaField.getValue()
                  }
                });
              }
            }
          }],
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

        if(esAdministrador)
        {
          Ext.Msg.prompt('Modificando...', 'Digite la causa de la modificación', function(btn, text, op)
          {
            if(btn == 'ok')
            {
              params['causa'] = text;
              modificarRegistroUsoMaquina(e.record.get('id_registro_uso_maquina'), e.field, e.value, par, params, callback);
            } else
            {
              recargarDatosMetodos(function()
              {
              });
            }
          });
        } else
        {
          modificarRegistroUsoMaquina(e.record.get('id_registro_uso_maquina'), e.field, e.value, par, params, callback);
        }
      }
    }
  });

  var horaField = new Ext.form.TextField(
  {
    xtype : 'textfield',
    fieldLabel : 'Hora',
    width : 97,
    // value: horas + ':' + minutos,
    readOnly : true
  });

  var actualizarHora = function()
  {
    var fechaActual = new Date();

    var segundos = '' + fechaActual.getSeconds();
    if(segundos.length == 1)
    {
      segundos = '0' + segundos;
    }
    var minutos = '' + fechaActual.getMinutes();
    if(minutos.length == 1)
    {
      minutos = '0' + minutos;
    }
    var horas = '' + fechaActual.getHours();
    if(horas.length == 1)
    {
      horas = '0' + horas;
    }

    horaField.setValue(horas + ':' + minutos + ':' + segundos);
  }
  var operario_field = null;

  var identificacion_field = new Ext.form.TextField(
  {
    name : 'identificacion',
    fieldLabel : 'Identificación',
    readOnly : true
  });

  if(esAdministrador)
  {
    var operarios_datastore = new Ext.data.Store(
    {
      proxy : new Ext.data.HttpProxy(
      {
        url : getAbsoluteUrl('ingreso_datos', 'listarOperarios'),
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
        name : 'identificacion',
        type : 'string'
      }])
    });
    
    operario_field = new Ext.form.ComboBox(
    {
      fieldLabel : 'Analista',
      store : operarios_datastore,
      displayField : 'nombre',
      valueField : 'codigo',
      mode : 'local',
      triggerAction : 'all',
      forceSelection : true,
      allowBlank : false,
      width : 130,
      listeners :
      {
        select : function(combo, registro, indice)
        {
          identificacion_field.setValue(registro.get('identificacion'));
          recargarDatosMetodos();
        }
      }
    });

    operarios_datastore.load(
    {
      callback : function()
      {
        operarios_datastore.loadData(
        {
          data : [
          {
            'codigo' : '-1',
            'nombre' : 'TODOS',
            'identificacion' : ''
          }]
        }, true);
        operario_field.setValue('-1');
      }
    });
  } else
  {
    operario_field =
    {
      xtype : 'textfield',
      name : 'nombre',
      fieldLabel : 'Analista',
      readOnly : true
    };
  }

  var panelPrincipal = new Ext.FormPanel(
  {
    // renderTo: 'panel_principal',
    border : false,
    frame : false,
    layout : 'border',
    region : 'center',
    items : [
    {
      border : true,
      frame : true,
      height : 63,
      region : 'north',
      items : [
      {
        height : 60,
        layout : 'column',
        items : [
        {
          width : '225',
          layout : 'form',
          labelWidth : 75,
          footer : false,
          items : [operario_field, maquina_combobox]
        },
        {
          width : '250',
          footer : false,
          layout : 'form',
          items : [identificacion_field, codigo_maquina]
        },
        {
          width : '220',
          layout : 'form',
          labelWidth : 75,
          items : [fechaField, horaField]
        },
        {
          width : '140',
          layout : 'form',
          layout : 'column',
          //hideLabel: true,
          items : [
          {
            xtype : 'panel',
            columnWidth : '1',
            padding : '0px 0px 4px 0px',
            items : [genral_tema_combobox]
          },
          {
            xtype : 'panel',
            columnWidth : '.5',
            items : [
            {
              xtype : 'button',
              text : 'Reportes',
              tooltip : 'Pulse este botón para ver los reportes',
              width : 70,
              iconCls : 'reportes',
              handler : function()
              {
                redirigirSiSesionExpiro();
                window.open(getAbsoluteUrl('interfaz_reporte', 'index'));
              }
            }]
          },
          {
            columnWidth : '.5',
            xtype : 'panel',
            padding : '0px 0px 0px 4px',
            items : [
            {
              xtype : 'button',
              text : 'Manual',
              iconCls : 'abrir_manual',
              tooltip : 'Pulse este botón para ver el manual',
              width : 60,
              handler : function()
              {
                window.open(urlWeb + 'manual-tpm/main.html');
              }
            }
]
          }]
        },
        {
          width : '150',
          layout : 'form',
          padding : '0px 0px 0px 10px',
          hideLabel : true,
          items : [
          {
            padding : '0px 0px 4px 0px',
            items : [
            
{
              xtype : 'button',
              text : 'Salir',
              tooltip : 'Pulse este botón para salir del sistema',
              width : 60,
              iconCls : 'salir',
              handler : function()
              {
                cerrarSesion();
              }
            }
]
          }
          // {
          // xtype : 'button',
          // text : 'Tiempo disponible',
          // iconCls : 'tiempo_disponible',
          // tooltip : 'Pulse este botón para utilizar la calculadora',
          // width : 70,
          // handler : function()
          // {
          // win_calculadora.show();
          // }
          // },
          ]
        }]
      }]
    }, grid]
  });

  if(esAdministrador)
  {

  } else
  {
    var datos_operario_datastore = new Ext.data.Store(
    {
      proxy : new Ext.data.HttpProxy(
      {
        url : getAbsoluteUrl('ingreso_datos', 'consultarDatosOperario'),
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
        name : 'identificacion',
        type : 'string'
      }])
    });

    datos_operario_datastore.load(
    {
      callback : function()
      {
        panelPrincipal.getForm().loadRecord(datos_operario_datastore.getAt(0));
      }
    });
  }

  actualizarHora();

  window.setInterval(actualizarHora, 1000);

  var interfaz_ingreso_datos = new Ext.Viewport(
  {
    layout : 'border',
    items : [
    {
      height : 48,
      split : true,
      region : 'north',
      xtype : 'box',
      el : 'titulo_ingreso_datos',
      border : false,
      margins : '0 0 0 0'
    }, panelPrincipal,
    {
      region : 'east',
      title : 'Ayudas',
      width : 300,
      border : true,
      collapsible : true,
      collapsed : true,
      split : true,
      layout : 'accordion',
      items : [
      {
        title : 'Conceptos B&aacute;sicos',
        frame : true,
        autoScroll : true,
        autoLoad :
        {
          url : urlWeb + 'ayudas/Ayuda-Conceptual.html',
          scripts : true,
          scope : this
        }
      },
      {
        title : 'Perdidas',
        frame : true,
        autoScroll : true,
        autoLoad :
        {
          url : urlWeb + 'ayudas/Ayuda-Perdidas.html',
          scripts : true,
          scope : this
        }
      },
      {
        title : 'Indicadores',
        frame : true,
        autoScroll : true,
        autoLoad :
        {
          url : urlWeb + 'ayudas/Ayuda-Indicadores.html',
          scripts : true,
          scope : this
        }
      }]
    },
    {
      region : 'south',
      height : 200,
      frame : true,
      collapsible : true,
      collapsed : false,
      split : true,
      layout: 'column',
      items: [{
            columnWidth: '.5',
            id: 'flashcontent'
      },
      {
           columnWidth: '.5',
            id: 'flashcontent1'
      }]
    }]
  });
});