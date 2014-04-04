<?php

/**
 * reporte_proyectos actions.
 *
 * @package    segaproyectos
 * @subpackage reporte_proyectos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_proyectosActions extends sfActions
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
            $datos = array();

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');

                    $conexion = new Criteria();
                    
                    if($request->getParameter('codigo_pers') != '-1') {
                        $conexion->add(ProyectoPeer::PRO_PERS_CODIGO, $request->getParameter('codigo_pers'));
                    }
                    
                    if($request->getParameter('codigo_est_proy') != '-1') {
                        $conexion->add(ProyectoPeer::PRO_EST_CODIGO, $request->getParameter('codigo_est_proy'));
                    }
                    
                    //Proyectos del coordinador activo en la sesión
                    $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
                    if($codigo_usuario != 1) {
                        $conexion->add(ProyectoPeer::PRO_PERS_CODIGO, $codigo_usuario);
                    }
                    
                    $conexion->add(ProyectoPeer::PRO_ELIMINADO, 0);
                    $proyectos_cantidad = ProyectoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conexion->addAscendingOrderByColumn(ProyectoPeer::PRO_NOMBRE);
                    $proyecto = ProyectoPeer::doSelect($conexion);

                    foreach($proyecto as $temporal)
                    {
                            $datos[$fila]['pro_nombre']=$temporal->getProNombre();
                            $datos[$fila]['pro_codigo_contable']=$temporal->getProCodigoContable();                            
                                          
                            $persona = PersonaPeer::retrieveByPK($temporal->getProPersCodigo());
                            $datos[$fila]['pro_coord_persona_nombre'] = $persona->getPersNombres().' '.$persona->getPersApellidos();
                            
                            $estado = EstadoproyectoPeer::retrieveByPK($temporal->getProEstCodigo());
                            $datos[$fila]['pro_estado_nombre'] = $estado->getEstProNombre();
                            
                            $datos[$fila]['pro_acumulado_ingresos']=$temporal->getProAcumuladoIngresos();
                            $datos[$fila]['pro_acumulado_egresos']=$temporal->getProAcumuladoEgresos();
                            $datos[$fila]['pro_disponible']=($temporal->getProAcumuladoIngresos()-$temporal->getProAcumuladoEgresos());
                            $datos[$fila]['pro_descripcion']=$temporal->getProDescripcion();
                            $datos[$fila]['pro_valor']=$temporal->getProValor();
                            $datos[$fila]['pro_fecha_inicio']=$temporal->getProFechaInicio();
                            $datos[$fila]['pro_fecha_fin']=$temporal->getProFechaFin();
                            $datos[$fila]['pro_observaciones']=$temporal->getProObservaciones();
                            $datos[$fila]['pro_presupuesto_url']=$temporal->getProPresupuestoUrl();                            
                            $datos[$fila]['pro_fecha_registro']=$temporal->getProFechaRegistro();                            
                            
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
    
    
    public function executeListarCoordinador() {
            $result = array();
            $data = array();
            
            $criteria = new Criteria();
            $criteria->add(PersonaPeer::PERS_ELIMINADO, 0);
            $criteria->addAscendingOrderByColumn(PersonaPeer::PERS_NOMBRES);
            //Nombre del coordinador activo en la sesión
            $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
            if($codigo_usuario != 1) {
                $criteria->add(PersonaPeer::PERS_CODIGO, $codigo_usuario);
            }
            $personas = PersonaPeer::doSelect($criteria);
            
            foreach ($personas as $temporal) {
                $fields = array();
                $fields['coord_codigo'] = $temporal->getPersCodigo();
                $fields['coord_nombre'] = $temporal->getPersNombres().' '.$temporal->getPersApellidos();
                $data[] = $fields;
            }

            $result['data'] = $data;
            return $this->renderText(json_encode($result));
    }
    
    
    public function executeListarEstadoProyecto() {
            $result = array();
            $data = array();
            
            $criteria = new Criteria();
            $criteria->addAscendingOrderByColumn(EstadoproyectoPeer::EST_PRO_NOMBRE);
            $estados = EstadoproyectoPeer::doSelect($criteria);
            
            foreach ($estados as $temporal) {
                $fields = array();
                $fields['est_proy_codigo'] = $temporal->getEstProCodigo();
                $fields['est_proy_nombre'] = $temporal->getEstProNombre();
                $data[] = $fields;
            }

            $result['data'] = $data;
            return $this->renderText(json_encode($result));
    }
    
    
    public function executeExportarReporte(sfWebRequest $request)
    {
            require_once("/tcpdf/config/lang/eng.php");
            require_once("/tcpdf/tcpdf.php");
            
            //Create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            //Set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Cinara');
            $pdf->SetTitle('Proyectos');
            $pdf->SetSubject('Proyectos');
            //Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            //Set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            //Set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            //Set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            //Set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            //Set some language-dependent strings
            $pdf->setLanguageArray($l);
            //Set font
            $pdf->SetFont('helvetica','', 11);
            //Add a page
            $pdf->AddPage('P','LETTER');            
            
            $html = '<font style="text-align:center" size="12"><b>INFORMACIÓN DE PROYECTOS</b></font><br/>';
            $conexion = new Criteria();                    
            if($request->getParameter('codigo_pers') != '-1') {
                $conexion->add(ProyectoPeer::PRO_PERS_CODIGO, $request->getParameter('codigo_pers'));
                $persona = PersonaPeer::retrieveByPK($request->getParameter('codigo_pers'));
                $html .= '<br/><b>COORDINADOR: '.strtoupper($persona->getPersNombres()).' '.strtoupper($persona->getPersApellidos()).'</b>';
            }
            if($request->getParameter('codigo_est_proy') != '-1') {
                $conexion->add(ProyectoPeer::PRO_EST_CODIGO, $request->getParameter('codigo_est_proy'));
                $estado = EstadoproyectoPeer::retrieveByPK($request->getParameter('codigo_est_proy'));
                $html .= '<br/><b>ESTADO DEL PROYECTO: '.strtoupper($estado->getEstProNombre()).'</b>';
            }
            $conexion->add(ProyectoPeer::PRO_ELIMINADO, 0);            
            $conexion->addAscendingOrderByColumn(ProyectoPeer::PRO_NOMBRE);
            //Proyectos del coordinador activo en la sesión
            $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
            if($codigo_usuario != 1) {
                $conexion->add(ProyectoPeer::PRO_PERS_CODIGO, $codigo_usuario);
            }
            $proyecto = ProyectoPeer::doSelect($conexion);
            $html .= '<br/><br/>';
            
            foreach($proyecto as $temporal)
            {
                    $html .= '<font style="text-align:justify"><b>Nombre del Proyecto:</b> ';
                    $html .= $temporal->getProNombre();
                    
                    $html .= '<br/><b>Centro de Costo:</b> ';
                    $html .= $temporal->getProCodigoContable();                            

                    $html .= '<br/><b>Coordinador del Proyecto:</b> ';
                    $persona = PersonaPeer::retrieveByPK($temporal->getProPersCodigo());
                    $html .= $persona->getPersNombres().' '.$persona->getPersApellidos();

                    $html .= '<br/><b>Estado del Proyecto:</b> ';
                    $estado = EstadoproyectoPeer::retrieveByPK($temporal->getProEstCodigo());
                    $html .= $estado->getEstProNombre();

                    $html .= '<br/><b>Valor del Proyecto:</b> ';
                    $html .= $temporal->getProValor();
                    
                    $html .= '<br/><b>Ingresos Acumulados:</b> ';
                    $html .= $temporal->getProAcumuladoIngresos();
                    
                    $html .= '<br/><b>Egresos Acumulados:</b> ';                    
                    $html .= $temporal->getProAcumuladoEgresos();
                    
                    $html .= '<br/><b>Total Disponible:</b> ';
                    $html .= ($temporal->getProAcumuladoIngresos()-$temporal->getProAcumuladoEgresos());
                    
                    $html .= '<br/><b>Descripción del Proyecto:</b> ';
                    $html .= $temporal->getProDescripcion();
                    
                    $html .= '<br/><b>Fecha de Inicio:</b> ';
                    $html .= $temporal->getProFechaInicio();
                    
                    $html .= '<br/><b>Fecha de Finalización:</b> ';
                    $html .= $temporal->getProFechaFin();
                    
                    $html .= '<br/><b>Observaciones:</b> ';
                    $html .= $temporal->getProObservaciones().'<br/><br/></font>';
            }            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            //Close and output PDF document
            $doc = $pdf->Output('Reporte.pdf', 'F');
            $pdf->Output($doc);
    }
}
