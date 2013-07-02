<?php


/**
 * Skeleton subclass for performing query and update operations on the 'empleado' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * 12/20/10 21:56:13
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    lib.model
 */
class EmpleadoPeer extends BaseEmpleadoPeer {



	
	/**
	 *@author:maryit sanchez
	 *@date:6 de enero de 2011
	 *Esta funcion retorna  un listado de los analaistas
	 */
	public static function listarAnalistas()
	{
		$fila=0;
		$datos;
		try{
			$conexion = new Criteria();
			$conexion->addJoin(EmpleadoPeer::EMPL_USU_CODIGO,UsuarioPeer::USU_CODIGO);
			$conexion->add(UsuarioPeer::USU_PER_CODIGO,3);//el perfil 3 es el de analistas
			$conexion->addDescendingOrderByColumn(EmpleadoPeer::EMPL_NOMBRES);
			$empleados = EmpleadoPeer::doSelect($conexion);
			
			foreach($empleados as $temporal)
			{
				$datos[$fila]['empl_usu_codigo'] = $temporal->getEmplUsuCodigo();
				$datos[$fila]['empl_nombre_completo'] = $temporal->getEmplNombres().' '.$temporal->getEmplApellidos();		
				$fila++;
			}
			$datos[$fila]['empl_usu_codigo']='';
			$datos[$fila]['empl_nombre_completo'] ='TODOS';
		}
		catch (Exception $excepcion)
		{
			//return "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en listar analistas ',error:'".$excepcion->getMessage()."'}})";
		}
		return $datos;
	}
	
	
} // EmpleadoPeer
