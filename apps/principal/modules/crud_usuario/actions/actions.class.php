<?php

/**
 * crud_usuario actions.
 *
 * @package    tpmlabs
 * @subpackage crud_usuario
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class crud_usuarioActions extends sfActions
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
  *Este metodo controla la accion a la cual va a llamar
  */	  
   public function executeCargar()
	{
		$task = '';
		$salida	='';
		$task = $this->getRequestParameter('task');

		switch($task){
			case "LISTARUSUARIOS":
				$salida = $this->listarUsuarios();
				break;

			case "LISTARPERFILES":
				$salida = $this->listarPerfil();
				break;

			case "ACTUALIZARUSUARIO":
				$salida = $this->actualizarUsuario();
				break;

			case "CREARUSUARIO":
				$salida = $this->crearUsuario();
				break;

			case "ELIMINARUSUARIO":
				$salida = $this->eliminarUsuario();
				break;
			default:
				$salida =  "({failure:true})";
				break;
		}	
		return $this->renderText($salida);
	}

  /**
  *@author:maryit sanchez
  *@date:14 de diciembre de 2010
  *Este metodo permite la creacion de usuarios del sistema
  */	  
	protected function crearUsuario()
	{
		try{
			$usu_login = $this->getRequestParameter('usu_login');
			
			$conexion=new Criteria();
			$conexion->add(UsuarioPeer::USU_LOGIN, $usu_login);
			$usuario=UsuarioPeer::doSelectOne($conexion);
			$salida	='';
			if(!$usuario)
			{
				$usuario = new Usuario();			  
				$usuario->setUsuLogin($this->getRequestParameter('usu_login'));
				$usuario->setUsuPassword(md5($this->getRequestParameter('usu_password')));
				$usuario->setUsuHabilitado($this->getRequestParameter('usu_habilitado'));
				$usuario->setUsuPerCodigo($this->getRequestParameter('per_codigo'));
				$usuario->setUsuFechaRegistroSistema(time());
				$usuario->setUsuCrea($this->getUser()->getAttribute('usu_codigo'));
				
				$usuario->save();
				$salida = "({success: true, mensaje:'El usuario fue creado exitosamente'})";
			} 
			else {
			  $salida = "({success: false, errors: { reason: 'Ya existe una persona con ese mismo usuario,cambielo e intente denuevo'}})";
			}
			return $salida;
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}	
	}
	
  /**
  *@author:maryit sanchez
  *@date:16 de diciembre de 2010
  *Este metodo permite la actualizacion de usuarios del sistema
  */	  
	protected function actualizarUsuario()
	{
		try{
			$usu_codigo = $this->getRequestParameter('usu_codigo');
			$conexion = new Criteria();
			$conexion->add(UsuarioPeer::USU_CODIGO, $usu_codigo);
			$usuario = UsuarioPeer::doSelectOne($conexion);
			$salida = '';

			if($usuario)
			{
					if($this->getRequestParameter('usu_password') != ''){
						$usuario->setUsuPassword(md5($this->getRequestParameter('usu_password')));
					}
					$usuario->setUsuHabilitado($this->getRequestParameter('usu_habilitado'));
					$usuario->setUsuPerCodigo($this->getRequestParameter('per_codigo'));		
					$usuario->setUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
					$usuario->setUsuFechaActualizacion(time());
					$usuario->setUsuCausaActualizacion($this->getRequestParameter('usu_causa_actualizacion'));
					
					$usuario->save();
					
					$salida = "({success: true, mensaje:'El usuario fue actualizado exitosamente'})";
			} else {
				$salida = "({success: false, errors: { reason: 'No se ha actualizado el usuario correctamente'}})";
			}
		
			return $salida;
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
	}
  /**
  *@author:maryit sanchez
  *@date:16 de diciembre de 2010
  *Este metodo permite la eliminacion de usuarios
  */	  
	public function executeEliminarUsuario()
	{
		$salida = "({success: false, errors: { reason: 'No se pudo eliminar el usuario'}})";
		
		try{
			//$ids_usuarios_codificados = $this->getRequestParameter('ids_usuarios');
			//$ids_usuarios = json_decode($ids_usuarios_codificados);
			$usu_codigo = $this->getRequestParameter('usu_codigo');
			$causa_eliminacion = $this->getRequestParameter('usu_causa_eliminacion');
			
			//foreach ($ids_usuarios as $usu_codigo)
			//{
			/*
				$conexion = new Criteria();
				$conexion->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $usu_codigo);
				$registrosusomaquinas = RegistroUsoMaquinaPeer::doCount($conexion);
				
				$conexion2 = new Criteria();
				$conexion2->add(RegistroModificacionPeer::REM_USU_CODIGO, $usu_codigo);
				$registrosmodificacion = RegistroModificacionPeer::doCount($conexion2);
				
				$conexion3 = new Criteria();
				$conexion3->add(EmpleadoPeer::EMPL_USU_CODIGO, $usu_codigo);
				$empleados = EmpleadoPeer::doCount($conexion3);
				
				if($registrosusomaquinas==0 && $registrosmodificacion==0 && $empleados==0){*/
				$usuario  = UsuarioPeer::retrieveByPk($usu_codigo);
				if($usuario)
				{
					$usuario->setUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina
					$usuario->setUsuFechaActualizacion(time());
				//++	$usuario->setUsuEliminado(1);
				//++	$usuario->setUsuCausaEliminacion($causa_eliminacion);
					$usuario->save();
					//$usuario->delete();
					$salida = "({success: true, mensaje:'El usuario fue eliminado exitosamente'})";
				}
				/*}
				else
				{
					$salida = "({success: false, errors: { reason: '<b>NO</b> se pudo eliminar el usuario, porque tiene informaci&oacute;n ligada a el'}})";
				}		
			}*/
			$salida;
		}
		catch (Exception $excepcion)
		{
			$salida = "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}

	/**
  *@author:maryit sanchez
  *@date:16 de diciembre de 2010
  *Este metodo permite obtener el nombre de un perfil de usuario
  */	  
	protected function obtenerNombrePerfil($per_codigo)
	{
		$perfil = PerfilPeer::retrieveByPK($per_codigo);
		
		return $perfil->getPerNombre(); 
	}
	
	/**
  *@author:maryit sanchez
  *@date:16 de diciembre de 2010
  *Este metodo retorna un listado de los usuarios del sistema
  */	  
	protected function listarUsuarios()
	{ 
		try{
			$per_codigo=$this->getUser()->getAttribute('usu_per_codigo');
			$usu_habilitado = $this->getRequestParameter('usu_habilitado');
			
			
			$conexion = new Criteria();
			
			if($usu_habilitado=='1')
			{
				$conexion->add(UsuarioPeer::USU_HABILITADO,$usu_habilitado);
			}
			if($usu_habilitado=='0')
			{
				$conexion->add(UsuarioPeer::USU_HABILITADO,$usu_habilitado);
			}
			
			if($per_codigo != 1){
			//sino no es el super admin debe poder ver solo los usuarios de la empresa , los superdamin tampoco son visibles en este caso
				$conexion->add(UsuarioPeer::USU_PER_CODIGO,1,Criteria::NOT_EQUAL);
			}
			if($per_codigo == 1){
			$conexion->add(UsuarioPeer::USU_PER_CODIGO,1,Criteria::EQUAL);
			
			}
			
			$numero_usuarios = UsuarioPeer::doCount($conexion);
			$conexion->setLimit($this->getRequestParameter('limit'));
			$conexion->setOffset($this->getRequestParameter('start'));
			$conexion->addDescendingOrderByColumn('USU_CODIGO');
			$usuarios = UsuarioPeer::doSelect($conexion);
			$fila = 0;
			$datos;
			
			foreach ($usuarios As $temporal)
			{
				$datos[$fila]['usu_codigo']=$temporal->getUsuCodigo();
				$datos[$fila]['usu_per_codigo']=$temporal->getUsuPerCodigo();
				$datos[$fila]['usu_habilitado']=$temporal->getUsuHabilitado();
				$datos[$fila]['usu_login']=$temporal->getUsuLogin();
				$datos[$fila]['usu_per_nombre'] = $this->obtenerNombrePerfil($temporal->getUsuPerCodigo());
				
				$datos[$fila]['usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getUsuCrea());
				$datos[$fila]['usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getUsuActualiza());
				//++$datos[$fila]['usu_eliminado'] = $temporal->getUsuEliminado();
				//++$datos[$fila]['usu_causa_eliminacion'] = $temporal->getUsuCausaEliminacion();
				$datos[$fila]['usu_causa_actualizacion'] = $temporal->getUsuCausaActualizacion();
				$datos[$fila]['usu_fecha_registro_sistema'] = $temporal->getUsuFechaRegistroSistema();
				$datos[$fila]['usu_fecha_actualizacion'] = $temporal->getUsuFechaActualizacion();
				
				$fila++;
			}
			
			if($fila>0){
				$jsonresult = json_encode($datos);
				return '({"total":"'.$numero_usuarios.'","results":'.$jsonresult.'})';
			}
			else {
				return '({"total":"0", "results":""})';
			}
		}
		catch (Exception $excepcion)
		{
			return '({"total":"0", "results":"", mensaje:"Hubo una excepci&oacute;n",error"'.$excepcion->getMessage().'"})';
		}
	}

  /**
  *@author:maryit sanchez
  *@date:16 de diciembre de 2010
  *Este metodo retorna una lista de los diferentes perfiles de usuario
  */	  
	public function executeListarPerfil(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		try{
			$conexion = new Criteria();
			$per_codigo=$this->getUser()->getAttribute('usu_per_codigo');
			if($per_codigo!=1){
			$conexion->add(PerfilPeer::PER_CODIGO,1,Criteria::NOT_EQUAL);//el 0 es del superadmin
			}
			else{
			$conexion->add(PerfilPeer::PER_CODIGO,1,Criteria::EQUAL);
			}
			$perfil = PerfilPeer::doSelect($conexion);
			$pos = 0;
			$datos;
			foreach ($perfil As $temporal)
			{
				$datos[$pos]['per_codigo']=''.$temporal->getPerCodigo();
				$datos[$pos]['per_nombre']=$temporal->getPerNombre();
				$pos++;
			}

			if($pos>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$pos.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			$salida= '({"total":"0", "results":"", mensaje:"Hubo una excepci&oacute;n en usuario",error:"'.$excepcion->getMessage().'"})';
		}
		return $this->renderText($salida);
	}
	
	
	  /**
  *@author:maryit sanchez
  *@date:16 de diciembre de 2010
  *Este metodo retorna una lista de los diferentes Empresas de usuario
  */	  
	public function executeListarEmpresa(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		try{
			$conexion = new Criteria();
			$Empresa = EmpresaPeer::doSelect($conexion);
			$pos = 0;
			$datos;
			foreach ($Empresa As $temporal)
			{
				$datos[$pos]['emp_codigo']=''.$temporal->getEmpCodigo();
				$datos[$pos]['emp_nombre']=$temporal->getEmpNombre();
				$pos++;
			}

			if($pos>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$pos.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			$salida= '({"total":"0", "results":"", mensaje:"Hubo una excepci&oacute;n en usuario",error:"'.$excepcion->getMessage().'"})';
		}
		return $this->renderText($salida);
	}
}
