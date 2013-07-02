var largo_panel = 400;

Ext.BLANK_IMAGE_URL = urlPrefix + '../css/extjs/resources/images/default/s.gif';
Ext.QuickTips.init();

function ComboRenderer(value, combo){
    var returnValue = value;
    var valueField = combo.valueField;
    
    var idx = combo.store.findBy(function(record){
        if (record.get(valueField) == value) {
            returnValue = record.get(combo.displayField);
            return true;
        }
    });
    
    if (idx < 0) {
        returnValue = '';
    }
    
    return returnValue;
}

function getAbsoluteUrl(module, action){
    return urlPrefix + module + '/' + action;
}

function ayuda(idcomponente, mensaje, titulo){
    if (titulo == null) {
        titulo = 'Ayuda r&aacute;pida';
    }
    if (mensaje != null) {
        new Ext.ToolTip({
            target: (Ext.getCmp(idcomponente)).getEl(),
            title: titulo,
            anchor: 'top',
            //anchorOffset: 85,
            html: mensaje,
            trackMouse: true
        });
    }
}

function mostrarMensajeRapido(titulo, contenido){
    Ext.example.msg(titulo, contenido);
    //Ext.example.msg('as','as');
}

function mostrarMensajeConfirmacion(titulo, contenido){
    if (contenido != '') {
        Ext.Msg.show({
            title: titulo,
            msg: contenido,
            buttons: Ext.Msg.OK,
            icon: Ext.MessageBox.WARNING
        });
    }
    else {
        Ext.Msg.show({
            title: 'Alerta',
            msg: contenido,
            buttons: Ext.Msg.OK,
            icon: Ext.MessageBox.WARNING
        });
    }
}

function subirDatos(panel, url_Action, extraParams, funcionSuccess, funcionFailure){

    panel.getForm().submit({
        method: 'POST',
        timeout: 60000,
        url: url_Action,
        params: extraParams,
        waitTitle: 'Enviando',
        waitMsg: 'Enviando datos...',
        success: function(response, action){
            obj = Ext.util.JSON.decode(action.response.responseText);
            salida = true;
            if (funcionSuccess != null) {
                funcionSuccess();
            }
            if (obj.success == true) {
                mostrarMensajeRapido('Aviso', obj.mensaje);
            }
            else {
                mostrarMensajeConfirmacion('Error', obj.errors.reason);
            }
            
        },
        failure: function(form, action, response){
            if (action.failureType == 'server') {
                obj = Ext.util.JSON.decode(action.response.responseText);
                mostrarMensajeConfirmacion('Error', obj.errors.reason);
            }
            if (funcionFailure != null) {
                funcionFailure();
            }
        }
    });
}

function subirDatosAjax(url_Action, extraParams, funcionSuccess, funcionFailure){
    Ext.Ajax.request({
        method: 'POST',
        timeout: 60000,
        url: url_Action,
        params: extraParams,
        waitTitle: 'Enviando',
        waitMsg: 'Enviando datos...',
        success: function(response){
            obj = Ext.util.JSON.decode(response.responseText);
            salida = true;
            if (funcionSuccess != null) {
                funcionSuccess();
            }
            if (obj.success == true) {
                mostrarMensajeRapido('Aviso', obj.mensaje);
            }
            else {
                mostrarMensajeConfirmacion('Error', obj.errors.reason);
            }
        },
        failure: function(form, response){
            if (action.failureType == 'server') {
                obj = Ext.util.JSON.decode(response.responseText);
                mostrarMensajeConfirmacion('Error', obj.errors.reason);
            }
            funcionFailure();
        }
    });
}

function datastoreRenderer(searchField, returnField, datastore){
    return function(value){
        var index = datastore.find(searchField, value);
        if (index != -1) {
            var record = datastore.getAt(index);
            return record.get(returnField);
        }
        else {
            return '';
        }
    }
}

function cerrarSesion(){
    Ext.MessageBox.confirm('Confirmar', 'Esta seguro que desea salir del sistema?', function(btn, text){
        if (btn == 'yes') {
            subirDatosAjax(getAbsoluteUrl('login', 'desautenticar'), {}, function(){
                window.location = getAbsoluteUrl('login', 'index');
            });
        }
    });
}

//arreglo de temas
var genral_tema_data_store = new Ext.data.ArrayStore({
    fields: ['tema', 'color'],
    data: [['xtheme-blue.css', 'blue'], ['xtheme-silverCherry.css', 'cherry'], ['xtheme-gray.css', 'gray'], ['xtheme-green.css', 'green'], ['xtheme-slate.css', 'slate']]
});

//combo de temas
var genral_tema_combobox = new Ext.form.ComboBox({
    store: genral_tema_data_store,
    displayField: 'color',
    typeAhead: true,
    mode: 'local',
    valueField: 'tema',
    triggerAction: 'all',
    emptyText: 'Seleccione un tema',
    selectOnFocus: true,
    width: 135,
    listeners: {
        scope: this,
        'select': function(){
            cambiaEstilo(genral_tema_combobox.getValue());
        }
    }
});

function cambiaEstilo(nuevoestilo){
    var ruta = urlPrefix + "../css/extjs/resources/css/" + nuevoestilo + "";
    Ext.util.CSS.swapStyleSheet('theme', ruta);
}

function redirigirSiSesionExpiro(callback){
    Ext.Ajax.request({
        method: 'POST',
        url: getAbsoluteUrl('login', 'consultarEstadoSesion'),
        success: function(response){
            if (response.responseText == 'Vencida') {
                alert('No se ha detectado actividad durante 15 minutos. La sesión de usuario ha expirado por motivos de seguridad.');
                window.location = getAbsoluteUrl('login', 'index');
            }
        },
        failure: function(form, response){
        
        }
    });
}

function abrirAcercaDe(){
    window.open(urlWeb + 'ayudas/Acerca_de.html', 'Acerca de...', "toolbar=0,location=1,directories=0,status=1,menubar=0,scrollbars=0,resizable=0,width=640,height=450");
}
