<?php


/**
 * Skeleton subclass for performing query and update operations on the 'maquina' table.
 *
 *
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * 12/13/10 23:16:12
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class MaquinaPeer extends BaseMaquinaPeer {

	public static function listarEquiposActivos()
	{
		//$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;
		try{
			$conexion = new Criteria();
			$conexion->add(MaquinaPeer::MAQ_ELIMINADO, false);
			$maquinas = MaquinaPeer::doSelect($conexion);

			foreach($maquinas as $temporal)
			{
				$datos[$fila]['maq_codigo'] = $temporal->getMaqCodigo();
				$datos[$fila]['maq_nombre'] = $temporal->getMaqNombre();//.'-'.$temporal->getMaqCodigoInventario();
				$fila++;
			}
			$datos[$fila]['maq_codigo']='';
			$datos[$fila]['maq_nombre'] ='TODOS';
			$fila++;
			/*
			 if($fila>0){
			 $jsonresult = json_encode($datos);
			 $salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
			 }*/
		}
		catch (Exception $excepcion)
		{
			//  return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en listar maquinas ',error:'".$excepcion->getMessage()."'}})";
		}
		return $datos;
	}

	/**
	 *@author:maryit sanchez
	 *@date:6 de enero de 2011
	 *Esta funcion retorna  un listado de los maquinas en buen estado y una opcion de todas
	 */
	public static function listarMaquinasBuenas()
	{
		//$salida='({"total":"0", "results":""})';
		$fila=0;
		$datos;
		try{
			$conexion = new Criteria();
			$conexion->add(MaquinaPeer::MAQ_EST_CODIGO,1);//bueno
			$conexion->addDescendingOrderByColumn(MaquinaPeer::MAQ_NOMBRE);
			$maquinas = MaquinaPeer::doSelect($conexion);

			foreach($maquinas as $temporal)
			{
				$datos[$fila]['maq_codigo'] = $temporal->getMaqCodigo();
				$datos[$fila]['maq_nombre'] = $temporal->getMaqNombre();//.'-'.$temporal->getMaqCodigoInventario();
				$fila++;
			}
			$datos[$fila]['maq_codigo']='';
			$datos[$fila]['maq_nombre'] ='TODOS';
			$fila++;
			/*
			 if($fila>0){
				$jsonresult = json_encode($datos);
				$salida= '({"total":"'.$fila.'","results":'.$jsonresult.'})';
				}*/
		}
		catch (Exception $excepcion)
		{
			//	return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en listar maquinas ',error:'".$excepcion->getMessage()."'}})";
		}
		return $datos;
	}


} // MaquinaPeer
