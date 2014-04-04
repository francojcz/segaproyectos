<?php

/**
 * reporte_documentos actions.
 *
 * @package    segaproyectos
 * @subpackage reporte_documentos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_documentosActions extends sfActions
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
  
    public function executeListarDocumento(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');

                    $conexion = new Criteria();
                    
                    if($request->getParameter('codigo_proy') != '-1') {
                        $conexion->add(DocumentoPeer::DOC_PRO_CODIGO, $request->getParameter('codigo_proy'));
                    }
                    
                    if($request->getParameter('codigo_tip_doc') != '-1') {
                        $conexion->add(DocumentoPeer::DOC_TIPD_CODIGO, $request->getParameter('codigo_tip_doc'));
                    }
                    
                    //Proyectos del coordinador activo en la sesión
                    $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
                    if($codigo_usuario != 1 && $request->getParameter('codigo_proy') == '-1') {
                        $criteria = new Criteria();
                        $criteria->add(ProyectoPeer::PRO_PERS_CODIGO, $codigo_usuario);
                        $proyectos = ProyectoPeer::doSelect($criteria);
                        foreach ($proyectos as $proyecto) {
                            $conexion->addOr(DocumentoPeer::DOC_PRO_CODIGO, $proyecto->getProCodigo());
                        }
                    }
                    
                    $conexion->add(DocumentoPeer::DOC_ELIMINADO, 0);
                    $documentos_cantidad = DocumentoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conexion->addAscendingOrderByColumn(DocumentoPeer::DOC_TIPD_CODIGO);
                    $documento = DocumentoPeer::doSelect($conexion);

                    foreach($documento as $temporal)
                    {                            
                            $datos[$fila]['doc_documento_url']=$temporal->getDocDocumentoUrl();
                            $datos[$fila]['doc_fecha_registro']=$temporal->getDocFechaRegistro();
                            
                            $tipo = TipodocumentoPeer::retrieveByPK($temporal->getDocTipdCodigo());
                            $datos[$fila]['doc_tipo_nombre'] = $tipo->getTipdNombre();
                            
                            $proyecto = ProyectoPeer::retrieveByPK($temporal->getDocProCodigo());
                            $datos[$fila]['doc_proyeto_nombre']=$proyecto->getProNombre();
                            
                            $crea = PersonaPeer::retrieveByPK($temporal->getDocUsuCodigo());
                            $datos[$fila]['doc_usuario_nombre'] = $crea->getPersNombres().' '.$crea->getPersApellidos();
                            
                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$documentos_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en documentos ',error:".$excepcion->getMessage()."'}})";
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
                $fields['pro_codigo'] = $temporal->getProCodigo();
                $fields['pro_nombre'] = $temporal->getProNombre();
                $data[] = $fields;
            }

            $result['data'] = $data;
            return $this->renderText(json_encode($result));
    }
    
    public function executeListarTipoDocumento() {
            $result = array();
            $data = array();
            
            $criteria = new Criteria();            
            $tipos = TipodocumentoPeer::doSelect($criteria);
            
            foreach ($tipos as $temporal) {
                $fields = array();
                $fields['tip_doc_codigo'] = $temporal->getTipdCodigo();
                $fields['tip_doc_nombre'] = $temporal->getTipdNombre();
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
            
            $html = '<font style="text-align:center" size="12"><b>DOCUMENTOS POR PROYECTO</b></font><br/>';
            
            $conexion = new Criteria();                    
            if($request->getParameter('codigo_proy') != '-1') {
                $conexion->add(DocumentoPeer::DOC_PRO_CODIGO, $request->getParameter('codigo_proy'));
                $proyecto = ProyectoPeer::retrieveByPK($request->getParameter('codigo_proy'));
                $html .= '<br/><b>PROYECTO: '.strtoupper($proyecto->getProNombre()).'</b>';
            }
            if($request->getParameter('codigo_tip_doc') != '-1') {
                $conexion->add(DocumentoPeer::DOC_TIPD_CODIGO, $request->getParameter('codigo_tip_doc'));
                $tipo = TipodocumentoPeer::retrieveByPK($request->getParameter('codigo_tip_doc'));
                $html .= '<br/><b>TIPO DE DOCUMENTO: '.strtoupper($tipo->getTipdNombre()).'</b>';
            }

            $conexion->add(DocumentoPeer::DOC_ELIMINADO, 0);
            $conexion->addAscendingOrderByColumn(DocumentoPeer::DOC_TIPD_CODIGO);
            
            //Proyectos del coordinador activo en la sesión
            $codigo_usuario = $this->getUser()->getAttribute('usu_codigo');
            if($codigo_usuario != 1 && $request->getParameter('codigo_proy') == '-1') {
                $criteria = new Criteria();
                $criteria->add(ProyectoPeer::PRO_PERS_CODIGO, $codigo_usuario);
                $proyectos = ProyectoPeer::doSelect($criteria);
                foreach ($proyectos as $proyecto) {
                    $conexion->addOr(DocumentoPeer::DOC_PRO_CODIGO, $proyecto->getProCodigo());
                }
            }
            
            $documento = DocumentoPeer::doSelect($conexion);
            
            $html .= '<br/><br/>';
            $html .='
            <table style="width:100%" cellspacing="0" cellpadding="1" border="1">
            <tr>
                <td style="width:40%;background-color:#000000;color:#FFFFFF;" align="center"><b>TIPO DE DOCUMENTO</b></td>
                <td style="width:60%;background-color:#000000;color:#FFFFFF;" align="center"><b>NOMBRE DEL PROYECTO</b></td>
            </tr>';
            
            foreach($documento as $temporal)
            {                            
                    $tipo = TipodocumentoPeer::retrieveByPK($temporal->getDocTipdCodigo());
                    $proyecto = ProyectoPeer::retrieveByPK($temporal->getDocProCodigo());
                    
                    $html .=  '<tr>
                        <td align="center">'.$tipo->getTipdNombre().'</td>
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
