<?php

/**
 * exportacion_datos actions.
 *
 * @package    tpmlabs
 * @subpackage exportacion_datos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class seguimiento_mantenimientoActions extends sfActions
{
	public function executeExportar(sfWebRequest $request) {
		// Send Header
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=datos.xls ");
		header("Content-Transfer-Encoding: binary ");
                	
                if($request->getParameter('codigo_maquina')!='-1') {
                    $criteria = new Criteria();
                    $criteria -> add(RegistroPmtoMaquinaPeer::RPM_MAQ_CODIGO, $request->getParameter('codigo_maquina'));                    
                    $registros = RegistroPmtoMaquinaPeer::doSelect($criteria);
                    
                $this->renderText('<?xml version="1.0"?>
                <?mso-application progid="Excel.Sheet"?>
                <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
                 xmlns:o="urn:schemas-microsoft-com:office:office"
                 xmlns:x="urn:schemas-microsoft-com:office:excel"
                 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
                 xmlns:html="http://www.w3.org/TR/REC-html40">
                 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
                  <Created>2006-09-16T00:00:00Z</Created>
                  <LastSaved>2011-02-01T00:11:48Z</LastSaved>
                  <Version>14.00</Version>
                 </DocumentProperties>
                 <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
                  <AllowPNG/>
                  <RemovePersonalInformation/>
                 </OfficeDocumentSettings>
                 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
                  <WindowHeight>8010</WindowHeight>
                  <WindowWidth>14805</WindowWidth>
                  <WindowTopX>240</WindowTopX>
                  <WindowTopY>105</WindowTopY>
                  <ProtectStructure>False</ProtectStructure>
                  <ProtectWindows>False</ProtectWindows>
                 </ExcelWorkbook>
                 <Styles>
                  <Style ss:ID="Default" ss:Name="Normal">
                   <Alignment ss:Vertical="Bottom"/>
                   <Borders/>
                   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="8" ss:Color="#000000"/>
                   <Interior/>
                   <NumberFormat/>
                   <Protection/>
                  </Style>
                  <Style ss:ID="s62">
                   <NumberFormat ss:Format="Fixed"/>
                  </Style>
                  <Style ss:ID="s65">
                   <Alignment ss:Horizontal="Center"/>
                   <Interior ss:Color="#FFDF4C" ss:Pattern="Solid"/>
                  </Style>
                  <Style ss:ID="s64">
                   <Alignment ss:Horizontal="Center"/>
                   <Interior ss:Color="#DDD9C4" ss:Pattern="Solid"/>
                  </Style>
                  <Style ss:ID="s66">
                   <Interior ss:Color="#4CD774" ss:Pattern="Solid"/>
                   <NumberFormat ss:Format="Fixed"/>
                  </Style>
                  <Style ss:ID="s68">
                   <Interior ss:Color="#71A7CD" ss:Pattern="Solid"/>
                   <NumberFormat ss:Format="Fixed"/>
                  </Style>
                  <Style ss:ID="s70">
                   <Interior ss:Color="#4F81BD" ss:Pattern="Solid"/>
                  </Style>
                  <Style ss:ID="s69">
                   <Interior ss:Color="#FF4C4C" ss:Pattern="Solid"/>
                   <NumberFormat ss:Format="Fixed"/>
                  </Style>
                  <Style ss:ID="s71">
                   <Interior ss:Color="#FF4C4C" ss:Pattern="Solid"/>
                  </Style>
                  <Style ss:ID="s72">
                   <Interior ss:Color="#71A7CD" ss:Pattern="Solid"/>
                  </Style>
                  <Style ss:ID="s73">
                   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
                   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="8" ss:Color="#000000"
                    ss:Bold="1"/>
                   <Interior ss:Color="#4F81BD" ss:Pattern="Solid"/>
                  </Style>');
                
                $this->renderText('
                <Style ss:ID="1 Día">
                 <Alignment ss:Horizontal="Center"/>
                 <Interior ss:Color="'.randomColor().'" ss:Pattern="Solid"/>
                </Style>');
                //Asignar color a cada periodo
                for($i=2;$i<=31;$i++) {
                    $this->renderText('
                      <Style ss:ID="'.$i.' Días">
                       <Alignment ss:Horizontal="Center"/>
                       <Interior ss:Color="'.randomColor().'" ss:Pattern="Solid"/>
                      </Style>');
                }
                $this->renderText('
                <Style ss:ID="1 Mes">
                 <Alignment ss:Horizontal="Center"/>
                 <Interior ss:Color="'.randomColor().'" ss:Pattern="Solid"/>
                </Style>');
                for($j=2;$j<=60;$j++) {
                    $this->renderText('
                      <Style ss:ID="'.$j.' Meses">
                       <Alignment ss:Horizontal="Center"/>
                       <Interior ss:Color="'.randomColor().'" ss:Pattern="Solid"/>
                      </Style>');
                }
                
                $this->renderText('
                  <Style ss:ID="s74">
                   <Alignment ss:Horizontal="Center"/>
                   <Interior ss:Color="#f0a05f" ss:Pattern="Solid"/>
                  </Style>
                 </Styles>');
                $this->renderText('
                 <Worksheet ss:Name="Hoja1">
                  <Table ss:ExpandedColumnCount="37" ss:ExpandedRowCount="'.((count($registros)*2)+1).'" x:FullColumns="1"
                    x:FullRows="1" ss:DefaultRowHeight="15">');
                    $this->renderText('
                    <Column ss:AutoFitWidth="0" ss:Width="130"/>
                    <Column ss:AutoFitWidth="0" ss:Width="45" ss:Span="30"/>
                    <Row ss:AutoFitHeight="0" ss:Height="40">
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Nombre de Equipo</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 1</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 2</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 3</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 4</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 5</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 6</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 7</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 8</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 9</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 10</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 11</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 12</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 13</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 14</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 15</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 16</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 17</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 18</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 19</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 20</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 21</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 22</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 23</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 24</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 25</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 26</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 27</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 28</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 29</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 30</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 31</Data></Cell>    
                   </Row>');
                          
                    
                  $maquina = MaquinaPeer::retrieveByPK($request->getParameter('codigo_maquina'));
                  
                  foreach($registros as $registro) {
                        //Obtener el periodo de mantenimiento
                        $pmto = PeriodoMantenimientoPeer::retrieveByPK($registro->getRpmPmtoCodigo());
                        $num_pmto = $pmto->getPmtoPeriodo();
                        $tipo_pmto = TipoPeriodoPeer::retrieveByPK($pmto->getPmtoTipo());
                        $nombre_pmto = $tipo_pmto->getTpNombre();

                        //Anos
                        $anos = array('2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023');

                        //Días de cada mes
                        $dias = array();
                        $dias[1] = 31;
                        if((($anos[($request->getParameter('codigo_ano'))-1])%4)!=0)
                            $dias[2] = 28;
                        else
                            $dias[2] = 29;
                        $dias[3] = 31;
                        $dias[4] = 30;
                        $dias[5] = 31;
                        $dias[6] = 30;
                        $dias[7] = 31;
                        $dias[8] = 31;
                        $dias[9] = 30;
                        $dias[10] = 31;
                        $dias[11] = 30;
                        $dias[12] = 31;

                        //Fecha de inicio y fin del periodo
                        $fecha_inicio = strtotime($registro->getRpmFechaInicio());
                        $ano_inicio = (int) date('Y',$fecha_inicio);
                        $mes_inicio = (int) date('m',$fecha_inicio);
                        $dia_inicio = (int) date('d',$fecha_inicio);
                        $ano_fin = $anos[($request->getParameter('codigo_ano'))-1];
                        $mes_fin = $request->getParameter('codigo_mes');

                        if($nombre_pmto=='Día') {                        

                            if($ano_inicio==$ano_fin) {
                                //Asignar fechas cuando el periodo es Día
                                $primer_dia = 1;
                                $residuo = 0;

                                //Cantidad de días equivalentes al primer día del mes seleccionado
                                for($j=1;$j<$mes_fin;$j++){
                                    $primer_dia += $dias[$j];
                                }

                                if($mes_inicio==$mes_fin) {
                                    $inicial = $dia_inicio;
                                    while($inicial <= $dias[$mes_fin]){                                    
                                        if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                        $inicial += $num_pmto;
                                    }
                                }
                                else {
                                    if($mes_fin>=$mes_inicio) {
                                        $residuo  = (($primer_dia - $dia_inicio)%$num_pmto);
                                        if($residuo==0) {
                                            $inicial = 1;
                                            while($inicial <= $dias[$mes_fin]){
                                                if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                    $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                                $inicial += $num_pmto;
                                            }
                                        }
                                        else {
                                            $inicial = $num_pmto-$residuo+1;
                                            while($inicial <= $dias[$mes_fin]){
                                                if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                    $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                                $inicial += $num_pmto;
                                            }
                                        }
                                    }    
                                }                        
                            }
                            else {
                                $diferencia = $ano_fin - $ano_inicio;
                                $pos = ($request->getParameter('codigo_ano'))-1;
                                $cantidad_dias = 0;
                                while($diferencia!=0){
                                    $cantidad_dias += 365;
                                    $pos--;
                                    $diferencia--;
                                }
                                //Asignar fechas cuando el periodo es Día
                                $primer_dia = $cantidad_dias + 1;
                                $residuo = 0;
                                if($nombre_pmto=='Día') {
                                    //Cantidad de días equivalentes al primer día del mes seleccionado
                                    for($j=1;$j<$mes_fin;$j++){
                                        $primer_dia += $dias[$j];
                                    }

                                    $residuo  = (($primer_dia - $dia_inicio)%$num_pmto);

                                    if($residuo==0) {
                                        $inicial = 1;
                                        while($inicial <= $dias[$mes_fin]){
                                            if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                            $inicial += $num_pmto;
                                        }
                                    }
                                    else {
                                        $inicial = $num_pmto-$residuo+1;
                                        while($inicial <= $dias[$mes_fin]){
                                            if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                            $inicial += $num_pmto;
                                        }
                                    }
                                }
                            }
                        }

                        if($nombre_pmto=='Mes') {
                            if($ano_inicio==$ano_fin) {
                                $residuo  = (($mes_fin - $mes_inicio)%$num_pmto);
                                if($mes_fin>=$mes_inicio) {
                                    if($residuo==0) {
                                        if($num_pmto>$temp['dia '.$dia_inicio]['Mes'])
                                            $temp['dia '.$dia_inicio]['Mes'] = $num_pmto;
                                    }                            
                                }
                            }
                            else {
                                $diferencia = $ano_fin - $ano_inicio;
                                $cantidad_dias = 0;
                                while($diferencia!=0){
                                    $cantidad_dias += 12;
                                    $diferencia--;
                                }
                                $residuo  = ((($mes_fin+$cantidad_dias) - $mes_inicio)%$num_pmto);
                                if($residuo==0) {
                                    if($num_pmto>$temp['dia '.$dia_inicio]['Mes'])
                                        $temp['dia '.$dia_inicio]['Mes'] = $num_pmto;
                                }  
                            }                        
                        }
                    }

                    for($j=1;$j<=31;$j++){
                        if($temp['dia '.$j]['Dia']!='' && $temp['dia '.$j]['Mes']!=''){
                            if($temp['dia '.$j]['Mes']==1)
                                $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Mes';
                            if($temp['dia '.$j]['Mes']>1)
                                $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Meses';
                        }
                        if($temp['dia '.$j]['Dia']!='' && $temp['dia '.$j]['Mes']==''){
                            if($temp['dia '.$j]['Dia']==1)
                                $fields['dia '.$j] = $temp['dia '.$j]['Dia'].' Día';
                            if($temp['dia '.$j]['Dia']>1)
                                $fields['dia '.$j] = $temp['dia '.$j]['Dia'].' Días';
                        }
                        if($temp['dia '.$j]['Dia']=='' && $temp['dia '.$j]['Mes']!=''){
                            if($temp['dia '.$j]['Mes']==1)
                                $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Mes';
                            if($temp['dia '.$j]['Mes']>1)
                                $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Meses';
                        }
                    }
                    
                  $colores = array();
                  for($k=1;$k<=31;$k++) {
                      if($fields['dia '.$k]=='')
                          $colores['dia '.$k] = 's64';
                      else
                          $colores['dia '.$k] = $fields['dia '.$k];
                  }
                    

                  $this->renderText('<Row>
                  <Cell ss:StyleID="s65"><Data ss:Type="String">'.$maquina->getMaqNombre().'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 1'].'"><Data ss:Type="String">'.$fields['dia 1'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 2'].'"><Data ss:Type="String">'.$fields['dia 2'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 3'].'"><Data ss:Type="String">'.$fields['dia 3'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 4'].'"><Data ss:Type="String">'.$fields['dia 4'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 5'].'"><Data ss:Type="String">'.$fields['dia 5'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 6'].'"><Data ss:Type="String">'.$fields['dia 6'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 7'].'"><Data ss:Type="String">'.$fields['dia 7'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 8'].'"><Data ss:Type="String">'.$fields['dia 8'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 9'].'"><Data ss:Type="String">'.$fields['dia 9'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 10'].'"><Data ss:Type="String">'.$fields['dia 10'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 11'].'"><Data ss:Type="String">'.$fields['dia 11'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 12'].'"><Data ss:Type="String">'.$fields['dia 12'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 13'].'"><Data ss:Type="String">'.$fields['dia 13'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 14'].'"><Data ss:Type="String">'.$fields['dia 14'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 15'].'"><Data ss:Type="String">'.$fields['dia 15'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 16'].'"><Data ss:Type="String">'.$fields['dia 16'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 17'].'"><Data ss:Type="String">'.$fields['dia 17'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 18'].'"><Data ss:Type="String">'.$fields['dia 18'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 19'].'"><Data ss:Type="String">'.$fields['dia 19'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 20'].'"><Data ss:Type="String">'.$fields['dia 20'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 21'].'"><Data ss:Type="String">'.$fields['dia 21'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 22'].'"><Data ss:Type="String">'.$fields['dia 22'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 23'].'"><Data ss:Type="String">'.$fields['dia 23'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 24'].'"><Data ss:Type="String">'.$fields['dia 24'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 25'].'"><Data ss:Type="String">'.$fields['dia 25'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 26'].'"><Data ss:Type="String">'.$fields['dia 26'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 27'].'"><Data ss:Type="String">'.$fields['dia 27'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 28'].'"><Data ss:Type="String">'.$fields['dia 28'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 29'].'"><Data ss:Type="String">'.$fields['dia 29'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 30'].'"><Data ss:Type="String">'.$fields['dia 30'].'</Data></Cell>
                  <Cell ss:StyleID="'.$colores['dia 31'].'"><Data ss:Type="String">'.$fields['dia 31'].'</Data></Cell>
                  </Row>');
                }
                else {
                    $criteria = new Criteria();
                    $criteria->add(MaquinaPeer::MAQ_ELIMINADO, false);
                    $maquinas = MaquinaPeer::doSelect($criteria);
                    
                    $this->renderText('<?xml version="1.0"?>
                <?mso-application progid="Excel.Sheet"?>
                <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
                 xmlns:o="urn:schemas-microsoft-com:office:office"
                 xmlns:x="urn:schemas-microsoft-com:office:excel"
                 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
                 xmlns:html="http://www.w3.org/TR/REC-html40">
                 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
                  <Created>2006-09-16T00:00:00Z</Created>
                  <LastSaved>2011-02-01T00:11:48Z</LastSaved>
                  <Version>14.00</Version>
                 </DocumentProperties>
                 <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
                  <AllowPNG/>
                  <RemovePersonalInformation/>
                 </OfficeDocumentSettings>
                 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
                  <WindowHeight>8010</WindowHeight>
                  <WindowWidth>14805</WindowWidth>
                  <WindowTopX>240</WindowTopX>
                  <WindowTopY>105</WindowTopY>
                  <ProtectStructure>False</ProtectStructure>
                  <ProtectWindows>False</ProtectWindows>
                 </ExcelWorkbook>
                 <Styles>
                  <Style ss:ID="Default" ss:Name="Normal">
                   <Alignment ss:Vertical="Bottom"/>
                   <Borders/>
                   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="8" ss:Color="#000000"/>
                   <Interior/>
                   <NumberFormat/>
                   <Protection/>
                  </Style>
                  <Style ss:ID="s62">
                   <NumberFormat ss:Format="Fixed"/>
                  </Style>
                  <Style ss:ID="s65">
                   <Alignment ss:Horizontal="Center"/>
                   <Interior ss:Color="#FFDF4C" ss:Pattern="Solid"/>
                  </Style>
                  <Style ss:ID="s64">
                   <Alignment ss:Horizontal="Center"/>
                   <Interior ss:Color="#DDD9C4" ss:Pattern="Solid"/>
                  </Style>
                  <Style ss:ID="s66">
                   <Interior ss:Color="#4CD774" ss:Pattern="Solid"/>
                   <NumberFormat ss:Format="Fixed"/>
                  </Style>
                  <Style ss:ID="s68">
                   <Interior ss:Color="#71A7CD" ss:Pattern="Solid"/>
                   <NumberFormat ss:Format="Fixed"/>
                  </Style>
                  <Style ss:ID="s70">
                   <Interior ss:Color="#4F81BD" ss:Pattern="Solid"/>
                  </Style>
                  <Style ss:ID="s69">
                   <Interior ss:Color="#FF4C4C" ss:Pattern="Solid"/>
                   <NumberFormat ss:Format="Fixed"/>
                  </Style>
                  <Style ss:ID="s71">
                   <Interior ss:Color="#FF4C4C" ss:Pattern="Solid"/>
                  </Style>
                  <Style ss:ID="s72">
                   <Interior ss:Color="#71A7CD" ss:Pattern="Solid"/>
                  </Style>
                  <Style ss:ID="s73">
                   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
                   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="8" ss:Color="#000000"
                    ss:Bold="1"/>
                   <Interior ss:Color="#4F81BD" ss:Pattern="Solid"/>
                  </Style>');
                    
                  $this->renderText('
                <Style ss:ID="1 Día">
                 <Alignment ss:Horizontal="Center"/>
                 <Interior ss:Color="'.randomColor().'" ss:Pattern="Solid"/>
                </Style>');
                //Asignar color a cada periodo
                for($i=2;$i<=31;$i++) {
                    $this->renderText('
                      <Style ss:ID="'.$i.' Días">
                       <Alignment ss:Horizontal="Center"/>
                       <Interior ss:Color="'.randomColor().'" ss:Pattern="Solid"/>
                      </Style>');
                }
                $this->renderText('
                <Style ss:ID="1 Mes">
                 <Alignment ss:Horizontal="Center"/>
                 <Interior ss:Color="'.randomColor().'" ss:Pattern="Solid"/>
                </Style>');
                for($j=2;$j<=60;$j++) {
                    $this->renderText('
                      <Style ss:ID="'.$j.' Meses">
                       <Alignment ss:Horizontal="Center"/>
                       <Interior ss:Color="'.randomColor().'" ss:Pattern="Solid"/>
                      </Style>');
                }
                
                $this->renderText('
                  <Style ss:ID="s74">
                   <Alignment ss:Horizontal="Center"/>
                   <Interior ss:Color="#f0a05f" ss:Pattern="Solid"/>
                  </Style>
                 </Styles>');
                $this->renderText('
                 <Worksheet ss:Name="Hoja1">
                  <Table ss:ExpandedColumnCount="37" ss:ExpandedRowCount="'.((count($maquinas)*2)+1).'" x:FullColumns="1"
                    x:FullRows="1" ss:DefaultRowHeight="15">');
                    $this->renderText('
                    <Column ss:AutoFitWidth="0" ss:Width="130"/>
                    <Column ss:AutoFitWidth="0" ss:Width="45" ss:Span="30"/>
                    <Row ss:AutoFitHeight="0" ss:Height="40">
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Nombre de Equipo</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 1</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 2</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 3</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 4</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 5</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 6</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 7</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 8</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 9</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 10</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 11</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 12</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 13</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 14</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 15</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 16</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 17</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 18</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 19</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 20</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 21</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 22</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 23</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 24</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 25</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 26</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 27</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 28</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 29</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 30</Data></Cell>    
                    <Cell ss:StyleID="s73"><Data ss:Type="String">Día 31</Data></Cell>    
                   </Row>');

                    $result = array();
                    $data = array();                    
                    
                    foreach($maquinas as $maquina) {
                        
                        $fields = array();
                        $temp = array();
                        $criteria -> add(RegistroPmtoMaquinaPeer::RPM_MAQ_CODIGO, $maquina->getMaqCodigo());
                        $registros = RegistroPmtoMaquinaPeer::doSelect($criteria);
                        
                        //Obtener el nombre del equipo
                        $maquina = MaquinaPeer::retrieveByPK($maquina->getMaqCodigo());
                        $fields['nombre_equipo'] = $maquina->getMaqNombre();

                        foreach($registros as $registro) {
                            //Obtener el periodo de mantenimiento
                            $pmto = PeriodoMantenimientoPeer::retrieveByPK($registro->getRpmPmtoCodigo());
                            $num_pmto = $pmto->getPmtoPeriodo();
                            $tipo_pmto = TipoPeriodoPeer::retrieveByPK($pmto->getPmtoTipo());
                            $nombre_pmto = $tipo_pmto->getTpNombre();

                            //Anos
                            $anos = array('2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023');

                            //Días de cada mes
                            $dias = array();
                            $dias[1] = 31;
                            if((($anos[($request->getParameter('codigo_ano'))-1])%4)!=0)
                                $dias[2] = 28;
                            else
                                $dias[2] = 29;
                            $dias[3] = 31;
                            $dias[4] = 30;
                            $dias[5] = 31;
                            $dias[6] = 30;
                            $dias[7] = 31;
                            $dias[8] = 31;
                            $dias[9] = 30;
                            $dias[10] = 31;
                            $dias[11] = 30;
                            $dias[12] = 31;

                            //Fecha de inicio y fin del periodo
                            $fecha_inicio = strtotime($registro->getRpmFechaInicio());
                            $ano_inicio = (int) date('Y',$fecha_inicio);
                            $mes_inicio = (int) date('m',$fecha_inicio);
                            $dia_inicio = (int) date('d',$fecha_inicio);
                            $ano_fin = $anos[($request->getParameter('codigo_ano'))-1];
                            $mes_fin = $request->getParameter('codigo_mes');

                            if($nombre_pmto=='Día') {
                                if($ano_inicio==$ano_fin) {
                                    //Asignar fechas cuando el periodo es Día
                                    $primer_dia = 1;
                                    $residuo = 0;

                                    //Cantidad de días equivalentes al primer día del mes seleccionado
                                    for($j=1;$j<$mes_fin;$j++){
                                        $primer_dia += $dias[$j];
                                    }

                                    if($mes_inicio==$mes_fin) {
                                        $inicial = $dia_inicio;
                                        while($inicial <= $dias[$mes_fin]){                                    
                                            if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                    $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                            $inicial += $num_pmto;
                                        }
                                    }
                                    else {
                                        if($mes_fin>=$mes_inicio) {
                                            $residuo  = (($primer_dia - $dia_inicio)%$num_pmto);
                                            if($residuo==0) {
                                                $inicial = 1;
                                                while($inicial <= $dias[$mes_fin]){
                                                    if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                        $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                                    $inicial += $num_pmto;
                                                }
                                            }
                                            else {
                                                $inicial = $num_pmto-$residuo+1;
                                                while($inicial <= $dias[$mes_fin]){
                                                    if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                        $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                                    $inicial += $num_pmto;
                                                }
                                            }
                                        }    
                                    }                        
                                }
                                else {
                                    $diferencia = $ano_fin - $ano_inicio;
                                    $pos = ($request->getParameter('codigo_ano'))-1;
                                    $cantidad_dias = 0;
                                    while($diferencia!=0){
                                        $cantidad_dias += 365;
                                        $pos--;
                                        $diferencia--;
                                    }
                                    //Asignar fechas cuando el periodo es Día
                                    $primer_dia = $cantidad_dias + 1;
                                    $residuo = 0;
                                    if($nombre_pmto=='Día') {
                                        //Cantidad de días equivalentes al primer día del mes seleccionado
                                        for($j=1;$j<$mes_fin;$j++){
                                            $primer_dia += $dias[$j];
                                        }

                                        $residuo  = (($primer_dia - $dia_inicio)%$num_pmto);

                                        if($residuo==0) {
                                            $inicial = 1;
                                            while($inicial <= $dias[$mes_fin]){
                                                if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                    $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                                $inicial += $num_pmto;
                                            }
                                        }
                                        else {
                                            $inicial = $num_pmto-$residuo+1;
                                            while($inicial <= $dias[$mes_fin]){
                                                if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                    $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                                $inicial += $num_pmto;
                                            }
                                        }
                                    }
                                }
                            }

                            if($nombre_pmto=='Mes') {
                                if($ano_inicio==$ano_fin) {
                                    $residuo  = (($mes_fin - $mes_inicio)%$num_pmto);
                                    if($mes_fin>=$mes_inicio) {
                                        if($residuo==0) {
                                            if($num_pmto>$temp['dia '.$dia_inicio]['Mes'])
                                                $temp['dia '.$dia_inicio]['Mes'] = $num_pmto;
                                        }                            
                                    }
                                }
                                else {
                                    $diferencia = $ano_fin - $ano_inicio;
                                    $cantidad_dias = 0;
                                    while($diferencia!=0){
                                        $cantidad_dias += 12;
                                        $diferencia--;
                                    }
                                    $residuo  = ((($mes_fin+$cantidad_dias) - $mes_inicio)%$num_pmto);
                                    if($residuo==0) {
                                        if($num_pmto>$temp['dia '.$dia_inicio]['Mes'])
                                            $temp['dia '.$dia_inicio]['Mes'] = $num_pmto;
                                    }  
                                }                        
                            }
                        }

                        for($j=1;$j<=31;$j++){
                            if($temp['dia '.$j]['Dia']!='' && $temp['dia '.$j]['Mes']!=''){
                                if($temp['dia '.$j]['Mes']==1)
                                    $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Mes';
                                if($temp['dia '.$j]['Mes']>1)
                                    $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Meses';
                            }
                            if($temp['dia '.$j]['Dia']!='' && $temp['dia '.$j]['Mes']==''){
                                if($temp['dia '.$j]['Dia']==1)
                                    $fields['dia '.$j] = $temp['dia '.$j]['Dia'].' Día';
                                if($temp['dia '.$j]['Dia']>1)
                                    $fields['dia '.$j] = $temp['dia '.$j]['Dia'].' Días';
                            }
                            if($temp['dia '.$j]['Dia']=='' && $temp['dia '.$j]['Mes']!=''){
                                if($temp['dia '.$j]['Mes']==1)
                                    $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Mes';
                                if($temp['dia '.$j]['Mes']>1)
                                    $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Meses';
                            }
                        }
                        
                          $colores = array();
                          for($k=1;$k<=31;$k++) {
                              if($fields['dia '.$k]=='')
                                  $colores['dia '.$k] = 's64';
                              else
                                  $colores['dia '.$k] = $fields['dia '.$k];
                          }


                          $this->renderText('<Row>
                          <Cell ss:StyleID="s65"><Data ss:Type="String">'.$maquina->getMaqNombre().'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 1'].'"><Data ss:Type="String">'.$fields['dia 1'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 2'].'"><Data ss:Type="String">'.$fields['dia 2'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 3'].'"><Data ss:Type="String">'.$fields['dia 3'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 4'].'"><Data ss:Type="String">'.$fields['dia 4'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 5'].'"><Data ss:Type="String">'.$fields['dia 5'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 6'].'"><Data ss:Type="String">'.$fields['dia 6'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 7'].'"><Data ss:Type="String">'.$fields['dia 7'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 8'].'"><Data ss:Type="String">'.$fields['dia 8'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 9'].'"><Data ss:Type="String">'.$fields['dia 9'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 10'].'"><Data ss:Type="String">'.$fields['dia 10'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 11'].'"><Data ss:Type="String">'.$fields['dia 11'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 12'].'"><Data ss:Type="String">'.$fields['dia 12'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 13'].'"><Data ss:Type="String">'.$fields['dia 13'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 14'].'"><Data ss:Type="String">'.$fields['dia 14'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 15'].'"><Data ss:Type="String">'.$fields['dia 15'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 16'].'"><Data ss:Type="String">'.$fields['dia 16'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 17'].'"><Data ss:Type="String">'.$fields['dia 17'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 18'].'"><Data ss:Type="String">'.$fields['dia 18'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 19'].'"><Data ss:Type="String">'.$fields['dia 19'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 20'].'"><Data ss:Type="String">'.$fields['dia 20'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 21'].'"><Data ss:Type="String">'.$fields['dia 21'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 22'].'"><Data ss:Type="String">'.$fields['dia 22'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 23'].'"><Data ss:Type="String">'.$fields['dia 23'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 24'].'"><Data ss:Type="String">'.$fields['dia 24'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 25'].'"><Data ss:Type="String">'.$fields['dia 25'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 26'].'"><Data ss:Type="String">'.$fields['dia 26'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 27'].'"><Data ss:Type="String">'.$fields['dia 27'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 28'].'"><Data ss:Type="String">'.$fields['dia 28'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 29'].'"><Data ss:Type="String">'.$fields['dia 29'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 30'].'"><Data ss:Type="String">'.$fields['dia 30'].'</Data></Cell>
                          <Cell ss:StyleID="'.$colores['dia 31'].'"><Data ss:Type="String">'.$fields['dia 31'].'</Data></Cell>
                          </Row>');
                    }
                }
                
		$this->renderText('</Table>
			<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
			<PageSetup>
			<Header x:Margin="0.3"/>
			<Footer x:Margin="0.3"/>
			<PageMargins x:Bottom="0.75" x:Left="0.7" x:Right="0.7" x:Top="0.75"/>
			</PageSetup>
			<Selected/>
			<Panes>
			<Pane>
			<Number>3</Number>
			<ActiveRow>3</ActiveRow>
			<ActiveCol>5</ActiveCol>
			</Pane>
			</Panes>
			<ProtectObjects>False</ProtectObjects>
			<ProtectScenarios>False</ProtectScenarios>
			</WorksheetOptions>
			</Worksheet>
			<Worksheet ss:Name="Hoja2">
			<Table ss:ExpandedColumnCount="1" ss:ExpandedRowCount="1" x:FullColumns="1"
			x:FullRows="1" ss:DefaultRowHeight="15">
			</Table>
			<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
			<PageSetup>
			<Header x:Margin="0.3"/>
			<Footer x:Margin="0.3"/>
			<PageMargins x:Bottom="0.75" x:Left="0.7" x:Right="0.7" x:Top="0.75"/>
			</PageSetup>
			<ProtectObjects>False</ProtectObjects>
			<ProtectScenarios>False</ProtectScenarios>
			</WorksheetOptions>
			</Worksheet>
			<Worksheet ss:Name="Hoja3">
			<Table ss:ExpandedColumnCount="1" ss:ExpandedRowCount="1" x:FullColumns="1"
			x:FullRows="1" ss:DefaultRowHeight="15">
			</Table>
			<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
			<PageSetup>
			<Header x:Margin="0.3"/>
			<Footer x:Margin="0.3"/>
			<PageMargins x:Bottom="0.75" x:Left="0.7" x:Right="0.7" x:Top="0.75"/>
			</PageSetup>
			<ProtectObjects>False</ProtectObjects>
			<ProtectScenarios>False</ProtectScenarios>
			</WorksheetOptions>
			</Worksheet>
			</Workbook>
        ');

		return $this->renderText('');
	}
	
        public function executeListarEquiposActivos() {
		$criteria = new Criteria();
		$criteria->add(MaquinaPeer::MAQ_ELIMINADO, false);
		$maquinas = MaquinaPeer::doSelect($criteria);

		$result = array();
		$data = array();

		foreach($maquinas as $maquina) {
			$fields = array();
                        
			$fields['codigo'] = $maquina->getMaqCodigo();
			$fields['nombre'] = $maquina->getMaqNombre();

			$data[] = $fields;
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
                
	public function executeListarMeses() {
		$result = array();
		$data = array();
                
                $mes = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre',
                    'Octubre', 'Noviembre', 'Diciembre');

		for($i=0;$i<12;$i++) {
                    $fields = array();
                    
                    $fields['codigo'] = ($i+1);
                    $fields['nombre'] = $mes[$i];
                    
                    $data[] = $fields;
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
        
        public function executeListarAnos() {
		$result = array();
		$data = array();
                
                $ano = array('2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023');

		for($i=0;$i<11;$i++) {
                    $fields = array();
                    
                    $fields['codigo'] = ($i+1);
                    $fields['nombre'] = $ano[$i];
                    
                    $data[] = $fields;
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
        
	public function executeListarRegistrosPeriodoMaquina(sfWebRequest $request) {
		$criteria = new Criteria();
                
                if($request->getParameter('codigo_maquina')!='-1') {
                    $criteria -> add(RegistroPmtoMaquinaPeer::RPM_MAQ_CODIGO, $request->getParameter('codigo_maquina'));
                    
                    $registros = RegistroPmtoMaquinaPeer::doSelect($criteria);
                
                    $result = array();
                    $data = array();
                    $fields = array();
                    $temp = array();
                    
                    //Obtener el nombre del equipo
                    $maquina = MaquinaPeer::retrieveByPK($request->getParameter('codigo_maquina'));
                    $fields['codigo_equipo'] = $maquina->getMaqCodigo();
                    $fields['nombre_equipo'] = $maquina->getMaqNombre();

                    foreach($registros as $registro) {
                        //Obtener el periodo de mantenimiento
                        $pmto = PeriodoMantenimientoPeer::retrieveByPK($registro->getRpmPmtoCodigo());
                        $num_pmto = $pmto->getPmtoPeriodo();
                        $tipo_pmto = TipoPeriodoPeer::retrieveByPK($pmto->getPmtoTipo());
                        $nombre_pmto = $tipo_pmto->getTpNombre();

                        //Anos
                        $anos = array('2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023');

                        //Días de cada mes
                        $dias = array();
                        $dias[1] = 31;
                        if((($anos[($request->getParameter('codigo_ano'))-1])%4)!=0)
                            $dias[2] = 28;
                        else
                            $dias[2] = 29;
                        $dias[3] = 31;
                        $dias[4] = 30;
                        $dias[5] = 31;
                        $dias[6] = 30;
                        $dias[7] = 31;
                        $dias[8] = 31;
                        $dias[9] = 30;
                        $dias[10] = 31;
                        $dias[11] = 30;
                        $dias[12] = 31;

                        //Fecha de inicio y fin del periodo
                        $fecha_inicio = strtotime($registro->getRpmFechaInicio());
                        $ano_inicio = (int) date('Y',$fecha_inicio);
                        $mes_inicio = (int) date('m',$fecha_inicio);
                        $dia_inicio = (int) date('d',$fecha_inicio);
                        $ano_fin = $anos[($request->getParameter('codigo_ano'))-1];
                        $mes_fin = $request->getParameter('codigo_mes');

                        if($nombre_pmto=='Día') {

                            if($ano_inicio==$ano_fin) {
                                //Asignar fechas cuando el periodo es Día
                                $primer_dia = 1;
                                $residuo = 0;

                                //Cantidad de días equivalentes al primer día del mes seleccionado
                                for($j=1;$j<$mes_fin;$j++){
                                    $primer_dia += $dias[$j];
                                }

                                if($mes_inicio==$mes_fin) {
                                    $inicial = $dia_inicio;
                                    while($inicial <= $dias[$mes_fin]){                                    
                                        if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                        $inicial += $num_pmto;
                                    }
                                }
                                else {
                                    if($mes_fin>=$mes_inicio) {
                                        $residuo  = (($primer_dia - $dia_inicio)%$num_pmto);
                                        if($residuo==0) {
                                            $inicial = 1;
                                            while($inicial <= $dias[$mes_fin]){
                                                if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                    $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                                $inicial += $num_pmto;
                                            }
                                        }
                                        else {
                                            $inicial = $num_pmto-$residuo+1;
                                            while($inicial <= $dias[$mes_fin]){
                                                if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                    $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                                $inicial += $num_pmto;
                                            }
                                        }
                                    }    
                                }                        
                            }
                            else {
                                $diferencia = $ano_fin - $ano_inicio;
                                $pos = ($request->getParameter('codigo_ano'))-1;
                                $cantidad_dias = 0;
                                while($diferencia!=0){
                                    $cantidad_dias += 365;
                                    $pos--;
                                    $diferencia--;
                                }
                                //Asignar fechas cuando el periodo es Día
                                $primer_dia = $cantidad_dias + 1;
                                $residuo = 0;
                                if($nombre_pmto=='Día') {
                                    //Cantidad de días equivalentes al primer día del mes seleccionado
                                    for($j=1;$j<$mes_fin;$j++){
                                        $primer_dia += $dias[$j];
                                    }

                                    $residuo  = (($primer_dia - $dia_inicio)%$num_pmto);

                                    if($residuo==0) {
                                        $inicial = 1;
                                        while($inicial <= $dias[$mes_fin]){
                                            if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                            $inicial += $num_pmto;
                                        }
                                    }
                                    else {
                                        $inicial = $num_pmto-$residuo+1;
                                        while($inicial <= $dias[$mes_fin]){
                                            if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                            $inicial += $num_pmto;
                                        }
                                    }
                                }
                            }
                        }

                        if($nombre_pmto=='Mes') {
                            if($ano_inicio==$ano_fin) {
                                $residuo  = (($mes_fin - $mes_inicio)%$num_pmto);
                                if($mes_fin>=$mes_inicio) {
                                    if($residuo==0) {
                                        if($num_pmto>$temp['dia '.$dia_inicio]['Mes'])
                                            $temp['dia '.$dia_inicio]['Mes'] = $num_pmto;
                                    }                            
                                }
                            }
                            else {
                                $diferencia = $ano_fin - $ano_inicio;
                                $cantidad_dias = 0;
                                while($diferencia!=0){
                                    $cantidad_dias += 12;
                                    $diferencia--;
                                }
                                $residuo  = ((($mes_fin+$cantidad_dias) - $mes_inicio)%$num_pmto);
                                if($residuo==0) {
                                    if($num_pmto>$temp['dia '.$dia_inicio]['Mes'])
                                        $temp['dia '.$dia_inicio]['Mes'] = $num_pmto;
                                }  
                            }                        
                        }
                        
                        for($j=1;$j<=31;$j++){
                            $estado = 'Pendiente';
                            $criteria = new Criteria();
                            $criteria -> add(SeguimientoPeer::SEG_MAQ_CODIGO, $request->getParameter('codigo_maquina'));
                            $criteria -> add(SeguimientoPeer::SEG_FECHA, $ano_fin."-".$mes_fin."-".$j);                        
                            $valor_estado = SeguimientoPeer::doSelectOne($criteria);
                            if($valor_estado != '') {
                                $estado = $valor_estado->getSegEstado();                        
                            }

                            $dateTimeFechaActual = new DateTime(date('Y-m-d'));
                            $FechaActual = $dateTimeFechaActual -> getTimestamp();
                            $dateTimeFechaRegistro = new DateTime($ano_fin."-".$mes_fin."-".$j);
                            $FechaRegistro = $dateTimeFechaRegistro -> getTimestamp();
                            if(($FechaActual > $FechaRegistro) && ($valor_estado == '')) {
                                $estado = 'Vencido';
                            }

                            if($temp['dia '.$j]['Dia']!='' && $temp['dia '.$j]['Mes']!=''){
                                if($temp['dia '.$j]['Mes']==1)
                                    $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Mes '.$estado;
                                if($temp['dia '.$j]['Mes']>1)
                                    $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Meses '.$estado;                            
                            }
                            if($temp['dia '.$j]['Dia']!='' && $temp['dia '.$j]['Mes']==''){
                                if($temp['dia '.$j]['Dia']==1)
                                    $fields['dia '.$j] = $temp['dia '.$j]['Dia'].' Día '.$estado;
                                if($temp['dia '.$j]['Dia']>1)
                                    $fields['dia '.$j] = $temp['dia '.$j]['Dia'].' Días '.$estado;
                            }
                            if($temp['dia '.$j]['Dia']=='' && $temp['dia '.$j]['Mes']!=''){
                                if($temp['dia '.$j]['Mes']==1)
                                    $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Mes '.$estado;
                                if($temp['dia '.$j]['Mes']>1)
                                    $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Meses '.$estado;
                            }                                               
                        }
                    } 

                    $data[] = $fields;
                }
                else {
                    $criteria = new Criteria();
                    $criteria->add(MaquinaPeer::MAQ_ELIMINADO, false);
                    $maquinas = MaquinaPeer::doSelect($criteria);

                    $result = array();
                    $data = array();
                    
                    foreach($maquinas as $maquina) {                        
                        $fields = array();
                        $temp = array();
                        $criteria -> add(RegistroPmtoMaquinaPeer::RPM_MAQ_CODIGO, $maquina->getMaqCodigo());
                        $registros = RegistroPmtoMaquinaPeer::doSelect($criteria);
                        
                        //Obtener el nombre del equipo
                        $maquina = MaquinaPeer::retrieveByPK($maquina->getMaqCodigo());
                        $fields['codigo_equipo'] = $maquina->getMaqCodigo();
                        $fields['nombre_equipo'] = $maquina->getMaqNombre();

                        foreach($registros as $registro) {
                            //Obtener el periodo de mantenimiento
                            $pmto = PeriodoMantenimientoPeer::retrieveByPK($registro->getRpmPmtoCodigo());
                            $num_pmto = $pmto->getPmtoPeriodo();
                            $tipo_pmto = TipoPeriodoPeer::retrieveByPK($pmto->getPmtoTipo());
                            $nombre_pmto = $tipo_pmto->getTpNombre();

                            //Anos
                            $anos = array('2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023');

                            //Días de cada mes
                            $dias = array();
                            $dias[1] = 31;
                            if((($anos[($request->getParameter('codigo_ano'))-1])%4)!=0)
                                $dias[2] = 28;
                            else
                                $dias[2] = 29;
                            $dias[3] = 31;
                            $dias[4] = 30;
                            $dias[5] = 31;
                            $dias[6] = 30;
                            $dias[7] = 31;
                            $dias[8] = 31;
                            $dias[9] = 30;
                            $dias[10] = 31;
                            $dias[11] = 30;
                            $dias[12] = 31;

                            //Fecha de inicio y fin del periodo
                            $fecha_inicio = strtotime($registro->getRpmFechaInicio());
                            $ano_inicio = (int) date('Y',$fecha_inicio);
                            $mes_inicio = (int) date('m',$fecha_inicio);
                            $dia_inicio = (int) date('d',$fecha_inicio);
                            $ano_fin = $anos[($request->getParameter('codigo_ano'))-1];
                            $mes_fin = $request->getParameter('codigo_mes');

                            if($nombre_pmto=='Día') {
                                if($ano_inicio==$ano_fin) {
                                    //Asignar fechas cuando el periodo es Día
                                    $primer_dia = 1;
                                    $residuo = 0;

                                    //Cantidad de días equivalentes al primer día del mes seleccionado
                                    for($j=1;$j<$mes_fin;$j++){
                                        $primer_dia += $dias[$j];
                                    }

                                    if($mes_inicio==$mes_fin) {
                                        $inicial = $dia_inicio;
                                        while($inicial <= $dias[$mes_fin]){                                    
                                            if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                    $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                            $inicial += $num_pmto;
                                        }
                                    }
                                    else {
                                        if($mes_fin>=$mes_inicio) {
                                            $residuo  = (($primer_dia - $dia_inicio)%$num_pmto);
                                            if($residuo==0) {
                                                $inicial = 1;
                                                while($inicial <= $dias[$mes_fin]){
                                                    if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                        $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                                    $inicial += $num_pmto;
                                                }
                                            }
                                            else {
                                                $inicial = $num_pmto-$residuo+1;
                                                while($inicial <= $dias[$mes_fin]){
                                                    if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                        $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                                    $inicial += $num_pmto;
                                                }
                                            }
                                        }    
                                    }                        
                                }
                                else {
                                    $diferencia = $ano_fin - $ano_inicio;
                                    $pos = ($request->getParameter('codigo_ano'))-1;
                                    $cantidad_dias = 0;
                                    while($diferencia!=0){
                                        $cantidad_dias += 365;
                                        $pos--;
                                        $diferencia--;
                                    }
                                    //Asignar fechas cuando el periodo es Día
                                    $primer_dia = $cantidad_dias + 1;
                                    $residuo = 0;
                                    if($nombre_pmto=='Día') {
                                        //Cantidad de días equivalentes al primer día del mes seleccionado
                                        for($j=1;$j<$mes_fin;$j++){
                                            $primer_dia += $dias[$j];
                                        }

                                        $residuo  = (($primer_dia - $dia_inicio)%$num_pmto);

                                        if($residuo==0) {
                                            $inicial = 1;
                                            while($inicial <= $dias[$mes_fin]){
                                                if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                    $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                                $inicial += $num_pmto;
                                            }
                                        }
                                        else {
                                            $inicial = $num_pmto-$residuo+1;
                                            while($inicial <= $dias[$mes_fin]){
                                                if($num_pmto>$temp['dia '.$inicial]['Dia'])
                                                    $temp['dia '.$inicial]['Dia'] = $num_pmto;
                                                $inicial += $num_pmto;
                                            }
                                        }
                                    }
                                }
                            }

                            if($nombre_pmto=='Mes') {
                                if($ano_inicio==$ano_fin) {
                                    $residuo  = (($mes_fin - $mes_inicio)%$num_pmto);
                                    if($mes_fin>=$mes_inicio) {
                                        if($residuo==0) {
                                            if($num_pmto>$temp['dia '.$dia_inicio]['Mes'])
                                                $temp['dia '.$dia_inicio]['Mes'] = $num_pmto;
                                        }                            
                                    }
                                }
                                else {
                                    $diferencia = $ano_fin - $ano_inicio;
                                    $cantidad_dias = 0;
                                    while($diferencia!=0){
                                        $cantidad_dias += 12;
                                        $diferencia--;
                                    }
                                    $residuo  = ((($mes_fin+$cantidad_dias) - $mes_inicio)%$num_pmto);
                                    if($residuo==0) {
                                        if($num_pmto>$temp['dia '.$dia_inicio]['Mes'])
                                            $temp['dia '.$dia_inicio]['Mes'] = $num_pmto;
                                    }  
                                }                        
                            }
                            
                            for($j=1;$j<=31;$j++){
                                $estado = 'Pendiente';
                                $criterio = new Criteria();
                                $criterio -> add(SeguimientoPeer::SEG_MAQ_CODIGO, $maquina->getMaqCodigo());
                                $criterio -> add(SeguimientoPeer::SEG_FECHA, $ano_fin."-".$mes_fin."-".$j);                        
                                $valor_estado = SeguimientoPeer::doSelectOne($criterio);
                                if($valor_estado != '') {
                                    $estado = $valor_estado->getSegEstado();                        
                                }
//
                                $dateTimeFechaActual = new DateTime(date('Y-m-d'));
                                $FechaActual = $dateTimeFechaActual -> getTimestamp();
                                $dateTimeFechaRegistro = new DateTime($ano_fin."-".$mes_fin."-".$j);
                                $FechaRegistro = $dateTimeFechaRegistro -> getTimestamp();
                                if(($FechaActual > $FechaRegistro) && ($valor_estado == '')) {
                                    $estado = 'Vencido';
                                }

                                if($temp['dia '.$j]['Dia']!='' && $temp['dia '.$j]['Mes']!=''){
                                    if($temp['dia '.$j]['Mes']==1)
                                        $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Mes '.$estado;
                                    if($temp['dia '.$j]['Mes']>1)
                                        $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Meses '.$estado;                            
                                }
                                if($temp['dia '.$j]['Dia']!='' && $temp['dia '.$j]['Mes']==''){
                                    if($temp['dia '.$j]['Dia']==1)
                                        $fields['dia '.$j] = $temp['dia '.$j]['Dia'].' Día '.$estado;
                                    if($temp['dia '.$j]['Dia']>1)
                                        $fields['dia '.$j] = $temp['dia '.$j]['Dia'].' Días '.$estado;
                                }
                                if($temp['dia '.$j]['Dia']=='' && $temp['dia '.$j]['Mes']!=''){
                                    if($temp['dia '.$j]['Mes']==1)
                                        $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Mes '.$estado;
                                    if($temp['dia '.$j]['Mes']>1)
                                        $fields['dia '.$j] = $temp['dia '.$j]['Mes'].' Meses '.$estado;
                                }                                               
                            }                        
                        } 
                        $data[] = $fields;
                    }
                }

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
        	
	public function executeIndex(sfWebRequest $request)
	{
		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$this->nombreEmpresa = $empresa->getEmpNombre();
		$this->urlLogo = $empresa->getEmpLogoUrl();
		$this->inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();
	}
        
        public function executeListarEstadosSeguimiento(sfWebRequest $request)
        {
            $criteria = new Criteria();
            if (($request -> hasParameter('fecha_seg')) && ($request -> hasParameter('codigo_maq'))) {
                $criteria -> add(SeguimientoPeer::SEG_FECHA, $request -> getParameter('fecha_seg'));
                $criteria -> add(SeguimientoPeer::SEG_MAQ_CODIGO, $request -> getParameter('codigo_maq'));
            }
            
            $registrosSeguimiento = SeguimientoPeer::doSelect($criteria);

            $result = array();
            $data = array();
            foreach ($registrosSeguimiento as $registroSeguimiento)
            {
                $fields = array();
                $fields['codigo'] = $registroSeguimiento -> getSegCodigo();
                $fields['codigo_maq'] = $registroSeguimiento -> getSegMaqCodigo();
                $fields['fecha'] = $registroSeguimiento -> getSegFecha();
                $fields['estado'] = $registroSeguimiento -> getSegEstado();
                $fields['observacion'] = $registroSeguimiento -> getSegObservacion();
                $fields['usu_registra'] = UsuarioPeer::obtenerNombreUsuario($registroSeguimiento -> getSegUsuRegistra());
                $data[] = $fields;
            }
            $result['data'] = $data;
            return $this -> renderText(json_encode($result));
        }
        
        public function executeListarEstados() {
		$result = array();
		$data = array();
                
                $estado = array('Realizado');

		for($i=0;$i<1;$i++) {
                    $fields = array();
                    
                    $fields['codigo'] = ($i+1);
                    $fields['nombre'] = $estado[$i];
                    
                    $data[] = $fields;
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
        
        public function executeRegistrarEstado(sfWebRequest $request)
        {          
            $user = $this -> getUser();           
            $codigo_usuario = $user -> getAttribute('usu_codigo');
            $codigo_perfil_usuario = $user -> getAttribute('usu_per_codigo');
            
            $dateTimeFechaUso = new DateTime($request->getParameter('fecha_seg'));
            $timeStampFechaUso = $dateTimeFechaUso -> getTimestamp();
            $dateTimeFechaActual = new DateTime(date('Y-m-d'));
            $timeStampFechaActual = $dateTimeFechaActual -> getTimestamp();

            if (($timeStampFechaUso < $timeStampFechaActual) && ($codigo_perfil_usuario!='2'))
            {
                return $this -> renderText('1');
            }
            
            $registro_seg = '';
            $criteria = new Criteria();
            $criteria -> add(SeguimientoPeer::SEG_FECHA, $request->getParameter('fecha_seg'));
            $criteria -> add(SeguimientoPeer::SEG_MAQ_CODIGO, $request->getParameter('maq_codigo'));
            $registro_seg += SeguimientoPeer::doSelectOne($criteria);
            
            $estado = array('','Realizado');
            
            if($registro_seg == ''){
                $registro = new Seguimiento();
                $registro -> setSegMaqCodigo($request->getParameter('maq_codigo'));
                $registro -> setSegFecha($request->getParameter('fecha_seg'));
                $registro -> setSegEstado($estado[$request->getParameter('estado_seg')]);
                $registro -> setSegObservacion($request->getParameter('observacion_seg'));
                $registro -> setSegUsuRegistra($codigo_usuario);
                $registro -> save();
                return $this -> renderText('Ok');
            }
            else
                return $this -> renderText('2');
            
        }
        
        public function executeEliminarEstado(sfWebRequest $request)
        {      
            $user = $this -> getUser();           
            $codigo_perfil_usuario = $user -> getAttribute('usu_per_codigo');
            
            $dateTimeFechaUso = new DateTime($request->getParameter('fecha_seg'));
            $timeStampFechaUso = $dateTimeFechaUso -> getTimestamp();
            $dateTimeFechaActual = new DateTime(date('Y-m-d'));
            $timeStampFechaActual = $dateTimeFechaActual -> getTimestamp();

            if (($timeStampFechaUso < $timeStampFechaActual) && ($codigo_perfil_usuario!='2'))
            {
                return $this -> renderText('1');
            }
            
            if ($request -> hasParameter('codigo'))
            {
                $registro = SeguimientoPeer::retrieveByPK($request -> getParameter('codigo'));
                $registro -> delete();
            }
            return $this -> renderText('Ok');
        }
}

function randomColor() {
    $str = '#';
    for($i = 0 ; $i < 6 ; $i++) {
        $randNum = rand(0 , 15);
        switch ($randNum) {
            case 10: $randNum = 'A'; break;
            case 11: $randNum = 'B'; break;
            case 12: $randNum = 'C'; break;
            case 13: $randNum = 'D'; break;
            case 14: $randNum = 'E'; break;
            case 15: $randNum = 'F'; break;
        }
        $str .= $randNum;
    }
    return $str;
}
