<?php

/**
 * reporte_organizaciones actions.
 *
 * @package    segaproyectos
 * @subpackage reporte_organizaciones
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_organizacionesActions extends sfActions
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
  
    public function executeListarOrganizacionProyecto(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');

                    $conexion = new Criteria(); 
                    
                    if($request->getParameter('codigo_org') != '-1') {
                        $conexion->add(OrganizacionproyectoPeer::ORPY_ORG_CODIGO, $request->getParameter('codigo_org'));
                    }
                    
                    if($request->getParameter('codigo_proy') != '-1') {
                        $conexion->add(OrganizacionproyectoPeer::ORPY_PRO_CODIGO, $request->getParameter('codigo_proy'));
                    }
                    
                    $organizaciones_cantidad = OrganizacionproyectoPeer::doCount($conexion);
                    
                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $organizaciones = OrganizacionproyectoPeer::doSelect($conexion);

                    foreach($organizaciones as $temporal)
                    {                        
                            $organizacion = OrganizacionPeer::retrieveByPK($temporal->getOrpyOrgCodigo());
                            $datos[$fila]['orpy_organizacion'] = $organizacion->getOrgNombreCompleto().' - '.$organizacion->getOrgNombreCorto();
                            
                            $proyecto = ProyectoPeer::retrieveByPK($temporal->getOrpyProCodigo());
                            $datos[$fila]['orpy_proyecto'] = $proyecto->getProNombre();
                            
                            $usuario = PersonaPeer::retrieveByPK($temporal->getOrpyUsuCodigo());
                            $datos[$fila]['orpy_usuario'] = $usuario->getPersNombres().' '.$usuario->getPersApellidos();
                            
                            $datos[$fila]['orpy_fecha_registro']=$temporal->getOrpyFechaRegistro();  
                            
                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$organizaciones_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en organizaciones ',error:".$excepcion->getMessage()."'}})";
            }

            return $this->renderText($salida);
    }
    
    
    public function executeListarOrganizacion() {
            $result = array();
            $data = array();
            
            $criteria = new Criteria();
            $criteria->add(OrganizacionPeer::ORG_ELIMINADO, 0);
            $criteria->addAscendingOrderByColumn(OrganizacionPeer::ORG_NOMBRE_COMPLETO);
            $organizaciones = OrganizacionPeer::doSelect($criteria);
            
            foreach ($organizaciones as $temporal) {
                $fields = array();
                $fields['org_codigo'] = $temporal->getOrgCodigo();
                $fields['org_nombre'] = $temporal->getOrgNombreCompleto().' - '.$temporal->getOrgNombreCorto();
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
                $fields['proye_codigo'] = $temporal->getProCodigo();
                $fields['proye_nombre'] = $temporal->getProNombre();
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
            
            $conexion = new Criteria();                     
            if($request->getParameter('codigo_org') != '-1') {
                $conexion->add(OrganizacionproyectoPeer::ORPY_ORG_CODIGO, $request->getParameter('codigo_org'));
            }
            if($request->getParameter('codigo_proy') != '-1') {
                $conexion->add(OrganizacionproyectoPeer::ORPY_PRO_CODIGO, $request->getParameter('codigo_proy'));
            }
            $organizaciones = OrganizacionproyectoPeer::doSelect($conexion);
            
            $html ='
            <table cellspacing="0" cellpadding="1" border="1">
            <tr>
                <td style="background-color:#000000;color:#FFFFFF;" colspan="2" align="center"><b>ORGANIZACIONES POR PROYECTO</b></td>
            </tr>
            <tr>
                <td align="center"><b>Nombre de la Organización</b></td>
                <td align="center"><b>Nombre del Proyecto</b></td>
            </tr>';

            foreach($organizaciones as $temporal)
            {                        
                    $organizacion = OrganizacionPeer::retrieveByPK($temporal->getOrpyOrgCodigo());
                    $proyecto = ProyectoPeer::retrieveByPK($temporal->getOrpyProCodigo());
                    
                    $html .=  '<tr>
                        <td align="center">'.$organizacion->getOrgNombreCompleto().' - '.$organizacion->getOrgNombreCorto().'</td>
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
