<?php

/**
 * maestra_evento actions.
 *
 * @package    tpmlabs
 * @subpackage maestra_evento
 * @author     maryit sanchez
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class maestra_eventoActions extends sfActions
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
	 *Esta funcion que crear y actualiza un Evento
	 */
	public function executeActualizarEvento(sfWebRequest $request)
	{
		$salida = '';

		try{
			$eve_codigo = $this->getRequestParameter('maestra_eve_codigo');
			$evento;
				
			if($eve_codigo!=''){
				$evento  = EventoPeer::retrieveByPk($eve_codigo);
			}
			else
			{
				$evento = new Evento();
				$evento->setEveFechaRegistroSistema(time());
				$evento->setEveUsuCrea($this->getUser()->getAttribute('usu_codigo'));
				$evento->setEveEliminado(0);
			}
				
			if($evento)
			{
				$evento->setEveNombre($this->getRequestParameter('maestra_eve_nombre'));
				$evento->setEveFechaActualizacion(time());
				$evento->setEveUsuActualiza($this->getUser()->getAttribute('usu_codigo'));
				$evento->setEveCausaActualizacion($this->getRequestParameter('maestra_eve_causa_actualizacion'));
				
				$evento->save();

				$salida = "({success: true, mensaje:'El evento fue actualizado exitosamente'})";
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en evento',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:14 de diciembre de 2010
	 *Esta funcion retorna  un livento de Eventoes
	 */
	public function executeListarEvento(sfWebRequest $request)
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			$eve_eliminado=$this->getRequestParameter('eve_eliminado');//los de mostrar
			if($eve_eliminado==''){
				$eve_eliminado=0;
			}
			$conexion = new Criteria();
			$conexion->add(EventoPeer::EVE_ELIMINADO,$eve_eliminado);	
			$cantidad_evento = EventoPeer::doCount($conexion);
			$conexion->setOffset($this->getRequestParameter('start'));
			$conexion->setLimit($this->getRequestParameter('limit'));
			$evento = EventoPeer::doSelect($conexion);

			foreach($evento as $temporal)
			{
				$datos[$fila]['maestra_eve_codigo']=$temporal->getEveCodigo();
				$datos[$fila]['maestra_eve_nombre'] = $temporal->getEveNombre();
				
				$datos[$fila]['maestra_eve_fecha_registro_sistema'] = $temporal->getEveFechaRegistroSistema();
				$datos[$fila]['maestra_eve_fecha_actualizacion'] = $temporal->getEveFechaActualizacion();
				//$datos[$fila]['maestra_eve_usu_crea'] = $temporal->getEveUsuCrea();
				//$datos[$fila]['maestra_eve_usu_actualiza'] = $temporal->getEveUsuActualiza();
				
				$datos[$fila]['maestra_eve_usu_crea_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getEveUsuCrea());
				$datos[$fila]['maestra_eve_usu_actualiza_nombre'] = UsuarioPeer::obtenerNombreUsuario($temporal->getEveUsuActualiza());
				
				$datos[$fila]['maestra_eve_eliminado'] = $temporal->getEveEliminado();
				$datos[$fila]['maestra_eve_causa_eliminacion'] = $temporal->getEveCausaEliminacion();
				$datos[$fila]['maestra_eve_causa_actualizacion'] = $temporal->getEveCausaActualizacion();
				$fila++;
			}
			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$cantidad_evento.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en evento al listar ',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}

	/**
	 *@author:maryit sanchez
	 *@date:14 de diciembre de 2010
	 *Esta funcion elimina un Evento
	 */
	public function executeEliminarEvento(sfWebRequest $request)
	{
		$salida = "({success: false,  errors: { reason: 'No ha seleccionado ning&uacute;n evento'}})";
		try{
			$eve_codigo = $this->getRequestParameter('maestra_eve_codigo');
			$causa_eliminacion = $this->getRequestParameter('maestra_eve_causa_eliminacion');
			
			$evento;
				
			if($eve_codigo!=''){					
				
				//validar foraneas
				/*
				$conexion1 = new Criteria();
				$conexion1->add(EventoPorCategoriaPeer::EVCA_EVE_CODIGO, $eve_codigo);
				$evento_por_categoria= EventoPorCategoriaPeer::doCount($conexion1);	
				
				$conexion2 = new Criteria();
				$conexion2->add(EventoEnRegistroPeer::EVRG_EVE_CODIGO, $eve_codigo);
				$evento_en_registro= EventoEnRegistroPeer::doCount($conexion2);	
				
				
				if($evento_por_categoria==0 && $evento_en_registro==0){
					$evento  = EventoPeer::retrieveByPk($eve_codigo);
					if($evento){
						$evento->delete();
						$salida = "({success: true, mensaje:'El evento fue eliminado exitosamente'})";
					}
				}
				else
				{*/
					$evento  = EventoPeer::retrieveByPk($eve_codigo);
					if($evento){
						$evento->setEveUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo elimina
						$evento->setEveFechaActualizacion(time());
						$evento->setEveEliminado(1);
						$evento->setEveCausaEliminacion($causa_eliminacion);
						$evento->save();
						$salida = "({success: true, mensaje:'El evento fue eliminado exitosamente'})";
					//$salida = "({success: false,  errors: { reason: '<b>NO</b> se pudo eliminar el evento, porque esta vinculado a catego&iacute;s o registros de uso de m&aacute;quinas'}})";
					}
				//}
			}
		}
		catch (Exception $excepcion)
		{
				
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en evento al tratar de eliminar',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
	
	
	/**
	 *@author:maryit sanchez
	 *@date:10 de enero de 2010
	 *Esta funcion retorna  un listado de categorias que tiene un evento
	 */
	public function executeListarCategoriasporevento(sfWebRequest $request )
	{
		$eve_codigo = $this->getRequestParameter('maestra_eve_codigo');
		
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			$conexion = new Criteria();
			$conexion->addJoin( EventoPorCategoriaPeer::EVCA_CAT_CODIGO ,CategoriaEventoPeer::CAT_CODIGO );
			$conexion->add(EventoPorCategoriaPeer::EVCA_EVE_CODIGO , $eve_codigo );	
			$conexion->add(CategoriaEventoPeer::CAT_ELIMINADO , 0);	
			$categorias = CategoriaEventoPeer::doSelect($conexion);

			foreach($categorias as $temporal)
			{
				$datos[$fila]['maestra_evca_cat_codigo'] = $temporal->getCatCodigo();
				$datos[$fila]['maestra_evca_cat_nombre'] = $temporal->getCatNombre();
				
				$fila++;
			}
			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n al listar Categor&iacute;as por evento',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
	
	/**
	 *@author:maryit sanchez
	 *@date:10 de enero de 2010
	 *Esta funcion retorna  un listado de categorias 
	 */
	public function executeListarCategorias(sfWebRequest $request )
	{
		$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;

		try{
			$conexion = new Criteria();
			$conexion->add(CategoriaEventoPeer::CAT_ELIMINADO , 0);	
			$categorias = CategoriaEventoPeer::doSelect($conexion);

			foreach($categorias as $temporal)
			{
				$datos[$fila]['cat_codigo'] = $temporal->getCatCodigo();
				$datos[$fila]['cat_nombre'] = $temporal->getCatNombre();
				
				$fila++;
			}
			if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
			}
		}
		catch (Exception $excepcion)
		{
			return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n al listar Categor&iacute;as ',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
	
	//
	
  
	/**
	 *@author:maryit sanchez
	 *@date:10 de enero de 2011
	 *Esta funcion que crear y actualiza un Evento
	 */
	public function executeGuardarEventoPorCategoria(sfWebRequest $request)
	{
		$salida = "({success: true, mensaje:'La asignaci&oacute; de evento a categoria no fue realizada'})";

		try{
			$eve_codigo = $this->getRequestParameter('eve_codigo');
			$cat_codigo = $this->getRequestParameter('cat_codigo');
			
			$eventoporcategoria;
				
			if($eve_codigo!='' && $cat_codigo!=''  ){
			
				$conexion = new Criteria();
				$conexion->add( EventoPorCategoriaPeer::EVCA_CAT_CODIGO ,$cat_codigo );
				$conexion->addAnd(EventoPorCategoriaPeer::EVCA_EVE_CODIGO , $eve_codigo );	
				$eventoporcategoria = EventoPorCategoriaPeer::doSelect($conexion);

				if($eventoporcategoria)
				{
					$salida = "({success: true, mensaje:'El evento por categor&iacute;a ya habia sido asignado'})";
				}
				else
				{
					$eventoporcategoria = new EventoPorCategoria();
					$eventoporcategoria->setEvcaEveCodigo($eve_codigo);
					$eventoporcategoria->setEvcaCatCodigo($cat_codigo);
					$eventoporcategoria->setEvcaFechaRegistroSistema(time());
					$eventoporcategoria->setEvcaUsuCrea($this->getUser()->getAttribute('usu_codigo'));
					$eventoporcategoria->save();

					$salida = "({success: true, mensaje:'La asignaci&oacute;n de evento a categor&iacute;a fue hecha exitosamente'})";
				}
				
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en evento por categor&iacute;a',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}

	
	/**
	 *@author:maryit sanchez
	 *@date:10 de enero de 2011
	 *Esta funcion que elimina una asociacion de Evento - categoria
	 */
	public function executeEliminarEventoPorCategoria(sfWebRequest $request)
	{
		$salida = "({success: true, mensaje:'La asignaci&oacute; de evento a categoria no fue eliminada'})";
		$eventoporcategoria;
		try{
			$eve_codigo = $this->getRequestParameter('eve_codigo');
			$temp=$this->getRequestParameter('cats_codigos');
			$cats_codigos = json_decode($temp);
		
			if($eve_codigo!='' ){
				foreach ($cats_codigos as $cat_codigo)
				{
					$conexion = new Criteria();
					$conexion->add( EventoPorCategoriaPeer::EVCA_CAT_CODIGO ,$cat_codigo );
					$conexion->addAnd(EventoPorCategoriaPeer::EVCA_EVE_CODIGO , $eve_codigo );	
					$eventoporcategoria = EventoPorCategoriaPeer::doSelectOne($conexion);

					if($eventoporcategoria)
					{
						$eventoporcategoria->delete();
						$salida = "({success: true, mensaje:'El evento por categor&iacute;a ha sido eliminado exitosamente'})";
					}
				}
			}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en evento por categor&iacute;a',error:'".$excepcion->getMessage()."'}})";
		}

		return $this->renderText($salida);
	}
	
	/**
  *@author:maryit sanchez
  *@date:29 de enero de 2010
  *Este metodo permite restablecer un objeto eliminado
  */	  
	public function executeRestablecerEvento()
	{
		$salida = "({success: false, errors: { reason: 'No se pudo restablecer el evento'}})";
		try{
			$eve_codigo = $this->getRequestParameter('maestra_eve_codigo');
			$causa_reestablece= $this->getRequestParameter('maestra_eve_causa_restablece');
			
			
			$evento  = EventoPeer::retrieveByPk($eve_codigo);
				
				if($evento)
				{
					$evento->setEveUsuActualiza($this->getUser()->getAttribute('usu_codigo'));//es el usuario que lo reestablece
					$evento->setEveFechaActualizacion(time());
					$evento->setEveEliminado(0);
					$evento->setEveCausaActualizacion($causa_reestablece);
					$evento->save();
					$salida = "({success: true, mensaje:'El evento fue restablecido exitosamente'})";
				}
		}
		catch (Exception $excepcion)
		{
			$salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n',error:'".$excepcion->getMessage()."'}})";
		}
		return $this->renderText($salida);
	}
}
