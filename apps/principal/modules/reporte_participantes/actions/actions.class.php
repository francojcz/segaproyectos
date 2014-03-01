<?php

/**
 * reporte_participantes actions.
 *
 * @package    segaproyectos
 * @subpackage reporte_participantes
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_participantesActions extends sfActions
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
  
    public function executeListarParticipante(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');

                    $conexion = new Criteria();                     
                    $participantes_cantidad = ParticipantePeer::doCount($conexion);
                    
                    if($request->getParameter('codigo_pers') != '-1') {
                        $conexion->add(ParticipantePeer::PAR_PERS_CODIGO, $request->getParameter('codigo_pers'));
                    }
                    
                    if($request->getParameter('codigo_proy') != '-1') {
                        $conexion->add(ParticipantePeer::PAR_PRO_CODIGO, $request->getParameter('codigo_proy'));
                    }

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $participantes = ParticipantePeer::doSelect($conexion);

                    foreach($participantes as $temporal)
                    {                        
                            $persona = PersonaPeer::retrieveByPK($temporal->getParPersCodigo());
                            $datos[$fila]['par_persona'] = $persona->getPersNombres().' '.$persona->getPersApellidos();
                            
                            $proyecto = ProyectoPeer::retrieveByPK($temporal->getParProCodigo());
                            $datos[$fila]['par_proyecto'] = $proyecto->getProNombre();
                            
                            $usuario = PersonaPeer::retrieveByPK($temporal->getParUsuCodigo());
                            $datos[$fila]['par_usuario'] = $usuario->getPersNombres().' '.$usuario->getPersApellidos();
                            
                            $datos[$fila]['par_fecha_registro']=$temporal->getParFechaRegistro();  
                            
                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$participantes_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en participantes ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
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
    
    public function executeListarProyecto() {
            $result = array();
            $data = array();
            
            $criteria = new Criteria();
            $criteria->add(ProyectoPeer::PRO_ELIMINADO, 0);
            $criteria->addAscendingOrderByColumn(ProyectoPeer::PRO_NOMBRE);
            $proyectos = ProyectoPeer::doSelect($criteria);
            
            foreach ($proyectos as $temporal) {
                $fields = array();
                $fields['proyec_codigo'] = $temporal->getProCodigo();
                $fields['proyec_nombre'] = $temporal->getProNombre();
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
            
            $criteria = new Criteria(); 
            if($request->getParameter('codigo_pers') != '-1') {
                $criteria->add(ParticipantePeer::PAR_PERS_CODIGO, $request->getParameter('codigo_pers'));
            }
            if($request->getParameter('codigo_proy') != '-1') {
                $criteria->add(ParticipantePeer::PAR_PRO_CODIGO, $request->getParameter('codigo_proy'));
            }
            $participantes = ParticipantePeer::doSelect($criteria);
            
            $html ='
            <table cellspacing="0" cellpadding="1" border="1">
            <tr>
                <td style="background-color:#000000;color:#FFFFFF;" colspan="2" align="center"><b>PARTICIPANTES POR PROYECTO</b></td>
            </tr>
            <tr>
                <td align="center"><b>Nombre de la Persona</b></td>
                <td align="center"><b>Nombre del Proyecto</b></td>
            </tr>';

            foreach($participantes as $temporal)
            {               
                    $persona = PersonaPeer::retrieveByPK($temporal->getParPersCodigo());
                    $proyecto = ProyectoPeer::retrieveByPK($temporal->getParProCodigo());
                    $html .=  '<tr>
                        <td align="center">'.$persona->getPersNombres().' '.$persona->getPersApellidos().'</td>
                        <td align="center">'.$proyecto->getProNombre().'</td>
                    </tr>';
            }
            $html .= '</table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
            
            //Close and output PDF document
            $doc = $pdf->Output('Reporte.pdf', 'F');
            $pdf->Output($doc);
    }
}
