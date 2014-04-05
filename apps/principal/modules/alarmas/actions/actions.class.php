<?php
/**
 * alarmas actions.
 *
 * @package    segaproyectos
 * @subpackage alarmas
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class alarmasActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
//    $this->forward('default', 'module');      
  }
  
    public function executeListarAlarma(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos = array();

            try{
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
                    
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');
                    
                    //Entrega de Producto
                    if(($request->getParameter('codigo_tipo')=='1') || ($request->getParameter('codigo_tipo')=='-1')) {
                        $conexionprod = new Criteria();
                        $conexionprod->add(ProductoPeer::PROD_ELIMINADO, 0);
                        $conexionprod->add(ProductoPeer::PROD_EST_CODIGO, 1);
                        $producto = ProductoPeer::doSelect($conexionprod);

                        if($start!=''){
                                $conexionprod->setOffset($start);
                                $conexionprod->setLimit($limit);
                        }                     

                        foreach($producto as $temporalprod)
                        {          
                                //Calcula la fecha de entrega
                                $fechaentrega = strtotime($temporalprod->getProdFechaEntrega());
                                $diaentrega = (int) date('d',$fechaentrega);
                                $mesentrega = (int) date('m',$fechaentrega);
                                $anoentrega = (int) date('Y',$fechaentrega);

                                //Cuando se ha vencido la fecha de entrega del producto
                                if(($anoactual > $anoentrega) || (($anoactual == $anoentrega) && ($mesactual > $mesentrega)) || (($anoactual == $anoentrega) && ($mesactual == $mesentrega) && ($diaactual > $diaentrega))) {                                                                    
                                    $datos[$fila]['pro_concepto']='Entrega de Producto';
                                    $datos[$fila]['pro_concepto_s']='<b>Entrega de Producto</b>';
                                    $datos[$fila]['alarma'] = '<a style="color:#000000;"><b>La fecha de entrega del producto "'.$temporalprod->getProdNombre().'" está vencida.  El producto debió ser entregado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega.'.</b></a>';
                                    $datos[$fila]['descripcion'] = 'la fecha de entrega del producto "'.$temporalprod->getProdNombre().'" está vencida.  El producto debió ser entregado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega;
                                    $datos[$fila]['pro_codigo']=$temporalprod->getProdCodigo();
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
                                        $datos[$fila]['pro_concepto_s']='<b>Entrega de Producto</b>';
                                        $datos[$fila]['alarma'] = '<a style="color:#FF0000;"><b>El producto "'.$temporalprod->getProdNombre().'" debe ser entregado hoy '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega.'.</b></a>';
                                        $datos[$fila]['descripcion'] = 'el producto "'.$temporalprod->getProdNombre().'" debe ser entregado hoy '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega;
                                        $datos[$fila]['pro_codigo']=$temporalprod->getProdCodigo();
                                        $fila++;
                                    }
                                    else {
                                        if($dias_faltantes >= 1 && $dias_faltantes <= 20) {
                                            $datos[$fila]['pro_concepto']='Entrega de Producto';
                                            $datos[$fila]['pro_concepto_s']='<b>Entrega de Producto</b>';
                                            $datos[$fila]['alarma'] = '<a style="color:#FF0000;"><b>Queda(n) '.$dias_faltantes.' día(s) disponible(s) para entregar el producto "'.$temporalprod->getProdNombre().'".  El producto debe ser entregado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega.'.</b></a>';
                                            $datos[$fila]['descripcion'] = 'queda(n) '.$dias_faltantes.' día(s) disponible(s) para entregar el producto "'.$temporalprod->getProdNombre().'".  El producto debe ser entregado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega;
                                            $datos[$fila]['pro_codigo']=$temporalprod->getProdCodigo();
                                            $fila++;
                                        }
                                    }
                                }
                          }
                    }
                    
                    //Finalización de Proyecto
                    if(($request->getParameter('codigo_tipo')=='2') || ($request->getParameter('codigo_tipo')=='-1')) {
                        $conexionproy = new Criteria();
                        $conexionproy->add(ProyectoPeer::PRO_ELIMINADO, 0);
                        $conexionproy->addOr(ProyectoPeer::PRO_EST_CODIGO, 1);
                        $conexionproy->addOr(ProyectoPeer::PRO_EST_CODIGO, 3);
                        $proyecto = ProyectoPeer::doSelect($conexionproy);
                        
                        if($start!=''){
                                $conexionproy->setOffset($start);
                                $conexionproy->setLimit($limit);
                        }  

                        foreach($proyecto as $temporalproy)
                        {          
                                //Calcula la fecha de entrega
                                $fechaentrega = strtotime($temporalproy->getProFechaFin());
                                $diaentrega = (int) date('d',$fechaentrega);
                                $mesentrega = (int) date('m',$fechaentrega);
                                $anoentrega = (int) date('Y',$fechaentrega);

                                //Cuando se ha retrazado la fecha de entrega del proyecto
                                if(($anoactual > $anoentrega) || (($anoactual == $anoentrega) && ($mesactual > $mesentrega)) || (($anoactual == $anoentrega) && ($mesactual == $mesentrega) && ($diaactual > $diaentrega))) {                                    
                                    $datos[$fila]['pro_concepto']='Finalización de Proyecto';
                                    $datos[$fila]['pro_concepto_s']='<b>Finalización de Proyecto</b>';
                                    $datos[$fila]['alarma'] = '<a style="color:#000000;"><b>La fecha de finalización del proyecto "'.$temporalproy->getProNombre().'" está vencida.  El proyecto debió ser finalizado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega.'.</b></a>';
                                    $datos[$fila]['descripcion'] = 'la fecha de finalización del proyecto "'.$temporalproy->getProNombre().'" está vencida.  El proyecto debió ser finalizado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega;
                                    $datos[$fila]['pro_codigo']=$temporalproy->getProCodigo();
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
                                        $datos[$fila]['pro_concepto']='Finalización de Proyecto';
                                        $datos[$fila]['pro_concepto_s']='<b>Finalización de Proyecto</b>';
                                        $datos[$fila]['alarma'] = '<a style="color:#FF0000;"><b>El proyecto "'.$temporalproy->getProNombre().'" debe ser finalizado hoy '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega.'.</b></a>';
                                        $datos[$fila]['descripcion'] = 'el proyecto "'.$temporalproy->getProNombre().'" debe ser finalizado hoy '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega;
                                        $datos[$fila]['pro_codigo']=$temporalproy->getProCodigo();
                                        $fila++;
                                    }
                                    else {                                        
                                        if($dias_faltantes >= 1 && $dias_faltantes <= 30) {  
                                            $datos[$fila]['pro_concepto']='Finalización de Proyecto';
                                            $datos[$fila]['pro_concepto_s']='<b>Finalización de Proyecto</b>';
                                            $datos[$fila]['alarma'] = '<a style="color:#FF0000;"><b>Queda(n) '.$dias_faltantes.' día(s) disponible(s) para finalizar el proyecto "'.$temporalproy->getProNombre().'".  El proyecto debe ser finalizado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega.'.</b></a>';
                                            $datos[$fila]['descripcion'] = 'queda(n) '.$dias_faltantes.' día(s) disponible(s) para finalizar el proyecto "'.$temporalproy->getProNombre().'".  El proyecto debe ser finalizado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega;
                                            $datos[$fila]['pro_codigo']=$temporalproy->getProCodigo();
                                            $fila++;
                                        }                                     
                                    }
                                }
                        }
                    }
                    
                    //Presupuesto de Proyecto
                    if(($request->getParameter('codigo_tipo')=='3') || ($request->getParameter('codigo_tipo')=='-1')) {
                        $conexionproy = new Criteria();
                        $conexionproy->add(ProyectoPeer::PRO_ELIMINADO, 0);
                        $conexionproy->addOr(ProyectoPeer::PRO_EST_CODIGO, 1);
                        $conexionproy->addOr(ProyectoPeer::PRO_EST_CODIGO, 3);
                        $proyecto = ProyectoPeer::doSelect($conexionproy);
                        
                        if($start!=''){
                                $conexionproy->setOffset($start);
                                $conexionproy->setLimit($limit);
                        }  

                        foreach($proyecto as $temporalproy)
                        {
                            $ingresos = $temporalproy->getProAcumuladoIngresos();
                            if($ingresos != 0 && $ingresos != '') {
                                    $disponible = $temporalproy->getProAcumuladoIngresos()-$temporalproy->getProAcumuladoEgresos();
                                    $porcentaje = (($disponible*100)/$ingresos);
                                    if($porcentaje <= 20 && $porcentaje > 0) {
                                            $datos[$fila]['pro_concepto']='Presupuesto de Proyecto';
                                            $datos[$fila]['pro_concepto_s']='<b>Presupuesto de Proyecto</b>';
                                            $datos[$fila]['alarma'] = '<a style="color:#FF0000;"><b>Queda menos del 20% del presupuesto disponible del proyecto "'.$temporalproy->getProNombre().'".  El presupuesto disponible actual del proyecto es de $'.number_format($disponible, 0, ',', '.').'.</b></a>';
                                            $datos[$fila]['descripcion'] = 'queda menos del 20% del presupuesto disponible del proyecto "'.$temporalproy->getProNombre().'".  El presupuesto disponible actual del proyecto es de $'.number_format($disponible, 0, ',', '.');
                                            $datos[$fila]['pro_codigo']=$temporalproy->getProCodigo();
                                            $fila++;
                                    }
                                    if($porcentaje == 0 && $porcentaje!='') {
                                            $datos[$fila]['pro_concepto']='Presupuesto de Proyecto';
                                            $datos[$fila]['pro_concepto_s']='<b>Presupuesto de Proyecto</b>';
                                            $datos[$fila]['alarma'] = '<a style="color:#FF0000;"><b>Se ha gastado todo el presupuesto disponible del proyecto "'.$temporalproy->getProNombre().'".</b></a>';
                                            $datos[$fila]['descripcion'] = 'se ha gastado todo el presupuesto disponible del proyecto "'.$temporalproy->getProNombre().'"';
                                            $datos[$fila]['pro_codigo']=$temporalproy->getProCodigo();
                                            $fila++;
                                    }
                                    if($porcentaje < 0) {
                                            $datos[$fila]['pro_concepto']='Presupuesto de Proyecto';
                                            $datos[$fila]['pro_concepto_s']='<b>Presupuesto de Proyecto</b>';
                                            $datos[$fila]['alarma'] = '<a style="color:#000000;"><b>Se ha superado en $'.number_format($disponible*(-1), 0, ',', '.').' el valor del presupuesto del proyecto "'.$temporalproy->getProNombre().'".</b></a>';
                                            $datos[$fila]['descripcion'] = 'se ha superado en $'.number_format($disponible*(-1), 0, ',', '.').' el valor del presupuesto del proyecto "'.$temporalproy->getProNombre().'"';
                                            $datos[$fila]['pro_codigo']=$temporalproy->getProCodigo();
                                            $fila++;
                                    }
                            }
                        }
                    }
                    
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en alarma ',error:".$excepcion->getMessage()."'}})";
            }
			
            for($i=0; $i<$fila; $i++) {
                $criteria = new Criteria();
                $criteria->add(AlarmaPeer::ALA_CONCEPTO, $datos[$i]['pro_concepto']);
                $criteria->add(AlarmaPeer::ALA_CON_CODIGO, $datos[$i]['pro_codigo']);
                $criteria->add(AlarmaPeer::ALA_DESCRIPCION, $datos[$i]['descripcion']);                
                $count = AlarmaPeer::doCount($criteria);
                
                $conexion = new Criteria();
                $conexion->add(AlarmaPeer::ALA_CONCEPTO, $datos[$i]['pro_concepto']);
                $conexion->add(AlarmaPeer::ALA_CON_CODIGO, $datos[$i]['pro_codigo']);
                $registro = AlarmaPeer::doSelectOne($conexion);
                $count_r = AlarmaPeer::doCount($conexion);
                if($count_r == 1) {
                    if($datos[$i]['descripcion'] != ($registro->getAlaDescripcion())) {
                        $registro->delete();
                    }                        
                }
                
                if($count == 0) {
                    $alarma = new Alarma();
                    $alarma->setAlaConcepto($datos[$i]['pro_concepto']);
                    $alarma->setAlaConCodigo($datos[$i]['pro_codigo']);
                    $alarma->setAlaDescripcion($datos[$i]['descripcion']);
                    $alarma->setAlaEnviado(0);
                    $alarma->save();
                }                
            }
			
            return $this->renderText($salida);
    }
    
    public function executeListarTipoAlarma() 
    {
            $result = array();
            $data = array();

            $tipos = array('Entrega de Producto', 'Finalización de Proyecto', 'Presupuesto de Proyecto');

            for($i=0;$i<3;$i++) {
                $fields = array();

                $fields['codigo'] = ($i+1);
                $fields['nombre'] = $tipos[$i];

                $data[] = $fields;
            }

            $result['data'] = $data;
            return $this->renderText(json_encode($result));
    }

    public function executeEnviarCorreoElectronico(sfWebRequest $request)
    {
        include("/var/www/segaproyectos/lib/phpmailer/class.phpmailer.php");
        include("/var/www/segaproyectos/lib/phpmailer/class.smtp.php");
        
        $criteria = new Criteria();
        $alarmas = AlarmaPeer::doSelect($criteria);
        
        foreach ($alarmas as $alarma) {
            if($alarma->getAlaConcepto() == 'Entrega de Producto') {
                $producto = ProductoPeer::retrieveByPK($alarma->getAlaConCodigo());
                $proyecto = ProyectoPeer::retrieveByPK($producto->getProdProCodigo());
                $persona = PersonaPeer::retrieveByPK($proyecto->getProPersCodigo());
                $correo_destino = $persona->getPersCorreo();
                $mensaje = $persona->getPersNombres().' '.$persona->getPersApellidos().',<br/><br/>';
                $mensaje .= 'Se le informa que '.$alarma->getAlaDescripcion().'.<br/><br/><br/>';
                $mensaje .= 'Atentamente,<br/><br/>';
                $mensaje .= 'Cinara';
                $enviar_correo = $this->enviarCorreo($correo_destino, $mensaje);                
            }
            if(($alarma->getAlaConcepto()=='Finalización de Proyecto') || ($alarma->getAlaConcepto()=='Presupuesto de Proyecto')) {
                $proyecto = ProyectoPeer::retrieveByPK($alarma->getAlaConCodigo());
                $persona = PersonaPeer::retrieveByPK($proyecto->getProPersCodigo());
                $correo_destino = $persona->getPersCorreo();
                $mensaje = $persona->getPersNombres().' '.$persona->getPersApellidos().',<br/><br/>';
                $mensaje .= 'Se le informa que '.$alarma->getAlaDescripcion().'.<br/><br/><br/>';
                $mensaje .= 'Atentamente,<br/><br/>';
                $mensaje .= 'Cinara';
                $enviar_correo = $this->enviarCorreo($correo_destino, $mensaje);                
            }
        }
        return $this -> renderText($enviar_correo);
    }
    
    function enviarCorreo($correo_destino, $mensaje) {        
            $correo = $correo_destino;
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 465;
            $mail->Mailer = 'smtp';
            $mail->Username = "franco.cundar@correounivalle.edu.co";
            $mail->Password = "francocz";
            $mail->From = "franco.cundar@correounivalle.edu.co";
            $mail->FromName = "Cinara";
            $mail->Subject = "Alarma Seguimiento a Proyectos";
            $mail->MsgHTML($mensaje);
            $mail->AddAddress($correo, "Destinatario");
            $mail->IsHTML(true); 
            if(!$mail->Send())
                return $mail->ErrorInfo;
            else
                return 'Ok';
    }
    
    public function mes($num_mes) {
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
}