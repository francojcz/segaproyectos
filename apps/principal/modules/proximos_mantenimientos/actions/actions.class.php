<?php

/**
 * proximos_mantenimientos actions.
 *
 * @package    tpmlabs
 * @subpackage proximos_mantenimientos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class proximos_mantenimientosActions extends sfActions
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

		$criteria = new Criteria();            
                if ($request -> getParameter('codigo_maquina') != '-1')
                {
                    $criteria -> add(RegistroRepMaquinaPeer::RRM_MAQ_CODIGO, $request -> getParameter('codigo_maquina'));
                }
                //Anos disponibles
                $anos = array('2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023');
                $mes = $request -> getParameter('codigo_mes');
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

                $ano = $anos[($request->getParameter('codigo_ano'))-1];
                $inicio = $ano.'-'.$mes.'-01';
                $fin = $ano.'-'.$mes.'-'.$dias[$mes];

                $criteria -> add(RegistroRepMaquinaPeer::RRM_FECHA_PROX_CAMBIO,$fin,CRITERIA::LESS_EQUAL);
                $criteria -> addAnd(RegistroRepMaquinaPeer::RRM_FECHA_PROX_CAMBIO,$inicio,CRITERIA::GREATER_EQUAL);

                $criteria -> addAscendingOrderByColumn(RegistroRepMaquinaPeer::RRM_FECHA_PROX_CAMBIO);
                $registros = RegistroRepMaquinaPeer::doSelect($criteria);

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
  <Style ss:ID="s64">
   <Alignment ss:Horizontal="Center"/> 
   <Borders>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="8" ss:Color="#FF0000"/>
   <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s65">
   <Alignment ss:Horizontal="Center"/>
   <Borders>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="8" ss:Color="#FFFFFF"/>
   <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s66">
   <Alignment ss:Horizontal="Center"/>
   <Borders>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="8" ss:Color="#000000"/>
   <Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s68">
   <Alignment ss:Horizontal="Center"/>
   <Interior ss:Color="#DDD9C4" ss:Pattern="Solid"/>
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
   <Alignment ss:Horizontal="Center"/>
   <Interior ss:Color="#E4E1D0" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s72">
   <Alignment ss:Horizontal="Center"/>
   <Interior ss:Color="#DDD9C4" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s73">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="8" ss:Color="#000000"
    ss:Bold="1"/>
   <Interior ss:Color="#4F81BD" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s74">
   <Alignment ss:Horizontal="Center"/>
   <Interior ss:Color="#f0a05f" ss:Pattern="Solid"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Hoja1">
  <Table ss:ExpandedColumnCount="37" ss:ExpandedRowCount="'.((count($registros)*2)+1).'" x:FullColumns="1"
   x:FullRows="1" ss:DefaultRowHeight="15">');
		$this->renderText('
    <Column ss:AutoFitWidth="0" ss:Width="170"/>
    <Column ss:AutoFitWidth="0" ss:Width="170"/>
    <Column ss:AutoFitWidth="0" ss:Width="130"/>
    <Column ss:AutoFitWidth="0" ss:Width="130"/>
    <Row ss:AutoFitHeight="0" ss:Height="40">
    <Cell ss:StyleID="s73"><Data ss:Type="String">Nombre de Equipo</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Nombre de Parte</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Número de Parte</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Fecha Próximo Cambio</Data></Cell> 
   </Row>');
                
                foreach($registros as $registro) {
                        $estado = 'Pendiente';
                        $criterio1 = new Criteria();
                        $criterio1 -> add(ProximosEstadoPeer::PRE_PROX_CODIGO, $registro -> getRrmCodigo());
                        $valor_estado = ProximosEstadoPeer::doSelectOne($criterio1);
                        if($valor_estado != '') {
                            $estado = $valor_estado->getPreEstado();                      
                        }

                        $dateTimeFechaActual = new DateTime(date('Y-m-d'));
                        $FechaActual = $dateTimeFechaActual -> getTimestamp();
                        $dateTimeFechaRegistro = new DateTime($registro -> getRrmFechaProxCambio());
                        $FechaRegistro = $dateTimeFechaRegistro -> getTimestamp();
                        if(($FechaActual > $FechaRegistro) && ($valor_estado == '')) {
                            $estado = 'Vencido';
                        }
                    
                        $maquina = MaquinaPeer::retrieveByPK($registro->getRrmMaqCodigo());
                        $repuesto = RepuestoPeer::retrieveByPK($registro->getRrmRepCodigo());
                        
                        if($estado == 'Vencido') {
                        $row = '<Row>
                            <Cell ss:StyleID="s64"><Data ss:Type="String">'.$maquina -> getMaqNombre().'</Data></Cell>
                            <Cell ss:StyleID="s64"><Data ss:Type="String">'.$repuesto -> getRepNombre().'</Data></Cell>
                            <Cell ss:StyleID="s64"><Data ss:Type="Number">'.$repuesto -> getRepNumero().'</Data></Cell>
                            <Cell ss:StyleID="s64"><Data ss:Type="String">'.$registro -> getRrmFechaProxCambio('d-m-Y').'</Data></Cell>			
                            </Row>';
                        }
                        if($estado == 'Realizado') {
                        $row = '<Row>
                            <Cell ss:StyleID="s65"><Data ss:Type="String">'.$maquina -> getMaqNombre().'</Data></Cell>
                            <Cell ss:StyleID="s65"><Data ss:Type="String">'.$repuesto -> getRepNombre().'</Data></Cell>
                            <Cell ss:StyleID="s65"><Data ss:Type="Number">'.$repuesto -> getRepNumero().'</Data></Cell>
                            <Cell ss:StyleID="s65"><Data ss:Type="String">'.$registro -> getRrmFechaProxCambio('d-m-Y').'</Data></Cell>			
                            </Row>';
                        }
                        if($estado == 'Pendiente') {
                        $row = '<Row>
                            <Cell ss:StyleID="s66"><Data ss:Type="String">'.$maquina -> getMaqNombre().'</Data></Cell>
                            <Cell ss:StyleID="s66"><Data ss:Type="String">'.$repuesto -> getRepNombre().'</Data></Cell>
                            <Cell ss:StyleID="s66"><Data ss:Type="Number">'.$repuesto -> getRepNumero().'</Data></Cell>
                            <Cell ss:StyleID="s66"><Data ss:Type="String">'.$registro -> getRrmFechaProxCambio('d-m-Y').'</Data></Cell>			
                            </Row>';
                        }
			$this->renderText($row);			
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
        
        public function executeListarRegistrosRepuestoMaquina(sfWebRequest $request)
        {
            $criteria = new Criteria();            
            if ($request -> getParameter('codigo_maquina') != '-1')
            {
                $criteria -> add(RegistroRepMaquinaPeer::RRM_MAQ_CODIGO, $request -> getParameter('codigo_maquina'));
            }
            //Anos disponibles
            $anos = array('2013', '2014', '2015', '2016', '2017', '2018', '2019', '2020', '2021', '2022', '2023');
            $mes = $request -> getParameter('codigo_mes');
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
                       
            $ano = $anos[($request->getParameter('codigo_ano'))-1];
            $inicio = $ano.'-'.$mes.'-01';
            $fin = $ano.'-'.$mes.'-'.$dias[$mes];
            
            $criteria -> add(RegistroRepMaquinaPeer::RRM_FECHA_PROX_CAMBIO,$fin,CRITERIA::LESS_EQUAL);
            $criteria -> addAnd(RegistroRepMaquinaPeer::RRM_FECHA_PROX_CAMBIO,$inicio,CRITERIA::GREATER_EQUAL);
            
            $criteria -> addAscendingOrderByColumn(RegistroRepMaquinaPeer::RRM_FECHA_PROX_CAMBIO);
            $registros = RegistroRepMaquinaPeer::doSelect($criteria);

            $result = array();
            $data = array();

            foreach ($registros as $registro)
            {
                $estado = 'Pendiente';
                $criterio1 = new Criteria();
                $criterio1 -> add(ProximosEstadoPeer::PRE_PROX_CODIGO, $registro -> getRrmCodigo());
                $valor_estado = ProximosEstadoPeer::doSelectOne($criterio1);
                if($valor_estado != '') {
                    $estado = $valor_estado->getPreEstado();                      
                }
                
                $dateTimeFechaActual = new DateTime(date('Y-m-d'));
                $FechaActual = $dateTimeFechaActual -> getTimestamp();
                $dateTimeFechaRegistro = new DateTime($registro -> getRrmFechaProxCambio());
                $FechaRegistro = $dateTimeFechaRegistro -> getTimestamp();
                if(($FechaActual > $FechaRegistro) && ($valor_estado == '')) {
                    $estado = 'Vencido';
                }
                
                $fields = array();
                $fields['id_registro_rep_maquina'] = $registro -> getRrmCodigo();
                $maquina = MaquinaPeer::retrieveByPK($registro->getRrmMaqCodigo());
                $fields['nombre_equipo'] = $maquina->getMaqNombre().' '.$estado;
                $repuesto = RepuestoPeer::retrieveByPK($registro->getRrmRepCodigo());
                $fields['nombre_parte'] = $repuesto->getRepNombre().' '.$estado;
                $fields['numero_parte'] = $repuesto->getRepNumero().' '.$estado;
                $fields['rrm_fecha_cambio'] = $registro -> getRrmFechaProxCambio().' '.$estado;               
                $fields['rrm_observaciones'] = $registro -> getRrmObservaciones();                                
                $fields['rrm_consumo'] = $registro -> getRrmConsumo();
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
            
            $registroRepMaquina = RegistroRepMaquinaPeer::retrieveByPK($request -> getParameter('cod_prox'));
            $dateTimeFechaUso = new DateTime($registroRepMaquina ->getRrmFechaProxCambio());
            $timeStampFechaUso = $dateTimeFechaUso -> getTimestamp();
            $dateTimeFechaActual = new DateTime(date('Y-m-d'));
            $timeStampFechaActual = $dateTimeFechaActual -> getTimestamp();

            if (($timeStampFechaUso < $timeStampFechaActual) && ($codigo_perfil_usuario!='2'))
            {
                return $this -> renderText('1');
            }
            
            $registro_prox = '';
            $criteria = new Criteria();
            $criteria -> add(ProximosEstadoPeer::PRE_PROX_CODIGO, $request->getParameter('cod_prox'));
            $registro_prox += ProximosEstadoPeer::doSelectOne($criteria);
            
            $estado = array('','Realizado');
            
            if($registro_prox == ''){
                $registro = new ProximosEstado();
                $registro -> setPreEstado($estado[$request->getParameter('estado_prox')]);
                $registro -> setPreObservacion($request->getParameter('observacion_prox'));
                $registro ->setPreProxCodigo($request->getParameter('cod_prox'));
                $registro -> setPreUsuRegistra($codigo_usuario);
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
            
            $registroRepMaquina = RegistroRepMaquinaPeer::retrieveByPK($request -> getParameter('codigo_prox'));
            $dateTimeFechaUso = new DateTime($registroRepMaquina ->getRrmFechaProxCambio());
            $timeStampFechaUso = $dateTimeFechaUso -> getTimestamp();
            $dateTimeFechaActual = new DateTime(date('Y-m-d'));
            $timeStampFechaActual = $dateTimeFechaActual -> getTimestamp();

            if (($timeStampFechaUso < $timeStampFechaActual) && ($codigo_perfil_usuario!='2'))
            {
                return $this -> renderText('1');
            }
            
            if ($request -> hasParameter('codigo'))
            {
                $registro = ProximosEstadoPeer::retrieveByPK($request -> getParameter('codigo'));
                $registro -> delete();
            }
            return $this -> renderText('Ok');
        }
        
        public function executeListarEstadosProximos(sfWebRequest $request)
        {
            $criteria = new Criteria();
            if ($request -> hasParameter('cod_registro')) {
                $criteria -> add(ProximosEstadoPeer::PRE_PROX_CODIGO, $request -> getParameter('cod_registro'));
            }
            
            $registrosProximos = ProximosEstadoPeer::doSelect($criteria);

            $result = array();
            $data = array();
            foreach ($registrosProximos as $registroProximo)
            {
                $fields = array();
                $fields['codigo'] = $registroProximo -> getPreCodigo();
                $registroRepMaquina = RegistroRepMaquinaPeer::retrieveByPK($registroProximo->getPreProxCodigo());
                $fields['fecha'] = $registroRepMaquina ->getRrmFechaProxCambio();
                $fields['estado'] = $registroProximo -> getPreEstado();
                $fields['observacion'] = $registroProximo -> getPreObservacion();
                $fields['usu_registra'] = UsuarioPeer::obtenerNombreUsuario($registroProximo -> getPreUsuRegistra());
                $data[] = $fields;
            }
            $result['data'] = $data;
            return $this -> renderText(json_encode($result));
        }
}
