Ext.onReady(function(){
    var boton_certificar = new Ext.Button({
        text: '<div style="width: 150px; height: 50px;"></div><div style="font-size: large;"><b>Generar<br>certificado</b></div>',
        iconCls: 'certificar',
        iconAlign: 'top',
        tooltip: 'Haga click para certificar este computador',
        disabled: true,
        handler: function(){
            Ext.Msg.prompt('Datos de computador ', 'Digite un nombre para este computador', function(idButton, text){
                if (idButton == 'ok') {
                    if (window.google && google.gears) {
                        var db = null;
                        try {
                            db = google.gears.factory.create('beta.database');
                            if (db) {
                                db.open('certificados-db');
                                db.execute('create table if not exists certificado (contenido varchar(40))');
                                db.execute('delete from certificado');
                                db.close();
                                Ext.Ajax.request({
                                    url: getAbsoluteUrl('certificacion', 'generarCertificado'),
                                    params: {
                                        'nombre_computador': text
                                    },
                                    failure: function(){
                                        alert('No ha sido posible generar un certificado');
                                    },
                                    success: function(result){
                                        if (result.responseText != '') {
                                            try {
                                                db = google.gears.factory.create('beta.database');
                                                if (db) {
                                                    db.open('certificados-db');
                                                    db.execute('insert into certificado values (?)', [result.responseText]);
                                                    db.close();
                                                    alert('Se ha generado un certificado para este computador');
                                                }
                                            } 
                                            catch (ex) {
                                            
                                            }
                                        }
                                        else {
                                            alert('No ha sido posible generar un certificado');
                                        }
                                        actualizarEstadoBotones();
                                    }
                                });
                            }
                            db.close();
                        } 
                        catch (ex) {
                            db.close();
                            alert('No ha sido posible generar un certificado: ' + ex.message);
                        }
                    }
                }
            });
        }
    });
    
    var boton_eliminar_certificado = new Ext.Button({
        text: '<div style="width: 150px; height: 50px;"></div><div style="font-size: large;"><b>Eliminar<br>certificado</b></div>',
        iconCls: 'eliminar_certificado',
        iconAlign: 'top',
        height: 100,
        width: 120,
        tooltip: 'Haga click para eliminar el certificado de este computador',
        disabled: true,
        handler: function(){
            if (window.google && google.gears) {
                var db = null;
                try {
                    db = google.gears.factory.create('beta.database');
                    if (db) {
                        var certificado = '';
                        db.open('certificados-db');
                        db.execute('create table if not exists certificado (contenido varchar(40))');
                        resultados = db.execute('select * from certificado');
                        if (resultados.isValidRow()) {
                            certificado = resultados.field(0);
                        }
                        resultados.close();
                        db.close();
                        Ext.Ajax.request({
                            url: getAbsoluteUrl('certificacion', 'eliminarCertificado'),
                            params: {
                                certificado: certificado
                            },
                            failure: function(){
                                alert('No ha sido posible eliminar el certificado');
                            },
                            success: function(result){
                                if (result.responseText != '') {
                                    try {
                                        db = google.gears.factory.create('beta.database');
                                        if (db) {
                                            db.open('certificados-db');
                                            db.execute('delete from certificado');
                                            db.close();
                                            alert('El certificado para este computador ha sido eliminado');
                                        }
                                    } 
                                    catch (ex) {
                                    
                                    }
                                }
                                actualizarEstadoBotones();
                            }
                        });
                    }
                    db.close();
                } 
                catch (ex) {
                    db.close();
                    alert('No ha sido posible eliminar el certificado: ' + ex.message);
                }
            }
        }
    });
    
    var formulario = new Ext.form.FormPanel({
        renderTo: 'formulario',
        border: false,
        width: 400,
        frame: false,
        layout: 'form',
        height: 500,
        items: [{
            layout: 'column',
            padding: 10,
            border: false,
            height: 500,
            items: [{
                padding: 10,
                border: false,
                items: [boton_certificar]
            }, {
                padding: 10,
                border: false,
                items: [boton_eliminar_certificado]
            }]
        }]
    });
    
    var actualizarEstadoBotones = function(){
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
        
        if (certificado == '') {
            boton_certificar.enable();
            boton_eliminar_certificado.disable();
        }
        else {
            boton_certificar.disable();
            boton_eliminar_certificado.enable();
        }
    }
    
    actualizarEstadoBotones();
});
