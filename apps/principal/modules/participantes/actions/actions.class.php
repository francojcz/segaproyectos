<?php

/**
 * participantes actions.
 *
 * @package    segaproyectos
 * @subpackage participantes
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class participantesActions extends sfActions
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
    
    
    public function executeListarPersonasPorProyecto(sfWebRequest $request )
    {
            $pro_codigo = $this->getRequestParameter('pro_codigo');

            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $conexion = new Criteria();
                    $conexion->addJoin(ParticipantePeer::PAR_PERS_CODIGO, PersonaPeer::PERS_CODIGO);
                    $conexion->add(ParticipantePeer::PAR_PRO_CODIGO, $pro_codigo );	
                    $conexion->add(PersonaPeer::PERS_ELIMINADO, 0);
                    $conexion->addAscendingOrderByColumn(PersonaPeer::PERS_NOMBRES);
                    $personas = PersonaPeer::doSelect($conexion);

                    foreach($personas as $temporal)
                    {
                            $datos[$fila]['pro_per_codigo'] = $temporal->getPersCodigo();
                            $datos[$fila]['pro_per_nombre'] = $temporal->getPersNombres().' '.$temporal->getPersApellidos();
                            
                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n al listar Personas por Proyecto',error:'".$excepcion->getMessage()."'}})";
            }
            return $this->renderText($salida);
    }
    
    
    public function executeListarPersona(sfWebRequest $request )
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $conexion = new Criteria();
                    $conexion->add(PersonaPeer::PERS_ELIMINADO, 0);	
                    $personas = PersonaPeer::doSelect($conexion);

                    foreach($personas as $temporal)
                    {
                            $datos[$fila]['per_codigo'] = $temporal->getPersCodigo();
                            $datos[$fila]['per_nombre'] = $temporal->getPersNombres().' '.$temporal->getPersApellidos();

                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n al listar Personas',error:'".$excepcion->getMessage()."'}})";
            }
            return $this->renderText($salida);
    }
    
    
    public function executeGuardarPersonaPorProyecto(sfWebRequest $request)
    {
            $salida = "({success: true, mensaje:'La asignaci&oacute;n de persona a proyecto no fue realizada'})";
            
            $user = $this -> getUser();  
            $codigo_usuario = $user -> getAttribute('usu_codigo');
            try{
                    $pro_codigo = $this->getRequestParameter('pro_codigo');
                    $per_codigo = $this->getRequestParameter('per_codigo');

                    $personaporproyecto;

                    if($pro_codigo!='' && $per_codigo!=''){

                            $conexion = new Criteria();
                            $conexion->add(ParticipantePeer::PAR_PERS_CODIGO ,$per_codigo);
                            $conexion->addAnd(ParticipantePeer::PAR_PRO_CODIGO , $pro_codigo );	
                            $personaporproyecto = ParticipantePeer::doSelect($conexion);

                            if($personaporproyecto)
                            {
                                    $salida = "({success: true, mensaje:'La persona ya hab&iacute;a sido asignada al proyecto'})";
                            }
                            else
                            {
                                    $personaporproyecto = new Participante();
                                    $personaporproyecto->setParProCodigo($pro_codigo);
                                    $personaporproyecto->setParPersCodigo($per_codigo);
                                    $personaporproyecto->setParUsuCodigo($codigo_usuario);
                                    $personaporproyecto->setParFechaRegistro(date('Y-m-d H:i:s'));
                                    $personaporproyecto->save();

                                    $salida = "({success: true, mensaje:'La asignaci&oacute;n de persona a proyecto fue realizada exitosamente'})";
                            }

                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en participante',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeEliminarPersonaPorProyecto(sfWebRequest $request)
    {
            $salida = "({success: true, mensaje:'La asignaci&oacute;n de persona a proyecto no fue eliminada'})";
            
            try{
                    $pro_codigo = $this->getRequestParameter('pro_codigo');
                    $temp=$this->getRequestParameter('perss_codigos');
                    $perss_codigos = json_decode($temp);

                    if($pro_codigo!='' ){
                            foreach ($perss_codigos as $per_codigo)
                            {
                                    $conexion = new Criteria();
                                    $conexion->add(ParticipantePeer::PAR_PERS_CODIGO ,$per_codigo );
                                    $conexion->addAnd(ParticipantePeer::PAR_PRO_CODIGO , $pro_codigo );	
                                    $personaporproyecto = ParticipantePeer::doSelectOne($conexion);

                                    if($personaporproyecto)
                                    {
                                            $personaporproyecto->delete();
                                            $salida = "({success: true, mensaje:'La persona ha sido eliminada exitosamente del proyecto'})";
                                    }
                                    
                                    $criteria = new Criteria();
                                    $criteria -> add(AsignaciondetiempoPeer::ADT_PERS_CODIGO, $per_codigo);
                                    $criteria -> add(AsignaciondetiempoPeer::ADT_PRO_CODIGO, $pro_codigo);                                    
                                    $asignaciones = AsignaciondetiempoPeer::doSelect($criteria);
                                    foreach ($asignaciones as $asignacion) {
                                        $asignacion->delete();
                                    }                                    
                            }
                    }
            }
            catch (Exception $excepcion)
            {
                    $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en participante',error:'".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }    
    
    public function executeListarAsignacionTiempos(sfWebRequest $request)
    {
        $result = array();
        $data = array();
        $fields = array();
        $fila = 0;
        
        $criteria = new Criteria();
        $criteria -> add(AsignaciondetiempoPeer::ADT_PRO_CODIGO, $request -> getParameter('cod_proyecto'));
        $criteria -> add(AsignaciondetiempoPeer::ADT_PERS_CODIGO, $request -> getParameter('cod_persona'));
        $criteria->addAscendingOrderByColumn(AsignaciondetiempoPeer::ADT_MES);
        $registrosAsignacion = AsignaciondetiempoPeer::doSelect($criteria);        
        
        foreach ($registrosAsignacion as $registros) {            
            $fields[$fila]['adt_codigo'] = $registros -> getAdtCodigo();
            $fields[$fila]['adt_mes'] = $registros->getAdtMes();
            $fields[$fila]['adt_ano'] = $registros->getAdtAno();
            $fields[$fila]['adt_asignacion'] = $registros -> getAdtAsignacion();
            $fields[$fila]['adt_cod_persona'] = $registros -> getAdtPersCodigo();
            $fields[$fila]['adt_cod_proyecto'] = $registros -> getAdtProCodigo(); 
            $persona = PersonaPeer::retrieveByPK($registros -> getAdtPersRegCodigo());
            $fields[$fila]['adt_creado_por'] = $persona->getPersNombres().' '.$persona->getPersApellidos();
            $fields[$fila]['adt_fecha_registro'] = $registros -> getAdtFechaRegistro();
            $fila++;
         }
                  
         $proyecto = ProyectoPeer::retrieveByPK($request -> getParameter('cod_proyecto'));
         $fecha_inicio = strtotime($proyecto->getProFechaInicio());
         $fecha_fin = strtotime($proyecto->getProFechaFin());
         $mes_inicio = (int) date('m',$fecha_inicio);
         $ano_inicio = (int) date('Y',$fecha_inicio);
         $mes_fin = (int) date('m',$fecha_fin);
         $ano_fin = (int) date('Y',$fecha_fin);
                  
         for($ano = $ano_inicio; $ano <= $ano_fin; $ano++) {
             if(($ano_fin-$ano)==0) {
                 $mes_fin_temp = $mes_fin;                 
             }
             else {
                 $mes_fin_temp = 12;
             }
             
             if(sizeof($data) == 0) {
                 $mes_inicio_temp = $mes_inicio;
             }
             else {
                 $mes_inicio_temp = 1;
             }
             if(sizeof($data) == 0 && ($ano_fin-$ano_inicio)==0) {
                 $mes_inicio_temp = $mes_inicio;
             }
             
             for($mes = $mes_inicio_temp; $mes <= $mes_fin_temp; $mes++) {
                 $var = false;
                 for($i=0; $i<=sizeof($fields); $i++) {
                     if(($fields[$i]['adt_mes'] == $mes) && ($fields[$i]['adt_ano'] == $ano)) {
                         $mes_field = $fields[$i]['adt_mes'];
                         $fields[$i]['adt_mes'] = $this->mes($mes_field);
                         $data[] = $fields[$i];
                         $var = true;
                     }
                 }
                 
                 if (!$var) {
                    $record = array();
                    $record['adt_codigo'] = '-1';
                    $record['adt_mes'] = $this->mes($mes);
                    $record['adt_ano'] = $ano;
                    $record['adt_asignacion'] = '0';
                    $record['adt_cod_persona'] = $request -> getParameter('cod_persona');
                    $record['adt_cod_proyecto'] = $request -> getParameter('cod_proyecto'); 
                    $record['adt_creado_por'] = '';
                    $record['adt_fecha_registro'] = '';
                    $data[] = $record;                     
                 }
             }             
        }
        
        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }
    
    public function executeModificarAsignacionTiempos(sfWebRequest $request)
    {
        $user = $this -> getUser();  
        $codigo_usuario = $user -> getAttribute('usu_codigo');
        
        if ($request -> hasParameter('adt_codigo')) {
            if($request -> getParameter('adt_codigo') == '-1'){
                $asignacion = new Asignaciondetiempo();
                $asignacion->setAdtMes($request->getParameter('adt_mes'));
                $asignacion->setAdtAno($request->getParameter('adt_ano'));
                $asignacion->setAdtAsignacion($request->getParameter('adt_asignacion'));
                $asignacion->setAdtPersCodigo($request->getParameter('adt_persona'));
                $asignacion->setAdtProCodigo($request->getParameter('adt_proyecto'));
                $asignacion->setAdtPersRegCodigo($codigo_usuario);
                $asignacion->setAdtFechaRegistro(date('Y-m-d H:i:s'));
                $asignacion->save();
            }
            else {
                $registro = AsignaciondetiempoPeer::retrieveByPK($request -> getParameter('adt_codigo'));                       
                if ($request -> hasParameter('adt_asignacion')) {
                    $registro->setAdtAsignacion($request -> getParameter('adt_asignacion')); 
                    $registro->setAdtPersRegCodigo($codigo_usuario);
                    $registro->setAdtFechaRegistro(date('Y-m-d H:i:s'));
                }            
                $registro -> save();                
            }            
        }
    }
    
    public function mes($mes) {
        if($mes == 1) { return 'Enero'; }
        if($mes == 2) { return 'Febrero'; }
        if($mes == 3) { return 'Marzo'; }
        if($mes == 4) { return 'Abril'; }
        if($mes == 5) { return 'Mayo'; }
        if($mes == 6) { return 'Junio'; }
        if($mes == 7) { return 'Julio'; }
        if($mes == 8) { return 'Agosto'; }
        if($mes == 9) { return 'Septiembre'; }
        if($mes == 10) { return 'Octubre'; }
        if($mes == 11) { return 'Noviembre'; }
        if($mes == 12) { return 'Diciembre'; }
    }
}
