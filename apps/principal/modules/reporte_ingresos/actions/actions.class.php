<?php

/**
 * reporte_ingresos actions.
 *
 * @package    segaproyectos
 * @subpackage reporte_ingresos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_ingresosActions extends sfActions
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
  
    public function executeListarIngreso(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos = array();
            $total_ingresos = 0;
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
                    $ano = $request->getParameter('ano');
                    $mes = $request->getParameter('mes');
                    
                    if($request->getParameter('codigo_proy') != '-1') {
                        $conexion->add(ConceptosingresoPeer::CSI_PRO_CODIGO, $request->getParameter('codigo_proy'));
                    }
                    if($request->getParameter('codigo_con') != '-1') {
                        $conexion->add(ConceptosingresoPeer::CSI_CON_CODIGO, $request->getParameter('codigo_con'));
                    }
                    if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') == '-1') {
                        $conexion->add(ConceptosingresoPeer::CSI_FECHA, $ano.'-01-01', Criteria::GREATER_EQUAL);
                        $conexion->addAnd(ConceptosingresoPeer::CSI_FECHA, $ano.'-12-31', Criteria::LESS_EQUAL);
                    }
                    if($request->getParameter('ano') == 'TODOS' && $request->getParameter('mes') != '-1') {
                        $conexion->add(ConceptosingresoPeer::CSI_FECHA, $anoactual.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                        $conexion->addAnd(ConceptosingresoPeer::CSI_FECHA, $anoactual.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
                    }
                    if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') != '-1') {
                        $conexion->add(ConceptosingresoPeer::CSI_FECHA, $ano.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                        $conexion->addAnd(ConceptosingresoPeer::CSI_FECHA, $ano.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
                    }
                    
                    $ingresos_cantidad = ConceptosingresoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conexion->addAscendingOrderByColumn(ConceptosingresoPeer::CSI_FECHA);
                    $ingresos = ConceptosingresoPeer::doSelect($conexion);

                    foreach($ingresos as $temporal)
                    {
                            $concepto = ConceptoPeer::retrieveByPK($temporal->getCsiConCodigo());
                            $datos[$fila]['csi_concepto']=$concepto->getConNombre();
                            
                            $datos[$fila]['csi_valor']=$temporal->getCsiValor();
                            $total_ingresos += $temporal->getCsiValor();;
                            
                            $ingreso = IngresoPeer::retrieveByPK($temporal->getCsiIngCodigo());
                            $datos[$fila]['csi_ingreso']=$ingreso->getIngConcepto();
                            $datos[$fila]['csi_fecha_registro']=$ingreso->getIngFechaRegistro();
                            $datos[$fila]['csi_fecha_ingreso']=$ingreso->getIngFecha();
                            
                            $proyecto = ProyectoPeer::retrieveByPK($temporal->getCsiProCodigo());
                            $datos[$fila]['csi_proyecto']=$proyecto->getProNombre();
                                          
                            $persona = PersonaPeer::retrieveByPK($temporal->getCsiUsuCodigo());
                            $datos[$fila]['csi_usuario'] = $persona->getPersNombres().' '.$persona->getPersApellidos(); 
                            
                            $fila++;
                    }
                    if($fila>0){
                            $datos[$fila]['csi_concepto']='<b>Total Ingresos</b>';
                            $datos[$fila]['csi_valor']='<b>'.$total_ingresos.'</b>';
                            
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$ingresos_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en reporte ingresos ',error:".$excepcion->getMessage()."'}})";
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
    
    public function executeListarConcepto() {
            $result = array();
            $data = array();
            
            $criteria = new Criteria();
            $criteria->addAscendingOrderByColumn(ConceptoPeer::CON_NOMBRE);
            $conceptos = ConceptoPeer::doSelect($criteria);
            
            foreach ($conceptos as $temporal) {
                $fields = array();
                $fields['conc_codigo'] = $temporal->getConCodigo();
                $fields['conc_nombre'] = $temporal->getConNombre();
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
            $total_ingresos = 0;
            $dias[1]=$dias[3]=$dias[5]=$dias[7]=$dias[8]=$dias[10]=$dias[12]=31;
            $dias[4]=$dias[6]=$dias[9]=$dias[11]=30;
            $fechaactual = strtotime(date("Y-m-d"));
            $anoactual = (int) date('Y',$fechaactual);
            if(($anoactual%4)==0) { $dias[2] = 29; }
            else { $dias[2] = 28; }             

            $conexion = new Criteria();
            $ano = $request->getParameter('ano');
            $mes = $request->getParameter('mes');
            
            $html ='
            <table style="width:100%" cellspacing="0" cellpadding="1" border="1">
            <tr>
                <td style="background-color:#000000;color:#FFFFFF;" colspan="4" align="center"><b>INGRESOS POR PROYECTO</b></td>
            </tr>
            <tr>
                <td style="width:20%" align="center"><b>Nombre del Concepto</b></td>
                <td style="width:15%" align="center"><b>Valor</b></td>
                <td style="width:25%" align="center"><b>Descripición del Ingreso</b></td>
                <td style="width:25%" align="center"><b>Nombre del Proyecto</b></td>
                <td style="width:15%" align="center"><b>Fecha del Ingreso</b></td>
            </tr>';

            if($request->getParameter('codigo_proy') != '-1') {
                $conexion->add(ConceptosingresoPeer::CSI_PRO_CODIGO, $request->getParameter('codigo_proy'));
            }
            if($request->getParameter('codigo_con') != '-1') {
                $conexion->add(ConceptosingresoPeer::CSI_CON_CODIGO, $request->getParameter('codigo_con'));
            }
            if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') == '-1') {
                $conexion->add(ConceptosingresoPeer::CSI_FECHA, $ano.'-01-01', Criteria::GREATER_EQUAL);
                $conexion->addAnd(ConceptosingresoPeer::CSI_FECHA, $ano.'-12-31', Criteria::LESS_EQUAL);
            }
            if($request->getParameter('ano') == 'TODOS' && $request->getParameter('mes') != '-1') {
                $conexion->add(ConceptosingresoPeer::CSI_FECHA, $anoactual.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                $conexion->addAnd(ConceptosingresoPeer::CSI_FECHA, $anoactual.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
            }
            if($request->getParameter('ano') != 'TODOS' && $request->getParameter('mes') != '-1') {
                $conexion->add(ConceptosingresoPeer::CSI_FECHA, $ano.'-'.$mes.'-01', Criteria::GREATER_EQUAL);
                $conexion->addAnd(ConceptosingresoPeer::CSI_FECHA, $ano.'-'.$mes.'-'.$dias[$mes], Criteria::LESS_EQUAL);
            }

            $conexion->addAscendingOrderByColumn(ConceptosingresoPeer::CSI_FECHA);
            $ingresos = ConceptosingresoPeer::doSelect($conexion);

            foreach($ingresos as $temporal)
            {
                    $concepto = ConceptoPeer::retrieveByPK($temporal->getCsiConCodigo());
                    $total_ingresos += $temporal->getCsiValor();
                    $ingreso = IngresoPeer::retrieveByPK($temporal->getCsiIngCodigo());
                    $proyecto = ProyectoPeer::retrieveByPK($temporal->getCsiProCodigo());
                    
                    $html .=  '<tr>
                        <td align="center">'.$concepto->getConNombre().'</td>
                        <td align="center">'.$temporal->getCsiValor().'</td>
                        <td align="center">'.$ingreso->getIngConcepto().'</td>
                        <td align="center">'.$proyecto->getProNombre().'</td>
                        <td align="center">'.$ingreso->getIngFecha().'</td></tr>';
                    $fila++;
            }
            if($fila>0){
                    $html .=  '<tr>
                        <td align="center"><b>Total Ingresos</b></td>
                        <td align="center"><b>'.$total_ingresos.'</b></td></tr>';
            }   
            $html .= '</table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            //Close and output PDF document
            $doc = $pdf->Output('Reporte.pdf', 'F');
            $pdf->Output($doc);
    }
}