<?php

/**
 * crud_metodo actions.
 *
 * @package    tpmlabs
 * @subpackage crud_metodo
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class crud_metodoActions extends sfActions
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
	 *@date:20 de diciembre de 2010
	 *Esta funcion que crear y actualiza un metodo
	 */
	public function executeActualizarMetodo(sfWebRequest $request)
	{

		$salida = '';

		try{
			$met_codigo = $this->getRequestParameter('met_codigo');
			$metodo;

			if($met_codigo!=''){
				$metodo  = MetodoPeer::retrieveByPk($met_codigo);
			}
			else
			{
				$metodo = new metodo();
				$metodo->setMetFechaRegistroSistema(time());
				$metodo->setMetUsuCrea($this->getUser()->getAttribute('usu_codigo'));
				$metodo->setMetEliminado(0);
			}

			if($metodo)
			{
				$metodo->setMetNombre($this->getRequestParameter('met_nombre'));

				$metodo->setMetQuitarColumna($this->getRequestParameter('met_quitar_columna'));
				$metodo->setMetLavadoEquipo($this->getRequestParameter('met_lavado_equipo'));
				$metodo->setMetLavadoSistema($this->getRequestParameter('met_lavado_sistema'));
				$metodo->setMetInstalacionColumna($this->getRequestParameter('met_instalacion_columna'));
				$metodo->setMetEstabilizacionFaseMovil($this->getRequestParameter('met_estabilizacion_fase_movil'));
				$metodo->setMetPurgaSistemaFaseMovil($this->getRequestParameter('met_purga_sistema_fase_movil'));
				$metodo->setMetPurgaSistemaAgua($this->getRequestParameter('met_purga_sistema_agua'));
				$metodo->setMetPurgaSistemaSinAlmacena($this->getRequestParameter('met_purga_sistema_sin_almacena'));
				$metodo->setMetTiempoEstandar($this->getRequestParameter('met_tiempo_estandar'));
				$metodo->setMetTiempoCorridaMuestra($this->getRequestParameter('met_tiempo_corrida_muestra'));
				$metodo->setMetTiempoCorridaSistema($this->getRequestParameter('met_tiempo_corrida_sistema'));
				$metodo->setMetTiempoCorridaCurvas($this->getRequestParameter('met_tiempo_corrida_curvas'));
				$metodo->setMetTiempoCambioModelo($this->getRequestParameter('met_tiempo_cambio_modelo'));
				
				$metodo->setMetNumeroInyeccionEstandar($this->getRequestParameter('met_numero_inyeccion_estandar'));
				$metodo->setMetNumInyeccionEstandar1($this->getRequestParameter('met_num_inyeccion_estandar_1'));
				$metodo->setMetNumInyeccionEstandar2($this->getRequestParameter('met_num_inyeccion_estandar_2'));
				$metodo->setMetNumInyeccionEstandar3($this->getRequestParameter('met_num_inyeccion_estandar_3'));
				$metodo->setMetNumInyeccionEstandar4($this->getRequestParameter('met_num_inyeccion_estandar_4'));
				$metodo->setMetNumInyeccionEstandar5($this->getRequestParameter('met_num_inyeccion_estandar_5'));
				$metodo->setMetNumInyeccionEstandar6($this->getRequestParameter('met_num_inyeccion_estandar_6'));
				$metodo->setMetNumInyeccionEstandar7($this->getRequestParameter('met_num_inyeccion_estandar_7'));
				$metodo->setMetNumInyeccionEstandar8($this->getRequestParameter('met_num_inyeccion_estandar_8'));
				
				$metodo->setMetNumInyecXMuProducto($this->getRequestParameter('met_num_inyec_x_mu_producto'));
				$metodo->setMetNumInyecXMuEstabilidad($this->getRequestParameter('met_num_inyec_x_mu_estabilidad'));
				$metodo->setMetNumInyecXMuMateriaPri($this->getRequestParameter('met_num_inyec_x_mu_materi_pri'));
				$metodo->setMetNumInyecXMuEstandar($this->getRequestParameter('met_num_inyec_x_mu_estandar'));
				$metodo->setMetFechaActualizacion(time());
				$metodo->setMetUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
				

				$metodo->save();

				$salida = "({success: true, mensaje:'El m&eacute;todo fue actualizado exitosamente'})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en m&eacute;todo' ,error: ".$excepcion->getMessage()."'}})";
			echo($excepcion->getMessage());
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:3 de octubre de 2010
	 *Esta funcion un listado de metodo
	 */
	public function executeListarMetodo(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			//$est_codigo=$this->getRequestParameter('est_codigo');
			$start=$this->getRequestParameter('start');
			$limit=$this->getRequestParameter('limit');
				
			$conexion = new Criteria();
			$conexion->add(MetodoPeer::MET_ELIMINADO,0);	
			/*if($est_codigo){
				$conexion->add(MetodoPeer::MET_EST_CODIGO,$est_codigo);
			}*/
				
			$metodos_cantidad = MetodoPeer::doCount($conexion);
				
			if($start!=''){
				$conexion->setOffset($start);
				$conexion->setLimit($limit);
			}
			$metodo = MetodoPeer::doSelect($conexion);

			foreach($metodo as $temporal)
			{
				$datos[$fila]['met_codigo']=$temporal->getMetCodigo();

				$datos[$fila]['met_nombre'] = $temporal->getMetNombre();
				$datos[$fila]['met_quitar_columna'] = $temporal->getMetQuitarColumna();
				$datos[$fila]['met_lavado_equipo'] = $temporal->getMetLavadoEquipo();
				$datos[$fila]['met_lavado_sistema'] = $temporal->getMetLavadoSistema();
				$datos[$fila]['met_instalacion_columna'] = $temporal->getMetInstalacionColumna();
				$datos[$fila]['met_estabilizacion_fase_movil'] = $temporal->getMetEstabilizacionFaseMovil();
				$datos[$fila]['met_purga_sistema_fase_movil'] = $temporal->getMetPurgaSistemaFaseMovil();
				$datos[$fila]['met_purga_sistema_agua'] = $temporal->getMetPurgaSistemaAgua();
				$datos[$fila]['met_purga_sistema_sin_almacena'] = $temporal->getMetPurgaSistemaSinalmacena();
				$datos[$fila]['met_tiempo_estandar'] = $temporal->getMetTiempoEstandar();
				$datos[$fila]['met_tiempo_corrida_muestra'] = $temporal->getMetTiempoCorridaMuestra();
				$datos[$fila]['met_tiempo_corrida_sistema'] = $temporal->getMetTiempoCorridaSistema();
				$datos[$fila]['met_tiempo_corrida_curvas'] = $temporal->getMetTiempoCorridaCurvas();
				$datos[$fila]['met_tiempo_cambio_modelo'] = $temporal->getMetTiempoCambioModelo();

				$datos[$fila]['met_numero_inyeccion_estandar'] = $temporal->getMetNumeroInyeccionEstandar();

				$datos[$fila]['met_num_inyeccion_estandar_1'] = $temporal->getMetNumInyeccionEstandar1();
				$datos[$fila]['met_num_inyeccion_estandar_2'] = $temporal->getMetNumInyeccionEstandar2();
				$datos[$fila]['met_num_inyeccion_estandar_3'] = $temporal->getMetNumInyeccionEstandar3();
				$datos[$fila]['met_num_inyeccion_estandar_4'] = $temporal->getMetNumInyeccionEstandar4();
				$datos[$fila]['met_num_inyeccion_estandar_5'] = $temporal->getMetNumInyeccionEstandar5();
				$datos[$fila]['met_num_inyeccion_estandar_6'] = $temporal->getMetNumInyeccionEstandar6();
				$datos[$fila]['met_num_inyeccion_estandar_7'] = $temporal->getMetNumInyeccionEstandar7();
				$datos[$fila]['met_num_inyeccion_estandar_8'] = $temporal->getMetNumInyeccionEstandar8();

				$datos[$fila]['met_num_inyec_x_mu_producto'] = $temporal->getMetNumInyecXMuProducto();
				$datos[$fila]['met_num_inyec_x_mu_estabilidad'] = $temporal->getMetNumInyecXMuEstabilidad();
				$datos[$fila]['met_num_inyec_x_mu_materi_pri'] = $temporal->getMetNumInyecXMuMateriaPri();
				$datos[$fila]['met_num_inyec_x_mu_estandar'] = $temporal->getMetNumInyecXMuEstandar();

				$datos[$fila]['met_fecha_registro_sistema'] = $temporal->getMetFechaRegistroSistema();
				$datos[$fila]['met_fecha_actualizacion'] = $temporal->getMetFechaActualizacion();
			//	$datos[$fila]['met_usu_crea'] = $temporal->getMetUsuCrea();
			//	$datos[$fila]['met_usu_actualiza'] = $temporal->getMetUsuActualiza();
				$datos[$fila]['met_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getMetUsuCrea());
				$datos[$fila]['met_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getMetUsuActualiza());
				$datos[$fila]['met_eliminado'] = $temporal->getMetEliminado();
				$datos[$fila]['met_causa_eliminacion'] = $temporal->getMetCausaEliminacion();
				
				$fila++;
			}
			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$metodos_cantidad.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return $this->renderText("({success: false, errors: { reason: 'Hubo una excepci&oacute;n en m&eacute;todo ',error:".$excepcion->getMessage()."'}})");
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:3 de octubre de 2010
	 *Esta funcion elimina un metodo
	 */
	public function executeEliminarMetodo(sfWebRequest $request)
	{
		$salida = "({success: false,  errors: { reason: 'No ha seleccionado ningun m&eacute;todo'}})";

		try{
			$met_codigo = $this->getRequestParameter('met_codigo');
			$causa_eliminacion = $this->getRequestParameter('met_causa_eliminacion');
			
			if($met_codigo!=''){
				//validar foraneas
				//verificamos si hay alguna tupla en registro_uso_maquina que lo utilice, si existe no se puede borrar	
				
				$conexion = new Criteria();
				$conexion->add(RegistroUsoMaquinaPeer::RUM_MET_CODIGO, $met_codigo);
				$registros = RegistroUsoMaquinaPeer::doCount($conexion);

				if($registros==0){
					$metodo  = MetodoPeer::retrieveByPk($met_codigo);
					if($metodo){
						$metodo->delete();
						$salida = "({success: true, mensaje:'El m&eacute;todo fue eliminado exitosamente'})";
					}
				}
				else{
					$metodo  = MetodoPeer::retrieveByPk($met_codigo);
					$metodo->setMetUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina
					$metodo->setMetFechaActualizacion(time());
					$metodo->setMetEliminado(1);
					$metodo->setMetCausaEliminacion($causa_eliminacion);
					$metodo->save();
					$salida = "({success: true, mensaje:'El m&eacute;todo fue eliminado exitosamente'})";
					//$salida = "({success: false,  errors: { reason:'<b>NO</b> se pudo eliminar el m&eacute;todo, porque hay registros de uso sobre el m&eacute;todo'}})";
				}
			}
		}
		catch (Exception $excepcion)
		{

			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en m&eacute;todo al tratar de eliminar ',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

}
