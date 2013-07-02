<?php

/**
 * maestra_indicador actions.
 *
 * @package    tpmlabs
 * @subpackage maestra_indicador
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class maestra_indicadorActions extends sfActions
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
	 *@date:13 de diciembre de 2010
	 *Esta funcion que crear y actualiza un Indicador
	 */
	public function executeActualizarIndicador(sfWebRequest $request)
	{
		$salida = '';

		try{
			$ind_codigo = $this->getRequestParameter('maestra_ind_codigo');
			$indicador;
				
			if($ind_codigo!=''){
				$indicador  = IndicadorPeer::retrieveByPk($ind_codigo);
			}
			else
			{
				$indicador = new Indicador();
				$indicador->setIndFechaRegistroSistema(time());
				$indicador->setIndUsuCrea($this->getUser()->getAttribute('usu_codigo'));
				$indicador->setIndEliminado(0);
			}
				
			if($indicador)
			{
				$indicador->setIndNombre($this->getRequestParameter('maestra_ind_nombre'));
				$indicador->setIndSigla($this->getRequestParameter('maestra_ind_sigla'));
				$indicador->setIndUnidad($this->getRequestParameter('maestra_ind_unidad'));
				$indicador->setIndFechaActualizacion(time());
				$indicador->setIndUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
				$indicador->setIndCausaActualizacion($this->getRequestParameter('maestra_ind_causa_actualizacion'));
				$indicador->save();

				$salida = "({success: true, mensaje:'El indicador fue actualizado exitosamente'})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en indicador',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:13 de diciembre de 2010
	 *Esta funcion retorna  un listado de Indicadores
	 */
	public function executeListarIndicador(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			$ind_eliminado=$this->getRequestParameter('ind_eliminado');//los de mostrar
			if($ind_eliminado==''){
				$ind_eliminado=0;
			}
			
			$conexion = new Criteria();
			$conexion->add(IndicadorPeer::IND_ELIMINADO,$ind_eliminado);	
			$cantidad_indicador = IndicadorPeer::doCount($conexion);
			$conexion->setOffset($this->getRequestParameter('start'));
			$conexion->setLimit($this->getRequestParameter('limit'));
			$indicador = IndicadorPeer::doSelect($conexion);

			foreach($indicador as $temporal)
			{
				$datos[$fila]['maestra_ind_codigo']=$temporal->getIndCodigo();
				$datos[$fila]['maestra_ind_nombre'] = $temporal->getIndNombre();
				$datos[$fila]['maestra_ind_sigla'] = $temporal->getIndSigla();
				$datos[$fila]['maestra_ind_unidad'] = $temporal->getIndUnidad();
				
				$datos[$fila]['maestra_ind_fecha_registro_sistema'] = $temporal->getIndFechaRegistroSistema();
				$datos[$fila]['maestra_ind_fecha_actualizacion'] = $temporal->getIndFechaActualizacion();
				
				$datos[$fila]['maestra_ind_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getIndUsuCrea());
				$datos[$fila]['maestra_ind_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getIndUsuActualiza());
				$datos[$fila]['maestra_ind_eliminado'] = $temporal->getIndEliminado();
				$datos[$fila]['maestra_ind_causa_eliminacion'] = $temporal->getIndCausaEliminacion();
				$datos[$fila]['maestra_ind_causa_actualizacion'] = $temporal->getIndCausaActualizacion();
			
				$fila++;
			}
			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$cantidad_indicador.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en indicador al listar ',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:13 de diciembre de 2010
	 *Esta funcion elimina un Indicador
	 */
	public function executeEliminarIndicador(sfWebRequest $request)
	{
		$salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n indicador'}})";
		try{
			$ind_codigo = $this->getRequestParameter('maestra_ind_codigo');
			$causa_eliminacion = $this->getRequestParameter('maestra_ind_causa_eliminacion');
			
			if($ind_codigo!=''){	
				//validar foraneas
				/*
				$conexion = new Criteria();
				$conexion->add(MetaAnualXIndicadorPeer::MEA_IND_CODIGO, $ind_codigo);
				$meta= MetaAnualXIndicadorPeer::doCount($conexion);	
				
				if($meta==0){
					$indicador  = IndicadorPeer::retrieveByPk($ind_codigo);
					if($indicador){
						$indicador->delete();
						$salida = "({success: true, mensaje:'El indicador fue eliminado exitosamente'})";
					}
				}
				else
				{*/
					$indicador  = IndicadorPeer::retrieveByPk($ind_codigo);
					if($indicador){
						$indicador->setIndUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina
						$indicador->setIndFechaActualizacion(time());
						$indicador->setIndEliminado(1);
						$indicador->setIndCausaEliminacion($causa_eliminacion);
						$indicador->save();
					}
					//$salida = "({success: false,  errors: { reason: '<b>NO</b> se pudo eliminar el indicador, porque hay metas que lo utilizan'}})";
				//}
			}
		}
		catch (Exception $excepcion)
		{
				
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en indicador al tratar de eliminar',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
	
	
	/**
  *@author:maryit sanchez
  *@date:29 de enero de 2010
  *Este metodo permite restablecer un objeto eliminado
  */	  
	public function executeRestablecerindicador()
	{
		$salida = "({success: false, errors: { reason: 'No se pudo restablecer el indicador'}})";
		try{
			$ind_codigo = $this->getRequestParameter('maestra_ind_codigo');
			$causa_reestablece= $this->getRequestParameter('maestra_ind_causa_restablece');
			
			
			$indicador  = IndicadorPeer::retrieveByPk($maq_codigo);
				
				if($indicador)
				{
					$indicador->setIndUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo reestablece
					$indicador->setIndFechaActualizacion(time());
					$indicador->setIndEliminado(0);
					$indicador->setIndCausaActualizacion($causa_reestablece);
					$indicador->save();
					$salida = "({success: true, mensaje:'El indicador fue restablecido exitosamente'})";
				}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
}
