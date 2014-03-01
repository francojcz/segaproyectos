<?php

/**
 * egresos actions.
 *
 * @package    segaproyectos
 * @subpackage egresos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class egresosActions extends sfActions
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
  
    public function executeActualizarEgreso(sfWebRequest $request)
    {
            try{
                    $egr_codigo = $this->getRequestParameter('egr_codigo');
                    $egreso;

                    if($egr_codigo!=''){
                            $egreso  = EgresoPeer::retrieveByPk($egr_codigo);
                    }
                    else
                    {
                            $egreso = new Egreso();
                            $egreso->setEgrFechaRegistro(time());
                            $egreso->setEgrEliminado(0); 
                            $egreso->setEgrUsuCodigo($this->getUser()->getAttribute('usu_codigo'));
                            $egreso->setEgrValor($this->getRequestParameter('egr_valor'));
                            
                            //Actualizar el valor de los acumulados
                            $proyecto = ProyectoPeer::retrieveByPK($this->getRequestParameter('egr_proyecto'));
                            $egresos = $proyecto->getProAcumuladoEgresos();
                            $proyecto->setProAcumuladoEgresos($egresos+$this->getRequestParameter('egr_valor'));
                            $proyecto->save();
                            
                            //Eliminar alarma proyecto
                            $criteria = new Criteria();
                            $criteria->add(AlarmaPeer::ALA_CONCEPTO, 'Presupuesto de Proyecto');
                            $criteria->add(AlarmaPeer::ALA_CON_CODIGO, $this->getRequestParameter('egr_proyecto'));
                            $registro = AlarmaPeer::doSelectOne($criteria);
                            $count_r = AlarmaPeer::doCount($criteria);
                            if($count_r == 1) {
                                $registro->delete();
                            }
                    }

                    if($egreso)
                    {
                            $egreso->setEgrConCodigo($this->getRequestParameter('egr_concepto'));                            
                            $egreso->setEgrFecha($this->getRequestParameter('egr_fecha'));
                            $egreso->setEgrProCodigo($this->getRequestParameter('egr_proyecto'));
                            $egreso->save();
                            
                            $salida = "({success: true, mensaje:'El egreso fue registrado exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en egreso',error:'".$excepcion."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeListarEgreso(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');
                    $egr_eliminado=$this->getRequestParameter('egr_eliminado');//los de mostrar
                    if($egr_eliminado==''){
                            $egr_eliminado=0;
                    }

                    $conexion = new Criteria();
                    $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
                    if($codigo_usuario != 1) {
                        $criteria = new Criteria();
                        $criteria->add(ProyectoPeer:: PRO_PERS_CODIGO, $codigo_usuario);
                        $proyectos = ProyectoPeer::doSelect($criteria);
                        foreach ($proyectos as $proyecto) {
                            $conexion->addOr(EgresoPeer::EGR_PRO_CODIGO, $proyecto->getProCodigo());
                        }
                    } 
                    $conexion->add(EgresoPeer::EGR_ELIMINADO, $egr_eliminado);
                    $egreso_cantidad = EgresoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conexion->addAscendingOrderByColumn(EgresoPeer::EGR_CODIGO);
                    $egresos = EgresoPeer::doSelect($conexion);

                    foreach($egresos as $temporal)
                    {
                            $datos[$fila]['egr_codigo']=$temporal->getEgrCodigo();
                            $datos[$fila]['egr_valor']=$temporal->getEgrValor();
                            $datos[$fila]['egr_fecha']=$temporal->getEgrFecha();
                            $datos[$fila]['egr_fecha_registro'] = $temporal->getEgrFechaRegistro();                          
                            $datos[$fila]['egr_eliminado'] = $temporal->getEgrEliminado();
                                  
                            $datos[$fila]['egr_usuario'] = $temporal->getEgrUsuCodigo();
                            $persona = PersonaPeer::retrieveByPK($temporal->getEgrUsuCodigo());
                            $datos[$fila]['egr_usuario_nombre'] = $persona->getPersNombres().' '.$persona->getPersApellidos();                            
                                                        
                            $datos[$fila]['egr_proyecto'] = $temporal->getEgrProCodigo();
                            $proyecto = ProyectoPeer::retrieveByPK($temporal->getEgrProCodigo());
                            $datos[$fila]['egr_proyecto_nombre'] = $proyecto->getProNombre();
                            $datos[$fila]['egr_acumulado_ingresos'] = $proyecto->getProAcumuladoIngresos();
                            $datos[$fila]['egr_acumulado_egresos'] = $proyecto->getProAcumuladoEgresos();
                            $datos[$fila]['egr_disponible'] = $proyecto->getProAcumuladoIngresos()-$proyecto->getProAcumuladoEgresos();
                            
                            $datos[$fila]['egr_concepto'] = $temporal->getEgrConCodigo();
                            $concepto = ConceptoPeer::retrieveByPK($temporal->getEgrConCodigo());
                            $datos[$fila]['egr_concepto_nombre'] = $concepto->getConNombre();

                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$egreso_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en egreso ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeEliminarEgreso(sfWebRequest $request)
    {
            $salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n egreso'}})";

            try{
                    $egr_codigo = $this->getRequestParameter('egr_codigo');

                    if($egr_codigo!=''){                          
                            $egreso = EgresoPeer::retrieveByPk($egr_codigo);
                            $egreso->setEgrEliminado(1);
                            $egreso->save();
                            $salida = "({success: true, mensaje:'El egreso fue eliminado exitosamente'})";                             
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en egreso al tratar de eliminar ',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeRestablecerEgreso()
    {
            $salida = "({success: false, errors: { reason: 'No se pudo eliminar el egreso'}})";
            try{
                    $egr_codigo = $this->getRequestParameter('egr_codigo');
                    $egreso = EgresoPeer::retrieveByPk($egr_codigo);

                    if($egreso)
                    {                            
                            $egreso->setEgrEliminado(0);
                            $egreso->save();
                            $salida = "({success: true, mensaje:'El egreso fue restablecido exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
            }
            return $this->renderText($salida);
    }
    
    
    public function executeListarProyecto(sfWebRequest $request )
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $conexion = new Criteria();
                    $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
                    if($codigo_usuario != 1) {
                        $conexion->add(ProyectoPeer::PRO_PERS_CODIGO, $codigo_usuario);
                    }
                    $conexion->add(ProyectoPeer::PRO_ELIMINADO, 0);	
                    $proyectos = ProyectoPeer::doSelect($conexion);

                    foreach($proyectos as $temporal)
                    {
                            $datos[$fila]['pro_egr_codigo'] = $temporal->getProCodigo();
                            $datos[$fila]['pro_egr_nombre'] = $temporal->getProNombre();

                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n al listar Proyectos',error:'".$excepcion->getMessage()."'}})";
            }
            return $this->renderText($salida);
    } 
    
    public function executeListarConcepto(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{

                    $conexion = new Criteria();
                    $concepto = ConceptoPeer::doSelect($conexion);

                    foreach($concepto As $temporal)
                    {
                            $datos[$fila]['con_egr_codigo'] = $temporal->getConCodigo();
                            $datos[$fila]['con_egr_nombre'] = $temporal->getConNombre();
                            $fila++;
                    }

                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
                    }
            }catch (Exception $excepcion)
            {
                    $salida='Excepcion en listar Conceptos';
            }
            return $this->renderText($salida);
    }
}
