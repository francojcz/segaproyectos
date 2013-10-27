<?php

/**
 * reporte_diario_perdidas actions.
 *
 * @package    tpmlabs
 * @subpackage reporte_diario_perdidas
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_diario_perdidasActions extends sfActions
{
	public function executeListarMaquinas() {
                $criteria = new Criteria();
                $criteria -> add(MaquinaPeer::MAQ_INDICADORES, true);
		$maquinas = MaquinaPeer::doSelect($criteria);

		$result = array();
		$data = array();

		foreach($maquinas as $maquina) {
			$fields = array();

			//      $maquina = new Maquina();

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

			$fields['nombre'] = $empleado->getNombreCompleto();

			$data[] = $fields;
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
	public function executeListarReportePorMetodo(sfWebRequest $request) {
		$user = $this->getUser();
		$codigo_usuario = $request->getParameter('codigo_usu_operario');
		$codigo_maquina = $request->getParameter('codigo_maquina');

		$criteria = new Criteria();
		if($codigo_maquina!='-1') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $codigo_maquina);
		}
		$criteria->add(RegistroUsoMaquinaPeer::RUM_FECHA, $request->getParameter('fecha'));
		$criteria->add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
		$registros = RegistroUsoMaquinaPeer::doSelect($criteria);


		$criteria = new Criteria();
		if($codigo_usuario!='-1') {
			$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
		}
		$operario = EmpleadoPeer::doSelectOne($criteria);
		$criteria = new Criteria();
		$criteria->add(EmpresaPeer::EMP_CODIGO, $operario->getEmplEmpCodigo());
		$empresa = EmpresaPeer::doSelectOne($criteria);
		$inyeccionesEstandarPromedio = $empresa->getEmpInyectEstandarPromedio();

		$result = array();
		$data = array();

		foreach($registros as $registro) {
			$fields = array();

			//      $registro = new RegistroUsoMaquina();

			$criteria = new Criteria();
			$criteria->add(EmpleadoPeer::EMPL_CODIGO, $registro->getRumUsuCodigo());
			$operario = EmpleadoPeer::doSelectOne($criteria);
			$fields['nombre_operario'] = $operario->getNombreCompleto();

			$maquina = MaquinaPeer::retrieveByPK($registro->getRumMaqCodigo());
			$fields['nombre_maquina'] = $maquina->getNombreCompleto();

			$metodo = MetodoPeer::retrieveByPK($registro->getRumMetCodigo());
			if($metodo) {
				$fields['nombre_metodo'] = $metodo->getMetNombre();
			}
			else {
				$fields['nombre_metodo'] = '';
			}

			$tiempoCorrida = round($registro->calcularTiempoCorridaHoras(), 2);
			$fields['numero_muestras_reanalizadas'] = $registro->contarNumeroMuestrasReAnalizadas();
			$fields['numero_reinyecciones'] = $registro->contarNumeroTotalReinyecciones($inyeccionesEstandarPromedio);
			$fields['paros_menores'] = number_format(round($registro->calcularParosMenoresMinutos($inyeccionesEstandarPromedio), 2), 2);
			$fields['retrabajos'] = number_format($registro->calcularRetrabajosMinutos($inyeccionesEstandarPromedio), 2);
			$fields['fallas'] = number_format(round($registro->getRumFallas(),2), 2);
			$fields['perdidas_velocidad'] = number_format(round($registro->calcularPerdidasVelocidadMinutos($inyeccionesEstandarPromedio),2), 2);

			$data[] = $fields;
		}

		$result['data'] = $data;
		return $this->renderText(json_encode($result));
	}
	public function executeIndex(sfWebRequest $request)
	{
		$this->nombreEmpresa = '';
		$this->urlLogo = '';

	}
}
