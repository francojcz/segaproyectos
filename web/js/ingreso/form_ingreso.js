var ayuda_ing_codigo='';
var ayuda_ing_concepto='Ingrese una descripción del ingreso'; 
var ayuda_ing_valor='Ingrese el valor del ingreso';
var ayuda_ing_fecha='Ingrese la fecha del ingreso';
var ayuda_ing_proyecto='Seleccione el proyecto';
var largo_panel=450;

var crud_ingreso_datastore = new Ext.data.Store({
id: 'crud_ingreso_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('ingresos','listarIngreso'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'ing_codigo', type: 'int'},
                {name: 'ing_concepto', type: 'string'},
                {name: 'ing_valor', type: 'string'},
                {name: 'ing_fecha', type: 'string'},
                {name: 'ing_fecha_registro', type: 'string'},                
                {name: 'ing_usuario', type: 'int'},
                {name: 'ing_usuario_nombre', type: 'string'},
                {name: 'ing_proyecto', type: 'string'},
                {name: 'ing_proyecto_nombre', type: 'string'},
                {name: 'ing_acumulado_ingresos', type: 'string'},
                {name: 'ing_acumulado_egresos', type: 'string'},
                {name: 'ing_disponible', type: 'string'}
        ])
});
crud_ingreso_datastore.load();

var crud_proyecto_datastore = new Ext.data.Store({
    id: 'crud_proyecto_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('ingresos', 'listarProyecto'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'pro_ing_codigo'
    }, {
        name: 'pro_ing_nombre'
    }])
});
crud_proyecto_datastore.load();

var crud_concepto_datastore = new Ext.data.Store({
    id: 'crud_concepto_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('ingresos', 'listarConcepto'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'con_ing_codigo'
    }, {
        name: 'con_ing_nombre'
    }])
});
crud_concepto_datastore.load();

var registros_conceptos_datastore = new Ext.data.Store(
{
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('ingresos', 'listarConceptosIngreso'),
      method : 'POST'
    }),
    reader : new Ext.data.JsonReader(
    {
      root : 'data'
    }, [
    {
      name : 'codigo',
      type : 'integer'
    }, {
      name : 'valor',
      type : 'string'
    }, {
      name : 'fecha_registro',
      type : 'string'
    }, {
      name : 'usu_registra',
      type : 'string'
    }, {
      name : 'concepto',
      type : 'string'
    }])
}); 
	
var ing_codigo=new Ext.form.NumberField({
   xtype: 'numberfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'ing_codigo',
   id: 'ing_codigo',
   hideLabel:true,
   hidden:true,
   listeners:
   {
        'render': function() {
                        ayuda('ing_codigo', ayuda_ing_codigo);
                        }
   }
});

var ing_concepto=new Ext.form.TextArea({
   xtype: 'textarea',
   labelStyle: 'text-align:right;',
   maxLength : 1000,
   width: 100,
   name: 'ing_concepto',
   id: 'ing_concepto',
   fieldLabel: 'Descripci&oacute;n del ingreso',
   allowBlank: false,
   listeners:
   {
    'render': function() {
                ayuda('ing_concepto', ayuda_ing_concepto);
                }
   }
});


var ing_valor=new Ext.form.NumberField({
   xtype: 'numberfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   decimalPrecision: 10,
   name: 'ing_valor',
   id: 'ing_valor',
   fieldLabel: 'Valor del ingreso',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('ing_valor', ayuda_ing_valor);
                        }
   }
});

var ing_fecha=new Ext.form.DateField({
    xtype: 'datefield',		 
    labelStyle: 'text-align:right;',
    name: 'ing_fecha',
    id: 'ing_fecha',
    fieldLabel: 'Fecha del ingreso',
    allowBlank: false,
    format:'Y-m-d',
    anchor:'98%',
    listeners:
    {
        'render': function() {
                        ayuda('ing_fecha', ayuda_ing_fecha);
                        }
    }
});

var ing_fecha_registro=new Ext.form.TextField({
   xtype: 'textfield',		 
   labelStyle: 'text-align:right;',
   name: 'ing_fecha_registro',
   id: 'ing_fecha_registro',
   fieldLabel: 'Fecha de registro',
   maxLength : 100,
   readOnly:true
});

var ing_proyecto = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'pro_ing_nombre',
    hiddenName: 'ing_proyecto',
    name: 'ing_proyecto',
    fieldLabel: 'Nombre del Proyecto',
    store: crud_proyecto_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'pro_ing_nombre',
    valueField: 'pro_ing_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('pro_ing_nombre', ayuda_ing_proyecto);
        },
        'change': function() {
            crud_proyecto_datastore.reload();
        }
    }
});

var ing_acumulado_ingresos=new Ext.form.TextField({   
   labelStyle: 'text-align:right;',
   maxLength : 1000,
   width: 150,
   name: 'ing_acumulado_ingresos',
   id: 'ing_acumulado_ingresos',
   fieldLabel: '<b>Total Acumulado Ingresos</b>',
   readOnly: true,
   disabled: true,
   style: 'font-weight: bold'
});

var ing_acumulado_egresos=new Ext.form.TextField({   
   labelStyle: 'text-align:right;',
   maxLength : 1000,
   width: 150,
   name: 'ing_acumulado_egresos',
   id: 'ing_acumulado_egresos',
   fieldLabel: '<b>Total Acumulado Egresos</b>',
   readOnly: true,
   disabled: true,
   style: 'font-weight: bold'
});

var ing_disponible=new Ext.form.TextField({   
   labelStyle: 'text-align:right;',
   maxLength : 1000,
   width: 150,
   name: 'ing_disponible',
   id: 'ing_disponible',
   fieldLabel: '<b>Total Disponible</b>',
   readOnly: true,
   disabled: true,
   style: 'font-weight: bold'
});


//Conceptos por ingreso
var concepto_para_agregar_combobox = new Ext.form.ComboBox(
{
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    fieldLabel: 'Estado',
    store: crud_concepto_datastore,
    mode: 'local',
    emptyText: 'Seleccione un Concepto',
    displayField: 'con_ing_nombre',
    valueField: 'con_ing_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false   
});

var valor_campo = new Ext.form.NumberField(
{
    xtype: 'numberfield',
    maxLength : 100,
    style: 'text-align: left',
    decimalPrecision: 10,
    emptyText: 'Valor del ingreso',
    allowBlank: false
});

var grillaConceptos = new Ext.grid.GridPanel(
{
    autoWidth : true,
    height : 400,
    store : registros_conceptos_datastore,
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
    concepto_para_agregar_combobox, '-', valor_campo, '-',
    {
      text : 'Agregar concepto',
      iconCls : 'agregar',
      handler : function()
      {          
        var record = crud_ingreso_gridpanel.getSelectionModel().getSelected();
        var id_concepto = concepto_para_agregar_combobox.getValue();
        if(id_concepto == '' || valor_campo.getValue() == '')
        {
          alert('Debe seleccionar el concepto e ingresar un valor'); 
          concepto_para_agregar_combobox.focus();
        } else        
        {
            Ext.Ajax.request({
              url : getAbsoluteUrl('ingresos', 'registrarConcepto'),
              failure : function()
              {
                recargarDatosConceptos();
              },
              success : function(result)
              {                
                var mensaje = null;
                switch(result.responseText)
                {
                  case 'Ok': recargarDatosConceptos();
                    break;
                  case '1':
                    mensaje = 'El concepto seleccionado ya se encuentra registrado para este ingreso.';
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
                'id_ingreso' : record.get('ing_codigo'),
                'id_concepto' : id_concepto,
                'valor' : valor_campo.getValue(),
                'id_proyecto' : record.get('ing_proyecto'),
                'fecha': record.get('ing_fecha')
              }
            });
        }
      }
    }, '-',
    {
      text : 'Eliminar concepto',
      iconCls : 'eliminar',
      handler : function()
      {
          var record = grillaConceptos.getSelectionModel().getSelected();
          if(record.get('codigo')!='')
          {
                    Ext.Msg.confirm('Eliminar concepto', 
                    "¿Realmente desea eliminar este concepto?", 
                    function(btn){
                            if (btn == 'yes') {
                                    Ext.Ajax.request({
                                      url : getAbsoluteUrl('ingresos', 'eliminarConcepto'),
                                      failure : function()
                                      {
                                        recargarDatosPeriodos();
                                      },
                                      success : function(result)
                                      {
                                        recargarDatosConceptos();
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
                    });
            }          
      }
    }]
    ,
    columns : [
    {
      dataIndex : 'concepto',
      header : 'Concepto',
      tooltip : 'Nombre del Concepto',
      width : 240,
      align : 'center'      
    }, {
      dataIndex : 'valor',
      header : 'Valor',
      tooltip : 'Valor para el Concepto',
      width : 150,
      align : 'center'   
    }, {
      dataIndex : 'usu_registra',
      header : 'Creado por',
      tooltip : 'Usuario que realiza el registro',
      width : 190,
      align : 'center'   
    }, {
      dataIndex : 'fecha_registro',
      header : 'Fecha del Ingreso',
      tooltip : 'Fecha del Ingreso',
      width : 140,
      align : 'center'   
    }]
});

var win_ing = new Ext.Window(
{
    layout : 'fit',
    width : 800,
    height : 300,
    closeAction : 'hide',
    plain : true,
    title : 'Conceptos por Ingreso',
    items : grillaConceptos,
    buttons : [
    {
      text : 'Aceptar',
      handler : function()
      {
        win_ing.hide();
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

var recargarDatosConceptos = function(callback)
{
    crud_concepto_datastore.load();   
    var record = crud_ingreso_gridpanel.getSelectionModel().getSelected();
    registros_conceptos_datastore.load(
    {
        params :
        {
            'cod_ingreso' : record.get('ing_codigo')
        }
    });    
}

var crud_ingreso_formpanel = new Ext.FormPanel({
        id:'crud_ingreso_formpanel',
        frame: true,
        region:'east',
        split:true,
        collapsible:true,
        width:450,
        border:true,
        title:'Registro de Ingresos',
        //autoWidth: true,
        columnWidth: '0.9',
        height: 300,
        layout:'form',
        //bodyStyle: 'padding:10px;',
        padding:10,
        defaults:{  anchor:'98%'},
        labelWidth: 180,
        fileUpload: true,
        items:
        [   
                ing_codigo,
                ing_concepto,
                ing_valor,   
                ing_fecha,
                ing_proyecto,
                ing_acumulado_ingresos,
                ing_acumulado_egresos,
                ing_disponible
        ],
        buttons:
        [
            {
                text : 'Conceptos por Ingreso',
                tooltip: 'Registrar los conceptos en los cuales será utilizado el ingreso',
                iconCls : 'calcular',
                handler : function()
                {
                    var sm = crud_ingreso_gridpanel.getSelectionModel();
                    if(sm.hasSelection())
                    {
                      recargarDatosConceptos();
                      Ext.getBody().mask();
                      win_ing.show();
                    } else
                    {
                      Ext.Msg.show(
                      {
                        title : 'Información',
                        msg : 'Primero debe seleccionar un ingreso',
                        buttons : Ext.Msg.OK,
                        icon : Ext.MessageBox.INFO
                      });
                    }
                }
            }, '-',{
                text: 'Guardar',
                iconCls:'guardar',
                id:'crud_ingreso_actualizar_boton',
                handler: crud_ingreso_actualizar
            }
        ]
});

var crud_ingreso_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
        {id: 'ing_codigo', header: "Id", width: 30, dataIndex: 'ing_codigo'},
        { header: "Concepto", width: 250, dataIndex: 'ing_concepto'},        
        { header: "Valor", width: 120, dataIndex: 'ing_valor'},
        { header: "Fecha del ingreso", width: 120, dataIndex: 'ing_fecha'},
        { header: "Nombre del Proyecto", width: 220, dataIndex: 'ing_proyecto_nombre'},
        { header: "Creado por", width: 150, dataIndex: 'ing_usuario_nombre'},
        { header: "Fecha de registro", width: 120, dataIndex: 'ing_fecha_registro'}
]
});

var crud_ingreso_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_ingreso_gridpanel',
            title:'Ingresos registrados en el sistema',
            region:'center',
            stripeRows:true,
            frame: true,
            ds: crud_ingreso_datastore,
            cm: crud_ingreso_colmodel,
            selModel: new Ext.grid.RowSelectionModel({
                    singleSelect: true,
                    listeners: {
                            rowselect: function(sm, row, record) {
                                    Ext.getCmp('crud_ingreso_formpanel').getForm().loadRecord(record);
                                    Ext.getCmp('crud_ingreso_actualizar_boton').setText('Actualizar');
                                    Ext.getCmp('ing_acumulado_ingresos').setDisabled(false);
                                    Ext.getCmp('ing_acumulado_egresos').setDisabled(false);
                                    Ext.getCmp('ing_disponible').setDisabled(false);
                                    Ext.getCmp('ing_valor').setDisabled(true);
                                    Ext.getCmp('ing_proyecto').setDisabled(true);
                            }
                    }
            }),
            height: largo_panel,
            bbar: new Ext.PagingToolbar({
                    pageSize: 15,
                    store: crud_ingreso_datastore,
                    displayInfo: true,
                    displayMsg: 'Ingresos {0} - {1} de {2}',
                    emptyMsg: "No hay ingresos aun"
            }),
            tbar:
            [
                    {	
                            id:'crud_ingreso_agregar_boton',
                            text:'Agregar',
                            tooltip:'Agregar',
                            iconCls:'agregar',
                            handler:crud_ingreso_agregar
                    },'-',
                    {
                            text:'Eliminar',
                            tooltip:'Eliminar',
                            iconCls:'eliminar',
                            handler:crud_ingreso_eliminar
                    },'-',
                    {
                            text:'',
                            iconCls:'activos',
                            tooltip:'Ingresos activos',
                            handler:function(){
                                    crud_ingreso_datastore.baseParams.ing_eliminado = '0';
                                    crud_ingreso_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },{
                            text:'',
                            iconCls:'eliminados',
                            tooltip:'Ingresos eliminados',
                            handler:function(){
                                    crud_ingreso_datastore.baseParams.ing_eliminado = '1';
                                    crud_ingreso_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },'-',{
                            text:'Restablecer',
                            iconCls:'restablece',
                            tooltip:'Restablecer un ingreso eliminado',
                            handler:function(){
                                     var cant_record = crud_ingreso_gridpanel.getSelectionModel().getCount();

                                    if(cant_record > 0){
                                    var record = crud_ingreso_gridpanel.getSelectionModel().getSelected();
                                            if (record.get('ing_codigo') != '') {
                                            Ext.Msg.confirm('Restablecer ingreso', 
                                            "¿Realmente desea restablecer este ingreso?", 
                                            function(btn){
                                                if (btn == 'yes') {
                                                    subirDatosAjax( 
                                                            getAbsoluteUrl('ingresos', 'restablecerIngreso'),
                                                    {
                                                        ing_codigo:record.get('ing_codigo')
                                                    }, 
                                                    function(){
                                                            crud_ingreso_datastore.reload();
                                                    }, 
                                                    function(){}
                                                    );
                                            }
                                            });
                                         }
                                    }
                                    else {
                                            mostrarMensajeConfirmacion('Error', "Seleccione un ingreso eliminado");
                                    }
                            }
                    }
            ],
		plugins:[ new Ext.ux.grid.Search({
				mode:          'local',
				position:      top,
				searchText:    'Filtrar',
				iconCls:  'filtrar',
				selectAllText: 'Seleccionar todos',
				searchTipText: 'Escriba el texto que desea buscar y presione la tecla enter',
				width:         150
			})
		]
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_ingreso_contenedor_panel = new Ext.Panel({
        id: 'crud_ingreso_contenedor_panel',
        height: largo_panel,
        autoWidth: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede ver, agregar y eliminar ingresos',
        monitorResize:true,
        layout:'border',
        items: 
        [
                crud_ingreso_gridpanel,
                crud_ingreso_formpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_ingreso'
});
		
function crud_ingreso_actualizar(btn){
        if(crud_ingreso_formpanel.getForm().isValid())
        {
                subirDatos(
                        crud_ingreso_formpanel,
                        getAbsoluteUrl('ingresos','actualizarIngreso'),
                        {},
                        function(){
                        crud_ingreso_formpanel.getForm().reset();
                        crud_ingreso_datastore.reload(); 
                        },
                        function(){}
                        );
        }
}
        
function crud_ingreso_eliminar()
{
        var cant_record = crud_ingreso_gridpanel.getSelectionModel().getCount();

        if(cant_record > 0){
                var record = crud_ingreso_gridpanel.getSelectionModel().getSelected();
                if(record.get('ing_codigo')!='')
                {
                        Ext.Msg.confirm('Eliminar ingreso', 
                        "¿Realmente desea eliminar este ingreso?", 
                        function(btn){
                                if (btn == 'yes') {
                                        subirDatosAjax(
                                                getAbsoluteUrl('ingresos','eliminarIngreso'),
                                                {
                                                    ing_codigo:record.get('ing_codigo')
                                                },
                                                function(){
                                                crud_ingreso_datastore.reload(); 
                                                }
                                        );
                                }
                        });
                }
        }
        else{
                mostrarMensajeConfirmacion('Error',"Seleccione un ingreso a eliminar");
        }
}
	
function crud_ingreso_agregar(btn, ev) {    
        crud_ingreso_formpanel.getForm().reset();
        Ext.getCmp('crud_ingreso_actualizar_boton').setText('Guardar');
        Ext.getCmp('ing_acumulado_ingresos').setDisabled(true);
        Ext.getCmp('ing_acumulado_egresos').setDisabled(true);
        Ext.getCmp('ing_disponible').setDisabled(true);
        Ext.getCmp('ing_valor').setDisabled(false);
        Ext.getCmp('ing_proyecto').setDisabled(false);      
}