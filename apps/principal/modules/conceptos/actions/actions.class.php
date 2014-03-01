<?php

/**
 * conceptos actions.
 *
 * @package    segaproyectos
 * @subpackage conceptos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class conceptosActions extends sfActions
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
  
    public function executeActualizarConcepto(sfWebRequest $request)
    {
            $salida = '';

            try{
                    $con_codigo = $this->getRequestParameter('con_codigo');
                    $concepto;

                    if($con_codigo!=''){
                            $concepto  = ConceptoPeer::retrieveByPk($con_codigo);

                    }
                    else
                    {
                            $concepto = new Concepto();
                            $concepto->setConFechaRegistro(time());
                            $concepto->setConUsuCodigo($this->getUser()->getAttribute('usu_codigo'));
                    }

                    if($concepto)
                    {
                            $concepto->setConNombre($this->getRequestParameter('con_nombre'));
                            $concepto->save();

                            $salida = "({success: true, mensaje:'La informaci&oacute;n del concepto fue registrada exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en concepto',error:'".$excepcion->getMessage()."'}})";
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
                    $cantidad_concepto = ConceptoPeer::doCount($conexion);
                    $conexion->setOffset($this->getRequestParameter('start'));
                    $conexion->setLimit($this->getRequestParameter('limit'));
                    $concepto = ConceptoPeer::doSelect($conexion);

                    foreach($concepto as $temporal)
                    {
                            $datos[$fila]['con_codigo']=$temporal->getConCodigo();
                            $datos[$fila]['con_nombre'] = $temporal->getConNombre();
                            $datos[$fila]['con_fecha_registro'] = $temporal->getConFechaRegistro();
                            $datos[$fila]['con_usu_codigo'] = $temporal->getConUsuCodigo();
                            $persona = PersonaPeer::retrieveByPK($temporal->getConUsuCodigo());
                            $datos[$fila]['con_usu_nombre'] = $persona->getPersNombres().' '.$persona->getPersApellidos();
                            
                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$cantidad_concepto.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en concepto al listar ',error:'".$excepcion->getMessage()."'}})";
            }
            return $this->renderText($salida);
    }
    
    public function executeEliminarConcepto(sfWebRequest $request)
    {
            $salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n concepto'}})";
            try{
                    $con_codigo = $this->getRequestParameter('con_codigo');

                    if($con_codigo!=''){                    

                            $concepto = ConceptoPeer::retrieveByPk($con_codigo);
                            if($concepto){
                                $concepto->delete();
                                $salida = "({success: true, mensaje:'El concepto fue eliminado exitosamente'})";                                    
                            }
                    }
            }
            catch (Exception $excepcion)
            {

                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en concepto al tratar de eliminar',error:'".$excepcion->getMessage()."'}})";
            }
            return $this->renderText($salida);
    }
}
