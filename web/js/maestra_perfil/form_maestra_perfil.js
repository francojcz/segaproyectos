/*
 manejo Perfils - tpm labs
 Desarrollado maryit sanchez
 2010
 */
//ayudas
var ayuda_maestra_per_codigo = 'C&oacute;digo identificador en el sistema';
var ayuda_maestra_per_nombre = 'Nombre perfil';

var maestra_perfil_datastore = new Ext.data.Store({
    id: 'maestra_perfil_datastore',
    proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('maestra_perfil', 'listarPerfil'),
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
        name: 'maestra_per_codigo',
        type: 'int'
    }, {
        name: 'maestra_per_nombre',
        type: 'string'
    }, {
        name: 'maestra_per_fecha_registro_sistema',
        type: 'string'
    }, {
        name: 'maestra_per_fecha_actualizacion',
        type: 'string'
    }, {
        name: 'maestra_per_usu_crea_nombre',
        type: 'string'
    }, {
        name: 'maestra_per_usu_actualiza_nombre',
        type: 'string'
    }, {
        name: 'maestra_per_causa_eliminacion',
        type: 'string'
    }, {
        name: 'maestra_per_causa_actualizacion',
        type: 'string'
    }])
});
maestra_perfil_datastore.load();


var maestra_per_codigo = new Ext.form.NumberField({
    xtype: 'numberfield',
    maxLength: 100,
    name: 'maestra_per_codigo',
    id: 'maestra_per_codigo',
    fieldLabel: 'C&ooacute;digo perfil',
    listeners: {
        'render': function(){
            ayuda('maestra_per_codigo', ayuda_maestra_per_codigo);
        }
    }
});


var maestra_per_nombre = new Ext.form.TextField({
    xtype: 'textfield',
    maxLength: 30,
    name: 'maestra_per_nombre',
    id: 'maestra_per_nombre',
    fieldLabel: 'Nombre perfil',
    allowBlank: false,
    listeners: {
        'render': function(){
            ayuda('maestra_per_nombre', ayuda_maestra_per_nombre);
        }
    }
});

var maestra_perfil_colmodel = new Ext.grid.ColumnModel({
    defaults: {
        sortable: true,
        locked: false,
        resizable: true
    },
    columns: [{
        id: 'maestra_per_codigo_column',
        header: "Id",
        width: 30,
        dataIndex: 'maestra_per_codigo'
    }, {
        id: 'maestra_per_nombre_column',
        header: "Nombre",
        width: 100,
        dataIndex: 'maestra_per_nombre',
        editor: maestra_per_nombre
    }, {
        header: "Creado por",
        width: 120,
        dataIndex: 'maestra_per_usu_crea_nombre'
    }, {
        header: "Fecha de creaci&oacute;n",
        width: 120,
        dataIndex: 'maestra_per_fecha_registro_sistema'
    }, {
        header: "Actualizado por",
        width: 120,
        dataIndex: 'maestra_per_usu_actualiza_nombre'
    }, {
        header: "Fecha de actualizaci&oacute;n",
        width: 120,
        dataIndex: 'maestra_per_fecha_actualizacion'
    }, {
        header: "Causa actualizaci&oacute;n",
        width: 120,
        dataIndex: 'maestra_per_causa_actualizacion'
    }//,
    //{ header: "Causa eliminaci&oacute;n", width: 120, dataIndex: 'maestra_per_causa_eliminacion'} lo quite porque no se puede eliminar
    ]
});


var maestra_perfil_roweditor = new Ext.ux.grid.RowEditor({
    saveText: 'Guardar',
    cancelText: 'Cancelar',
    showTooltip: function(msg){
    },
    listeners: {
        'afteredit': function(gr, obj, record, num){
        
            if (record.get('maestra_per_codigo') != '') {
            
                Ext.Msg.prompt('Perfil', 'Digite la causa de la actualizaci&oacute;n de este perfil', function(btn, text, op){
                    if (btn == 'ok') {
                        maestra_perfil_actualizar(record, text);
                    }
                });
            }
            else {
                maestra_perfil_actualizar(record, '');
            }
        },
        'canceledit': function(){
        }
    }
});

//CREACION DE LA GRILLA

var maestra_perfil_gridpanel = new Ext.grid.GridPanel({
    id: 'maestra_perfil_gridpanel',
    title: 'Perfiles',
    stripeRows: true,
    frame: true,
    ds: maestra_perfil_datastore,
    cm: maestra_perfil_colmodel,
    selModel: new Ext.grid.RowSelectionModel({
        singleSelect: true,
        moveEditorOnEnter: false
    }),
    autoExpandColumn: 'maestra_per_nombre_column',
    height: largo_panel,
    bbar: new Ext.PagingToolbar({
        pageSize: 20,
        store: maestra_perfil_datastore,
        displayInfo: true,
        displayMsg: 'Perfiles {0} - {1} de {2}',
        emptyMsg: "No hay perfiles aun"
    }),
    tbar: [    /*{	
     id:'maestra_perfil_agregar_boton',
     text:'Agregar',
     tooltip:'Agregar',
     iconCls:'agregar',
     handler:maestra_perfil_agregar
     },'-',
     {
     text:'Eliminar',
     tooltip:'Eliminar',
     iconCls:'eliminar',
     handler:maestra_perfil_eliminar
     },'-',{
     text:'',
     iconCls:'activos',
     tooltip:'Perfiles activos',
     handler:function(){
     maestra_perfil_datastore.baseParams.per_eliminado = '0';
     maestra_perfil_datastore.load({
     params: {
     start: 0,
     limit: 20
     }
     });
     }
     },{
     text:'',
     iconCls:'eliminados',
     tooltip:'Perfiles eliminados',
     handler:function(){
     maestra_perfil_datastore.baseParams.per_eliminado = '1';
     maestra_perfil_datastore.load({
     params: {
     start: 0,
     limit: 20
     }
     });
     }
     },'-',{
     text:'Restablecer',
     iconCls:'restablece',
     tooltip:'Restablecer un perfil eliminado',
     handler:function(){
     var cant_record = maestra_perfil_gridpanel.getSelectionModel().getCount();
     
     if(cant_record > 0){
     var record = maestra_perfil_gridpanel.getSelectionModel().getSelected();
     if (record.get('maestra_per_codigo') != '') {
     
     Ext.Msg.prompt('Restablecer perfil',
     'Digite la causa de restablecimiento',
     function(btn, text){
     if (btn == 'ok')  {
     subirDatosAjax(
     getAbsoluteUrl('maestra_perfil', 'restablecerPerfil'),
     {
     maestra_per_codigo:record.get('maestra_per_codigo'),
     maestra_per_causa_restablece:text
     },
     function(){
     maestra_perfil_datastore.reload();
     },
     function(){}
     );
     }
     }
     );
     }
     }
     else {
     mostrarMensajeConfirmacion('Error', "Seleccione un perfil eliminado");
     }
     }
     }*/
    ],
    plugins: [maestra_perfil_roweditor, new Ext.ux.grid.Search({
        mode: 'local',
        position: top,
        searchText: 'Filtrar',
        iconCls: 'filtrar',
        selectAllText: 'Seleccionar todos',
        searchTipText: 'Escriba el texto que desea buscar y presione la tecla enter',
        width: 100
    })]
});


/*INTEGRACION AL CONTENEDOR*/
var maestra_perfil_contenedor_panel = new Ext.Panel({
    id: 'maestra_perfil_contenedor_panel',
    height: largo_panel,
    autoWidth: true,
    border: false,
    tabTip: 'Aqu&iacute; puedes ver, agregar, eliminar perfiles',
    monitorResize: true,
    items: [maestra_perfil_gridpanel],
    renderTo: 'div_form_maestra_perfil'
});


function maestra_perfil_actualizar(record, text){
    //var record = maestra_perfil_gridpanel.getSelectionModel().getSelected();
    
    subirDatosAjax(getAbsoluteUrl('maestra_perfil', 'actualizarPerfil'), {
        maestra_per_codigo: record.get('maestra_per_codigo'),
        maestra_per_nombre: record.get('maestra_per_nombre'),
        maestra_per_causa_actualizacion: text
    }, function(){
        maestra_perfil_datastore.reload();
    });
}

function maestra_perfil_eliminar(){
    var cant_record = maestra_perfil_gridpanel.getSelectionModel().getCount();
    
    if (cant_record > 0) {
        var record = maestra_perfil_gridpanel.getSelectionModel().getSelected();
        
        if (record.get('maestra_per_codigo') != '') {
            Ext.Msg.confirm('Eliminar perfil', "Realmente desea eliminar este perfil?", function(btn){
                if (btn == 'yes') {
                
                    Ext.Msg.prompt('Eliminar perfil', 'Digite la causa de la eliminaci&oacute;n de este perfil', function(btn2, text){
                        if (btn2 == 'ok') {
                            subirDatosAjax(getAbsoluteUrl('maestra_perfil', 'eliminarPerfil'), {
                                maestra_per_codigo: record.get('maestra_per_codigo'),
                                maestra_per_causa_eliminacion: text
                            }, function(){
                                maestra_perfil_datastore.reload();
                            });
                        }
                    });
                }
            });
        }
    }
    else {
        mostrarMensajeConfirmacion('Error', "Seleccione un perfil a eliminar");
    }
}

function maestra_perfil_agregar(btn, ev){
    var row = new maestra_perfil_gridpanel.store.recordType({
        maestra_per_codigo: '',
        maestra_per_nombre: ''
    });
    
    maestra_perfil_gridpanel.getSelectionModel().clearSelections();
    maestra_perfil_roweditor.stopEditing();
    maestra_perfil_gridpanel.store.insert(0, row);
    maestra_perfil_gridpanel.getSelectionModel().selectRow(0);
    maestra_perfil_roweditor.startEditing(0);
}

