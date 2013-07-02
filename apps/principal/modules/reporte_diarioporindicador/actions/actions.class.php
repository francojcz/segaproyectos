<?php

/**
 * reporte_diarioporindicador actions.
 *
 * @package    tpmlabs
 * @subpackage reporte_diarioporindicador
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_diarioporindicadorActions extends sfActions
{
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request)
	{
		//$this->forward('default', 'module');
	}

	public function obtenerConexion(){
		$conexion = new Criteria();
		$desde_fecha=$this->getRequestParameter('desde_fecha');
		$hasta_fecha=$this->getRequestParameter('hasta_fecha');
		$maquina_codigo=$this->getRequestParameter('maquina_codigo');
		$metodo_codigo=$this->getRequestParameter('metodo_codigo');
		$analista_codigo=$this->getRequestParameter('analista_codigo');

		if($desde_fecha!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_FECHA,$desde_fecha,CRITERIA::GREATER_EQUAL);}
		if($hasta_fecha!=''){
			if($desde_fecha!=''){$conexion->addAnd(RegistroUsoMaquinaPeer::RUM_FECHA,$hasta_fecha,CRITERIA::LESS_EQUAL);}
			else{$conexion->add(RegistroUsoMaquinaPeer::RUM_FECHA,$hasta_fecha,CRITERIA::LESS_EQUAL);}
		}
		if($maquina_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO,$maquina_codigo,CRITERIA::EQUAL);}
		if($metodo_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_MET_CODIGO,$metodo_codigo,CRITERIA::EQUAL);}
		if($analista_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO,$analista_codigo,CRITERIA::EQUAL);}
		$conexion->add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
		$conexion->addDescendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_FECHA);

		return $conexion;
	}

	public function obtenerConexionDia($fecha_dia){
		$conexion = new Criteria();
		$maquina_codigo=$this->getRequestParameter('maquina_codigo');
		$metodo_codigo=$this->getRequestParameter('metodo_codigo');
		$analista_codigo=$this->getRequestParameter('analista_codigo');

		$conexion->add(RegistroUsoMaquinaPeer::RUM_FECHA,$fecha_dia,CRITERIA::EQUAL);
		if($maquina_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO,$maquina_codigo,CRITERIA::EQUAL);}
		if($metodo_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_MET_CODIGO,$metodo_codigo,CRITERIA::EQUAL);}
		if($analista_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO,$analista_codigo,CRITERIA::EQUAL);}
		$conexion->add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
		$conexion->addDescendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_FECHA);

		return $conexion;
	}

	/**
	 *@author:maryit sanchez
	 *@date:29 de diciembre de 2010
	 *Esta funcion retorna  un listado de los metodos por indicador
	 */
	public function executeListarReportediarioporindicador(sfWebRequest $request)
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
		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos_dia;
		$datos_basicos;
		$datos;
		try{
			//los datos de la conexion deben estar ordenados por fecha
			$conexion=$this->obtenerConexion();

			$registros_uso_maquinas = RegistroUsoMaquinaPeer::doSelect($conexion);

			$cant_registros_dia = RegistroUsoMaquinaPeer::doCount($conexion);

			foreach($registros_uso_maquinas as $temporal)
			{
				//				$temporal = new RegistroUsoMaquina();

				$maq_tiempo_inyeccion=$temporal->obtenerTiempoInyeccionMaquina();
				$tp= $temporal->obtenerTPMetodo($maq_tiempo_inyeccion);
				$tf=$temporal->obtenerTFMetodo();
				$to=$temporal->obtenerTOMetodo($maq_tiempo_inyeccion);
				$d=RegistroUsoMaquinaPeer::obtenerDisponibilidad($tf,$to);
				$e=RegistroUsoMaquinaPeer::obtenerEficiencia($to,$tp);
				$c=$temporal->obtenerCalidadMetodo();
				$oee=RegistroUsoMaquinaPeer::obtenerEfectividadGlobal($d,$e,$c);
				$fecha_dia=$temporal->getRumFecha();

					
				$datos[$fila]['rdpi_maquina'] = $temporal->obtenerMaquina();
				$datos[$fila]['rdpi_analista'] = $temporal->obtenerAnalista();
				$datos[$fila]['rdpi_metodo'] =  $temporal->obtenerMetodo();
				$datos[$fila]['rdpi_fecha'] = $temporal->getRumFecha();
					
				$datos[$fila]['rdpi_tp_metodo'] =round($tp,2);
				$datos[$fila]['rdpi_tnp_metodo'] = round($temporal->obtenerTNPMetodo(),2);
				$datos[$fila]['rdpi_tpp_metodo'] = round($temporal->obtenerTPPMetodo(),2);
				$datos[$fila]['rdpi_tpnp_metodo'] = round($temporal->calcularTPNPMinutos(8),2);
				$datos[$fila]['rdpi_tf_metodo'] = round($tf,2);
				$datos[$fila]['rdpi_to_metodo'] = round($to,2);

				$datos[$fila]['rdpi_d_metodo'] = round($d,0);
				$datos[$fila]['rdpi_e_metodo'] = round($e,0);
				$datos[$fila]['rdpi_c_metodo'] = round($c,0);
				$datos[$fila]['rdpi_oee_metodo'] = round($oee,0);
					
					
				$datos[$fila]['rdpi_cantidad_dia']=$this->obtenerCantidadRegistros($fecha_dia);
					
				//poner condifional para qu no lo haga siempre
				$conexion_dia=$conexion;
				$conexion_dia->add(RegistroUsoMaquinaPeer::RUM_FECHA,$fecha_dia,CRITERIA::EQUAL);

				$tp_dia=RegistroUsoMaquinaPeer::obtenerTPDia($conexion_dia);
				$tpp_dia=RegistroUsoMaquinaPeer::obtenerTPPDia($conexion_dia);
				$tnp_dia=RegistroUsoMaquinaPeer::obtenerTNPDia($conexion_dia);
				$tpnp_dia=RegistroUsoMaquinaPeer::obtenerTPNPDia($conexion_dia, $inyeccionesEstandarPromedio);
				$tf_dia=24-$tpp_dia-$tnp_dia;
				$to_dia=RegistroUsoMaquinaPeer::obtenerTODia($tf_dia,$tpnp_dia);
				$d_dia=RegistroUsoMaquinaPeer::obtenerDisponibilidad($tf_dia,$to_dia);
				$e_dia=RegistroUsoMaquinaPeer::obtenerEficiencia($to_dia,$tp_dia);
				$c_dia=RegistroUsoMaquinaPeer::obtenerCalidad($conexion_dia);
				$ae_dia=RegistroUsoMaquinaPeer::obtenerAprovechamiento($tf_dia,24);
				$oee_dia=RegistroUsoMaquinaPeer::obtenerEfectividadGlobal($d_dia,$e_dia,$c_dia);

				$datos[$fila]['rdpi_tp_dia'] = round($tp_dia,1);
				$datos[$fila]['rdpi_tpp_dia'] = round($tpp_dia,1);
				$datos[$fila]['rdpi_tnp_dia'] = round($tnp_dia,1);
				$datos[$fila]['rdpi_tpnp_dia'] = round($tpnp_dia,1);
				$datos[$fila]['rdpi_tf_dia'] = round($tf_dia,1);
				$datos[$fila]['rdpi_to_dia'] = round($to_dia,1);
					
				$datos[$fila]['rdpi_d_dia'] = round($d_dia,0);
				$datos[$fila]['rdpi_e_dia'] =round($e_dia,0);
				$datos[$fila]['rdpi_c_dia'] = round($c_dia,0);
				$datos[$fila]['rdpi_ae_dia'] = round($ae_dia,0);
				$datos[$fila]['rdpi_oee_dia'] =round($oee_dia,0);
					
				$fila++;
			}

			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en estado al listar ',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}


	/**
	 *@author:maryit sanchez
	 *@date:3 de enero de 2011
	 *Esta funcion redondea un numero
	 */
	public function truncar($numero){
		$numero=(round($numero,1));
		return $numero;
	}

	/**
	 *@author:maryit sanchez
	 *@date:3 de enero de 2011
	 *Esta funcion obtiene la cantidad de registros uso maquina para un dia especifico
	 */
	protected function obtenerCantidadRegistros($fecha){

		try{
			$conexion = $this->obtenerConexionDia($fecha);
			$cant_registros_dia = RegistroUsoMaquinaPeer::doCount($conexion);

			return $cant_registros_dia;
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n ',error:'".$excepcion->getMessage()."'}})";
		}
		return 0;
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
		return $this->renderText($salida);	}


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
