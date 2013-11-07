
/*
 gestion maquinas - tpm labs
 Desarrollado maryit sanchez
 2010
 */
var ayuda_maq_codigo = '';
var ayuda_maq_codigo_inventario = 'Escriba el código de inventario';
var ayuda_maq_nombre = 'Escriba el nombre del equipo';
var ayuda_maq_est_nombre = 'Seleccione el estado del equipo';
var ayuda_maq_marca = 'Escriba la marca del equipo';
var ayuda_maq_modelo = 'Escriba el modelo del equipo';
var ayuda_maq_tiempo_inyeccion = 'Escriba cual es el tiempo estandar de inyección del equipo';
var ayuda_maq_fecha_adquisicion = 'Escoja la fecha en que adquirió el equipo';
var ayuda_maq_cat_nombre = 'Seleccione el nombre del grupo del equipo';
var ayuda_maq_indicadores = 'Seleccione si el equipo se tiene en cuenta para el cálculo de indicadores';
var largo_panel = 500;

var datastore = new Ext.data.Store({
    id: 'datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('crud_maquina', 'listarMaquina'),
        method: 'POST'
    }),
    baseParams: {
        start: 0,
        limit: 20
    },
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'maq_codigo',
        type: 'int'
    }, {
        name: 'maq_codigo_inventario',
        type: 'string'
    }, {
        name: 'maq_nombre',
        type: 'string'
    }, {
        name: 'maq_est_codigo',
        type: 'int'
    }, {
        name: 'maq_marca',
        type: 'string'
    }, {
        name: 'maq_modelo',
        type: 'string'
    }, {
        name: 'maq_tiempo_inyeccion',
        type: 'float'
    }, {
        name: 'maq_fecha_adquisicion',
        type: 'string'
    }, {
        name: 'certificado',
        type: 'string'
    }, {
        name: 'maq_fecha_registro_sistema',
        type: 'string'
    }, {
        name: 'maq_fecha_actualizacion',
        type: 'string'
    }, {
        name: 'maq_usu_crea_nombre',
        type: 'string'
    }, {
        name: 'maq_usu_actualiza_nombre',
        type: 'string'
    }, {
        name: 'maq_causa_eliminacion',
        type: 'string'
    }, {
        name: 'maq_causa_actualizacion',
        type: 'string'
    }, {
        name: 'maq_eliminado',
        type: 'string'
    }, {
        name: 'maq_cat_codigo',
        type: 'int'
    }, {
        name: 'maq_indicadores',
        type: 'int'
    }])
});

  var registros_periodos_datastore = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('crud_maquina', 'listarRegistrosPeriodoMaquina'),
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
      name : 'id_periodo',
      type : 'string'
    },
    {
      name : 'fecha_inicio',
      type : 'string'
    },
    {
      name : 'usu_registra',
      type : 'string'
    },
    {
      name : 'fecha_registro',
      type : 'string'
    }])
  });  
  
  var periodos_datastore_combo = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('crud_maquina', 'listarPeriodos'),
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
  
  var periodos_datastore_renderer = new Ext.data.Store(
  {
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('crud_maquina', 'listarPeriodos'),
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

var crud_maquina_estado_datastore = new Ext.data.Store({
    id: 'crud_maquina_estado_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('crud_maquina', 'listarEstado'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'est_codigo'
    }, {
        name: 'est_nombre'
    }])
});

var crud_maquina_categoria_datastore = new Ext.data.Store({
    id: 'crud_maquina_categoria_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('crud_maquina', 'listarCategoria'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'cat_codigo'
    }, {
        name: 'cat_nombre'
    }])
});
crud_maquina_categoria_datastore.load();

  
var maq_eliminado = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    maxLength: 20,
    name: 'maq_eliminado',
    id: 'maq_eliminado',
    hideLabel: true,
    hidden: true
});

var maq_codigo = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: 'text-align:right;',
    maxLength: 20,
    name: 'maq_codigo',
    id: 'maq_codigo',
    hideLabel: true,
    hidden: true,
    listeners: {
        'render': function(){
            ayuda('maq_codigo', ayuda_maq_codigo);
        }
    }
});

var maq_codigo_inventario = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    name: 'maq_codigo_inventario',
    id: 'maq_codigo_inventario',
    fieldLabel: '<html>C&oacute;digo inventario</html>',
    allowBlank: false,
    maxLength: 100,
    listeners: {
        'render': function(){
            ayuda('maq_codigo_inventario', ayuda_maq_codigo_inventario);
        }
    }
});

var maq_nombre = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    maxLength: 100,
    name: 'maq_nombre',
    id: 'maq_nombre',
    fieldLabel: 'Nombre equipo',
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('maq_nombre', ayuda_maq_nombre);
        }
    }
});

var maq_est_codigo = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'maq_est_nombre',
    hiddenName: 'maq_est_codigo',
    name: 'maq_est_codigo',
    fieldLabel: 'Estado',
    store: crud_maquina_estado_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'est_nombre',
    valueField: 'est_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('maq_est_nombre', ayuda_maq_est_nombre);
        },
        focus: function(){
            crud_maquina_estado_datastore.reload();
        }
    }
});

var maq_cat_codigo = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'maq_cat_nombre',
    hiddenName: 'maq_cat_codigo',
    name: 'maq_cat_codigo',
    fieldLabel: 'Grupo equipo',
    store: crud_maquina_categoria_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'cat_nombre',
    valueField: 'cat_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('maq_cat_nombre', ayuda_maq_cat_nombre);
        },
        focus: function(){
            crud_maquina_categoria_datastore.reload();
        }
    }
});


var maq_marca = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    name: 'maq_marca',
    id: 'maq_marca',
    fieldLabel: '<html>Marca</html>',
    allowBlank: false,
    maxLength: 100,
    listeners: {
        'render': function(){
            ayuda('maq_marca', ayuda_maq_marca);
        }
    }
});


var maq_modelo = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    name: 'maq_modelo',
    id: 'maq_modelo',
    fieldLabel: '<html>Modelo</html>',
    allowBlank: false,
    maxLength: 100,
    listeners: {
        'render': function(){
            ayuda('maq_modelo', ayuda_maq_modelo);
        }
    }
});


var maq_tiempo_inyeccion = new Ext.form.NumberField({
    xtype: 'numberfield',
    labelStyle: ' text-align:right;',
    name: 'maq_tiempo_inyeccion',
    id: 'maq_tiempo_inyeccion',
    fieldLabel: 'Tiempo inyección (Min.)',
    allowDecimals: true,
    allowNegative: false,
    maxLength: 100,
    listeners: {
        'render': function(){
            ayuda('maq_tiempo_inyeccion', ayuda_maq_tiempo_inyeccion);
        }
    }
});
/*agregado el 16 y eliminado el 18 de febrero sugerencias de karl
 var maq_tiempo_inyeccion_actual = new Ext.form.NumberField({
 xtype: 'numberfield',
 labelStyle: ' text-align:right;',
 name: 'maq_tiempo_inyeccion_actual',
 id: 'maq_tiempo_inyeccion_actual',
 fieldLabel: 'Tiempo inyección actual',
 allowDecimals: true,
 allowNegative: false,
 maxLength: 100,
 listeners: {
 'render': function(){
 ayuda('maq_tiempo_inyeccion_actual', ayuda_maq_tiempo_inyeccion_actual);
 }
 }
 });*/
var maq_fecha_adquisicion = new Ext.form.DateField({
    xtype: 'datefield',
    labelStyle: 'text-align:right;',
    name: 'maq_fecha_adquisicion',
    id: 'maq_fecha_adquisicion',
    format: 'Y-m-d',
    fieldLabel: 'Fecha adquisici&oacute;n',
    allowBlank: false,
    maxLength: 100,
    listeners: {
        'render': function(){
            ayuda('maq_fecha_adquisicion', ayuda_maq_fecha_adquisicion);
        }
    }
});

var maq_fecha_registro_sistema = new Ext.form.TextField({
    xtype: 'textfield',
    labelStyle: 'text-align:right;',
    name: 'maq_fecha_registro_sistema',
    id: 'maq_fecha_registro_sistema',
    fieldLabel: 'Fecha registro',
    maxLength: 100,
    readOnly: true
});

var maq_indicadores = new Ext.form.RadioGroup({
    xtype: 'radiogroup',
    fieldLabel: 'Cálculo Indicadores',
    labelStyle: 'text-align:right;',
    id: 'maq_indicadores',
    allowBlank: false,
    //columns: 1,
    items: [{
        boxLabel: 'Activo',
        name: 'maq_indicadores',
        id: 'maq_indicadores_si',
        inputValue: 1,
        checked: true
    }, {
        boxLabel: 'Inactivo',
        name: 'maq_indicadores',
        id: 'maq_indicadores_no',
        inputValue: 0
    }],
    listeners: {
        render: function(){
            ayuda('maq_indicadores', ayuda_maq_indicadores);
        }
    }
});

var certificados_datastore = new Ext.data.Store({
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('crud_maquina', 'listarComputadores'),
        method: 'POST'
    }),
    reader: new Ext.data.JsonReader({
        root: 'data'
    }, [{
        name: 'certificado',
        type: 'string'
    }, {
        name: 'nombre',
        type: 'string'
    }])
});

certificados_datastore.load({
    callback: function(){
        crud_maquina_estado_datastore.load({
            callback: function(){
                datastore.load();
            }
        });
    }
});

var certificado_combo = new Ext.form.ComboBox({
    hiddenName: 'certificado',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Computador',
    store: certificados_datastore,
    displayField: 'nombre',
    valueField: 'certificado',
    mode: 'local',
    triggerAction: 'all'
});

var crud_maquina_formpanel = new Ext.FormPanel({
    id: 'crud_maquina_formpanel',
    frame: true,
    region: 'east',
    split: true,
    collapsible: true,
    width: 400,
    border: true,
    title: 'Equipo detalle',
    columnWidth: '0.6',
    height: 470,
    layout: 'form',
    bodyStyle: 'padding:10px;',
    labelWidth: 140,
    defaults: {
        anchor: '98%'
    },
    items: [maq_eliminado, maq_codigo, maq_codigo_inventario, maq_nombre, maq_est_codigo, maq_tiempo_inyeccion, maq_marca, maq_modelo, maq_fecha_adquisicion, certificado_combo, maq_cat_codigo, maq_indicadores, maq_fecha_registro_sistema],
    buttons: [{
        text: 'Guardar',
        iconCls: 'guardar',
        id: 'crud_maquina_actualizar_boton',
        handler: function(formulario, accion){
        
            if (Ext.getCmp('crud_maquina_actualizar_boton').getText() == 'Actualizar') {
                if (maq_eliminado.getValue() == 0) {
                    Ext.Msg.prompt('Equipo', 'Digite la causa de la actualizaci&oacute;n de este equipo', function(btn, text, op){
                        if (btn == 'ok') {
                            crud_maquina_actualizar(text);
                        }
                    });
                }
                else {
                    crud_maquina_actualizar('');
                }
            }
            else {
                crud_maquina_actualizar('');
            }
        }
    }]
});

function maquinaRenderComboColumn1(value, meta, record){
    return ComboRenderer(value, maq_est_codigo);
}

function maquinaRenderComboColumn2(value, meta, record){
    return ComboRenderer(value, maq_cat_codigo);
}

var crud_maquina_colmodel = new Ext.grid.ColumnModel({
    defaults: {
        sortable: true,
        locked: false,
        resizable: true
    },
    columns: [{
        id: 'maq_codigo',
        header: "Id",
        width: 30,
        dataIndex: 'maq_codigo'
    }, {
        header: "Nombre",
        width: 100,
        dataIndex: 'maq_nombre'
    }, {
        header: "Código inventario",
        width: 100,
        dataIndex: 'maq_codigo_inventario'
    }, {
        header: "Estado",
        width: 100,
        dataIndex: 'maq_est_codigo',
        renderer: maquinaRenderComboColumn1
    }, {
        header: "Marca",
        width: 100,
        dataIndex: 'maq_marca'
    }, {
        header: "Modelo",
        width: 100,
        dataIndex: 'maq_modelo'
    }, {
        header: "Computador",
        width: 100,
        dataIndex: 'certificado',
        renderer: function(value){
            var index = certificados_datastore.find('certificado', value);
            if (index != -1) {
                var record = certificados_datastore.getAt(index);
                return record.get('nombre');
            }
            else {
                return '';
            }
        }
    }, {
        header: "Categoría",
        width: 100,
        dataIndex: 'maq_cat_codigo',
        renderer: maquinaRenderComboColumn2
    }, {
        header: 'Cálc. Indicadores',
        dataIndex: 'maq_indicadores',
        width: 95,
        renderer: function(val){
            if (val == '1') {
                return 'Activo';
            }
            else {
                return 'Inactivo';
            }
        }
    }, {
        header: "Creado por",
        width: 120,
        dataIndex: 'maq_usu_crea_nombre'
    }, {
        header: "Fecha de creaci&oacute;n",
        width: 120,
        dataIndex: 'maq_fecha_registro_sistema'
    }, {
        header: "Actualizado por",
        width: 120,
        dataIndex: 'maq_usu_actualiza_nombre'
    }, {
        header: "Fecha de actualizaci&oacute;n",
        width: 120,
        dataIndex: 'maq_fecha_actualizacion'
    }, {
        header: "Causa actualizaci&oacute;n",
        width: 120,
        dataIndex: 'maq_causa_actualizacion'
    }, {
        header: "Causa eliminaci&oacute;n",
        width: 120,
        dataIndex: 'maq_causa_eliminacion'
    }]
});

  var periodo_para_agregar_combobox = new Ext.form.ComboBox(
  {
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Estado',
    store: periodos_datastore_combo,
    mode: 'local',
    emptyText: 'Seleccione un Periodo',
    displayField: 'nombre',
    valueField: 'codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false   
  });
  
  var fechaField = new Ext.form.DateField(
  {    
      xtype : 'datefield',
      fieldLabel : 'Fecha de inicio',
      allowBlank : false,
      value : new Date(),
      labelStyle: 'text-align:right;'
  });
  
  var recargarDatosPeriodos = function(callback)
  {
    periodos_datastore_combo.load();
    periodos_datastore_renderer.load(
    {
      callback : function()
      {
        var record = grid.getSelectionModel().getSelected();
        registros_periodos_datastore.load(
        {
          params :
          {
            'cod_maquina' : record.get('maq_codigo')
          }
        });
      }
    });
  }

  var grillaPeriodos = new Ext.grid.GridPanel(
  {
    autoWidth : true,
    height : 400,
    store : registros_periodos_datastore,
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
    periodo_para_agregar_combobox, '-', fechaField, '-',
    {
      text : 'Agregar periodo',
      iconCls : 'agregar',
      handler : function()
      {          
        var record = grid.getSelectionModel().getSelected();
        var id_periodo = periodo_para_agregar_combobox.getValue();
        if(id_periodo == '')
        {
          alert('Primero debe seleccionar un periodo'); 
          periodo_para_agregar_combobox.focus();
        } else        
        {            
            Ext.Ajax.request({
              url : getAbsoluteUrl('crud_maquina', 'registrarPeriodo'),
              failure : function()
              {
                recargarDatosPeriodos();
              },
              success : function(result)
              {                
                var mensaje = null;
                switch(result.responseText)
                {
                  case 'Ok': recargarDatosPeriodos();
                    break;
                  case '1':
                    mensaje = 'El periodo seleccionado ya se encuentra registrado para este equipo.';
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
                'maq_codigo' : record.get('maq_codigo'),
                'id_periodo' : id_periodo,
                'fecha_inicio' : fechaField.getValue()      
              }
            });
        }
      }
    }, '-',
    {
      text : 'Eliminar periodo',
      iconCls : 'eliminar',
      handler : function()
      {
          var record = grillaPeriodos.getSelectionModel().getSelected();
          Ext.Ajax.request({
              url : getAbsoluteUrl('crud_maquina', 'eliminarPeriodo'),
              failure : function()
              {
                recargarDatosPeriodos();
              },
              success : function(result)
              {
                recargarDatosPeriodos();
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
    }]
    ,
    columns : [
    {
      dataIndex : 'id_periodo',
      header : 'Nombre y Tipo del Periodo',
      tooltip : 'Nombre y Tipo del Periodo',
      width : 180,
      align : 'center',
      editor : new Ext.form.ComboBox(
      {
        store : periodos_datastore_combo,
        displayField : 'nombre',
        valueField : 'codigo',
        mode : 'local',
        triggerAction : 'all',
        forceSelection : true,
        allowBlank : false
      }),
      renderer : function(valor)
      {
        var index = periodos_datastore_renderer.find('codigo', valor);
        if(index != -1)
        {
          var record = periodos_datastore_renderer.getAt(index);
          return record.get('nombre');
        } else
        {
          return '';
        }
      }
    },
    {
      dataIndex : 'fecha_inicio',
      header : 'Fecha de inicio',
      tooltip : 'Fecha de inicio',
      width : 150,
      align : 'center',
      editor :
      { 
          xtype : 'datefield',
          allowBlank : false
      }
    },
    {
      dataIndex : 'usu_registra',
      header : 'Creado por',
      tooltip : 'Creado por',
      width : 150,
      align : 'center',
      editor : new Ext.form.TextField()
    },
    {
      dataIndex : 'fecha_registro',
      header : 'Fecha de creación',
      tooltip : 'Fecha de creación',
      width : 150,
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
    title : 'Periodo Mantenimiento',
    items : grillaPeriodos,
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
  
var grid = new Ext.grid.GridPanel({
    id: 'grid',
    title: 'Equipos en el sistema',
    columnWidth: '.4',
    region: 'center',
    stripeRows: true,
    frame: true,
    ds: datastore,
    cm: crud_maquina_colmodel,
    selModel: new Ext.grid.RowSelectionModel({
        singleSelect: true,
        listeners: {
            rowselect: function(sm, row, record){
                Ext.getCmp('crud_maquina_formpanel').getForm().loadRecord(record);
                Ext.getCmp('crud_maquina_actualizar_boton').setText('Actualizar');
            }
        }
    }),
    //autoExpandColumn: 'maq_nombre',
    //autoExpandMin: 100,
    height: largo_panel,
    bbar: new Ext.PagingToolbar({
        pageSize: 15,
        store: datastore,
        displayInfo: true,
        displayMsg: 'Equipos {0} - {1} de {2}',
        emptyMsg: "No hay equipos aun"
    }),
    tbar: [{
        id: 'crud_maquina_agregar_boton',
        text: 'Agregar',
        tooltip: 'Agregar',
        iconCls: 'agregar',
        handler: crud_maquina_agregar
    }, '-', {
        text: 'Eliminar',
        tooltip: 'Eliminar',
        iconCls: 'eliminar',
        handler: crud_maquina_eliminar
    }, '-', {
        text: '',
        iconCls: 'activos',
        tooltip: 'Equipos activos',
        handler: function(){
            datastore.baseParams.maq_eliminado = '0';
            datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, {
        text: '',
        iconCls: 'eliminados',
        tooltip: 'Equipos eliminados',
        handler: function(){
            datastore.baseParams.maq_eliminado = '1';
            datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, '-', {
        text: 'Restablecer',
        iconCls: 'restablece',
        tooltip: 'Restablecer un equipo eliminado',
        handler: function(){
            var cant_record = grid.getSelectionModel().getCount();
            
            if (cant_record > 0) {
                var record = grid.getSelectionModel().getSelected();
                if (record.get('maq_codigo') != '') {
                
                    Ext.Msg.prompt('Restablecer equipo', 'Digite la causa de restablecimiento', function(btn, text){
                        if (btn == 'ok') {
                            subirDatosAjax(getAbsoluteUrl('crud_maquina', 'restablecerMaquina'), {
                                maq_codigo: record.get('maq_codigo'),
                                maq_causa_restablece: text
                            }, function(){
                                datastore.reload();
                            }, function(){
                            });
                        }
                    });
                }
            }
            else {
                mostrarMensajeConfirmacion('Error', "Seleccione un equipo eliminado");
            }
        }
    }, '-',
    {
      text : 'Periodo Mantenimiento',
      tooltip: 'Asignar Periodo de Mantenimiento',
      iconCls : 'evento',
      handler : function()
      {
        var sm = grid.getSelectionModel();
        if(sm.hasSelection())
        {
          recargarDatosPeriodos();
          Ext.getBody().mask();
          win.show();
        } else
        {
          Ext.Msg.show(
          {
            title : 'Información',
            msg : 'Primero debe seleccionar un equipo',
            buttons : Ext.Msg.OK,
            icon : Ext.MessageBox.INFO
          });
        }
      }
    }]
});


/*INTEGRACION AL CONTENEDOR*/
var crud_maquina_contenedor_panel = new Ext.Panel({
    id: 'crud_maquina_contenedor_panel',
    height: largo_panel,
    autoWidth: true,
    //width:1000,
    border: false,
    tabTip: 'Aqui puedes ver, agregar, eliminar equipos',
    monitorResize: true,
    layout: 'border',
    items: [grid, crud_maquina_formpanel],
    buttonAlign: 'left',
    renderTo: 'div_form_crud_maquina'
});

function crud_maquina_actualizar(text){

    if (crud_maquina_formpanel.getForm().isValid()) {
        subirDatos(crud_maquina_formpanel, getAbsoluteUrl('crud_maquina', 'actualizarMaquina'), {
            maq_causa_actualizacion: text
        }, function(){
            crud_maquina_formpanel.getForm().reset();
            datastore.reload();
        }, function(){
        });
    }
}

function crud_maquina_eliminar(){
    var cant_record = grid.getSelectionModel().getCount();
    
    if (cant_record > 0) {
        var record = grid.getSelectionModel().getSelected();
        if (record.get('maq_codigo') != '') {
        
            Ext.Msg.confirm('Eliminar equipo', "Realmente desea eliminar esta equipo?", function(btn){
                if (btn == 'yes') {
                
                    Ext.Msg.prompt('Eliminar equipo', 'Digite la causa de la eliminaci&oacute;n de este equipo', function(btn2, text){
                        if (btn2 == 'ok') {
                            subirDatosAjax(getAbsoluteUrl('crud_maquina', 'eliminarMaquina'), {
                                maq_codigo: record.get('maq_codigo'),
                                maq_causa_eliminacion: text
                            }, function(){
                                datastore.reload();
                            });
                        }
                    });
                }
            });
        }
    }
    else {
        mostrarMensajeConfirmacion('Error', "Seleccione un equipo a eliminar");
    }
}

function crud_maquina_agregar(btn, ev){
    crud_maquina_formpanel.getForm().reset();
    Ext.getCmp('crud_maquina_actualizar_boton').setText('Guardar');
    
}


