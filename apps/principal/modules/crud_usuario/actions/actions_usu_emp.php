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
				
				$usuario->setUsuNombres($this->getRequestParameter('usu_nombres'));
				$usuario->setUsuApellidos($this->getRequestParameter('usu_apellidos'));
				$usuario->setUsuNumeroIdentificacion($this->getRequestParameter('usu_numero_identificacion'));
				$usuario->setUsuTidCodigo($this->getRequestParameter('tid_codigo'));
				$usuario->setUsuEmpCodigo($this->getRequestParameter('emp_codigo'));
				
				  
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
					
					$usuario->setUsuNombres($this->getRequestParameter('usu_nombres'));
					$usuario->setUsuApellidos($this->getRequestParameter('usu_apellidos'));
					$usuario->setUsuNumeroIdentificacion($this->getRequestParameter('usu_numero_identificacion'));
					$usuario->setUsuTidCodigo($this->getRequestParameter('tid_codigo'));
					$usuario->setUsuEmpCodigo($this->getRequestParameter('emp_codigo'));
					
					$usuario->save();
					
					$salida = "({success: true, mensaje:'El usuario fue actualizado exitosamente'})";
			} else {
				$salida = "({success: false, errors: { reason: 'No se ha actualizado el usuario correctamente'}})";
			}
		
			return $salida;
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepcion'}})";
		}
	}
  /**
  *@author:maryit sanchez
  *@date:16 de diciembre de 2010
  *Este metodo permite la eliminacion de usuarios
  */	  
	protected function eliminarUsuario()
	{
		try{
			$ids_usuarios_codificados = $this->getRequestParameter('ids_usuarios');
			$ids_usuarios = json_decode($ids_usuarios_codificados);
			
			foreach ($ids_usuarios as $usu_codigo)
			{
				$conexion = new Criteria();
				$conexion->add(UsuarioPeer::USU_CODIGO, $usu_codigo);
				
				if($conexion)
				{
					$usuariosaeliminar = UsuarioPeer::doSelect($conexion);
					foreach ($usuariosaeliminar as $elemento)
					{
						$elemento->delete();
					}
					$salida = "({success: true, mensaje:'El usuario fue eliminado exitosamente'})";
				}
				else
				{
					$salida = "({success: false, errors: { reason: 'No se pudo eliminar el usuario'}})";
				}		
			}
			return $salida;
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepcion'}})";
		}
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
			$conexion = new Criteria();
			if($this->getRequestParameter('usu_habilitado'))
			{
			$conexion->add(UsuarioPeer::USU_HABILITADO,$this->getRequestParameter('usu_habilitado'));
			}
			$numero_usuarios = UsuarioPeer::doCount($conexion);
			$conexion->setLimit($this->getRequestParameter('limit'));
			$conexion->setOffset($this->getRequestParameter('start'));
			$conexion->addDescendingOrderByColumn('USU_CODIGO');
			$usuarios = UsuarioPeer::doSelect($conexion);
			$pos = 0;
			$datos;
			
			foreach ($usuarios As $temporal)
			{
				$datos[$pos]['usu_codigo']=$temporal->getUsuCodigo();
				$datos[$pos]['usu_per_codigo']=$temporal->getUsuPerCodigo();
				$datos[$pos]['usu_habilitado']=$temporal->getUsuHabilitado();
				$datos[$pos]['usu_login']=$temporal->getUsuLogin();
				
				$datos[$pos]['usu_nombres']=$temporal->getUsuNombres();
				$datos[$pos]['usu_apellidos']=$temporal->getUsuApellidos();
				$datos[$pos]['usu_numero_identificacion']=$temporal->getUsuNumeroIdentificacion();
				$datos[$pos]['usu_tid_codigo']=$temporal->getUsuTidCodigo();
				$datos[$pos]['usu_emp_codigo']=$temporal->getUsuEmpCodigo();
				$datos[$pos]['usu_url_foto']=$temporal->getUsuUrlFoto();
				$datos[$pos]['usu_fecha_registro_sistema']=$temporal->getUsuFechaRegistroSistema();
				
				$datos[$pos]['per_nombre'] = $this->obtenerNombrePerfil($temporal->getUsuPerCodigo());
				
				$pos++;
			}
			
			if($pos>0){
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
  *Este metodo retorna una lista de los diferentes Tipos de identificacion de usuario
  */	  
	public function executeListarTipoidentificacion(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		try{
			$conexion = new Criteria();
			$tipoidentificacion = TipoidentificacionPeer::doSelect($conexion);
			$pos = 0;
			$datos;
			foreach ($tipoidentificacion As $temporal)
			{
				$datos[$pos]['tid_codigo']=''.$temporal->getTidCodigo();
				$datos[$pos]['tid_nombre']=$temporal->getTidNombre();
				$pos++;
			}

			if($pos>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$pos.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			$salida='({"total":"0", "results":"", mensaje:"Hubo una excepci&oacute;n en usuario",error:"'.$excepcion->getMessage().'"})';
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
