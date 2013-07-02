<?php

/**
 * crud_empresa actions.
 *
 * @package    tpmlabs
 * @subpackage crud_empresa
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class crud_empresaActions extends sfActions
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
	 *@date:21 de diciembre de 2010
	 *Esta funcion que crear y actualiza un empresa
	 */
	public function executeActualizarEmpresa(sfWebRequest $request)
	{
		$salida = "({success: true, mensaje:'No se ha creado la empresa, usted <b>no</b> esta autorizado para realizar esto'})";

		try{
			$emp_codigo = $this->getRequestParameter('emp_codigo');
			$empresa;
			$per_codigo=$this->getUser()->getAttribute('usu_per_codigo');
			
			if($emp_codigo!=''){
				$empresa  = EmpresaPeer::retrieveByPk($emp_codigo);
			}
			else
			{				
				if($per_codigo==1){
				$empresa = new empresa();
				$empresa->setEmpFechaRegistroSistema(time());
				$empresa->setEmpUsuCrea($this->getUser()->getAttribute('usu_codigo'));
				$empresa->setEmpEliminado(0);
			
				}
			}
				
			if($empresa)
			{
				$empresa->setEmpNombre($this->getRequestParameter('emp_nombre'));
				$empresa->setEmpNit($this->getRequestParameter('emp_nit'));
				//--$empresa->setEmpTliCodigo($this->getRequestParameter('tli_codigo'));
				if($per_codigo==1){//solo el super admin puede cambiar las fechas de la licencia
					$empresa->setEmpFechaInicioLicencia($this->getRequestParameter('emp_fecha_inicio_licencia'));
					$empresa->setEmpFechaLimiteLicencia($this->getRequestParameter('emp_fecha_limite_licencia'));
				}
				//--$empresa->setEmpTiempoAlertaConsumible($this->getRequestParameter('emp_tiempo_alerta_consumible'));
				$empresa->setEmpInyectEstandarPromedio(6);//--$this->getRequestParameter('emp_inyect_estandar_promedio')
				$empresa->setEmpFechaActualizacion(time());
				$empresa->setEmpUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
				$empresa->setEmpCausaActualizacion($this->getRequestParameter('emp_causa_actualizacion'));
				
				$empresa->save();
				$this->guardarLogo($request,$empresa);
				$salida = "({success: true, mensaje:'La empresa fue actualizada exitosamente'})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en empresa',error:'".$excepcion."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:3 de octubre de 2010
	 *Esta funcion retorna un listado de empresas
	 */
	public function executeListarEmpresa(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			//--$tli_codigo=$this->getRequestParameter('tli_codigo');
			$start=$this->getRequestParameter('start');
			$limit=$this->getRequestParameter('limit');
			$per_codigo=$this->getUser()->getAttribute('usu_per_codigo');
			$emp_eliminado=$this->getRequestParameter('emp_eliminado');//los de mostrar
			if($emp_eliminado==''){
				$emp_eliminado=0;
			}
			
			$conexion = new Criteria();
			$conexion->add(EmpresaPeer::EMP_ELIMINADO,$emp_eliminado);
			
			if($per_codigo!=1){
				$emp_codigo=$this->getUser()->getAttribute('empl_emp_codigo');
				$conexion->add(EmpresaPeer::EMP_CODIGO,$emp_codigo ,Criteria::EQUAL);
			}
			
			/*if($tli_codigo){
				$conexion->add(EmpresaPeer::EMP_TLI_CODIGO,$tli_codigo);
			}*/
			
			$empresas_cantidad = EmpresaPeer::doCount($conexion);
			
			if($start!=''){
				$conexion->setOffset($start);
				$conexion->setLimit($limit);
			}
			$empresa = EmpresaPeer::doSelect($conexion);

			foreach($empresa as $temporal)
			{
				$datos[$fila]['emp_codigo']=$temporal->getEmpCodigo();
				$datos[$fila]['emp_nombre'] = $temporal->getEmpNombre();
				$datos[$fila]['emp_nit'] = $temporal->getEmpNit();
				
				$datos[$fila]['emp_logo_url'] = $temporal->getEmpLogoUrl();
				
				//--$datos[$fila]['emp_tli_codigo'] = $temporal->getEmpTliCodigo();
				
				$datos[$fila]['emp_fecha_inicio_licencia'] = $temporal->getEmpFechaInicioLicencia();
				$datos[$fila]['emp_fecha_limite_licencia'] = $temporal->getEmpFechaLimiteLicencia();
				//--$datos[$fila]['emp_inyect_estandar_promedio'] = $temporal->getEmpInyectEstandarPromedio();
				//--$datos[$fila]['emp_tiempo_alerta_consumible'] = $temporal->getEmpTiempoAlertaConsumible();
				$datos[$fila]['emp_fecha_registro_sistema'] = $temporal->getEmpFechaRegistroSistema();
				
				$datos[$fila]['emp_fecha_actualizacion'] = $temporal->getEmpFechaActualizacion();
				$datos[$fila]['emp_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getEmpUsuCrea());
				$datos[$fila]['emp_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getEmpUsuActualiza());
				$datos[$fila]['emp_eliminado'] = $temporal->getEmpEliminado();
				$datos[$fila]['emp_causa_eliminacion'] = $temporal->getEmpCausaEliminacion();
				$datos[$fila]['emp_causa_actualizacion'] = $temporal->getEmpCausaActualizacion();
				
				$fila++;
			}
			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$empresas_cantidad.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en empresa ',error:".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:3 de octubre de 2010
	 *Esta funcion elimina un empresa
	 */
	public function executeEliminarEmpresa(sfWebRequest $request)
	{
		$salida = "({success: false,  errors: { reason: 'No ha seleccionado ninguna empresa'}})";

		try{
			$emp_codigo = $this->getRequestParameter('emp_codigo');
			$causa_eliminacion= $this->getRequestParameter('emp_causa_eliminacion');
			
			if($emp_codigo!=''){
					
				$conexion = new Criteria();
				$conexion->add(EmpleadoPeer::EMPL_EMP_CODIGO, $emp_codigo);
				$empleados = EmpleadoPeer::doCount($conexion);
				
				if($empleados==0){
				
					$empresa  = EmpresaPeer::retrieveByPk($emp_codigo);
					if($empresa){
						$empresa->delete();
						$salida = "({success: true, mensaje:'La empresa fue eliminada exitosamente'})";
					}
				}
				else
				{
					$empresa  = EmpresaPeer::retrieveByPk($emp_codigo);
					$empresa->setEmpUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina
					$empresa->setEmpFechaActualizacion(time());
					$empresa->setEmpEliminado(1);
					$empresa->setEmpCausaEliminacion($causa_eliminacion);
					$empresa->save();
					$salida = "({success: true, mensaje:'La empresa fue eliminada exitosamente'})";
					//$salida = "({success: false,  errors: { reason: '<b>NO</b> se pudo eliminar la empresa, porque tiene empleados asociados'}})";
				}
			}
				
		}
		catch (Exception $excepcion)
		{
				
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en empresa al tratar de eliminar ',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:21 de diciembre de 2010
	 *Esta funcion lista los  TipoLicencias del empresa
	 */
	 /*
	public function executeListarTipoLicencia(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{

			$conexion = new Criteria();
			$per_codigo=$this->getUser()->getAttribute('usu_per_codigo');
			if($per_codigo!=1){
				$tli_codigo=$this->getUser()->getAttribute('emp_tli_codigo');
				$conexion->add(TipoLicenciaPeer::TLI_CODIGO,$tli_codigo ,Criteria::EQUAL);
			}
			$TipoLicencias = TipoLicenciaPeer::doSelect($conexion);
				
			foreach($TipoLicencias As $temporal)
			{
				$datos[$fila]['tli_codigo'] = $temporal->getTliCodigo();
				$datos[$fila]['tli_nombre'] = $temporal->getTliNombre();
				$fila++;
			}

			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
			}
		}catch (Exception $excepcion)
		{
			$salida='exception en listar TipoLicencias';
		}
		return $this->renderText($salida);
	}*/
	
	/**
	 *@author:maryit sanchez
	 *@date:27 de diciembre de 2010
	 *Esta funcion guarda el logo de una empresa
	 */
	public function guardarLogo(sfWebRequest $request,$empresa){
		$salida='';
		try{
				
			$emp_codigo=$empresa->getEmpCodigo();
			$nombre_carpeta = "uploads/".$emp_codigo;
		
			if(!is_dir($nombre_carpeta))
			{
				mkdir($nombre_carpeta, 7777, true);
			}
		
			sleep(2);
			$nombre = $_FILES['emp_logo']['name'];
			$tamano = $_FILES['emp_logo']['size'];
			$tipo = $_FILES['emp_logo']['type'];
			$temporal = $_FILES['emp_logo']['tmp_name'];
			
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
						$empresa->setEmpLogoUrl($nombre_carpeta."/".$nombre);
						$empresa->save();
						$salida='true';
						}
					}
				}
			}
		}catch (Exception $excepcion)
		{
			$salida='exeception en guardar logo de empresa'.$excepcion->getMessage();
			$salida='false';
		}
		return $salida;
	}
	
	
	/**
  *@author:maryit sanchez
  *@date:29 de enero de 2010
  *Este metodo permite restablecer un objeto eliminado
  */	  
	public function executeRestablecerEmpresa()
	{
		$salida = "({success: false, errors: { reason: 'No se pudo eliminar la empresa'}})";
		try{
			$emp_codigo = $this->getRequestParameter('emp_codigo');
			$causa_reestablece= $this->getRequestParameter('emp_causa_restablece');
			
			
			$empresa  = EmpresaPeer::retrieveByPk($emp_codigo);
				
				if($empresa)
				{
					$empresa->setEmpUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo reestablece
					$empresa->setEmpFechaActualizacion(time());
					$empresa->setEmpEliminado(0);
					$empresa->setEmpCausaActualizacion($causa_reestablece);
					$empresa->save();
					$salida = "({success: true, mensaje:'La empresa fue reestablecida exitosamente'})";
				}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
}
