<?php

/**
 * crud_empleado actions.
 *
 * @package    tpmlabs
 * @subpackage crud_empleado
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class crud_empleadoActions extends sfActions
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
  *Este metodo controla la accion a la cual va a llamar
  */	  
   public function executeCargar()
	{
		$task = '';
		$salida	='';
		$task = $this->getRequestParameter('task');

		switch($task){
			case "LISTARUSUARIOS":
				$salida = $this->listarEmpleados();
				break;

			case "LISTARPERFILES":
				$salida = $this->listarPerfil();
				break;

			case "ACTUALIZARUSUARIO":
				$salida = $this->actualizarEmpleado();
				break;

			case "CREARUSUARIO":
				$salida = $this->crearEmpleado();
				break;

			case "ELIMINARUSUARIO":
				$salida = $this->eliminarEmpleado();
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
  *Este metodo permite la creacion de empleados del sistema
  */	  
	protected function crearEmpleado()
	{
		$salida	='';
		try{
			$empl_numero_identificacion = $this->getRequestParameter('empl_numero_identificacion');
			$usu_codigo=$this->getRequestParameter('usu_codigo');

			$conexion=new Criteria();
			$conexion->add(EmpleadoPeer::EMPL_NUMERO_IDENTIFICACION, $empl_numero_identificacion);
			$empleado=EmpleadoPeer::doSelectOne($conexion);
			
			if($empleado){
				$salida = "({success: false, errors: { reason: 'Ya existe un empleado con ese mismo n�mero de identificaci�n,cambielo e intente denuevo'}})";
			}
			else{
				$conexion=new Criteria();
				$conexion->add(EmpleadoPeer::EMPL_USU_CODIGO, $usu_codigo);
				$empleado=EmpleadoPeer::doSelectOne($conexion);
				$salida = "({success: false, errors: { reason: 'Ya existe un empleado con ese mismo login,cambielo e intente denuevo'}})";

				if(!$empleado)
				{
					$empleado = new Empleado();	
				
					$empleado->setEmplNombres($this->getRequestParameter('empl_nombres'));
					$empleado->setEmplApellidos($this->getRequestParameter('empl_apellidos'));
					$empleado->setEmplNumeroIdentificacion($this->getRequestParameter('empl_numero_identificacion'));
					$empleado->setEmplTidCodigo($this->getRequestParameter('tid_codigo'));
					$empleado->setEmplEmpCodigo($this->getRequestParameter('emp_codigo'));//codgio
					$empleado->setEmplUsuCodigo($this->getRequestParameter('usu_codigo'));
				
					$empleado->setEmplFechaRegistroSistema(time());
					$empleado->setEmplFechaActualizacion(time());
					$empleado->setEmplUsuCrea($this->getUser()->getAttribute('usu_codigo'));
					$empleado->setEmplUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
					$empleado->setEmplEliminado(0);
				
					$empleado->save();
					$this->guardarFoto($empleado);
					$salida = "({success: true, mensaje:'El empleado fue creado exitosamente'})";
				} 
			}		
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}	
		return $salida;
	}
	
  /**
  *@author:maryit sanchez
  *@date:16 de diciembre de 2010
  *Este metodo permite la actualizacion de empleados del sistema
  */	  
	protected function actualizarEmpleado()
	{
		try{
			$empl_codigo = $this->getRequestParameter('empl_codigo');
			$conexion = new Criteria();
			$conexion->add(EmpleadoPeer::EMPL_CODIGO, $empl_codigo);
			$empleado = EmpleadoPeer::doSelectOne($conexion);
			$salida = '';

			if($empleado)
			{
					$empleado->setEmplNombres($this->getRequestParameter('empl_nombres'));
					$empleado->setEmplApellidos($this->getRequestParameter('empl_apellidos'));
					$empleado->setEmplNumeroIdentificacion($this->getRequestParameter('empl_numero_identificacion'));
					$empleado->setEmplTidCodigo($this->getRequestParameter('tid_codigo'));
					$empleado->setEmplEmpCodigo($this->getRequestParameter('emp_codigo'));
					$empleado->setEmplUsuCodigo($this->getRequestParameter('usu_codigo'));
					$empleado->setEmplFechaActualizacion(time());
					$empleado->setEmplUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
					$empleado->setEmplCausaActualizacion($this->getRequestParameter('empl_causa_actualizacion'));
					
					$empleado->save();
					$this->guardarFoto($empleado);
					$salida = "({success: true, mensaje:'El empleado fue actualizado exitosamente'})";
			} else {
				$salida = "({success: false, errors: { reason: 'No se ha actualizado el empleado correctamente'}})";
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
  *Este metodo permite la eliminacion de empleados
  */	  
	protected function eliminarEmpleado()
	{
		$salida = "({success: false, errors: { reason: 'No se pudo eliminar el empleado'}})";
		try{
			$ids_empleados_codificados = $this->getRequestParameter('ids_empleados');
			$causa_eliminacion= $this->getRequestParameter('empl_causa_eliminacion');
			
			$ids_empleados = json_decode($ids_empleados_codificados);
			
			foreach ($ids_empleados as $empl_codigo)
			{
				$empleado  = EmpleadoPeer::retrieveByPk($empl_codigo);
				
				if($empleado)
				{
					$empleado->setEmplUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina
					$empleado->setEmplFechaActualizacion(time());
					$empleado->setEmplEliminado(1);
					$empleado->setEmplCausaEliminacion($causa_eliminacion);
					$empleado->save();
					//$empleado->delete();
					$salida = "({success: true, mensaje:'El empleado fue eliminado exitosamente'})";
				}
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
  *Este metodo permite obtener el nombre de un perfil de empleado
  */	  
	protected function obtenerNombrePerfil($per_codigo)
	{
		$perfil = PerfilPeer::retrieveByPK($per_codigo);
		
		return $perfil->getPerNombre(); 
	}
	
	
	/**
  *@author:maryit sanchez
  *@date:22 de diciembre de 2010
  *Este metodo permite obtener el nombre un tipo de identificaci�n
  */	  
	protected function obtenerNombreTipoIndetificacion($tid_codigo)
	{
		$tipoidentificacion = TipoIdentificacionPeer::retrieveByPK($tid_codigo);
		
		return $tipoidentificacion->getTidNombre(); 
	}
	
	/**
  *@author:maryit sanchez
  *@date:16 de diciembre de 2010
  *Este metodo retorna un listado de los empleados del sistema
  */	  
	protected function listarEmpleados()
	{ 
		try{
			$per_codigo=$this->getUser()->getAttribute('usu_per_codigo');
			$empl_eliminado=$this->getRequestParameter('empl_eliminado');//los de mostrar
			if($empl_eliminado==''){
				$empl_eliminado=0;
			}
			
			$conexion = new Criteria();
			$conexion->add(EmpleadoPeer::EMPL_ELIMINADO,$empl_eliminado);
			
			
			if($per_codigo!=1){
			//sino no es el super admin debe poder ver solo los empleados de la empresa 
				$conexion->add(EmpleadoPeer::EMPL_EMP_CODIGO,$this->getUser()->getAttribute('empl_emp_codigo'));
			}
			
			$numero_empleados = EmpleadoPeer::doCount($conexion);
			
			$conexion->setLimit($this->getRequestParameter('limit'));
			$conexion->setOffset($this->getRequestParameter('start'));
			$conexion->addDescendingOrderByColumn('EMPL_CODIGO');
			$empleados = EmpleadoPeer::doSelect($conexion);
			$fila = 0;
			$datos;
			
			foreach ($empleados As $temporal)
			{
				$datos[$fila]['empl_codigo']=$temporal->getEmplCodigo();
				
				$datos[$fila]['empl_nombres']=$temporal->getEmplNombres();
				$datos[$fila]['empl_apellidos']=$temporal->getEmplApellidos();
				$datos[$fila]['empl_numero_identificacion']=$temporal->getEmplNumeroIdentificacion();
				$datos[$fila]['empl_tid_codigo']=$temporal->getEmplTidCodigo();
				$datos[$fila]['empl_emp_codigo']=$temporal->getEmplEmpCodigo();
				$datos[$fila]['empl_usu_codigo']=$temporal->getEmplUsuCodigo();
				$datos[$fila]['empl_url_foto']=$temporal->getEmplUrlFoto();
				$datos[$fila]['empl_tid_nombre']= $this->obtenerNombreTipoIndetificacion($temporal->getEmplTidCodigo());
				
				$datos[$fila]['empl_fecha_registro_sistema']=$temporal->getEmplFechaRegistroSistema();
				$datos[$fila]['empl_fecha_actualizacion'] = $temporal->getEmplFechaActualizacion();
				$datos[$fila]['empl_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getEmplUsuCrea());
				$datos[$fila]['empl_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getEmplUsuActualiza());
				$datos[$fila]['empl_eliminado'] = $temporal->getEmplEliminado();
				$datos[$fila]['empl_causa_eliminacion'] = $temporal->getEmplCausaEliminacion();
				$datos[$fila]['empl_causa_actualizacion'] = $temporal->getEmplCausaActualizacion();
				
				$fila++;
			}
			
			if($fila>0){
				$jsonresult = json_encode($datos);
				return '({"total":"'.$numero_empleados.'","results":'.$jsonresult.'})';
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
  *Este metodo retorna una lista de los diferentes Tipos de identificacion de empleado
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
			$salida='({"total":"0", "results":"", mensaje:"Hubo una excepci&oacute;n en empleado",error:"'.$excepcion->getMessage().'"})';
		}
		return $this->renderText($salida);
	}
	
	  /**
  *@author:maryit sanchez
  *@date:16 de diciembre de 2010
  *Este metodo retorna una lista de los diferentes Empresas de empleado
  */	  
	public function executeListarEmpresa(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		try{
			$conexion = new Criteria();
			$per_codigo=$this->getUser()->getAttribute('usu_per_codigo');
			if($per_codigo!=1){
				$emp_codigo=$this->getUser()->getAttribute('empl_emp_codigo');
				$conexion->add(EmpresaPeer::EMP_CODIGO,$emp_codigo ,Criteria::EQUAL);
			}
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
			$salida= '({"total":"0", "results":"", mensaje:"Hubo una excepci&oacute;n en empleado",error:"'.$excepcion->getMessage().'"})';
		}
		return $this->renderText($salida);
	}
	
	
	  /**
  *@author:maryit sanchez
  *@date:22 de diciembre de 2010
  *Este metodo retorna una lista de los diferentes logins de usuarios
  */	  
	public function executeListarLogin(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		try{
			$conexion = new Criteria();
			$usuario = UsuarioPeer::doSelect($conexion);
			$pos = 0;
			$datos;
			foreach ($usuario As $temporal)
			{
				$datos[$pos]['usu_codigo']=''.$temporal->getUsuCodigo();
				$datos[$pos]['usu_login']=$temporal->getUsuLogin();
				$pos++;
			}

			if($pos>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$pos.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			$salida= '({"total":"0", "results":"", mensaje:"Hubo una excepci&oacute;n en listar logins",error:"'.$excepcion->getMessage().'"})';
		}
		return $this->renderText($salida);
	}
	/**
	 *@author:maryit sanchez
	 *@date:27 de diciembre de 2010
	 *Esta funcion guarda  la foto de un empleado
	 */
	public function guardarFoto($empleado){
		$salida='';
		try{
				
			$empl_codigo=$empleado->getEmplCodigo();
			$nombre_carpeta = "uploads/empleados/".$empl_codigo;
		
			if(!is_dir($nombre_carpeta))
			{
				mkdir($nombre_carpeta, 7777, true);
			}
		
			sleep(2);
			$nombre = $_FILES['empl_foto']['name'];
			$tamano = $_FILES['empl_foto']['size'];
			$tipo = $_FILES['empl_foto']['type'];
			$temporal = $_FILES['empl_foto']['tmp_name'];
			
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
  *@date:29 de enero de 2010
  *Este metodo permite restablecer un objeto eliminado
  */	  
	public function executeRestablecerEmpleado()
	{
		$salida = "({success: false, errors: { reason: 'No se pudo eliminar el empleado'}})";
		try{
			$empl_codigo = $this->getRequestParameter('empl_codigo');
			$causa_reestablece= $this->getRequestParameter('empl_causa_restablece');
			
			
			$empleado  = EmpleadoPeer::retrieveByPk($empl_codigo);
				
				if($empleado)
				{
					$empleado->setEmplUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo reestablece
					$empleado->setEmplFechaActualizacion(time());
					$empleado->setEmplEliminado(0);
					$empleado->setEmplCausaActualizacion($causa_reestablece);
					$empleado->save();
					$salida = "({success: true, mensaje:'El empleado fue restablecido exitosamente'})";
				}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}

}
