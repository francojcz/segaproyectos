<?php

/**
 * reporte_asignaciones actions.
 *
 * @package    segaproyectos
 * @subpackage reporte_asignaciones
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_asignacionesActions extends sfActions
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
  
  
    public function executeListarAsignacionTiempo(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos = array();
            $total = array();

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');

                    $conexion = new Criteria();
                    $conexion->add(AsignaciondetiempoPeer::ADT_ANO, $request->getParameter('ano'));
                                          
                    if($request->getParameter('codigo_pers') != '-1') {
                        $conexion->add(AsignaciondetiempoPeer::ADT_PERS_CODIGO, $request->getParameter('codigo_pers'));
                    }
                                        
                    $asignaciones_cantidad = AsignaciondetiempoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    
                    $asignacion = AsignaciondetiempoPeer::doSelect($conexion);

                    foreach($asignacion as $temporal)
                    {
                            //Identificar si el proyecto fue encontrado
                            $var = false;
                            for($i=0; $i<$fila; $i++) {
                                if($datos[$i]['adt_proyecto'] == $temporal->getAdtProCodigo()) {
                                    $datos[$i]['adt_'.$this->mes($temporal->getAdtMes())] = $temporal->getAdtAsignacion();
                                    $total['adt_'.$this->mes($temporal->getAdtMes())] += $temporal->getAdtAsignacion();
                                    $var = true;
                                    $i = $fila;
                                }
                            }
                            if($var == false) {
                                $datos[$fila]['adt_proyecto']=$temporal->getAdtProCodigo();
                                $datos[$fila]['adt_'.$this->mes($temporal->getAdtMes())]=$temporal->getAdtAsignacion();
                                $total['adt_'.$this->mes($temporal->getAdtMes())] += $temporal->getAdtAsignacion();
                                $fila++;
                            }
                    }
                    
                    if($fila>0){
                            for($j=0; $j<$fila; $j++) {
                                for($k=1; $k<=12; $k++) {
                                    if($datos[$j]['adt_'.$this->mes($k)] == '') {
                                        $datos[$j]['adt_'.$this->mes($k)] = 0;
                                    }
                                }
                                $proyecto = ProyectoPeer::retrieveByPK($datos[$j]['adt_proyecto']);
                                $datos[$j]['adt_proyecto_nombre'] = $proyecto->getProNombre();
                            }
                            $datos[$fila]['adt_proyecto_nombre'] = '<b>Total Asignación</b>';
                            for($m=1; $m<=12; $m++) {
                                if(($total['adt_'.$this->mes($m)]) != 0)
                                    $datos[$fila]['adt_'.$this->mes($m)] = $total['adt_'.$this->mes($m)];
                                else
                                    $datos[$fila]['adt_'.$this->mes($m)] = 0;
                            }
                                                        
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$asignaciones_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en asignaci&oacute;n de tiempos ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
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
    
    public function executeListarPersona() {
            $result = array();
            $data = array();
            
            $criteria = new Criteria();
            $criteria->add(PersonaPeer::PERS_ELIMINADO, 0);
            $criteria->addAscendingOrderByColumn(PersonaPeer::PERS_NOMBRES);
            $personas = PersonaPeer::doSelect($criteria);
            
            foreach ($personas as $temporal) {
                $fields = array();
                $fields['pers_codigo'] = $temporal->getPersCodigo();
                $fields['pers_nombre'] = $temporal->getPersNombres().' '.$temporal->getPersApellidos();
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
            
            $html = '<font style="text-align:center" size="12"><b>ASIGNACIÓN DE TIEMPOS</b></font><br/>';
            
            $total = array();
            $datos = array();
            $fila=0;
            $conexion = new Criteria();
            $conexion->add(AsignaciondetiempoPeer::ADT_ANO, $request->getParameter('ano'));
            $html .= '<br/><b>AÑO: '.$request->getParameter('ano').'</b>';
            if($request->getParameter('codigo_pers') != '-1') {
                $conexion->add(AsignaciondetiempoPeer::ADT_PERS_CODIGO, $request->getParameter('codigo_pers'));
                $persona = PersonaPeer::retrieveByPK($request->getParameter('codigo_pers'));
                $html .= '<br/><b>PERSONA: '.strtoupper($persona->getPersNombres()).' '.strtoupper($persona->getPersApellidos()).'</b>';
            }
            $asignacion = AsignaciondetiempoPeer::doSelect($conexion);
            
            $html .= '<br/><br/>';
            $html .='
            <table cellspacing="0" cellpadding="1" border="1">
            <tr>
                <td style="background-color:#000000;color:#FFFFFF;width:40%" align="center"><b>NOMBRE DEL PROYECTO</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:5%" align="center"><b>ENE</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:5%" align="center"><b>FEB</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:5%" align="center"><b>MAR</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:5%" align="center"><b>ABR</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:5%" align="center"><b>MAY</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:5%" align="center"><b>JUN</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:5%" align="center"><b>JUL</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:5%" align="center"><b>AGO</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:5%" align="center"><b>SEP</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:5%" align="center"><b>OCT</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:5%" align="center"><b>NOV</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:5%" align="center"><b>DIC</b></td>
            </tr>';

            foreach($asignacion as $temporal)
            {
                    //Identificar si el proyecto fue encontrado
                    $var = false;
                    for($i=0; $i<$fila; $i++) {
                        if($datos[$i]['adt_proyecto'] == $temporal->getAdtProCodigo()) {
                            $datos[$i]['adt_'.$this->mes($temporal->getAdtMes())] = $temporal->getAdtAsignacion();
                            $total['adt_'.$this->mes($temporal->getAdtMes())] += $temporal->getAdtAsignacion();
                            $var = true;
                            $i = $fila;
                        }
                    }
                    if($var == false) {
                        $datos[$fila]['adt_proyecto']=$temporal->getAdtProCodigo();
                        $datos[$fila]['adt_'.$this->mes($temporal->getAdtMes())]=$temporal->getAdtAsignacion();
                        $total['adt_'.$this->mes($temporal->getAdtMes())] += $temporal->getAdtAsignacion();
                        $fila++;
                    }
            }
            
            if($fila>0){
                
                    for($j=0; $j<$fila; $j++) {
                        $proyecto = ProyectoPeer::retrieveByPK($datos[$j]['adt_proyecto']);
                        $html .=  '<tr>
                        <td align="center">'.$proyecto->getProNombre().'</td>';
                        for($k=1; $k<=12; $k++) {
                            if($datos[$j]['adt_'.$this->mes($k)] == '') {
                                $html .= '<td align="center">0</td>';
                            }
                            else {
                                $html .= '<td align="center">'.$datos[$j]['adt_'.$this->mes($k)].'</td>';
                            }
                        }
                        $html .=  '</tr>';
                    }
                    
                    $html .=  '<tr>
                    <td align="center"><b>Total Asignación</b></td>';
                    for($m=1; $m<=12; $m++) {
                        if(($total['adt_'.$this->mes($m)]) != 0)
                            $html .= '<td align="center">'.$total['adt_'.$this->mes($m)].'</td>';
                        else
                            $html .= '<td align="center">0</td>';
                    }
                    $html .=  '</tr>';
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
