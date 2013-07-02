<?php

/**
 * maestra_tipolicencia actions.
 *
 * @package    tpmlabs
 * @subpackage maestra_tipolicencia
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class maestra_tipolicenciaActions extends sfActions
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
	 *Tlia funcion que crear y actualiza un Tipolicencia
	 */
	public function executeActualizarTipolicencia(sfWebRequest $request)
	{
		$salida = '';

		try{
			$tli_codigo = $this->getRequestParameter('maestra_tli_codigo');
			$tipolicencia;
				
			if($tli_codigo!=''){
				$tipolicencia  = TipoLicenciaPeer::retrieveByPk($tli_codigo);
			}
			else
			{
				$tipolicencia = new Tipolicencia();
				$tipolicencia->setTliFechaRegistroSistema(time());
				$tipolicencia->setTliUsuCrea($this->getUser()->getAttribute('usu_codigo'));
				$tipolicencia->setTliEliminado(0);
			}
				
			if($tipolicencia)
			{
				$tipolicencia->setTliNombre($this->getRequestParameter('maestra_tli_nombre'));
				$tipolicencia->setTliFechaActualizacion(time());
				$tipolicencia->setTliUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
				
				$tipolicencia->save();

				$salida = "({success: true, mensaje:'El tipo de licencia fue actualizado exitosamente'})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en tipo de licencia',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:14 de diciembre de 2010
	 *Tlia funcion retorna  un listado de Tipolicenciaes
	 */
	public function executeListarTipolicencia(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			$conexion = new Criteria();
			$conexion->add(TipoLicenciaPeer::TLI_ELIMINADO,0);	
			$cantidad_tipolicencia = TipoLicenciaPeer::doCount($conexion);
			$conexion->setOffset($this->getRequestParameter('start'));
			$conexion->setLimit($this->getRequestParameter('limit'));
			$tipolicencia = TipoLicenciaPeer::doSelect($conexion);

			foreach($tipolicencia as $temporal)
			{
				$datos[$fila]['maestra_tli_codigo']=$temporal->getTliCodigo();
				$datos[$fila]['maestra_tli_nombre'] = $temporal->getTliNombre();
				
				$datos[$fila]['maestra_tli_fecha_registro_sistema'] = $temporal->getTliFechaRegistroSistema();
				$datos[$fila]['maestra_tli_fecha_actualizacion'] = $temporal->getTliFechaActualizacion();
				$datos[$fila]['maestra_tli_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getTliUsuCrea());
				$datos[$fila]['maestra_tli_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getTliUsuActualiza());
				$fila++;
			}
			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$cantidad_tipolicencia.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en tipo de licencia al listar ',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:14 de diciembre de 2010
	 *Tlia funcion elimina un Tipolicencia
	 */
	public function executeEliminarTipolicencia(sfWebRequest $request)
	{
		$salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n tipo de licencia'}})";
		try{
			$tli_codigo = $this->getRequestParameter('maestra_tli_codigo');
			$causa_eliminacion = $this->getRequestParameter('tli_causa_eliminacion');
			
			if($tli_codigo!=''){	
				//validar foraneas
				$conexion = new Criteria();
				$conexion->add(EmpresaPeer::EMP_TLI_CODIGO, $tli_codigo);
				$empresa = EmpresaPeer::doCount($conexion);
				
				if($empresa==0){
					$tipolicencia  = TipoLicenciaPeer::retrieveByPk($tli_codigo);
					if($tipolicencia){
						$tipolicencia->delete();
						$salida = "({success: true, mensaje:'El tipo de licencia fue eliminado exitosamente'})";
					}
				}
				else
				{
					$tipolicencia->setTliUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina
					$tipolicencia->setTliFechaActualizacion(time());
					$tipolicencia->setTliEliminado(1);
					$tipolicencia->setTliCausaEliminacion($causa_eliminacion);
					$tipolicencia->save();
					$salida = "({success: true, mensaje:'El tipo de licencia fue eliminado exitosamente'})";
					//$salida = "({success: false,  errors: { reason: '<b>NO</b> se pudo eliminar el tipo de licencia, porque hay empresas que la estan utilizando'}})";
				}
			}
			
		}
		catch (Exception $excepcion)
		{
				
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en tipo de licencia al tratar de eliminar',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
}
