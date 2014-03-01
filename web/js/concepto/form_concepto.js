var ayuda_concepto_codigo='Código del Concepto';
var ayuda_concepto_nombre='Nombre del Concepto';
var largo_panel = 500;

var concepto_datastore = new Ext.data.Store({
id: 'concepto_datastore',
proxy: new Ext.data.HttpProxy({
        url: getAbsoluteUrl('conceptos','listarConcepto'),
        method: 'POST'
}),
baseParams:{start:0, limit:20}, 
reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total',
        id: 'id'
        },[ 
                {name: 'con_codigo', type: 'int'},
                {name: 'con_nombre', type: 'string'},
                {name: 'con_fecha_registro', type: 'string'},
                {name: 'con_usu_codigo',type: 'int'},
                {name: 'con_usu_nombre',type: 'string'}
          ])
});
concepto_datastore.load();
	

var con_codigo=new Ext.form.NumberField({
   xtype: 'numberfield',
   maxLength : 100,
   name: 'con_codigo',
   id: 'con_codigo',
   fieldLabel: 'C&ooacute;digo del Concepto',
   listeners:
   {
        'render': function() {
                        ayuda('con_codigo', ayuda_concepto_codigo);
                        }
   }
});
	

var con_nombre=new Ext.form.TextField({
   xtype: 'textfield',
   maxLength : 100,
   name: 'con_nombre',
   id: 'con_nombre',
   fieldLabel: 'Nombre tipo identificacion',
   allowBlank: false,
   listeners:
   {
        'render': function() {
                        ayuda('con_nombre', ayuda_concepto_nombre);
                        }
   }
});

var concepto_colmodel = new Ext.grid.ColumnModel({
    defaults:{sortable: true, locked: false, resizable: true},
    columns:[
            { id: 'con_codigo_column', header: "Id", width: 30, dataIndex: 'con_codigo'},
            { id: 'con_nombre_column', header: "Nombre", width: 300, dataIndex: 'con_nombre', editor:con_nombre},
            { header: "Creado por", width: 250, dataIndex: 'con_usu_nombre'},
            { header: "Fecha de creaci&oacute;n", width: 150, dataIndex: 'con_fecha_registro'}
    ]
});
	

var concepto_roweditor = new Ext.ux.grid.RowEditor({
        saveText: 'Guardar',
        cancelText: 'Cancelar',
        showTooltip: function(msg){},
        listeners:
        {
                'afteredit': function(gr,obj,record,num){
                        concepto_actualizar(record,'');

                },
                'canceledit': function(){}
        }
});


//Creación de la Grilla
var concepto_gridpanel = new Ext.grid.GridPanel({
        id: 'concepto_gridpanel',
        title:'Conceptos',
        stripeRows:true,
        frame: true,
        ds: concepto_datastore,
        cm: concepto_colmodel,
        selModel: new Ext.grid.RowSelectionModel({
                singleSelect:true,	
                moveEditorOnEnter :false
        }),
        autoExpandColumn: 'con_nombre_column',
        height: largo_panel,
        bbar: new Ext.PagingToolbar({
                pageSize: 20,
                store: concepto_datastore,
                displayInfo: true,
                displayMsg: 'Conceptos {0} - {1} de {2}',
                emptyMsg: "No hay conceptos aun"
        }),
        tbar:
        [
                {	
                        id:'concepto_agregar_boton',
                        text:'Agregar',
                        tooltip:'Agregar',
                        iconCls:'agregar',
                        handler:concepto_agregar
                }
        ],
        plugins:[concepto_roweditor,
            new Ext.ux.grid.Search({
                        mode:          'local',
                        position:      top,
                        searchText:    'Filtrar',
                        iconCls:  'filtrar',
                        selectAllText: 'Seleccionar todos',
                        searchTipText: 'Digite el texto que desea buscar y presione la tecla enter',
                        width:         170
                })
        ]
});
	

	/*INTEGRACION AL CONTENEDOR*/
	var concepto_contenedor_panel = new Ext.Panel({
		id: 'concepto_contenedor_panel',
		height: largo_panel,
		autoWidth: true,
		border: false,
		tabTip :'Aqu&iacute; puede ver, agregar y eliminar conceptos',
		monitorResize:true,
		items: 
		[
			concepto_gridpanel
		],
		renderTo:'div_form_concepto'
	});
	

function concepto_actualizar(record,text){
        subirDatosAjax(
                getAbsoluteUrl('conceptos','actualizarConcepto'),
                {
                        con_codigo: record.get('con_codigo'),
                        con_nombre: record.get('con_nombre')
                },
                function(){
                        concepto_datastore.reload(); 
                }
        );
}
        
	function concepto_eliminar()
	{
		var cant_record = concepto_gridpanel.getSelectionModel().getCount();
		if(cant_record > 0){
			var record = concepto_gridpanel.getSelectionModel().getSelected();
			
			if(record.get('con_codigo')!='')
			{
				Ext.Msg.confirm('Eliminar concepto', "¿Realmente desea eliminar el concepto?", function(btn){
					if (btn == 'yes') {					
						subirDatosAjax(
                                                        getAbsoluteUrl('conceptos','eliminarConcepto'),
                                                        {
                                                        con_codigo:record.get('con_codigo')
                                                        },
                                                        function(){
                                                        concepto_datastore.reload(); 
                                                        }
                                                );
					}
				});
			}
		}
		else{
			mostrarMensajeConfirmacion('Error',"Seleccione un concepto a eliminar");
		}
	}

	function concepto_agregar(btn, ev) {
		var row = new concepto_gridpanel.store.recordType({
			con_codigo : '',
			con_nombre: ''
		});

		concepto_gridpanel.getSelectionModel().clearSelections();
		concepto_roweditor.stopEditing();
		concepto_gridpanel.store.insert(0, row);
		concepto_gridpanel.getSelectionModel().selectRow(0);
		concepto_roweditor.startEditing(0);
	}
	
