<?php

/**
 * grafico_tiempos_anual actions.
 *
 * @package    tpmlabs
 * @subpackage grafico_tiempos_anual
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class graficos_anualesActions extends sfActions
{
	public function executeGenerarConfiguracionGraficoInyeccionesLineas() {
		$this->renderText('<?xml version="1.0" encoding="UTF-8"?>');
		$this->renderText('<settings>');
		$this->renderText('<grid>
    <x>                            
    <approx_count>10</approx_count>
    </x>                           
    </grid>');
		$this->renderText('<values>
    <x>                                
      <rotate>45</rotate>              
    </x>                               
    </values>');
		$this->renderText('<indicator>
    <enabled>true</enabled>                      
    <zoomable>false</zoomable>                   
    </indicator>');   
		$this->renderText('<legend>
    <graph_on_off>true</graph_on_off>
    <values>                     
    <text><![CDATA[]]></text>  
    </values>                   
    </legend>');
		require_once(dirname(__FILE__).'/../../../../../config/variablesGenerales.php');
		$this->renderText('<export_as_image>
    <file>'.$urlWeb.'flash/amline/export.php</file>     
    <color>#CC0000</color>                      
    <alpha>50</alpha>                           
    </export_as_image>');
		$this->renderText('<help>
    <button>                               
    </button>                              
    <balloon>                              
    <text><![CDATA[]]></text>            
    </balloon>                             
    </help>');
		$this->renderText('<labels>
    <label>
    <x>0</x>
    <y>7</y>
    <text_color>000000</text_color>
    <text_size>13</text_size>
    <align>center</align>
    <text>
    <![CDATA[<b>Inyecciones realizadas / Mes</b>]]>
    </text>        
    </label>
    <label lid="1">
      <x>5</x> 
      <y>50%</y>
      <rotate>true</rotate> 
      <width>100</width>
      <align>left</align>
      <text>
        <![CDATA[<b>No. inyecciones</b>]]>
      </text> 
    </label>
    </labels>');
		$this->renderText('<guides>
    <max_min></max_min>          
    <guide>                      
    <axis>right</axis>         
    </guide>                     
    </guides>');
		return $this->renderText('</settings>');
	}
	public function executeGenerarDatosGraficoInyeccionesLineas(sfWebRequest $request) {
		$anho = $request->getParameter('anho');

		$params = array();
		if($request->getParameter('codigo_operario')!='-1') {
			$params['codigo_operario'] = $request->getParameter('codigo_operario');
		}
		if($request->getParameter('codigo_metodo')!='-1') {
			$params['codigo_metodo'] = $request->getParameter('codigo_metodo');
		}

		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		$codigoMaquina = $request->getParameter('codigo_maquina');
		$this->renderText('<?xml version="1.0" encoding="UTF-8"?>
    <chart>
      <series>
        <value xid="0">Enero</value>
        <value xid="1">Febrero</value>
        <value xid="2">Marzo</value>
        <value xid="3">Abril</value>
        <value xid="4">Mayo</value>
        <value xid="5">Junio</value>
        <value xid="6">Julio</value>
        <value xid="7">Agosto</value>
        <value xid="8">Septiembre</value>
        <value xid="9">Octubre</value>
        <value xid="10">Noviembre</value>
        <value xid="11">Diciembre</value>
      </series>
      <graphs>');
		$inyeccionesObligatoriasEnero = 0;
		$inyeccionesObligatoriasFebrero = 0;
		$inyeccionesObligatoriasMarzo = 0;
		$inyeccionesObligatoriasAbril = 0;
		$inyeccionesObligatoriasMayo = 0;
		$inyeccionesObligatoriasJunio = 0;
		$inyeccionesObligatoriasJulio = 0;
		$inyeccionesObligatoriasAgosto =  0;
		$inyeccionesObligatoriasSeptiembre = 0;
		$inyeccionesObligatoriasOctubre = 0;
		$inyeccionesObligatoriasNoviembre = 0;
		$inyeccionesObligatoriasDiciembre = 0;

		$reinyeccionesEnero = 0;
		$reinyeccionesFebrero = 0;
		$reinyeccionesMarzo = 0;
		$reinyeccionesAbril = 0;
		$reinyeccionesMayo = 0;
		$reinyeccionesJunio = 0;
		$reinyeccionesJulio = 0;
		$reinyeccionesAgosto = 0;
		$reinyeccionesSeptiembre = 0;
		$reinyeccionesOctubre = 0;
		$reinyeccionesNoviembre = 0;
		$reinyeccionesDiciembre = 0;

		if($codigoMaquina=='-1') {
			$maquinas = MaquinaPeer::doSelect(new Criteria());

			foreach($maquinas as $maquina) {
				//                    $maquina = new Maquina();
				$codigoTemporalMaquina = $maquina->getMaqCodigo();

				$inyeccionesObligatoriasEnero += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
				$inyeccionesObligatoriasFebrero += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
				$inyeccionesObligatoriasMarzo += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
				$inyeccionesObligatoriasAbril += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
				$inyeccionesObligatoriasMayo += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
				$inyeccionesObligatoriasJunio += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
				$inyeccionesObligatoriasJulio += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
				$inyeccionesObligatoriasAgosto += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
				$inyeccionesObligatoriasSeptiembre += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
				$inyeccionesObligatoriasOctubre += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
				$inyeccionesObligatoriasNoviembre += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
				$inyeccionesObligatoriasDiciembre += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

				$reinyeccionesEnero += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
				$reinyeccionesFebrero += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
				$reinyeccionesMarzo += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
				$reinyeccionesAbril += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
				$reinyeccionesMayo += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
				$reinyeccionesJunio += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
				$reinyeccionesJulio += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
				$reinyeccionesAgosto += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
				$reinyeccionesSeptiembre += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
				$reinyeccionesOctubre += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
				$reinyeccionesNoviembre += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
				$reinyeccionesDiciembre += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);
			}
		}
		else {
			$inyeccionesObligatoriasEnero = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
			$inyeccionesObligatoriasFebrero = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
			$inyeccionesObligatoriasMarzo = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
			$inyeccionesObligatoriasAbril = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
			$inyeccionesObligatoriasMayo = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
			$inyeccionesObligatoriasJunio = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
			$inyeccionesObligatoriasJulio = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
			$inyeccionesObligatoriasAgosto = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
			$inyeccionesObligatoriasSeptiembre = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
			$inyeccionesObligatoriasOctubre = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
			$inyeccionesObligatoriasNoviembre = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
			$inyeccionesObligatoriasDiciembre = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

			$reinyeccionesEnero = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
			$reinyeccionesFebrero = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
			$reinyeccionesMarzo = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
			$reinyeccionesAbril = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
			$reinyeccionesMayo = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
			$reinyeccionesJunio = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
			$reinyeccionesJulio = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
			$reinyeccionesAgosto = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
			$reinyeccionesSeptiembre = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
			$reinyeccionesOctubre = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
			$reinyeccionesNoviembre = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
			$reinyeccionesDiciembre = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);
		}
		$this->renderText('<graph color="#ffdc44" title="Inyecciones" bullet="round">
          <value xid="0">'.$inyeccionesObligatoriasEnero.'</value>
          <value xid="1">'.$inyeccionesObligatoriasFebrero.'</value>
          <value xid="2">'.$inyeccionesObligatoriasMarzo.'</value>
          <value xid="3">'.$inyeccionesObligatoriasAbril.'</value>
          <value xid="4">'.$inyeccionesObligatoriasMayo.'</value>
          <value xid="5">'.$inyeccionesObligatoriasJunio.'</value>
          <value xid="6">'.$inyeccionesObligatoriasJulio.'</value>
          <value xid="7">'.$inyeccionesObligatoriasAgosto.'</value>
          <value xid="8">'.$inyeccionesObligatoriasSeptiembre.'</value>
          <value xid="9">'.$inyeccionesObligatoriasOctubre.'</value>
          <value xid="10">'.$inyeccionesObligatoriasNoviembre.'</value>
          <value xid="11">'.$inyeccionesObligatoriasDiciembre.'</value>
        </graph>'); 
		$this->renderText('<graph color="#47d552" title="Reinyecciones" bullet="round">
              <value xid="0">'.$reinyeccionesEnero.'</value>
              <value xid="1">'.$reinyeccionesFebrero.'</value>
              <value xid="2">'.$reinyeccionesMarzo.'</value>
              <value xid="3">'.$reinyeccionesAbril.'</value>
              <value xid="4">'.$reinyeccionesMayo.'</value>
              <value xid="5">'.$reinyeccionesJunio.'</value>
              <value xid="6">'.$reinyeccionesJulio.'</value>
              <value xid="7">'.$reinyeccionesAgosto.'</value>
              <value xid="8">'.$reinyeccionesSeptiembre.'</value>
              <value xid="9">'.$reinyeccionesOctubre.'</value>
              <value xid="10">'.$reinyeccionesNoviembre.'</value>
              <value xid="11">'.$reinyeccionesDiciembre.'</value>
            </graph>');
		$totalInyeccionesObligatorias = 0;
		$totalInyeccionesObligatorias += $inyeccionesObligatoriasEnero;
		$totalInyeccionesObligatorias += $inyeccionesObligatoriasFebrero;
		$totalInyeccionesObligatorias += $inyeccionesObligatoriasMarzo;
		$totalInyeccionesObligatorias += $inyeccionesObligatoriasAbril;
		$totalInyeccionesObligatorias += $inyeccionesObligatoriasMayo;
		$totalInyeccionesObligatorias += $inyeccionesObligatoriasJunio;
		$totalInyeccionesObligatorias += $inyeccionesObligatoriasJulio;
		$totalInyeccionesObligatorias += $inyeccionesObligatoriasAgosto;
		$totalInyeccionesObligatorias += $inyeccionesObligatoriasSeptiembre;
		$totalInyeccionesObligatorias += $inyeccionesObligatoriasOctubre;
		$totalInyeccionesObligatorias += $inyeccionesObligatoriasNoviembre;
		$totalInyeccionesObligatorias += $inyeccionesObligatoriasDiciembre;

		$totalReinyecciones = 0;
		$totalReinyecciones += $reinyeccionesEnero;
		$totalReinyecciones += $reinyeccionesFebrero;
		$totalReinyecciones += $reinyeccionesMarzo;
		$totalReinyecciones += $reinyeccionesAbril;
		$totalReinyecciones += $reinyeccionesMayo;
		$totalReinyecciones += $reinyeccionesJunio;
		$totalReinyecciones += $reinyeccionesJulio;
		$totalReinyecciones += $reinyeccionesAgosto;
		$totalReinyecciones += $reinyeccionesSeptiembre;
		$totalReinyecciones += $reinyeccionesOctubre;
		$totalReinyecciones += $reinyeccionesNoviembre;
		$totalReinyecciones += $reinyeccionesDiciembre;

		$totalInyecciones = $totalInyeccionesObligatorias + $totalReinyecciones;

		$maximoValor = 0;
		if($totalInyeccionesObligatorias>=$totalReinyecciones) {
			$maximoValor = $totalInyeccionesObligatorias;
		}
		else {
			$maximoValor = $totalReinyecciones;
		}

		return $this->renderText('
      </graphs>
      <guides>
      <max_min>true</max_min>
	      <guide>
		      <start_value>'.($maximoValor*1.2).'</start_value>
		      <title>Total inyecciones: '.$totalInyecciones.'</title>
		      <color>#00CC00</color>
		      <inside>true</inside>
		      <width>0</width>
	      </guide>
	      <guide>
		      <start_value>'.($maximoValor*1.05).'</start_value>
		      <title>Inyecciones: '.$totalInyeccionesObligatorias.' ('.round(($totalInyeccionesObligatorias/$totalInyecciones)*100, 2).' %)</title>
		      <color>#00CC00</color>
		      <inside>true</inside>
		      <width>0</width>
	      </guide>
	      <guide>
		      <start_value>'.($maximoValor*0.9).'</start_value>
		      <title>Reinyecciones: '.$totalReinyecciones.' ('.round(($totalReinyecciones/$totalInyecciones)*100, 2).' %)</title>
		      <color>#00CC00</color>
		      <inside>true</inside>
		      <width>0</width>
	      </guide>
      </guides>
    </chart>
    ');
		return $this->renderText('');
	}
	public function executeGenerarConfiguracionGraficoMuestrasLineas() {
		$this->renderText('<?xml version="1.0" encoding="UTF-8"?>');
		$this->renderText('<settings>');
		$this->renderText('<grid>
    <x>                            
    <approx_count>10</approx_count>
    </x>                           
    </grid>');
		$this->renderText('<values>
    <x>                                
      <rotate>45</rotate>              
    </x>                               
    </values>');
		$this->renderText('<indicator>
    <enabled>true</enabled>                      
    <zoomable>false</zoomable>                   
    </indicator>');   
		$this->renderText('<legend>
    <graph_on_off>true</graph_on_off>
    <values>                     
    <text><![CDATA[]]></text>  
    </values>                   
    </legend>');
		require_once(dirname(__FILE__).'/../../../../../config/variablesGenerales.php');
		$this->renderText('<export_as_image>
    <file>'.$urlWeb.'flash/amline/export.php</file>     
    <color>#CC0000</color>                      
    <alpha>50</alpha>                           
    </export_as_image>');
		$this->renderText('<help>
    <button>                               
    </button>                              
    <balloon>                              
    <text><![CDATA[]]></text>            
    </balloon>                             
    </help>');
		$this->renderText('<labels>
    <label>
    <x>0</x>
    <y>7</y>
    <text_color>000000</text_color>
    <text_size>13</text_size>
    <align>center</align>
    <text>
    <![CDATA[<b>Muestras analizadas / Mes</b>]]>
    </text>        
    </label>
    <label lid="1">
      <x>10</x> 
      <y>50%</y>
      <rotate>true</rotate> 
      <width>100</width>
      <align>left</align>
      <text>
        <![CDATA[<b>No. muestras</b>]]>
      </text> 
    </label>
    </labels>');
		$this->renderText('<guides>
    <max_min></max_min>          
    <guide>                      
    <axis>right</axis>         
    </guide>                     
    </guides>');
		return $this->renderText('</settings>');
	}
	public function executeGenerarDatosGraficoMuestrasLineas(sfWebRequest $request) {
		$anho = $request->getParameter('anho');

		$params = array();
		if($request->getParameter('codigo_operario')!='-1') {
			$params['codigo_operario'] = $request->getParameter('codigo_operario');
		}
		if($request->getParameter('codigo_metodo')!='-1') {
			$params['codigo_metodo'] = $request->getParameter('codigo_metodo');
		}

		$codigoMaquina = $request->getParameter('codigo_maquina');
		$this->renderText('<?xml version="1.0" encoding="UTF-8"?>
    <chart>
      <series>
        <value xid="0">Enero</value>
        <value xid="1">Febrero</value>
        <value xid="2">Marzo</value>
        <value xid="3">Abril</value>
        <value xid="4">Mayo</value>
        <value xid="5">Junio</value>
        <value xid="6">Julio</value>
        <value xid="7">Agosto</value>
        <value xid="8">Septiembre</value>
        <value xid="9">Octubre</value>
        <value xid="10">Noviembre</value>
        <value xid="11">Diciembre</value>
      </series>
      <graphs>');
		$muestrasAnalizadasEnero = 0;
		$muestrasAnalizadasFebrero = 0;
		$muestrasAnalizadasMarzo = 0;
		$muestrasAnalizadasAbril = 0;
		$muestrasAnalizadasMayo = 0;
		$muestrasAnalizadasJunio = 0;
		$muestrasAnalizadasJulio = 0;
		$muestrasAnalizadasAgosto =  0;
		$muestrasAnalizadasSeptiembre = 0;
		$muestrasAnalizadasOctubre = 0;
		$muestrasAnalizadasNoviembre = 0;
		$muestrasAnalizadasDiciembre = 0;

		$muestrasReanalizadasEnero = 0;
		$muestrasReanalizadasFebrero = 0;
		$muestrasReanalizadasMarzo = 0;
		$muestrasReanalizadasAbril = 0;
		$muestrasReanalizadasMayo = 0;
		$muestrasReanalizadasJunio = 0;
		$muestrasReanalizadasJulio = 0;
		$muestrasReanalizadasAgosto = 0;
		$muestrasReanalizadasSeptiembre = 0;
		$muestrasReanalizadasOctubre = 0;
		$muestrasReanalizadasNoviembre = 0;
		$muestrasReanalizadasDiciembre = 0;

		if($codigoMaquina=='-1') {
			$maquinas = MaquinaPeer::doSelect(new Criteria());

			foreach($maquinas as $maquina) {
				//                    $maquina = new Maquina();
				$codigoTemporalMaquina = $maquina->getMaqCodigo();

				$muestrasAnalizadasEnero += RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoTemporalMaquina, 1, $anho, $params);
				$muestrasAnalizadasFebrero += RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoTemporalMaquina, 2, $anho, $params);
				$muestrasAnalizadasMarzo += RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoTemporalMaquina, 3, $anho, $params);
				$muestrasAnalizadasAbril += RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoTemporalMaquina, 4, $anho, $params);
				$muestrasAnalizadasMayo += RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoTemporalMaquina, 5, $anho, $params);
				$muestrasAnalizadasJunio += RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoTemporalMaquina, 6, $anho, $params);
				$muestrasAnalizadasJulio += RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoTemporalMaquina, 7, $anho, $params);
				$muestrasAnalizadasAgosto += RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoTemporalMaquina, 8, $anho, $params);
				$muestrasAnalizadasSeptiembre += RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoTemporalMaquina, 9, $anho, $params);
				$muestrasAnalizadasOctubre += RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoTemporalMaquina, 10, $anho, $params);
				$muestrasAnalizadasNoviembre += RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoTemporalMaquina, 11, $anho, $params);
				$muestrasAnalizadasDiciembre += RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoTemporalMaquina, 12, $anho, $params);

				$muestrasReanalizadasEnero += RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoTemporalMaquina, 1, $anho, $params);
				$muestrasReanalizadasFebrero += RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoTemporalMaquina, 2, $anho, $params);
				$muestrasReanalizadasMarzo += RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoTemporalMaquina, 3, $anho, $params);
				$muestrasReanalizadasAbril += RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoTemporalMaquina, 4, $anho, $params);
				$muestrasReanalizadasMayo += RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoTemporalMaquina, 5, $anho, $params);
				$muestrasReanalizadasJunio += RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoTemporalMaquina, 6, $anho, $params);
				$muestrasReanalizadasJulio += RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoTemporalMaquina, 7, $anho, $params);
				$muestrasReanalizadasAgosto += RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoTemporalMaquina, 8, $anho, $params);
				$muestrasReanalizadasSeptiembre += RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoTemporalMaquina, 9, $anho, $params);
				$muestrasReanalizadasOctubre += RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoTemporalMaquina, 10, $anho, $params);
				$muestrasReanalizadasNoviembre += RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoTemporalMaquina, 11, $anho, $params);
				$muestrasReanalizadasDiciembre += RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoTemporalMaquina, 12, $anho, $params);
			}
		}
		else {
			$muestrasAnalizadasEnero = RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoMaquina, 1, $anho, $params);
			$muestrasAnalizadasFebrero = RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoMaquina, 2, $anho, $params);
			$muestrasAnalizadasMarzo = RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoMaquina, 3, $anho, $params);
			$muestrasAnalizadasAbril = RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoMaquina, 4, $anho, $params);
			$muestrasAnalizadasMayo = RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoMaquina, 5, $anho, $params);
			$muestrasAnalizadasJunio = RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoMaquina, 6, $anho, $params);
			$muestrasAnalizadasJulio = RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoMaquina, 7, $anho, $params);
			$muestrasAnalizadasAgosto = RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoMaquina, 8, $anho, $params);
			$muestrasAnalizadasSeptiembre = RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoMaquina, 9, $anho, $params);
			$muestrasAnalizadasOctubre = RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoMaquina, 10, $anho, $params);
			$muestrasAnalizadasNoviembre = RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoMaquina, 11, $anho, $params);
			$muestrasAnalizadasDiciembre = RegistroUsoMaquinaPeer::contarMuestrasAnalizadasMes($codigoMaquina, 12, $anho, $params);

			$muestrasReanalizadasEnero = RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoMaquina, 1, $anho, $params);
			$muestrasReanalizadasFebrero = RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoMaquina, 2, $anho, $params);
			$muestrasReanalizadasMarzo = RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoMaquina, 3, $anho, $params);
			$muestrasReanalizadasAbril = RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoMaquina, 4, $anho, $params);
			$muestrasReanalizadasMayo = RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoMaquina, 5, $anho, $params);
			$muestrasReanalizadasJunio = RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoMaquina, 6, $anho, $params);
			$muestrasReanalizadasJulio = RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoMaquina, 7, $anho, $params);
			$muestrasReanalizadasAgosto = RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoMaquina, 8, $anho, $params);
			$muestrasReanalizadasSeptiembre = RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoMaquina, 9, $anho, $params);
			$muestrasReanalizadasOctubre = RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoMaquina, 10, $anho, $params);
			$muestrasReanalizadasNoviembre = RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoMaquina, 11, $anho, $params);
			$muestrasReanalizadasDiciembre = RegistroUsoMaquinaPeer::contarMuestrasReanalizadasMes($codigoMaquina, 12, $anho, $params);
		}
		$this->renderText('<graph color="#ffdc44" title="Muestras analizadas" bullet="round">
          <value xid="0">'.$muestrasAnalizadasEnero.'</value>
          <value xid="1">'.$muestrasAnalizadasFebrero.'</value>
          <value xid="2">'.$muestrasAnalizadasMarzo.'</value>
          <value xid="3">'.$muestrasAnalizadasAbril.'</value>
          <value xid="4">'.$muestrasAnalizadasMayo.'</value>
          <value xid="5">'.$muestrasAnalizadasJunio.'</value>
          <value xid="6">'.$muestrasAnalizadasJulio.'</value>
          <value xid="7">'.$muestrasAnalizadasAgosto.'</value>
          <value xid="8">'.$muestrasAnalizadasSeptiembre.'</value>
          <value xid="9">'.$muestrasAnalizadasOctubre.'</value>
          <value xid="10">'.$muestrasAnalizadasNoviembre.'</value>
          <value xid="11">'.$muestrasAnalizadasDiciembre.'</value>
        </graph>'); 
		$this->renderText('<graph color="#47d552" title="Muestras reanalizadas" bullet="round">
              <value xid="0">'.$muestrasReanalizadasEnero.'</value>
              <value xid="1">'.$muestrasReanalizadasFebrero.'</value>
              <value xid="2">'.$muestrasReanalizadasMarzo.'</value>
              <value xid="3">'.$muestrasReanalizadasAbril.'</value>
              <value xid="4">'.$muestrasReanalizadasMayo.'</value>
              <value xid="5">'.$muestrasReanalizadasJunio.'</value>
              <value xid="6">'.$muestrasReanalizadasJulio.'</value>
              <value xid="7">'.$muestrasReanalizadasAgosto.'</value>
              <value xid="8">'.$muestrasReanalizadasSeptiembre.'</value>
              <value xid="9">'.$muestrasReanalizadasOctubre.'</value>
              <value xid="10">'.$muestrasReanalizadasNoviembre.'</value>
              <value xid="11">'.$muestrasReanalizadasDiciembre.'</value>
            </graph>');

		$totalMuestrasAnalizadas = 0;
		$totalMuestrasAnalizadas += $muestrasAnalizadasEnero;
		$totalMuestrasAnalizadas += $muestrasAnalizadasFebrero;
		$totalMuestrasAnalizadas += $muestrasAnalizadasMarzo;
		$totalMuestrasAnalizadas += $muestrasAnalizadasAbril;
		$totalMuestrasAnalizadas += $muestrasAnalizadasMayo;
		$totalMuestrasAnalizadas += $muestrasAnalizadasJunio;
		$totalMuestrasAnalizadas += $muestrasAnalizadasJulio;
		$totalMuestrasAnalizadas += $muestrasAnalizadasAgosto;
		$totalMuestrasAnalizadas += $muestrasAnalizadasSeptiembre;
		$totalMuestrasAnalizadas += $muestrasAnalizadasOctubre;
		$totalMuestrasAnalizadas += $muestrasAnalizadasNoviembre;
		$totalMuestrasAnalizadas += $muestrasAnalizadasDiciembre;

		$totalMuestrasReanalizadas = 0;
		$totalMuestrasReanalizadas += $muestrasReanalizadasEnero;
		$totalMuestrasReanalizadas += $muestrasReanalizadasFebrero;
		$totalMuestrasReanalizadas += $muestrasReanalizadasMarzo;
		$totalMuestrasReanalizadas += $muestrasReanalizadasAbril;
		$totalMuestrasReanalizadas += $muestrasReanalizadasMayo;
		$totalMuestrasReanalizadas += $muestrasReanalizadasJunio;
		$totalMuestrasReanalizadas += $muestrasReanalizadasJulio;
		$totalMuestrasReanalizadas += $muestrasReanalizadasAgosto;
		$totalMuestrasReanalizadas += $muestrasReanalizadasSeptiembre;
		$totalMuestrasReanalizadas += $muestrasReanalizadasOctubre;
		$totalMuestrasReanalizadas += $muestrasReanalizadasNoviembre;
		$totalMuestrasReanalizadas += $muestrasReanalizadasDiciembre;

		$totalMuestras = $totalMuestrasAnalizadas + $totalMuestrasReanalizadas;

		$maximoValor = 0;
		if($totalMuestrasAnalizadas>=$totalMuestrasReanalizadas) {
			$maximoValor = $totalMuestrasAnalizadas;
		}
		else {
			$maximoValor = $totalMuestrasReanalizadas;
		}

		return $this->renderText('
      </graphs>
      <guides>
      <max_min>true</max_min>
	      <guide>
		      <start_value>'.($maximoValor*1.2).'</start_value>
		      <title>Total muestras: '.$totalMuestras.'</title>
		      <color>#00CC00</color>
		      <inside>true</inside>
		      <width>0</width>
	      </guide>
	      <guide>
		      <start_value>'.($maximoValor*1.05).'</start_value>
		      <title>Muestras analizadas: '.$totalMuestrasAnalizadas.' ('.round(($totalMuestrasAnalizadas/$totalMuestras)*100, 2).' %)</title>
		      <color>#00CC00</color>
		      <inside>true</inside>
		      <width>0</width>
	      </guide>
	      <guide>
		      <start_value>'.($maximoValor*0.9).'</start_value>
		      <title>Muestras reanalizadas: '.$totalMuestrasReanalizadas.' ('.round(($totalMuestrasReanalizadas/$totalMuestras)*100, 2).' %)</title>
		      <color>#00CC00</color>
		      <inside>true</inside>
		      <width>0</width>
	      </guide>
      </guides>
    </chart>
    ');
		return $this->renderText('');
	}
	public function executeGenerarConfiguracionTortaPerdidas() {
		$this->renderText('<settings>');
		$this->renderText('<data_type>csv</data_type>');
		$this->renderText('<pie>');
		$this->renderText('<x>245</x>
    <y>190</y>                     
    <inner_radius>40</inner_radius>
    <height>20</height>            
    <angle>30</angle>
    <colors>#ffdc44,#47d552,#ff5454,#f0a05f,#72a8cd</colors>
    <hover_brightness>-10</hover_brightness>                
    <gradient>radial</gradient>                             
    <gradient_ratio>0,0,0,-50,0,0,0,-50</gradient_ratio>');
		$this->renderText('</pie>');
		$this->renderText('<animation>
    <start_time>2</start_time>                 
    <start_effect>regular</start_effect>       
    <start_alpha>0</start_alpha>               
    <sequenced>true</sequenced>                
    <pull_out_on_click>true</pull_out_on_click>
    <pull_out_time>1.5</pull_out_time>         
    </animation>');
		$this->renderText('<data_labels>
    <show>                                     
    <![CDATA[{percents}%]]>        
    </show>                                    
    </data_labels>');
		$this->renderText('<balloon>
    <alpha>80</alpha>                   
    <show>                              
    <![CDATA[{title}: {value} días ({percents}%) <br>{description}]]>
    </show>                             
    <max_width>300</max_width>          
    <corner_radius>5</corner_radius>    
    <border_width>3</border_width>      
    <border_alpha>50</border_alpha>     
    <border_color>#000000</border_color>
    </balloon>
    <labels>
    <label>
    <x>0</x>
    <y>40</y>
    <text_color>000000</text_color>
    <text_size>13</text_size>
    <align>center</align>
    <text>
    <![CDATA[<b>Discriminación de pérdidas (TPNP)</b>]]>
    </text>        
    </label>
    </labels>
    ');
		$this->renderText('<legend>
    <enabled>true</enabled>        
    <x>100</x>                     
    <y>300</y>                     
    <max_columns>2</max_columns>   
    <values>                       
    <enabled></enabled>          
    <width></width>              
    <text><![CDATA[]]></text>    
    </values>                     
    </legend>');
		require_once(dirname(__FILE__).'/../../../../../config/variablesGenerales.php');
		$this->renderText('<export_as_image>
    <file>'.$urlWeb.'flash/ampie/export.php</file>     
    <color>#CC0000</color>                      
    <alpha>50</alpha>                           
    </export_as_image>');
		return $this->renderText('</settings>');
	}
	public function executeGenerarDatosTortaPerdidas(sfWebRequest $request) {
		$anho = $request->getParameter('anho');
		$codigoMaquina = $request->getParameter('codigo_maquina');

		$params = array();
		if($request->getParameter('codigo_operario')!='-1') {
			$params['codigo_operario'] = $request->getParameter('codigo_operario');
		}
		if($request->getParameter('codigo_metodo')!='-1') {
			$params['codigo_metodo'] = $request->getParameter('codigo_metodo');
		}

		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		$fallasAnual = 0;
		$parosMenoresAnual = 0;
		$retrabajosAnual = 0;
		$perdidasVelocidadAnual = 0;

		if($codigoMaquina=='-1') {
			$maquinas = MaquinaPeer::doSelect(new Criteria());

			foreach($maquinas as $maquina) {
				//                    $maquina = new Maquina();
				$codigoTemporalMaquina = $maquina->getMaqCodigo();

				$fallasAnual += RegistroUsoMaquinaPeer::contarFallasAñoEnDias($codigoTemporalMaquina, $anho, $params);
				$parosMenoresAnual += RegistroUsoMaquinaPeer::contarParosMenoresIncluyendoCambioMetodoAñoEnDias($codigoTemporalMaquina, $anho, $params, $inyeccionesEstandarPromedio);
				$retrabajosAnual += RegistroUsoMaquinaPeer::contarRetrabajosAñoEnDias($codigoTemporalMaquina, $anho, $params, $inyeccionesEstandarPromedio);
				$perdidasVelocidadAnual += RegistroUsoMaquinaPeer::contarPerdidasVelocidadAñoEnDias($codigoTemporalMaquina, $anho, $params, $inyeccionesEstandarPromedio);
			}
		}
		else {
			$fallasAnual = RegistroUsoMaquinaPeer::contarFallasAñoEnDias($codigoMaquina, $anho, $params);
			$parosMenoresAnual = RegistroUsoMaquinaPeer::contarParosMenoresIncluyendoCambioMetodoAñoEnDias($codigoMaquina, $anho, $params, $inyeccionesEstandarPromedio);
			$retrabajosAnual = RegistroUsoMaquinaPeer::contarRetrabajosAñoEnDias($codigoMaquina, $anho, $params, $inyeccionesEstandarPromedio);
			$perdidasVelocidadAnual = RegistroUsoMaquinaPeer::contarPerdidasVelocidadAñoEnDias($codigoMaquina, $anho, $params, $inyeccionesEstandarPromedio);
		}

		echo "Fallas;".round($fallasAnual,2)."\n";
		echo "Paros menores y reajustes;".round($parosMenoresAnual,2)."\n";
		echo "Retrabajos;".round($retrabajosAnual,2)."\n";
		return $this->renderText("Pérdidas de velocidad;".round($perdidasVelocidadAnual,2)."\n");
	}
	public function executeGenerarConfiguracionGraficoPerdidasLineas() {
		$this->renderText('<?xml version="1.0" encoding="UTF-8"?>');
		$this->renderText('<settings>');
		$this->renderText('<grid>
    <x>                            
    <approx_count>10</approx_count>
    </x>                           
    </grid>');
		$this->renderText('<values>
    <x>                                
      <rotate>45</rotate>              
    </x>                               
    </values>');
		$this->renderText('<indicator>
    <enabled>true</enabled>                      
    <zoomable>false</zoomable>                   
    </indicator>');   
		$this->renderText('<legend>
    <graph_on_off>true</graph_on_off>
    <values>                     
    <text><![CDATA[]]></text>  
    </values>                   
    </legend>');
		require_once(dirname(__FILE__).'/../../../../../config/variablesGenerales.php');
		$this->renderText('<export_as_image>
    <file>'.$urlWeb.'flash/amline/export.php</file>     
    <color>#CC0000</color>                      
    <alpha>50</alpha>                           
    </export_as_image>');
		$this->renderText('<help>
    <button>                               
    </button>                              
    <balloon>                              
    <text><![CDATA[]]></text>            
    </balloon>                             
    </help>');
		$this->renderText('<labels>
	    <label>
	    <x>0</x>
	    <y>15</y>
	    <text_color>000000</text_color>
	    <text_size>13</text_size>
	    <align>center</align>
	    <text>
	    <![CDATA[<b>Pérdidas (tiempo) / Mes</b>]]>
	    </text>        
	    </label>
	    <label lid="1">
	      <x>10</x> 
	      <y>50%</y>
	      <rotate>true</rotate> 
	      <width>100</width>
	      <align>left</align>
	      <text>
	        <![CDATA[<b>Días</b>]]>
	      </text> 
      </label>
    </labels>');
		$this->renderText('<guides>
    <max_min></max_min>          
    <guide>                      
    <axis>right</axis>         
    </guide>                     
    </guides>');
		return $this->renderText('</settings>');
	}
	public function executeGenerarDatosGraficoPerdidasLineas(sfWebRequest $request) {
		$anho = $request->getParameter('anho');

		$params = array();
		if($request->getParameter('codigo_operario')!='-1') {
			$params['codigo_operario'] = $request->getParameter('codigo_operario');
		}
		if($request->getParameter('codigo_metodo')!='-1') {
			$params['codigo_metodo'] = $request->getParameter('codigo_metodo');
		}

		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		$codigoMaquina = $request->getParameter('codigo_maquina');
		$this->renderText('<?xml version="1.0" encoding="UTF-8"?>
    <chart>
      <series>
        <value xid="0">Enero</value>
        <value xid="1">Febrero</value>
        <value xid="2">Marzo</value>
        <value xid="3">Abril</value>
        <value xid="4">Mayo</value>
        <value xid="5">Junio</value>
        <value xid="6">Julio</value>
        <value xid="7">Agosto</value>
        <value xid="8">Septiembre</value>
        <value xid="9">Octubre</value>
        <value xid="10">Noviembre</value>
        <value xid="11">Diciembre</value>
      </series>
      <graphs>');

		$fallasEnero = 0;
		$fallasFebrero = 0;
		$fallasMarzo = 0;
		$fallasAbril = 0;
		$fallasMayo = 0;
		$fallasJunio = 0;
		$fallasJulio = 0;
		$fallasAgosto =  0;
		$fallasSeptiembre = 0;
		$fallasOctubre = 0;
		$fallasNoviembre = 0;
		$fallasDiciembre = 0;

		$parosMenoresEnero = 0;
		$parosMenoresFebrero = 0;
		$parosMenoresMarzo = 0;
		$parosMenoresAbril = 0;
		$parosMenoresMayo = 0;
		$parosMenoresJunio = 0;
		$parosMenoresJulio = 0;
		$parosMenoresAgosto = 0;
		$parosMenoresSeptiembre = 0;
		$parosMenoresOctubre = 0;
		$parosMenoresNoviembre = 0;
		$parosMenoresDiciembre = 0;

		$retrabajosEnero = 0;
		$retrabajosFebrero = 0;
		$retrabajosMarzo = 0;
		$retrabajosAbril = 0;
		$retrabajosMayo = 0;
		$retrabajosJunio = 0;
		$retrabajosJulio = 0;
		$retrabajosAgosto = 0;
		$retrabajosSeptiembre = 0;
		$retrabajosOctubre = 0;
		$retrabajosNoviembre = 0;
		$retrabajosDiciembre = 0;

		$perdidasVelocidadEnero = 0;
		$perdidasVelocidadFebrero = 0;
		$perdidasVelocidadMarzo = 0;
		$perdidasVelocidadAbril = 0;
		$perdidasVelocidadMayo = 0;
		$perdidasVelocidadJunio = 0;
		$perdidasVelocidadJulio = 0;
		$perdidasVelocidadAgosto = 0;
		$perdidasVelocidadSeptiembre = 0;
		$perdidasVelocidadOctubre = 0;
		$perdidasVelocidadNoviembre = 0;
		$perdidasVelocidadDiciembre = 0;

		if($codigoMaquina=='-1') {
			$maquinas = MaquinaPeer::doSelect(new Criteria());

			foreach($maquinas as $maquina) {
				//                    $maquina = new Maquina();
				$codigoTemporalMaquina = $maquina->getMaqCodigo();

				$fallasEnero += RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 1, $anho, $params);
				$fallasFebrero += RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 2, $anho, $params);
				$fallasMarzo += RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 3, $anho, $params);
				$fallasAbril += RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 4, $anho, $params);
				$fallasMayo += RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 5, $anho, $params);
				$fallasJunio += RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 6, $anho, $params);
				$fallasJulio += RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 7, $anho, $params);
				$fallasAgosto += RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 8, $anho, $params);
				$fallasSeptiembre += RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 9, $anho, $params);
				$fallasOctubre += RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 10, $anho, $params);
				$fallasNoviembre += RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 11, $anho, $params);
				$fallasDiciembre += RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 12, $anho, $params);

				$parosMenoresEnero += RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoTemporalMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
				$parosMenoresFebrero += RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoTemporalMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
				$parosMenoresMarzo += RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoTemporalMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
				$parosMenoresAbril += RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoTemporalMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
				$parosMenoresMayo += RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoTemporalMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
				$parosMenoresJunio += RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoTemporalMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
				$parosMenoresJulio += RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoTemporalMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
				$parosMenoresAgosto += RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoTemporalMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
				$parosMenoresSeptiembre += RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoTemporalMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
				$parosMenoresOctubre += RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoTemporalMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
				$parosMenoresNoviembre += RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoTemporalMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
				$parosMenoresDiciembre += RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoTemporalMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

				$retrabajosEnero += RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoTemporalMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
				$retrabajosFebrero += RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoTemporalMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
				$retrabajosMarzo += RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoTemporalMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
				$retrabajosAbril += RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoTemporalMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
				$retrabajosMayo += RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoTemporalMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
				$retrabajosJunio += RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoTemporalMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
				$retrabajosJulio += RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoTemporalMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
				$retrabajosAgosto += RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoTemporalMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
				$retrabajosSeptiembre += RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoTemporalMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
				$retrabajosOctubre += RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoTemporalMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
				$retrabajosNoviembre += RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoTemporalMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
				$retrabajosDiciembre += RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoTemporalMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

				$perdidasVelocidadEnero += RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoTemporalMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
				$perdidasVelocidadFebrero += RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoTemporalMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
				$perdidasVelocidadMarzo += RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoTemporalMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
				$perdidasVelocidadAbril += RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoTemporalMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
				$perdidasVelocidadMayo += RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoTemporalMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
				$perdidasVelocidadJunio += RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoTemporalMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
				$perdidasVelocidadJulio += RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoTemporalMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
				$perdidasVelocidadAgosto += RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoTemporalMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
				$perdidasVelocidadSeptiembre += RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoTemporalMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
				$perdidasVelocidadOctubre += RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoTemporalMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
				$perdidasVelocidadNoviembre += RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoTemporalMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
				$perdidasVelocidadDiciembre += RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoTemporalMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);
			}
		}
		else {
			$fallasEnero = RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 1, $anho, $params);
			$fallasFebrero = RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 2, $anho, $params);
			$fallasMarzo = RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 3, $anho, $params);
			$fallasAbril = RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 4, $anho, $params);
			$fallasMayo = RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 5, $anho, $params);
			$fallasJunio = RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 6, $anho, $params);
			$fallasJulio = RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 7, $anho, $params);
			$fallasAgosto = RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 8, $anho, $params);
			$fallasSeptiembre = RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 9, $anho, $params);
			$fallasOctubre = RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 10, $anho, $params);
			$fallasNoviembre = RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 11, $anho, $params);
			$fallasDiciembre = RegistroUsoMaquinaPeer::contarFallasMesEnDias($codigoTemporalMaquina, 12, $anho, $params);

			$parosMenoresEnero = RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
			$parosMenoresFebrero = RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
			$parosMenoresMarzo = RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
			$parosMenoresAbril = RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
			$parosMenoresMayo = RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
			$parosMenoresJunio = RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
			$parosMenoresJulio = RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
			$parosMenoresAgosto = RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
			$parosMenoresSeptiembre = RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
			$parosMenoresOctubre = RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
			$parosMenoresNoviembre = RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
			$parosMenoresDiciembre = RegistroUsoMaquinaPeer::contarParosMenoresMesEnDias($codigoMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

			$retrabajosEnero = RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
			$retrabajosFebrero = RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
			$retrabajosMarzo = RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
			$retrabajosAbril = RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
			$retrabajosMayo = RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
			$retrabajosJunio = RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
			$retrabajosJulio = RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
			$retrabajosAgosto = RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
			$retrabajosSeptiembre = RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
			$retrabajosOctubre = RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
			$retrabajosNoviembre = RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
			$retrabajosDiciembre = RegistroUsoMaquinaPeer::contarRetrabajosMesEnDias($codigoMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

			$perdidasVelocidadEnero = RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
			$perdidasVelocidadFebrero = RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
			$perdidasVelocidadMarzo = RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
			$perdidasVelocidadAbril = RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
			$perdidasVelocidadMayo = RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
			$perdidasVelocidadJunio = RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
			$perdidasVelocidadJulio = RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
			$perdidasVelocidadAgosto = RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
			$perdidasVelocidadSeptiembre = RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
			$perdidasVelocidadOctubre = RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
			$perdidasVelocidadNoviembre = RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
			$perdidasVelocidadDiciembre = RegistroUsoMaquinaPeer::contarPerdidasVelocidadMesEnDias($codigoMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);
		}
		$this->renderText('<graph color="#72a8cd" title="Fallas" bullet="round">
          <value xid="0">'.round($fallasEnero, 2).'</value>
          <value xid="1">'.round($fallasFebrero, 2).'</value>
          <value xid="2">'.round($fallasMarzo, 2).'</value>
          <value xid="3">'.round($fallasAbril, 2).'</value>
          <value xid="4">'.round($fallasMayo, 2).'</value>
          <value xid="5">'.round($fallasJunio, 2).'</value>
          <value xid="6">'.round($fallasJulio, 2).'</value>
          <value xid="7">'.round($fallasAgosto, 2).'</value>
          <value xid="8">'.round($fallasSeptiembre, 2).'</value>
          <value xid="9">'.round($fallasOctubre, 2).'</value>
          <value xid="10">'.round($fallasNoviembre, 2).'</value>
          <value xid="11">'.round($fallasDiciembre, 2).'</value>
        </graph>'); 
		$this->renderText('<graph color="#ff5454" title="Paros menores y reajustes" bullet="round">
              <value xid="0">'.round($parosMenoresEnero, 2).'</value>
              <value xid="1">'.round($parosMenoresFebrero, 2).'</value>
              <value xid="2">'.round($parosMenoresMarzo, 2).'</value>
              <value xid="3">'.round($parosMenoresAbril, 2).'</value>
              <value xid="4">'.round($parosMenoresMayo, 2).'</value>
              <value xid="5">'.round($parosMenoresJunio, 2).'</value>
              <value xid="6">'.round($parosMenoresJulio, 2).'</value>
              <value xid="7">'.round($parosMenoresAgosto, 2).'</value>
              <value xid="8">'.round($parosMenoresSeptiembre, 2).'</value>
              <value xid="9">'.round($parosMenoresOctubre, 2).'</value>
              <value xid="10">'.round($parosMenoresNoviembre, 2).'</value>
              <value xid="11">'.round($parosMenoresDiciembre, 2).'</value>
            </graph>');
		$this->renderText('<graph color="#47d552" title="Retrabajos" bullet="round">
              <value xid="0">'.round($retrabajosEnero, 2).'</value>
              <value xid="1">'.round($retrabajosFebrero, 2).'</value>
              <value xid="2">'.round($retrabajosMarzo, 2).'</value>
              <value xid="3">'.round($retrabajosAbril, 2).'</value>
              <value xid="4">'.round($retrabajosMayo, 2).'</value>
              <value xid="5">'.round($retrabajosJunio, 2).'</value>
              <value xid="6">'.round($retrabajosJulio, 2).'</value>
              <value xid="7">'.round($retrabajosAgosto, 2).'</value>
              <value xid="8">'.round($retrabajosSeptiembre, 2).'</value>
              <value xid="9">'.round($retrabajosOctubre, 2).'</value>
              <value xid="10">'.round($retrabajosNoviembre, 2).'</value>
              <value xid="11">'.round($retrabajosDiciembre, 2).'</value>
            </graph>');
		$this->renderText('<graph color="#f0a05f" title="Pérdidas de velocidad" bullet="round">
              <value xid="0">'.round($perdidasVelocidadEnero, 2).'</value>
              <value xid="1">'.round($perdidasVelocidadFebrero, 2).'</value>
              <value xid="2">'.round($perdidasVelocidadMarzo, 2).'</value>
              <value xid="3">'.round($perdidasVelocidadAbril, 2).'</value>
              <value xid="4">'.round($perdidasVelocidadMayo, 2).'</value>
              <value xid="5">'.round($perdidasVelocidadJunio, 2).'</value>
              <value xid="6">'.round($perdidasVelocidadJulio, 2).'</value>
              <value xid="7">'.round($perdidasVelocidadAgosto, 2).'</value>
              <value xid="8">'.round($perdidasVelocidadSeptiembre, 2).'</value>
              <value xid="9">'.round($perdidasVelocidadOctubre, 2).'</value>
              <value xid="10">'.round($perdidasVelocidadNoviembre, 2).'</value>
              <value xid="11">'.round($perdidasVelocidadDiciembre, 2).'</value>
            </graph>');
		return $this->renderText('
      </graphs>
    </chart>
    ');
		return $this->renderText('');
	}
	public function executeGenerarConfiguracionGraficoIndicadoresColumnas() {
		$this->renderText('
		<settings> 
    <data_type>csv</data_type> 
    <font>Tahoma</font>   
    <text_size>11</text_size>
    <depth>15</depth>
    <angle>30</angle>
    <column>
	    <width>50</width>     
	    <spacing>0</spacing> 
	    <grow_time>3</grow_time>
	    <grow_effect>elastic</grow_effect>
	    <data_labels>
        <![CDATA[{value} %]]>                                   
	    </data_labels>
	    <data_labels_position>above</data_labels_position>
	    <data_labels_always_on>true</data_labels_always_on>    
	    <balloon_text>                                                    
	    <![CDATA[{title}: {value} %]]>
	    </balloon_text>    
    </column>
    <plot_area>
    <margins>
    <left>50</left>
    <right>20</right>
    </margins>
    </plot_area>
    <grid>                 
    <category>                                                                
    <alpha>10</alpha>  
    <dashed>true</dashed>
    </category>
    <value>                      
    <alpha>10</alpha>    
    <dashed>true</dashed>
    </value>
    </grid>
    <values>                 
      <category>             
      <color>999999</color>
      <rotate>45</rotate>
      </category>
    <value>                    
    <min>0</min>             
    </value>
    </values>
    <axes>                       
    <category>                 
    <color>E7E7E7</color>    
    <width>1</width>         
    </category>
    <value>                    
    <color>#E7E7E7</color>       
    <width>1</width>         
    </value>
    </axes>  
    <balloon>                    
    <text_color>000000</text_color>
    <corner_radius>4</corner_radius>
    <border_width>3</border_width>
    <border_alpha>50</border_alpha>
    <border_color>#000000</border_color>
    <alpha>80</alpha>
    </balloon>
    <labels>                         
    <label>
    <x>0</x>
    <y>7</y>
    <text_color>000000</text_color>
    <text_size>13</text_size>
    <align>center</align>
    <text>
    <![CDATA[<b>Indicadores Vs. Metas</b>]]>
    </text>        
    </label>
    <label lid="1">
      <x>0</x> 
      <y>50%</y>
      <rotate>true</rotate> 
      <width>100</width>
      <align>left</align>
      <text>
        <![CDATA[<b>Porcentaje(%)</b>]]>
      </text> 
    </label>
    </labels>
    <legend>
    <enabled></enabled>
    <align></align>
    <border_alpha>100</border_alpha>
    <border_color>E7E7E7</border_color>
    <margins>5</margins>
    </legend>');
		require_once(dirname(__FILE__).'/../../../../../config/variablesGenerales.php');
		$this->renderText('<export_as_image>
    <file>'.$urlWeb.'flash/amcolumn/export.php</file>     
    <color>#CC0000</color>                      
    <alpha>50</alpha>                           
    </export_as_image>');
		return $this->renderText('<graphs>
    <graph>                          
    <type></type>                                                      
    <title>Indicador</title>             
    <color>add981</color>                  
    </graph>      
    <graph>                          
    <type></type>                                                      
    <title>Meta</title>             
    <color>7F8DA9</color>                  
    </graph>
    </graphs>
    </settings>');
	}
	public function executeGenerarDatosGraficoIndicadoresColumnas(sfWebRequest $request) {
		$anho = $request->getParameter('anho');
		$codigoMaquina = $request->getParameter('codigo_maquina');

		$params = array();
		if($request->getParameter('codigo_operario')!='-1') {
			$params['codigo_operario'] = $request->getParameter('codigo_operario');
		}
		if($request->getParameter('codigo_metodo')!='-1') {
			$params['codigo_metodo'] = $request->getParameter('codigo_metodo');
		}

		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		$TNPAnual = 0;
		$TPPAnual = 0;
		$TPNPAnual = 0;
		$TFAnual = 0;
		$TOAnual = 0;
		$TPAnual = 0;

		//		$cantidadHoras = RegistroUsoMaquinaPeer::calcularNumeroDiasDelAño($anho) * 24;

		$tiempoCalendario = 0;

		$numeroInyecciones = 0;
		$numeroReinyecciones = 0;

		if($codigoMaquina=='-1') {
			$maquinas = MaquinaPeer::doSelect(new Criteria());

			foreach($maquinas as $maquina) {
				//				                    $maquina = new Maquina();
				$codigoTemporalMaquina = $maquina->getMaqCodigo();

				$TNPAnual += RegistroUsoMaquinaPeer::calcularTNPAnhoEnHoras($codigoTemporalMaquina, $anho, $params);
				$TPPAnual += RegistroUsoMaquinaPeer::calcularTPPAnhoEnHoras($codigoTemporalMaquina, $anho, $params);
				$TPNPAnual += RegistroUsoMaquinaPeer::calcularTPNPAnhoEnHoras($codigoTemporalMaquina, $anho, $params, $inyeccionesEstandarPromedio);
				$tiempoCalendario += $maquina->calcularNumeroHorasActivasDelAño($anho);
				$TPAnual += RegistroUsoMaquinaPeer::calcularTPAnhoEnHoras($codigoTemporalMaquina, $anho, $params, $inyeccionesEstandarPromedio);
				$numeroInyecciones += RegistroUsoMaquinaPeer::contarNumeroInyeccionesObligatoriasAño($codigoTemporalMaquina, $anho, $params, $inyeccionesEstandarPromedio);
				$numeroReinyecciones += RegistroUsoMaquinaPeer::contarNumeroReinyeccionesAño($codigoTemporalMaquina, $anho, $params, $inyeccionesEstandarPromedio);
			}
		}
		else {
			$maquina = MaquinaPeer::retrieveByPK($codigoMaquina);
				
			$TNPAnual = RegistroUsoMaquinaPeer::calcularTNPAnhoEnHoras($codigoMaquina, $anho, $params);
			$TPPAnual = RegistroUsoMaquinaPeer::calcularTPPAnhoEnHoras($codigoMaquina, $anho, $params);
			$TPNPAnual = RegistroUsoMaquinaPeer::calcularTPNPAnhoEnHoras($codigoMaquina, $anho, $params, $inyeccionesEstandarPromedio);
			$tiempoCalendario = $maquina->calcularNumeroHorasActivasDelAño($anho);
			$TPAnual = RegistroUsoMaquinaPeer::calcularTPAnhoEnHoras($codigoMaquina, $anho, $params, $inyeccionesEstandarPromedio);
			$numeroInyecciones = RegistroUsoMaquinaPeer::contarNumeroInyeccionesObligatoriasAño($codigoMaquina, $anho, $params, $inyeccionesEstandarPromedio);
			$numeroReinyecciones = RegistroUsoMaquinaPeer::contarNumeroReinyeccionesAño($codigoMaquina, $anho, $params, $inyeccionesEstandarPromedio);
		}
		$TFAnual = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendario, $TPPAnual, $TNPAnual);
		$TOAnual = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFAnual, $TPNPAnual);

		$DAnual = RegistroUsoMaquinaPeer::calcularDisponibilidad($TOAnual, $TFAnual);
		$EAnual = RegistroUsoMaquinaPeer::calcularEficiencia($TPAnual, $TOAnual);
		$CAnual = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyecciones, $numeroReinyecciones);
		$OEEAnual = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DAnual, $EAnual, $CAnual);
		$AAnual = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFAnual, $tiempoCalendario);
		$PTEEAnual = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($AAnual, $OEEAnual);

		$criteria = new Criteria();
		$criteria->add(MetaAnualXIndicadorPeer::MEA_EMP_CODIGO, $empresa->getEmpCodigo());
		$criteria->add(MetaAnualXIndicadorPeer::MEA_IND_CODIGO, 7);
		$criteria->add(MetaAnualXIndicadorPeer::MEA_ANIO, $anho);
		$meta = MetaAnualXIndicadorPeer::doSelectOne($criteria);
		$metaAnualDisponibilidad = 0;
		if($meta) {
			$metaAnualDisponibilidad = $meta->getMeaValor();
		}

		$this->renderText('Disponibilidad;'.round($DAnual,2).";".$metaAnualDisponibilidad."\n");

		$criteria = new Criteria();
		$criteria->add(MetaAnualXIndicadorPeer::MEA_EMP_CODIGO, $empresa->getEmpCodigo());
		$criteria->add(MetaAnualXIndicadorPeer::MEA_IND_CODIGO, 8);
		$criteria->add(MetaAnualXIndicadorPeer::MEA_ANIO, $anho);
		$meta = MetaAnualXIndicadorPeer::doSelectOne($criteria);
		$metaAnualEficiencia = 0;
		if($meta) {
			$metaAnualEficiencia = $meta->getMeaValor();
		}

		$this->renderText('Eficiencia;'.round($EAnual,2).";".$metaAnualEficiencia."\n");

		$criteria = new Criteria();
		$criteria->add(MetaAnualXIndicadorPeer::MEA_EMP_CODIGO, $empresa->getEmpCodigo());
		$criteria->add(MetaAnualXIndicadorPeer::MEA_IND_CODIGO, 9);
		$criteria->add(MetaAnualXIndicadorPeer::MEA_ANIO, $anho);
		$meta = MetaAnualXIndicadorPeer::doSelectOne($criteria);
		$metaAnualCalidad = 0;
		if($meta) {
			$metaAnualCalidad = $meta->getMeaValor();
		}

		$this->renderText('Calidad;'.round($CAnual,2).";".$metaAnualCalidad."\n");

		$criteria = new Criteria();
		$criteria->add(MetaAnualXIndicadorPeer::MEA_EMP_CODIGO, $empresa->getEmpCodigo());
		$criteria->add(MetaAnualXIndicadorPeer::MEA_IND_CODIGO, 10);
		$criteria->add(MetaAnualXIndicadorPeer::MEA_ANIO, $anho);
		$meta = MetaAnualXIndicadorPeer::doSelectOne($criteria);
		$metaAnualAprovechamiento = 0;
		if($meta) {
			$metaAnualAprovechamiento = $meta->getMeaValor();
		}

		$this->renderText('Aprovechamiento;'.round($AAnual,2).";".$metaAnualAprovechamiento."\n");

		$criteria = new Criteria();
		$criteria->add(MetaAnualXIndicadorPeer::MEA_EMP_CODIGO, $empresa->getEmpCodigo());
		$criteria->add(MetaAnualXIndicadorPeer::MEA_IND_CODIGO, 11);
		$criteria->add(MetaAnualXIndicadorPeer::MEA_ANIO, $anho);
		$meta = MetaAnualXIndicadorPeer::doSelectOne($criteria);
		$metaAnualOEE = 0;
		if($meta) {
			$metaAnualOEE = $meta->getMeaValor();
		}

		$this->renderText('Efectividad Global;'.round($OEEAnual,2).";".$metaAnualOEE."\n");

		$metaAnualPTEE = ($metaAnualAprovechamiento * $metaAnualOEE) / 100;

		return $this->renderText('Productividad Total;'.round($PTEEAnual,2).";".$metaAnualPTEE."\n");
	}
	public function executeGenerarConfiguracionGraficoIndicadoresLineas() {
		$this->renderText('<?xml version="1.0" encoding="UTF-8"?>');
		$this->renderText('<settings>');
		$this->renderText('<grid>
    <x>                            
    <approx_count>10</approx_count>
    </x>                           
    </grid>');
		$this->renderText('<values>
    <x>                                
      <rotate>45</rotate>              
    </x>                               
    </values>');
		$this->renderText('<indicator>
    <enabled>true</enabled>                      
    <zoomable>false</zoomable>                   
    </indicator>');   
		$this->renderText('<legend>
    <graph_on_off>true</graph_on_off>
    <values>                     
    <text><![CDATA[]]></text>  
    </values>                   
    </legend>');
		require_once(dirname(__FILE__).'/../../../../../config/variablesGenerales.php');
		$this->renderText('<export_as_image>
    <file>'.$urlWeb.'flash/amline/export.php</file>     
    <color>#CC0000</color>                      
    <alpha>50</alpha>                           
    </export_as_image>');
		$this->renderText('<help>
    <button>                               
    </button>                              
    <balloon>                              
    <text><![CDATA[]]></text>            
    </balloon>
    </help>');
		$this->renderText('<labels>
    <label>
    <x>0</x>
    <y>7</y>
    <text_color>000000</text_color>
    <text_size>13</text_size>
    <align>center</align>
    <text>
    <![CDATA[<b>Indicadores / Mes</b>]]>
    </text>        
    </label>
    <label lid="1">
      <x>10</x> 
      <y>50%</y>
      <rotate>true</rotate> 
      <width>100</width>
      <align>left</align>
      <text>
        <![CDATA[<b>Porcentaje(%)</b>]]>
      </text> 
    </label>
    </labels>');
		$this->renderText('<guides>
    <max_min></max_min>          
    <guide>                      
    <axis>right</axis>         
    </guide>                     
    </guides>');
		return $this->renderText('</settings>');
	}
	public function executeGenerarDatosGraficoIndicadoresLineas(sfWebRequest $request) {
		$anho = $request->getParameter('anho');
		$codigoMaquina = $request->getParameter('codigo_maquina');

		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		$params = array();
		if($request->getParameter('codigo_operario')!='-1') {
			$params['codigo_operario'] = $request->getParameter('codigo_operario');
		}
		if($request->getParameter('codigo_metodo')!='-1') {
			$params['codigo_metodo'] = $request->getParameter('codigo_metodo');
		}

		$this->renderText('<?xml version="1.0" encoding="UTF-8"?>
    <chart>
      <series>
        <value xid="0">Enero</value>
        <value xid="1">Febrero</value>
        <value xid="2">Marzo</value>
        <value xid="3">Abril</value>
        <value xid="4">Mayo</value>
        <value xid="5">Junio</value>
        <value xid="6">Julio</value>
        <value xid="7">Agosto</value>
        <value xid="8">Septiembre</value>
        <value xid="9">Octubre</value>
        <value xid="10">Noviembre</value>
        <value xid="11">Diciembre</value>
      </series>
      <graphs>');
		$TNPEnero = 0;
		$TNPFebrero = 0;
		$TNPMarzo = 0;
		$TNPAbril = 0;
		$TNPMayo = 0;
		$TNPJunio = 0;
		$TNPJulio = 0;
		$TNPAgosto =  0;
		$TNPSeptiembre = 0;
		$TNPOctubre = 0;
		$TNPNoviembre = 0;
		$TNPDiciembre = 0;

		$TPPEnero = 0;
		$TPPFebrero = 0;
		$TPPMarzo = 0;
		$TPPAbril = 0;
		$TPPMayo = 0;
		$TPPJunio = 0;
		$TPPJulio = 0;
		$TPPAgosto = 0;
		$TPPSeptiembre = 0;
		$TPPOctubre = 0;
		$TPPNoviembre = 0;
		$TPPDiciembre = 0;

		$TPNPEnero = 0;
		$TPNPFebrero = 0;
		$TPNPMarzo = 0;
		$TPNPAbril = 0;
		$TPNPMayo = 0;
		$TPNPJunio = 0;
		$TPNPJulio = 0;
		$TPNPAgosto = 0;
		$TPNPSeptiembre = 0;
		$TPNPOctubre = 0;
		$TPNPNoviembre = 0;
		$TPNPDiciembre = 0;

		$TFEnero = 0;
		$TFFebrero = 0;
		$TFMarzo = 0;
		$TFAbril = 0;
		$TFMayo = 0;
		$TFJunio = 0;
		$TFJulio = 0;
		$TFAgosto = 0;
		$TFSeptiembre = 0;
		$TFOctubre = 0;
		$TFNoviembre = 0;
		$TFDiciembre = 0;

		$TOEnero = 0;
		$TOFebrero = 0;
		$TOMarzo = 0;
		$TOAbril = 0;
		$TOMayo = 0;
		$TOJunio = 0;
		$TOJulio = 0;
		$TOAgosto = 0;
		$TOSeptiembre = 0;
		$TOOctubre = 0;
		$TONoviembre = 0;
		$TODiciembre = 0;

		$TPEnero = 0;
		$TPFebrero = 0;
		$TPMarzo = 0;
		$TPAbril = 0;
		$TPMayo = 0;
		$TPJunio = 0;
		$TPJulio = 0;
		$TPAgosto = 0;
		$TPSeptiembre = 0;
		$TPOctubre = 0;
		$TPNoviembre = 0;
		$TPDiciembre = 0;

		//		$cantidadHorasEnero = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('01', $anho) * 24;
		//		$cantidadHorasFebrero = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('02', $anho) * 24;
		//		$cantidadHorasMarzo = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('03', $anho) * 24;
		//		$cantidadHorasAbril = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('04', $anho) * 24;
		//		$cantidadHorasMayo = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('05', $anho) * 24;
		//		$cantidadHorasJunio = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('06', $anho) * 24;
		//		$cantidadHorasJulio = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('07', $anho) * 24;
		//		$cantidadHorasAgosto = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('08', $anho) * 24;
		//		$cantidadHorasSeptiembre = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('09', $anho) * 24;
		//		$cantidadHorasOctubre = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('10', $anho) * 24;
		//		$cantidadHorasNoviembre = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('11', $anho) * 24;
		//		$cantidadHorasDiciembre = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('12', $anho) * 24;

		$tiempoCalendarioEnero = 0;
		$tiempoCalendarioFebrero = 0;
		$tiempoCalendarioMarzo = 0;
		$tiempoCalendarioAbril = 0;
		$tiempoCalendarioMayo = 0;
		$tiempoCalendarioJunio = 0;
		$tiempoCalendarioJulio = 0;
		$tiempoCalendarioAgosto = 0;
		$tiempoCalendarioSeptiembre = 0;
		$tiempoCalendarioOctubre = 0;
		$tiempoCalendarioNoviembre = 0;
		$tiempoCalendarioDiciembre = 0;

		$numeroInyeccionesEnero = 0;
		$numeroInyeccionesFebrero = 0;
		$numeroInyeccionesMarzo = 0;
		$numeroInyeccionesAbril = 0;
		$numeroInyeccionesMayo = 0;
		$numeroInyeccionesJunio = 0;
		$numeroInyeccionesJulio = 0;
		$numeroInyeccionesAgosto = 0;
		$numeroInyeccionesSeptiembre = 0;
		$numeroInyeccionesOctubre = 0;
		$numeroInyeccionesNoviembre = 0;
		$numeroInyeccionesDiciembre = 0;

		$numeroReinyeccionesEnero = 0;
		$numeroReinyeccionesFebrero = 0;
		$numeroReinyeccionesMarzo = 0;
		$numeroReinyeccionesAbril = 0;
		$numeroReinyeccionesMayo = 0;
		$numeroReinyeccionesJunio = 0;
		$numeroReinyeccionesJulio = 0;
		$numeroReinyeccionesAgosto = 0;
		$numeroReinyeccionesSeptiembre = 0;
		$numeroReinyeccionesOctubre = 0;
		$numeroReinyeccionesNoviembre = 0;
		$numeroReinyeccionesDiciembre = 0;

		if($codigoMaquina=='-1') {
			$maquinas = MaquinaPeer::doSelect(new Criteria());

			foreach($maquinas as $maquina) {
				//                    $maquina = new Maquina();
				$codigoTemporalMaquina = $maquina->getMaqCodigo();

				$TNPEnero += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 1, $anho, $params);
				$TNPFebrero += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 2, $anho, $params);
				$TNPMarzo += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 3, $anho, $params);
				$TNPAbril += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 4, $anho, $params);
				$TNPMayo += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 5, $anho, $params);
				$TNPJunio += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 6, $anho, $params);
				$TNPJulio += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 7, $anho, $params);
				$TNPAgosto += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 8, $anho, $params);
				$TNPSeptiembre += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 9, $anho, $params);
				$TNPOctubre += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 10, $anho, $params);
				$TNPNoviembre += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 11, $anho, $params);
				$TNPDiciembre += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 12, $anho, $params);

				$TPPEnero += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 1, $anho, $params);
				$TPPFebrero += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 2, $anho, $params);
				$TPPMarzo += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 3, $anho, $params);
				$TPPAbril += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 4, $anho, $params);
				$TPPMayo += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 5, $anho, $params);
				$TPPJunio += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 6, $anho, $params);
				$TPPJulio += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 7, $anho, $params);
				$TPPAgosto += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 8, $anho, $params);
				$TPPSeptiembre += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 9, $anho, $params);
				$TPPOctubre += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 10, $anho, $params);
				$TPPNoviembre += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 11, $anho, $params);
				$TPPDiciembre += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 12, $anho, $params);

				$TPNPEnero += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPFebrero += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPMarzo += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPAbril += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPMayo += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPJunio += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPJulio += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPAgosto += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPSeptiembre += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPOctubre += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPNoviembre += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPDiciembre += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

				$TPEnero += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
				$TPFebrero += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
				$TPMarzo += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
				$TPAbril += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
				$TPMayo += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
				$TPJunio += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
				$TPJulio += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
				$TPAgosto += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
				$TPSeptiembre += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
				$TPOctubre += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNoviembre += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
				$TPDiciembre += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

				$numeroInyeccionesEnero += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroInyeccionesFebrero += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroInyeccionesMarzo += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroInyeccionesAbril += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroInyeccionesMayo += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroInyeccionesJunio += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroInyeccionesJulio += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroInyeccionesAgosto += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroInyeccionesSeptiembre += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroInyeccionesOctubre += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroInyeccionesNoviembre += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroInyeccionesDiciembre += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoTemporalMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

				$numeroReinyeccionesEnero += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroReinyeccionesFebrero += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroReinyeccionesMarzo += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroReinyeccionesAbril += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroReinyeccionesMayo += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroReinyeccionesJunio += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroReinyeccionesJulio += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroReinyeccionesAgosto += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroReinyeccionesSeptiembre += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroReinyeccionesOctubre += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroReinyeccionesNoviembre += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
				$numeroReinyeccionesDiciembre += RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoTemporalMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

				$tiempoCalendarioEnero += $maquina->calcularNumeroHorasActivasDelMes(1, $anho);
				$tiempoCalendarioFebrero += $maquina->calcularNumeroHorasActivasDelMes(2, $anho);
				$tiempoCalendarioMarzo += $maquina->calcularNumeroHorasActivasDelMes(3, $anho);
				$tiempoCalendarioAbril += $maquina->calcularNumeroHorasActivasDelMes(4, $anho);
				$tiempoCalendarioMayo += $maquina->calcularNumeroHorasActivasDelMes(5, $anho);
				$tiempoCalendarioJunio += $maquina->calcularNumeroHorasActivasDelMes(6, $anho);
				$tiempoCalendarioJulio += $maquina->calcularNumeroHorasActivasDelMes(7, $anho);
				$tiempoCalendarioAgosto += $maquina->calcularNumeroHorasActivasDelMes(8, $anho);
				$tiempoCalendarioSeptiembre += $maquina->calcularNumeroHorasActivasDelMes(9, $anho);
				$tiempoCalendarioOctubre += $maquina->calcularNumeroHorasActivasDelMes(10, $anho);
				$tiempoCalendarioNoviembre += $maquina->calcularNumeroHorasActivasDelMes(11, $anho);
				$tiempoCalendarioDiciembre += $maquina->calcularNumeroHorasActivasDelMes(12, $anho);
			}
		}
		else {
			$maquina = MaquinaPeer::retrieveByPK($codigoMaquina);

			$TNPEnero = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 1, $anho, $params);
			$TNPFebrero = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 2, $anho, $params);
			$TNPMarzo = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 3, $anho, $params);
			$TNPAbril = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 4, $anho, $params);
			$TNPMayo = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 5, $anho, $params);
			$TNPJunio = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 6, $anho, $params);
			$TNPJulio = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 7, $anho, $params);
			$TNPAgosto = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 8, $anho, $params);
			$TNPSeptiembre = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 9, $anho, $params);
			$TNPOctubre = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 10, $anho, $params);
			$TNPNoviembre = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 11, $anho, $params);
			$TNPDiciembre = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 12, $anho, $params);

			$TPPEnero = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 1, $anho, $params);
			$TPPFebrero = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 2, $anho, $params);
			$TPPMarzo = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 3, $anho, $params);
			$TPPAbril = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 4, $anho, $params);
			$TPPMayo = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 5, $anho, $params);
			$TPPJunio = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 6, $anho, $params);
			$TPPJulio = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 7, $anho, $params);
			$TPPAgosto = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 8, $anho, $params);
			$TPPSeptiembre = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 9, $anho, $params);
			$TPPOctubre = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 10, $anho, $params);
			$TPPNoviembre = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 11, $anho, $params);
			$TPPDiciembre = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 12, $anho, $params);

			$TPNPEnero = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPFebrero = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPMarzo = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPAbril = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPMayo = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPJunio = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPJulio = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPAgosto = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPSeptiembre = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPOctubre = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPNoviembre = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPDiciembre = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

			$TPEnero = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
			$TPFebrero = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
			$TPMarzo = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
			$TPAbril = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
			$TPMayo = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
			$TPJunio = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
			$TPJulio = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
			$TPAgosto = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
			$TPSeptiembre = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
			$TPOctubre = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNoviembre = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
			$TPDiciembre = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

			$numeroInyeccionesEnero = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroInyeccionesFebrero = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroInyeccionesMarzo = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroInyeccionesAbril = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroInyeccionesMayo = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroInyeccionesJunio = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroInyeccionesJulio = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroInyeccionesAgosto = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroInyeccionesSeptiembre = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroInyeccionesOctubre = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroInyeccionesNoviembre = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroInyeccionesDiciembre = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasMes($codigoMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

			$numeroReinyeccionesEnero = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroReinyeccionesFebrero = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroReinyeccionesMarzo = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroReinyeccionesAbril = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroReinyeccionesMayo = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroReinyeccionesJunio = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroReinyeccionesJulio = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroReinyeccionesAgosto = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroReinyeccionesSeptiembre = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroReinyeccionesOctubre = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroReinyeccionesNoviembre = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
			$numeroReinyeccionesDiciembre = RegistroUsoMaquinaPeer::contarReinyeccionesMes($codigoMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

			$tiempoCalendarioEnero = $maquina->calcularNumeroHorasActivasDelMes(1, $anho);
			$tiempoCalendarioFebrero = $maquina->calcularNumeroHorasActivasDelMes(2, $anho);
			$tiempoCalendarioMarzo = $maquina->calcularNumeroHorasActivasDelMes(3, $anho);
			$tiempoCalendarioAbril = $maquina->calcularNumeroHorasActivasDelMes(4, $anho);
			$tiempoCalendarioMayo = $maquina->calcularNumeroHorasActivasDelMes(5, $anho);
			$tiempoCalendarioJunio = $maquina->calcularNumeroHorasActivasDelMes(6, $anho);
			$tiempoCalendarioJulio = $maquina->calcularNumeroHorasActivasDelMes(7, $anho);
			$tiempoCalendarioAgosto = $maquina->calcularNumeroHorasActivasDelMes(8, $anho);
			$tiempoCalendarioSeptiembre = $maquina->calcularNumeroHorasActivasDelMes(9, $anho);
			$tiempoCalendarioOctubre = $maquina->calcularNumeroHorasActivasDelMes(10, $anho);
			$tiempoCalendarioNoviembre = $maquina->calcularNumeroHorasActivasDelMes(11, $anho);
			$tiempoCalendarioDiciembre = $maquina->calcularNumeroHorasActivasDelMes(12, $anho);
		}
		$TFEnero = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioEnero, $TPPEnero, $TNPEnero);
		$TFFebrero = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioFebrero, $TPPFebrero, $TNPFebrero);
		$TFMarzo = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioMarzo, $TPPMarzo, $TNPMarzo);
		$TFAbril = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioAbril, $TPPAbril, $TNPAbril);
		$TFMayo = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioMayo, $TPPMayo, $TNPMayo);
		$TFJunio = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioJunio, $TPPJunio, $TNPJunio);
		$TFJulio = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioJulio, $TPPJulio, $TNPJulio);
		$TFAgosto = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioAgosto, $TPPAgosto, $TNPAgosto);
		$TFSeptiembre = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioSeptiembre, $TPPSeptiembre, $TNPSeptiembre);
		$TFOctubre = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioOctubre, $TPPOctubre, $TNPOctubre);
		$TFNoviembre = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioNoviembre, $TPPNoviembre, $TNPNoviembre);
		$TFDiciembre = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioDiciembre, $TPPDiciembre, $TNPDiciembre);

		$TOEnero = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFEnero, $TPNPEnero);
		$TOFebrero = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFFebrero, $TPNPFebrero);
		$TOMarzo = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFMarzo, $TPNPMarzo);
		$TOAbril = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFAbril, $TPNPAbril);
		$TOMayo = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFMayo, $TPNPMayo);
		$TOJunio = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFJunio, $TPNPJunio);
		$TOJulio = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFJulio, $TPNPJulio);
		$TOAgosto = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFAgosto, $TPNPAgosto);
		$TOSeptiembre = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFSeptiembre, $TPNPSeptiembre);
		$TOOctubre = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFOctubre, $TPNPOctubre);
		$TONoviembre = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFNoviembre, $TPNPNoviembre);
		$TODiciembre = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFDiciembre, $TPNPDiciembre);

		$DEnero = RegistroUsoMaquinaPeer::calcularDisponibilidad($TOEnero, $TFEnero);
		$DFebrero = RegistroUsoMaquinaPeer::calcularDisponibilidad($TOFebrero, $TFFebrero);
		$DMarzo = RegistroUsoMaquinaPeer::calcularDisponibilidad($TOMarzo, $TFMarzo);
		$DAbril = RegistroUsoMaquinaPeer::calcularDisponibilidad($TOAbril, $TFAbril);
		$DMayo = RegistroUsoMaquinaPeer::calcularDisponibilidad($TOMayo, $TFMayo);
		$DJunio = RegistroUsoMaquinaPeer::calcularDisponibilidad($TOJunio, $TFJunio);
		$DJulio = RegistroUsoMaquinaPeer::calcularDisponibilidad($TOJulio, $TFJulio);
		$DAgosto = RegistroUsoMaquinaPeer::calcularDisponibilidad($TOAgosto, $TFAgosto);
		$DSeptiembre = RegistroUsoMaquinaPeer::calcularDisponibilidad($TOSeptiembre, $TFSeptiembre);
		$DOctubre = RegistroUsoMaquinaPeer::calcularDisponibilidad($TOOctubre, $TFOctubre);
		$DNoviembre = RegistroUsoMaquinaPeer::calcularDisponibilidad($TONoviembre, $TFNoviembre);
		$DDiciembre = RegistroUsoMaquinaPeer::calcularDisponibilidad($TODiciembre, $TFDiciembre);

		$EEnero = RegistroUsoMaquinaPeer::calcularEficiencia($TPEnero, $TOEnero);
		$EFebrero = RegistroUsoMaquinaPeer::calcularEficiencia($TPFebrero, $TOFebrero);
		$EMarzo = RegistroUsoMaquinaPeer::calcularEficiencia($TPMarzo, $TOMarzo);
		$EAbril = RegistroUsoMaquinaPeer::calcularEficiencia($TPAbril, $TOAbril);
		$EMayo = RegistroUsoMaquinaPeer::calcularEficiencia($TPMayo, $TOMayo);
		$EJunio = RegistroUsoMaquinaPeer::calcularEficiencia($TPJunio, $TOJunio);
		$EJulio = RegistroUsoMaquinaPeer::calcularEficiencia($TPJulio, $TOJulio);
		$EAgosto = RegistroUsoMaquinaPeer::calcularEficiencia($TPAgosto, $TOAgosto);
		$ESeptiembre = RegistroUsoMaquinaPeer::calcularEficiencia($TPSeptiembre, $TOSeptiembre);
		$EOctubre = RegistroUsoMaquinaPeer::calcularEficiencia($TPOctubre, $TOOctubre);
		$ENoviembre = RegistroUsoMaquinaPeer::calcularEficiencia($TPNoviembre, $TONoviembre);
		$EDiciembre = RegistroUsoMaquinaPeer::calcularEficiencia($TPDiciembre, $TODiciembre);

		$CEnero = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesEnero, $numeroReinyeccionesEnero);
		$CFebrero = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesFebrero, $numeroReinyeccionesFebrero);
		$CMarzo = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesMarzo, $numeroReinyeccionesMarzo);
		$CAbril = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesAbril, $numeroReinyeccionesAbril);
		$CMayo = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesMayo, $numeroReinyeccionesMayo);
		$CJunio = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesJunio, $numeroReinyeccionesJunio);
		$CJulio = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesJulio, $numeroReinyeccionesJulio);
		$CAgosto = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesAgosto, $numeroReinyeccionesAgosto);
		$CSeptiembre = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesSeptiembre, $numeroReinyeccionesSeptiembre);
		$COctubre = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesOctubre, $numeroReinyeccionesOctubre);
		$CNoviembre = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesNoviembre, $numeroReinyeccionesNoviembre);
		$CDiciembre = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesDiciembre, $numeroReinyeccionesDiciembre);

		$OEEEnero = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DEnero, $EEnero, $CEnero);
		$OEEFebrero = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DFebrero, $EFebrero, $CFebrero);
		$OEEMarzo = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DMarzo, $EMarzo, $CMarzo);
		$OEEAbril = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DAbril, $EAbril, $CAbril);
		$OEEMayo = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DMayo, $EMayo, $CMayo);
		$OEEJunio = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DJunio, $EJunio, $CJunio);
		$OEEJulio = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DJulio, $EJulio, $CJulio);
		$OEEAgosto = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DAgosto, $EAgosto, $CAgosto);
		$OEESeptiembre = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DSeptiembre, $ESeptiembre, $CSeptiembre);
		$OEEOctubre = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DOctubre, $EOctubre, $COctubre);
		$OEENoviembre = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DNoviembre, $ENoviembre, $CNoviembre);
		$OEEDiciembre = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($DDiciembre, $EDiciembre, $CDiciembre);

		$AEnero = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFEnero, $tiempoCalendarioEnero);
		$AFebrero = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFFebrero, $tiempoCalendarioFebrero);
		$AMarzo = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFMarzo, $tiempoCalendarioMarzo);
		$AAbril = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFAbril, $tiempoCalendarioAbril);
		$AMayo = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFMayo, $tiempoCalendarioMayo);
		$AJunio = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFJunio, $tiempoCalendarioJunio);
		$AJulio = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFJulio, $tiempoCalendarioJulio);
		$AAgosto = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFAgosto, $tiempoCalendarioAgosto);
		$ASeptiembre = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFSeptiembre, $tiempoCalendarioSeptiembre);
		$AOctubre = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFOctubre, $tiempoCalendarioOctubre);
		$ANoviembre = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFNoviembre, $tiempoCalendarioNoviembre);
		$ADiciembre = RegistroUsoMaquinaPeer::calcularAprovechamiento($TFDiciembre, $tiempoCalendarioDiciembre);

		$PTEEEnero = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($AEnero, $OEEEnero);
		$PTEEFebrero = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($AFebrero, $OEEFebrero);
		$PTEEMarzo = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($AMarzo, $OEEMarzo);
		$PTEEAbril = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($AAbril, $OEEAbril);
		$PTEEMayo = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($AMayo, $OEEMayo);
		$PTEEJunio = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($AJunio, $OEEJunio);
		$PTEEJulio = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($AJulio, $OEEJulio);
		$PTEEAgosto = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($AAgosto, $OEEAgosto);
		$PTEESeptiembre = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($ASeptiembre, $OEESeptiembre);
		$PTEEOctubre = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($AOctubre, $OEEOctubre);
		$PTEENoviembre = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($ANoviembre, $OEENoviembre);
		$PTEEDiciembre = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($ADiciembre, $OEEDiciembre);

		$this->renderText('<graph color="#ffdc44" title="A" bullet="round">
          <value xid="0">'.round($AEnero, 2).'</value>
          <value xid="1">'.round($AFebrero, 2).'</value>
          <value xid="2">'.round($AMarzo, 2).'</value>
          <value xid="3">'.round($AAbril, 2).'</value>
          <value xid="4">'.round($AMayo, 2).'</value>
          <value xid="5">'.round($AJunio, 2).'</value>
          <value xid="6">'.round($AJulio, 2).'</value>
          <value xid="7">'.round($AAgosto, 2).'</value>
          <value xid="8">'.round($ASeptiembre, 2).'</value>
          <value xid="9">'.round($AOctubre, 2).'</value>
          <value xid="10">'.round($ANoviembre, 2).'</value>
          <value xid="11">'.round($ADiciembre, 2).'</value>
        </graph>');
		$this->renderText('<graph color="#47d552" title="D" bullet="round">
          <value xid="0">'.round($DEnero, 2).'</value>     
          <value xid="1">'.round($DFebrero, 2).'</value>   
          <value xid="2">'.round($DMarzo, 2).'</value>     
          <value xid="3">'.round($DAbril, 2).'</value>     
          <value xid="4">'.round($DMayo, 2).'</value>      
          <value xid="5">'.round($DJunio, 2).'</value>     
          <value xid="6">'.round($DJulio, 2).'</value>     
          <value xid="7">'.round($DAgosto, 2).'</value>    
          <value xid="8">'.round($DSeptiembre, 2).'</value>
          <value xid="9">'.round($DOctubre, 2).'</value>   
          <value xid="10">'.round($DNoviembre, 2).'</value>
          <value xid="11">'.round($DDiciembre, 2).'</value>
        </graph>');
		$this->renderText('<graph color="#ff5454" title="E" bullet="round">
          <value xid="0">'.round($EEnero, 2).'</value>     
          <value xid="1">'.round($EFebrero, 2).'</value>   
          <value xid="2">'.round($EMarzo, 2).'</value>     
          <value xid="3">'.round($EAbril, 2).'</value>     
          <value xid="4">'.round($EMayo, 2).'</value>      
          <value xid="5">'.round($EJunio, 2).'</value>     
          <value xid="6">'.round($EJulio, 2).'</value>     
          <value xid="7">'.round($EAgosto, 2).'</value>    
          <value xid="8">'.round($ESeptiembre, 2).'</value>
          <value xid="9">'.round($EOctubre, 2).'</value>   
          <value xid="10">'.round($ENoviembre, 2).'</value>
          <value xid="11">'.round($EDiciembre, 2).'</value>
        </graph>');
		$this->renderText('<graph color="#f0a05f" title="C" bullet="round">
          <value xid="0">'.round($CEnero, 2).'</value>     
          <value xid="1">'.round($CFebrero, 2).'</value>   
          <value xid="2">'.round($CMarzo, 2).'</value>     
          <value xid="3">'.round($CAbril, 2).'</value>     
          <value xid="4">'.round($CMayo, 2).'</value>      
          <value xid="5">'.round($CJunio, 2).'</value>     
          <value xid="6">'.round($CJulio, 2).'</value>     
          <value xid="7">'.round($CAgosto, 2).'</value>    
          <value xid="8">'.round($CSeptiembre, 2).'</value>
          <value xid="9">'.round($COctubre, 2).'</value>   
          <value xid="10">'.round($CNoviembre, 2).'</value>
          <value xid="11">'.round($CDiciembre, 2).'</value>
        </graph>');		
		$this->renderText('<graph color="#72a8cd" title="OEE" bullet="round">
          <value xid="0">'.round($OEEEnero, 2).'</value>     
          <value xid="1">'.round($OEEFebrero, 2).'</value>   
          <value xid="2">'.round($OEEMarzo, 2).'</value>     
          <value xid="3">'.round($OEEAbril, 2).'</value>     
          <value xid="4">'.round($OEEMayo, 2).'</value>      
          <value xid="5">'.round($OEEJunio, 2).'</value>     
          <value xid="6">'.round($OEEJulio, 2).'</value>     
          <value xid="7">'.round($OEEAgosto, 2).'</value>    
          <value xid="8">'.round($OEESeptiembre, 2).'</value>
          <value xid="9">'.round($OEEOctubre, 2).'</value>   
          <value xid="10">'.round($OEENoviembre, 2).'</value>
          <value xid="11">'.round($OEEDiciembre, 2).'</value>
        </graph>');
		$this->renderText('<graph color="#72a8cd" title="PTEE" bullet="round">
          <value xid="0">'.round($PTEEEnero, 2).'</value>     
          <value xid="1">'.round($PTEEFebrero, 2).'</value>   
          <value xid="2">'.round($PTEEMarzo, 2).'</value>     
          <value xid="3">'.round($PTEEAbril, 2).'</value>     
          <value xid="4">'.round($PTEEMayo, 2).'</value>      
          <value xid="5">'.round($PTEEJunio, 2).'</value>     
          <value xid="6">'.round($PTEEJulio, 2).'</value>     
          <value xid="7">'.round($PTEEAgosto, 2).'</value>    
          <value xid="8">'.round($PTEESeptiembre, 2).'</value>
          <value xid="9">'.round($PTEEOctubre, 2).'</value>   
          <value xid="10">'.round($PTEENoviembre, 2).'</value>
          <value xid="11">'.round($PTEEDiciembre, 2).'</value>
        </graph>');
		$this->renderText('
      </graphs>
    </chart>
    ');
		return $this->renderText('');
	}
	public function executeListarMetodos() {
		$metodos = MetodoPeer::doSelect(new Criteria());

		$result = array();
		$data = array();

		foreach($metodos as $metodo) {
			$fields = array();

			//			$metodo = new Metodo();

			$fields['codigo'] = $metodo->getMetCodigo();
			$fields['nombre'] = $metodo->getMetNombre();

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

			$fields['nombre'] = $empleado->getNombreCompleto();

			$data[] = $fields;
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
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
			$fields['nombre'] = $maquina->getMaqNombre();//$maquina->getNombreCompleto();

			$data[] = $fields;
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
	public function executeListarMaquinas() {
		$maquinas = MaquinaPeer::doSelect(new Criteria());

		$result = array();
		$data = array();

		foreach($maquinas as $maquina) {
			$fields = array();
			$fields['codigo'] = $maquina->getMaqCodigo();
			$fields['nombre'] = $maquina->getMaqNombre();//$maquina->getNombreCompleto();

			$data[] = $fields;
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
	public function executeGenerarConfiguracionGraficoTiemposLineas() {
		$this->renderText('<?xml version="1.0" encoding="UTF-8"?>');
		$this->renderText('<settings>');
		$this->renderText('<grid>
    <x>                            
    <approx_count>10</approx_count>
    </x>                           
    </grid>');
		$this->renderText('<values>
    <x>                                
      <rotate>45</rotate>              
    </x>                               
    </values>');
		$this->renderText('<indicator>
    <enabled>true</enabled>                      
    <zoomable>false</zoomable>                   
    </indicator>');   
		$this->renderText('<legend>
    <graph_on_off>true</graph_on_off>
    <values>                     
    <text><![CDATA[]]></text>  
    </values>                   
    </legend>');
		require_once(dirname(__FILE__).'/../../../../../config/variablesGenerales.php');
		$this->renderText('<export_as_image>
    <file>'.$urlWeb.'flash/amline/export.php</file>     
    <color>#CC0000</color>                      
    <alpha>50</alpha>                           
    </export_as_image>');
		$this->renderText('<help>
    <button>                               
    </button>                              
    <balloon>                              
    <text><![CDATA[]]></text>            
    </balloon>                             
    </help>');
		$this->renderText('<labels>
    <label>
    <x>0</x>
    <y>7</y>
    <text_color>000000</text_color>
    <text_size>13</text_size>
    <align>center</align>
    <text>
    <![CDATA[<b>Tiempos para cálculo OEE / Mes</b>]]>
    </text>        
    </label>
    <label lid="1">
      <x>10</x> 
      <y>50%</y>
      <rotate>true</rotate> 
      <width>100</width>
      <align>left</align>
      <text>
        <![CDATA[<b>Días</b>]]>
      </text> 
    </label>
    </labels>');
		$this->renderText('<guides>
    <max_min></max_min>          
    <guide>                      
    <axis>right</axis>         
    </guide>                     
    </guides>');
		return $this->renderText('</settings>');
	}
	public function executeGenerarDatosGraficoTiemposLineas(sfWebRequest $request) {
		$anho = $request->getParameter('anho');

		$params = array();
		if($request->getParameter('codigo_operario')!='-1') {
			$params['codigo_operario'] = $request->getParameter('codigo_operario');
		}
		if($request->getParameter('codigo_metodo')!='-1') {
			$params['codigo_metodo'] = $request->getParameter('codigo_metodo');
		}

		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		$codigoMaquina = $request->getParameter('codigo_maquina');
		$this->renderText('<?xml version="1.0" encoding="UTF-8"?>
    <chart>
      <series>
        <value xid="0">Enero</value>
        <value xid="1">Febrero</value>
        <value xid="2">Marzo</value>
        <value xid="3">Abril</value>
        <value xid="4">Mayo</value>
        <value xid="5">Junio</value>
        <value xid="6">Julio</value>
        <value xid="7">Agosto</value>
        <value xid="8">Septiembre</value>
        <value xid="9">Octubre</value>
        <value xid="10">Noviembre</value>
        <value xid="11">Diciembre</value>
      </series>
      <graphs>');
		$TNPEnero = 0;
		$TNPFebrero = 0;
		$TNPMarzo = 0;
		$TNPAbril = 0;
		$TNPMayo = 0;
		$TNPJunio = 0;
		$TNPJulio = 0;
		$TNPAgosto =  0;
		$TNPSeptiembre = 0;
		$TNPOctubre = 0;
		$TNPNoviembre = 0;
		$TNPDiciembre = 0;

		$TPPEnero = 0;
		$TPPFebrero = 0;
		$TPPMarzo = 0;
		$TPPAbril = 0;
		$TPPMayo = 0;
		$TPPJunio = 0;
		$TPPJulio = 0;
		$TPPAgosto = 0;
		$TPPSeptiembre = 0;
		$TPPOctubre = 0;
		$TPPNoviembre = 0;
		$TPPDiciembre = 0;

		$TPNPEnero = 0;
		$TPNPFebrero = 0;
		$TPNPMarzo = 0;
		$TPNPAbril = 0;
		$TPNPMayo = 0;
		$TPNPJunio = 0;
		$TPNPJulio = 0;
		$TPNPAgosto = 0;
		$TPNPSeptiembre = 0;
		$TPNPOctubre = 0;
		$TPNPNoviembre = 0;
		$TPNPDiciembre = 0;

		$TFEnero = 0;
		$TFFebrero = 0;
		$TFMarzo = 0;
		$TFAbril = 0;
		$TFMayo = 0;
		$TFJunio = 0;
		$TFJulio = 0;
		$TFAgosto = 0;
		$TFSeptiembre = 0;
		$TFOctubre = 0;
		$TFNoviembre = 0;
		$TFDiciembre = 0;

		$TOEnero = 0;
		$TOFebrero = 0;
		$TOMarzo = 0;
		$TOAbril = 0;
		$TOMayo = 0;
		$TOJunio = 0;
		$TOJulio = 0;
		$TOAgosto = 0;
		$TOSeptiembre = 0;
		$TOOctubre = 0;
		$TONoviembre = 0;
		$TODiciembre = 0;

		//		$cantidadHorasEnero = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('01', $anho) * 24;
		//		$cantidadHorasFebrero = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('02', $anho) * 24;
		//		$cantidadHorasMarzo = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('03', $anho) * 24;
		//		$cantidadHorasAbril = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('04', $anho) * 24;
		//		$cantidadHorasMayo = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('05', $anho) * 24;
		//		$cantidadHorasJunio = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('06', $anho) * 24;
		//		$cantidadHorasJulio = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('07', $anho) * 24;
		//		$cantidadHorasAgosto = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('08', $anho) * 24;
		//		$cantidadHorasSeptiembre = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('09', $anho) * 24;
		//		$cantidadHorasOctubre = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('10', $anho) * 24;
		//		$cantidadHorasNoviembre = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('11', $anho) * 24;
		//		$cantidadHorasDiciembre = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes('12', $anho) * 24;

		$tiempoCalendarioEnero = 0;
		$tiempoCalendarioFebrero = 0;
		$tiempoCalendarioMarzo = 0;
		$tiempoCalendarioAbril = 0;
		$tiempoCalendarioMayo = 0;
		$tiempoCalendarioJunio = 0;
		$tiempoCalendarioJulio = 0;
		$tiempoCalendarioAgosto = 0;
		$tiempoCalendarioSeptiembre = 0;
		$tiempoCalendarioOctubre = 0;
		$tiempoCalendarioNoviembre = 0;
		$tiempoCalendarioDiciembre = 0;

		if($codigoMaquina=='-1') {
			$maquinas = MaquinaPeer::doSelect(new Criteria());

			foreach($maquinas as $maquina) {
				//				                    $maquina = new Maquina();
				$codigoTemporalMaquina = $maquina->getMaqCodigo();

				$TNPEnero += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 1, $anho, $params);
				$TNPFebrero += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 2, $anho, $params);
				$TNPMarzo += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 3, $anho, $params);
				$TNPAbril += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 4, $anho, $params);
				$TNPMayo += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 5, $anho, $params);
				$TNPJunio += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 6, $anho, $params);
				$TNPJulio += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 7, $anho, $params);
				$TNPAgosto += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 8, $anho, $params);
				$TNPSeptiembre += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 9, $anho, $params);
				$TNPOctubre += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 10, $anho, $params);
				$TNPNoviembre += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 11, $anho, $params);
				$TNPDiciembre += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, 12, $anho, $params);

				$TPPEnero += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 1, $anho, $params);
				$TPPFebrero += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 2, $anho, $params);
				$TPPMarzo += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 3, $anho, $params);
				$TPPAbril += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 4, $anho, $params);
				$TPPMayo += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 5, $anho, $params);
				$TPPJunio += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 6, $anho, $params);
				$TPPJulio += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 7, $anho, $params);
				$TPPAgosto += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 8, $anho, $params);
				$TPPSeptiembre += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 9, $anho, $params);
				$TPPOctubre += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 10, $anho, $params);
				$TPPNoviembre += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 11, $anho, $params);
				$TPPDiciembre += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, 12, $anho, $params);

				$TPNPEnero += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPFebrero += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPMarzo += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPAbril += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPMayo += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPJunio += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPJulio += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPAgosto += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPSeptiembre += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPOctubre += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPNoviembre += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
				$TPNPDiciembre += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

				$tiempoCalendarioEnero += $maquina->calcularNumeroHorasActivasDelMes(1, $anho);
				$tiempoCalendarioFebrero += $maquina->calcularNumeroHorasActivasDelMes(2, $anho);
				$tiempoCalendarioMarzo += $maquina->calcularNumeroHorasActivasDelMes(3, $anho);
				$tiempoCalendarioAbril += $maquina->calcularNumeroHorasActivasDelMes(4, $anho);
				$tiempoCalendarioMayo += $maquina->calcularNumeroHorasActivasDelMes(5, $anho);
				$tiempoCalendarioJunio += $maquina->calcularNumeroHorasActivasDelMes(6, $anho);
				$tiempoCalendarioJulio += $maquina->calcularNumeroHorasActivasDelMes(7, $anho);
				$tiempoCalendarioAgosto += $maquina->calcularNumeroHorasActivasDelMes(8, $anho);
				$tiempoCalendarioSeptiembre += $maquina->calcularNumeroHorasActivasDelMes(9, $anho);
				$tiempoCalendarioOctubre += $maquina->calcularNumeroHorasActivasDelMes(10, $anho);
				$tiempoCalendarioNoviembre += $maquina->calcularNumeroHorasActivasDelMes(11, $anho);
				$tiempoCalendarioDiciembre += $maquina->calcularNumeroHorasActivasDelMes(12, $anho);
			}
		}
		else {
			$maquina = MaquinaPeer::retrieveByPK($codigoMaquina);

			$TNPEnero = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 1, $anho, $params);
			$TNPFebrero = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 2, $anho, $params);
			$TNPMarzo = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 3, $anho, $params);
			$TNPAbril = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 4, $anho, $params);
			$TNPMayo = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 5, $anho, $params);
			$TNPJunio = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 6, $anho, $params);
			$TNPJulio = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 7, $anho, $params);
			$TNPAgosto = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 8, $anho, $params);
			$TNPSeptiembre = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 9, $anho, $params);
			$TNPOctubre = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 10, $anho, $params);
			$TNPNoviembre = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 11, $anho, $params);
			$TNPDiciembre = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoMaquina, 12, $anho, $params);

			$TPPEnero = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 1, $anho, $params);
			$TPPFebrero = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 2, $anho, $params);
			$TPPMarzo = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 3, $anho, $params);
			$TPPAbril = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 4, $anho, $params);
			$TPPMayo = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 5, $anho, $params);
			$TPPJunio = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 6, $anho, $params);
			$TPPJulio = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 7, $anho, $params);
			$TPPAgosto = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 8, $anho, $params);
			$TPPSeptiembre = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 9, $anho, $params);
			$TPPOctubre = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 10, $anho, $params);
			$TPPNoviembre = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 11, $anho, $params);
			$TPPDiciembre = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoMaquina, 12, $anho, $params);

			$TPNPEnero = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 1, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPFebrero = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 2, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPMarzo = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 3, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPAbril = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 4, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPMayo = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 5, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPJunio = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 6, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPJulio = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 7, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPAgosto = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 8, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPSeptiembre = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 9, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPOctubre = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 10, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPNoviembre = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 11, $anho, $params,$inyeccionesEstandarPromedio);
			$TPNPDiciembre = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoMaquina, 12, $anho, $params,$inyeccionesEstandarPromedio);

			$tiempoCalendarioEnero = $maquina->calcularNumeroHorasActivasDelMes(1, $anho);
			$tiempoCalendarioFebrero = $maquina->calcularNumeroHorasActivasDelMes(2, $anho);
			$tiempoCalendarioMarzo = $maquina->calcularNumeroHorasActivasDelMes(3, $anho);
			$tiempoCalendarioAbril = $maquina->calcularNumeroHorasActivasDelMes(4, $anho);
			$tiempoCalendarioMayo = $maquina->calcularNumeroHorasActivasDelMes(5, $anho);
			$tiempoCalendarioJunio = $maquina->calcularNumeroHorasActivasDelMes(6, $anho);
			$tiempoCalendarioJulio = $maquina->calcularNumeroHorasActivasDelMes(7, $anho);
			$tiempoCalendarioAgosto = $maquina->calcularNumeroHorasActivasDelMes(8, $anho);
			$tiempoCalendarioSeptiembre = $maquina->calcularNumeroHorasActivasDelMes(9, $anho);
			$tiempoCalendarioOctubre = $maquina->calcularNumeroHorasActivasDelMes(10, $anho);
			$tiempoCalendarioNoviembre = $maquina->calcularNumeroHorasActivasDelMes(11, $anho);
			$tiempoCalendarioDiciembre = $maquina->calcularNumeroHorasActivasDelMes(12, $anho);
		}
		$TFEnero = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioEnero, $TPPEnero, $TNPEnero);
		$TFFebrero = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioFebrero, $TPPFebrero, $TNPFebrero);
		$TFMarzo = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioMarzo, $TPPMarzo, $TNPMarzo);
		$TFAbril = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioAbril, $TPPAbril, $TNPAbril);
		$TFMayo = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioMayo, $TPPMayo, $TNPMayo);
		$TFJunio = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioJunio, $TPPJunio, $TNPJunio);
		$TFJulio = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioJulio, $TPPJulio, $TNPJulio);
		$TFAgosto = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioAgosto, $TPPAgosto, $TNPAgosto);
		$TFSeptiembre = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioSeptiembre, $TPPSeptiembre, $TNPSeptiembre);
		$TFOctubre = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioOctubre, $TPPOctubre, $TNPOctubre);
		$TFNoviembre = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioNoviembre, $TPPNoviembre, $TNPNoviembre);
		$TFDiciembre = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendarioDiciembre, $TPPDiciembre, $TNPDiciembre);

		$TOEnero = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFEnero, $TPNPEnero);
		$TOFebrero = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFFebrero, $TPNPFebrero);
		$TOMarzo = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFMarzo, $TPNPMarzo);
		$TOAbril = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFAbril, $TPNPAbril);
		$TOMayo = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFMayo, $TPNPMayo);
		$TOJunio = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFJunio, $TPNPJunio);
		$TOJulio = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFJulio, $TPNPJulio);
		$TOAgosto = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFAgosto, $TPNPAgosto);
		$TOSeptiembre = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFSeptiembre, $TPNPSeptiembre);
		$TOOctubre = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFOctubre, $TPNPOctubre);
		$TONoviembre = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFNoviembre, $TPNPNoviembre);
		$TODiciembre = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFDiciembre, $TPNPDiciembre);

		$this->renderText('<graph color="#ffdc44" title="TNP" bullet="round">
          <value xid="0">'.round($TNPEnero/24, 2).'</value>
          <value xid="1">'.round($TNPFebrero/24, 2).'</value>
          <value xid="2">'.round($TNPMarzo/24, 2).'</value>
          <value xid="3">'.round($TNPAbril/24, 2).'</value>
          <value xid="4">'.round($TNPMayo/24, 2).'</value>
          <value xid="5">'.round($TNPJunio/24, 2).'</value>
          <value xid="6">'.round($TNPJulio/24, 2).'</value>
          <value xid="7">'.round($TNPAgosto/24, 2).'</value>
          <value xid="8">'.round($TNPSeptiembre/24, 2).'</value>
          <value xid="9">'.round($TNPOctubre/24, 2).'</value>
          <value xid="10">'.round($TNPNoviembre/24, 2).'</value>
          <value xid="11">'.round($TNPDiciembre/24, 2).'</value>
        </graph>'); 
		$this->renderText('<graph color="#47d552" title="TPP" bullet="round">
              <value xid="0">'.round($TPPEnero/24, 2).'</value>
              <value xid="1">'.round($TPPFebrero/24, 2).'</value>
              <value xid="2">'.round($TPPMarzo/24, 2).'</value>
              <value xid="3">'.round($TPPAbril/24, 2).'</value>
              <value xid="4">'.round($TPPMayo/24, 2).'</value>
              <value xid="5">'.round($TPPJunio/24, 2).'</value>
              <value xid="6">'.round($TPPJulio/24, 2).'</value>
              <value xid="7">'.round($TPPAgosto/24, 2).'</value>
              <value xid="8">'.round($TPPSeptiembre/24, 2).'</value>
              <value xid="9">'.round($TPPOctubre/24, 2).'</value>
              <value xid="10">'.round($TPPNoviembre/24, 2).'</value>
              <value xid="11">'.round($TPPDiciembre/24, 2).'</value>
            </graph>');
		$this->renderText('<graph color="#ff5454" title="TPNP" bullet="round">
              <value xid="0">'.round($TPNPEnero/24, 2).'</value>
              <value xid="1">'.round($TPNPFebrero/24, 2).'</value>
              <value xid="2">'.round($TPNPMarzo/24, 2).'</value>
              <value xid="3">'.round($TPNPAbril/24, 2).'</value>
              <value xid="4">'.round($TPNPMayo/24, 2).'</value>
              <value xid="5">'.round($TPNPJunio/24, 2).'</value>
              <value xid="6">'.round($TPNPJulio/24, 2).'</value>
              <value xid="7">'.round($TPNPAgosto/24, 2).'</value>
              <value xid="8">'.round($TPNPSeptiembre/24, 2).'</value>
              <value xid="9">'.round($TPNPOctubre/24, 2).'</value>
              <value xid="10">'.round($TPNPNoviembre/24, 2).'</value>
              <value xid="11">'.round($TPNPDiciembre/24, 2).'</value>
            </graph>');
		$this->renderText('<graph color="#f0a05f" title="TF" bullet="round">
              <value xid="0">'.round($TFEnero/24, 2).'</value>
              <value xid="1">'.round($TFFebrero/24, 2).'</value>
              <value xid="2">'.round($TFMarzo/24, 2).'</value>
              <value xid="3">'.round($TFAbril/24, 2).'</value>
              <value xid="4">'.round($TFMayo/24, 2).'</value>
              <value xid="5">'.round($TFJunio/24, 2).'</value>
              <value xid="6">'.round($TFJulio/24, 2).'</value>
              <value xid="7">'.round($TFAgosto/24, 2).'</value>
              <value xid="8">'.round($TFSeptiembre/24, 2).'</value>
              <value xid="9">'.round($TFOctubre/24, 2).'</value>
              <value xid="10">'.round($TFNoviembre/24, 2).'</value>
              <value xid="11">'.round($TFDiciembre/24, 2).'</value>
            </graph>');
		$this->renderText('<graph color="#72a8cd" title="TO" bullet="round">
              <value xid="0">'.round($TOEnero/24, 2).'</value>
              <value xid="1">'.round($TOFebrero/24, 2).'</value>
              <value xid="2">'.round($TOMarzo/24, 2).'</value>
              <value xid="3">'.round($TOAbril/24, 2).'</value>
              <value xid="4">'.round($TOMayo/24, 2).'</value>
              <value xid="5">'.round($TOJunio/24, 2).'</value>
              <value xid="6">'.round($TOJulio/24, 2).'</value>
              <value xid="7">'.round($TOAgosto/24, 2).'</value>
              <value xid="8">'.round($TOSeptiembre/24, 2).'</value>
              <value xid="9">'.round($TOOctubre/24, 2).'</value>
              <value xid="10">'.round($TONoviembre/24, 2).'</value>
              <value xid="11">'.round($TODiciembre/24, 2).'</value>
            </graph>
            ');
		return $this->renderText('
      </graphs>
    </chart>
    ');
		return $this->renderText('');
	}
	public function executeGenerarConfiguracionTortaTiempos() {
		$this->renderText('<settings>');
		$this->renderText('<data_type>csv</data_type>');
		$this->renderText('<pie>');
		$this->renderText('<x>245</x>
    <y>190</y>                     
    <inner_radius>40</inner_radius>
    <height>20</height>            
    <angle>30</angle>
    <colors>#ffdc44,#47d552,#ff5454,#72a8cd</colors>
    <hover_brightness>-10</hover_brightness>                
    <gradient>radial</gradient>                             
    <gradient_ratio>0,0,0,-50,0,0,0,-50</gradient_ratio>');
		$this->renderText('</pie>');
		$this->renderText('<animation>
    <start_time>2</start_time>                 
    <start_effect>regular</start_effect>       
    <start_alpha>0</start_alpha>               
    <sequenced>true</sequenced>                
    <pull_out_on_click>true</pull_out_on_click>
    <pull_out_time>1.5</pull_out_time>         
    </animation>');
		$this->renderText('<data_labels>
    <show>                                     
    <![CDATA[{percents}%]]>        
    </show>                                    
    </data_labels>');
		$this->renderText('<balloon>
    <alpha>80</alpha>                   
    <show>                              
    <![CDATA[{title}: {value} días ({percents}%) <br>{description}]]>
    </show>                             
    <max_width>300</max_width>          
    <corner_radius>5</corner_radius>    
    <border_width>3</border_width>      
    <border_alpha>50</border_alpha>     
    <border_color>#000000</border_color>
    </balloon>
    <labels>
    <label>
    <x>-30</x>
    <y>7</y>
    <text_color>000000</text_color>
    <text_size>13</text_size>
    <align>center</align>
    <text>
    <![CDATA[<b>Porcentaje de tiempos para cálculo OEE</b>]]>
    </text>        
    </label>
    </labels>
    ');
		$this->renderText('<legend>
    <enabled>true</enabled>        
    <x>50</x>                     
    <y>300</y>                     
    <max_columns>2</max_columns>   
    <values>                       
    <enabled></enabled>          
    <width></width>              
    <text><![CDATA[]]></text>    
    </values>                     
    </legend>');
		require_once(dirname(__FILE__).'/../../../../../config/variablesGenerales.php');
		$this->renderText('<export_as_image>
    <file>'.$urlWeb.'flash/ampie/export.php</file>     
    <color>#CC0000</color>                      
    <alpha>50</alpha>                           
    </export_as_image>');
		return $this->renderText('</settings>');
	}
	public function executeGenerarDatosTortaTiempos(sfWebRequest $request) {
		$anho = $request->getParameter('anho');
		$codigoMaquina = $request->getParameter('codigo_maquina');

		$params = array();
		if($request->getParameter('codigo_operario')!='-1') {
			$params['codigo_operario'] = $request->getParameter('codigo_operario');
		}
		if($request->getParameter('codigo_metodo')!='-1') {
			$params['codigo_metodo'] = $request->getParameter('codigo_metodo');
		}

		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		$TNPAnual = 0;
		$TPPAnual = 0;
		$TPNPAnual = 0;
		$TFAnual = 0;
		$TOAnual = 0;

		$cantidadHoras = RegistroUsoMaquinaPeer::calcularNumeroDiasDelAño($anho) * 24;

		$tiempoCalendario = 0;

		if($codigoMaquina=='-1') {
			$maquinas = MaquinaPeer::doSelect(new Criteria());

			foreach($maquinas as $maquina) {
				//				                    $maquina = new Maquina();
				$codigoTemporalMaquina = $maquina->getMaqCodigo();

				$TNPAnual += round(RegistroUsoMaquinaPeer::calcularTNPAnhoEnHoras($codigoTemporalMaquina, $anho, $params), 2);
				$TPPAnual += round(RegistroUsoMaquinaPeer::calcularTPPAnhoEnHoras($codigoTemporalMaquina, $anho, $params), 2);
				$TPNPAnual += round(RegistroUsoMaquinaPeer::calcularTPNPAnhoEnHoras($codigoTemporalMaquina, $anho, $params, $inyeccionesEstandarPromedio), 2);
				$tiempoCalendario += $maquina->calcularNumeroHorasActivasDelAño($anho);
			}
		}
		else {
			$maquina = MaquinaPeer::retrieveByPK($codigoMaquina);

			$TNPAnual = round(RegistroUsoMaquinaPeer::calcularTNPAnhoEnHoras($codigoMaquina, $anho, $params), 2);
			$TPPAnual = round(RegistroUsoMaquinaPeer::calcularTPPAnhoEnHoras($codigoMaquina, $anho, $params), 2);
			$TPNPAnual = round(RegistroUsoMaquinaPeer::calcularTPNPAnhoEnHoras($codigoMaquina, $anho, $params, $inyeccionesEstandarPromedio), 2);
			$tiempoCalendario = $maquina->calcularNumeroHorasActivasDelAño($anho);
		}
		$TFAnual = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendario, $TPPAnual, $TNPAnual);
		$TOAnual = RegistroUsoMaquinaPeer::calcularTODiaMesAño($TFAnual, $TPNPAnual);

		$this->renderText("Tiempo no programado;".round($TNPAnual/24, 2)."\n");
		$this->renderText("Tiempo de paradas programadas;".round($TPPAnual/24, 2)."\n");
		$this->renderText("Tiempo de paradas no programadas;".round($TPNPAnual/24, 2)."\n");
//		$this->renderText("Tiempo de funcionamiento;".round($TFAnual/24, 2)."\n");
		return $this->renderText("Tiempo operativo real;".round($TOAnual/24, 2)."\n");
	}

	/** * Executes index action * *
	@param sfRequest $request A request object */ public function
	executeIndex(sfWebRequest $request) {

	}
}
