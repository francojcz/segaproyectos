<?php

/**
 * usuarios actions.
 *
 * @package    segaproyectos
 * @subpackage usuarios
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class usuariosActions extends sfActions
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
  
  public function executeActualizarUsuario(sfWebRequest $request)
    {
            try{
                    $usu_codigo = $this->getRequestParameter('usu_codigo');
                    $usuario;

                    if($usu_codigo!=''){
                            $usuario  = UsuarioPeer::retrieveByPk($usu_codigo);
                    }
                    else
                    {
                            $usuario = new Usuario();
                            $usuario->setUsuFechaRegistro(time());                  
                    }

                    if($usuario)
                    {
                            $usuario->setUsuLogin($this->getRequestParameter('usu_login'));
                            $usuario->setUsuContrasena($this->getRequestParameter('usu_contrasena'));
                            $usuario->setUsuPerCodigo($this->getRequestParameter('usu_perfil'));
                            $usuario->setUsuPersCodigo($this->getRequestParameter('usu_persona'));
                            $usuario->setUsuHabilitado($this->getRequestParameter('usu_habilitado'));
                            $usuario->save();
                            $salida = "({success: true, mensaje:'El usuario fue registrado exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en usuario',error:'".$excepcion."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeListarUsuario(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');                    

                    $conexion = new Criteria();
                    $usuarios_cantidad = UsuarioPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    
                    $conexion->addAscendingOrderByColumn(UsuarioPeer::USU_CODIGO);
                    $usuario = UsuarioPeer::doSelect($conexion);

                    foreach($usuario as $temporal)
                    {
                            $datos[$fila]['usu_codigo']=$temporal->getUsuCodigo();
                            $datos[$fila]['usu_login']=$temporal->getUsuLogin();
                            $datos[$fila]['usu_contrasena']=$temporal->getUsuContrasena();
                            
                            $datos[$fila]['usu_perfil'] = $temporal->getUsuPerCodigo();
                            $perfil = PerfilPeer::retrieveByPK($temporal->getUsuPerCodigo());
                            $datos[$fila]['usu_perfil_nombre'] = $perfil->getPerNombre();
                            
                            $datos[$fila]['usu_persona'] = $temporal->getUsuPersCodigo();                            
                            $persona = PersonaPeer::retrieveByPK($temporal->getUsuPersCodigo());
                            $datos[$fila]['usu_persona_nombre'] = $persona->getPersNombres().' '.$persona->getPersApellidos();
                            
                            $datos[$fila]['usu_habilitado'] = $temporal->getUsuHabilitado(); 
                            $datos[$fila]['usu_fecha_registro'] = $temporal->getUsuFechaRegistro(); 
                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$usuarios_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en usuario ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    public function executeListarPerfil(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{

                    $conexion = new Criteria();
                    $perfil = PerfilPeer::doSelect($conexion);

                    foreach($perfil As $temporal)
                    {
                            $datos[$fila]['per_codigo'] = $temporal->getPerCodigo();
                            $datos[$fila]['per_nombre'] = $temporal->getPerNombre();
                            $fila++;
                    }

                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
                    }
            }catch (Exception $excepcion)
            {
                    $salida='Excepcion en listar Perfiles';
            }
            return $this->renderText($salida);
    }
    
    public function executeListarPersona(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{

                    $conexion = new Criteria();
                    $conexion->add(PersonaPeer::PERS_ELIMINADO, 0);
                    $persona = PersonaPeer::doSelect($conexion);

                    foreach($persona As $temporal)
                    {
                            $datos[$fila]['persona_codigo'] = $temporal->getPersCodigo();
                            $datos[$fila]['persona_nombre'] = $temporal->getPersNombres().' '.$temporal->getPersApellidos();
                            $fila++;
                    }

                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
                    }
            }catch (Exception $excepcion)
            {
                    $salida='Excepcion en listar Personas';
            }
            return $this->renderText($salida);
    }
}
