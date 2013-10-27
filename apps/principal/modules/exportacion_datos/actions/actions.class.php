<?php

/**
 * exportacion_datos actions.
 *
 * @package    tpmlabs
 * @subpackage exportacion_datos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class exportacion_datosActions extends sfActions
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
		if($request->getParameter('codigo_operario')!='-1') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $request->getParameter('codigo_operario'));
		}
		if($request->getParameter('codigo_maquina')!='-1') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $request->getParameter('codigo_maquina'));
		}
		$fechaInferiorCerrada = $request->getParameter('fecha_inicio');
		$fechaSuperiorCerrada = $request->getParameter('fecha_fin');
		$criteria->add(RegistroUsoMaquinaPeer::RUM_FECHA, $fechaInferiorCerrada, Criteria::GREATER_EQUAL);
		$criteria->addAnd(RegistroUsoMaquinaPeer::RUM_FECHA, $fechaSuperiorCerrada, Criteria::LESS_EQUAL);
		$criteria->addAscendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_FECHA);
		$criteria->addAscendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_TIEMPO_ENTRE_MODELO);
		$criteria->add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
		$registros = RegistroUsoMaquinaPeer::doSelect($criteria);

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
   <Alignment ss:Horizontal="Right"/>
   <Interior ss:Color="#FFDF4C" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="s64">
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
		<Column ss:AutoFitWidth="0" ss:Width="78.75"/>
    <Column ss:AutoFitWidth="0" ss:Width="36" ss:Span="15"/>
    <Column ss:Index="18" ss:AutoFitWidth="0" ss:Width="42"/>
    <Column ss:AutoFitWidth="0" ss:Width="36" ss:Span="13"/>
		<Row ss:AutoFitHeight="0" ss:Height="53.25">
	<Cell ss:StyleID="s73"><Data ss:Type="String">Fecha registro</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Método</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Tiempo entre métodos</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Cambio método y ajuste</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Tiempo corrida   S. S.</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de inyecc.</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Tiempo corrida C. C.</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de inyecc. stnd. 1</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de inyecc. stnd. 2</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de inyecc. stnd. 3</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de inyecc. stnd. 4</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de inyecc. stnd. 5</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de inyecc. stnd. 6</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de inyecc. stnd. 7</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de inyecc. stnd. 8</Data></Cell>
    
    <Cell ss:StyleID="s73"><Data ss:Type="String">Tiempo corrida producto</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de muestras producto</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. inyecc. x muestra</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Tiempo de corrida estabilidad</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de muestras estabilid.</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. inyecc. x muestra</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Tiempo corrida Mo. Po.</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de muestras Mo. Po.</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. inyecc. x muestra</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Tiempo corrida pureza</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de muestras pureza</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. inyecc. x muestra</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Tiempo corrida disoluci.</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de muestras disoluci.</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. inyecc. x muestra</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Tiempo corrida uniform.</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. de muestras uniform.</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">No. inyecc. x muestra</Data></Cell>
    
    <Cell ss:StyleID="s73"><Data ss:Type="String">Hora inicio de corrida</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Hora fin de corrida</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Fallas</Data></Cell>
    <Cell ss:StyleID="s73"><Data ss:Type="String">Observaciones</Data></Cell>
   </Row>');

		$horasFin = 0;
		$minutosFin = 0;
		$segundosFin = 0;

		foreach($registros as $index => $registro) {
			$fields = array();

			//			$registro = new RegistroUsoMaquina();

			$metodo = MetodoPeer::retrieveByPK($registro->getRumMetCodigo());

			$this->renderText('<Row>
			<Cell ss:StyleID="s65"><Data ss:Type="String">'.$registro->getRumFecha('d-m-Y').'</Data></Cell>
			<Cell ss:StyleID="s64"><Data ss:Type="String">'.$metodo->getMetNombre().'</Data></Cell>
			<Cell ss:StyleID="s65"><Data ss:Type="String">'.$registro->getRumTiempoEntreModelo('H:i:s').'</Data></Cell>
			<Cell ss:StyleID="s66"><Data ss:Type="Number">'.number_format($registro->getRumTiempoCambioModelo(), 2).'</Data></Cell>
			<Cell ss:StyleID="s68"><Data ss:Type="Number">'.number_format($registro->getRumTiempoCorridaSistema(), 2).'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.$registro->getRumNumeroInyeccionEstandar().'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumTiempoCorridaCurvas(), 2).'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.$registro->getRumNumeroInyeccionEstandar1().'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.$registro->getRumNumeroInyeccionEstandar2().'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.$registro->getRumNumeroInyeccionEstandar3().'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.$registro->getRumNumeroInyeccionEstandar4().'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.$registro->getRumNumeroInyeccionEstandar5().'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.$registro->getRumNumeroInyeccionEstandar6().'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.$registro->getRumNumeroInyeccionEstandar7().'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.$registro->getRumNumeroInyeccionEstandar8().'</Data></Cell>
			
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumTcProductoTerminado(), 2, '.', '').'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumNumMuestrasProducto(), 2, '.', '').'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumNumInyecXMuestraProduc(), 2, '.', '').'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumTcEstabilidad(), 2, '.', '').'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumNumMuestrasEstabilidad(), 2, '.', '').'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumNumInyecXMuestraEstabi(), 2, '.', '').'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumTcMateriaPrima(), 2, '.', '').'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumNumMuestrasMateriaPrima(), 2, '.', '').'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumNumInyecXMuestraMateri(), 2, '.', '').'</Data></Cell>
			<Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumTcPureza(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumNumMuestrasPureza(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumNumInyecXMuestraPureza(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumTcDisolucion(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumNumMuestrasDisolucion(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumNumInyecXMuestraDisolu(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumTcUniformidad(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumNumMuestrasUniformidad(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s72"><Data ss:Type="Number">'.number_format($registro->getRumNumInyecXMuestraUnifor(), 2, '.', '').'</Data></Cell>
			
			<Cell ss:StyleID="s74"><Data ss:Type="String">'.$registro->getRumHoraInicioTrabajo('H:i:s').'</Data></Cell>
			<Cell ss:StyleID="s74"><Data ss:Type="String">'.$registro->getRumHoraFinTrabajo('H:i:s').'</Data></Cell>
			<Cell ss:StyleID="s69"><Data ss:Type="Number">'.number_format($registro->getRumFallas(), 2).'</Data></Cell>
                        <Cell ss:StyleID="s64"><Data ss:Type="String">'.$registro->getRumObservaciones().'</Data></Cell>
			</Row>');

			$this->renderText('<Row>
      <Cell ss:StyleID="s65"><Data ss:Type="String">'.$registro->getRumFecha('d-m-Y').'</Data></Cell>
      <Cell ss:StyleID="s64"><Data ss:Type="String">'.$metodo->getMetNombre().'</Data></Cell>
      <Cell ss:StyleID="s65"><Data ss:Type="String">'.number_format(round($registro->calcularTiempoEntreMetodosHoras($horasFin, $minutosFin, $segundosFin),2), 2).'</Data></Cell>
      <Cell ss:StyleID="s69"><Data ss:Type="Number">'.number_format($registro->calcularPerdidaCambioMetodoAjusteMinutos(), 2).'</Data></Cell>
      <Cell ss:StyleID="s69"><Data ss:Type="Number">'.number_format($registro->getRumTiempoCorridaSistema(), 2).'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.$registro->getRumNumInyeccionEstandarPer().'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumTiempoCorridaCurvas(), 2).'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.$registro->getRumNumInyeccionEstandar1Pe().'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.$registro->getRumNumInyeccionEstandar2Pe().'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.$registro->getRumNumInyeccionEstandar3Pe().'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.$registro->getRumNumInyeccionEstandar4Pe().'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.$registro->getRumNumInyeccionEstandar5Pe().'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.$registro->getRumNumInyeccionEstandar6Pe().'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.$registro->getRumNumInyeccionEstandar7Pe().'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.$registro->getRumNumInyeccionEstandar8Pe().'</Data></Cell>
      
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumTcProductoTerminado(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumNumMuProductoPerdida(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumNumInyecXMuProducPerd(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumTcEstabilidad(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumNumMuEstabilidadPerdida(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumNumInyecXMuEstabiPerd(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumTcMateriaPrima(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumNumMuMateriaPrimaPerdi(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumNumInyecXMuMateriPerd(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumTcPureza(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumNumMuestrasPurezaPerdid(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumNumInyecXMuPurezaPerd(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumTcDisolucion(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumNumMuestrasDisolucionPe(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumNumInyecXMuDisoluPerd(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumTcUniformidad(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumNumMuestrasUniformidadP(), 2, '.', '').'</Data></Cell>
      <Cell ss:StyleID="s71"><Data ss:Type="Number">'.number_format($registro->getRumNumInyecXMuUniforPerd(), 2, '.', '').'</Data></Cell>
      
      <Cell ss:StyleID="s74"><Data ss:Type="String">'.$registro->getRumHoraInicioTrabajo('H:i:s').'</Data></Cell>
      <Cell ss:StyleID="s74"><Data ss:Type="String">'.$registro->getRumHoraFinTrabajo('H:i:s').'</Data></Cell>
      <Cell ss:StyleID="s69"><Data ss:Type="Number">'.number_format($registro->getRumFallas(), 2).'</Data></Cell>
      <Cell ss:StyleID="s64"><Data ss:Type="String">'.$registro->getRumObservaciones().'</Data></Cell>
      </Row>');

			$horasFin = $registro->getRumHoraFinTrabajo('H');
			$minutosFin = $registro->getRumHoraFinTrabajo('i');
			$segundosFin = $registro->getRumHoraFinTrabajo('s');
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
                $criteria->add(MaquinaPeer::MAQ_INDICADORES, true);
		$maquinas = MaquinaPeer::doSelect($criteria);

		$result = array();
		$data = array();

		foreach($maquinas as $maquina) {
			$fields = array();

			//			            $maquina = new Maquina();

			$fields['codigo'] = $maquina->getMaqCodigo();
			$fields['nombre'] = $maquina->getMaqNombre();

			$data[] = $fields;
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
	public function executeListarMaquinas() {
                $criteria = new Criteria();
                $criteria -> add(MaquinaPeer::MAQ_INDICADORES, true);
		$maquinas = MaquinaPeer::doSelect($criteria);

		$result = array();
		$data = array();

		foreach($maquinas as $maquina) {
			$fields = array();

			//			      $maquina = new Maquina();

			$fields['codigo'] = $maquina->getMaqCodigo();
			$fields['nombre'] = $maquina->getNombreCompleto();

			$data[] = $fields;
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
	public function executeListarOperarios() {
		$result = array();
		$data = array();

		$criteria = new Criteria();
		$criteria->add(UsuarioPeer::USU_PER_CODIGO, 3);
		$operarios = UsuarioPeer::doSelect($criteria);

		foreach($operarios as $operario) {
			$fields = array();

			//      $operario = new Usuario();

			$fields['codigo'] = $operario->getUsuCodigo();

			$criteria = new Criteria();
			$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $operario->getUsuCodigo());
			$empleado = EmpleadoPeer::doSelectOne($criteria);
			if($empleado) {
				$fields['nombre'] = $empleado->getNombreCompleto();
				$data[] = $fields;
			}
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
	public function executeListarRegistrosUsoMaquina(sfWebRequest $request) {
		$criteria = new Criteria();
		if($request->getParameter('codigo_operario')!='-1') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $request->getParameter('codigo_operario'));
		}
		if($request->getParameter('codigo_maquina')!='-1') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $request->getParameter('codigo_maquina'));
		}
		$fechaInferiorCerrada = $request->getParameter('fecha_inicio');
		$fechaSuperiorCerrada = $request->getParameter('fecha_fin');
		$criteria->add(RegistroUsoMaquinaPeer::RUM_FECHA, $fechaInferiorCerrada, Criteria::GREATER_EQUAL);
		$criteria->addAnd(RegistroUsoMaquinaPeer::RUM_FECHA, $fechaSuperiorCerrada, Criteria::LESS_EQUAL);
		$criteria->addAscendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_FECHA);
		$criteria->addAscendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_TIEMPO_ENTRE_MODELO);
		$criteria->add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
		$registros = RegistroUsoMaquinaPeer::doSelect($criteria);

		$result = array();
		$data = array();

		$horasFin = 0;
		$minutosFin = 0;
		$segundosFin = 0;

		foreach($registros as $registro) {
			$fields = array();

			//      $registro = new RegistroUsoMaquina();

			$fields['id_registro_uso_maquina'] = $registro->getRumCodigo();
			$metodo = MetodoPeer::retrieveByPK($registro->getRumMetCodigo());
			$fields['nombre_metodo'] = $metodo->getMetNombre();
			$fields['fecha_metodo'] = $registro->getRumFecha('d-m-Y');
			$fields['tiempo_entre_metodos'] = $registro->getRumTiempoEntreModelo('H:i:s');
			$fields['cambio_metodo_ajuste'] = number_format($registro->getRumTiempoCambioModelo(), 2, '.', '');
			$fields['tiempo_corrida_ss'] = number_format($registro->getRumTiempoCorridaSistema(), 2, '.', '');
			$fields['numero_inyecciones_ss'] = number_format($registro->getRumNumeroInyeccionEstandar(), 2, '.', '');
			$fields['tiempo_corrida_cc'] = number_format($registro->getRumTiempoCorridaCurvas(), 2, '.', '');

			$fields['numero_inyecciones_estandar1'] = number_format($registro->getRumNumeroInyeccionEstandar1(), 2, '.', '');
			$fields['numero_inyecciones_estandar2'] = number_format($registro->getRumNumeroInyeccionEstandar2(), 2, '.', '');
			$fields['numero_inyecciones_estandar3'] = number_format($registro->getRumNumeroInyeccionEstandar3(), 2, '.', '');
			$fields['numero_inyecciones_estandar4'] = number_format($registro->getRumNumeroInyeccionEstandar4(), 2, '.', '');
			$fields['numero_inyecciones_estandar5'] = number_format($registro->getRumNumeroInyeccionEstandar5(), 2, '.', '');
			$fields['numero_inyecciones_estandar6'] = number_format($registro->getRumNumeroInyeccionEstandar6(), 2, '.', '');
			$fields['numero_inyecciones_estandar7'] = number_format($registro->getRumNumeroInyeccionEstandar7(), 2, '.', '');
			$fields['numero_inyecciones_estandar8'] = number_format($registro->getRumNumeroInyeccionEstandar8(), 2, '.', '');

			// Version 1.1 {
			$fields['tiempo_corrida_producto'] = number_format($registro->getRumTcProductoTerminado(), 2, '.', '');
			$fields['tiempo_corrida_estabilidad'] = number_format($registro->getRumTcEstabilidad(), 2, '.', '');
			$fields['tiempo_corrida_materia_prima'] = number_format($registro->getRumTcMateriaPrima(), 2, '.', '');
			$fields['tiempo_corrida_pureza'] = number_format($registro->getRumTcPureza(), 2, '.', '');
			$fields['tiempo_corrida_disolucion'] = number_format($registro->getRumTcDisolucion(), 2, '.', '');
			$fields['tiempo_corrida_uniformidad'] = number_format($registro->getRumTcUniformidad(), 2, '.', '');
			// }

			$fields['numero_muestras_producto'] = number_format($registro->getRumNumMuestrasProducto(), 2, '.', '');
			$fields['numero_inyecciones_x_muestra_producto'] = number_format($registro->getRumNumInyecXMuestraProduc(), 2, '.', '');
			$fields['numero_muestras_estabilidad'] = number_format($registro->getRumNumMuestrasEstabilidad(), 2, '.', '');
			$fields['numero_inyecciones_x_muestra_estabilidad'] = number_format($registro->getRumNumInyecXMuestraEstabi(), 2, '.', '');
			$fields['numero_muestras_materia_prima'] = number_format($registro->getRumNumMuestrasMateriaPrima(), 2, '.', '');
			$fields['numero_inyecciones_x_muestra_materia_prima'] = number_format($registro->getRumNumInyecXMuestraMateri(), 2, '.', '');

			// Version 1.1 {
			$fields['numero_muestras_pureza'] = number_format($registro->getRumNumMuestrasPureza(), 2, '.', '');
			$fields['numero_inyecciones_x_muestra_pureza'] = number_format($registro->getRumNumInyecXMuestraPureza(), 2, '.', '');
			$fields['numero_muestras_disolucion'] = number_format($registro->getRumNumMuestrasDisolucion(), 2, '.', '');
			$fields['numero_inyecciones_x_muestra_disolucion'] = number_format($registro->getRumNumInyecXMuestraDisolu(), 2, '.', '');
			$fields['numero_muestras_uniformidad'] = number_format($registro->getRumNumMuestrasUniformidad(), 2, '.', '');
			$fields['numero_inyecciones_x_muestra_uniformidad'] = number_format($registro->getRumNumInyecXMuestraUnifor(), 2, '.', '');
			// }

			$fields['hora_inicio_corrida'] = $registro->getRumHoraInicioTrabajo('H:i:s');
			$fields['hora_fin_corrida'] = $registro->getRumHoraFinTrabajo('H:i:s');
			$fields['fallas'] = number_format($registro->getRumFallas(), 2);
                        $fields['observaciones'] = $registro->getRumObservaciones();

			$data[] = $fields;

			$fields = array();

			$fields['id_registro_uso_maquina'] = $registro->getRumCodigo();
			$fields['nombre_metodo'] = $metodo->getMetNombre();
			$fields['tiempo_entre_metodos'] = number_format(round($registro->calcularTiempoEntreMetodosHoras($horasFin, $minutosFin, $segundosFin),2), 2);
			$fields['cambio_metodo_ajuste'] = number_format($registro->calcularPerdidaCambioMetodoAjusteMinutos(), 2);
			$fields['tiempo_corrida_ss'] = number_format($registro->getRumTiempoCorridaSistema(), 2);
			$fields['numero_inyecciones_ss'] = $registro->getRumNumInyeccionEstandarPer();
			$fields['tiempo_corrida_cc'] = number_format($registro->getRumTiempoCorridaCurvas(), 2);

			$fields['numero_inyecciones_estandar1'] = number_format($registro->getRumNumInyeccionEstandar1Pe(), 2, '.', '');
			$fields['numero_inyecciones_estandar2'] = number_format($registro->getRumNumInyeccionEstandar2Pe(), 2, '.', '');
			$fields['numero_inyecciones_estandar3'] = number_format($registro->getRumNumInyeccionEstandar3Pe(), 2, '.', '');
			$fields['numero_inyecciones_estandar4'] = number_format($registro->getRumNumInyeccionEstandar4Pe(), 2, '.', '');
			$fields['numero_inyecciones_estandar5'] = number_format($registro->getRumNumInyeccionEstandar5Pe(), 2, '.', '');
			$fields['numero_inyecciones_estandar6'] = number_format($registro->getRumNumInyeccionEstandar6Pe(), 2, '.', '');
			$fields['numero_inyecciones_estandar7'] = number_format($registro->getRumNumInyeccionEstandar7Pe(), 2, '.', '');
			$fields['numero_inyecciones_estandar8'] = number_format($registro->getRumNumInyeccionEstandar8Pe(), 2, '.', '');

			// Version 1.1 {
			$fields['tiempo_corrida_producto'] = number_format($registro->getRumTcProductoTerminado(), 2, '.', '');
			$fields['tiempo_corrida_estabilidad'] = number_format($registro->getRumTcEstabilidad(), 2, '.', '');
			$fields['tiempo_corrida_materia_prima'] = number_format($registro->getRumTcMateriaPrima(), 2, '.', '');
			$fields['tiempo_corrida_pureza'] = number_format($registro->getRumTcPureza(), 2, '.', '');
			$fields['tiempo_corrida_disolucion'] = number_format($registro->getRumTcDisolucion(), 2, '.', '');
			$fields['tiempo_corrida_uniformidad'] = number_format($registro->getRumTcUniformidad(), 2, '.', '');
			// }

			$fields['numero_muestras_producto'] = number_format($registro->getRumNumMuProductoPerdida(), 2, '.', '');
			$fields['numero_inyecciones_x_muestra_producto'] = number_format($registro->getRumNumInyecXMuProducPerd(), 2, '.', '');
			$fields['numero_muestras_estabilidad'] = number_format($registro->getRumNumMuEstabilidadPerdida(), 2, '.', '');
			$fields['numero_inyecciones_x_muestra_estabilidad'] = number_format($registro->getRumNumInyecXMuEstabiPerd(), 2, '.', '');
			$fields['numero_muestras_materia_prima'] = number_format($registro->getRumNumMuMateriaPrimaPerdi(), 2, '.', '');
			$fields['numero_inyecciones_x_muestra_materia_prima'] = number_format($registro->getRumNumInyecXMuMateriPerd(), 2, '.', '');

			// Version 1.1 {
			$fields['numero_muestras_pureza'] = number_format($registro->getRumNumMuestrasPurezaPerdid(), 2, '.', '');
			$fields['numero_inyecciones_x_muestra_pureza'] = number_format($registro->getRumNumInyecXMuPurezaPerd(), 2, '.', '');
			$fields['numero_muestras_disolucion'] = number_format($registro->getRumNumMuestrasDisolucionPe(), 2, '.', '');
			$fields['numero_inyecciones_x_muestra_disolucion'] = number_format($registro->getRumNumInyecXMuDisoluPerd(), 2, '.', '');
			$fields['numero_muestras_uniformidad'] = number_format($registro->getRumNumMuestrasUniformidadP(), 2, '.', '');
			$fields['numero_inyecciones_x_muestra_uniformidad'] = number_format($registro->getRumNumInyecXMuUniforPerd(), 2, '.', '');
			// }

			$fields['hora_inicio_corrida'] = $registro->getRumHoraInicioTrabajo('H:i:s');
			$fields['hora_fin_corrida'] = $registro->getRumHoraFinTrabajo('H:i:s');
			$fields['fallas'] = number_format($registro->getRumFallas(), 2);
                        $fields['observaciones'] = $registro->getRumObservaciones();

			$horasFin = $registro->getRumHoraFinTrabajo('H');
			$minutosFin = $registro->getRumHoraFinTrabajo('i');
			$segundosFin = $registro->getRumHoraFinTrabajo('s');

			$data[] = $fields;
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
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
}
