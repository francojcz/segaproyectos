<?php

/**
 * personas actions.
 *
 * @package    segaproyectos
 * @subpackage personas
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class personasActions extends sfActions
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
  
    public function executeActualizarPersona(sfWebRequest $request)
    {
            try{
                    $pers_codigo = $this->getRequestParameter('pers_codigo');
                    $persona;

                    if($pers_codigo!=''){
                            $persona  = PersonaPeer::retrieveByPk($pers_codigo);
                    }
                    else
                    {
                            $persona = new Persona();
                            $persona->setPersFechaRegistro(time());
                            $persona->setPersEliminado(0);    
                            $persona->setPersUsuCodigo($this->getUser()->getAttribute('usu_codigo'));
                    }

                    if($persona)
                    {
                            $persona->setPersNombres($this->getRequestParameter('pers_nombres'));
                            $persona->setPersApellidos($this->getRequestParameter('pers_apellidos'));
                            $persona->setPersNumeroIdentificacion($this->getRequestParameter('pers_numero_identificacion'));
                            $persona->setPersCargo($this->getRequestParameter('pers_cargo'));
                            $persona->setPersCorreo($this->getRequestParameter('pers_correo'));
                            $persona->setPersTelefono($this->getRequestParameter('pers_telefono'));                                                       
                            $persona->setPersCelular($this->getRequestParameter('pers_celular'));
                            $persona->save();
                            $salida = "({success: true, mensaje:'La persona fue registrada exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en persona',error:'".$excepcion."'}})";
            }

            return $this->renderText($salida);
    }

    
    public function executeListarPersona(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');
                    $pers_eliminado=$this->getRequestParameter('pers_eliminado');//los de mostrar
                    if($pers_eliminado==''){
                            $pers_eliminado=0;
                    }

                    $conexion = new Criteria();
                    $conexion->add(PersonaPeer::PERS_ELIMINADO,$pers_eliminado);
                    $personas_cantidad = PersonaPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conexion->addAscendingOrderByColumn(PersonaPeer::PERS_CODIGO);
                    $persona = PersonaPeer::doSelect($conexion);

                    foreach($persona as $temporal)
                    {
                            $datos[$fila]['pers_codigo']=$temporal->getPersCodigo();
                            $datos[$fila]['pers_nombres'] = $temporal->getPersNombres();
                            $datos[$fila]['pers_apellidos'] = $temporal->getPersApellidos();
                            $datos[$fila]['pers_numero_identificacion'] = $temporal->getPersNumeroIdentificacion();
                            $datos[$fila]['pers_cargo'] = $temporal->getPersCargo();
                            $datos[$fila]['pers_correo'] = $temporal->getPersCorreo();
                            $datos[$fila]['pers_telefono'] = $temporal->getPersTelefono();                         
                            $datos[$fila]['pers_celular'] = $temporal->getPersCelular();
                            $datos[$fila]['pers_fecha_registro'] = $temporal->getPersFechaRegistro();                          
                            $datos[$fila]['pers_eliminado'] = $temporal->getPersEliminado();
                            
                            $datos[$fila]['pers_usuario'] = $temporal->getPersUsuCodigo();
                            $persona = PersonaPeer::retrieveByPK($temporal->getPersUsuCodigo());
                            $datos[$fila]['pers_usuario_nombre'] = $persona->getPersNombres();

                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$personas_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en persona ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }

    
    public function executeEliminarPersona(sfWebRequest $request)
    {
            $salida = "({success: false,  errors: { reason: 'No ha seleccionado ninguna persona'}})";

            try{
                    $pers_codigo = $this->getRequestParameter('pers_codigo');

                    if($pers_codigo!=''){                          
                            $persona = PersonaPeer::retrieveByPk($pers_codigo);
                            $persona->setPersEliminado(1);
                            $persona->save();
                            $salida = "({success: true, mensaje:'La persona fue eliminada exitosamente'})";                             
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en persona al tratar de eliminar ',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }

    public function executeRestablecerPersona()
    {
            $salida = "({success: false, errors: { reason: 'No se pudo eliminar la persona'}})";
            try{
                    $pers_codigo = $this->getRequestParameter('pers_codigo');
                    $persona  = PersonaPeer::retrieveByPk($pers_codigo);

                    if($persona)
                    {                            
                            $persona->setPersEliminado(0);
                            $persona->save();
                            $salida = "({success: true, mensaje:'La persona fue restablecida exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
            }
            return $this->renderText($salida);
    }
}
