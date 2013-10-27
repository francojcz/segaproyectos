<?php

class mantenimiento_autonomoActions extends sfActions
{ 

    public function executeIndex(sfWebRequest $request) {}
    
    public function executeListarCategoriasEquipo()
    {
        $result = array();
        $data = array();

        $criteria = new Criteria();
        $criteria -> add(CategoriaEquipoPeer::CAT_ELIMINADO, 0);
        $catsequipo = CategoriaEquipoPeer::doSelect($criteria);

        foreach ($catsequipo as $catequipo)
        {
            $fields = array();
            $fields['codigo'] = $catequipo -> getCatCodigo();
            $fields['nombre'] = $catequipo -> getCatNombre();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }   
  
    public function executeListarEquiposPorCategoria(sfWebRequest $request)
    {        
        $criteria = new Criteria();
        $criteria -> add(MaquinaPeer::MAQ_CAT_CODIGO, $request -> getParameter('codigo_categoria'));
        $criteria -> add(MaquinaPeer::MAQ_ELIMINADO, 0);
        $maquinas = MaquinaPeer::doSelect($criteria);

        $result = array();
        $data = array();

        foreach ($maquinas as $maquina)
        {
            $fields = array();
            $fields['codigo'] = $maquina -> getMaqCodigo();
            $fields['nombre'] = $maquina -> getMaqNombre();
            $fields['codigo_inventario'] = $maquina -> getMaqCodigoInventario();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }
    
    public function executeListarItemsPorEquipo(sfWebRequest $request)
    {
        $criteria = new Criteria();
        $criteria -> add(RepuestoPeer::REP_CAT_CODIGO, $request -> getParameter('codigo_categoria'));
        $criteria -> add(RepuestoPeer::REP_ELIMINADO, 0);        
        $repuestos = RepuestoPeer::doSelect($criteria);
        
        $result = array();
        $data = array();
        
        foreach ($repuestos as $repuesto) {
            $fields = array();
            $fields['item_codigo'] = $repuesto -> getRepCodigo();
            $fields['item_numero'] = $repuesto -> getRepNumero();
            $fields['item_nombre'] = $repuesto -> getRepNombre();
            
            $data[] = $fields;
        }
        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    public function executeModificarRegistroUsoMaquina(sfWebRequest $request)
    {
        if ($request -> hasParameter('id_registro_rep_maquina'))
        {
            $registro = RegistroRepMaquinaPeer::retrieveByPK($request -> getParameter('id_registro_rep_maquina'));

            $user = $this -> getUser();           
            $codigo_usuario = $user -> getAttribute('usu_codigo');
                       
            if ($request -> hasParameter('rrm_consumo'))
            {   
                //Restar a la cantidad de existencias la cantidad consumida
                $criteria = new Criteria();
                $criteria -> add(RepuestoPeer::REP_CODIGO, $registro ->getRrmRepCodigo());
                $repuesto = RepuestoPeer::doSelectOne($criteria);
                
                $existencia = $repuesto->getRepCantidad() - $request->getParameter('rrm_consumo');                
                if($existencia >= 0) {
                    $repuesto -> setRepCantidad($existencia);
                    $repuesto -> save();
                    
                    $registro -> setRrmConsumo($request -> getParameter('rrm_consumo'));
                    $registro -> setRrmUsuActualiza($codigo_usuario);
                    $registro -> setRrmFechaActualizacion(date('Y-m-d H:i:s'));
                }
                else
                    return $this -> renderText('1');
            }
            if ($request -> hasParameter('rrm_fecha_cambio'))
            {
                $registro -> setRrmFechaCambio($request -> getParameter('rrm_fecha_cambio'));
                $registro -> setRrmUsuActualiza($codigo_usuario);
                $registro -> setRrmFechaActualizacion(date('Y-m-d H:i:s'));
                
                //Obtener la periodicidad de cambio del repuesto
                $criteria = new Criteria();
                $criteria -> add(RepuestoPeer::REP_CODIGO, $registro ->getRrmRepCodigo());
                $repuesto = RepuestoPeer::doSelectOne($criteria);
                $periodicidad= $repuesto->getRepPeriodicidad();
                
                //Calcular la fecha de proximo cambio
                $fechacambio = strtotime($request -> getParameter('rrm_fecha_cambio'));
                $ano = (int) date('Y',$fechacambio);
                $mes = (int) date('m',$fechacambio);
                $mes += $periodicidad;
                $dia = (int) date('d',$fechacambio);
                
                if($mes <= 12) {
                    $fechaproxcambio = $ano.'-'.$mes.'-'.$dia;
                } else {
                    while($mes > 12) {
                        $mes -= 12;
                        $ano += 1;
                        $fechaproxcambio = $ano.'-'.$mes.'-'.$dia;
                    }
                }
                if(($mes == 2) && ($dia > 28) && ($dia != 29)) {
                    $fechaproxcambio = $ano.'-03-'.($dia - 28);
                }
                if(($mes == 2) && ($dia == 29 ) && (($ano%4)!=0)) {
                    $fechaproxcambio = $ano.'-03-01';
                }
                if((($mes == 4) || ($mes == 6) || ($mes == 9) || ($mes == 11)) && ($dia > 30)) {
                    $fechaproxcambio = $ano.'-'.($mes + 1).'-01';
                }
                                
                $registro ->setRrmFechaProxCambio($fechaproxcambio);
                $registro -> setRrmUsuActualiza($codigo_usuario);
                $registro -> setRrmFechaActualizacion(date('Y-m-d H:i:s'));
            }
            if ($request -> hasParameter('rrm_observaciones'))
            {
                $registro ->setRrmObservaciones($request -> getParameter('rrm_observaciones'));
                $registro -> setRrmUsuActualiza($codigo_usuario);
                $registro -> setRrmFechaActualizacion(date('Y-m-d H:i:s'));
            }
            
            $registro -> save();
        }
        return $this -> renderText('Ok');
    }

    public function executeListarRegistrosRepuestoMaquina(sfWebRequest $request)
    {
        $criteria = new Criteria();
        if ($request -> hasParameter('codigo_maquina'))
        {
            $criteria -> add(RegistroRepMaquinaPeer::RRM_MAQ_CODIGO, $request -> getParameter('codigo_maquina'));
        }
        $criteria -> add(RegistroRepMaquinaPeer::RRM_FECHA_REGISTRO, $request -> getParameter('fecha_registro'));
        $registros = RegistroRepMaquinaPeer::doSelect($criteria);

        $result = array();
        $data = array();

        foreach ($registros as $registro)
        {
            $fields = array();

            $fields['id_registro_rep_maquina'] = $registro -> getRrmCodigo();
            $fields['rrm_maq_codigo'] = $registro -> getRrmMaqCodigo();
            $fields['rrm_rep_codigo'] = $registro -> getRrmRepCodigo();
            $fields['rrm_fecha_cambio'] = $registro -> getRrmFechaCambio();
            $fields['rrm_fecha_prox_cambio'] = $registro -> getRrmFechaProxCambio();
            $fields['rrm_observaciones'] = $registro -> getRrmObservaciones();
            $fields['rrm_usu_registra'] = UsuarioPeer::obtenerNombreUsuario($registro->getRrmUsuRegistra());
            $fields['rrm_fecha_registro'] = $registro -> getRrmFechaRegistro();
            $fields['rrm_usu_actualiza'] = UsuarioPeer::obtenerNombreUsuario($registro->getRrmUsuActualiza());
            $fields['rrm_fecha_actualizacion'] = $registro -> getRrmFechaActualizacion();
            $fields['rrm_consumo'] = $registro -> getRrmConsumo();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }
    
    public function executeListarRepuestos()
    {
        $conexion = new Criteria();
        $conexion -> add(RepuestoPeer::REP_ELIMINADO, 0);
        $repuestos = RepuestoPeer::doSelect($conexion);
        
        $result = array();
        $data = array();

        foreach ($repuestos as $repuesto)
        {
            $fields = array();
            $fields['codigo'] = $repuesto -> getRepCodigo();
            $fields['nombre'] = $repuesto -> getRepNombre();
            $fields['numero'] = $repuesto -> getRepNumero();
            $fields['cantidad'] = $repuesto -> getRepCantidad();
            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }
    
    public function executeAgregarItemsPorEquipo(sfWebRequest $request)
    {
        $salida = "({success: true, mensaje:'La asignaci&oacute;n del &iacute;tem al equipo no fue posible'})";
        
        try {
            $equipo_codigo = $this->getRequestParameter('equipo_codigo');
            $temp = $this->getRequestParameter('items_codigos');
            $items_codigos = json_decode($temp);
            
            $user = $this -> getUser();           
            $codigo_usuario = $user -> getAttribute('usu_codigo');
            
            if($equipo_codigo!='' ) {
                foreach ($items_codigos as $item_codigo) {
                    $repuesto = new RegistroRepMaquina();
                    $repuesto -> setRrmMaqCodigo($equipo_codigo);
                    $repuesto -> setRrmRepCodigo($item_codigo);
                    $repuesto -> setRrmUsuRegistra($codigo_usuario);
                    $repuesto -> setRrmFechaRegistro(date('Y-m-d H:i:s'));
                    $repuesto -> save();
                    $salida = "({success: true, mensaje:'Registro(s) agregado(s) exitosamente'})";
                }
            }
        }
        catch (Exception $excepcion)
        {
                $salida= "({success: false, errors: { reason: 'Hubo una excepci&oacute;n en Mantenimiento',error:'".$excepcion->getMessage()."'}})";
        }

        return $this->renderText($salida);
    }
    
    public function executeEliminarRegistroRepuestoEquipo(sfWebRequest $request)
    {        
        if ($request -> hasParameter('id_registro_rep_maquina'))
        {            
            $registro = RegistroRepMaquinaPeer::retrieveByPK($request -> getParameter('id_registro_rep_maquina'));
            if($registro->getRrmConsumo()!=''){
                $repuesto = RepuestoPeer::retrieveByPK($registro->getRrmRepCodigo());
                $actual = $repuesto -> getRepCantidad();
                $repuesto -> setRepCantidad($actual + $registro ->getRrmConsumo());
                $repuesto -> save();
                $registro -> delete();
            }
            else {
                $registro -> delete();
            }
        }
        return $this -> renderText('Ok');
    }
}
