<?php

/**
 * reporte_evento actions.
 *
 * @package    tpmlabs
 * @subpackage reporte_evento
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_eventoActions extends sfActions
{
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request)
	{
	}

	public function obtenerConexion(){

		$desde_fecha=$this->getRequestParameter('desde_fecha');
		$hasta_fecha=$this->getRequestParameter('hasta_fecha');
		$maquina_codigo=$this->getRequestParameter('maquina_codigo');
		$metodo_codigo=$this->getRequestParameter('metodo_codigo');
		$analista_codigo=$this->getRequestParameter('analista_codigo');
		$categoriaevento_codigo=$this->getRequestParameter('categoria_codigo');

		$conexion = new Criteria();
		$conexion->addJoin(EventoEnRegistroPeer::EVRG_RUM_CODIGO,RegistroUsoMaquinaPeer::RUM_CODIGO);
		if($desde_fecha!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_FECHA,$desde_fecha,CRITERIA::GREATER_EQUAL);}
		if($hasta_fecha!=''){
			if($desde_fecha!=''){$conexion->addAnd(RegistroUsoMaquinaPeer::RUM_FECHA,$hasta_fecha,CRITERIA::LESS_EQUAL);}
			else{$conexion->add(RegistroUsoMaquinaPeer::RUM_FECHA,$hasta_fecha,CRITERIA::LESS_EQUAL);}
		}
		if($maquina_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO,$maquina_codigo,CRITERIA::EQUAL);}
		if($metodo_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_MET_CODIGO,$metodo_codigo,CRITERIA::EQUAL);}
		if($analista_codigo!=''){$conexion->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO,$analista_codigo,CRITERIA::EQUAL);}

		$conexion->add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
		if($categoriaevento_codigo!=''){
			$conexion->addJoin(EventoEnRegistroPeer::EVRG_EVE_CODIGO,EventoPorCategoriaPeer::EVCA_EVE_CODIGO);
			$conexion->add(EventoPorCategoriaPeer::EVCA_CAT_CODIGO,$categoriaevento_codigo,CRITERIA::EQUAL );
		}
		return $conexion;
	}

	/**
	 *@author:maryit sanchez
	 *@date:21 de enero de 2010
	 *Esta funcion retorna  un listado de los metodos por indicador
	 */
	public function executeListarReporteEventoEnRegistro(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			$conexion=$this->obtenerConexion();
			$evento_en_registro = EventoEnRegistroPeer::doSelect($conexion);

			foreach($evento_en_registro as $temporal)
			{
				$rum_codigo = $temporal->getEvrgRumCodigo();
				$registro_uso_maquinas  = RegistroUsoMaquinaPeer::retrieveByPk($rum_codigo);

				if($registro_uso_maquinas){
					$datos[$fila]['evrg_maquina'] = $registro_uso_maquinas->obtenerMaquina();
					$datos[$fila]['evrg_analista'] = $registro_uso_maquinas->obtenerAnalista();
					$datos[$fila]['evrg_metodo'] =  $registro_uso_maquinas->obtenerMetodo();
					$datos[$fila]['evrg_fecha'] = $registro_uso_maquinas->getRumFecha();
				}

				$eve_codigo = $temporal->getEvrgEveCodigo();
				$evento  = EventoPeer::retrieveByPk($eve_codigo);
				if($evento){
					$datos[$fila]['evrg_eve_nombre'] = $evento->getEveNombre();
				}

				$datos[$fila]['evrg_codigo'] = $temporal->getEvrgCodigo();
				$datos[$fila]['evrg_duracion'] = $temporal->getEvrgDuracion();
				$datos[$fila]['evrg_observaciones'] = $temporal->getEvrgObservaciones();
				$datos[$fila]['evrg_hora_ocurrio'] = $temporal->getEvrgHoraOcurrio();
					
				$datos[$fila]['evrg_hora_registro'] = $temporal->getEvrgHoraRegistro();

				$fila++;
			}

			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Excepci&oacute;n  en registro de eventos ocurridos ',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}

	public function executeGenerarDatosOcurrenciaEventosBarra(sfWebRequest $request)
	{
		$criteria = new Criteria();
		$criteria->addJoin(EventoPeer::EVE_CODIGO, EventoEnRegistroPeer::EVRG_EVE_CODIGO);
		$criteria->addJoin(EventoEnRegistroPeer::EVRG_RUM_CODIGO, RegistroUsoMaquinaPeer::RUM_CODIGO);
		$criteria->clearSelectColumns();
		$criteria->addSelectColumn(EventoPeer::EVE_NOMBRE);
		$criteria->addSelectColumn('COUNT(*)');
		$criteria->addGroupByColumn(EventoPeer::EVE_CODIGO);
		$criteria->addDescendingOrderByColumn('COUNT(*)');
		$criteria->add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);

		if($request->getParameter('desde_fecha')!='') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_FECHA, $request->getParameter('desde_fecha'), Criteria::GREATER_EQUAL);
		}

		if($request->getParameter('hasta_fecha')!='') {
			$criteria->addAnd(RegistroUsoMaquinaPeer::RUM_FECHA, $request->getParameter('hasta_fecha'), Criteria::LESS_EQUAL);
		}

		if($request->getParameter('categoria_codigo')!='') {
			$criteria->addJoin(EventoPeer::EVE_CODIGO, EventoPorCategoriaPeer::EVCA_EVE_CODIGO);
			$criteria->add(EventoPorCategoriaPeer::EVCA_CAT_CODIGO, $request->getParameter('categoria_codigo'));
		}

		if($request->getParameter('analista_codigo')!='') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $request->getParameter('analista_codigo'));
		}

		if($request->getParameter('maquina_codigo')!='') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $request->getParameter('maquina_codigo'));
		}

		if($request->getParameter('metodo_codigo')!='') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_MET_CODIGO, $request->getParameter('metodo_codigo'));
		}

		$statement = EventoPeer::doSelectStmt($criteria);
		$eventos = $statement->fetchAll(PDO::FETCH_NUM);

		$xml='<?xml version="1.0"?>';
		$xml.='<chart>';

		$colores = array();
		$colores[] = 'FF6600';
		$colores[] = 'FCD202';
		$colores[] = 'B0DE09';
		$colores[] = '0D8ECF';
		$colores[] = '2A0CD0';
		$colores[] = 'CD0D74';
		$colores[] = 'CC0000';
		$colores[] = '00CC00';
		$colores[] = '0000CC';

		$cantidadColores = count($colores);

		$xmlSeries = '<series>';
		$xmlGraphs = '<graphs><graph  title="Cantidad total de eventos ">';

		$i = 0;
		foreach($eventos as $evento) {
			$xmlSeries .= '<value xid="'.$i.'" >'.$evento[0].'</value>';
			$xmlGraphs .= '<value xid="'.$i.'" color="'.$colores[($i%$cantidadColores)].'">'.$evento[1].'</value>';
			$i++;
		}

		$xmlSeries .= '</series>';
		$xmlGraphs .= '</graph></graphs>';

		$xml .= $xmlSeries;
		$xml .= $xmlGraphs;
		$xml .= '</chart>';

		return $this->renderText($xml);
	}

	public function executeGenerarDatosOcurrenciaEventosTiempo(sfWebRequest $request)
	{
		$criteria = new Criteria();
		$criteria->addJoin(EventoPeer::EVE_CODIGO, EventoEnRegistroPeer::EVRG_EVE_CODIGO);
		$criteria->addJoin(EventoEnRegistroPeer::EVRG_RUM_CODIGO, RegistroUsoMaquinaPeer::RUM_CODIGO);
		$criteria->clearSelectColumns();
		$criteria->addSelectColumn(EventoPeer::EVE_NOMBRE);
		$criteria->addSelectColumn('SUM('.EventoEnRegistroPeer::EVRG_DURACION.')');
		$criteria->addGroupByColumn(EventoPeer::EVE_CODIGO);
		$criteria->addDescendingOrderByColumn('SUM('.EventoEnRegistroPeer::EVRG_DURACION.')');
		$criteria->add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);

		if($request->getParameter('desde_fecha')!='') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_FECHA, $request->getParameter('desde_fecha'), Criteria::GREATER_EQUAL);
		}

		if($request->getParameter('hasta_fecha')!='') {
			$criteria->addAnd(RegistroUsoMaquinaPeer::RUM_FECHA, $request->getParameter('hasta_fecha'), Criteria::LESS_EQUAL);
		}

		if($request->getParameter('categoria_codigo')!='') {
			$criteria->addJoin(EventoPeer::EVE_CODIGO, EventoPorCategoriaPeer::EVCA_EVE_CODIGO);
			$criteria->add(EventoPorCategoriaPeer::EVCA_CAT_CODIGO, $request->getParameter('categoria_codigo'));
		}

		if($request->getParameter('analista_codigo')!='') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $request->getParameter('analista_codigo'));
		}

		if($request->getParameter('maquina_codigo')!='') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $request->getParameter('maquina_codigo'));
		}

		if($request->getParameter('metodo_codigo')!='') {
			$criteria->add(RegistroUsoMaquinaPeer::RUM_MET_CODIGO, $request->getParameter('metodo_codigo'));
		}

		$statement = EventoPeer::doSelectStmt($criteria);
		$eventos = $statement->fetchAll(PDO::FETCH_NUM);

		$xml='<?xml version="1.0"?>';
		$xml.='<chart>';

		$colores = array();
		$colores[] = 'FF6600';
		$colores[] = 'FCD202';
		$colores[] = 'B0DE09';
		$colores[] = '0D8ECF';
		$colores[] = '2A0CD0';
		$colores[] = 'CD0D74';
		$colores[] = 'CC0000';
		$colores[] = '00CC00';
		$colores[] = '0000CC';

		$cantidadColores = count($colores);

		$xmlSeries = '<series>';
		$xmlGraphs = '<graphs><graph  title="Tiempo total de eventos ">';

		$i = 0;
		foreach($eventos as $evento) {
			$xmlSeries .= '<value xid="'.$i.'" >'.$evento[0].'</value>';
			$xmlGraphs .= '<value xid="'.$i.'" color="'.$colores[($i%$cantidadColores)].'">'.$evento[1].'</value>';
			$i++;
		}

		$xmlSeries .= '</series>';
		$xmlGraphs .= '</graph></graphs>';

		$xml .= $xmlSeries;
		$xml .= $xmlGraphs;
		$xml .= '</chart>';

		return $this->renderText($xml);
	}


	/**
	 *@author:maryit sanchez
	 *@date:21 de enero de 2010
	 *Esta funcion retorna  arreglo con los datos totales de ocurrencias por evento
	 */
	public function obtenerDatosTotalEventos()
	{
		$fila=0;
		$datos;

		try{

			$conexion_eventoenregistro=$this->obtenerConexion();

			$conexion = new Criteria();
			$eventos = EventoPeer::doSelect($conexion);

			foreach($eventos as $evento){

				$conexion_evento=$conexion_eventoenregistro;
				$conexion_eventoenregistro->add(EventoEnRegistroPeer::EVRG_EVE_CODIGO,$evento->getEveCodigo());
				$conexion_eventoenregistro->setDistinct();
				$cant_eventoenregistro = EventoEnRegistroPeer::doCount($conexion_evento);

				$datos[$fila]['codigo']=$evento->getEveCodigo();
				$datos[$fila]['nombre']=$evento->getEveNombre();
				//echo($categoria->getEveNombre());
				$datos[$fila]['ocurrecias']=$cant_eventoenregistro;
				$fila++;
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Excepci&oacute;n  en registro de eventos ocurridos ',error:'".$excepcion->getMessage()."'}})";
		}
		return $datos;
	}


	/**
	 *@author:maryit sanchez
	 *@date:21 de enero de 2010
	 *Esta funcion retorna  arreglo con los datos totales de ocurrencias por categoria de eventos
	 */
	public function obtenerDatosTotalEventosPorCategoria()
	{
		$fila=0;
		$datos;

		try{

			$conexion_eventoenregistro=$this->obtenerConexion();

			$conexion = new Criteria();
			$categorias_eventos = CategoriaEventoPeer::doSelect($conexion);

			foreach($categorias_eventos as $categoria){

				$conexion_evento=$conexion_eventoenregistro;
				$conexion_eventoenregistro->addJoin(EventoEnRegistroPeer::EVRG_EVE_CODIGO,EventoPorCategoriaPeer::EVCA_EVE_CODIGO);//ojo cambiar evrg_evr por evrg_eve
				$conexion_eventoenregistro->add(EventoPorCategoriaPeer::EVCA_CAT_CODIGO,$categoria->getCatCodigo() ,CRITERIA::EQUAL);
				$conexion_eventoenregistro->setDistinct();
				$cant_eventoenregistro = EventoEnRegistroPeer::doCount($conexion_evento);

				$datos[$fila]['codigo']=$categoria->getCatCodigo();
				$datos[$fila]['nombre']=$categoria->getCatNombre();
				//echo($categoria->getCatNombre());
				$datos[$fila]['ocurrecias']=$cant_eventoenregistro;
				$fila++;
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Excepci&oacute;n  en registro de eventos ocurridos ',error:'".$excepcion->getMessage()."'}})";
		}
		return $datos;
	}


	/**
	 *@author:maryit sanchez
	 *@date:22 de marzo de 2011
	 *Esta funcion retorna arreglo con los datos totales de tiempos por tipos de eventos
	 */
	public function obtenerDatosTotalMinutosPorEvento()
	{
		$fila=0;
		$datos;

		try{
			$desde_fecha=$this->getRequestParameter('desde_fecha');
			$hasta_fecha=$this->getRequestParameter('hasta_fecha');
			$maquina_codigo=$this->getRequestParameter('maquina_codigo');
			$metodo_codigo=$this->getRequestParameter('metodo_codigo');
			$analista_codigo=$this->getRequestParameter('analista_codigo');
			$categoriaevento_codigo=$this->getRequestParameter('categoria_codigo');

			$consulta="SELECT evrg_eve_codigo, ";
			$consulta.=" sum(evrg_duracion) ";
			$consulta.=" FROM evento_en_registro , registro_uso_maquina ";
			if($categoriaevento_codigo!=''){
				$consulta.=" ,evento_por_categoria ";
			}

			$consulta.=" WHERE evrg_rum_codigo=rum_codigo ";
			if($categoriaevento_codigo!=''){
				$consulta.=" and evrg_eve_codigo=evca_eve_codigo ";
				$consulta.=" and evca_cat_codigo='".$categoriaevento_codigo."'";
			}

			if($desde_fecha!=''){$consulta.=" and rum_fecha>='".$desde_fecha."' "; }
			if($hasta_fecha!=''){$consulta.=" and rum_fecha<='".$hasta_fecha."' "; }
			if($maquina_codigo!=''){$consulta.=" and rum_maq_codigo='".$maquina_codigo."' ";}
			if($metodo_codigo!=''){$consulta.=" and rum_met_codigo='".$metodo_codigo."' ";}
			if($analista_codigo!=''){$consulta.=" and rum_usu_codigo='".$analista_codigo."' ";}
			if($hasta_fecha!=''){$consulta.=" and rum_eliminado=false"; }
			if($hasta_fecha!=''){$consulta.=" group by (evrg_eve_codigo) "; }

			$con = Propel::getConnection();
			$stmt = $con->prepare($consulta);
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
					
				$datos[$fila]['evento'] = $row[0];//evento codigo
				$datos[$fila]['cantidad'] = $row[1];//cantidad minutos
				//echo($row[0].'-'.$row[1]);
				$fila++;
			}

		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Excepci&oacute;n en reporte de eventos tiempo vs evento ',error:'".$excepcion->getMessage()."'}})";
		}
		return $datos;
	}



	/**
	 *@author:maryit sanchez
	 *@date:6 de enero de 2011
	 *Esta funcion retorna  un listado de los analistas
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


		/**
		 *@author:maryit sanchez
		 *@date:22 de enero de 2011
		 *Esta funcion retorna  un listado de las categorias de eventos
		 */
		public function executeListarCategoriaEventos(sfWebRequest $request)
		{
			$salida='({"total":"0", "results":""})';
			$fila=0;
			$datos;
			try{
				$conexion = new Criteria();
				$conexion->addDescendingOrderByColumn(CategoriaEventoPeer::CAT_NOMBRE);
				$categorias = CategoriaEventoPeer::doSelect($conexion);
					
				foreach($categorias as $temporal)
				{
					$datos[$fila]['cat_codigo'] = $temporal->getCatCodigo();
					$datos[$fila]['cat_nombre'] = $temporal->getCatNombre();
					$fila++;
				}
				$datos[$fila]['cat_codigo']='';
				$datos[$fila]['cat_nombre'] ='TODAS';
				$fila++;
					
				if($fila>0){
					$jsonresult = json_encode($datos);
					$salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
				}
			}
			catch (Exception $excepcion)
			{
				//	return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en listar categorias ',error:'".$excepcion->getMessage()."'}})";
			}
			return $this->renderText($salida);
		}
}
