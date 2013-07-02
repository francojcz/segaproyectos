<?php

/**
 * maestra_tipoidentificacion actions.
 *
 * @package    tpmlabs
 * @subpackage maestra_tipoidentificacion
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class maestra_tipoidentificacionActions extends sfActions
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
	 *@date:14 de diciembre de 2010
	 *Tida funcion que crear y actualiza un Tipoidentificacion
	 */
	public function executeActualizarTipoidentificacion(sfWebRequest $request)
	{
		$salida = '';

		try{
			$tid_codigo = $this->getRequestParameter('maestra_tid_codigo');
			$tipoidentificacion;
				
			if($tid_codigo!=''){
				$tipoidentificacion  = TipoIdentificacionPeer::retrieveByPk($tid_codigo);
				
			}
			else
			{
				$tipoidentificacion = new Tipoidentificacion();
				$tipoidentificacion->setTidFechaRegistroSistema(time());
				$tipoidentificacion->setTidUsuCrea($this->getUser()->getAttribute('usu_codigo'));
				$tipoidentificacion->setTidEliminado(0);
			}
				
			if($tipoidentificacion)
			{
				$tipoidentificacion->setTidNombre($this->getRequestParameter('maestra_tid_nombre'));
				$tipoidentificacion->setTidFechaActualizacion(time());
				$tipoidentificacion->setTidUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
				$tipoidentificacion->setTidCausaActualizacion($this->getRequestParameter('maestra_tid_causa_actualizacion'));
				$tipoidentificacion->save();

				$salida = "({success: true, mensaje:'El tipo de identificaci&oacute;n fue actualizado exitosamente'})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en tipo de identificaci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:14 de diciembre de 2010
	 *Tida funcion retorna  un listado de Tipoidentificaciones
	 */
	public function executeListarTipoidentificacion(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			$tid_eliminado=$this->getRequestParameter('tid_eliminado');//los de mostrar
			if($tid_eliminado==''){
				$tid_eliminado=0;
			}
			
			$conexion = new Criteria();
			$conexion->add(TipoIdentificacionPeer::TID_ELIMINADO,$tid_eliminado);	
			$cantidad_tid_ado = TipoIdentificacionPeer::doCount($conexion);
			$conexion->setOffset($this->getRequestParameter('start'));
			$conexion->setLimit($this->getRequestParameter('limit'));
			$tipoidentificacion = TipoIdentificacionPeer::doSelect($conexion);

			foreach($tipoidentificacion as $temporal)
			{
				$datos[$fila]['maestra_tid_codigo']=$temporal->getTidCodigo();
				$datos[$fila]['maestra_tid_nombre'] = $temporal->getTidNombre();
				
				$datos[$fila]['maestra_tid_fecha_registro_sistema'] = $temporal->getTidFechaRegistroSistema();
				$datos[$fila]['maestra_tid_fecha_actualizacion'] = $temporal->getTidFechaActualizacion();
				$datos[$fila]['maestra_tid_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getTidUsuCrea());
				$datos[$fila]['maestra_tid_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getTidUsuActualiza());
				$datos[$fila]['maestra_tid_eliminado'] = $temporal->getTidEliminado();
				$datos[$fila]['maestra_tid_causa_eliminacion'] = $temporal->getTidCausaEliminacion();
				$datos[$fila]['maestra_tid_causa_actualizacion'] = $temporal->getTidCausaActualizacion();
			
				$fila++;
			}
			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$cantidad_tid_ado.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en tipo de identificaci&oacute;n al listar ',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:14 de diciembre de 2010
	 *Tida funcion elimina un Tipoidentificacion
	 */
	public function executeEliminarTipoidentificacion(sfWebRequest $request)
	{
		$salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n tipo de identificaci&oacute;n'}})";
		try{
			$tid_codigo = $this->getRequestParameter('maestra_tid_codigo');
			$causa_eliminacion = $this->getRequestParameter('maestra_tid_causa_eliminacion');
			
			if($tid_codigo!=''){
			//validar foraneas
			/*
			$conexion = new Criteria();
			$conexion->add(EmpleadoPeer::EMPL_TID_CODIGO, $tid_codigo);
			$empleado = EmpleadoPeer::doCount($conexion);
			
				if($empleado==0){					
					$tipoidentificacion  = TipoIdentificacionPeer::retrieveByPk($tid_codigo);
					if($tipoidentificacion){
						$tipoidentificacion->delete();
						$salida = "({success: true, mensaje:'El tipo de identificaci&oacute;n fue eliminado exitosamente'})";
					}
				}
				else
				{*/
				
				$tipoidentificacion  = TipoIdentificacionPeer::retrieveByPk($tid_codigo);
				if($tipoidentificacion){
					$tipoidentificacion->setTidUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina
					$tipoidentificacion->setTidFechaActualizacion(time());
					$tipoidentificacion->setTidEliminado(1);
					$tipoidentificacion->setTidCausaEliminacion($causa_eliminacion);
					$tipoidentificacion->save();
					$salida = "({success: true, mensaje:'El tipo de identificaci&oacute;n fue eliminado exitosamente'})";
					//$salida = "({success: false,  errors: { reason: '<b>NO</b> se pudo eliminar el tipo de identificaci&oacute;n, porque hay empleados que la estan utilizando'}})";
				}
			}
		}
		catch (Exception $excepcion)
		{
				
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en tipo de identificaci&oacute;n al tratar de eliminar',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
	
	
	/**
  *@author:maryit sanchez
  *@date:29 de enero de 2010
  *Este metodo permite restablecer un objeto eliminado
  */	  
	public function executeRestablecerTipoIdentificacion()
	{
		$salida = "({success: false, errors: { reason: 'No se pudo restablecer el tipo identificaci&oacute;n '}})";
		try{
			$tid_codigo = $this->getRequestParameter('maestra_tid_codigo');
			$causa_reestablece= $this->getRequestParameter('maestra_tid_causa_restablece');
			
			
			$tipoidentificacion  = TipoIdentificacionPeer::retrieveByPk($tid_codigo);
				
				if($tipoidentificacion)
				{
					$tipoidentificacion->setTidUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo reestablece
					$tipoidentificacion->setTidFechaActualizacion(time());
					$tipoidentificacion->setTidEliminado(0);
					$tipoidentificacion->setTidCausaActualizacion($causa_reestablece);
					$tipoidentificacion->save();
					$salida = "({success: true, mensaje:'El tipo identificaci&oacute;n fue restablecido exitosamente'})";
				}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
}
