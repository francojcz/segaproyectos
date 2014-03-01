<?php

/**
 * proyectos actions.
 *
 * @package    segaproyectos
 * @subpackage proyectos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class proyectosActions extends sfActions
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
  
  
    public function executeActualizarProyecto(sfWebRequest $request)
    {
            try{
                    $pro_codigo = $this->getRequestParameter('pro_codigo');
                    $proyecto;

                    if($pro_codigo!=''){
                            $proyecto  = ProyectoPeer::retrieveByPk($pro_codigo);
                    }
                    else
                    {
                            $proyecto = new Proyecto();
                            $proyecto->setProFechaRegistro(time());
                            $proyecto->setProEliminado(0);
                            $proyecto->setProUsuCodigo($this->getUser()->getAttribute('usu_codigo'));
                    }

                    if($proyecto)
                    {                        
                            $proyecto->setProCodigoContable($this->getRequestParameter('pro_codigo_contable'));
                            $proyecto->setProNombre($this->getRequestParameter('pro_nombre'));
                            $proyecto->setProDescripcion($this->getRequestParameter('pro_descripcion'));
                            $proyecto->setProPersCodigo($this->getRequestParameter('pro_usu_persona'));
                            $proyecto->setProEstCodigo($this->getRequestParameter('pro_estado'));
                            $proyecto->setProEjeCodigo($this->getRequestParameter('pro_ejecutor'));
                            $proyecto->setProTippCodigo($this->getRequestParameter('pro_tipo'));
                            $proyecto->setProOtroTipo($this->getRequestParameter('pro_otro_tipo'));
                            $proyecto->setProValor($this->getRequestParameter('pro_valor'));
                            $proyecto->setProFechaInicio($this->getRequestParameter('pro_fecha_inicio'));
                            $proyecto->setProFechaFin($this->getRequestParameter('pro_fecha_fin'));                            
                            $proyecto->setProObservaciones($this->getRequestParameter('pro_observaciones'));
                            $proyecto->save();
                            $this->guardarArchivoPresupuesto($request,$proyecto);
                            //Eliminar alarma proyecto
                            $criteria = new Criteria();
                            $criteria->add(AlarmaPeer::ALA_CONCEPTO, 'Finalización de Proyecto');
                            $criteria->add(AlarmaPeer::ALA_CON_CODIGO, $proyecto->getProCodigo());
                            $registro = AlarmaPeer::doSelectOne($criteria);
                            $count_r = AlarmaPeer::doCount($criteria);
                            if($count_r == 1) {
                                $registro->delete();
                            }
                            
                            $salida = "({success: true, mensaje:'El proyecto fue registrado exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en proyecto',error:'".$excepcion."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeListarProyecto(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');
                    $pro_eliminado=$this->getRequestParameter('pro_eliminado');//los de mostrar
                    if($pro_eliminado==''){
                            $pro_eliminado=0;
                    }

                    $conexion = new Criteria();     
                    $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
                    if($codigo_usuario != 1) {
                        $conexion->add(ProyectoPeer::PRO_PERS_CODIGO, $codigo_usuario);
                    }
                    $conexion->add(ProyectoPeer::PRO_ELIMINADO,$pro_eliminado);
                    $proyectos_cantidad = ProyectoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conexion->addAscendingOrderByColumn(ProyectoPeer::PRO_CODIGO);
                    $proyecto = ProyectoPeer::doSelect($conexion);

                    foreach($proyecto as $temporal)
                    {
                            $datos[$fila]['pro_codigo']=$temporal->getProCodigo();
                            $datos[$fila]['pro_codigo_contable']=$temporal->getProCodigoContable();
                            $datos[$fila]['pro_nombre']=$temporal->getProNombre();
                            $datos[$fila]['pro_descripcion']=$temporal->getProDescripcion();
                            
                            $datos[$fila]['pro_usu_persona'] = $temporal->getProPersCodigo();                            
                            $persona = PersonaPeer::retrieveByPK($temporal->getProPersCodigo());
                            $datos[$fila]['pro_usu_persona_pro_nombre'] = $persona->getPersNombres().' '.$persona->getPersApellidos();
                            
                            $datos[$fila]['pro_estado'] = $temporal->getProEstCodigo();
                            $estado = EstadoproyectoPeer::retrieveByPK($temporal->getProEstCodigo());
                            $datos[$fila]['pro_estado_nombre'] = $estado->getEstProNombre();
                            
                            $datos[$fila]['pro_ejecutor'] = $temporal->getProEjeCodigo();
                            $ejecutor = EjecutorproyectoPeer::retrieveByPK($temporal->getProEjeCodigo());
                            $datos[$fila]['pro_ejecutor_nombre'] = $ejecutor->getEjeProNombre();
                            
                            $datos[$fila]['pro_tipo'] = $temporal->getProTippCodigo();
                            $tipo = TipoproyectoPeer::retrieveByPK($temporal->getProTippCodigo());
                            $datos[$fila]['pro_tipo_nombre'] = $tipo->getTippNombre();
                            
                            $datos[$fila]['pro_otro_tipo']=$temporal->getProOtroTipo();
                            $datos[$fila]['pro_valor']=$temporal->getProValor();
                            $datos[$fila]['pro_fecha_inicio']=$temporal->getProFechaInicio();
                            $datos[$fila]['pro_fecha_fin']=$temporal->getProFechaFin();
                            $datos[$fila]['pro_observaciones']=$temporal->getProObservaciones();
                            $datos[$fila]['pro_presupuesto_url']=$temporal->getProPresupuestoUrl();
                            $datos[$fila]['pro_eliminado']=$temporal->getProEliminado();
                            $datos[$fila]['pro_fecha_registro']=$temporal->getProFechaRegistro();
                            
                            $datos[$fila]['pro_usuario'] = $temporal->getProUsuCodigo();
                            $crea = PersonaPeer::retrieveByPK($temporal->getProUsuCodigo());
                            $datos[$fila]['pro_usuario_nombre'] = $crea->getPersNombres().' '.$crea->getPersApellidos();
                            
                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$proyectos_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en proyecto ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeEliminarProyecto(sfWebRequest $request)
    {
            $salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n proyecto'}})";

            try{
                    $pro_codigo = $this->getRequestParameter('pro_codigo');

                    if($pro_codigo!=''){                          
                            $proyecto = ProyectoPeer::retrieveByPk($pro_codigo);
                            $proyecto->setProEliminado(1);
                            $proyecto->save();
                            $salida = "({success: true, mensaje:'El proyecto fue eliminado exitosamente'})";                             
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en proyecto al tratar de eliminar ',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    public function executeRestablecerProyecto()
    {
            $salida = "({success: false, errors: { reason: 'No se pudo eliminar el proyecto'}})";
            try{
                    $pro_codigo = $this->getRequestParameter('pro_codigo');
                    $proyecto  = ProyectoPeer::retrieveByPk($pro_codigo);

                    if($proyecto)
                    {                            
                            $proyecto->setProEliminado(0);
                            $proyecto->save();
                            $salida = "({success: true, mensaje:'El proyecto fue restablecido exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
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
                    $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
                    if($codigo_usuario != 1) {
                        $conexion->add(PersonaPeer::PERS_CODIGO, $codigo_usuario);
                    }
                    $conexion->add(PersonaPeer::PERS_ELIMINADO, 0);
                    $persona = PersonaPeer::doSelect($conexion);

                    foreach($persona As $temporal)
                    {
                            $datos[$fila]['persona_pro_codigo'] = $temporal->getPersCodigo();
                            $datos[$fila]['persona_pro_nombre'] = $temporal->getPersNombres().' '.$temporal->getPersApellidos();
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
    
    
    public function executeListarEstado(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{

                    $conexion = new Criteria();
                    $estado = EstadoproyectoPeer::doSelect($conexion);

                    foreach($estado As $temporal)
                    {
                            $datos[$fila]['est_codigo'] = $temporal->getEstProCodigo();
                            $datos[$fila]['est_nombre'] = $temporal->getEstProNombre();
                            $fila++;
                    }

                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
                    }
            }catch (Exception $excepcion)
            {
                    $salida='Excepcion en listar Estados';
            }
            return $this->renderText($salida);
    }
    
    
    public function executeListarEjecutor(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{

                    $conexion = new Criteria();
                    $ejecutor = EjecutorproyectoPeer::doSelect($conexion);

                    foreach($ejecutor As $temporal)
                    {
                            $datos[$fila]['eje_codigo'] = $temporal->getEjeProCodigo();
                            $datos[$fila]['eje_nombre'] = $temporal->getEjeProNombre();
                            $fila++;
                    }

                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
                    }
            }catch (Exception $excepcion)
            {
                    $salida='Excepcion en listar Ejecutores';
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
                    $tipo = TipoproyectoPeer::doSelect($conexion);

                    foreach($tipo As $temporal)
                    {
                            $datos[$fila]['tipp_codigo'] = $temporal->getTippCodigo();
                            $datos[$fila]['tipp_nombre'] = $temporal->getTippNombre();
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
    
    public function guardarArchivoPresupuesto(sfWebRequest $request,$proyecto){
            $salida='';
            try{
                    $pro_codigo = $proyecto->getProCodigo();
                    $nombre_carpeta = "proyectos/".$pro_codigo;

                    if(!is_dir($nombre_carpeta))
                    {
                            mkdir($nombre_carpeta, 7777, true);
                    }

                    sleep(2);
                    $nombre = $_FILES['pro_presupuesto_url']['name'];
                    $tamano = $_FILES['pro_presupuesto_url']['size'];
                    $tipo = $_FILES['pro_presupuesto_url']['type'];
                    $temporal = $_FILES['pro_presupuesto_url']['tmp_name'];

                    if($nombre!=''){
                        if($tamano > 21000000)//21 megas
                        {
                                $salida = "({success: false, errors: { reason: 'El archivo excede el limite de tama&ntilde;o permitido'}})";
                        }
                        else
                        {
                                $copio=copy($temporal, $nombre_carpeta."/".utf8_decode($nombre));
                                if($copio){
                                $proyecto->setProPresupuestoUrl($nombre_carpeta."/".$nombre);
                                $proyecto->save();
                                $salida='true';
                                }
                        }
                    }
            }catch (Exception $excepcion)
            {
                    $salida='Execepci&oacute;n al guardar archivo con presupuesto de proyecto'.$excepcion->getMessage();
                    $salida='false';
            }
            return $salida;
    }     
    
}