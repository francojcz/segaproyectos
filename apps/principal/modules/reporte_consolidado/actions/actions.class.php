<?php

/**
 * reporte_consolidado actions.
 *
 * @package    segaproyectos
 * @subpackage reporte_consolidado
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_consolidadoActions extends sfActions
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
  
    public function executeListarConceptosIngreso(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos = array();
            $total_ingresos = 0;
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
                    $conceptos_cantidad = ConceptoPeer::doCount($conexion);
                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conceptos = ConceptoPeer::doSelect($conexion);
                    $ano = $request->getParameter('ano');
                    $mes = $request->getParameter('mes');

                    foreach($conceptos as $temporal)
                    {
                            $criteria_ing = new Criteria();
                            $criteria_ing->add(ConceptosingresoPeer::CSI_CON_CODIGO, $temporal->getConCodigo());                            
                            if($request->getParameter('codigo_proy') != '-1') {                                
                                $criteria_ing->add(ConceptosingresoPeer::CSI_PRO_CODIGO, $request->getParameter('codigo_proy'));
                            }                            
                            if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') == '-1') {
                                $criteria_ing->add(ConceptosingresoPeer::CSI_FECHA, $ano.'-01-01', Criteria::GREATER_EQUAL);
                                $criteria_ing->addAnd(ConceptosingresoPeer::CSI_FECHA, $ano.'-12-31', Criteria::LESS_EQUAL);
                            }
                            if($request->getParameter('ano') == 'TODOS' && $request->getParameter('mes') != '-1') {
                                $criteria_ing->add(ConceptosingresoPeer::CSI_FECHA, $anoactual.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                                $criteria_ing->addAnd(ConceptosingresoPeer::CSI_FECHA, $anoactual.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
                            }
                            if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') != '-1') {
                                $criteria_ing->add(ConceptosingresoPeer::CSI_FECHA, $ano.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                                $criteria_ing->addAnd(ConceptosingresoPeer::CSI_FECHA, $ano.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
                            }
                            
                            $ingresos = ConceptosingresoPeer::doSelect($criteria_ing);
                            $valor_ingresos = 0;
                            foreach ($ingresos as $ingreso) {
                                $valor_ingresos += $ingreso->getCsiValor();
                            }
                            
                            $criteria_egr = new Criteria();
                            $criteria_egr->add(EgresoPeer::EGR_CON_CODIGO, $temporal->getConCodigo());
                            $criteria_egr->add(EgresoPeer::EGR_ELIMINADO, 0);                            
                            if($request->getParameter('codigo_proy') != '-1') {
                                $criteria_egr->add(EgresoPeer::EGR_PRO_CODIGO, $request->getParameter('codigo_proy'));
                            }
                            if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') == '-1') {
                                $criteria_egr->add(EgresoPeer::EGR_FECHA, $ano.'-01-01', Criteria::GREATER_EQUAL);
                                $criteria_egr->addAnd(EgresoPeer::EGR_FECHA, $ano.'-12-31', Criteria::LESS_EQUAL);
                            }
                            if($request->getParameter('ano') == 'TODOS' && $request->getParameter('mes') != '-1') {
                                $criteria_egr->add(EgresoPeer::EGR_FECHA, $anoactual.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                                $criteria_egr->addAnd(EgresoPeer::EGR_FECHA, $anoactual.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
                            }
                            if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') != '-1') {
                                $criteria_egr->add(EgresoPeer::EGR_FECHA, $ano.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                                $criteria_egr->addAnd(EgresoPeer::EGR_FECHA, $ano.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
                            }
                            $egresos = EgresoPeer::doSelect($criteria_egr);
                            $valor_egresos = 0;
                            foreach ($egresos as $egreso) {
                                $valor_egresos += $egreso->getEgrValor();
                            }
                            
                            $datos[$fila]['concepto_nombre'] = $temporal->getConNombre();
                            $datos[$fila]['total_ingresos'] = $valor_ingresos;
                            $datos[$fila]['total_egresos'] = $valor_egresos;
                            $valor_disponible = $valor_ingresos-$valor_egresos;
                            if($valor_disponible < 0)
                                $datos[$fila]['total_disponible'] = '<a style="color:#FF0000;"><b>'.$valor_disponible.'</b></a>';
                            else
                                $datos[$fila]['total_disponible'] = $valor_disponible;
                            
                            $total_ingresos += $valor_ingresos;
                            $total_egresos += $valor_egresos;
                            
                            $fila++;
                    }
                    if($fila>0){
                            $datos[$fila]['concepto_nombre'] = '<b>Total</b>';
                            $datos[$fila]['total_ingresos'] = '<b>'.$total_ingresos.'</b>';
                            $datos[$fila]['total_egresos'] = '<b>'.$total_egresos.'</b>';
                            $total_disponible = $total_ingresos-$total_egresos;
                            if($total_disponible < 0)
                                $datos[$fila]['total_disponible'] = '<a style="color:#FF0000;"><b>'.$total_disponible.'</b></a>';
                            else
                                $datos[$fila]['total_disponible'] = '<b>'.$total_disponible.'</b>';
                            
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$conceptos_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en consolidado ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeListarProyecto() {
            $result = array();
            $data = array();
            
            $criteria = new Criteria();
            $criteria->add(ProyectoPeer::PRO_ELIMINADO, 0);
            $criteria->addAscendingOrderByColumn(ProyectoPeer::PRO_NOMBRE);
            $proyectos = ProyectoPeer::doSelect($criteria);
            
            foreach ($proyectos as $temporal) {
                $fields = array();
                $fields['proyect_codigo'] = $temporal->getProCodigo();
                $fields['proyect_nombre'] = $temporal->getProNombre();
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
            $pdf->SetFont('helvetica','', 10);
            //Add a page
            $pdf->AddPage('L','LETTER');
            
            $fila=0;
            $datos = array();
            $total_ingresos = 0;
            $total_egresos = 0;
            $dias[1]=$dias[3]=$dias[5]=$dias[7]=$dias[8]=$dias[10]=$dias[12]=31;
            $dias[4]=$dias[6]=$dias[9]=$dias[11]=30;
            $fechaactual = strtotime(date("Y-m-d"));
            $anoactual = (int) date('Y',$fechaactual);
            if(($anoactual%4)==0) { $dias[2] = 29; }
            else { $dias[2] = 28; }             

            $conexion = new Criteria();  
            $conceptos = ConceptoPeer::doSelect($conexion);
            $ano = $request->getParameter('ano');
            $mes = $request->getParameter('mes');
            
            $html ='
            <table style="width:100%" cellspacing="0" cellpadding="1" border="1">
            <tr>
                <td style="background-color:#000000;color:#FFFFFF;" colspan="4" align="center"><b>PRESUPUESTO CONSOLIDADO</b></td>
            </tr>
            <tr>
                <td style="width:40%" align="center"><b>Nombre del Concepto</b></td>
                <td style="width:20%" align="center"><b>Total Ingresos</b></td>
                <td style="width:20%" align="center"><b>Total Egresos</b></td>
                <td style="width:20%" align="center"><b>Total Disponible</b></td>
            </tr>';

            foreach($conceptos as $temporal)
            {
                    $criteria_ing = new Criteria();
                    $criteria_ing->add(ConceptosingresoPeer::CSI_CON_CODIGO, $temporal->getConCodigo());                            
                    if($request->getParameter('codigo_proy') != '-1') {                                
                        $criteria_ing->add(ConceptosingresoPeer::CSI_PRO_CODIGO, $request->getParameter('codigo_proy'));
                    }                            
                    if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') == '-1') {
                        $criteria_ing->add(ConceptosingresoPeer::CSI_FECHA, $ano.'-01-01', Criteria::GREATER_EQUAL);
                        $criteria_ing->addAnd(ConceptosingresoPeer::CSI_FECHA, $ano.'-12-31', Criteria::LESS_EQUAL);
                    }
                    if($request->getParameter('ano') == 'TODOS' && $request->getParameter('mes') != '-1') {
                        $criteria_ing->add(ConceptosingresoPeer::CSI_FECHA, $anoactual.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                        $criteria_ing->addAnd(ConceptosingresoPeer::CSI_FECHA, $anoactual.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
                    }
                    if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') != '-1') {
                        $criteria_ing->add(ConceptosingresoPeer::CSI_FECHA, $ano.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                        $criteria_ing->addAnd(ConceptosingresoPeer::CSI_FECHA, $ano.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
                    }

                    $ingresos = ConceptosingresoPeer::doSelect($criteria_ing);
                    $valor_ingresos = 0;
                    foreach ($ingresos as $ingreso) {
                        $valor_ingresos += $ingreso->getCsiValor();
                    }

                    $criteria_egr = new Criteria();
                    $criteria_egr->add(EgresoPeer::EGR_CON_CODIGO, $temporal->getConCodigo());
                    $criteria_egr->add(EgresoPeer::EGR_ELIMINADO, 0);                            
                    if($request->getParameter('codigo_proy') != '-1') {
                        $criteria_egr->add(EgresoPeer::EGR_PRO_CODIGO, $request->getParameter('codigo_proy'));
                    }
                    if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') == '-1') {
                        $criteria_egr->add(EgresoPeer::EGR_FECHA, $ano.'-01-01', Criteria::GREATER_EQUAL);
                        $criteria_egr->addAnd(EgresoPeer::EGR_FECHA, $ano.'-12-31', Criteria::LESS_EQUAL);
                    }
                    if($request->getParameter('ano') == 'TODOS' && $request->getParameter('mes') != '-1') {
                        $criteria_egr->add(EgresoPeer::EGR_FECHA, $anoactual.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                        $criteria_egr->addAnd(EgresoPeer::EGR_FECHA, $anoactual.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
                    }
                    if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') != '-1') {
                        $criteria_egr->add(EgresoPeer::EGR_FECHA, $ano.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                        $criteria_egr->addAnd(EgresoPeer::EGR_FECHA, $ano.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
                    }
                    $egresos = EgresoPeer::doSelect($criteria_egr);
                    $valor_egresos = 0;
                    foreach ($egresos as $egreso) {
                        $valor_egresos += $egreso->getEgrValor();
                    }
                    
                    $html .=  '<tr>
                        <td align="center">'.$temporal->getConNombre().'</td>
                        <td align="center">'.$valor_ingresos.'</td>
                        <td align="center">'.$valor_egresos.'</td>';
                    
                    $valor_disponible = $valor_ingresos-$valor_egresos;
                    if($valor_disponible < 0)
                        $html .=  '<td align="center"><a style="color:#FF0000;"><b>'.$valor_disponible.'</b></a></td>';
                    else
                        $html .=  '<td align="center"><b>'.$valor_disponible.'</b></td>';                     
                    $html .= '</tr>';

                    $total_ingresos += $valor_ingresos;
                    $total_egresos += $valor_egresos;

                    $fila++;
            }
            if($fila>0){
                    $html .=  '<tr>
                        <td align="center"><b>Total</b></td>
                        <td align="center"><b>'.$total_ingresos.'</b></td>
                        <td align="center"><b>'.$total_egresos.'</b></td>';
                    $total_disponible = $total_ingresos-$total_egresos;
                    if($total_disponible < 0)
                        $html .=  '<td align="center"><a style="color:#FF0000;"><b>'.$total_disponible.'</b></a></td>';
                    else
                        $html .=  '<td align="center"><b>'.$total_disponible.'</b></td>';                     
                    $html .= '</tr>';
            }            
            $html .= '</table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            //Close and output PDF document
            $doc = $pdf->Output('Reporte.pdf', 'F');
            $pdf->Output($doc);
    }
}
