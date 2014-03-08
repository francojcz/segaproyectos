<?php

/**
 * alarmas_coord actions.
 *
 * @package    segaproyectos
 * @subpackage alarmas_coord
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class alarmas_coordActions extends sfActions
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
                                    $datos[$fila]['pro_concepto_s']='<b>Entrega de Producto</b>';
                                    $datos[$fila]['alarma'] = '<a style="color:#000000;"><b>La fecha de entrega del producto "'.$temporalprod->getProdNombre().'" está vencida.  El producto debió ser entregado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega.'.</b></a>';
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
                                        $datos[$fila]['pro_concepto_s']='<b>Entrega de Producto</b>';
                                        $datos[$fila]['alarma'] = '<a style="color:#FF0000;"><b>El producto "'.$temporalprod->getProdNombre().'" debe ser entregado hoy '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega.'.</b></a>';
                                        $fila++;
                                    }
                                    else {
                                        if($dias_faltantes >= 1 && $dias_faltantes <= 20) {       
                                            $datos[$fila]['pro_concepto_s']='<b>Entrega de Producto</b>';
                                            $datos[$fila]['alarma'] = '<a style="color:#FF0000;"><b>Quedan '.$dias_faltantes.' días disponibles para entregar el producto "'.$temporalprod->getProdNombre().'".  El producto debe ser entregado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega.'.</b></a>';
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
                                    $datos[$fila]['pro_concepto_s']='<b>Finalización de Proyecto</b>';
                                    $datos[$fila]['alarma'] = '<a style="color:#000000;"><b>La fecha de finalización del proyecto "'.$temporalproy->getProNombre().'" está vencida.  El proyecto debió ser finalizado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega.'.</b></a>';
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
                                        $datos[$fila]['pro_concepto_s']='<b>Finalización de Proyecto</b>';
                                        $datos[$fila]['alarma'] = '<a style="color:#FF0000;"><b>El proyecto "'.$temporalproy->getProNombre().'" debe ser finalizado hoy '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega.'.</b></a>';
                                        $fila++;
                                    }
                                    else {
                                        if($dias_faltantes >= 1 && $dias_faltantes <= 30) {                  
                                            $datos[$fila]['pro_concepto_s']='<b>Finalización de Proyecto</b>';
                                            $datos[$fila]['alarma'] = '<a style="color:#FF0000;"><b>Quedan '.$dias_faltantes.' disponibles para finalizar el proyecto "'.$temporalproy->getProNombre().'".  El proyecto debe ser finalizado el '.$diaentrega.' de '.$this->mes($mesentrega).' de '.$anoentrega.'.</b></a>';
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
                            $disponible = $temporalproy->getProAcumuladoIngresos()-$temporalproy->getProAcumuladoEgresos();
                            $porcentaje = round(($disponible*100)/$ingresos);
                            if($porcentaje <= 20 && $porcentaje > 0) {
                                $datos[$fila]['pro_concepto_s']='<b>Presupuesto de Proyecto</b>';
                                $datos[$fila]['alarma'] = '<a style="color:#FF0000;"><b>Queda menos del 20% del presupuesto disponible del proyecto "'.$temporalproy->getProNombre().'".  El presupuesto disponible actual del proyecto es de $'.number_format($disponible, 0, ',', '.').'.</b></a>';
                                $fila++;
                            }
                            if($porcentaje == 0 && $porcentaje!='') {
                                $datos[$fila]['pro_concepto_s']='<b>Presupuesto de Proyecto</b>';
                                $datos[$fila]['alarma'] = '<a style="color:#FF0000;"><b>Se ha gastado todo el presupuesto disponible del proyecto "'.$temporalproy->getProNombre().'".</b></a>';
                                $fila++;
                            }
                            if($porcentaje < 0) {
                                $datos[$fila]['pro_concepto_s']='<b>Presupuesto de Proyecto</b>';
                                $datos[$fila]['alarma'] = '<a style="color:#000000;"><b>Se ha superado en $'.number_format($disponible*(-1), 0, ',', '.').' el valor del presupuesto del proyecto "'.$temporalproy->getProNombre().'".</b></a>';
                                $fila++;
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
