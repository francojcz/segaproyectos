<?php

/**
 * documentos actions.
 *
 * @package    segaproyectos
 * @subpackage documentos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class documentosActions extends sfActions
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
  
  public function executeActualizarDocumento(sfWebRequest $request)
  {
            try{
                    $doc_codigo = $this->getRequestParameter('doc_codigo');
                    $documento;

                    if($doc_codigo!=''){
                            $documento  = DocumentoPeer::retrieveByPk($doc_codigo);
                    }
                    else
                    {
                            $documento = new Documento();
                            $documento->setDocFechaRegistro(time());
                            $documento->setDocEliminado(0); 
                            $documento->setDocUsuCodigo($this->getUser()->getAttribute('usu_codigo'));
                    }

                    if($documento)
                    {                            
                            $documento->setDocTipdCodigo($this->getRequestParameter('doc_tipo'));
                            $documento->setDocProCodigo($this->getRequestParameter('doc_proyecto'));
                            $documento->save();
                            $salida = $this->guardarDocumentoDocumento($request,$documento);
                            if($salida != true) {
                                $salida = "({success: true, mensaje:'El documento fue registrado exitosamente'})";
                            }
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en documento',error:'".$excepcion."'}})";
            }

            return $this->renderText($salida);
    }
    
    public function executeListarDocumento(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');
                    $doc_eliminado=$this->getRequestParameter('doc_eliminado');//los de mostrar
                    if($doc_eliminado==''){
                            $doc_eliminado=0;
                    }

                    $conexion = new Criteria();
                    $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
                    if($codigo_usuario != 1) {
                        $criteria = new Criteria();
                        $criteria->add(ProyectoPeer:: PRO_PERS_CODIGO, $codigo_usuario);
                        $proyectos = ProyectoPeer::doSelect($criteria);
                        foreach ($proyectos as $proyecto) {
                            $conexion->addOr(DocumentoPeer::DOC_PRO_CODIGO, $proyecto->getProCodigo());
                        }
                    }
                    $conexion->add(DocumentoPeer::DOC_ELIMINADO,$doc_eliminado);
                    $documentos_cantidad = DocumentoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conexion->addAscendingOrderByColumn(DocumentoPeer::DOC_CODIGO);
                    $documento = DocumentoPeer::doSelect($conexion);

                    foreach($documento as $temporal)
                    {
                            $datos[$fila]['doc_codigo']=$temporal->getDocCodigo();
                            $datos[$fila]['doc_documento_url']=$temporal->getDocDocumentoUrl();
                                                        
                            $datos[$fila]['doc_tipo'] = $temporal->getDocTipdCodigo();
                            $tipo = TipodocumentoPeer::retrieveByPK($temporal->getDocTipdCodigo());
                            $datos[$fila]['doc_tipo_nombre'] = $tipo->getTipdNombre();
                                               
                            $datos[$fila]['doc_fecha_registro'] = $temporal->getDocFechaRegistro();
                            $datos[$fila]['doc_eliminado'] = $temporal->getDocEliminado();
                            
                            $datos[$fila]['doc_usuario'] = $temporal->getDocUsuCodigo();
                            $persona = PersonaPeer::retrieveByPK($temporal->getDocUsuCodigo());
                            $datos[$fila]['doc_usuario_nombre'] = $persona->getPersNombres().' '.$persona->getPersApellidos();
                            
                            $datos[$fila]['doc_proyecto'] = $temporal->getDocProCodigo();
                            $proyecto = ProyectoPeer::retrieveByPK($temporal->getDocProCodigo());
                            $datos[$fila]['doc_proyecto_nombre'] = $proyecto->getProNombre();

                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$documentos_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en documento ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    public function executeEliminarDocumento(sfWebRequest $request)
    {
            $salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n documento'}})";

            try{
                    $doc_codigo = $this->getRequestParameter('doc_codigo');

                    if($doc_codigo!=''){                          
                            $documento = DocumentoPeer::retrieveByPk($doc_codigo);
                            $documento->setDocEliminado(1);
                            $documento->save();
                            $salida = "({success: true, mensaje:'El documento fue eliminado exitosamente'})";                             
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en documento al tratar de eliminar ',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeRestablecerDocumento()
    {
            $salida = "({success: false, errors: { reason: 'No se pudo eliminar el documento'}})";
            try{
                    $doc_codigo = $this->getRequestParameter('doc_codigo');
                    $documento  = DocumentoPeer::retrieveByPk($doc_codigo);

                    if($documento)
                    {                            
                            $documento->setDocEliminado(0);
                            $documento->save();
                            $salida = "({success: true, mensaje:'El documento fue restablecido exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
            }
            return $this->renderText($salida);
    }
    
    
    public function executeListarTipoDocumento(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{

                    $conexion = new Criteria();
                    $tipod = TipodocumentoPeer::doSelect($conexion);

                    foreach($tipod As $temporal)
                    {
                            $datos[$fila]['tipd_codigo'] = $temporal->getTipdCodigo();
                            $datos[$fila]['tipd_nombre'] = $temporal->getTipdNombre();
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
    
    public function guardarDocumentoDocumento(sfWebRequest $request,$documento){
            $salida='';
            try{
                    $doc_codigo = $documento->getDocCodigo();
                    $nombre_carpeta = "documentos/".$doc_codigo;

                    if(!is_dir($nombre_carpeta))
                    {
                            mkdir($nombre_carpeta, 7777, true);
                    }

                    sleep(2);
                    $nombre = $_FILES['doc_documento_url']['name'];
                    $tamano = $_FILES['doc_documento_url']['size'];
                    $tipo = $_FILES['doc_documento_url']['type'];
                    $temporal = $_FILES['doc_documento_url']['tmp_name'];

                    if($nombre!=''){
                        if($tamano > 21000000)//21 Megas
                        {
                                $salida = "({success: false, errors: { reason: 'El archivo excede el l&iacute;mite de tama&ntilde;o permitido'}})";
                        }
                        else
                        {
                                $copio=copy($temporal, $nombre_carpeta."/".utf8_decode($nombre));
                                if($copio){
                                $documento->setDocDocumentoUrl($nombre_carpeta."/".$nombre);
                                $documento->save();
                                $salida='true';
                                }
                        }
                    }
            }catch (Exception $excepcion)
            {
                    $salida='Execepci&oacute;n al guardar documento'.$excepcion->getMessage();
                    $salida='false';
            }
            return $salida;
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
                            $datos[$fila]['proyec_codigo'] = $temporal->getProCodigo();
                            $datos[$fila]['proyec_nombre'] = $temporal->getProNombre();

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
     
}