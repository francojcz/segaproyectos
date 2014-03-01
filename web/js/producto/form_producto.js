var ayuda_prod_codigo='';
var ayuda_prod_nombre='Ingrese el nombre del producto';
var ayuda_prod_fecha_entrega='Seleccione la fecha de entrega del producto';
var ayuda_prod_proyecto='Seleccione el proyecto';
var ayuda_prod_estado='Seleccione el estado del producto';
var largo_panel=500;

var crud_producto_datastore = new Ext.data.Store({
id: 'crud_producto_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('productos','listarProducto'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'prod_codigo', type: 'int'},
                {name: 'prod_nombre', type: 'string'},
                {name: 'prod_fecha_entrega', type: 'string'},                
                {name: 'prod_fecha_registro', type: 'string'},                
                {name: 'prod_documento_url', type: 'string'},
                {name: 'prod_proyecto', type: 'string'},
                {name: 'prod_proyecto_nombre', type: 'string'},
                {name: 'prod_estado', type: 'int'},
                {name: 'prod_estado_nombre', type: 'string'},
                {name: 'prod_usuario', type: 'int'},
                {name: 'prod_usuario_nombre', type: 'string'}
        ])
});
crud_producto_datastore.load();

var crud_proyecto_datastore = new Ext.data.JsonStore({
        id: 'crud_proyecto_datastore',
        url: getAbsoluteUrl('productos', 'listarProyecto'),
        root: 'results',
        totalProperty: 'total',
        fields: [
                {name: 'proye_codigo',type: 'int'}, 
                {name: 'proye_nombre',type: 'string'}
        ],
        sortInfo: {
                field: 'proye_nombre',
                direction: 'ASC'
        }
});
crud_proyecto_datastore.load();

var crud_estado_datastore = new Ext.data.Store({
    id: 'crud_estado_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('productos', 'listarEstado'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'estd_codigo'
    }, {
        name: 'estd_nombre'
    }])
});
crud_estado_datastore.load();
	
var prod_codigo=new Ext.form.NumberField({
   xtype: 'numberfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'prod_codigo',
   id: 'prod_codigo',
   hideLabel:true,
   hidden:true,
   listeners:
   {
        'render': function() {
                        ayuda('prod_codigo', ayuda_prod_codigo);
                        }
   }
});
	
var prod_nombre=new Ext.form.TextField({
   xtype: 'textfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'prod_nombre',
   id: 'prod_nombre',
   fieldLabel: 'Nombre del producto',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('prod_nombre', ayuda_prod_nombre);
                        }
   }
});

var prod_estado = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'estd_nombre',
    hiddenName: 'prod_estado',
    name: 'prod_estado',
    fieldLabel: 'Estado del producto',
    store: crud_estado_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'estd_nombre',
    valueField: 'estd_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('estd_nombre', ayuda_prod_estado);
        },
        focus: function(){
            crud_estado_datastore.reload();
        }
    }
});

var prod_fecha_entrega=new Ext.form.DateField({
    xtype: 'datefield',		 
    labelStyle: 'text-align:right;',
    name: 'prod_fecha_entrega',
    id: 'prod_fecha_entrega',
    fieldLabel: 'Fecha de entrega',
    allowBlank: false,
    format:'Y-m-d',
    anchor:'98%',
    listeners:
    {
        'render': function() {
                        ayuda('prod_fecha_entrega', ayuda_prod_fecha_entrega);
                        }
    }
});

var prod_fecha_registro=new Ext.form.TextField({
   xtype: 'textfield',		 
   labelStyle: 'text-align:right;',
   name: 'prod_fecha_registro',
   id: 'prod_fecha_registro',
   fieldLabel: 'Fecha de registro',
   maxLength : 100,
   readOnly:true
});

var prod_documento_url=new Ext.ux.form.FileUploadField({
        xtype: 'fileuploadfield',		 
        labelStyle: 'text-align:right;',
        name: 'prod_documento_url',
        id: 'prod_documento_url',
        fieldLabel: 'Archivo',
        emptyText: 'Seleccione un archivo', 
        buttonText: '',
        disabled:false,
        buttonCfg: {iconCls: 'archivo'},  	
        allowBlank: false
});

var prod_proyecto = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'proye_nombre',
    hiddenName: 'prod_proyecto',
    name: 'prod_proyecto',
    fieldLabel: 'Nombre del Proyecto',
    store: crud_proyecto_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'proye_nombre',
    valueField: 'proye_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('proye_nombre', ayuda_prod_proyecto);
        },
        focus: function(){
            crud_proyecto_datastore.reload();
        }
    }
});

var crud_producto_formpanel = new Ext.FormPanel({
        id:'crud_producto_formpanel',
        frame: true,
        region:'east',
        split:true,
        collapsible:true,
        width:400,
        border:true,
        title:'Registro de Producto',
        //autoWidth: true,
        columnWidth: '0.6',
        height: 500,
        layout:'form',
        //bodyStyle: 'padding:10px;',
        padding:10,
        defaults:{  anchor:'98%'},
        labelWidth: 150,
        fileUpload: true,
        items:
        [   
                prod_codigo,
                prod_nombre,
                prod_estado,
                prod_fecha_entrega,                
                prod_documento_url,
                prod_proyecto
        ],
        buttons:
        [
            {
              text : 'Archivo del Producto',
              tooltip: 'Descargar el archivo con la descripci&oacute;n del producto',
              iconCls : 'abrir_manual',
              handler : function()
              {
                var sm = crud_producto_gridpanel.getSelectionModel();
                if(sm.hasSelection())
                {
                    var registro = sm.getSelected();
                    if(registro.get('prod_documento_url') === '') {
                        Ext.Msg.show(
                          {
                            title : 'Información',
                            msg : 'No se ha registrado ningún archivo',
                            buttons : Ext.Msg.OK,
                            icon : Ext.MessageBox.INFO
                          });
                    } else {
                        window.open(urlWeb + registro.get('prod_documento_url'));                     
                    }                    
                } else
                {
                  Ext.Msg.show(
                  {
                    title : 'Información',
                    msg : 'Primero debe seleccionar un producto',
                    buttons : Ext.Msg.OK,
                    icon : Ext.MessageBox.INFO
                  });
                }
              }
            }, '-',{
                text: 'Guardar',
                iconCls:'guardar',
                id:'crud_producto_actualizar_boton',
                handler: crud_producto_actualizar
            }
        ]
});

var crud_producto_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
        {id: 'prod_codigo', header: "Id", width: 30, dataIndex: 'prod_codigo'},
        { header: "Nombre", width: 220, dataIndex: 'prod_nombre'},
        { header: "Estado", width: 150, dataIndex: 'prod_estado_nombre'},
        { header: "Fecha de entrega", width: 150, dataIndex: 'prod_fecha_entrega'},
        { header: "Nombre del Proyecto", width: 200, dataIndex: 'prod_proyecto_nombre'},
        { header: "Creado por", width: 150, dataIndex: 'prod_usuario_nombre'},
        { header: "Fecha de registro", width: 150, dataIndex: 'prod_fecha_registro'}
]
});
	
var crud_producto_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_producto_gridpanel',
            title:'Productos registrados en el sistema',
            region:'center',
            stripeRows:true,
            frame: true,
            ds: crud_producto_datastore,
            cm: crud_producto_colmodel,
            selModel: new Ext.grid.RowSelectionModel({
                    singleSelect: true,
                    listeners: {
                            rowselect: function(sm, row, record) {
                                    Ext.getCmp('crud_producto_formpanel').getForm().loadRecord(record);
                                    Ext.getCmp('crud_producto_actualizar_boton').setText('Actualizar');
                            }
                    }
            }),
            height: largo_panel,
            bbar: new Ext.PagingToolbar({
                    pageSize: 15,
                    store: crud_producto_datastore,
                    displayInfo: true,
                    displayMsg: 'Productos {0} - {1} de {2}',
                    emptyMsg: "No hay productos aun"
            }),
            tbar:
            [
                    {	
                            id:'crud_productos_agregar_boton',
                            text:'Agregar',
                            tooltip:'Agregar',
                            iconCls:'agregar',
                            handler:crud_producto_agregar
                    },'-',
                    {
                            text:'Eliminar',
                            tooltip:'Eliminar',
                            iconCls:'eliminar',
                            handler:crud_producto_eliminar
                    },'-',
                    {
                            text:'',
                            iconCls:'activos',
                            tooltip:'Productos activos',
                            handler:function(){
                                    crud_producto_datastore.baseParams.prod_eliminado = '0';
                                    crud_producto_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },{
                            text:'',
                            iconCls:'eliminados',
                            tooltip:'Productos eliminados',
                            handler:function(){
                                    crud_producto_datastore.baseParams.prod_eliminado = '1';
                                    crud_producto_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },'-',{
                            text:'Restablecer',
                            iconCls:'restablece',
                            tooltip:'Restablecer un producto eliminada',
                            handler:function(){
                                     var cant_record = crud_producto_gridpanel.getSelectionModel().getCount();

                                    if(cant_record > 0){
                                    var record = crud_producto_gridpanel.getSelectionModel().getSelected();
                                            if (record.get('prod_codigo') != '') {
                                            Ext.Msg.confirm('Restablecer producto', 
                                            "¿Realmente desea restablecer este producto?", 
                                            function(btn){
                                                if (btn == 'yes') {
                                                    subirDatosAjax( 
                                                            getAbsoluteUrl('productos', 'restablecerProducto'),
                                                    {
                                                        prod_codigo:record.get('prod_codigo'),
                                                    }, 
                                                    function(){
                                                            crud_producto_datastore.reload();
                                                    }, 
                                                    function(){}
                                                    );
                                            }
                                            });
                                         }
                                    }
                                    else {
                                            mostrarMensajeConfirmacion('Error', "Seleccione un producto eliminada");
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
var crud_producto_contenedor_panel = new Ext.Panel({
        id: 'crud_producto_contenedor_panel',
        height: largo_panel,
        autoWidth: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede ver, agregar y eliminar productos',
        monitorResize:true,
        layout:'border',
        items: 
        [
                crud_producto_gridpanel,
                crud_producto_formpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_producto'
});
		
function crud_producto_actualizar(btn){
        if(crud_producto_formpanel.getForm().isValid())
        {
                subirDatos(
                        crud_producto_formpanel,
                        getAbsoluteUrl('productos','actualizarProducto'),
                        {},
                        function(){
                        crud_producto_formpanel.getForm().reset();
                        crud_producto_datastore.reload(); 
                        },
                        function(){}
                        );
        }
}
        
function crud_producto_eliminar()
{
        var cant_record = crud_producto_gridpanel.getSelectionModel().getCount();

        if(cant_record > 0){
                var record = crud_producto_gridpanel.getSelectionModel().getSelected();
                if(record.get('prod_codigo')!='')
                {
                        Ext.Msg.confirm('Eliminar producto', 
                        "¿Realmente desea eliminar este producto?", 
                        function(btn){
                                if (btn == 'yes') {
                                        subirDatosAjax(
                                                getAbsoluteUrl('productos','eliminarProducto'),
                                                {
                                                    prod_codigo:record.get('prod_codigo'),
                                                },
                                                function(){
                                                crud_producto_datastore.reload(); 
                                                }
                                        );
                                }
                        });
                }
        }
        else{
                mostrarMensajeConfirmacion('Error',"Seleccione un producto a eliminar");
        }
}
	
function crud_producto_agregar(btn, ev) {
        crud_producto_formpanel.getForm().reset();
        Ext.getCmp('crud_producto_actualizar_boton').setText('Guardar');

}