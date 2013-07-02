
var ayuda_usu_login = 'Ingrese el login suministrado para poder ingresar al sistema';
var ayuda_usu_password = 'Ingrese el password suministrado para poder ingresar al sistema';

var login_panel = new Ext.form.FormPanel({
    autoHeight: true,
    padding: 10,
    defaultType: 'textfield',
    border: true,
    bodyStyle: 'background-color: #dde8f8;',
    x: 218,
    y: 320,
    width: 300,
    labelWidth: 80,
    xtype: 'fieldset',
    items: [{
        fieldLabel: 'Usuario',
        anchor: '100%',
        id: 'usu_login',
        name: 'usu_login',
        maxLength: 30,
        minLength: 4,
        vtype: 'alphanum',
        allowBlank: false,
        listeners: {
            specialkey: function(field, e){
                if (e.getKey() == e.ENTER) {
                    if (Ext.getCmp('usu_login').isValid() && Ext.getCmp('usu_password').isValid()) {
                        login_autenticar();
                    }
                    else {
                        Ext.example.msg('Error', 'Campos incompletos');
                    }
                }
            },
            'render': function(){
                ayuda('usu_login', ayuda_usu_login);
            }
        }
    }, {
        fieldLabel: 'Password',
        inputType: 'password',
        maxLength: 32,
        minLength: 4,
        anchor: '100%',
        id: 'usu_password',
        name: 'usu_password',
        allowBlank: false,
        listeners: {
            specialkey: function(field, e){
                if (e.getKey() == e.ENTER) {
                    if (Ext.getCmp('usu_login').isValid() && Ext.getCmp('usu_password').isValid()) {
                        login_autenticar();
                    }
                    else {
                        Ext.example.msg('Error', 'Campos incompletos');
                    }
                }
            },
            'render': function(){
                ayuda('usu_password', ayuda_usu_password);
            }
        }
    }],
    buttons: [{
        text: 'Ingresar',
        iconCls: 'login',
        handler: function(){
            if (Ext.getCmp('usu_login').isValid() && Ext.getCmp('usu_password').isValid()) {
                login_autenticar();
            }
            else {
                Ext.example.msg('Error', 'Campos incompletos');
            }
        }
    }]
});

var form_login = new Ext.Panel({
    renderTo: 'login',
    padding: 5,
    layout: 'absolute',
    bodyStyle: 'background-color: transparent; background-image: url(' + urlPrefix + '../images/fondo-login.png); ',
    border: false,
    width: 720,
    height: 525,
    items: [login_panel]
});


function login_autenticar(){
    var certificado = '';
    var db = null;
    if (window.google && google.gears) {
        try {
            db = google.gears.factory.create('beta.database');
            if (db) {
                db.open('certificados-db');
                db.execute('create table if not exists certificado (contenido varchar(40))');
                
                resultados = db.execute('select * from certificado');
                if (resultados.isValidRow()) {
                    certificado = resultados.field(0);
                }
                resultados.close();
            }
            db.close();
        } 
        catch (ex) {
            db.close();
        }
    }
    Ext.Ajax.request({
        waitMsg: 'Espere por favor',
        url: getAbsoluteUrl('login', 'autenticar'),
        params: {
            usu_login: Ext.getCmp('usu_login').getValue(),
            usu_password_encriptada: hex_md5(Ext.getCmp('usu_password').getValue()),
            certificado: certificado
        },
        success: function(response, action){
            obj = Ext.util.JSON.decode(response.responseText);
            if (obj.success) {
                if (obj.mensaje == '1') {
                    window.location = getAbsoluteUrl('interfaz_superadmin', 'index');
                }
                if (obj.mensaje == '2') {
                    window.location = getAbsoluteUrl('interfaz_admin', 'index');
                }
                if (obj.mensaje == '3') {
                    window.location = getAbsoluteUrl('ingreso_datos', 'index');
                }
                if (obj.mensaje == '4') {
                    window.location = getAbsoluteUrl('interfaz_reporte', 'index');
                }
            }
            else {
                if (obj.success == false) {
                    Ext.example.msg('Error', obj.errors.reason);
                }
            }
        },
        failure: function(response){
            var result = response.responseText;
            Ext.example.msg('Error', 'No se pudo conectar a la base de datos intente mas tarde');
        }
    });
}

