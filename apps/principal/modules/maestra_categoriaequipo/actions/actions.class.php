<?php

class maestra_categoriaequipoActions extends sfActions
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
	 *@date:5 de enero de 2010
	 *Evea funcion que crear y actualiza un CategoriaEquipo
	 */
	public function executeActualizarCategoriaEquipo(sfWebRequest $request)
	{
		$salida = '';

		try{
			$cat_codigo = $this->getRequestParameter('maestra_cat_codigo');
			$categoria;

			if($cat_codigo!=''){
				$categoria  = CategoriaEquipoPeer::retrieveByPk($cat_codigo);
			}
			else
			{
				$categoria = new CategoriaEquipo();
				$categoria->setCatFechaRegistroSistema(time());
				$categoria->setCatUsuCrea($this->getUser()->getAttribute('usu_codigo'));
				$categoria->setCatEliminado(0);
			}

			if($categoria)
			{
				$categoria->setCatNombre($this->getRequestParameter('maestra_cat_nombre'));
				$categoria->setCatFechaActualizacion(time());
				$categoria->setCatUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
				$categoria->setCatCausaActualizacion($this->getRequestParameter('maestra_cat_causa_actualizacion'));
				$categoria->save();

				$salida = "({success: true, mensaje:'La categor&iacute;a fue actualizada exitosamente'})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en categor&iacute;a equipo',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:5 de enero de 2010
	 *Evea funcion retorna  un livento de CategoriaEquipoes
	 */
	public function executeListarCategoriaEquipo(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			$cat_eliminado=$this->getRequestParameter('cat_eliminado');//los de mostrar
			if($cat_eliminado==''){
				$cat_eliminado=0;
			}
			$conexion = new Criteria();
			$conexion->add(CategoriaEquipoPeer::CAT_ELIMINADO,$cat_eliminado);
			$cantidad_equipo = CategoriaEquipoPeer::doCount($conexion);
			$conexion->setOffset($this->getRequestParameter('start'));
			$conexion->setLimit($this->getRequestParameter('limit'));
			$categoria = CategoriaEquipoPeer::doSelect($conexion);

			foreach($categoria as $temporal)
			{
				$datos[$fila]['maestra_cat_codigo']=$temporal->getCatCodigo();
				$datos[$fila]['maestra_cat_nombre'] = $temporal->getCatNombre();

				$datos[$fila]['maestra_cat_fecha_registro_sistema'] = $temporal->getCatFechaRegistroSistema();
				$datos[$fila]['maestra_cat_fecha_actualizacion'] = $temporal->getCatFechaActualizacion();
				$datos[$fila]['maestra_cat_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getCatUsuCrea());
				$datos[$fila]['maestra_cat_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getCatUsuActualiza());
				$datos[$fila]['maestra_cat_eliminado'] = $temporal->getCatEliminado();
				$datos[$fila]['maestra_cat_causa_eliminacion'] = $temporal->getCatCausaEliminacion();
				$datos[$fila]['maestra_cat_causa_actualizacion'] = $temporal->getCatCausaActualizacion();

				$fila++;
			}
			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$cantidad_equipo.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en equipo al listar categor&iacute;as',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:5 de enero de 2010
	 *Evea funcion elimina un Categoria Equipo
	 */
	public function executeEliminarCategoriaEquipo(sfWebRequest $request)
	{
		$salida = "({success: false,  errors: { reason: 'No ha seleccionado ninguna categor&iacute;a de equipos'}})";

		try{
			$cat_codigo = $this->getRequestParameter('maestra_cat_codigo');
			$causa_eliminacion = $this->getRequestParameter('maestra_cat_causa_eliminacion');
				
			if($cat_codigo!=''){
				//validar foraneas
				/*
				 $conexion = new Criteria();
				 $conexion->add(EquipoPorCategoriaPeer::EVCA_CAT_CODIGO, $cat_codigo);
				 $equipo_por_categoria= EquipoPorCategoriaPeer::doCount($conexion);

				 if($equipo_por_categoria==0){
				 	
					$categoria  = CategoriaEquipoPeer::retrieveByPk($cat_codigo);
					if($categoria){
					$categoria->delete();
					$salida = "({success: true, mensaje:'La categor&iacute;a equipo fue eliminada exitosamente'})";
					}
					}
					else
					{*/
				$categoria  = CategoriaEquipoPeer::retrieveByPk($cat_codigo);
				if($categoria){
					$categoria->setCatUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina
					$categoria->setCatFechaActualizacion(time());
					$categoria->setCatEliminado(1);
					$categoria->setCatCausaEliminacion($causa_eliminacion);
					$categoria->save();
					$salida = "({success: true, mensaje:'La categor&iacute;a equipo fue eliminada exitosamente'})";
					//$salida = "({success: false,  errors: { reason: '<b>NO</b> se pudo eliminar la categor&iacute;a, porque hay equipos asociados a esta categor&iacute;a'}})";
				}
				//}
			}
		}
		catch (Exception $excepcion)
		{

			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en categor&iacute;a de equipo al tratar de eliminar',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}


	/**
	 *@author:maryit sanchez
	 *@date:29 de enero de 2010
	 *Este metodo permite restablecer un objeto eliminado
	 */
	public function executeRestablecerCategoriaEquipo()
	{
		$salida = "({success: false, errors: { reason: 'No se pudo restablecer el categor&iacute;a'}})";
		try{
			$cat_codigo = $this->getRequestParameter('maestra_cat_codigo');
			$causa_reestablece= $this->getRequestParameter('maestra_cat_causa_restablece');
				
				
			$categoria  = CategoriaEquipoPeer::retrieveByPk($cat_codigo);

			if($categoria)
			{
				$categoria->setCatUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo reestablece
				$categoria->setCatFechaActualizacion(time());
				$categoria->setCatEliminado(0);
				$categoria->setCatCausaActualizacion($causa_reestablece);
				$categoria->save();
				$salida = "({success: true, mensaje:'La categor&iacute;a fue restablecida exitosamente'})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
}
