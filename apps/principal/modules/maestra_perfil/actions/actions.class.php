<?php

/**
 * maestra_perfil actions.
 *
 * @package    tpmlabs
 * @subpackage maestra_perfil
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class maestra_perfilActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  //  $this->forward('default', 'module');
  }
  
  	/**
	 *@author:maryit sanchez
	 *@date:14 de diciembre de 2010
	 *Pera funcion que crear y actualiza un Perfil
	 */
	public function executeActualizarPerfil(sfWebRequest $request)
	{
		$salida = '';

		try{
			$per_codigo = $this->getRequestParameter('maestra_per_codigo');
			$perfil;
				
			if($per_codigo!=''){
				$perfil  = PerfilPeer::retrieveByPk($per_codigo);
			}
			else
			{
				$perfil = new Perfil();
				$perfil->setPerFechaRegistroSistema(time());
				$perfil->setPerUsuCrea($this->getUser()->getAttribute('usu_codigo'));
				$perfil->setPerEliminado(0);
			}
				
			if($perfil)
			{
				$perfil->setPerNombre($this->getRequestParameter('maestra_per_nombre'));
				$perfil->setPerFechaActualizacion(time());
				$perfil->setPerUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
				$perfil->setPerCausaActualizacion($this->getRequestParameter('maestra_per_causa_actualizacion'));
				$perfil->save();

				$salida = "({success: true, mensaje:'El perfil fue actualizado exitosamente'})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en perfil',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:14 de diciembre de 2010
	 *Pera funcion retorna  un listado de Perfiles
	 */
	public function executeListarPerfil(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			$per_eliminado=$this->getRequestParameter('per_eliminado');//los de mostrar
			if($per_eliminado==''){
				$per_eliminado=0;
			}
			
			$conexion = new Criteria();
			$conexion->add(PerfilPeer::PER_ELIMINADO,$per_eliminado);	
			$cantidad_perfil = PerfilPeer::doCount($conexion);
			$conexion->setOffset($this->getRequestParameter('start'));
			$conexion->setLimit($this->getRequestParameter('limit'));
			$perfil = PerfilPeer::doSelect($conexion);

			foreach($perfil as $temporal)
			{
				$datos[$fila]['maestra_per_codigo']=$temporal->getPerCodigo();
				$datos[$fila]['maestra_per_nombre'] = $temporal->getPerNombre();
				
				$datos[$fila]['maestra_per_fecha_registro_sistema'] = $temporal->getPerFechaRegistroSistema();
				$datos[$fila]['maestra_per_fecha_actualizacion'] = $temporal->getPerFechaActualizacion();
				$datos[$fila]['maestra_per_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getPerUsuCrea());
				$datos[$fila]['maestra_per_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getPerUsuActualiza());
				$datos[$fila]['maestra_per_causa_actualizacion'] = $temporal->getPerCausaActualizacion();
			
				$fila++;
			}
			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$cantidad_perfil.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en perfil al listar ',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:14 de diciembre de 2010
	 *Pera funcion elimina un Perfil
	 */
	public function executeEliminarPerfil(sfWebRequest $request)
	{
		$salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n perfil'}})";
		try{
			$per_codigo = $this->getRequestParameter('maestra_per_codigo');
			$causa_eliminacion = $this->getRequestParameter('maestra_per_causa_eliminacion');
			$perfil;
				
			if($per_codigo!=''){	
				//validar foraneas
				/*
				$conexion = new Criteria();
				$conexion->add(UsuarioPeer::USU_PER_CODIGO, $per_codigo);
				$usuario= UsuarioPeer::doCount($conexion);	
				
				if($usuario==0){
					$perfil  = PerfilPeer::retrieveByPk($per_codigo);
					if($perfil){
						$perfil->delete();
						$salida = "({success: true, mensaje:'El perfil fue eliminado exitosamente'})";
					}
				}
				else
				{*/
					//$perfil->setPerUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina pero este no tiene
				$perfil  = PerfilPeer::retrieveByPk($per_codigo);
				if($perfil){
					$perfil->setPerFechaActualizacion(time());
					$perfil->setPerEliminado(1);
					$perfil->setPerCausaEliminacion($causa_eliminacion);
					$perfil->save();
					$salida = "({success: true, mensaje:'El perfil fue eliminado exitosamente'})";
					//$salida = "({success: false,  errors: { reason: '<b>NO</b> se pudo eliminar el perfil, porque hay usuarios que lo estan utilizando'}})";
				}
			}
		}
		catch (Exception $excepcion)
		{
				
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en perfil al tratar de eliminar',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
	
	/**
  *@author:maryit sanchez
  *@date:29 de enero de 2010
  *Este metodo permite restablecer un objeto eliminado
  */	  
	public function executeRestablecerPerfil()
	{
		$salida = "({success: false, errors: { reason: 'No se pudo restablecer el perfil'}})";
		try{
			$per_codigo = $this->getRequestParameter('maestra_per_codigo');
			$causa_reestablece= $this->getRequestParameter('maestra_per_causa_restablece');
			
			
			$perfil  = PerfilPeer::retrieveByPk($per_codigo);
				
				if($perfil)
				{
					$perfil->setPerUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo reestablece
					$perfil->setPerFechaActualizacion(time());
					$perfil->setPerEliminado(0);
					$perfil->setPerCausaActualizacion($causa_reestablece);
					$perfil->save();
					$salida = "({success: true, mensaje:'El perfil fue restablecido exitosamente'})";
				}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
}
