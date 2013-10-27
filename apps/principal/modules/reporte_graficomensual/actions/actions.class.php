<?php

/**
 * reporte_graficomensual actions.
 *
 * @package    tpmlabs
 * @subpackage reporte_graficomensual
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_graficomensualActions extends sfActions
{
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request)
	{
	}

	public function obtenerConexionSinFecha() {
		$maquina_codigo=$this->getRequestParameter('maquina_codigo');
		$metodo_codigo=$this->getRequestParameter('metodo_codigo');
		$analista_codigo=$this->getRequestParameter('analista_codigo');

		$conexion = new Criteria();
		if($maquina_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO,$maquina_codigo,CRITERIA::EQUAL);}
		if($metodo_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_MET_CODIGO,$metodo_codigo,CRITERIA::EQUAL);}
		if($analista_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO,$analista_codigo,CRITERIA::EQUAL);}
		$conexion->add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
		return $conexion;
	}


	public function obtenerConexionDia($dia) {
		$maquina_codigo=$this->getRequestParameter('maquina_codigo');
		$metodo_codigo=$this->getRequestParameter('metodo_codigo');
		$analista_codigo=$this->getRequestParameter('analista_codigo');

		$conexion = new Criteria();
		$conexion->add(RegistroUsoMaquinaPeer::RUM_FECHA,$dia,CRITERIA::EQUAL);
		if($maquina_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO,$maquina_codigo,CRITERIA::EQUAL);}
		if($metodo_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_MET_CODIGO,$metodo_codigo,CRITERIA::EQUAL);}
		if($analista_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO,$analista_codigo,CRITERIA::EQUAL);}
		$conexion->add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
		return $conexion;
	}

	public function obtenerConexionMes($anio,$mes) {
		$maquina_codigo=$this->getRequestParameter('maquina_codigo');
		$metodo_codigo=$this->getRequestParameter('metodo_codigo');
		$analista_codigo=$this->getRequestParameter('analista_codigo');

		$conexion = new Criteria();
		$conexion->add(RegistroUsoMaquinaPeer::RUM_FECHA,$anio.'-'.$mes.'-01',CRITERIA::GREATER_EQUAL);
		$conexion->addAnd(RegistroUsoMaquinaPeer::RUM_FECHA,$anio.'-'.$mes.'-31',CRITERIA::LESS_EQUAL);

		if($maquina_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO,$maquina_codigo,CRITERIA::EQUAL);}
		if($metodo_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_MET_CODIGO,$metodo_codigo,CRITERIA::EQUAL);}
		if($analista_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO,$analista_codigo,CRITERIA::EQUAL);}
		$conexion->add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
		return $conexion;
	}

	public function agregarGuiaGrafica($titulo,$posicion) {
		if($posicion==0){
			$posicion=8;
		}
		$guia='<guide>';
		$guia.='<start_value>'.$posicion.'</start_value>';
		$guia.='<title>'.$titulo.'</title>';
		$guia.='<color>#00CC00</color>';
		$guia.='<inside>true</inside>';
		$guia.='<width>0</width>';
		$guia.='</guide>';

		return $guia;
	}

	public function obtenerCantidadDiasMes($mes,$anio) {
		$cant_dias=0;

		for ($dia=31;$dia>0;$dia--){
			if(checkdate($mes, $dia, $anio)){
				$cant_dias=$dia;
				$dia=0;
			}
		}
		return $cant_dias+1;
	}
	/**************************************Reporte de inyecciones *******************/
	public function executeGenerarDatosGraficoInyecciones(sfWebRequest $request)
	{
		$anio=$this->getRequestParameter('anio');
		$mes=$this->getRequestParameter('mes');
		$cant_dias=$this->obtenerCantidadDiasMes($mes,$anio);

		$total_inyecciones_realiza_mes=0;
		$total_reinyecciones_mes=0;
		$datos=$this->calcularInyecciones($anio,$mes,$cant_dias);
		$max_numero_inyecc=0;

		$xml='<?xml version="1.0"?>';
		$xml.='<chart>';

		$xml.='<series>';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++)
		{
			$xml.='<value xid="'.$diasmes.'">'.$diasmes.'</value>';
		}
		$xml.='</series>';
		$xml.='<graphs>';
		$xml.='<graph color="#72a8cd" title="Número inyecciones realizadas" bullet="round">';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++){

			$total_inyecciones_realiza=$datos[$diasmes]['inyecciones'];
			$xml.='<value xid="'.$diasmes.'">'.round($total_inyecciones_realiza, 2).'</value>';

			$total_inyecciones_realiza_mes+=$total_inyecciones_realiza ;

			if($total_inyecciones_realiza>$max_numero_inyecc){
				$max_numero_inyecc=$total_inyecciones_realiza;
			}
		}
		$xml.='</graph>';

		$xml.='<graph color="#ff5454" title="Número reinyecciones" bullet="round">';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++){
			$total_reinyecciones=$datos[$diasmes]['reinyecciones'];
			$xml.='<value xid="'.$diasmes.'">'.round($total_reinyecciones, 2).'</value>';
			$total_reinyecciones_mes+=$total_reinyecciones ;

			if($total_reinyecciones>$max_numero_inyecc){
				$max_numero_inyecc=$total_reinyecciones;
			}
		}
		$xml.='</graph>';

		$xml.='</graphs>';
		$xml.='<guides>';

		$porcen_inyecciones_realizadas=0;
		$porcen_reinyecciones_realizadas=0;
		$total_inyecciones=$total_inyecciones_realiza_mes+$total_reinyecciones_mes;

		$unidad_separancion=($max_numero_inyecc/8);
		$xml.=$this->agregarGuiaGrafica('Total inyecciones : '.$total_inyecciones,$max_numero_inyecc+ ($unidad_separancion*3));
		if($total_inyecciones!=0){
			$porcen_inyecciones_realizadas=round((($total_inyecciones_realiza_mes/$total_inyecciones)*100),2);
			$porcen_reinyecciones_realizadas=round((($total_reinyecciones_mes/$total_inyecciones)*100),2);
			$xml.=$this->agregarGuiaGrafica('Inyecciones         : '.round($total_inyecciones_realiza_mes,2).' ('.$porcen_inyecciones_realizadas.' %)',$max_numero_inyecc+($unidad_separancion*2));
			$xml.=$this->agregarGuiaGrafica('Reinyecciones     : '.round($total_reinyecciones_mes,2).' ('.$porcen_reinyecciones_realizadas.' %)',$max_numero_inyecc+($unidad_separancion*1));
		}
		$xml.='</guides>';
		$xml.='</chart>';

		$this->getRequest()->setRequestFormat('xml');
		$response = $this->getResponse();
		$response->setContentType('text/xml');
		$response->setHttpHeader('Content-length', strlen($xml), true);

		return $this->renderText($xml);
	}

	public function calcularInyecciones($anio,$mes,$cant_dias)
	{
		//echo($cant_dias);
		$datos;
		try{
			$numeroInyeccionesMes = 0;
			$numeroReinyeccionesMes = 0;
			for($dia=1;$dia<$cant_dias;$dia++){
				$suma_numero_inyecciones_dia= 0;
				$suma_numero_reinyecciones_dia= 0;

				$conexion=$this->obtenerConexionDia($anio.'-'.$mes.'-'.$dia);
				$registros_uso_maquinas = RegistroUsoMaquinaPeer::doSelect($conexion);

				foreach($registros_uso_maquinas as $temporal)
				{
					$suma_numero_inyecciones_dia+= $temporal->contarNumeroInyeccionesObligatorias();
					$suma_numero_reinyecciones_dia+= $temporal->contarNumeroTotalReinyecciones();
				}
				$datos[$dia]['inyecciones'] = $suma_numero_inyecciones_dia;
				$numeroInyeccionesMes += $suma_numero_inyecciones_dia;
				$datos[$dia]['reinyecciones'] = $suma_numero_reinyecciones_dia;
				$numeroReinyeccionesMes += $suma_numero_reinyecciones_dia;
			}
			$datos['inyeccionesMes'] = $numeroInyeccionesMes;
			$datos['reinyeccionesMes'] = $numeroReinyeccionesMes;
		}catch (Exception $excepcion)
		{
			echo "(exception: 'Excepci&oacute;n en reporte-calcularInyecciones ',error:'".$excepcion->getMessage()."')";
		}
		return $datos;
	}

	/**************************************Reporte de muestras *******************/
	public function executeGenerarDatosGraficoMuestras(sfWebRequest $request)
	{
		//$anio='2011';
		//$mes='02';
		$anio=$this->getRequestParameter('anio');
		$mes=$this->getRequestParameter('mes');
		$cant_dias=$this->obtenerCantidadDiasMes($mes,$anio);

		$total_muestras_analizadas_mes=0;
		$total_muestras_reanalizadas_mes=0;
		$max_numero_muestra=0;
		$datos=$this->calcularMuestras($anio,$mes,$cant_dias);

		$xml='<?xml version="1.0"?>';
		$xml.='<chart>';

		$xml.='<series>';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++)
		{
			$xml.='<value xid="'.$diasmes.'">'.$diasmes.'</value>';
		}
		$xml.='</series>';
		$xml.='<graphs>';
		$xml.='<graph color="#ffdc44" title="Número muestras analizadas" bullet="round">';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++){
			$numero_muestras_analizadas_dia=$datos[$diasmes]['analizadas'];
			$xml.='<value xid="'.$diasmes.'">'.$numero_muestras_analizadas_dia.'</value>';
			$total_muestras_analizadas_mes+=$numero_muestras_analizadas_dia;

			if($numero_muestras_analizadas_dia>$max_numero_muestra){
				$max_numero_muestra=$numero_muestras_analizadas_dia;
			}
		}
		$xml.='</graph>';

		$xml.='<graph color="#47d552" title="Número muestras reanalizadas" bullet="round" >';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++){
			$numero_muestras_reanalizadas_dia=$datos[$diasmes]['reanalizadas'];
			$xml.='<value xid="'.$diasmes.'">'.$numero_muestras_reanalizadas_dia.'</value>';
			$total_muestras_reanalizadas_mes+=$numero_muestras_reanalizadas_dia;
			if($numero_muestras_reanalizadas_dia>$max_numero_muestra){
				$max_numero_muestra=$numero_muestras_reanalizadas_dia;
			}
		}
		$xml.='</graph>';

		$xml.='</graphs>';
		$xml.='<guides>';

		$porcen_muestras_analizadas=0;
		$porcen_muestras_reanalizadas=0;
		$total_muestras_mes=$total_muestras_analizadas_mes+$total_muestras_reanalizadas_mes;
		if($total_muestras_mes!=0){
			$porcen_muestras_analizadas=round((($total_muestras_analizadas_mes/$total_muestras_mes)*100),2);
			$porcen_muestras_reanalizadas=round((($total_muestras_reanalizadas_mes/$total_muestras_mes)*100),2);
		}
		$unidad_separancion=($max_numero_muestra/8);

		$xml.=$this->agregarGuiaGrafica('Total muestras               : '.round($total_muestras_mes,2),$max_numero_muestra+(3*$unidad_separancion));
		if($total_muestras_mes!=0){
			$xml.=$this->agregarGuiaGrafica('Muestras analizadas    : '.round($total_muestras_analizadas_mes,2).' ('.$porcen_muestras_analizadas .' %)',$max_numero_muestra+(2*$unidad_separancion));
			$xml.=$this->agregarGuiaGrafica('Muestras reanalizadas : '.round($total_muestras_reanalizadas_mes,2).' ('.$porcen_muestras_reanalizadas.' %)',$max_numero_muestra+(1*$unidad_separancion));
		}
		$xml.='</guides>';
		$xml.='</chart>';

		$this->getRequest()->setRequestFormat('xml');
		$response = $this->getResponse();
		$response->setContentType('text/xml');
		$response->setHttpHeader('Content-length', strlen($xml), true);
		return $this->renderText($xml);
	}

	public function calcularMuestras($anio,$mes,$cant_dias)
	{
		$datos;
		try{

			for($dia=0;$dia<$cant_dias;$dia++){
				$suma_numero_muestras_analizadas_dia= 0;
				$suma_numero_muestras_reanalizadas_dia= 0;

				$conexion=$this->obtenerConexionDia($anio.'-'.$mes.'-'.$dia);
				$registros_uso_maquinas = RegistroUsoMaquinaPeer::doSelect($conexion);

				foreach($registros_uso_maquinas as $temporal)
				{
					$suma_numero_muestras_analizadas_dia+= $temporal->contarNumeroMuestrasProgramadas();
					$suma_numero_muestras_reanalizadas_dia+= $temporal->contarNumeroMuestrasReAnalizadas();
				}
				$datos[$dia]['analizadas']=round($suma_numero_muestras_analizadas_dia,2);
				$datos[$dia]['reanalizadas']=round($suma_numero_muestras_reanalizadas_dia,2);
			}
		}catch (Exception $excepcion)
		{
			return "(exeption: 'Excepci&oacute;n en reporte-calcularMuestras ',error:'".$excepcion->getMessage()."')";
		}
		return $datos;
	}

	/**************************************Reporte de perdidas diarias del mes *******************/
	public function executeGenerarDatosGraficoPerdidas(sfWebRequest $request)
	{
		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		//$anio='2011';
		//$mes='02';
		$anio=$this->getRequestParameter('anio');
		$mes=$this->getRequestParameter('mes');
		$cant_dias=$this->obtenerCantidadDiasMes($mes,$anio);

		$datos=$this->calcularPerdidasDiariasMes($anio,$mes,$cant_dias, $inyeccionesEstandarPromedio);

		$xml='<?xml version="1.0"?>';
		$xml.='<chart>';

		$xml.='<series>';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++)
		{
			$xml.='<value xid="'.$diasmes.'">'.$diasmes.'</value>';
		}
		$xml.='</series>';

		$xml.='<graphs>';
		$xml.='<graph color="#72a8cd" title="Fallas" bullet="round">';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++){
			$numero_fallas_dia=$datos[$diasmes]['fallas'];
			$xml.='<value xid="'.$diasmes.'">'.$numero_fallas_dia.'</value>';
		}
		$xml.='</graph>';

		$xml.='<graph color="#ff5454" title="Paros menores y reajustes" bullet="round" >';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++){
			$numero_paros_dia=$datos[$diasmes]['paros'];
			$xml.='<value xid="'.$diasmes.'">'.$numero_paros_dia.'</value>';
		}
		$xml.='</graph>';

		$xml.='<graph color="#47d552" title="Defectos y retrabajos" bullet="round" >';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++){
			$numero_retrabajos_dia=$datos[$diasmes]['retrabajos'];
			$xml.='<value xid="'.$diasmes.'">'.$numero_retrabajos_dia.'</value>';
		}
		$xml.='</graph>';


		$xml.='<graph color="#47d599" title="Pérdidas de velocidad" bullet="round" >';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++){
			$numero_retrabajos_dia=$datos[$diasmes]['perdida_rendimiento'];
			$xml.='<value xid="'.$diasmes.'">'.$numero_retrabajos_dia.'</value>';
		}
		$xml.='</graph>';

		$xml.='</graphs>';

		$xml.='</chart>';

		$this->getRequest()->setRequestFormat('xml');
		$response = $this->getResponse();
		$response->setContentType('text/xml');
		$response->setHttpHeader('Content-length', strlen($xml), true);
		return $this->renderText($xml);
	}

	public function calcularPerdidasDiariasMes($anio,$mes,$cant_dias,$inyeccionesEstandarPromedio)
	{
		$datos;
		try{
			for($dia=1;$dia<$cant_dias;$dia++){
				$suma_fallas_dia= 0;
				$suma_paros_dia= 0;
				$suma_retrabajos_dia= 0;
				$suma_perdidarendimiento_dia= 0;

				$conexion=$this->obtenerConexionDia($anio.'-'.$mes.'-'.$dia);
				$registros_uso_maquinas = RegistroUsoMaquinaPeer::doSelect($conexion);

				foreach($registros_uso_maquinas as $temporal)
				{
					$suma_fallas_dia+= $temporal->getRumFallas();
					$suma_paros_dia+= $temporal->calcularParosMenoresMinutos(8)+$temporal->calcularPerdidaCambioMetodoAjusteMinutos();
					$suma_retrabajos_dia+= $temporal->calcularRetrabajosMinutos(8);
					$suma_perdidarendimiento_dia+=$temporal->calcularPerdidasVelocidadMinutos($inyeccionesEstandarPromedio);
				}

				$datos[$dia]['fallas'] = round($suma_fallas_dia/60,2);
				$datos[$dia]['paros'] = round($suma_paros_dia/60,2);
				$datos[$dia]['retrabajos'] = round($suma_retrabajos_dia/60,2);
				$datos[$dia]['perdida_rendimiento'] = round($suma_perdidarendimiento_dia/60,2);

					
			}
		}catch (Exception $excepcion)
		{
			return "(exeption: 'Excepci&oacute;n en reporte-calcularFallas ',error:'".$excepcion->getMessage()."')";
		}
		return $datos;
	}

	/**************************************Reporte de perdidas del mes *******************/
	public function executeGenerarDatosGraficoPerdidasTorta(sfWebRequest $request)
	{
		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		//$anio='2011';
		//$mes='01';
		$anio=$this->getRequestParameter('anio');
		$mes=$this->getRequestParameter('mes');

		$datos=$this->calcularPerdidasMes($anio,$mes,$inyeccionesEstandarPromedio);

		$xml='<?xml version="1.0"?>';
		$xml.='<pie>';
		$xml.='<slice title="Fallas " color="#72a8cd" pull_out="true">'.$datos['fallas'].'</slice>';
		$xml.='<slice title="Paros Menores o Reajustes" color="#ff5454" pull_out="false">'.$datos['paros'].'</slice>';
		$xml.='<slice title="Defectos y Retrabajos" color="#47d552" pull_out="false">'.$datos['retrabajos'].'</slice>';
		$xml.='<slice title="Pérdidas de velocidad" color="#47d599" pull_out="false">'.$datos['perdida_rendimiento'].'</slice>';
		$xml.='</pie>';

		$this->getRequest()->setRequestFormat('xml');
		$response = $this->getResponse();
		$response->setContentType('text/xml');
		$response->setHttpHeader('Content-length', strlen($xml), true);
		return $this->renderText($xml);
	}

	public function calcularPerdidasMes($anio,$mes,$inyeccionesEstandarPromedio)
	{
		$datos;
		try{
			$suma_fallas_dia= 0;
			$suma_paros_dia= 0;
			$suma_retrabajos_dia= 0;
			$suma_perdidarendimiento_dia=0;

			$conexion=$this->obtenerConexionMes($anio,$mes);
			$registros_uso_maquinas = RegistroUsoMaquinaPeer::doSelect($conexion);

			foreach($registros_uso_maquinas as $temporal)
			{
				//				$temporal = new RegistroUsoMaquina();

				$suma_fallas_dia+= $temporal->getRumFallas();
				$suma_paros_dia+= $temporal->calcularParosMenoresMinutos(8) + $temporal->calcularPerdidaCambioMetodoAjusteMinutos();
				$suma_retrabajos_dia+= $temporal->calcularRetrabajosMinutos(8);
				$suma_perdidarendimiento_dia+=$temporal->calcularPerdidasVelocidadMinutos($inyeccionesEstandarPromedio);
			}
			$datos['fallas']=round($suma_fallas_dia/60,2);
			$datos['paros']=round(($suma_paros_dia/60),2);
			$datos['retrabajos']=round($suma_retrabajos_dia/60,2);
			$datos['perdida_rendimiento']=round($suma_perdidarendimiento_dia/60,2);


		}catch (Exception $excepcion)
		{
			return "(exeption: 'Excepci&oacute;n en reporte-calcularPerdidasMes ',error:'".$excepcion->getMessage()."')";
		}
		return $datos;
	}

	/**************************************Reporte de tiempos diarios del mes *******************/
	public function executeGenerarDatosGraficoTiempos(sfWebRequest $request)
	{
		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		//$anio='2011';
		//$mes='02';
		$anio=$this->getRequestParameter('anio');
		$mes=$this->getRequestParameter('mes');
		$cant_dias=$this->obtenerCantidadDiasMes($mes,$anio);

		$datos=$this->calcularTiemposDiariosMes($anio,$mes,$cant_dias,$inyeccionesEstandarPromedio);
		$indicadores_tiempo=array(    'TPP',     'TNP',   'TPNP',  'TF',   'TO');
		$indicadores_colores=array('47d552','ffdc44','ff5454','f0a05f','72a8cd');

		$xml='<?xml version="1.0"?>';
		$xml.='<chart>';

		$xml.='<series>';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++)
		{
			$xml.='<value xid="'.$diasmes.'">'.$diasmes.'</value>';
		}
		$xml.='</series>';
		$xml.='<graphs>';
		for ($indicador=0;$indicador<5;$indicador++){

			$xml.='<graph color="#'.$indicadores_colores[$indicador].'" title="'.$indicadores_tiempo[$indicador].'" bullet="round">';
			for($diasmes=1;$diasmes<$cant_dias;$diasmes++){
				$numero_fallas_dia=$datos[$diasmes][$indicadores_tiempo[$indicador]];
				$xml.='<value xid="'.$diasmes.'">'.round($numero_fallas_dia, 2).'</value>';
			}
			$xml.='</graph>';
		}
		$xml.='</graphs>';

		$xml.='</chart>';

		//		$this->getRequest()->setRequestFormat('xml');
		//		$response = $this->getResponse();
		//		$response->setContentType('text/xml');
		//		$response->setHttpHeader('Content-length', strlen($xml), true);
		return $this->renderText($xml);
	}

	public function calcularTiemposDiariosMes($anio,$mes,$cant_dias,$inyeccionesEstandarPromedio)
	{
		$datos;

		$maquina_codigo = $this->getRequestParameter('maquina_codigo');

		try{

			$params = array();

			for($dia=1;$dia<$cant_dias;$dia++){

				$año = $anio;

				$tpnp_dia = null;
				$tnp_dia = null;
				$tpp_dia = null;
				$tf_dia = null;
				$to_dia = null;
				$tp_dia = null;

				$horasActivas = 0;

				if($maquina_codigo!='-1' && $maquina_codigo!='') {
					$maquina = MaquinaPeer::retrieveByPK($maquina_codigo);

					$tpnp_dia = RegistroUsoMaquinaPeer::calcularTPNPDiaEnHoras($maquina_codigo, $dia, $mes, $año, $params, 8);
					$tnp_dia = RegistroUsoMaquinaPeer::calcularTNPDiaEnHoras($maquina_codigo, $dia, $mes, $año, $params, 8);
					$tpp_dia = RegistroUsoMaquinaPeer::calcularTPPDiaEnHoras($maquina_codigo, $dia, $mes, $año, $params, 8);
					$horasActivas = $maquina->calcularNumeroHorasActivasDelDia($dia, $mes, $año);
					$tf_dia = $horasActivas - $tpp_dia - $tnp_dia;
					$to_dia = RegistroUsoMaquinaPeer::calcularTODiaMesAño($tf_dia, $tpnp_dia);
					$tp_dia = RegistroUsoMaquinaPeer::calcularTPDiaEnHoras($maquina_codigo, $dia, $mes, $año, $params, 8);
				}
				else {
                                        $criteria = new Criteria();                                        
                                        $criteria->add(MaquinaPeer::MAQ_INDICADORES, true);
					$maquinas = MaquinaPeer::doSelect(new Criteria());
					$tpnp_dia = 0;
					$tnp_dia = 0;
					$tpp_dia = 0;
					$tf_dia = 0;
					$to_dia = 0;
					$tp_dia = 0;
					foreach($maquinas as $maquina) {
						//                    $maquina = new Maquina();

						$codigoTemporalMaquina = $maquina->getMaqCodigo();

						$tpnp_dia += RegistroUsoMaquinaPeer::calcularTPNPDiaEnHoras($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
						$tnp_dia += RegistroUsoMaquinaPeer::calcularTNPDiaEnHoras($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
						$tpp_dia += RegistroUsoMaquinaPeer::calcularTPPDiaEnHoras($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
						$horasActivas += $maquina->calcularNumeroHorasActivasDelDia($dia, $mes, $año);
						$tp_dia += RegistroUsoMaquinaPeer::calcularTPDiaEnHoras($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
					}
					$tf_dia = $horasActivas - $tpp_dia - $tnp_dia;
					$to_dia = RegistroUsoMaquinaPeer::calcularTODiaMesAño($tf_dia, $tpnp_dia);
				}

				$datos[$dia]['TP'] = $tp_dia;
				$datos[$dia]['TNP'] = $tnp_dia;
				$datos[$dia]['TPNP'] = $tpnp_dia;
				$datos[$dia]['TPP'] = $tpp_dia;
				$datos[$dia]['TO'] = $to_dia;
				$datos[$dia]['TF'] = $tf_dia;
				$datos[$dia]['HorasActivas'] = $horasActivas;
			}
		}catch (Exception $excepcion)
		{
			return "(exception: 'Excepci&oacute;n en reporte-calcularTiemposDiariosMes ',error:'".$excepcion->getMessage()."')";
		}
		return $datos;
	}

	/**************************************Reporte de tiempos del mes *******************/
	public function executeGenerarDatosGraficoTiemposTorta(sfWebRequest $request)
	{
		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		//$anio='2011';
		//$mes='02';
		$anio=$this->getRequestParameter('anio');
		$mes=$this->getRequestParameter('mes');

		$datos=$this->calcularTiemposDiariosMesTorta($anio,$mes,$inyeccionesEstandarPromedio);
		$indicadores_tiempo=array(    'TPP',     'TNP',    'TPNP',    'TO');
		$indicadores_colores=array('47d552','ffdc44','ff5454','72a8cd');

		$xml='<?xml version="1.0"?>';
		$xml.='<pie>';
		for ($ind=0;$ind<4;$ind++){
			$xml.='<slice title="'.$indicadores_tiempo[$ind].'" color="#'.$indicadores_colores[$ind].'"  pull_out="false">'.round($datos[$indicadores_tiempo[$ind]], 2).'</slice>';
		}
		$xml.='</pie>';

		$this->getRequest()->setRequestFormat('xml');
		$response = $this->getResponse();
		$response->setContentType('text/xml');
		$response->setHttpHeader('Content-length', strlen($xml), true);
		return $this->renderText($xml);
	}

	public function calcularTiemposDiariosMesTorta($anio,$mes,$inyeccionesEstandarPromedio)
	{
		$datos;

		$tp_mes = 0;
		$tnp_mes = 0;
		$tpnp_mes = 0;
		$tpp_mes = 0;
		$to_mes = 0;
		$tf_mes = 0;

		$maquina_codigo = $this->getRequestParameter('maquina_codigo');

		$params = array();

		$tiempoCalendario = 0;

		try{
			//			$cantidadDias = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes($mes, $anio);
			//			$cantidadHoras = $cantidadDias * 24;

			if($maquina_codigo!='-1' && $maquina_codigo!='') {
				$maquina = MaquinaPeer::retrieveByPK($maquina_codigo);

				$tpp_mes = RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($maquina_codigo, $mes, $anio, $params);
				$tnp_mes = RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($maquina_codigo, $mes, $anio, $params);
				$tpnp_mes = RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($maquina_codigo, $mes, $anio, $params);
				$tiempoCalendario = $maquina->calcularNumeroHorasActivasDelMes($mes, $anio);
				$tf_mes = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendario, $tpp_mes, $tnp_mes);
				$to_mes = RegistroUsoMaquinaPeer::calcularTODiaMesAño($tf_mes, $tpnp_mes);
				$tp_mes = RegistroUsoMaquinaPeer::calcularTPMesEnHoras($maquina_codigo, $mes, $anio, $params, 8);
			}
			else {
                                $criteria = new Criteria();
                                $criteria -> add(MaquinaPeer::MAQ_INDICADORES, true);
				$maquinas = MaquinaPeer::doSelect($criteria);
				$tpp_mes = 0;
				$tnp_mes = 0;
				$tpnp_mes = 0;
				$tf_mes = 0;
				$tp_mes = 0;
				$tiempoCalendario = 0;
				foreach($maquinas as $maquina) {
					//                    $maquina = new Maquina();

					$codigoTemporalMaquina = $maquina->getMaqCodigo();

					$tpp_mes += RegistroUsoMaquinaPeer::calcularTPPMesEnHoras($codigoTemporalMaquina, $mes, $anio, $params, 8);
					$tnp_mes += RegistroUsoMaquinaPeer::calcularTNPMesEnHoras($codigoTemporalMaquina, $mes, $anio, $params, 8);
					$tpnp_mes += RegistroUsoMaquinaPeer::calcularTPNPMesEnHoras($codigoTemporalMaquina, $mes, $anio, $params, 8);
					$tiempoCalendario += $maquina->calcularNumeroHorasActivasDelMes($mes, $anio);
					$tp_mes += RegistroUsoMaquinaPeer::calcularTPMesEnHoras($codigoTemporalMaquina, $mes, $anio, $params, 8);
				}
				$tf_mes = RegistroUsoMaquinaPeer::calcularTFDiaMesAño($tiempoCalendario, $tpp_mes, $tnp_mes);
				$to_mes = RegistroUsoMaquinaPeer::calcularTODiaMesAño($tf_mes, $tpnp_mes);
			}

			$datos['TP'] = $tp_mes;
			$datos['TNP'] = $tnp_mes;
			$datos['TPNP'] = $tpnp_mes;
			$datos['TPP'] = $tpp_mes;
			$datos['TO'] = $to_mes;
//			$datos['TF'] = $tf_mes;
			$datos['HorasActivas'] = $tiempoCalendario;

		}catch (Exception $excepcion)
		{
			echo "(exeption: 'Excepci&oacute;n en reporte-calcularTiemposDiariosMes ',error:'".$excepcion->getMessage()."')";
		}
		return $datos;
	}

	/**************************************Reporte de indicadores diarios del mes *******************/
	public function executeGenerarDatosGraficoIndicadores(sfWebRequest $request)
	{
		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		//$anio='2011';
		//$mes='02';
		$anio=$this->getRequestParameter('anio');
		$mes=$this->getRequestParameter('mes');

		$cant_dias=$this->obtenerCantidadDiasMes($mes,$anio);

		$datos=$this->calcularIndicadoresDiariosMes($anio,$mes,$cant_dias, $inyeccionesEstandarPromedio);
		$indicadores_tiempo=array(      'D',     'E',     'C',    'A',   'OEE',   'PTEE');
		$indicadores_colores=array('ff5454','47d552','f0a05f','ffdc44','72a8cd','b97a57');

		$xml='<?xml version="1.0"?>';
		$xml.='<chart>';

		$xml.='<series>';
		for($diasmes=1;$diasmes<$cant_dias;$diasmes++)
		{
			$xml.='<value xid="'.$diasmes.'">'.$diasmes.'</value>';
		}
		$xml.='</series>';
		$xml.='<graphs>';
		for ($indicador=0;$indicador<6;$indicador++){

			$xml.='<graph color="#'.$indicadores_colores[$indicador].'" title="'.$indicadores_tiempo[$indicador].'" bullet="round">';
			for($diasmes=1;$diasmes<$cant_dias;$diasmes++){
				$numero_fallas_dia=$datos[$diasmes][$indicadores_tiempo[$indicador]];
				$xml.='<value xid="'.$diasmes.'">'.$numero_fallas_dia.'</value>';
			}
			$xml.='</graph>';
		}
		$xml.='</graphs>';

		$xml.='</chart>';

		$this->getRequest()->setRequestFormat('xml');
		$response = $this->getResponse();
		$response->setContentType('text/xml');
		$response->setHttpHeader('Content-length', strlen($xml), true);
		return $this->renderText($xml);
	}

	public function calcularIndicadoresDiariosMes($anio,$mes,$cant_dias, $inyeccionesEstandarPromedio)
	{
		$datos;

		try{
			$datosTiempos = $this->calcularTiemposDiariosMes($anio, $mes, $cant_dias, $inyeccionesEstandarPromedio);
			$datosInyecciones = $this->calcularInyecciones($anio, $mes, $cant_dias);

			$maquina_codigo = $this->getRequestParameter('maquina_codigo');
			$cantidadMaquinas = null;
			if($maquina_codigo!='-1' && $maquina_codigo!='') {
				$cantidadMaquinas = 1;
			}
			else {
                                $criteria = new Criteria();
                                $criteria -> add(MaquinaPeer::MAQ_INDICADORES, true);
				$cantidadMaquinas = MaquinaPeer::doCount($criteria);
			}

			$cantidadHoras = $cantidadMaquinas * 24;

			for($dia=1;$dia<$cant_dias;$dia++){
				$tf_dia = $datosTiempos[$dia]['TF'];
				$to_dia = $datosTiempos[$dia]['TO'];
				$tp_dia = $datosTiempos[$dia]['TP'];

				$numeroInyecciones = $datosInyecciones[$dia]['inyecciones'];
				$numeroReinyecciones = $datosInyecciones[$dia]['reinyecciones'];

				$d_dia = RegistroUsoMaquinaPeer::calcularDisponibilidad($to_dia, $tf_dia);
				$e_dia = RegistroUsoMaquinaPeer::calcularEficiencia($tp_dia, $to_dia);
				$c_dia = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyecciones,$numeroReinyecciones);
				$horasActivas = $datosTiempos[$dia]['HorasActivas'];
				$a_dia = RegistroUsoMaquinaPeer::calcularAprovechamiento($tf_dia, $horasActivas);
				$oee_dia = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($d_dia, $e_dia, $c_dia);
				$ptee_dia = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($a_dia,$oee_dia);

				$datos[$dia]['D'] = round($d_dia,2);
				$datos[$dia]['E'] = round($e_dia,2);
				$datos[$dia]['C'] = round($c_dia,2);
				$datos[$dia]['A'] = round($a_dia,2);
				$datos[$dia]['OEE'] = round($oee_dia,2);
				$datos[$dia]['PTEE'] = round($ptee_dia,2);
			}
		}catch (Exception $excepcion)
		{
			return "(exeption: 'Excepci&oacute;n en reporte-calcularTiemposDiariosMes ',error:'".$excepcion->getMessage()."')";
		}
		return $datos;
	}
	/**************************************Reporte de indicadores barra del mes *******************/
	public function executeGenerarDatosGraficoIndicadoresBarras(sfWebRequest $request)
	{
		$user = $this->getUser();
		$codigo_usuario = $user->getAttribute('usu_codigo');
		$criteria = new Criteria();
		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);

		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		//$anio='2011';
		//$mes='02';
		$anio=$this->getRequestParameter('anio');
		$mes=$this->getRequestParameter('mes');

		$cant_dias=$this->obtenerCantidadDiasMes($mes,$anio);

		$datos=$this->calcularIndicadoresDiariosMesBarras($anio,$mes,$cant_dias,$inyeccionesEstandarPromedio);
		$datos_metas=$this->obtenerIndicadoresMetasMesBarras($anio,$mes);
		$indicadores_porcentaje=array(      'D',     'E',     'C',    'A',   'OEE',   'PTEE');
		$indicadores_descripcion=array('Disponibilidad','Eficiencia','Calidad','Aprovechamiento','Efectividad global','PTEE');
		$indicadores_colores=array('ff5454','47d552','f0a05f','ffdc44','72a8cd','b97a57');

		$xml='<?xml version="1.0"?>';
		$xml.='<chart>';

		$xml.='<series>';
		for($ind=0;$ind<6;$ind++)
		{
			//$xml.='<value xid="'.$ind.'" >'.$indicadores_porcentaje[$ind].'</value>';
			$xml.='<value xid="'.$ind.'" >'.$indicadores_descripcion[$ind].'</value>';
		}
		$xml.='</series>';
		$xml.='<graphs>';
		$xml.='<graph  title="Resultado Indicador " >';
		for ($ind=0;$ind<6;$ind++){
			$resultado=$datos[$indicadores_porcentaje[$ind]];
			$xml.='<value xid="'.$ind.'"  color="'.$indicadores_colores[$ind].'">'.$resultado.'</value>';
			//$xml.='<value xid="'.$ind.'" color="">'.$resultado.'</value>';
		}
		$xml.='</graph>';

		$xml.='<graph  title="Meta Indicador " color="7F8DA9" >';
		for ($ind=0;$ind<6;$ind++){
			$resultado=$datos_metas[$indicadores_porcentaje[$ind]];
			//$xml.='<value xid="'.$ind.'" color="'.$indicadores_colores[$ind].',ffffff">'.$resultado.'</value>';
			$xml.='<value xid="'.$ind.'" color="7F8DA9">'.$resultado.'</value>';
		}
		$xml.='</graph>';

		$xml.='</graphs>';

		$xml.='</chart>';

		$this->getRequest()->setRequestFormat('xml');
		$response = $this->getResponse();
		$response->setContentType('text/xml');
		$response->setHttpHeader('Content-length', strlen($xml), true);
		return $this->renderText($xml);
	}

	public function calcularIndicadoresDiariosMesBarras($anio,$mes,$cant_dias,$inyeccionesEstandarPromedio)
	{
		$datos;
		$d_mes = 0;
		$e_mes = 0;
		$c_mes = 0;
		$a_mes = 0;
		$oee_mes = 0;
		$ptee_mes = 0;

		try{
			$cant_dias = $this->obtenerCantidadDiasMes($mes,$anio);

			$maquina_codigo = $this->getRequestParameter('maquina_codigo');
			$cantidadMaquinas = null;
			if($maquina_codigo!='-1' && $maquina_codigo!='') {
				$cantidadMaquinas = 1;
			}
			else {
                                $criteria = new Criteria();
                                $criteria -> add(MaquinaPeer::MAQ_INDICADORES, true);
				$cantidadMaquinas = MaquinaPeer::doCount($criteria);
			}

			$cantidadDias = RegistroUsoMaquinaPeer::calcularNumeroDiasDelMes($mes, $anio);
			$cantidadHoras = $cantidadDias * $cantidadMaquinas * 24;

			$datosTiempos = $this->calcularTiemposDiariosMesTorta($anio, $mes, 8);

			$tp_mes = $datosTiempos['TP'];
			$tpp_mes = $datosTiempos['TPP'];
			$tnp_mes = $datosTiempos['TNP'];
			$tpnp_mes = $datosTiempos['TPNP'];
			$tf_mes = $datosTiempos['TF'];
			$to_mes = $datosTiempos['TO'];

			$datosInyecciones = $this->calcularInyecciones($anio, $mes, $cant_dias);

			$numeroInyeccionesMes = $datosInyecciones['inyeccionesMes'];
			$numeroReinyeccionesMes = $datosInyecciones['reinyeccionesMes'];

			$d_mes = RegistroUsoMaquinaPeer::calcularDisponibilidad($to_mes, $tf_mes);
			$e_mes = RegistroUsoMaquinaPeer::calcularEficiencia($tp_mes, $to_mes);
			$c_mes = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesMes, $numeroReinyeccionesMes);
			$cantidadHoras = $datosTiempos['HorasActivas'];
			$a_mes = RegistroUsoMaquinaPeer::calcularAprovechamiento($tf_mes, $cantidadHoras);
			$oee_mes = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($d_mes, $e_mes, $c_mes);
			$ptee_mes = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($a_mes, $oee_mes);

			$datos['D'] = round($d_mes, 2);
			$datos['E'] = round($e_mes, 2);
			$datos['C'] = round($c_mes, 2);
			$datos['A'] = round($a_mes, 2);
			$datos['OEE'] = round($oee_mes, 2);
			$datos['PTEE'] = round($ptee_mes, 2);
		}catch (Exception $excepcion)
		{
			echo "(exeption: 'Excepci&oacute;n en reporte-calcularTiemposDiariosMes ',error:'".$excepcion->getMessage()."')";
		}

		return $datos;
	}


	public function obtenerIndicadoresMetasMesBarras($anio,$mes)
	{
		$datos;
		try{
			$emp_codigo=$this->getUser()->getAttribute('empl_emp_codigo');

			$datos['A'] = $this->obtenerMetaPorIndicador($anio,'A',$emp_codigo);
			$datos['E'] = $this->obtenerMetaPorIndicador($anio,'E',$emp_codigo);
			$datos['C'] = $this->obtenerMetaPorIndicador($anio,'C',$emp_codigo);
			$datos['D'] = $this->obtenerMetaPorIndicador($anio,'D',$emp_codigo);
			$datos['OEE'] = $this->obtenerMetaPorIndicador($anio,'OEE',$emp_codigo);
			$datos['PTEE'] = $this->obtenerMetaPorIndicador($anio,'PTEE',$emp_codigo);

		}catch (Exception $excepcion)
		{
			echo "(exeption: 'Excepci&oacute;n en reporte-obtenerIndicadoresMetasMesBarras ',error:'".$excepcion->getMessage()."')";
		}

		return $datos;
	}

	public function obtenerMetaPorIndicador($anio,$ind_sigla,$emp_codigo)
	{
		$salida=0;
		try{
			//echo($anio);
			if($anio!='' && $emp_codigo!='' && $ind_sigla!='')
			{
				$conexion = new Criteria();
				$conexion->add(MetaAnualXIndicadorPeer::MEA_ANIO,$anio);
				$conexion->add(MetaAnualXIndicadorPeer::MEA_EMP_CODIGO,$emp_codigo);
				$conexion->addJoin(MetaAnualXIndicadorPeer::MEA_IND_CODIGO,IndicadorPeer::IND_CODIGO);
				$conexion->add(IndicadorPeer::IND_SIGLA,$ind_sigla,Criteria::EQUAL);

				$metaanualxindicador = MetaAnualXIndicadorPeer::doSelectOne($conexion);

				if($metaanualxindicador){
					$salida= $metaanualxindicador->getMeaValor();
				}

			}
		}
		catch (Exception $excepcion)
		{
			echo "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en obtenerMetaPorIndicador',error:'".$excepcion->getMessage()."'}})";
		}
		return $salida;
	}



	/**
	 *@author:maryit sanchez
	 *@date:6 de enero de 2011
	 *Esta funcion retorna  un listado de los analaistas
	 */
	public function executeListarAnalistas(sfWebRequest $request)
	{

		$salida='({"total":"0", "results":""})';
		$datos=EmpleadoPeer::listarAnalistas();
		$cant=count($datos);
		if (count($datos)>0){
			$jsonresult = json_encode($datos);
			$salida= '({"total":"'.$cant.'","results":'.$jsonresult.'})';
		}
		return $this->renderText($salida);
	}

	public function executeListarEquiposActivos(sfWebRequest $request)
	{
		$salida = '({"total":"0", "results":""})';
		$datos = MaquinaPeer::listarEquiposActivos();
		$cant = count($datos);
		if (count($datos)>0){
			$jsonresult = json_encode($datos);
			$salida= '({"total":"'.$cant.'","results":'.$jsonresult.'})';
		}
		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:6 de enero de 2011
	 *Esta funcion retorna  un listado de los maquinas
	 */
	public function executeListarMaquinas(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$datos=MaquinaPeer::listarMaquinasBuenas();
		$cant=count($datos);
		if (count($datos)>0){
			$jsonresult = json_encode($datos);
			$salida= '({"total":"'.$cant.'","results":'.$jsonresult.'})';
		}
		return $this->renderText($salida);
	}


	/**
	 *@author:maryit sanchez
	 *@date:6 de enero de 2011
	 *Esta funcion retorna  un listado de los metodos
	 */
	public function executeListarMetodos(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$datos=MetodoPeer::listarMetodosActivos();
		$cant=count($datos);
		if ($cant>0){
			$jsonresult = json_encode($datos);
			$salida= '({"total":"'.$cant.'","results":'.$jsonresult.'})';
		}
		return $this->renderText($salida);
	}

}
