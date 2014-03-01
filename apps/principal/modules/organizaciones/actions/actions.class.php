<?php

/**
 * organizaciones actions.
 *
 * @package    segaproyectos
 * @subpackage organizaciones
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class organizacionesActions extends sfActions
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
  
  public function executeActualizarOrganizacion(sfWebRequest $request)
  {
            try{
                    $org_codigo = $this->getRequestParameter('org_codigo');
                    $organizacion;

                    if($org_codigo!=''){
                            $organizacion  = OrganizacionPeer::retrieveByPk($org_codigo);
                    }
                    else
                    {
                            $organizacion = new Organizacion();
                            $organizacion->setOrgFechaRegistro(time());
                            $organizacion->setOrgEliminado(0);       
                            $organizacion->setOrgUsuCodigo($this->getUser()->getAttribute('usu_codigo'));
                    }

                    if($organizacion)
                    {
                            $organizacion->setOrgNombreCompleto($this->getRequestParameter('org_nombre_completo'));
                            $organizacion->setOrgNombreCorto($this->getRequestParameter('org_nombre_corto'));
                            $organizacion->setOrgNit($this->getRequestParameter('org_nit'));
                            $organizacion->setOrgTipCodigo($this->getRequestParameter('org_tipo'));
                            $organizacion->setOrgDireccion($this->getRequestParameter('org_direccion'));
                            $organizacion->setOrgCorreo($this->getRequestParameter('org_correo'));
                            $organizacion->setOrgNombreContacto($this->getRequestParameter('org_nombre_contacto'));
                            $organizacion->setOrgTelefono($this->getRequestParameter('org_telefono'));
                            $organizacion->save();
                            $salida = "({success: true, mensaje:'La organizaci&oacute;n fue registrada exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en organizaci&oacute;n',error:'".$excepcion."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeListarOrganizacion(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');
                    $org_eliminado=$this->getRequestParameter('org_eliminado');//los de mostrar
                    if($org_eliminado==''){
                            $org_eliminado=0;
                    }

                    $conexion = new Criteria();
                    $conexion->add(OrganizacionPeer::ORG_ELIMINADO,$org_eliminado);
                    $organizaciones_cantidad = OrganizacionPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conexion->addAscendingOrderByColumn(OrganizacionPeer::ORG_CODIGO);
                    $organizacion = OrganizacionPeer::doSelect($conexion);

                    foreach($organizacion as $temporal)
                    {
                            $datos[$fila]['org_codigo']=$temporal->getOrgCodigo();
                            $datos[$fila]['org_nombre_completo']=$temporal->getOrgNombreCompleto();
                            $datos[$fila]['org_nombre_corto']=$temporal->getOrgNombreCorto();
                            $datos[$fila]['org_nit']=$temporal->getOrgNit();
                            
                            $datos[$fila]['org_tipo'] = $temporal->getOrgTipCodigo();
                            $tipo = TipoorganizacionPeer::retrieveByPK($temporal->getOrgTipCodigo());
                            $datos[$fila]['org_tipo_nombre'] = $tipo->getTipoNombre();

                            $datos[$fila]['org_direccion']=$temporal->getOrgDireccion();                            
                            $datos[$fila]['org_correo'] = $temporal->getOrgCorreo();
                            $datos[$fila]['org_nombre_contacto'] = $temporal->getOrgNombreContacto();  
                            $datos[$fila]['org_telefono'] = $temporal->getOrgTelefono();                         
                            $datos[$fila]['org_fecha_registro'] = $temporal->getOrgFechaRegistro();                          
                            $datos[$fila]['org_eliminado'] = $temporal->getOrgEliminado();
                            
                            $datos[$fila]['org_usuario'] = $temporal->getOrgUsuCodigo();
                            $persona = PersonaPeer::retrieveByPK($temporal->getOrgUsuCodigo());
                            $datos[$fila]['org_usuario_nombre'] = $persona->getPersNombres().' '.$persona->getPersApellidos();

                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$organizaciones_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en organizaci&oacute;n ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeEliminarOrganizacion(sfWebRequest $request)
    {
            $salida = "({success: false,  errors: { reason: 'No ha seleccionado ninguna organizaci&oacute;n'}})";

            try{
                    $org_codigo = $this->getRequestParameter('org_codigo');

                    if($org_codigo!=''){                          
                            $organizacion = OrganizacionPeer::retrieveByPk($org_codigo);
                            $organizacion->setOrgEliminado(1);
                            $organizacion->save();
                            $salida = "({success: true, mensaje:'La organizaci&oacute;n fue eliminada exitosamente'})";                             
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en organizaci&oacute;n al tratar de eliminar ',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeRestablecerOrganizacion()
    {
            $salida = "({success: false, errors: { reason: 'No se pudo eliminar la organizaci&oacute;n'}})";
            try{
                    $org_codigo = $this->getRequestParameter('org_codigo');
                    $organizacion  = OrganizacionPeer::retrieveByPk($org_codigo);

                    if($organizacion)
                    {                            
                            $organizacion->setOrgEliminado(0);
                            $organizacion->save();
                            $salida = "({success: true, mensaje:'La organizaci&oacute;n fue restablecida exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
            }
            return $this->renderText($salida);
    }
    
    
    public function executeListarTipo(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{

                    $conexion = new Criteria();
                    $tipo = TipoorganizacionPeer::doSelect($conexion);

                    foreach($tipo As $temporal)
                    {
                            $datos[$fila]['tip_codigo'] = $temporal->getTipoCodigo();
                            $datos[$fila]['tip_nombre'] = $temporal->getTipoNombre();
                            $fila++;
                    }

                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
                    }
            }catch (Exception $excepcion)
            {
                    $salida='Excepcion en listar Tipos';
            }
            return $this->renderText($salida);
    }    
    
    public function executeListarOrganizacionesPorProyecto(sfWebRequest $request )
    {
            $org_codigo = $this->getRequestParameter('org_codigo');

            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $conexion = new Criteria();                    
                    $conexion->addJoin(OrganizacionproyectoPeer::ORPY_PRO_CODIGO, ProyectoPeer::PRO_CODIGO);
                    $conexion->add(OrganizacionproyectoPeer::ORPY_ORG_CODIGO, $org_codigo );
                    $conexion->add(ProyectoPeer::PRO_ELIMINADO, 0);
                    $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
                    if($codigo_usuario != 1) {
                        $conexion->add(ProyectoPeer::PRO_PERS_CODIGO, $codigo_usuario);
                    }
                    $conexion->addAscendingOrderByColumn(ProyectoPeer::PRO_NOMBRE);
                    $proyectos = ProyectoPeer::doSelect($conexion);

                    foreach($proyectos as $temporal)
                    {
                            $datos[$fila]['org_pro_codigo'] = $temporal->getProCodigo();
                            $datos[$fila]['org_pro_nombre'] = $temporal->getProNombre();

                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n al listar Organizaciones por Proyecto',error:'".$excepcion->getMessage()."'}})";
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
                            $datos[$fila]['pro_codigo'] = $temporal->getProCodigo();
                            $datos[$fila]['pro_nombre'] = $temporal->getProNombre();

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
    
    public function executeGuardarProyectoPorOrganizacion(sfWebRequest $request)
    {
            $salida = "({success: true, mensaje:'La asignaci&oacute;n de organizaci&oacute;n a proyecto no fue realizada'})";

            $user = $this -> getUser();  
            $codigo_usuario = $user -> getAttribute('usu_codigo');
            try{
                    $org_proyecto = $this->getRequestParameter('org_codigo');
                    $pro_codigo = $this->getRequestParameter('pro_codigo');

                    $organizaporproyecto;

                    if($org_proyecto!='' && $pro_codigo!=''){

                            $conexion = new Criteria();
                            $conexion->add(OrganizacionproyectoPeer::ORPY_PRO_CODIGO, $pro_codigo);
                            $conexion->addAnd(OrganizacionproyectoPeer::ORPY_ORG_CODIGO, $org_proyecto );	
                            $organizaporproyecto = OrganizacionproyectoPeer::doSelect($conexion);

                            if($organizaporproyecto)
                            {
                                    $salida = "({success: true, mensaje:'El proyecto ya hab&iacute;a sido asignado a la organizaci&oacute;n'})";
                            }
                            else
                            {
                                    $organizaporproyecto = new Organizacionproyecto();
                                    $organizaporproyecto->setOrpyOrgCodigo($org_proyecto);
                                    $organizaporproyecto->setOrpyProCodigo($pro_codigo);
                                    $organizaporproyecto->setOrpyUsuCodigo($codigo_usuario);
                                    $organizaporproyecto->setOrpyFechaRegistro(date('Y-m-d H:i:s'));
                                    $organizaporproyecto->save();

                                    $salida = "({success: true, mensaje:'La asignaci&oacute;n de proyecto a organizaci&oacute;n fue realizada exitosamente'})";
                            }

                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en participante',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    public function executeEliminarOrganizacionPorProyecto(sfWebRequest $request)
    {
            $salida = "({success: true, mensaje:'La asignaci&oacute;n de organizaci&oacute;n a proyecto no fue eliminada'})";
            $organizaporproyecto;
            try{
                    $org_codigo = $this->getRequestParameter('org_codigo');
                    $temp = $this->getRequestParameter('pros_codigos');
                    $pros_codigos = json_decode($temp);

                    if($org_codigo!='' ){
                            foreach ($pros_codigos as $pro_codigo)
                            {
                                    $conexion = new Criteria();
                                    $conexion->add(OrganizacionproyectoPeer::ORPY_PRO_CODIGO, $pro_codigo );
                                    $conexion->addAnd(OrganizacionproyectoPeer::ORPY_ORG_CODIGO, $org_codigo );	
                                    $organizaporproyecto = OrganizacionproyectoPeer::doSelectOne($conexion);

                                    if($organizaporproyecto)
                                    {
                                            $organizaporproyecto->delete();
                                            $salida = "({success: true, mensaje:'La organizaci&oacute;n ha sido eliminada exitosamente del proyecto'})";
                                    }
                            }
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en organizaci&oacute;n',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
}
