<?php

/**
 * crud_maquina actions.
 *
 * @package    tpmlabs
 * @subpackage crud_maquina
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class crud_maquinaActions extends sfActions
{
	public function executeListarComputadores() {
		$computadores = ComputadorPeer::doSelect(new Criteria());

		$result = array();
		$data = array();

		foreach($computadores as $computador) {
			$fields = array();

			//			$computador = new Computador();

			$fields['certificado'] = $computador->getComCertificado();
			$fields['nombre'] = $computador->getComNombre();

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
		//$this->forward('default', 'module');
	}


	/**
	 *@author:maryit sanchez
	 *@date:20 de diciembre de 2010
	 *Esta funcion que crear y actualiza un maquina
	 */
	public function executeActualizarMaquina(sfWebRequest $request)
	{
		$salida = '';

		try{
			$maq_codigo = $this->getRequestParameter('maq_codigo');
			$maquina = null;

			if($maq_codigo!=''){
				$maquina  = MaquinaPeer::retrieveByPk($maq_codigo);

				if($maquina->getMaqEliminado()) {
					$salida = "({success: false, errors: { reason: 'No es posible modificar un equipo que ha sido eliminado'}})";
					return $this->renderText($salida);
				}
			}
			else
			{
				$maquina = new maquina();
				$maquina->setMaqFechaRegistroSistema(time());
				$maquina->setMaqUsuCrea($this->getUser()->getAttribute('usu_codigo'));
				$maquina->setMaqEliminado(0);
			}

			if($maquina)
			{
				$maquina->setMaqCodigoInventario($this->getRequestParameter('maq_codigo_inventario'));
				$maquina->setMaqEstCodigo($this->getRequestParameter('maq_est_codigo'));
				$maquina->setMaqNombre($this->getRequestParameter('maq_nombre'));
				$maquina->setMaqMarca($this->getRequestParameter('maq_marca'));
				$maquina->setMaqModelo($this->getRequestParameter('maq_modelo'));
				$maquina->setMaqFechaAdquisicion($this->getRequestParameter('maq_fecha_adquisicion'));
				$maquina->setMaqTiempoInyeccion($this->getRequestParameter('maq_tiempo_inyeccion'));
				//--$maquina->setMaqTiempoInyeccionActual($this->getRequestParameter('maq_tiempo_inyeccion_actual'));
				$maquina->setMaqComCertificado($this->getRequestParameter('certificado'));
				$maquina->setMaqFechaActualizacion(time());
				$maquina->setMaqUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
				$maquina->setMaqCausaActualizacion($this->getRequestParameter('maq_causa_actualizacion'));
                                $maquina->setMaqCatCodigo($this->getRequestParameter('maq_cat_codigo'));
                                $maquina->setMaqIndicadores($this->getRequestParameter('maq_indicadores'));

				$maquina->save();

				$salida = "({success: true, mensaje:'El equipo en inventario fue actualizado exitosamente'})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en equipo en inventario ".$excepcion."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:3 de octubre de 2010
	 *Esta funcion un listado de maquina
	 */
	public function executeListarMaquina(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			$est_codigo=$this->getRequestParameter('est_codigo');
			$start=$this->getRequestParameter('start');
			$limit=$this->getRequestParameter('limit');
			$maq_eliminado=$this->getRequestParameter('maq_eliminado');//los de mostrar
			if($maq_eliminado==''){
				$maq_eliminado=0;
			}

			$conexion = new Criteria();
			$conexion->add(MaquinaPeer::MAQ_ELIMINADO,$maq_eliminado);
			if($est_codigo){
				$conexion->add(MaquinaPeer::MAQ_EST_CODIGO,$est_codigo);
			}

			$maquinas_cantidad = MaquinaPeer::doCount($conexion);

			if($start!=''){
				$conexion->setOffset($start);
				$conexion->setLimit($limit);
			}
			$maquina = MaquinaPeer::doSelect($conexion);

			foreach($maquina as $temporal)
			{

				$datos[$fila]['maq_codigo']=$temporal->getMaqCodigo();
				$datos[$fila]['maq_codigo_inventario'] = $temporal->getMaqCodigoInventario();
				$datos[$fila]['maq_nombre'] = $temporal->getMaqNombre();
				$datos[$fila]['maq_est_codigo'] = $temporal->getMaqEstCodigo();
				$datos[$fila]['maq_modelo'] = $temporal->getMaqModelo();
				$datos[$fila]['maq_marca'] = $temporal->getMaqMarca();
				$datos[$fila]['maq_tiempo_inyeccion'] = $temporal->getMaqTiempoInyeccion();
				//--$datos[$fila]['maq_tiempo_inyeccion_actual'] = $temporal->getMaqTiempoInyeccionActual();
				$datos[$fila]['maq_fecha_adquisicion'] = $temporal->getMaqFechaAdquisicion();
				$datos[$fila]['certificado'] = $temporal->getMaqComCertificado();
				$datos[$fila]['maq_fecha_registro_sistema'] = $temporal->getMaqFechaRegistroSistema();
				$datos[$fila]['maq_fecha_actualizacion'] = $temporal->getMaqFechaActualizacion();
				//$datos[$fila]['maq_usu_crea'] = $temporal->getMaqUsuCrea();
				//$datos[$fila]['maq_usu_actualiza'] = $temporal->getMaqUsuActualiza();
				$datos[$fila]['maq_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getMaqUsuCrea());
				$datos[$fila]['maq_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getMaqUsuActualiza());
				$datos[$fila]['maq_eliminado'] = $temporal->getMaqEliminado();
				$datos[$fila]['maq_causa_eliminacion'] = $temporal->getMaqCausaEliminacion();
				$datos[$fila]['maq_causa_actualizacion'] = $temporal->getMaqCausaActualizacion();
                                $datos[$fila]['maq_cat_codigo'] = $temporal->getMaqCatCodigo();
                                $datos[$fila]['maq_indicadores'] = $temporal->getMaqIndicadores();                                
                                                                
				$fila++;
			}
			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$maquinas_cantidad.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en equipo ',error:".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:3 de octubre de 2010
	 *Esta funcion elimina un maquina
	 */
	public function executeEliminarMaquina(sfWebRequest $request)
	{
		$salida = '';

		try{
			$maq_codigo = $this->getRequestParameter('maq_codigo');
			$causa_eliminacion= $this->getRequestParameter('maq_causa_eliminacion');


			if($maq_codigo!=''){
					
				/*
				 $maquina  = maquinaPeer::retrieveByPk($maq_codigo);
				 if($maquina){
					$maquina->delete();
					$salida = "({success: true, mensaje:'El equipo fue eliminada exitosamente'})";
					}
					else{
					*/

				$maquina  = MaquinaPeer::retrieveByPk($maq_codigo);
				if($maquina){
					$maquina->setMaqUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina
					$maquina->setMaqFechaActualizacion(time());
					$maquina->setMaqEliminado(1);
					$maquina->setMaqCausaEliminacion($causa_eliminacion);
					$maquina->save();
					$salida = "({success: true, mensaje:'El equipo fue eliminado exitosamente'})";
				}
				//}
			}
			else
			{
				$salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n equipo'}})";
			}

		}
		catch (Exception $excepcion)
		{

			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en equipo al tratar de eliminar ',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:20 de diciembre de 2010
	 *Esta funcion lista los  estados del maquina
	 */
	public function executeListarEstado(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;
		try{
			$conexion = new Criteria();
			$estados = EstadoPeer::doSelect($conexion);

			foreach($estados As $temporal)
			{
				$datos[$fila]['est_codigo'] = $temporal->getEstCodigo();
				$datos[$fila]['est_nombre'] = $temporal->getEstNombre();
				$fila++;
			}

			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
			}
		}catch (Exception $excepcion)
		{
			$salida='exception en listar Estados';
		}
		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:29 de enero de 2010
	 *Este metodo permite restablecer un objeto eliminado
	 */
	public function executeRestablecerMaquina()
	{
		$salida = "({success: false, errors: { reason: 'No se pudo restablecer el equipo'}})";
		try{
			$maq_codigo = $this->getRequestParameter('maq_codigo');
			$causa_reestablece= $this->getRequestParameter('maq_causa_restablece');


			$maquina  = MaquinaPeer::retrieveByPk($maq_codigo);

			if($maquina)
			{
				$maquina->setMaqUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo reestablece
				$maquina->setMaqFechaActualizacion(time());
				$maquina->setMaqEliminado(0);
				$maquina->setMaqCausaActualizacion($causa_reestablece);
				$maquina->save();
				$salida = "({success: true, mensaje:'El equipo fue restablecido exitosamente'})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
        
        
        public function executeListarCategoria(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;
		try{
			$conexion = new Criteria();
			$categorias = CategoriaEquipoPeer::doSelect($conexion);
			foreach($categorias As $temporal) {
				$datos[$fila]['cat_codigo'] = $temporal->getCatCodigo();
				$datos[$fila]['cat_nombre'] = $temporal->getCatNombre();
				$fila++;
			}

			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
			}
		}catch (Exception $excepcion)
		{
			$salida='exception en listar Categorias';
		}
		return $this->renderText($salida);
	}

        public function executeListarPeriodos()
        {
            $criteria = new Criteria();
            $criteria -> addAscendingOrderByColumn(PeriodoMantenimientoPeer::PMTO_PERIODO);
            $periodos = PeriodoMantenimientoPeer::doSelect($criteria);
            $result = array();
            $data = array();

            foreach ($periodos as $periodo)
            {
                $fields = array();
                $fields['codigo'] = $periodo -> getPmtoCodigo();
                
                //Tipo de Periodo
                $criteria = new Criteria();                
                $criteria -> add(TipoPeriodoPeer::TP_CODIGO, $periodo->getPmtoTipo());
                $tipo = TipoPeriodoPeer::doSelectOne($criteria);
                
                if($periodo->getPmtoPeriodo()==1 && $tipo->getTpNombre()=='Día')
                    $fields['nombre'] = $periodo->getPmtoPeriodo().' '.$tipo->getTpNombre();
                if($periodo->getPmtoPeriodo()>1 && $tipo->getTpNombre()=='Día')
                    $fields['nombre'] = $periodo->getPmtoPeriodo().' '.$tipo->getTpNombre().'s';
                if($periodo->getPmtoPeriodo()==1 && $tipo->getTpNombre()=='Mes')
                    $fields['nombre'] = $periodo->getPmtoPeriodo().' '.$tipo->getTpNombre();
                if($periodo->getPmtoPeriodo()>1 && $tipo->getTpNombre()=='Mes')
                    $fields['nombre'] = $periodo->getPmtoPeriodo().' '.$tipo->getTpNombre().'es';

                $data[] = $fields;
            }

            $result['data'] =  $data;
            return $this -> renderText(json_encode($result));
        }
        
        
        public function executeListarRegistrosPeriodoMaquina(sfWebRequest $request)
        {
            $criteria = new Criteria();
            if ($request -> hasParameter('cod_maquina'))
                $criteria -> add(RegistroPmtoMaquinaPeer::RPM_MAQ_CODIGO, $request -> getParameter('cod_maquina'));
            
            $registrosPeriodos = RegistroPmtoMaquinaPeer::doSelect($criteria);

            $result = array();
            $data = array();
            foreach ($registrosPeriodos as $registrosPeriodo)
            {
                $fields = array();
                $fields['codigo'] = $registrosPeriodo -> getRpmCodigo();
                $fields['id_periodo'] = $registrosPeriodo -> getRpmPmtoCodigo();
                $fields['fecha_inicio'] = $registrosPeriodo -> getRpmFechaInicio();
                $fields['usu_registra'] = UsuarioPeer::obtenerNombreUsuario($registrosPeriodo -> getRpmUsuRegistra());
                $fields['fecha_registro'] = $registrosPeriodo -> getRpmFechaRegistro();
                $data[] = $fields;
            }
            $result['data'] = $data;
            return $this -> renderText(json_encode($result));
        }
        
        public function executeRegistrarPeriodo(sfWebRequest $request)
        {
            $user = $this -> getUser();           
            $codigo_usuario = $user -> getAttribute('usu_codigo');
            
            $registro_rpm = '';
            $criteria = new Criteria();
            $criteria -> add(RegistroPmtoMaquinaPeer::RPM_PMTO_CODIGO, $request->getParameter('id_periodo'));
            $criteria -> add(RegistroPmtoMaquinaPeer::RPM_MAQ_CODIGO, $request->getParameter('maq_codigo'));
            $registro_rpm += RegistroPmtoMaquinaPeer::doSelectOne($criteria);
            
            if($registro_rpm == ''){
                $registro = new RegistroPmtoMaquina();
                $registro -> setRpmMaqCodigo($request->getParameter('maq_codigo'));
                $registro -> setRpmPmtoCodigo($request->getParameter('id_periodo'));
                $registro -> setRpmFechaInicio($request->getParameter('fecha_inicio'));
                $registro -> setRpmUsuRegistra($codigo_usuario);
                $registro -> setRpmFechaRegistro(date('Y-m-d H:i:s'));
                $registro -> save();
                return $this -> renderText('Ok');
            }
            else
                return $this -> renderText('1');
            
        }
        
        public function executeEliminarPeriodo(sfWebRequest $request)
        {        
            if ($request -> hasParameter('codigo'))
            {
                $registro = RegistroPmtoMaquinaPeer::retrieveByPK($request -> getParameter('codigo'));
                $registro ->delete();
            }
            return $this -> renderText('Ok');
        }
}