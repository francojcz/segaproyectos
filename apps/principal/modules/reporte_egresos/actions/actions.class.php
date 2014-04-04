<?php

/**
 * reporte_egresos actions.
 *
 * @package    segaproyectos
 * @subpackage reporte_egresos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_egresosActions extends sfActions
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
  
    public function executeListarEgreso(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos = array();
            $total_egresos = 0;
            $dias[1]=$dias[3]=$dias[5]=$dias[7]=$dias[8]=$dias[10]=$dias[12]=31;
            $dias[4]=$dias[6]=$dias[9]=$dias[11]=30;
            $fechaactual = strtotime(date("Y-m-d"));
            $anoactual = (int) date('Y',$fechaactual);
            if(($anoactual%4)==0) { $dias[2] = 29; }
            else { $dias[2] = 28; }

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');

                    $conexion = new Criteria();
                    $conexion->add(EgresoPeer::EGR_ELIMINADO, 0); 
                    $ano = $request->getParameter('ano');
                    $mes = $request->getParameter('mes');
                    
                    if($request->getParameter('codigo_proy') != '-1') {
                        $conexion->add(EgresoPeer::EGR_PRO_CODIGO, $request->getParameter('codigo_proy'));
                    }
                    
                    //Proyectos del coordinador activo en la sesión
                    $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
                    if($codigo_usuario != 1 && $request->getParameter('codigo_proy') == '-1') {
                        $criteria = new Criteria();
                        $criteria->add(ProyectoPeer::PRO_PERS_CODIGO, $codigo_usuario);
                        $proyectos = ProyectoPeer::doSelect($criteria);
                        foreach ($proyectos as $proyecto) {
                            $conexion->addOr(EgresoPeer::EGR_PRO_CODIGO, $proyecto->getProCodigo());
                        }
                    }
                    
                    if($request->getParameter('codigo_con') != '-1') {
                        $conexion->add(EgresoPeer::EGR_CON_CODIGO, $request->getParameter('codigo_con'));
                    }
                    if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') == '-1') {
                        $conexion->add(EgresoPeer::EGR_FECHA, $ano.'-01-01', Criteria::GREATER_EQUAL);
                        $conexion->addAnd(EgresoPeer::EGR_FECHA, $ano.'-12-31', Criteria::LESS_EQUAL);
                    }
                    if($request->getParameter('ano') == 'TODOS' && $request->getParameter('mes') != '-1') {
                        $conexion->add(EgresoPeer::EGR_FECHA, $anoactual.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                        $conexion->addAnd(EgresoPeer::EGR_FECHA, $anoactual.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
                    }
                    if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') != '-1') {
                        $conexion->add(EgresoPeer::EGR_FECHA, $ano.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                        $conexion->addAnd(EgresoPeer::EGR_FECHA, $ano.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
                    }
                    
                    $egresos_cantidad = EgresoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conexion->addAscendingOrderByColumn(EgresoPeer::EGR_FECHA);
                    $egresos = EgresoPeer::doSelect($conexion);

                    foreach($egresos as $temporal)
                    {
                            $concepto = ConceptoPeer::retrieveByPK($temporal->getEgrConCodigo());
                            $datos[$fila]['egr_concepto']=$concepto->getConNombre();
                            
                            $datos[$fila]['egr_valor']=$temporal->getEgrValor();
                            $total_egresos += $temporal->getEgrValor();;
                            $datos[$fila]['egr_fecha']=$temporal->getEgrFecha();
                            $datos[$fila]['egr_fecha_registro']=$temporal->getEgrFechaRegistro();
                            
                            $proyecto = ProyectoPeer::retrieveByPK($temporal->getEgrProCodigo());
                            $datos[$fila]['egr_proyecto']=$proyecto->getProNombre();
                                          
                            $persona = PersonaPeer::retrieveByPK($temporal->getEgrUsuCodigo());
                            $datos[$fila]['egr_usuario'] = $persona->getPersNombres().' '.$persona->getPersApellidos(); 
                            
                            $fila++;
                    }
                    if($fila>0){
                            $datos[$fila]['egr_concepto']='<b>Total Egresos</b>';
                            $datos[$fila]['egr_valor']='<b>'.$total_egresos.'</b>';
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$egresos_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en reporte egresos ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    public function executeListarProyecto() {
            $result = array();
            $data = array();
            
            $criteria = new Criteria();
            $criteria->add(ProyectoPeer::PRO_ELIMINADO, 0);
            $criteria->addAscendingOrderByColumn(ProyectoPeer::PRO_NOMBRE);
            
            //Proyectos del coordinador activo en la sesión
            $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
            if($codigo_usuario != 1) {
                $criteria->addOr(ProyectoPeer::PRO_PERS_CODIGO, $codigo_usuario);
            }
            
            $proyectos = ProyectoPeer::doSelect($criteria);
            
            foreach ($proyectos as $temporal) {
                $fields = array();
                $fields['proyecto_codigo'] = $temporal->getProCodigo();
                $fields['proyecto_nombre'] = $temporal->getProNombre();
                $data[] = $fields;
            }

            $result['data'] = $data;
            return $this->renderText(json_encode($result));
    }    
    
    public function executeListarConcepto() {
            $result = array();
            $data = array();
            
            $criteria = new Criteria();
            $criteria->addAscendingOrderByColumn(ConceptoPeer::CON_NOMBRE);
            $conceptos = ConceptoPeer::doSelect($criteria);
            
            foreach ($conceptos as $temporal) {
                $fields = array();
                $fields['concep_codigo'] = $temporal->getConCodigo();
                $fields['concep_nombre'] = $temporal->getConNombre();
                $data[] = $fields;
            }

            $result['data'] = $data;
            return $this->renderText(json_encode($result));
    }
    
    public function executeListarAno() {
            $result = array();
            $data = array();
            
            $ano = array('2014', '2015', '2016', '2017', '2018', '2019', '2020');
            
            for($i=0; $i<sizeof($ano); $i++) {
                $fields = array();
                $fields['ano'] = $ano[$i];
                $data[] = $fields;
            }
            
            $result['data'] = $data;
            return $this->renderText(json_encode($result));
    }
    
    public function executeListarMes() {
            $result = array();
            $data = array();
            
            $mes = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
            
            for($i=1; $i <= sizeof($mes); $i++) {
                $fields = array();
                $fields['mes_codigo'] = $i;
                $fields['mes_nombre'] = $mes[$i-1];
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
            $pdf->SetFont('helvetica','', 9);
            //Add a page
            $pdf->AddPage('L','LETTER');
            
            $fila=0;
            $total_egresos = 0;
            $dias[1]=$dias[3]=$dias[5]=$dias[7]=$dias[8]=$dias[10]=$dias[12]=31;
            $dias[4]=$dias[6]=$dias[9]=$dias[11]=30;
            $fechaactual = strtotime(date("Y-m-d"));
            $anoactual = (int) date('Y',$fechaactual);
            if(($anoactual%4)==0) { $dias[2] = 29; }
            else { $dias[2] = 28; }             

            $conexion = new Criteria();
            $conexion->add(EgresoPeer::EGR_ELIMINADO, 0); 
            $ano = $request->getParameter('ano');
            $mes = $request->getParameter('mes');
            
            $html = '<font style="text-align:center" size="12"><b>EGRESOS POR PROYECTO</b></font><br/>';
            if($request->getParameter('codigo_proy') != '-1') {
                $proyecto = ProyectoPeer::retrieveByPK($request->getParameter('codigo_proy'));
                $html .= '<br/><b>PROYECTO: '.strtoupper($proyecto->getProNombre()).'</b>';
            }
            if($request->getParameter('codigo_con') != '-1') {
                $concepto = ConceptoPeer::retrieveByPK($request->getParameter('codigo_con'));
                $html .= '<br/><b>CONCEPTO: '.strtoupper($concepto->getConNombre()).'</b>';
            }
            if($ano != 'TODOS') {
                $html .= '<br/><b>AÑO: '.$ano.'</b>';
            }
            if($mes != '-1') {
                $html .= '<br/><b>MES: '.strtoupper($this->mes($mes)).'</b>';
            }
            
            $html .= '<br/><br/>';            
            $html .='
            <table style="width:100%" cellspacing="0" cellpadding="1" border="1">
            <tr>
                <td style="background-color:#000000;color:#FFFFFF;width:30%" align="center"><b>NOMBRE DEL CONCEPTO</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:15%" align="center"><b>VALOR</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:40%" align="center"><b>NOMBRE DEL PROYECTO</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:15%" align="center"><b>FECHA DEL INGRESO</b></td>
            </tr>';

            if($request->getParameter('codigo_proy') != '-1') {
                $conexion->add(EgresoPeer::EGR_PRO_CODIGO, $request->getParameter('codigo_proy'));
            }
            //Proyectos del coordinador activo en la sesión
            $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
            if($codigo_usuario != 1 && $request->getParameter('codigo_proy') == '-1') {
                $criteria = new Criteria();
                $criteria->add(ProyectoPeer::PRO_PERS_CODIGO, $codigo_usuario);
                $proyectos = ProyectoPeer::doSelect($criteria);
                foreach ($proyectos as $proyecto) {
                    $conexion->addOr(EgresoPeer::EGR_PRO_CODIGO, $proyecto->getProCodigo());
                }
            }
            if($request->getParameter('codigo_con') != '-1') {
                $conexion->add(EgresoPeer::EGR_CON_CODIGO, $request->getParameter('codigo_con'));
            }
            if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') == '-1') {
                $conexion->add(EgresoPeer::EGR_FECHA, $ano.'-01-01', Criteria::GREATER_EQUAL);
                $conexion->addAnd(EgresoPeer::EGR_FECHA, $ano.'-12-31', Criteria::LESS_EQUAL);
            }
            if($request->getParameter('ano') == 'TODOS' && $request->getParameter('mes') != '-1') {
                $conexion->add(EgresoPeer::EGR_FECHA, $anoactual.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                $conexion->addAnd(EgresoPeer::EGR_FECHA, $anoactual.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
            }
            if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') != '-1') {
                $conexion->add(EgresoPeer::EGR_FECHA, $ano.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                $conexion->addAnd(EgresoPeer::EGR_FECHA, $ano.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
            }
            $conexion->addAscendingOrderByColumn(EgresoPeer::EGR_FECHA);
            $egresos = EgresoPeer::doSelect($conexion);

            foreach($egresos as $temporal)
            {
                    $concepto = ConceptoPeer::retrieveByPK($temporal->getEgrConCodigo());
                    $total_egresos += $temporal->getEgrValor();
                    $proyecto = ProyectoPeer::retrieveByPK($temporal->getEgrProCodigo());
                    
                    $html .=  '<tr>
                        <td align="center">'.$concepto->getConNombre().'</td>
                        <td align="center">'.$temporal->getEgrValor().'</td>
                        <td align="center">'.$proyecto->getProNombre().'</td>
                        <td align="center">'.$temporal->getEgrFecha().'</td></tr>';
                    $fila++;
            }
            if($fila>0){
                    $html .=  '<tr>
                        <td align="center"><b>Total Egresos</b></td>
                        <td align="center"><b>'.$total_egresos.'</b></td></tr>';
            }   
            $html .= '</table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            //Close and output PDF document
            $doc = $pdf->Output('Reporte.pdf', 'F');
            $pdf->Output($doc);
    }
    
    public function mes($mes) {
        if($mes == 1) { return 'enero'; }
        if($mes == 2) { return 'febrero'; }
        if($mes == 3) { return 'marzo'; }
        if($mes == 4) { return 'abril'; }
        if($mes == 5) { return 'mayo'; }
        if($mes == 6) { return 'junio'; }
        if($mes == 7) { return 'julio'; }
        if($mes == 8) { return 'agosto'; }
        if($mes == 9) { return 'septiembre'; }
        if($mes == 10) { return 'octubre'; }
        if($mes == 11) { return 'noviembre'; }
        if($mes == 12) { return 'diciembre'; }
    }
}