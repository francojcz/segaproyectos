<?php

/**
 * maestra_estado actions.
 *
 * @package    tpmlabs
 * @subpackage maestra_estado
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class maestra_estadoActions extends sfActions
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
  
  
	/**
	 *@author:maryit sanchez
	 *@date:14 de diciembre de 2010
	 *Esta funcion que crear y actualiza un Estado
	 */
	public function executeActualizarEstado(sfWebRequest $request)
	{
		$salida = '';

		try{
			$est_codigo = $this->getRequestParameter('maestra_est_codigo');
			$estado;
				
			if($est_codigo!=''){
				$estado  = EstadoPeer::retrieveByPk($est_codigo);
			}
			else
			{
				$estado = new Estado();
				$estado->setEstFechaRegistroSistema(time());
				$estado->setEstUsuCrea($this->getUser()->getAttribute('usu_codigo'));
				$estado->setEstEliminado(0);
			}
				
			if($estado)
			{
				$estado->setEstNombre($this->getRequestParameter('maestra_est_nombre'));
				$estado->setEstFechaActualizacion(time());
				$estado->setEstUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
				$estado->setEstCausaActualizacion($this->getRequestParameter('maestra_est_causa_actualizacion'));
				$estado->save();

				$salida = "({success: true, mensaje:'El estado fue actualizado exitosamente'})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en estado',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:14 de diciembre de 2010
	 *Esta funcion retorna  un listado de Estadoes
	 */
	public function executeListarEstado(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			$est_eliminado=$this->getRequestParameter('est_eliminado');//los de mostrar
			if($est_eliminado==''){
				$est_eliminado=0;
			}
			
			$conexion = new Criteria();
			$conexion->add(EstadoPeer::EST_ELIMINADO,$est_eliminado);	
			$cantidad_estado = EstadoPeer::doCount($conexion);
			$conexion->setOffset($this->getRequestParameter('start'));
			$conexion->setLimit($this->getRequestParameter('limit'));
			$estado = EstadoPeer::doSelect($conexion);

			foreach($estado as $temporal)
			{
				$datos[$fila]['maestra_est_codigo']=$temporal->getEstCodigo();
				$datos[$fila]['maestra_est_nombre'] = $temporal->getEstNombre();
				
				$datos[$fila]['maestra_est_fecha_registro_sistema'] = $temporal->getEstFechaRegistroSistema();
				$datos[$fila]['maestra_est_fecha_actualizacion'] = $temporal->getEstFechaActualizacion();
				//$datos[$fila]['maestra_est_usu_crea'] = $temporal->getEstUsuCrea();
				//$datos[$fila]['maestra_est_usu_actualiza'] = $temporal->getEstUsuActualiza();
				$datos[$fila]['maestra_est_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getEstUsuCrea());
				$datos[$fila]['maestra_est_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getEstUsuActualiza());
				$datos[$fila]['maestra_est_eliminado'] = $temporal->getEstEliminado();
				$datos[$fila]['maestra_est_causa_eliminacion'] = $temporal->getEstCausaEliminacion();
				$datos[$fila]['maestra_est_causa_actualizacion'] = $temporal->getEstCausaActualizacion();
				
				$fila++;
			}
			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$cantidad_estado.'","results":'.$jsonresult.'})';
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
	 *@date:14 de diciembre de 2010
	 *Esta funcion elimina un Estado
	 */
	public function executeEliminarEstado(sfWebRequest $request)
	{
		$salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n estado'}})";
		try{
			$est_codigo = $this->getRequestParameter('maestra_est_codigo');
			$causa_eliminacion = $this->getRequestParameter('maestra_est_causa_eliminacion');
			
			if($est_codigo!=''){					
				//validar foraneas
				/*
				$conexion = new Criteria();
				$conexion->add(MaquinaPeer::MAQ_EST_CODIGO, $est_codigo);
				$maquina= MaquinaPeer::doCount($conexion);	
				
				if($maquina==0){
					$estado  = EstadoPeer::retrieveByPk($est_codigo);
					if($estado){
						$estado->delete();
						$salida = "({success: true, mensaje:'El estado fue eliminado exitosamente'})";
					}
				}
				else
				{*/
					$estado  = EstadoPeer::retrieveByPk($est_codigo);
					if($estado){
						$estado->setEstUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina
						$estado->setEstFechaActualizacion(time());
						$estado->setEstEliminado(1);
						$estado->setEstCausaEliminacion($causa_eliminacion);
						$estado->save();
						$salida = "({success: true, mensaje:'El estado fue eliminado exitosamente'})";
				//	$salida = "({success: false,  errors: { reason: '<b>NO</b> se pudo eliminar el estado, porque hay m&aacute;quinas que lo estan utilizando'}})";
					}
				//}
			}
			
		}
		catch (Exception $excepcion)
		{
				
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en estado al tratar de eliminar',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
	
	/**
  *@author:maryit sanchez
  *@date:29 de enero de 2010
  *Este metodo permite restablecer un objeto eliminado
  */	  
	public function executeRestablecerEstado()
	{
		$salida = "({success: false, errors: { reason: 'No se pudo restablecer el estado'}})";
		try{
			$est_codigo = $this->getRequestParameter('maestra_est_codigo');
			$causa_reestablece= $this->getRequestParameter('maestra_est_causa_restablece');
			
			
			$estado  = EstadoPeer::retrieveByPk($est_codigo);
				
				if($estado)
				{
					$estado->setEstUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo reestablece
					$estado->setEstFechaActualizacion(time());
					$estado->setEstEliminado(0);
					$estado->setEstCausaActualizacion($causa_reestablece);
					$estado->save();
					$salida = "({success: true, mensaje:'El estado fue restablecido exitosamente'})";
				}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
}
