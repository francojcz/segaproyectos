var largo_panel=500;

var crud_participante_datastore = new Ext.data.Store({
id: 'crud_participante_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('participantes','listarProyecto'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'pro_codigo', type: 'int'},
                {name: 'pro_codigo_contable', type: 'string'},
                {name: 'pro_nombre', type: 'string'},
                {name: 'pro_descripcion', type: 'string'},
                {name: 'pro_valor', type: 'string'},
                {name: 'pro_fecha_inicio', type: 'string'},
                {name: 'pro_fecha_fin', type: 'string'},
                {name: 'pro_observaciones', type: 'string'},
                {name: 'pro_fecha_registro', type: 'string'},
                {name: 'pro_usu_persona', type: 'int'},
                {name: 'pro_usu_persona_pro_nombre', type: 'string'},
                {name: 'pro_estado', type: 'int'},
                {name: 'pro_estado_nombre', type: 'string'},
                {name: 'pro_presupuesto_url', type: 'string'},
                {name: 'pro_usuario', type: 'string'},
                {name: 'pro_usuario_nombre', type: 'string'}
        ])
});
crud_participante_datastore.load();

var crud_persona_datastore = new Ext.data.JsonStore({
        id: 'crud_persona_datastore',
        url: getAbsoluteUrl('participantes', 'listarPersona'),
        root: 'results',
        totalProperty: 'total',
        fields: [
                {name: 'per_codigo',type: 'int'}, 
                {name: 'per_nombre',type: 'string'}
        ],
        sortInfo: {
                field: 'per_nombre',
                direction: 'ASC'
        }
});
crud_persona_datastore.load();

var persona_codigo = new Ext.form.ComboBox({
        xtype: 'combo',
        width: 200,
        store: crud_persona_datastore,
        hiddenName: 'per_codigo',
        name: 'proper_per_codigo',
        mode: 'local',
        valueField: 'per_codigo',
        forceSelection: true,
        displayField: 'per_nombre',
        triggerAction: 'all',
        emptyText: 'Seleccione una Persona',
        selectOnFocus: true,
        fieldLabel: 'Persona',
        listeners: {
                focus : function(){
                        crud_persona_datastore.reload();
                } 
        }
});


//Asignación de Tiempos
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

var asignacion_tiempos_datastore = new Ext.data.Store(
{
    proxy : new Ext.data.HttpProxy(
    {
      url : getAbsoluteUrl('participantes', 'listarAsignacionTiempos'),
      method : 'POST'
    }),
    reader : new Ext.data.JsonReader(
    {
      root : 'data'
    }, [
    {
      name : 'adt_codigo',
      type : 'integer'
    }, {
      name : 'adt_mes',
      type : 'string'
    }, {
      name : 'adt_ano',
      type : 'string'
    }, {
      name : 'adt_asignacion',
      type : 'string'
    }, {
      name : 'adt_cod_persona',
      type : 'string'
    }, {
      name : 'adt_cod_proyecto',
      type : 'string'
    }, {
      name : 'adt_creado_por',
      type : 'string'
    }, {
      name : 'adt_fecha_registro',
      type : 'string'
    }
    ])
});

var grillaAsignacion = new Ext.grid.EditorGridPanel(
{
    store : asignacion_tiempos_datastore,
    autoWidth : true,
    region : 'center',
    stripeRows : true,
    frame : true,
    border : true,
    autoScroll : true,
    columnLines : true,
    loadMask :
    {
      msg : 'Cargando...'
    },
    columns : [
    {
      dataIndex : 'adt_mes',
      header : 'Mes',
      tooltip : 'Mes',
      width : 120,
      align : 'center'
    }, {
      dataIndex : 'adt_ano',
      header : 'A&ntilde;o',
      tooltip : 'A&ntilde;o',
      width : 100,
      align : 'center'
    }, {
      dataIndex : 'adt_asignacion',
      header : 'Asignaci&oacute;n de Tiempo',
      tooltip : 'Asignaci&oacute;n de Tiempo',
      width : 150,
      align : 'center',
      editor : new Ext.form.NumberField(),
      renderer : generarRenderer('#ffdc44', '#000000')
    }, {
      dataIndex : 'adt_creado_por',
      header : 'Creado por',
      tooltip : 'Creado por',
      width : 220,
      align : 'center'
    }, {
      dataIndex : 'adt_fecha_registro',
      header : 'Fecha de registro',
      tooltip : 'Fecha de Registro',
      width : 130,
      align : 'center'
    }],
    listeners :
    {
      afteredit : function(e)
      {            
          var sm = crud_participante_gridpanel.getSelectionModel();
          var registro = sm.getSelected();
          var proyecto = registro.get('pro_codigo');
          
          var personasSeleccionadas = personaporproyecto_gridpanel.selModel.getSelections();
          var persona = personasSeleccionadas[0].json.pro_per_codigo;
                                                
          Ext.Ajax.request(
          {
            url : getAbsoluteUrl('participantes', 'modificarAsignacionTiempos'),
            failure : function()
            {
                recargarDatosAsignacion();
            },
            success : function()
            {
                recargarDatosAsignacion();
            },
            params :
            {
                'adt_codigo' : e.record.get('adt_codigo'),
                'adt_mes': mes(e.record.get('adt_mes')),
                'adt_ano': e.record.get('adt_ano'),
                'adt_asignacion': e.value,
                'adt_persona': persona,
                'adt_proyecto': proyecto                
            }
          });
      }
    }   
});

var win = new Ext.Window(
{
    layout : 'fit',
    width : 800,
    height : 300,
    closeAction : 'hide',
    plain : true,
    title : 'Asignación de Tiempos',
    items : grillaAsignacion,
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

var recargarDatosAsignacion = function(callback)
{    
    var sm = crud_participante_gridpanel.getSelectionModel();
    var registro = sm.getSelected();
    var proyecto = registro.get('pro_codigo');
    
    var personasSeleccionadas = personaporproyecto_gridpanel.selModel.getSelections();
    var persona = personasSeleccionadas[0].json.pro_per_codigo;
       
    asignacion_tiempos_datastore.load(
    {
      params :
      {
        'cod_proyecto' : proyecto,
        'cod_persona': persona
      }
    });    
}
  

//Asignación de Participantes
var personaporproyecto_datastore = new Ext.data.Store({
    id: 'personaporproyecto_datastore',
    proxy: new Ext.data.HttpProxy({
            url: getAbsoluteUrl('participantes','listarPersonasPorProyecto'),
            method: 'POST'
    }),
    reader: new Ext.data.JsonReader({
            root: 'results',
            totalProperty: 'total',
            id: 'id'
            },[ 
                    {name: 'pro_per_codigo', type: 'int'},
                    {name: 'pro_per_nombre', type: 'string'}
        ])
});
personaporproyecto_datastore.load();

var personaporproyecto_pertenece_selmodel = new Ext.grid.CheckboxSelectionModel({
        singleSelect:true,	
        listeners: {
                rowselect: function(sm, row, rec) {
                }
        }
});

var personaporproyecto_colmodel = new Ext.grid.ColumnModel({
        defaults:{sortable: true, locked: true, resizable: true},
        columns:[
                personaporproyecto_pertenece_selmodel,
                { header: "Id", width: 30, dataIndex: 'pro_per_codigo',hidden:true},
                { header: "Nombre", width: 300, dataIndex: 'pro_per_nombre'}
        ]
});

var personaporproyecto_gridpanel = new Ext.grid.GridPanel({
        id: 'personaporproyecto_gridpanel',
        frame: true,
        region:'east',
        stripeRows: true,      
        autoWidth : true,
        split:true,
        collapsible:true,
        width:400,
        border:true,
        title:'Registro de Participantes',
        columnWidth: '0.6',
        height: 500,
        layout:'form',
        padding:10,
        defaults:{  anchor:'98%'},
        labelWidth: 150,
        fileUpload: true,
        ds: personaporproyecto_datastore,
        cm: personaporproyecto_colmodel,
        sm: personaporproyecto_pertenece_selmodel,
        tbar:[
                persona_codigo,
                {text:'Agregar a Proyecto',iconCls:'agregar',
                        handler:function(){
                                var sm = crud_participante_gridpanel.getSelectionModel();
                                var registro = sm.getSelected();
                                if((persona_codigo.getValue()!='') && (sm.hasSelection())){
                                        subirDatosAjax(
                                                getAbsoluteUrl('participantes', 'guardarPersonaPorProyecto'),
                                                {
                                                        per_codigo: persona_codigo.getValue(),
                                                        pro_codigo: registro.get('pro_codigo')
                                                }, 
                                                function(){
                                                        personaporproyecto_datastore.reload();
                                                },
                                                function(){}
                                        );
                                }
                                else {
                                      Ext.Msg.show(
                                      {
                                        title : 'Información',
                                        msg : 'Debe seleccionar el proyecto y la persona a asignar como participante.',
                                        buttons : Ext.Msg.OK,
                                        icon : Ext.MessageBox.INFO
                                      });
                                }
                        }
                }],
        bbar:[
                {
                    text:'Quitar Persona',iconCls:'eliminar',
                        handler:function(){
                                var personasSeleccionadas = personaporproyecto_gridpanel.selModel.getSelections();
                                var personasEliminar = [];
                                for(i = 0; i< personaporproyecto_gridpanel.selModel.getCount(); i++){
                                        personasEliminar.push(personasSeleccionadas[i].json.pro_per_codigo);
                                }
                                var encoded_array_personas = Ext.encode(personasEliminar);
                                var sm = crud_participante_gridpanel.getSelectionModel();
                                var registro = sm.getSelected();

                                if(registro.get('pro_codigo') != '' ){
                                        subirDatosAjax(
                                                getAbsoluteUrl('participantes', 'eliminarPersonaPorProyecto'),
                                                {
                                                        perss_codigos: encoded_array_personas,
                                                        pro_codigo: registro.get('pro_codigo')
                                                }, 
                                                function(){
                                                        personaporproyecto_datastore.reload();
                                                },
                                                function(){}
                                        );
                                }				
                        }
                }, '-', {
                    text:'Asignación de Tiempos',iconCls:'historial',
                        handler:function(){
                            recargarDatosAsignacion();
                            Ext.getBody().mask();
                            win.show();
                        }
                }
        ]
});
	
var crud_participante_formpanel = new Ext.FormPanel({
        id:'crud_participante_formpanel',
        frame: true,
        region:'east',
        split:true,
        collapsible:true,
        width:400,
        border:true,
        title:'Registro de Participantes',
        //autoWidth: true,
        columnWidth: '0.6',
        height: 500,
        layout:'form',
        //bodyStyle: 'padding:10px;',
        padding:10,
        defaults:{  anchor:'98%'},
        labelWidth: 150,
        fileUpload: true,
        items:[
                personaporproyecto_gridpanel
        ]
});

var crud_participante_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
        {id: 'pro_codigo', header: "Id", width: 30, dataIndex: 'pro_codigo'},
        { header: "C&oacute;digo Contable", width: 100, dataIndex: 'pro_codigo_contable'},
        { header: "Nombre", width: 180, dataIndex: 'pro_nombre'},
        { header: "Descripci&oacute;n", width: 250, dataIndex: 'pro_descripcion'},
        { header: "Coordinador", width: 180, dataIndex: 'pro_usu_persona_pro_nombre'}, 
        { header: "Estado", width: 100, dataIndex: 'pro_estado_nombre'}, 
        { header: "Valor", width: 100, dataIndex: 'pro_valor'},
        { header: "Fecha de inicio", width: 120, dataIndex: 'pro_fecha_inicio'},
        { header: "Fecha de finalizaci&oacute;n", width: 120, dataIndex: 'pro_fecha_fin'},      
        { header: "Creado por", width: 160, dataIndex: 'pro_usuario_nombre'},
        { header: "Fecha de registro", width: 120, dataIndex: 'pro_fecha_registro'}
]
});
	
var crud_participante_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_participante_gridpanel',
            title:'Proyectos registrados en el sistema',
//            columnWidth: '.6',
            region:'center',
            stripeRows:true,
            frame: true,
            ds: crud_participante_datastore,
            cm: crud_participante_colmodel,
            selModel: new Ext.grid.RowSelectionModel({
                    singleSelect: true,
                    listeners: {
                            rowselect: function(sm, row, record) {
                                    Ext.getCmp('crud_participante_formpanel').getForm().loadRecord(record);
                                    var sm = crud_participante_gridpanel.getSelectionModel();
                                    var registro = sm.getSelected();                                    
                                    personaporproyecto_datastore.load({params:{pro_codigo:registro.get('pro_codigo') }});
                            }
                    }
            }),
            height: largo_panel,
            bbar: new Ext.PagingToolbar({
                    pageSize: 15,
                    store: crud_participante_datastore,
                    displayInfo: true,
                    displayMsg: 'Proyectos {0} - {1} de {2}',
                    emptyMsg: "No hay proyectos aun"
            }),            
            plugins:[ new Ext.ux.grid.Search({
                            mode:          'local',
                            position:      top,
                            searchText:    'Filtrar',
                            iconCls:  'filtrar',
                            selectAllText: 'Seleccionar todos',
                            searchTipText: 'Escriba el texto que desea buscar y presione la tecla enter',
                            width:         150
                    })
            ],
            tbar:
            [
                    {
                            text:'',
                            iconCls:'activos',
                            tooltip:'Proyectos activos',
                            handler:function(){
                                    crud_participante_datastore.baseParams.pro_eliminado = '0';
                                    crud_participante_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },{
                            text:'',
                            iconCls:'eliminados',
                            tooltip:'Proyectos eliminados',
                            handler:function(){
                                    crud_participante_datastore.baseParams.pro_eliminado = '1';
                                    crud_participante_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    }
            ]
});
	

/*INTEGRACION AL CONTENEDOR*/
var crud_participante_contenedor_panel = new Ext.Panel({
        id: 'crud_participante_contenedor_panel',
        height: largo_panel,
        autoWidth: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede agregar y eliminar participantes de un proyecto',
        monitorResize:true,
        layout:'border',
        items: 
        [
                crud_participante_gridpanel,
                personaporproyecto_gridpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_participante'
});

function mes(mes){
    if(mes == 'Enero') { return 1; }
    if(mes == 'Febrero') { return 2; }
    if(mes == 'Marzo') { return 3; }
    if(mes == 'Abril') { return 4; }
    if(mes == 'Mayo') { return 5; }
    if(mes == 'Junio') { return 6; }
    if(mes == 'Julio') { return 7; }
    if(mes == 'Agosto') { return 8; }
    if(mes == 'Septiembre') { return 9; }
    if(mes === 'Octubre') { return 10; }
    if(mes === 'Noviembre') { return 11; }
    if(mes === 'Diciembre') { return 12; }
}