<?php

/**
 * ingresos actions.
 *
 * @package    segaproyectos
 * @subpackage ingresos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ingresosActions extends sfActions
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
  
    public function executeActualizarIngreso(sfWebRequest $request)
    {
            try{
                    $ing_codigo = $this->getRequestParameter('ing_codigo');
                    $ingreso;

                    if($ing_codigo!=''){
                            $ingreso  = IngresoPeer::retrieveByPk($ing_codigo);
                    }
                    else
                    {
                            $ingreso = new Ingreso();
                            $ingreso->setIngFechaRegistro(time());
                            $ingreso->setIngEliminado(0); 
                            $ingreso->setIngUsuCodigo($this->getUser()->getAttribute('usu_codigo'));
                            $ingreso->setIngValor($this->getRequestParameter('ing_valor'));
                            
                            //Actualizar el valor de los acumulados
                            $conexion = new Criteria();
                            $conexion->add(ProyectoPeer::PRO_ELIMINADO, 0);
                            $conexion->add(ProyectoPeer::PRO_CODIGO, $this->getRequestParameter('ing_proyecto'));
                            $proyecto = ProyectoPeer::doSelectOne($conexion);
                            $ingresos = $proyecto->getProAcumuladoIngresos();
                            $proyecto->setProAcumuladoIngresos($ingresos+$this->getRequestParameter('ing_valor'));
                            $proyecto->save();
                            
                            //Eliminar alarma proyecto
                            $criteria = new Criteria();
                            $criteria->add(AlarmaPeer::ALA_CONCEPTO, 'Presupuesto de Proyecto');
                            $criteria->add(AlarmaPeer::ALA_CON_CODIGO, $this->getRequestParameter('ing_proyecto'));
                            $registro = AlarmaPeer::doSelectOne($criteria);
                            $count_r = AlarmaPeer::doCount($criteria);
                            if($count_r == 1) {
                                $registro->delete();
                            }
                    }

                    if($ingreso)
                    {
                            $ingreso->setIngConcepto($this->getRequestParameter('ing_concepto'));                            
                            $ingreso->setIngFecha($this->getRequestParameter('ing_fecha'));
                            $ingreso->setIngProCodigo($this->getRequestParameter('ing_proyecto'));
                            $ingreso->save();
                                                        
                            $salida = "({success: true, mensaje:'El ingreso fue registrado exitosamente'})";
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en ingreso',error:'".$excepcion."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeListarIngreso(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');
                    $ing_eliminado=$this->getRequestParameter('ing_eliminado');//los de mostrar
                    if($ing_eliminado==''){
                            $ing_eliminado=0;
                    }

                    $conexion = new Criteria();
                    $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
                    if($codigo_usuario != 1) {
                        $criteria = new Criteria();
                        $criteria->add(ProyectoPeer:: PRO_PERS_CODIGO, $codigo_usuario);
                        $proyectos = ProyectoPeer::doSelect($criteria);
                        foreach ($proyectos as $proyecto) {
                            $conexion->addOr(IngresoPeer::ING_PRO_CODIGO, $proyecto->getProCodigo());
                        }
                    }   
                    $conexion->add(IngresoPeer::ING_ELIMINADO,$ing_eliminado);
                    $ingreso_cantidad = IngresoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conexion->addAscendingOrderByColumn(IngresoPeer::ING_CODIGO);
                    $ingresos = IngresoPeer::doSelect($conexion);

                    foreach($ingresos as $temporal)
                    {
                            $datos[$fila]['ing_codigo']=$temporal->getIngCodigo();
                            $datos[$fila]['ing_concepto']=$temporal->getIngConcepto();
                            $datos[$fila]['ing_valor']=$temporal->getIngValor();
                            $datos[$fila]['ing_fecha']=$temporal->getIngFecha();
                            $datos[$fila]['ing_fecha_registro'] = $temporal->getIngFechaRegistro();                          
                            $datos[$fila]['ing_eliminado'] = $temporal->getIngEliminado();
                                  
                            $datos[$fila]['ing_usuario'] = $temporal->getIngUsuCodigo();
                            $persona = PersonaPeer::retrieveByPK($temporal->getIngUsuCodigo());
                            $datos[$fila]['ing_usuario_nombre'] = $persona->getPersNombres().' '.$persona->getPersApellidos();                            
                                                        
                            $datos[$fila]['ing_proyecto'] = $temporal->getIngProCodigo();
                            $proyecto = ProyectoPeer::retrieveByPK($temporal->getIngProCodigo());
                            $datos[$fila]['ing_proyecto_nombre'] = $proyecto->getProNombre();
                            $datos[$fila]['ing_acumulado_ingresos'] = $proyecto->getProAcumuladoIngresos();
                            $datos[$fila]['ing_acumulado_egresos'] = $proyecto->getProAcumuladoEgresos();
                            $datos[$fila]['ing_disponible'] = $proyecto->getProAcumuladoIngresos()-$proyecto->getProAcumuladoEgresos();

                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$ingreso_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en ingreso ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeEliminarIngreso(sfWebRequest $request)
    {
            $salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n ingreso'}})";

            try{
                    $ing_codigo = $this->getRequestParameter('ing_codigo');

                    if($ing_codigo!=''){                          
                            $ingreso = IngresoPeer::retrieveByPk($ing_codigo);
                            $ingreso->setIngEliminado(1);
                            $ingreso->save();
                            $salida = "({success: true, mensaje:'El ingreso fue eliminado exitosamente'})";                             
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en ingreso al tratar de eliminar ',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeRestablecerIngreso()
    {
            $salida = "({success: false, errors: { reason: 'No se pudo eliminar el ingreso'}})";
            try{
                    $ing_codigo = $this->getRequestParameter('ing_codigo');
                    $ingreso = IngresoPeer::retrieveByPk($ing_codigo);

                    if($ingreso)
                    {                            
                            $ingreso->setIngEliminado(0);
                            $ingreso->save();
                            $salida = "({success: true, mensaje:'El ingreso fue restablecido exitosamente'})";
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
                            $datos[$fila]['pro_ing_codigo'] = $temporal->getProCodigo();
                            $datos[$fila]['pro_ing_nombre'] = $temporal->getProNombre();

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
                            $datos[$fila]['con_ing_codigo'] = $temporal->getConCodigo();
                            $datos[$fila]['con_ing_nombre'] = $temporal->getConNombre();
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
    
    public function executeListarConceptosIngreso(sfWebRequest $request)
    {
        $criteria = new Criteria();
        if ($request -> hasParameter('cod_ingreso'))
            $criteria -> add(ConceptosingresoPeer::CSI_ING_CODIGO, $request->getParameter('cod_ingreso'));

        $registros = ConceptosingresoPeer::doSelect($criteria);

        $result = array();
        $data = array();
        foreach ($registros as $temporal)
        {
            $fields = array();
            $fields['codigo'] = $temporal->getCsiCodigo();
            $fields['valor'] = $temporal->getCsiValor();
            $fields['fecha_registro'] = $temporal->getCsiFecha();
            $persona = PersonaPeer::retrieveByPK($temporal->getCsiUsuCodigo());
            $fields['usu_registra'] = $persona->getPersNombres().' '.$persona->getPersApellidos();
            $concepto = ConceptoPeer::retrieveByPK($temporal->getCsiConCodigo());
            $fields['concepto'] = $concepto->getConNombre();
            $data[] = $fields;
        }
        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }
    
    
    public function executeRegistrarConcepto(sfWebRequest $request)
    {
        $user = $this -> getUser();           
        $codigo_usuario = $user -> getAttribute('usu_codigo');

        $registro_csii = '';
        $criteria = new Criteria();
        $criteria -> add(ConceptosingresoPeer::CSI_CON_CODIGO, $request->getParameter('id_concepto'));
        $criteria -> add(ConceptosingresoPeer::CSI_ING_CODIGO, $request->getParameter('id_ingreso'));
        $registro_csii += ConceptosingresoPeer::doSelectOne($criteria);

        if($registro_csii == ''){
            $registro = new Conceptosingreso();
            $registro->setCsiValor($request->getParameter('valor'));
            $registro->setCsiFecha($request->getParameter('fecha'));
            $registro->setCsiUsuCodigo($codigo_usuario);
            $registro->setCsiConCodigo($request->getParameter('id_concepto'));
            $registro->setCsiIngCodigo($request->getParameter('id_ingreso'));
            $registro->setCsiProCodigo($request->getParameter('id_proyecto'));
            $registro -> save();
            return $this -> renderText('Ok');
        }
        else
            return $this -> renderText('1');
    }
    
    public function executeEliminarConcepto(sfWebRequest $request)
        {        
            if ($request -> hasParameter('codigo'))
            {
                $registro = ConceptosingresoPeer::retrieveByPK($request->getParameter('codigo'));
                $registro ->delete();
            }
            return $this -> renderText('Ok');
        }
}