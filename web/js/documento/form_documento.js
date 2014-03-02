var ayuda_doc_codigo='';
var ayuda_doc_tipo='Seleccione el tipo de documento';
var ayuda_doc_proyecto='Seleccione el proyecto';
var largo_panel=450;

var crud_documento_datastore = new Ext.data.Store({
id: 'crud_documento_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('documentos','listarDocumento'),
        method: 'POST'
        }),
        baseParams:{start:0,limit:20}, 
        reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'doc_codigo', type: 'int'},                
                {name: 'doc_fecha_registro', type: 'string'},
                {name: 'doc_tipo', type: 'int'},
                {name: 'doc_tipo_nombre', type: 'string'},                                
                {name: 'doc_documento_url', type: 'string'},
                {name: 'doc_proyecto', type: 'string'},
                {name: 'doc_proyecto_nombre', type: 'string'},
                {name: 'doc_usuario', type: 'int'},
                {name: 'doc_usuario_nombre', type: 'string'}
        ])
});
crud_documento_datastore.load();

var crud_tipo_datastore = new Ext.data.Store({
    id: 'crud_tipo_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('documentos', 'listarTipoDocumento'),
        method: 'POST'
    }),
    baseParams: {},
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
    }, [{
        name: 'tipd_codigo'
    }, {
        name: 'tipd_nombre'
    }])
});
crud_tipo_datastore.load();

var crud_proyecto_datastore = new Ext.data.JsonStore({
        id: 'crud_proyecto_datastore',
        url: getAbsoluteUrl('documentos', 'listarProyecto'),
        root: 'results',
        totalProperty: 'total',
        fields: [
                {name: 'proyec_codigo',type: 'int'}, 
                {name: 'proyec_nombre',type: 'string'}
        ],
        sortInfo: {
                field: 'proyec_nombre',
                direction: 'ASC'
        }
});
crud_proyecto_datastore.load();

var doc_codigo=new Ext.form.NumberField({
   xtype: 'numberfield',
   labelStyle: 'text-align:right;',
   maxLength : 100,
   name: 'doc_codigo',
   id: 'doc_codigo',
   hideLabel:true,
   hidden:true,
   listeners:
   {
        'render': function() {
                        ayuda('doc_codigo', ayuda_doc_codigo);
                        }
   }
});

var doc_fecha_registro=new Ext.form.TextField({
   xtype: 'textfield',		 
   labelStyle: 'text-align:right;',
   name: 'doc_fecha_registro',
   id: 'doc_fecha_registro',
   fieldLabel: 'Fecha de registro',
   maxLength : 100,
   readOnly:true
});

var doc_tipo = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'tipd_nombre',
    hiddenName: 'doc_tipo',
    name: 'doc_tipo',
    fieldLabel: 'Tipo de Documento',
    store: crud_tipo_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'tipd_nombre',
    valueField: 'tipd_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('tipd_nombre', ayuda_doc_tipo);
        },
        focus: function(){
            crud_tipo_datastore.reload();
        }
    }
});

var doc_documento_url=new Ext.ux.form.FileUploadField({
        xtype: 'fileuploadfield',		 
        labelStyle: 'text-align:right;',
        name: 'doc_documento_url',
        id: 'doc_documento_url',
        fieldLabel: 'Archivo',
        emptyText: 'Seleccione un archivo', 
        buttonText: '',
        disabled:false,
        buttonCfg: {iconCls: 'archivo'},  	
        allowBlank: false
});

var doc_proyecto = new Ext.form.ComboBox({
    xtype: 'combobox',
    labelStyle: 'text-align:right;',
    id: 'proyec_nombre',
    hiddenName: 'doc_proyecto',
    name: 'doc_proyecto',
    fieldLabel: 'Nombre del Proyecto',
    store: crud_proyecto_datastore,
    mode: 'local',
    emptyText: 'Seleccione ...',
    displayField: 'proyec_nombre',
    valueField: 'proyec_codigo',
    triggerAction: 'all',
    forceSelection: true,
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('proyec_nombre', ayuda_doc_proyecto);
        },
        focus: function(){
            crud_proyecto_datastore.reload();
        }
    }
});
	
var crud_documento_formpanel = new Ext.FormPanel({
        id:'crud_documento_formpanel',
        frame: true,
        region:'east',
        split:true,
        collapsible:true,
        width:400,
        border:true,
        title:'Registro de Documento',
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
                doc_codigo,                
                doc_tipo,         
                doc_documento_url,
                doc_proyecto
        ],
        buttons:
        [
            {
              text : 'Archivo del Documento',
              tooltip: 'Descargar el archivo con la descripci&oacute;n del documento',
              iconCls : 'abrir_manual',
              handler : function()
              {
                var sm = crud_documento_gridpanel.getSelectionModel();
                if(sm.hasSelection())
                {
                    var registro = sm.getSelected();
                    if(registro.get('doc_documento_url') === '') {
                        Ext.Msg.show(
                          {
                            title : 'Información',
                            msg : 'No se ha registrado ningún archivo',
                            buttons : Ext.Msg.OK,
                            icon : Ext.MessageBox.INFO
                          });
                    } else {
                        window.open(urlWeb + registro.get('doc_documento_url'));                    
                    }                    
                } else
                {
                  Ext.Msg.show(
                  {
                    title : 'Información',
                    msg : 'Primero debe seleccionar un documento',
                    buttons : Ext.Msg.OK,
                    icon : Ext.MessageBox.INFO
                  });
                }
              }
            }, '-',{
                text: 'Guardar',
                iconCls:'guardar',
                id:'crud_documento_actualizar_boton',
                handler: crud_documento_actualizar
            }
        ]
});

var crud_documento_colmodel = new Ext.grid.ColumnModel({
defaults:{sortable: true, locked: false, resizable: true},
columns:[
        {id: 'doc_codigo', header: "Id", width: 30, dataIndex: 'doc_codigo'},       
        { header: "Tipo de Documento", width: 200, dataIndex: 'doc_tipo_nombre'},
        { header: "Nombre del Proyecto", width: 200, dataIndex: 'doc_proyecto_nombre'},
        { header: "Creado por", width: 150, dataIndex: 'doc_usuario_nombre'},
        { header: "Fecha de registro", width: 150, dataIndex: 'doc_fecha_registro'}
]
});
	
var crud_documento_gridpanel = new Ext.grid.GridPanel({
            id: 'crud_documento_gridpanel',
            title:'Documentos registrados en el sistema',
//            columnWidth: '.6',
            region:'center',
            stripeRows:true,
            frame: true,
            ds: crud_documento_datastore,
            cm: crud_documento_colmodel,
            selModel: new Ext.grid.RowSelectionModel({
                    singleSelect: true,
                    listeners: {
                            rowselect: function(sm, row, record) {
                                    Ext.getCmp('crud_documento_formpanel').getForm().loadRecord(record);
                                    Ext.getCmp('crud_documento_actualizar_boton').setText('Actualizar');
                            }
                    }
            }),
            height: largo_panel,
            bbar: new Ext.PagingToolbar({
                    pageSize: 15,
                    store: crud_documento_datastore,
                    displayInfo: true,
                    displayMsg: 'Documentos {0} - {1} de {2}',
                    emptyMsg: "No hay documentos aun"
            }),
            tbar:
            [
                    {	
                            id:'crud_documento_agregar_boton',
                            text:'Agregar',
                            tooltip:'Agregar',
                            iconCls:'agregar',
                            handler:crud_documento_agregar
                    },'-',
                    {
                            text:'Eliminar',
                            tooltip:'Eliminar',
                            iconCls:'eliminar',
                            handler:crud_documento_eliminar
                    },'-',
                    {
                            text:'',
                            iconCls:'activos',
                            tooltip:'Documentos activos',
                            handler:function(){
                                    crud_documento_datastore.baseParams.doc_eliminado = '0';
                                    crud_documento_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },{
                            text:'',
                            iconCls:'eliminados',
                            tooltip:'Documentos eliminados',
                            handler:function(){
                                    crud_documento_datastore.baseParams.doc_eliminado = '1';
                                    crud_documento_datastore.load({
                                            params: {
                                                    start: 0,
                                                    limit: 20
                                            }
                                    });
                            }
                    },'-',{
                            text:'Restablecer',
                            iconCls:'restablece',
                            tooltip:'Restablecer un documento eliminado',
                            handler:function(){
                                     var cant_record = crud_documento_gridpanel.getSelectionModel().getCount();

                                    if(cant_record > 0){
                                    var record = crud_documento_gridpanel.getSelectionModel().getSelected();
                                            if (record.get('doc_codigo') != '') {
                                            Ext.Msg.confirm('Restablecer documento', 
                                            "¿Realmente desea restablecer este documento?", 
                                            function(btn){
                                                if (btn == 'yes') {
                                                    subirDatosAjax( 
                                                            getAbsoluteUrl('documentos', 'restablecerDocumento'),
                                                    {
                                                        doc_codigo:record.get('doc_codigo')
                                                    }, 
                                                    function(){
                                                            crud_documento_datastore.reload();
                                                    }, 
                                                    function(){}
                                                    );
                                            }
                                            });
                                         }
                                    }
                                    else {
                                            mostrarMensajeConfirmacion('Error', "Seleccione un documento eliminado");
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
var crud_documento_contenedor_panel = new Ext.Panel({
        id: 'crud_documento_contenedor_panel',
        height: largo_panel,
        autoWidth: true,
        //width:1000,
        border: false,
        tabTip :'Aqui puede ver, agregar y eliminar documentos',
        monitorResize:true,
        layout:'border',
        items: 
        [
                crud_documento_gridpanel,
                crud_documento_formpanel
        ],
        buttonAlign :'left',
        renderTo:'div_form_documento'
});
		
function crud_documento_actualizar(btn){
        if(crud_documento_formpanel.getForm().isValid())
        {
                subirDatos(
                        crud_documento_formpanel,
                        getAbsoluteUrl('documentos','actualizarDocumento'),
                        {},
                        function(){
                        crud_documento_formpanel.getForm().reset();
                        crud_documento_datastore.reload(); 
                        },
                        function(){}
                        );
        }
}
        
function crud_documento_eliminar()
{
        var cant_record = crud_documento_gridpanel.getSelectionModel().getCount();

        if(cant_record > 0){
                var record = crud_documento_gridpanel.getSelectionModel().getSelected();
                if(record.get('doc_codigo')!='')
                {
                        Ext.Msg.confirm('Eliminar documento', 
                        "¿Realmente desea eliminar este documento?", 
                        function(btn){
                                if (btn == 'yes') {
                                        subirDatosAjax(
                                                getAbsoluteUrl('documentos','eliminarDocumento'),
                                                {
                                                    doc_codigo:record.get('doc_codigo')
                                                },
                                                function(){
                                                crud_documento_datastore.reload(); 
                                                }
                                        );
                                }
                        });
                }
        }
        else{
                mostrarMensajeConfirmacion('Error',"Seleccione un documento a eliminar");
        }
}
	
function crud_documento_agregar(btn, ev) {
        crud_documento_formpanel.getForm().reset();
        Ext.getCmp('crud_documento_actualizar_boton').setText('Guardar');

}