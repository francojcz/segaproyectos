<?php

/**
 * crud_empleadousuario actions.
 *
 * @package    tpmlabs
 * @subpackage crud_empleadousuario
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class crud_empleadousuarioActions extends sfActions
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
	 *Este metodo permite la creacion de usuarios del sistema
	 */
	public function executeCrearUsuario()
	{
		$salida	='';
		try{

			$emplusu_login = $this->getRequestParameter('emplusu_login');
			$conexion=new Criteria();
			$conexion->add(UsuarioPeer::USU_LOGIN, $emplusu_login);
			$usuario=UsuarioPeer::doSelectOne($conexion);

			if(!$usuario)
			{
				$perfil=$this->getRequestParameter('per_codigo');

				$usuario = new Usuario();
				$usuario->setUsuLogin($emplusu_login);
				$usuario->setUsuPassword(md5($this->getRequestParameter('emplusu_password')));
				$usuario->setUsuHabilitado($this->getRequestParameter('emplusu_habilitado'));
				$usuario->setUsuPerCodigo($perfil);
				$usuario->setUsuFechaRegistroSistema(time());
				$usuario->setUsuCrea($this->getUser()->getAttribute('usu_codigo'));

				if($perfil!='1')//no es un usuario superadmin entonces crear empleado
				{
					$salida ="({success: false, errors: { reason: 'No se ha podido crear el empleado'}})";
					$emplusu_numero_identificacion = $this->getRequestParameter('emplusu_numero_identificacion');

					$conexion2=new Criteria();
					$conexion2->add(EmpleadoPeer::EMPL_NUMERO_IDENTIFICACION, $emplusu_numero_identificacion);
					$empleado=EmpleadoPeer::doSelectOne($conexion2);

					if($empleado){
						$salida = "({success: false, errors: { reason: 'Ya existe un empleado con el mismo número de identificación. Modifiquelo e intente nuevamente.'}})";
					}
					else{
						$empleado = new Empleado();

						$empleado->setEmplNombres($this->getRequestParameter('emplusu_nombres'));
						$empleado->setEmplApellidos($this->getRequestParameter('emplusu_apellidos'));
						$empleado->setEmplNumeroIdentificacion($this->getRequestParameter('emplusu_numero_identificacion'));
						$empleado->setEmplTidCodigo($this->getRequestParameter('tid_codigo'));
						$empleado->setEmplEmpCodigo($this->getRequestParameter('emp_codigo'));
						$empleado->setEmplFechaRegistroSistema(time());
						$empleado->setEmplFechaActualizacion(time());
						$empleado->setEmplUsuCrea($this->getUser()->getAttribute('usu_codigo'));
						$empleado->setEmplUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
						$empleado->setEmplEliminado(0);

						$empleado->setUsuarioRelatedByEmplUsuCodigo($usuario);
							
						$empleado->save();
						$this->guardarFoto($empleado);
						$salida = "({success: true, mensaje:'El usuario fue creado exitosamente'})";
					}
				}

			}
			else {
				$salida = "({success: false, errors: { reason: 'Ya existe una cuenta con el mismo nombre de usuario. Modifiquelo e intente nuevamente.'}})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}


	/**
	 *@author:maryit sanchez
	 *@date:16 de diciembre de 2010
	 *Este metodo permite la actualizacion de usuarios del sistema
	 */
	public function executeActualizarUsuario()
	{
		$salida = '';
		try{
			$emplusu_usu_codigo = $this->getRequestParameter('emplusu_usu_codigo');
			$conexion = new Criteria();
			$conexion->add(UsuarioPeer::USU_CODIGO, $emplusu_usu_codigo);
			$usuario = UsuarioPeer::doSelectOne($conexion);

			$emplusu_codigo = $this->getRequestParameter('emplusu_codigo');
			$conexion2 = new Criteria();
			$conexion2->add(EmpleadoPeer::EMPL_CODIGO, $emplusu_codigo);
			$empleado = EmpleadoPeer::doSelectOne($conexion2);

			if($usuario && $empleado)
			{
				if($this->getRequestParameter('emplusu_password') != ''){
					$usuario->setUsuPassword(md5($this->getRequestParameter('emplusu_password')));
				}
				$usuario->setUsuHabilitado($this->getRequestParameter('emplusu_habilitado'));
				$usuario->setUsuPerCodigo($this->getRequestParameter('per_codigo'));
				$usuario->setUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
				$usuario->setUsuFechaActualizacion(time());
				$usuario->setUsuCausaActualizacion($this->getRequestParameter('emplusu_causa_actualizacion'));
					
				$usuario->save();
					
				$empleado->setEmplNombres($this->getRequestParameter('emplusu_nombres'));
				$empleado->setEmplApellidos($this->getRequestParameter('emplusu_apellidos'));
				$empleado->setEmplNumeroIdentificacion($this->getRequestParameter('emplusu_numero_identificacion'));
				$empleado->setEmplTidCodigo($this->getRequestParameter('tid_codigo'));
				$empleado->setEmplEmpCodigo($this->getRequestParameter('emp_codigo'));
				$empleado->setEmplUsuCodigo($this->getRequestParameter('emplusu_usu_codigo'));
				$empleado->setEmplFechaActualizacion(time());
				$empleado->setEmplUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
				$empleado->setEmplCausaActualizacion($this->getRequestParameter('emplusu_causa_actualizacion'));
					
				$empleado->save();
				$this->guardarFoto($empleado);
					
				$salida = "({success: true, mensaje:'El usuario fue actualizado exitosamente'})";
			} else {
				$salida = "({success: false, errors: { reason: 'No se ha actualizado el usuario correctamente'}})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
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
			$emplusu_usu_codigo = $this->getRequestParameter('emplusu_usu_codigo');
			$causa_eliminacion = $this->getRequestParameter('emplusu_causa_eliminacion');

			//foreach ($ids_usuarios as $emplusu_codigo)
			//{
			/*
				$conexion = new Criteria();
				$conexion->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $emplusu_codigo);
				$registrosusomaquinas = RegistroUsoMaquinaPeer::doCount($conexion);

				$conexion2 = new Criteria();
				$conexion2->add(RegistroModificacionPeer::REM_USU_CODIGO, $emplusu_codigo);
				$registrosmodificacion = RegistroModificacionPeer::doCount($conexion2);

				$conexion3 = new Criteria();
				$conexion3->add(EmpleadoPeer::EMPL_USU_CODIGO, $emplusu_codigo);
				$empleados = EmpleadoPeer::doCount($conexion3);

				if($registrosusomaquinas==0 && $registrosmodificacion==0 && $empleados==0){*/
			$usuario  = UsuarioPeer::retrieveByPk($emplusu_usu_codigo);
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
	public function executeListarUsuarios()
	{
		try{
			$per_codigo=$this->getUser()->getAttribute('usu_per_codigo');
			$emplusu_habilitado = $this->getRequestParameter('emplusu_habilitado');


			$conexion = new Criteria();

			if($emplusu_habilitado=='1')
			{
				$conexion->add(UsuarioPeer::USU_HABILITADO,$emplusu_habilitado);
			}
			if($emplusu_habilitado=='0')
			{
				$conexion->add(UsuarioPeer::USU_HABILITADO,$emplusu_habilitado);
			}

			if($per_codigo != 1){
				//sino no es el super admin debe poder ver solo los usuarios de la empresa , los superdamin tampoco son visibles en este caso
				$conexion->add(UsuarioPeer::USU_PER_CODIGO,1,Criteria::NOT_EQUAL);
			}
			if($per_codigo == 1){
				$conexion->add(UsuarioPeer::USU_PER_CODIGO,2);
					
					
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
				$datos[$fila]['emplusu_usu_codigo']=$temporal->getUsuCodigo();
				$datos[$fila]['emplusu_per_codigo']=$temporal->getUsuPerCodigo();
				$datos[$fila]['emplusu_habilitado']=$temporal->getUsuHabilitado();
				$datos[$fila]['emplusu_login']=$temporal->getUsuLogin();
				$datos[$fila]['emplusu_per_nombre'] = $this->obtenerNombrePerfil($temporal->getUsuPerCodigo());

				$datos[$fila]['emplusu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getUsuCrea());
				$datos[$fila]['emplusu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getUsuActualiza());
				//++$datos[$fila]['emplusu_eliminado'] = $temporal->getUsuEliminado();
				//++$datos[$fila]['emplusu_causa_eliminacion'] = $temporal->getUsuCausaEliminacion();
				$datos[$fila]['emplusu_causa_actualizacion'] = $temporal->getUsuCausaActualizacion();
				$datos[$fila]['emplusu_fecha_registro_sistema'] = $temporal->getUsuFechaRegistroSistema();
				$datos[$fila]['emplusu_fecha_actualizacion'] = $temporal->getUsuFechaActualizacion();


				$conexion2 = new Criteria();
				$conexion2->add(EmpleadoPeer::EMPL_USU_CODIGO,$temporal->getUsuCodigo());
				$empleado = EmpleadoPeer::doSelectOne($conexion2);
					
				if ($empleado)
				{
					$datos[$fila]['emplusu_codigo']=$empleado->getEmplCodigo();

					$datos[$fila]['emplusu_nombres']=$empleado->getEmplNombres();
					$datos[$fila]['emplusu_apellidos']=$empleado->getEmplApellidos();
					$datos[$fila]['emplusu_numero_identificacion']=$empleado->getEmplNumeroIdentificacion();
					$datos[$fila]['emplusu_tid_codigo']=$empleado->getEmplTidCodigo();
					$datos[$fila]['emplusu_emp_codigo']=$empleado->getEmplEmpCodigo();
					$datos[$fila]['emplusu_emplusu_codigo']=$empleado->getEmplUsuCodigo();
					$datos[$fila]['emplusu_url_foto']=$empleado->getEmplUrlFoto();
					$datos[$fila]['emplusu_tid_nombre']= $this->obtenerNombreTipoIndetificacion($empleado->getEmplTidCodigo());

					$datos[$fila]['emplusu_fecha_registro_sistema']=$empleado->getEmplFechaRegistroSistema();
					$datos[$fila]['emplusu_fecha_actualizacion'] = $empleado->getEmplFechaActualizacion();
					$datos[$fila]['emplusu_emplusu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($empleado->getEmplUsuCrea());
					$datos[$fila]['emplusu_emplusu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($empleado->getEmplUsuActualiza());
					$datos[$fila]['emplusu_eliminado'] = $empleado->getEmplEliminado();
					$datos[$fila]['emplusu_causa_eliminacion'] = $empleado->getEmplCausaEliminacion();
					$datos[$fila]['emplusu_causa_actualizacion'] = $empleado->getEmplCausaActualizacion();
				}

				$fila++;
			}

			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$numero_usuarios.'","results":'.$jsonresult.'})';
			}
			else {
				$salida= '({"total":"0", "results":""})';
			}
		}
		catch (Exception $excepcion)
		{
			$salida= '({"total":"0", "results":"", mensaje:"Hubo una excepci&oacute;n",error"'.$excepcion->getMessage().'"})';
		}
		return $this->renderText($salida);
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
				$conexion->add(PerfilPeer::PER_CODIGO,1,Criteria::NOT_EQUAL);//el 1 es del superadmin
			}
			else{
				//$conexion->add(PerfilPeer::PER_CODIGO,4,Criteria::NOT_EQUAL);
				//$conexion->addAnd(PerfilPeer::PER_CODIGO,3,Criteria::NOT_EQUAL);
				$conexion->add(PerfilPeer::PER_CODIGO,2,Criteria::EQUAL); //solo los adminis
			}
			$conexion->add(PerfilPeer::PER_ELIMINADO,0);
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
			$conexion->add(EmpresaPeer::EMP_ELIMINADO,0);
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


	/**
	 *@author:maryit sanchez
	 *@date:22 de diciembre de 2010
	 *Este metodo permite obtener el nombre un tipo de identificaci�n
	 */
	protected function obtenerNombreTipoIndetificacion($tid_codigo)
	{
		$tipoidentificacion = TipoIdentificacionPeer::retrieveByPK($tid_codigo);
		if($tipoidentificacion)
		{
			return $tipoidentificacion->getTidNombre();
		}
		return '';
	}

	/**
	 *@author:maryit sanchez
	 *@date:27 de diciembre de 2010
	 *Esta funcion guarda  la foto de un empleado
	 */
	public function guardarFoto($empleado){
		$salida='';
		try{

			$emplusu_codigo=$empleado->getEmplCodigo();
			$nombre_carpeta = "uploads/empleados/".$emplusu_codigo;

			if(!is_dir($nombre_carpeta))
			{
				mkdir($nombre_carpeta, 0777, true);
			}

			sleep(2);
			$nombre = $_FILES['emplusu_foto']['name'];
			$tamano = $_FILES['emplusu_foto']['size'];
			$tipo = $_FILES['emplusu_foto']['type'];
			$temporal = $_FILES['emplusu_foto']['tmp_name'];

			if($nombre!=''){
				if(file_exists($nombre_carpeta."/".$nombre))
				{
					$salida = "({success: false, errors: { reason: 'Ya existe el archivo con el mismo nombre de en el sistema'}})";
				}
				else
				{
					if($tamano > 2100000)//$tamano > algo 1000000 aprox 1mega
					{
						$salida = "({success: false, errors: { reason: 'El archivo excede el limite de tama&ntilde;o permitido'}})";
					}
					else
					{
						$copio=copy($temporal, $nombre_carpeta."/".$nombre);
						if($copio){
							$empleado->setEmplUrlFoto($nombre_carpeta."/".$nombre);
							$empleado->save();
							$salida='true';
						}
					}
				}
			}
		}catch (Exception $excepcion)
		{
			$salida='exeception en guardar logo de empleado'.$excepcion->getMessage();
			$salida='false';
		}
		return $salida;
	}


	/**
	 *@author:maryit sanchez
	 *@date:16 de diciembre de 2010
	 *Este metodo retorna una lista de los diferentes Tipos de identificacion de empleado
	 */
	public function executeListarTipoidentificacion(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		try{
			$conexion = new Criteria();
			$conexion->add(TipoidentificacionPeer::TID_ELIMINADO,0);
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
			$salida='({"total":"0", "results":"", mensaje:"Hubo una excepci&oacute;n en empleado",error:"'.$excepcion->getMessage().'"})';
		}
		return $this->renderText($salida);
	}

}
