
var emplusu_password_valor_trae = '';

var ayuda_emplusu_usu_codigo = 'Id del usuario';
var ayuda_emplusu_login = 'Login del usuario, min&iacute;mo 4 digitos ';
var ayuda_emplusu_password = 'Password del usuario, min&iacute;mo 8 digitos ';
var ayuda_emplusu_repassword = 'Repetir el password del usuario';
var ayuda_emplusu_per_codigo = 'Seleccione el perfil del usuario';
var ayuda_emplusu_habilitado = 'Seleccione si el usuario esta habilitado';

var ayuda_emplusu_codigo = 'Id del empleado';
var ayuda_emplusu_nombres = 'Escriba el nombre del empleado';
var ayuda_emplusu_apellidos = 'Escriba el apellido del empleado';
var ayuda_emplusu_numero_identificacion = 'Escriba el número de identificación';
var ayuda_emplusu_tid_codigo = 'Escoja el tipo de identificación';
var ayuda_emplusu_emp_codigo = 'Seleccione la empresa';
var ayuda_emplusu_url_foto = 'Seleccione la foto del empleado';
var ayuda_emplusu_emplusu_codigo = 'Seleccione el login usuario que usará el empleado';

var largo_panel = 500;

var crud_empleadousuario_datastore = new Ext.data.JsonStore({
    url: getAbsoluteUrl('crud_empleadousuario', 'listarUsuarios'),
    root: 'results',
    baseParams: {
        emplusu_habilitado: '1'
    },
    totalProperty: 'total',
    reader: new Ext.data.JsonReader({
        root: 'results',
        totalProperty: 'total'
    }),
    fields: [ //datos usuario
    {
        name: 'emplusu_usu_codigo',
        type: 'int'
    }, {
        name: 'emplusu_login',
        type: 'string'
    }, {
        name: 'emplusu_per_codigo',
        type: 'int'
    }, {
        name: 'emplusu_habilitado',
        type: 'int'
    }, {
        name: 'emplusu_per_nombre',
        type: 'string'
    }, {
        name: 'emplusu_crea_nombre',
        type: 'string'
    }, {
        name: 'emplusu_actualiza_nombre',
        type: 'string'
    }, //datos empleado
    {
        name: 'emplusu_codigo',
        type: 'int'
    }, {
        name: 'emplusu_nombres',
        type: 'string'
    }, {
        name: 'emplusu_apellidos',
        type: 'string'
    }, {
        name: 'emplusu_numero_identificacion',
        type: 'string'
    }, {
        name: 'emplusu_tid_codigo',
        type: 'int'
    }, {
        name: 'emplusu_tid_nombre',
        type: 'string'
    }, {
        name: 'emplusu_emp_codigo',
        type: 'int'
    }, {
        name: 'emplusu_emplusu_codigo',
        type: 'int'
    }, {
        name: 'emplusu_url_foto',
        type: 'string'
    }, {
        name: 'emplusu_fecha_registro_sistema',
        type: 'string'
    }, {
        name: 'emplusu_fecha_actualizacion',
        type: 'string'
    }, {
        name: 'emplusu_emplusu_crea_nombre',
        type: 'string'
    }, {
        name: 'emplusu_emplusu_actualiza_nombre',
        type: 'string'
    }, {
        name: 'emplusu_causa_eliminacion',
        type: 'string'
    }, {
        name: 'emplusu_causa_actualizacion',
        type: 'string'
    }],
    sortInfo: {
        field: 'emplusu_login',
        direction: 'ASC'
    }
});
crud_empleadousuario_datastore.load({
    params: {
        start: 0,
        limit: 20
    }
});

var crud_empleadousuario_columnmodel = new Ext.grid.ColumnModel({
    columns: [{
        header: 'Login',
        dataIndex: 'emplusu_login',
        width: 100
    }, {
        header: 'Perfil',
        dataIndex: 'emplusu_per_nombre',
        width: 100
    }, {
        header: 'Habilitado',
        dataIndex: 'emplusu_habilitado',
        width: 80,
        renderer: function(val){
            if (val == '1') {
                return '<img src="' + urlPrefix + '../images/iconos/habilitado.png" >';
            }
            else {
                return '<img src="' + urlPrefix + '../images/iconos/deshabilitado.png" >';
            }
        }
    }, {
        header: 'Nombres',
        dataIndex: 'emplusu_nombres',
        width: 150
    }, {
        header: 'Apellidos',
        dataIndex: 'emplusu_apellidos',
        width: 150
    }, {
        header: 'Tipo identificación',
        dataIndex: 'emplusu_tid_nombre',
        width: 100
    }, {
        header: 'Número identificación',
        dataIndex: 'emplusu_numero_identificacion',
        width: 100
    }, {
        header: "Creado por",
        width: 120,
        dataIndex: 'emplusu_crea_nombre'
    }, {
        header: "Fecha de creaci&oacute;n",
        width: 120,
        dataIndex: 'emplusu_fecha_registro_sistema'
    }, {
        header: "Actualizado por",
        width: 120,
        dataIndex: 'emplusu_actualiza_nombre'
    }, {
        header: "Fecha de actualizaci&oacute;n",
        width: 120,
        dataIndex: 'emplusu_fecha_actualizacion'
    }, {
        header: "Causa actualizaci&oacute;n",
        width: 120,
        dataIndex: 'emplusu_causa_actualizacion'
    }],
    defaults: {
        sortable: true
    }
});

var crud_empleadousuario_gridpanel = new Ext.grid.GridPanel({
    columnWidth: '.5',
    frame: true,
    region: 'center',
    height: largo_panel - 20,
    cm: crud_empleadousuario_columnmodel,
    stripeRows: true,
    ds: crud_empleadousuario_datastore,
    title: 'Lista de usuarios del sistema',
    border: true,
    bbar: new Ext.PagingToolbar({
        pageSize: 10,
        store: crud_empleadousuario_datastore,
        displayInfo: true,
        displayMsg: 'Usuarios {0} - {1} de {2}',
        emptyMsg: "No hay Usuarios"
    }),
    listeners: {
        render: function(g){
            g.getSelectionModel().selectRow(0);
        },
        delay: 10
    },
    tbar: [{
        text: 'Agregar',
        tooltip: 'Agregar un usuario',
        iconCls: 'agregar',
        handler: empleadousuario_agregar
    }, {
        text: '',
        tooltip: 'Usuarios habilitados',
        iconCls: 'usuario_habilitados_icono',
        handler: function(){
            crud_empleadousuario_datastore.baseParams.emplusu_habilitado = '1';
            crud_empleadousuario_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, {
        text: '',
        tooltip: 'Usuarios deshabilitados',
        iconCls: 'usuario_deshabilitados_icono',
        handler: function(){
            crud_empleadousuario_datastore.baseParams.emplusu_habilitado = '0';
            crud_empleadousuario_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }, {
        text: '',
        tooltip: 'Todos los usuarios',
        iconCls: 'usuario_todos_icono',
        handler: function(){
            crud_empleadousuario_datastore.baseParams.emplusu_habilitado = '';
            crud_empleadousuario_datastore.load({
                params: {
                    start: 0,
                    limit: 20
                }
            });
        }
    }],
    selModel: new Ext.grid.RowSelectionModel({
        singleSelect: true,
        listeners: {
            rowselect: function(sm, row, rec){
            
                crud_empleadousuario_usu_formpanel.getForm().loadRecord(rec);
                crud_empleadousuario_empl_formpanel.getForm().loadRecord(rec);
                
                Ext.getCmp('crud_empleadousuario_guardar_boton').setText('Actualizar');
                //crud_empleadousuario_formpanel.setTitle('Actualizar usuario');
                emplusu_login.setDisabled(true);
                
                emplusu_password.setValue('');
                emplusu_repassword.setValue('');
                
                if (rec.data.emplusu_url_foto != '') {
                    Ext.get('emplusu_image_foto').dom.src = urlPrefix + '../' + rec.data.emplusu_url_foto;
                }
                else {
                    Ext.get('emplusu_image_foto').dom.src = urlPrefix + '../images/vacio.png';
                }
                
            }
        }
    })
});


var usuarioempleado_perfil_datastore = new Ext.data.JsonStore({
    url: getAbsoluteUrl('crud_empleadousuario', 'listarPerfil'),
    root: 'results',
    totalProperty: 'total',
    fields: [{
        name: 'per_codigo',
        type: 'string'
    }, {
        name: 'per_nombre',
        type: 'string'
    }, ],
    sortInfo: {
        field: 'per_nombre',
        direction: 'ASC'
    }
});
usuarioempleado_perfil_datastore.load();

var emplusu_login = new Ext.form.TextField({
    fieldLabel: 'Login de usuario',
    allowBlank: false,
    name: 'emplusu_login',
    id: 'emplusu_login',
    blankText: 'El login es obligatorio',
    vtype: 'alphanum',
    maxLength: 200,
    minLength: 4,
    listeners: {
        render: function(){
            ayuda('emplusu_login', ayuda_emplusu_login);
        }
    }
});

var emplusu_password = new Ext.form.TextField({
    fieldLabel: 'Password',
    name: 'emplusu_password',
    id: 'emplusu_password',
    inputType: 'password',
    blankText: 'La password es obligatorio y debe tener m&iacute;nimo 4 digitos',
    allowBlank: true,
    maxLength: 200,
    minLength: 8,
    listeners: {
        render: function(){
            ayuda('emplusu_password', ayuda_emplusu_password);
        }
    }
});


var emplusu_repassword = new Ext.form.TextField({
    fieldLabel: 'Repetir password',
    name: 'emplusu_repassword',
    id: 'emplusu_repassword',
    initialPassField: 'emplusu_repassword',
    allowBlank: true,
    inputType: 'password',
    blankText: 'Repetir el password, es obligatorio',
    maxLength: 200,
    minLength: 8,
    listeners: {
        render: function(){
            ayuda('emplusu_repassword', ayuda_emplusu_repassword);
        }
    }
});

var emplusu_per_codigo = new Ext.form.ComboBox({
    xtype: 'combo',
    allowBlank: false,
    store: usuarioempleado_perfil_datastore,
    hiddenName: 'per_codigo',
    name: 'emplusu_per_codigo',
    id: 'emplusu_per_codigo',
    mode: 'local',
    valueField: 'per_codigo',
    forceSelection: true,
    displayField: 'per_nombre',
    triggerAction: 'all',
    emptyText: 'Seleccione un perfil...',
    selectOnFocus: true,
    fieldLabel: 'Perfil usuario',
    listeners: {
        render: function(){
            ayuda('emplusu_per_codigo', ayuda_emplusu_per_codigo);
        },
        focus: function(){
            usuarioempleado_perfil_datastore.reload();
        }
    }
});

var emplusu_habilitado = new Ext.form.RadioGroup({
    xtype: 'radiogroup',
    fieldLabel: 'Habilitado',
    id: 'emplusu_habilitado',
    allowBlank: false,
    //columns: 1,
    items: [{
        boxLabel: 'Si',
        name: 'emplusu_habilitado',
        id: 'emplusu_habilitado_si',
        inputValue: 1,
        cheked: true
    }, {
        boxLabel: 'No',
        name: 'emplusu_habilitado',
        id: 'emplusu_habilitado_no',
        inputValue: 0
    }],
    listeners: {
        render: function(){
            ayuda('emplusu_habilitado', ayuda_emplusu_habilitado);
        }
    }
});


var crud_empleadousuario_usu_formpanel = new Ext.FormPanel({
    columnWidth: '.48',
    width: 450,
    url: getAbsoluteUrl('crud_empleadousuario', 'cargar'),
    labelAlign: 'left',
    title: 'Datos de usuario',
    padding: 10,
    autoScroll: true,
    layout: 'form',
    labelWidth: 110,
    defaults: {
        allowBlank: true,
        anchor: '98%',
        labelStyle: 'text-align:right;'
    },
    items: [{
        xtype: 'label',
        html: '<center>Todos los campos son obligatorios<br/><br/></center>',
        style: 'font-size:8.5pt; color:#484848;font-weight: bold;'
    }, {
        xtype: 'textfield',
        fieldLabel: 'Id de usuario',
        hidden: true,
        hiddenLabel: true,
        name: 'emplusu_usu_codigo',
        id: 'emplusu_usu_codigo'
    }, emplusu_login, emplusu_password, emplusu_repassword, emplusu_per_codigo, emplusu_habilitado]
});


var emplusu_nombres = new Ext.form.TextField({
    fieldLabel: 'Nombres',
    name: 'emplusu_nombres',
    id: 'emplusu_nombres',
    allowBlank: false,
    blankText: 'Escribir nombres',
    maxLength: 200,
    listeners: {
        render: function(){
            ayuda('emplusu_nombres', ayuda_emplusu_nombres);
        }
    }
});

var emplusu_apellidos = new Ext.form.TextField({
    fieldLabel: 'Apellidos',
    name: 'emplusu_apellidos',
    id: 'emplusu_apellidos',
    allowBlank: false,
    blankText: 'Escribir apellidos',
    maxLength: 200,
    listeners: {
        render: function(){
            ayuda('emplusu_apellidos', ayuda_emplusu_apellidos);
        }
    }
});


var emplusu_numero_identificacion = new Ext.form.NumberField({
    fieldLabel: 'Número Identificación',
    name: 'emplusu_numero_identificacion',
    id: 'emplusu_numero_identificacion',
    allowBlank: false,
    blankText: 'Escribir número identificación',
    allowDecimal: false,
    allowNegative: false,
    maxLength: 10,
    listeners: {
        render: function(){
            ayuda('emplusu_numero_identificacion', ayuda_emplusu_numero_identificacion);
        }
    }
});

var crud_empleadousuario_tipo_identificacion_datastore = new Ext.data.JsonStore({
    url: getAbsoluteUrl('crud_empleadousuario', 'listarTipoidentificacion'),
    root: 'results',
    totalProperty: 'total',
    fields: [{
        name: 'tid_codigo',
        type: 'int'
    }, {
        name: 'tid_nombre',
        type: 'string'
    }, ],
    sortInfo: {
        field: 'tid_nombre',
        direction: 'ASC'
    }
});
crud_empleadousuario_tipo_identificacion_datastore.load();


var emplusu_tid_codigo = new Ext.form.ComboBox({
    xtype: 'combo',
    store: crud_empleadousuario_tipo_identificacion_datastore,
    hiddenName: 'tid_codigo',
    name: 'emplusu_tid_codigo',
    id: 'emplusu_tid_codigo',
    mode: 'local',
    valueField: 'tid_codigo',
    forceSelection: true,
    displayField: 'tid_nombre',
    triggerAction: 'all',
    emptyText: 'Seleccione un tipo identificación...',
    selectOnFocus: true,
    fieldLabel: 'Tipo identificación',
    allowBlank: false,
    listeners: {
        render: function(){
            ayuda('emplusu_tid_codigo', ayuda_emplusu_tid_codigo);
        },
        focus: function(){
            crud_empleadousuario_tipo_identificacion_datastore.reload();
        }
    }
});


var crud_empleadousuario_empresa_datastore = new Ext.data.JsonStore({
    url: getAbsoluteUrl('crud_empleadousuario', 'listarEmpresa'),
    root: 'results',
    totalProperty: 'total',
    fields: [{
        name: 'emp_codigo',
        type: 'string'
    }, {
        name: 'emp_nombre',
        type: 'string'
    }, ],
    sortInfo: {
        field: 'emp_nombre',
        direction: 'ASC'
    }
});
crud_empleadousuario_empresa_datastore.load();


var emplusu_emp_codigo = new Ext.form.ComboBox({
    xtype: 'combo',
    store: crud_empleadousuario_empresa_datastore,
    hiddenName: 'emp_codigo',
    name: 'emplusu_emp_codigo',
    id: 'emplusu_emp_codigo',
    mode: 'local',
    valueField: 'emp_codigo',
    forceSelection: true,
    displayField: 'emp_nombre',
    triggerAction: 'all',
    emptyText: 'Seleccione un empresa...',
    selectOnFocus: true,
    fieldLabel: 'Empresa',
    allowBlank: false,
    listeners: {
        render: function(){
            ayuda('emplusu_emp_codigo', ayuda_emplusu_emp_codigo);
        },
        focus: function(){
            crud_empleadousuario_empresa_datastore.reload();
        }
    }
});

var emplusu_url_foto = new Ext.ux.form.FileUploadField({
    xtype: 'fileuploadfield',
    labelStyle: 'text-align:right;',
    name: 'emplusu_foto',
    id: 'emplusu_url_foto',
    fieldLabel: '<html>Escoger Foto</html>',
    emptyText: 'Seleccione una imagen',
    buttonText: '',
    disabled: false,
    buttonCfg: {
        iconCls: 'archivo'
    },
    allowBlank: true,
    listeners: {
        'render': function(){
            ayuda('emplusu_url_foto', ayuda_emplusu_url_foto);
        }
    }
});


var emplusu_image_foto = new Ext.Panel({
    height: 110,
    bodyStyle: 'text-align:center;margin-left:auto;',
    html: '<img id="emplusu_image_foto" width=100 heigth=100 align=center />'
});

var crud_empleadousuario_empl_formpanel = new Ext.FormPanel({
    columnWidth: '.5',
    width: 450,
    url: getAbsoluteUrl('crud_empleadousuario', 'cargar'),
    labelAlign: 'left',
    title: 'Datos de empleado',
    padding: 5,
    autoScroll: true,
    labelWidth: 140,
    fileUpload: true,
    defaults: {
        allowBlank: true,
        anchor: '98%',
        labelStyle: 'text-align:right;'
    },
    items: [{
        xtype: 'label',
        html: '<center>Todos los campos son obligatorios<br/><br/></center>',
        style: 'font-size:8.5pt; color:#484848;font-weight: bold;'
    }, {
        xtype: 'textfield',
        fieldLabel: 'Id de empleado',
        hidden: true,
        hiddenLabel: true,
        name: 'emplusu_codigo',
        id: 'emplusu_codigo'
    }, emplusu_nombres, emplusu_apellidos, emplusu_tid_codigo, emplusu_numero_identificacion, emplusu_emp_codigo, emplusu_url_foto]
});


var crud_empleadousuario_contenedor = new Ext.Panel({
    id: 'crud_empleadousuario_contenedor',
    url: getAbsoluteUrl('crud_empleadousuario', 'cargar'),
    monitorResize: true,
    labelAlign: 'left',
    border: false,
    bodyStyle: 'padding:5px',
    autoScroll: true,
    height: largo_panel,
    layout: 'border',
    autoWidth: true,
    items: [crud_empleadousuario_gridpanel, {
        xtype: 'panel',
        title: 'Datos del usuario',
        region: 'east',
        width: '600',
        frame: true,
        split: true,
        height: largo_panel - 20,
        collapsible: true,
        columnWidth: '.48',
        layout: 'column',
        items: [{
            layout: 'form',
            columnWidth: '1',
            items: [emplusu_image_foto]
        }, crud_empleadousuario_usu_formpanel, crud_empleadousuario_empl_formpanel],
        buttons: [{
            text: 'Crear',
            align: 'center',
            id: 'crud_empleadousuario_guardar_boton',
            iconCls: 'guardar',
            handler: function(formu, accion){
            
                if (Ext.getCmp('crud_empleadousuario_guardar_boton').getText() == 'Actualizar') {
                    Ext.Msg.prompt('Empleado', 'Digite la causa de la actualizaci&oacute;n de este empleado', function(btn, text, op){
                        if (btn == 'ok') {
                            empleadousuario_actualizar(formu, accion, text);
                        }
                    });
                }
                else {
                    empleadousuario_actualizar(formu, accion, '');
                }
            }
        }]
    }],
    renderTo: 'div_form_crud_empleadousuario'
});

Ext.get('emplusu_image_foto').dom.src = urlPrefix + '../images/vacio.png';

/*************************************/
/*Aqui tenemos el manejo de eventos tanto de crear , actualizar, eliminar*/
/*************************************/
function empleadousuario_agregar(formulario, accion){

    //var usuario_titulo_Panel = 'Nuevo usuario';
    //crud_empleadousuario_formpanel.setTitle(usuario_titulo_Panel);
    crud_empleadousuario_usu_formpanel.getForm().reset();
    crud_empleadousuario_empl_formpanel.getForm().reset();
    
    emplusu_login.setDisabled(false);
    emplusu_password.setDisabled(false);
    emplusu_repassword.setDisabled(false);
    emplusu_per_codigo.setDisabled(false);
    cargaEmpresa();
    
    //manejo de habilitado por defecto
    Ext.getCmp('emplusu_habilitado_si').setDisabled(false);
    Ext.getCmp('emplusu_habilitado_no').setDisabled(false);
    Ext.getCmp('emplusu_habilitado_si').setValue(true);
    Ext.getCmp('emplusu_habilitado_no').setValue(false);
    Ext.getCmp('crud_empleadousuario_guardar_boton').setText('Crear');
}

function empleadousuario_actualizar(formulario, accion, text){

    var verificacion = empleadousuario_verificarcampos(Ext.getCmp('crud_empleadousuario_guardar_boton').getText());
    
    if (verificacion) {
        if (Ext.getCmp('crud_empleadousuario_guardar_boton').getText() == 'Actualizar') {
            task = 'actualizarUsuario';
            Ext.getCmp('emplusu_codigo').setDisabled(false);
            Ext.getCmp('emplusu_login').setDisabled(false);
        }
        else {
            task = 'crearUsuario';
        }
        crud_empleadousuario_empl_formpanel.getForm().submit({
            method: 'POST',
            url: getAbsoluteUrl('crud_empleadousuario', task),
            params: {
                task: task,
                emplusu_causa_actualizacion: text,
                emplusu_causa_actualizacion: text,
                emplusu_usu_codigo: Ext.getCmp('emplusu_usu_codigo').getValue(),
                emplusu_login: emplusu_login.getValue(),
                emplusu_password: emplusu_password.getValue(),
                emplusu_repassword: emplusu_repassword.getValue(),
                per_codigo: emplusu_per_codigo.getValue(),
                emplusu_habilitado: emplusu_habilitado.getValue().getGroupValue()
            },
            waitTitle: 'Enviando',
            waitMsg: 'Enviando datos...',
            success: function(response, action){
                obj = Ext.util.JSON.decode(action.response.responseText);
                mostrarMensajeRapido('Aviso', obj.mensaje);
                // crud_empleadousuario_datastore.reload();
                crud_empleadousuario_datastore.baseParams.emplusu_habilitado = '';
                crud_empleadousuario_datastore.load({
                    params: {
                        start: 0,
                        limit: 20
                    }
                });
                Ext.getCmp('emplusu_codigo').setDisabled(true);
                if (task == 'actualizarUsuario') {
                    Ext.getCmp('emplusu_login').setDisabled(true);
                }
                else {
                    crud_empleadousuario_usu_formpanel.getForm().reset();
                    crud_empleadousuario_empl_formpanel.getForm().reset();
                }
            },
            failure: function(form, action, response){
                if (action.failureType == 'server') {
                    obj = Ext.util.JSON.decode(action.response.responseText);
                    mostrarMensajeConfirmacion('Error', obj.errors.reason);
                }
                
                Ext.getCmp('emplusu_codigo').setDisabled(true);
                if (task == 'actualizarUsuario') {
                    Ext.getCmp('emplusu_login').setDisabled(true);
                }
            }
        });
        
        
    }
}



function empleadousuario_verificarcampos(accion){
    var valido = true;
    
    if (!(Ext.getCmp('emplusu_login').isValid() &&
    Ext.getCmp('emplusu_password').isValid())) {
        mostrarMensajeRapido('Aviso', 'Faltan campos por llenar, por favor verifique el login y password');
        return false;
    }
    
    if (Ext.getCmp('emplusu_password').getValue() != '' || accion == "Crear") {
        if (!(Ext.getCmp('emplusu_password').getValue() == Ext.getCmp('emplusu_repassword').getValue())) {
            mostrarMensajeConfirmacion('Aviso', 'La password y la repassword deben ser iguales');
            return false;
        }
        
        if ((Ext.getCmp('emplusu_password').getValue()).length < 8) {
            mostrarMensajeConfirmacion('Aviso', 'La password debe tener m&iacute;nimo 8 digitos');
            return false;
        }
        
    }
    
    if (!(Ext.getCmp('emplusu_per_codigo').validate())) {
        mostrarMensajeConfirmacion('Aviso', 'Debe selecionar un perfil');
        return false;
    }
    
    if (Ext.getCmp('emplusu_nombres').getValue() == '') {
        mostrarMensajeConfirmacion('Aviso', 'Debe llenar el nombre');
        return false;
    }
    if (Ext.getCmp('emplusu_numero_identificacion').getValue() == '') {
        mostrarMensajeConfirmacion('Aviso', 'Debe llenar el n&uacute;mero de identificación');
        return false;
    }
    if (Ext.getCmp('emplusu_tid_codigo').getValue() == '') {
        mostrarMensajeConfirmacion('Aviso', 'Debe seleccionar el tipo de identificación');
        return false;
    }
    
    return valido;
}

function cargaEmpresa(){
    //seleccionar la primera empresa de la lista cuando se de click en nuevo
    //
    if (crud_empleadousuario_empresa_datastore.getCount() > 0) {
        var record_emp = crud_empleadousuario_empresa_datastore.getAt(0);
        emplusu_emp_codigo.setValue(record_emp.data.emp_codigo);
    }
}





