<?php

/**
 * crud_repuesto actions.
 *
 * @package    tpmlabs
 * @subpackage crud_repuesto
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class crud_repuestoActions extends sfActions
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
    
    
    /*Esta funcion que crea y actualiza un repuesto*/
    public function executeActualizarRepuesto(sfWebRequest $request)
    {
        $salida = '';

        try{
                $rep_codigo = $this->getRequestParameter('rep_codigo');
                $repuesto = null;

                if($rep_codigo!=''){
                        $repuesto  = RepuestoPeer::retrieveByPk($rep_codigo);

                        if($repuesto->getRepEliminado()) {
                                $salida = "({success: false, errors: { reason: 'No es posible modificar un repuesto que ha sido eliminado'}})";
                                return $this->renderText($salida);
                        }
                }
                else
                {
                        $repuesto = new Repuesto();
                        $repuesto->setRepFechaRegistroSistema(time());
                        $repuesto->setRepUsuCrea($this->getUser()->getAttribute('usu_codigo'));
                        $repuesto->setRepEliminado(0);
                }

                if($repuesto)
                {
                        $repuesto->setRepNumero($this->getRequestParameter('rep_numero'));
                        $repuesto->setRepNombre($this->getRequestParameter('rep_nombre'));
                        $repuesto->setRepCantidad($this->getRequestParameter('rep_cantidad'));
                        $repuesto->setRepPeriodicidad($this->getRequestParameter('rep_periodicidad'));                        
                        $repuesto->setRepFechaActualizacion(time());
                        $repuesto->setRepUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
                        $repuesto->setRepCausaActualizacion($this->getRequestParameter('rep_causa_actualizacion'));
                        $repuesto->setRepCatCodigo($this->getRequestParameter('rep_cat_codigo'));

                        $repuesto->save();

                        $salida = "({success: true, mensaje:'El repuesto fue actualizado exitosamente'})";
                }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en repuesto ".$excepcion."'}})";
            }

            return $this->renderText($salida);
    }
        
    
    /*Esta funcion retorna un listado de repuestos*/
    public function executeListarRepuesto(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $rep_numero=$this->getRequestParameter('rep_numero');
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');
                    $rep_eliminado=$this->getRequestParameter('rep_eliminado');//los de mostrar
                    if($rep_eliminado==''){
                            $rep_eliminado=0;
                    }

                    $conexion = new Criteria();
                    $conexion->add(RepuestoPeer::REP_ELIMINADO,$rep_eliminado);
                    if($rep_numero){
                            $conexion->add(RepuestoPeer::REP_NUMERO,$rep_numero);
                    }

                    $repuestos_cantidad = RepuestoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $repuesto = RepuestoPeer::doSelect($conexion);

                    foreach($repuesto as $temporal)
                    {
                            $datos[$fila]['rep_codigo']=$temporal->getRepCodigo();
                            $datos[$fila]['rep_numero'] = $temporal->getRepNumero();
                            $datos[$fila]['rep_nombre'] = $temporal->getRepNombre();
                            $datos[$fila]['rep_cantidad'] = $temporal->getRepCantidad();
                            $datos[$fila]['rep_periodicidad'] = $temporal->getRepPeriodicidad();                                                        
                            $datos[$fila]['rep_fecha_registro_sistema'] = $temporal->getRepFechaRegistroSistema();
                            $datos[$fila]['rep_fecha_actualizacion'] = $temporal->getRepFechaActualizacion();                            
                            $datos[$fila]['rep_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getRepUsuCrea());
                            $datos[$fila]['rep_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getRepUsuActualiza());
                            $datos[$fila]['rep_eliminado'] = $temporal->getRepEliminado();
                            $datos[$fila]['rep_causa_eliminacion'] = $temporal->getRepCausaEliminacion();
                            $datos[$fila]['rep_causa_actualizacion'] = $temporal->getRepCausaActualizacion();
                            $datos[$fila]['rep_cat_codigo'] = $temporal->getRepCatCodigo();                                             

                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$repuestos_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en repuesto ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    /*Esta funcion elimina un repuesto*/
    public function executeEliminarRepuesto(sfWebRequest $request)
    {
            $salida = '';

            try{
                    $rep_codigo = $this->getRequestParameter('rep_codigo');
                    $causa_eliminacion= $this->getRequestParameter('rep_causa_eliminacion');


                    if($rep_codigo!=''){

                            /*
                             $repuesto  = repuestoPeer::retrieveByPk($rep_codigo);
                             if($repuesto){
                                    $repuesto->delete();
                                    $salida = "({success: true, mensaje:'El repuesto fue eliminada exitosamente'})";
                                    }
                                    else{
                                    */

                            $repuesto  = RepuestoPeer::retrieveByPk($rep_codigo);
                            if($repuesto){
                                    $repuesto->setRepUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina
                                    $repuesto->setRepFechaActualizacion(time());
                                    $repuesto->setRepEliminado(1);
                                    $repuesto->setRepCausaEliminacion($causa_eliminacion);
                                    $repuesto->save();
                                    $salida = "({success: true, mensaje:'El repuesto fue eliminado exitosamente'})";
                            }
                            //}
                    }
                    else
                    {
                            $salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n repuesto'}})";
                    }

            }
            catch (Exception $excepcion)
            {

                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en repuesto al tratar de eliminar ',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    /*Este metodo permite restablecer un objeto eliminado*/
    public function executeRestablecerRepuesto()
    {
            $salida = "({success: false, errors: { reason: 'No se pudo restablecer el repuesto'}})";
            try{
                    $rep_codigo = $this->getRequestParameter('rep_codigo');
                    $causa_reestablece= $this->getRequestParameter('rep_causa_restablece');


                    $repuesto  = RepuestoPeer::retrieveByPk($rep_codigo);

                    if($repuesto)
                    {
                            $repuesto->setRepUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo reestablece
                            $repuesto->setRepFechaActualizacion(time());
                            $repuesto->setRepEliminado(0);
                            $repuesto->setRepCausaActualizacion($causa_reestablece);
                            $repuesto->save();
                            $salida = "({success: true, mensaje:'El repuesto fue restablecido exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
            }
            return $this->renderText($salida);
    }    
    
    
    public function executeListarCategoria(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{

                    $conexion = new Criteria();
                    $categorias = CategoriaEquipoPeer::doSelect($conexion);

                    foreach($categorias As $temporal)
                    {
                            $datos[$fila]['cat_codigo'] = $temporal->getCatCodigo();
                            $datos[$fila]['cat_nombre'] = $temporal->getCatNombre();
                            $fila++;
                    }

                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
                    }
            }catch (Exception $excepcion)
            {
                    $salida='exception en listar Categorias';
            }
            return $this->renderText($salida);
    }
}
