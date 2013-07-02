<?php

/**
 * gestion_metaanual actions.
 *
 * @package    tpmlabs
 * @subpackage gestion_metaanual
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class gestion_metaanualActions extends sfActions
{
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request)
	{
		// $this->forward('default', 'module');
	}

	/**
	 *@author:maryit sanchez
	 *@date:22 de  diciembre de 2010
	 *Esta funcion que crear y actualiza un MetaAnualXIndicador
	 */
	public function executeActualizarMeta(sfWebRequest $request)
	{
		$salida = '';

		try{
			$metaanualxindicador;
			$mea_anio=$this->getRequestParameter('mea_anio');
			$mea_ind_codigo = $this->getRequestParameter('mea_ind_codigo');
			$mea_emp_codigo=$this->getRequestParameter('mea_emp_codigo');//cambiar por empresa logueada
				
			if($mea_anio!='' && $mea_emp_codigo!='')
			{
				$conexion = new Criteria();
				$conexion->add(MetaAnualXIndicadorPeer::MEA_ANIO,$mea_anio);
				$conexion->add(MetaAnualXIndicadorPeer::MEA_IND_CODIGO,$mea_ind_codigo);
				$conexion->add(MetaAnualXIndicadorPeer::MEA_EMP_CODIGO,$mea_emp_codigo);

				$metaanualxindicador = MetaAnualXIndicadorPeer::doSelectOne($conexion);
					
				if(!$metaanualxindicador){
						
					$metaanualxindicador = new MetaAnualXIndicador();
					$metaanualxindicador->setMeaFechaRegistroSistema(time());
					$metaanualxindicador->setMeaUsuCrea($this->getUser()->getAttribute('usu_codigo'));
					$metaanualxindicador->setMeaEliminado(0);
				}
					
				if($metaanualxindicador)
				{
					$metaanualxindicador->setMeaAnio($mea_anio);
					$metaanualxindicador->setMeaIndCodigo($mea_ind_codigo);
					$metaanualxindicador->setMeaEmpCodigo($mea_emp_codigo);
					$metaanualxindicador->setMeaFechaActualizacion(time());
					$metaanualxindicador->setMeaUsuActualiza($this->getUser()->getAttribute('usu_codigo'));

					$mea_valor=$this->getRequestParameter('mea_valor');
						
					if($mea_valor==''){
						$metaanualxindicador->setMeaValor(null);
					}
					else{
						$metaanualxindicador->setMeaValor($mea_valor);
					}
						
					$metaanualxindicador->save();

					$salida = "({success: true, mensaje:'La meta fue actualizada exitosamente'})";
				}
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en meta anual por indicador',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:22 de  diciembre de 2010
	 *Esta funcion retorna  un listado de MetaAnualXIndicadores
	 */
	public function executeListarMetaanual(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			$mea_anio=$this->getRequestParameter('mea_anio');
			$mea_emp_codigo=$this->getUser()->getAttribute('empl_emp_codigo');
				
				
			if($mea_anio!='' && $mea_emp_codigo!='')
			{
				$conexion2 = new Criteria();
				$indicadores = IndicadorPeer::doSelect($conexion2);

				foreach($indicadores as $temporal)
				{
					$datos[$fila]['mea_ind_codigo']=$temporal->getIndCodigo();
					$datos[$fila]['mea_emp_codigo'] = $mea_emp_codigo;//empresa logueada
					$datos[$fila]['mea_anio'] = $mea_anio;
					$datos[$fila]['mea_ind_nombre'] = $temporal->getIndNombre().' ('.$temporal->getIndUnidad().')';
					$ind_codigo=$temporal->getIndCodigo();

					$metaanual=$this->obtenerMetaAnualPorIndicador($mea_anio,$ind_codigo,$mea_emp_codigo);
					if($metaanual){
						//$datos[$fila]['mea_valor'] = $this->obtenerMetaPorIndicador($mea_anio,$ind_codigo,$mea_emp_codigo);
						//$datos[$fila]['mea_fecha_registro_sistema'] = $this->obtenerFechaMetaPorIndicador($mea_anio,$ind_codigo,$mea_emp_codigo);
						$datos[$fila]['mea_valor'] = $metaanual->getMeaValor();
						$datos[$fila]['mea_fecha_registro_sistema'] = $metaanual->getMeaFechaRegistroSistema();
						$datos[$fila]['mea_fecha_actualizacion'] = $metaanual->getMeaFechaActualizacion();
						//$datos[$fila]['mea_usu_crea'] = $metaanual->getMeaUsuCrea();
						//$datos[$fila]['mea_usu_actualiza'] = $metaanual->getMeaUsuActualiza();
						$datos[$fila]['mea_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($metaanual->getMeaUsuCrea());
						$datos[$fila]['mea_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($metaanual->getMeaUsuActualiza());
					}
					else{
						$datos[$fila]['mea_valor'] ='-';
						$datos[$fila]['mea_fecha_registro_sistema'] = '';
						$datos[$fila]['mea_fecha_actualizacion'] = '';
						$datos[$fila]['mea_usu_crea_nombre'] = '';
						$datos[$fila]['mea_usu_actualiza_nombre'] = '';
					}

					$fila++;
				}
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
					
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en metaanualxindicador al listar ',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
	/**
	 *@author:maryit sanchez
	 *@date:22 de  diciembre de 2010
	 *Esta funcion el nombre de un indicador
	 */
	public function obtenerIndicadorNombre($ind_codigo)
	{
		$salida = '';
		try{
			$indicador;
			$indicador  = indicadorPeer::retrieveByPk($ind_codigo);
				
			if($indicador)
			{
				$salida= $indicador->getIndNombre();
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en indicador',error:'".$excepcion->getMessage()."'}})";
		}
		return $salida;
	}

	/**
	 *@author:maryit sanchez
	 *@date:12 de  enero de 2011
	 *Esta funcion retorna la meta de un indicador especifico en un anio especifico y para una empresa determinada
	 */
	public function obtenerMetaAnualPorIndicador($anio,$ind_codigo,$emp_codigo)
	{
		$metaanualxindicador;
		try{
			if($anio!='' && $emp_codigo!='' && $ind_codigo!='')
			{
				$conexion = new Criteria();
				$conexion->add(MetaAnualXIndicadorPeer::MEA_ANIO,$anio);
				$conexion->add(MetaAnualXIndicadorPeer::MEA_EMP_CODIGO,$emp_codigo);
				$conexion->add(MetaAnualXIndicadorPeer::MEA_IND_CODIGO,$ind_codigo);
				$metaanualxindicador = MetaAnualXIndicadorPeer::doSelectOne($conexion);
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en obtenerMetaAnualPorIndicador',error:'".$excepcion->getMessage()."'}})";
		}
		return $metaanualxindicador;
	}
	/**
	 *@author:maryit sanchez
	 *@date:12 de  enero de 2011
	 *Esta funcion retorna la meta de un indicador especifico en un anio especifico y para una empresa determinada
	 */
	public function obtenerMetaPorIndicador($anio,$ind_codigo,$emp_codigo)
	{
		$salida='-';
		try{
			if($anio!='' && $emp_codigo!='' && $ind_codigo!='')
			{
				$conexion = new Criteria();
				$conexion->add(MetaAnualXIndicadorPeer::MEA_ANIO,$anio);
				$conexion->add(MetaAnualXIndicadorPeer::MEA_EMP_CODIGO,$emp_codigo);
				$conexion->add(MetaAnualXIndicadorPeer::MEA_IND_CODIGO,$ind_codigo);
				$metaanualxindicador = MetaAnualXIndicadorPeer::doSelectOne($conexion);

				if($metaanualxindicador){
					$salida= $metaanualxindicador->getMeaValor();
				}
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en obtenerMetaPorIndicador',error:'".$excepcion->getMessage()."'}})";
		}
		return $salida;
	}


	/**
	 *@author:maryit sanchez
	 *@date:12 de  enero de 2011
	 *Esta funcion retorna la meta de un indicador especifico en un anio especifico y para una empresa determinada
	 */
	public function obtenerFechaMetaPorIndicador($anio,$ind_codigo,$emp_codigo)
	{
		$salida='';
		try{
			if($anio!='' && $emp_codigo!='' && $ind_codigo!='')
			{
				$conexion = new Criteria();
				$conexion->add(MetaAnualXIndicadorPeer::MEA_ANIO,$anio);
				$conexion->add(MetaAnualXIndicadorPeer::MEA_EMP_CODIGO,$emp_codigo);
				$conexion->add(MetaAnualXIndicadorPeer::MEA_IND_CODIGO,$ind_codigo);
				$metaanualxindicador = MetaAnualXIndicadorPeer::doSelectOne($conexion);

				if($metaanualxindicador){
					$salida= $metaanualxindicador->getMeaFechaRegistroSistema();
				}
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en obtenerFechaMetaPorIndicador',error:'".$excepcion->getMessage()."'}})";
		}
		return $salida;
	}

}
