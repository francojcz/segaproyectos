<?php

/**
 * reporte_productos actions.
 *
 * @package    segaproyectos
 * @subpackage reporte_productos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_productosActions extends sfActions
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
  
    public function executeListarProducto(sfWebRequest $request)
    {
            $salida='({"total":"0", "results":""})';
            $fila=0;
            $datos;

            try{
                    $start=$this->getRequestParameter('start');
                    $limit=$this->getRequestParameter('limit');

                    $conexion = new Criteria();
                    
                    if($request->getParameter('codigo_proy') != '-1') {
                        $conexion->add(ProductoPeer::PROD_PRO_CODIGO, $request->getParameter('codigo_proy'));
                    }
                    
                    if($request->getParameter('codigo_est_prod') != '-1') {
                        $conexion->add(ProductoPeer::PROD_EST_CODIGO, $request->getParameter('codigo_est_prod'));
                    }
                    
                    $conexion->add(ProductoPeer::PROD_ELIMINADO, 0);
                    $productos_cantidad = ProductoPeer::doCount($conexion);

                    if($start!=''){
                            $conexion->setOffset($start);
                            $conexion->setLimit($limit);
                    }
                    $conexion->addAscendingOrderByColumn(ProductoPeer::PROD_NOMBRE);
                    $producto = ProductoPeer::doSelect($conexion);

                    foreach($producto as $temporal)
                    {                            
                            $datos[$fila]['prod_nombre']=$temporal->getProdNombre();
                            $datos[$fila]['prod_fecha_entrega']=$temporal->getProdFechaEntrega();
                            $datos[$fila]['prod_documento_url']=$temporal->getProdDocumentoUrl();
                            $datos[$fila]['prod_fecha_registro']=$temporal->getProdFechaRegistro();
                            
                            $proyecto = ProyectoPeer::retrieveByPK($temporal->getProdProCodigo());
                            $datos[$fila]['prod_proyeto_nombre']=$proyecto->getProNombre();
                            
                            $crea = PersonaPeer::retrieveByPK($temporal->getProdUsuCodigo());
                            $datos[$fila]['prod_usuario_nombre'] = $crea->getPersNombres().' '.$crea->getPersApellidos();
                                          
                            $estado = EstadoproductoPeer::retrieveByPK($temporal->getProdEstCodigo());
                            $datos[$fila]['prod_estado_nombre'] = $estado->getEstProdNombre();
                            
                            $fila++;
                    }
                    if($fila>0){
                            $jsonresult = json_encode($datos);
                            $salida= '({"total":"'.$productos_cantidad.'","results":'.$jsonresult.'})';
                    }
            }
            catch (Exception $excepcion)
            {
                    return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en producto ',error:".$excepcion->getMessage()."'}})";
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
                $fields['proy_codigo'] = $temporal->getProCodigo();
                $fields['proy_nombre'] = $temporal->getProNombre();
                $data[] = $fields;
            }

            $result['data'] = $data;
            return $this->renderText(json_encode($result));
    }
    
    public function executeListarEstadoProducto() {
            $result = array();
            $data = array();
            
            $criteria = new Criteria();
            $criteria->addAscendingOrderByColumn(EstadoproductoPeer::EST_PROD_NOMBRE);
            $estados = EstadoproductoPeer::doSelect($criteria);
            
            foreach ($estados as $temporal) {
                $fields = array();
                $fields['est_prod_codigo'] = $temporal->getEstProdCodigo();
                $fields['est_prod_nombre'] = $temporal->getEstProdNombre();
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
            
            $html = '<font style="text-align:center" size="12"><b>PRODUCTOS POR PROYECTO</b></font><br/>';
            
            $conexion = new Criteria();                    
            if($request->getParameter('codigo_proy') != '-1') {
                $conexion->add(ProductoPeer::PROD_PRO_CODIGO, $request->getParameter('codigo_proy'));
                $proyecto = ProyectoPeer::retrieveByPK($request->getParameter('codigo_proy'));
                $html .= '<br/><b>PROYECTO: '.strtoupper($proyecto->getProNombre()).'</b>';
            }
            if($request->getParameter('codigo_est_prod') != '-1') {
                $conexion->add(ProductoPeer::PROD_EST_CODIGO, $request->getParameter('codigo_est_prod'));
                $estado = EstadoproductoPeer::retrieveByPK($request->getParameter('codigo_est_prod'));
                $html .= '<br/><b>ESTADO DEL PRODUCTO: '.strtoupper($estado->getEstProdNombre()).'</b>';
            }
            $conexion->add(ProductoPeer::PROD_ELIMINADO, 0);   
            $conexion->addAscendingOrderByColumn(ProductoPeer::PROD_NOMBRE);
            $producto = ProductoPeer::doSelect($conexion);
            
            $html .= '<br/><br/>';
            $html .='
            <table style="width:100%" cellspacing="0" cellpadding="1" border="1">            
            <tr>
                <td style="background-color:#000000;color:#FFFFFF;width:35%" align="center"><b>NOMBRE DEL PRODUCTO</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:15%" align="center"><b>FECHA DE ENTREGA</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:15%" align="center"><b>ESTADO</b></td>
                <td style="background-color:#000000;color:#FFFFFF;width:35%" align="center"><b>NOMBRE DEL PROYECTO</b></td>
            </tr>';
            
            foreach($producto as $temporal)
            {                            
                    $datos[$fila]['prod_nombre']=$temporal->getProdNombre();
                    $datos[$fila]['prod_fecha_entrega']=$temporal->getProdFechaEntrega();
                    $proyecto = ProyectoPeer::retrieveByPK($temporal->getProdProCodigo());
                    $estado = EstadoproductoPeer::retrieveByPK($temporal->getProdEstCodigo());
                    
                    $html .=  '<tr>
                        <td align="center">'.$temporal->getProdNombre().'</td>
                        <td align="center">'.$temporal->getProdFechaEntrega().'</td>
                        <td align="center">'.$estado->getEstProdNombre().'</td>
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
