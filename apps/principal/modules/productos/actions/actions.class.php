<?php

/**
 * productos actions.
 *
 * @package    segaproyectos
 * @subpackage productos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class productosActions extends sfActions
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
  
  public function executeActualizarProducto(sfWebRequest $request)
    {
            try{
                    $prod_codigo = $this->getRequestParameter('prod_codigo');
                    $producto;

                    if($prod_codigo!=''){
                            $producto  = ProductoPeer::retrieveByPk($prod_codigo);
                    }
                    else
                    {
                            $producto = new Producto();
                            $producto->setProdFechaRegistro(time());
                            $producto->setProdEliminado(0);    
                            $producto->setProdUsuCodigo($this->getUser()->getAttribute('usu_codigo'));
                    }

                    if($producto)
                    {
                            $producto->setProdNombre($this->getRequestParameter('prod_nombre'));
                            $producto->setProdFechaEntrega($this->getRequestParameter('prod_fecha_entrega'));
                            $producto->setProdProCodigo($this->getRequestParameter('prod_proyecto'));
                            $producto->setProdEstCodigo($this->getRequestParameter('prod_estado'));
                            $producto->save();
                            //Guardar archivo producto
                            $salida = $this->guardarDocumentoProducto($request,$producto);
                            if($salida != true) {
                                $salida = "({success: true, mensaje:'El producto fue registrado exitosamente'})";
                            }
                            //Eliminar alarma producto
                            $criteria = new Criteria();
                            $criteria->add(AlarmaPeer::ALA_CONCEPTO, 'Entrega de Producto');
                            $criteria->add(AlarmaPeer::ALA_CON_CODIGO, $producto->getProdCodigo());
                            $registro = AlarmaPeer::doSelectOne($criteria);
                            $count_r = AlarmaPeer::doCount($criteria);
                            if($count_r == 1) {
                                $registro->delete();
                            }
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en producto',error:'".$excepcion."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeListarProducto(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');
                    $prod_eliminado=$this->getRequestParameter('prod_eliminado');//los de mostrar
                    if($prod_eliminado==''){
                            $prod_eliminado=0;
                    }
                    
                    $conexion = new Criteria();
                    $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
                    if($codigo_usuario != 1) {
                        $criteria = new Criteria();
                        $criteria->add(ProyectoPeer:: PRO_PERS_CODIGO, $codigo_usuario);
                        $proyectos = ProyectoPeer::doSelect($criteria);
                        foreach ($proyectos as $proyecto) {
                            $conexion->addOr(ProductoPeer::PROD_PRO_CODIGO, $proyecto->getProCodigo());
                        }
                    }                    
                    $conexion->add(ProductoPeer::PROD_ELIMINADO,$prod_eliminado);
                    $productos_cantidad = ProductoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conexion->addAscendingOrderByColumn(ProductoPeer::PROD_CODIGO);
                    $producto = ProductoPeer::doSelect($conexion);

                    foreach($producto as $temporal)
                    {
                            $datos[$fila]['prod_codigo']=$temporal->getProdCodigo();
                            $datos[$fila]['prod_nombre'] = $temporal->getProdNombre();
                            $datos[$fila]['prod_fecha_entrega'] = $temporal->getProdFechaEntrega();
                            $datos[$fila]['prod_documento_url'] = $temporal->getProdDocumentoUrl();                                                   
                            $datos[$fila]['prod_fecha_registro'] = $temporal->getProdFechaRegistro();                          
                            $datos[$fila]['prod_eliminado'] = $temporal->getProdEliminado();
                            
                            $datos[$fila]['prod_proyecto'] = $temporal->getProdProCodigo();
                            $proyecto = ProyectoPeer::retrieveByPK($temporal->getProdProCodigo());
                            $datos[$fila]['prod_proyecto_nombre'] = $proyecto->getProNombre();
                            
                            $datos[$fila]['prod_usuario'] = $temporal->getProdUsuCodigo();
                            $persona = PersonaPeer::retrieveByPK($temporal->getProdUsuCodigo());
                            $datos[$fila]['prod_usuario_nombre'] = $persona->getPersNombres().' '.$persona->getPersApellidos();
                            
                            $datos[$fila]['prod_estado'] = $temporal->getProdEstCodigo();
                            $estado = EstadoproductoPeer::retrieveByPK($temporal->getProdEstCodigo());
                            $datos[$fila]['prod_estado_nombre'] = $estado->getEstProdNombre();

                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$productos_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en producto ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeEliminarProducto(sfWebRequest $request)
    {
            $salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n producto'}})";

            try{
                    $prod_codigo = $this->getRequestParameter('prod_codigo');

                    if($prod_codigo!=''){                          
                            $producto = ProductoPeer::retrieveByPk($prod_codigo);
                            $producto->setProdEliminado(1);
                            $producto->save();
                            $salida = "({success: true, mensaje:'El producto fue eliminado exitosamente'})";                             
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en producto al tratar de eliminar ',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    public function executeRestablecerProducto()
    {
            $salida = "({success: false, errors: { reason: 'No se pudo eliminar el producto'}})";
            try{
                    $prod_codigo = $this->getRequestParameter('prod_codigo');
                    $producto  = ProductoPeer::retrieveByPk($prod_codigo);

                    if($producto)
                    {                            
                            $producto->setProdEliminado(0);
                            $producto->save();
                            $salida = "({success: true, mensaje:'El producto fue restablecido exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
            }
            return $this->renderText($salida);
    }
    
    
    public function guardarDocumentoProducto(sfWebRequest $request,$producto){
            $salida='';
            try{
                    $prod_codigo = $producto->getProdCodigo();
                    $nombre_carpeta = "productos/".$prod_codigo;

                    if(!is_dir($nombre_carpeta))
                    {
                            mkdir($nombre_carpeta, 7777, true);
                    }

                    sleep(2);
                    $nombre = $_FILES['prod_documento_url']['name'];
                    $tamano = $_FILES['prod_documento_url']['size'];
                    $tipo = $_FILES['prod_documento_url']['type'];
                    $temporal = $_FILES['prod_documento_url']['tmp_name'];

                    if($nombre!=''){
                        if($tamano > 21000000)//21 Megas
                        {
                                $salida = "({success: false, errors: { reason: 'El archivo excede el l&iacute;mite de tama&ntilde;o permitido'}})";
                        }
                        else
                        {
                                $copio=copy($temporal, $nombre_carpeta."/".utf8_decode($nombre));
                                if($copio){
                                $producto->setProdDocumentoUrl($nombre_carpeta."/".$nombre);
                                $producto->save();
                                $salida='true';
                                }
                        }
                    }
            }catch (Exception $excepcion)
            {
                    $salida='Execepci&oacute;n al guardar producto'.$excepcion->getMessage();
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
                            $datos[$fila]['proye_codigo'] = $temporal->getProCodigo();
                            $datos[$fila]['proye_nombre'] = $temporal->getProNombre();

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
    
    public function executeListarEstado(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{

                    $conexion = new Criteria();
                    $estado = EstadoproductoPeer::doSelect($conexion);

                    foreach($estado As $temporal)
                    {
                            $datos[$fila]['estd_codigo'] = $temporal->getEstProdCodigo();
                            $datos[$fila]['estd_nombre'] = $temporal->getEstProdNombre();
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
}