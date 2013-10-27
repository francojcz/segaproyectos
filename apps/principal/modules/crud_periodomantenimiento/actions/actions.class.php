<?php

/**
 * crud_periodomantenimiento actions.
 *
 * @package    tpmlabs
 * @subpackage crud_periodomantenimiento
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class crud_periodomantenimientoActions extends sfActions
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
  
    /*Esta funcion que crea y actualiza un periodo*/
    public function executeActualizarPeriodoMantenimiento(sfWebRequest $request)
    {
        $salida = '';

        try{
                $pmto_codigo = $this->getRequestParameter('pmto_codigo');
                $periodo = null;

                if($pmto_codigo!=''){
                        $periodo  = PeriodoMantenimientoPeer::retrieveByPk($pmto_codigo);

                        if($periodo->getPmtoEliminado()) {
                                $salida = "({success: false, errors: { reason: 'No es posible modificar un periodo de mantenimiento que ha sido eliminado'}})";
                                return $this->renderText($salida);
                        }
                }
                else
                {
                        $periodo = new PeriodoMantenimiento();
                        $periodo->setPmtoFechaRegistroSistema(time());
                        $periodo->setPmtoUsuCrea($this->getUser()->getAttribute('usu_codigo'));
                        $periodo->setPmtoEliminado(0);
                }

                if($periodo)
                {
                    $periodo->setPmtoPeriodo($this->getRequestParameter('pmto_periodo'));
                    $periodo->setPmtoTipo($this->getRequestParameter('pmto_tipo'));                    
                    $periodo->setPmtoFechaActualizacion(time());
                    $periodo->setPmtoUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
                    $periodo->setPmtoCausaActualizacion($this->getRequestParameter('pmto_causa_actualizacion'));

                    $periodo->save();

                    $salida = "({success: true, mensaje:'El periodo fue registrado exitosamente'})";
                }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en periodo ".$excepcion."'}})";
            }
        return $this->renderText($salida);
    }
    
    /*Esta funcion retorna un listado de periodos de mantenimiento*/
    public function executeListarPeriodoMantenimiento(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $pmto_numero = $this->getRequestParameter('pmto_numero');
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');
                    $pmto_eliminado = $this->getRequestParameter('pmto_eliminado');//los de mostrar
                    if($pmto_eliminado == ''){
                            $pmto_eliminado=0;
                    }

                    $conexion = new Criteria();
                    $conexion->add(PeriodoMantenimientoPeer::PMTO_ELIMINADO,$pmto_eliminado);
                    if($pmto_numero){
                            $conexion->add(PeriodoMantenimientoPeer::PMTO_NUMERO,$pmto_numero);
                    }

                    $tipoperiodo_cantidad = PeriodoMantenimientoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $periodo = PeriodoMantenimientoPeer::doSelect($conexion);

                    foreach($periodo as $temporal)
                    {
                            $datos[$fila]['pmto_codigo']=$temporal->getPmtoCodigo();
                            $datos[$fila]['pmto_periodo'] = $temporal->getPmtoPeriodo();
                            $datos[$fila]['pmto_tipo'] = $temporal->getPmtoTipo();                            
                            $datos[$fila]['pmto_fecha_registro_sistema'] = $temporal->getPmtoFechaRegistroSistema();
                            $datos[$fila]['pmto_fecha_actualizacion'] = $temporal->getPmtoFechaActualizacion();                            
                            $datos[$fila]['pmto_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getPmtoUsuCrea());
                            $datos[$fila]['pmto_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getPmtoUsuActualiza());
                            $datos[$fila]['pmto_eliminado'] = $temporal->getPmtoEliminado();
                            $datos[$fila]['pmto_causa_eliminacion'] = $temporal->getPmtoCausaEliminacion();
                            $datos[$fila]['pmto_causa_actualizacion'] = $temporal->getPmtoCausaActualizacion();                            

                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$tipoperiodo_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en periodo ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
        
    /*Esta funcion elimina un periodo*/
    public function executeEliminarPeriodoMantenimiento(sfWebRequest $request)
    {
            $salida = '';

            try{
                    $pmto_codigo = $this->getRequestParameter('pmto_codigo');
                    $causa_eliminacion= $this->getRequestParameter('pmto_causa_eliminacion');

                    if($pmto_codigo != ''){

                            $periodo_mto  = PeriodoMantenimientoPeer::retrieveByPk($pmto_codigo);         
                            if($periodo_mto){
                                    $periodo_mto->setPmtoUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
                                    $periodo_mto->setPmtoFechaActualizacion(time());
                                    $periodo_mto->setPmtoEliminado(1);
                                    $periodo_mto->setPmtoCausaEliminacion($causa_eliminacion);
                                    $periodo_mto->save();
                                    $salida = "({success: true, mensaje:'El periodo fue eliminado exitosamente'})";
                            }                            
                    }
                    else
                    {
                            $salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n periodo'}})";
                    }

            }
            catch (Exception $excepcion)
            {
                $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en periodo al tratar de eliminar ',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    /*Este metodo permite restablecer un objeto eliminado*/
    public function executeRestablecerPeriodoMantenimiento()
    {
            $salida = "({success: false, errors: { reason: 'No se pudo restablecer el periodo'}})";
            try{
                    $pmto_codigo = $this->getRequestParameter('pmto_codigo');
                    $causa_reestablece= $this->getRequestParameter('pmto_causa_restablece');

                    $periodo  = PeriodoMantenimientoPeer::retrieveByPk($pmto_codigo);

                    if($periodo)
                    {
                            $periodo->setPmtoUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo reestablece
                            $periodo->setPmtoFechaActualizacion(time());
                            $periodo->setPmtoEliminado(0);
                            $periodo->setPmtoCausaActualizacion($causa_reestablece);
                            $periodo->save();
                            $salida = "({success: true, mensaje:'El periodo fue restablecido exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
            }
            return $this->renderText($salida);
    }
    
    public function executeListarTipoPeriodo(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{

                    $conexion = new Criteria();
                    $tipo_periodo = TipoPeriodoPeer::doSelect($conexion);

                    foreach($tipo_periodo As $temporal)
                    {
                            $datos[$fila]['tp_codigo'] = $temporal->getTpCodigo();
                            $datos[$fila]['tp_nombre'] = $temporal->getTpNombre();
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
