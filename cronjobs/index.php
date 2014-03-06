<?php
//Conexión a la base de datos
$host = 'mysql.hostinger.es';
$usuario = 'u607150602_frank';
$contrasena = 'sistemas';
mysql_connect($host, $usuario, $contrasena) or die('No se pudo conectar: ' . mysql_error());
mysql_select_db('u607150602_proy') or die('No se pudo seleccionar la base de datos');

//Alarmas
$fila = 0;
$datos = array();
//Calcula la fecha actual
$fechaactual = strtotime(date("Y-m-d"));
$diaactual = (int) date('d',$fechaactual);
$mesactual = (int) date('m',$fechaactual);
$anoactual = (int) date('Y',$fechaactual);
                    
//Asigna los días que tiene cada mes
$dias_mes = array();
$dias_mes[1] = 31;
if(($anoactual%4)==0) { $dias_mes[2] = 29; }
else { $dias_mes[2] = 28; }
$dias_mes[3] = 31;
$dias_mes[4] = 30;
$dias_mes[5] = 31;
$dias_mes[6] = 30;
$dias_mes[7] = 31;
$dias_mes[8] = 31;
$dias_mes[9] = 30;
$dias_mes[10] = 31;
$dias_mes[11] = 30;
$dias_mes[12] = 31;

//Alarmas productos
$consultaProd = 'SELECT * FROM producto WHERE prod_eliminado=0 AND prod_est_codigo=1';
$producto = mysql_query($consultaProd) or die('Error consulta productos: ' . mysql_error());
while ($row = mysql_fetch_row($producto)){
    //Calcula la fecha de entrega
    $fechaentrega = strtotime($row[2]);
    $diaentrega = (int) date('d',$fechaentrega);
    $mesentrega = (int) date('m',$fechaentrega);
    $anoentrega = (int) date('Y',$fechaentrega);
	
    //Cuando se ha vencido la fecha de entrega del producto
    if(($anoactual > $anoentrega) || (($anoactual == $anoentrega) && ($mesactual > $mesentrega)) || (($anoactual == $anoentrega) && ($mesactual == $mesentrega) && ($diaactual > $diaentrega))) {
        $datos[$fila]['pro_concepto']='Entrega de Producto';        
        $datos[$fila]['descripcion'] = 'la fecha de entrega del producto "'.$row[1].'" está vencida.  El producto debió ser entregado el '.$diaentrega.' de '.mes($mesentrega).' de '.$anoentrega;
        $datos[$fila]['pro_codigo']=$row[0];
		$fila++;
    }    
	
    //Cuando faltan X días para entregar el producto
    if(($anoactual == $anoentrega) && ($mesactual <= $mesentrega)) {
        $dias_entrega = 0;
        for($i=1; $i<$mesentrega; $i++) {
            $dias_entrega += $dias_mes[$i]; 
        }
        $dias_entrega += $diaentrega;

        $dias_actual = 0;
        for($j=1; $j<$mesactual; $j++) {
            $dias_actual += $dias_mes[$j]; 
        }
        $dias_actual += $diaactual;

        $dias_faltantes = $dias_entrega-$dias_actual;

        if($dias_faltantes == 0) {                                        
            $datos[$fila]['pro_concepto']='Entrega de Producto';
            $datos[$fila]['descripcion'] = 'el producto "'.$row[1].'" debe ser entregado hoy '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega;
            $datos[$fila]['pro_codigo']=$row[0];
            $fila++;
        }
        else {
            if(($dias_faltantes == 10) || ($dias_faltantes == 20)) {
                $datos[$fila]['pro_concepto']='Entrega de Producto';
                $datos[$fila]['descripcion'] = 'quedan '.$dias_faltantes.' días disponibles para entregar el producto "'.$row[1].'".  El producto debe ser entregado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega;
                $datos[$fila]['pro_codigo']=$row[0];
                $fila++;
            }
        }
    }
}


//Alarmas Finalización de Proyecto
$consultaProy = "SELECT * FROM proyecto WHERE pro_eliminado=0 AND pro_est_codigo=1 OR pro_est_codigo=3";
$proyecto = mysql_query($consultaProy) or die('Error consulta proyectos: ' . mysql_error());
while ($row = mysql_fetch_row($proyecto)){
	//Calcula la fecha de entrega
    $fechaentrega = strtotime($row[8]);
    $diaentrega = (int) date('d',$fechaentrega);
    $mesentrega = (int) date('m',$fechaentrega);
    $anoentrega = (int) date('Y',$fechaentrega);
	
	//Cuando se ha retrazado la fecha de entrega del proyecto
	if(($anoactual > $anoentrega) || (($anoactual == $anoentrega) && ($mesactual > $mesentrega)) || (($anoactual == $anoentrega) && ($mesactual == $mesentrega) && ($diaactual > $diaentrega))) {
		$datos[$fila]['pro_concepto'] = 'Finalización de Proyecto';
        $datos[$fila]['descripcion'] = 'la fecha de finalización del proyecto "'.$row[2].'" está vencida.  El proyecto debió ser finalizado el '.$diaentrega.' de '.mes($mesentrega).' de '.$anoentrega;
        $datos[$fila]['pro_codigo'] = $row[0];
		$fila++;
	}
	
	//Cuando faltan X días para entregar el proyecto
    if(($anoactual == $anoentrega) && ($mesactual <= $mesentrega)) {
        $dias_entrega = 0;
        for($i=1; $i<$mesentrega; $i++) {
            $dias_entrega += $dias_mes[$i]; 
        }
        $dias_entrega += $diaentrega;

        $dias_actual = 0;
        for($j=1; $j<$mesactual; $j++) {
            $dias_actual += $dias_mes[$j]; 
        }
        $dias_actual += $diaactual;

        $dias_faltantes = $dias_entrega-$dias_actual;
		
        if($dias_faltantes == 0) {
            $datos[$fila]['pro_concepto'] = 'Finalización de Proyecto';
            $datos[$fila]['descripcion'] = 'el proyecto "'.$row[2].'" debe ser finalizado hoy '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega;
            $datos[$fila]['pro_codigo'] = $row[0];
            $fila++;
        }
        else {
            if(($dias_faltantes == 10) || ($dias_faltantes == 20) || ($dias_faltantes == 30)) {
                $datos[$fila]['pro_concepto'] = 'Finalización de Proyecto';
                $datos[$fila]['descripcion'] = 'quedan '.$dias_faltantes.' días disponibles para finalizar el proyecto "'.$row[2].'".  El proyecto debe ser finalizado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega;
                $datos[$fila]['pro_codigo'] = $row[0];
                $fila++;
            }
        }
    }
}


//Alarmas Presupuesto de Proyecto
$consultaPres = "SELECT * FROM proyecto WHERE pro_eliminado=0 AND pro_est_codigo=1 OR pro_est_codigo=3";
$presupuesto = mysql_query($consultaPres) or die('Error consulta proyectos: ' . mysql_error());
while ($row = mysql_fetch_row($presupuesto)){
	$ingresos = $row[5];
	$disponible = $row[5]-$row[6];
	$porcentaje = round(($disponible*100)/$ingresos);
	if($porcentaje <= 20 && $porcentaje > 0) {
        $datos[$fila]['pro_concepto'] = 'Presupuesto de Proyecto';
        $datos[$fila]['descripcion'] = 'queda menos del 20% del presupuesto disponible del proyecto "'.$row[2].'".  El presupuesto disponible actual del proyecto es de $'.number_format($disponible, 0, ',', '.');
        $datos[$fila]['pro_codigo'] = $row[0];
		$fila++;
	}
	if($porcentaje == 0) {
        $datos[$fila]['pro_concepto'] = 'Presupuesto de Proyecto';
        $datos[$fila]['descripcion'] = 'se ha gastado todo el presupuesto disponible del proyecto "'.$row[2].'".';
        $datos[$fila]['pro_codigo'] = $row[0];
		$fila++;
	}
	if($porcentaje < 0) {
		$datos[$fila]['pro_concepto'] = 'Presupuesto de Proyecto';
        $datos[$fila]['descripcion'] = 'se ha superado en $'.number_format($disponible*(-1), 0, ',', '.').' el valor del presupuesto del proyecto "'.$row[2].'".';
        $datos[$fila]['pro_codigo'] = $row[0];
		$fila++;
	}
}


for($i=0; $i<$fila; $i++) {
    //Registro de alarmas
    $consultaAla1 = "SELECT * FROM alarma WHERE ala_concepto='".$datos[$i]['pro_concepto']."' AND ala_con_codigo='".$datos[$i]['pro_codigo']."' AND ala_descripcion='".$datos[$i]['descripcion']."'";
    mysql_query($consultaAla1) or die('Error consulta alarmas1: ' . mysql_error());    
    $resultado1 = mysql_query("SELECT * FROM alarma WHERE ala_concepto='".$datos[$i]['pro_concepto']."' AND ala_con_codigo='".$datos[$i]['pro_codigo']."' AND ala_descripcion='".$datos[$i]['descripcion']."'");
    $count = mysql_num_rows($resultado1);
    
    $consultaAla2 = "SELECT * FROM alarma WHERE ala_concepto='".$datos[$i]['pro_concepto'].'" AND ala_con_codigo="'.$datos[$i]['pro_codigo']."'";
    $alarma2 = mysql_query($consultaAla2) or die('Error consulta alarmas2: ' . mysql_error());    
    $resultado2 = mysql_query("SELECT * FROM alarma WHERE ala_concepto='".$datos[$i]['pro_concepto']."' AND ala_con_codigo='".$datos[$i]['pro_codigo']."'");
    $count_r = mysql_num_rows($resultado2);
    
    if($count_r == 1) {
        while ($rowAla = mysql_fetch_row($alarma2)){
            if($datos[$i]['descripcion'] != $rowAla[3]) {
                $consultaAla3 = "DELETE FROM TABLE alarma WHERE ala_descripcion='".$datos[$i]['descripcion']."'";
                mysql_query($consultaAla3) or die('Error eliminado alarmas: ' . mysql_error());    
            }            
        }                     
    }

    if($count == 0) {
        $consultaAla4 = "INSERT INTO alarma (ala_concepto, ala_con_codigo, ala_descripcion, ala_enviado)
            VALUES('".$datos[$i]['pro_concepto']."', '".$datos[$i]['pro_codigo']."', '".$datos[$i]['descripcion']."', 0)";
        mysql_query($consultaAla4) or die('Error registro alarmas: ' . mysql_error());
    }                
}
enviarCorreoElectronico();

function enviarCorreoElectronico() {
    $consulta1 = "SELECT * FROM alarma WHERE ala_enviado=0";
    $alarmas = mysql_query($consulta1) or die('Error consulta alarmas correo: ' . mysql_error()); 
    while ($row = mysql_fetch_row($alarmas)){
        if($row[1] == 'Entrega de Producto') {
            $consulta2 = "SELECT persona.pers_correo, persona.pers_nombres, persona.pers_apellidos
                FROM persona,proyecto,producto 
                WHERE persona.pers_codigo=proyecto.pro_pers_codigo AND
                proyecto.pro_codigo=producto.prod_pro_codigo AND 
                producto.prod_codigo='".$row[2]."'";
            $resultado = mysql_query($consulta2) or die('Error consulta alarmas producto: ' . mysql_error()); 
            while ($rowProd = mysql_fetch_row($resultado)){
                $correo_destino = $rowProd[0];
                $mensaje = '<html>'.$rowProd[1].' '.$rowProd[2].',<br/><br/>';
                $mensaje .= 'Se le informa que '.$row[3].'.<br/><br/><br/>';
                $mensaje .= 'Atentamente,<br/><br/>';
                $mensaje .= 'Cinara<br/><br/><html>';
				$consulta3 = "UPDATE alarma SET ala_enviado=1 WHERE ala_codigo='".$row[0]."'";
				$modificar = mysql_query($consulta3) or die('Error consulta modificar estado alarma: ' . mysql_error()); 
                enviarCorreo($correo_destino, $mensaje);   
            }
        }
		if($row[1] == 'Finalización de Proyecto' || $row[1] == 'Presupuesto de Proyecto') {
            $consulta2 = "SELECT persona.pers_correo, persona.pers_nombres, persona.pers_apellidos
                FROM persona,proyecto
                WHERE persona.pers_codigo=proyecto.pro_pers_codigo AND
                proyecto.pro_codigo='".$row[2]."'";
            $resultado = mysql_query($consulta2) or die('Error consulta alarmas proyecto: ' . mysql_error()); 
            while ($rowProy = mysql_fetch_row($resultado)){
                $correo_destino = $rowProy[0];
                $mensaje = '<html>'.$rowProy[1].' '.$rowProy[2].',<br/><br/>';
                $mensaje .= 'Se le informa que '.$row[3].'.<br/><br/><br/>';
                $mensaje .= 'Atentamente,<br/><br/>';
                $mensaje .= 'Cinara<br/><br/><html>';
				$consulta3 = "UPDATE alarma SET ala_enviado=1 WHERE ala_codigo='".$row[0]."'";
				$modificar = mysql_query($consulta3) or die('Error consulta modificar estado alarma: ' . mysql_error()); 
                enviarCorreo($correo_destino, $mensaje);   
            }
        }
    }
}

function enviarCorreo($correo_destino, $mensaje) {        
        $para = $correo_destino;
        $asunto = "Alarma Seguimiento a Proyectos";
        $encabezado = "MIME-Version: 1.0" . "\r\n";
        $encabezado .= "Content-type:text/html; " . "\r\n";
        $encabezado .= "From: cinarauv@correounivalle.edu.co";
        mail($para, $asunto, $mensaje, $encabezado);
}

function mes($num_mes) {
    if($num_mes == 1) return 'enero';
    if($num_mes == 2) return 'febrero';
    if($num_mes == 3) return 'marzo';
    if($num_mes == 4) return 'abril';
    if($num_mes == 5) return 'mayo';
    if($num_mes == 6) return 'junio';
    if($num_mes == 7) return 'julio';
    if($num_mes == 8) return 'agosto';
    if($num_mes == 9) return 'septiembre';
    if($num_mes == 10) return 'octubre';
    if($num_mes == 11) return 'noviembre';
    if($num_mes == 12) return 'diciembre';
}
?>