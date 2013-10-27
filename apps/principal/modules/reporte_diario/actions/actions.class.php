<?php

/**
 * reporte_diario actions.
 *
 * @package    tpmlabs
 * @subpackage reporte_diario
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reporte_diarioActions extends sfActions
{
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
    }

    /**
     @autor:maryit sanchez
     @date:3 de fecbrero de 2011
     devuelve uns conexion dados unos parametros*/
    private function obtenerConexion()
    {
        $conexion = new Criteria();
        $fecha_dia = $this -> getRequestParameter('fecha');
        $maquina_codigo = $this -> getRequestParameter('codigo_maquina');
        $metodo_codigo = $this -> getRequestParameter('metodo_codigo');
        $analista_codigo = $this -> getRequestParameter('codigo_usu_operario');

        if ($fecha_dia != '-1' && $fecha_dia != '')
        {
            $conexion -> add(RegistroUsoMaquinaPeer::RUM_FECHA, $fecha_dia, CRITERIA::EQUAL);
        }

        if ($maquina_codigo != '-1' && $maquina_codigo != '')
        {
            $conexion -> add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $maquina_codigo, CRITERIA::EQUAL);
        }
        if ($metodo_codigo != '-1' && $metodo_codigo != '')
        {
            $conexion -> add(RegistroUsoMaquinaPeer::RUM_MET_CODIGO, $metodo_codigo, CRITERIA::EQUAL);
        }
        if ($analista_codigo != '-1' && $analista_codigo != '')
        {
            $conexion -> add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $analista_codigo, CRITERIA::EQUAL);
        }
        $conexion -> add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
        $conexion -> addDescendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_FECHA);

        return $conexion;
    }

    /**
     @autor:maryit sanchez
     @date:3 de fecbrero de 2011
     lista los tiempos por metodo*/
    public function executeListarReporteTiemposMetodo(sfWebRequest $request)
    {
        $user = $this -> getUser();
        $codigo_usuario = $user -> getAttribute('usu_codigo');
        $criteria = new Criteria();
        $criteria -> add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
        $operario = EmpleadoPeer::doSelectOne($criteria);
        $criteria = new Criteria();
        $criteria -> add(EmpresaPeer::EMP_CODIGO, $operario -> getEmplEmpCodigo());
        $empresa = EmpresaPeer::doSelectOne($criteria);

        $inyeccionesEstandarPromedio = $empresa -> getEmpInyectEstandarPromedio();

        $salida = '({"total":"0", "results":""})';
        $datos;

        //sacar los criterios de consulta
        $fecha_dia = $this -> getRequestParameter('fecha');
        $maquina_codigo = $this -> getRequestParameter('codigo_maquina');
        // $metodo_codigo = $this -> getRequestParameter('metodo_codigo');
        $metodo_codigo = '-1';
        $analista_codigo = $this -> getRequestParameter('codigo_usu_operario');

        $horasFin = 0;
        $minutosFin = 0;
        $segundosFin = 0;
        $fila = 0;

        $criteria = new Criteria();
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_FECHA, $fecha_dia);
        if ($maquina_codigo != '-1' && $maquina_codigo != '')
        {
            $criteria -> add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $maquina_codigo);
        }
        if ($metodo_codigo != '-1' && $metodo_codigo != '')
        {
            $criteria -> add(RegistroUsoMaquinaPeer::RUM_MET_CODIGO, $metodo_codigo);
        }
        if ($analista_codigo != '-1' && $analista_codigo != '')
        {
            $criteria -> add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $analista_codigo);
        }
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, FALSE);
        $criteria -> addAscendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_TIEMPO_ENTRE_MODELO);
        $registros = RegistroUsoMaquinaPeer::doSelect($criteria);
        foreach ($registros as $registro)
        {
            // $registro = new RegistroUsoMaquina();

            $maq_tiempo_inyeccion = $registro -> obtenerTiempoInyeccionMaquina();
            $tp = $registro -> obtenerTPMetodo($maq_tiempo_inyeccion);
            $tf = $registro -> obtenerTFMetodo();
            $to = $registro -> obtenerTOMetodo($maq_tiempo_inyeccion);
            $fecha_dia = $registro -> getRumFecha();

            $datos[$fila]['rdtiemp_maquina'] = $registro -> obtenerMaquina();
            $datos[$fila]['rdtiemp_analista'] = $registro -> obtenerAnalista();
            $datos[$fila]['rdtiemp_metodo'] = $registro -> obtenerMetodo();
            $datos[$fila]['rdtiemp_fecha'] = $registro -> getRumFecha();

            $datos[$fila]['rdtiemp_TP_metodo'] = round($tp, 2);
            $datos[$fila]['rdtiemp_TNP_metodo'] = number_format(round($registro -> calcularTiempoEntreMetodosHoras($horasFin, $minutosFin, $segundosFin), 2), 2, '.', '');

            $datos[$fila]['rdtiemp_TPP_metodo'] = round($registro -> calcularTPPHoras(), 2);
            $datos[$fila]['rdtiemp_TPNP_metodo'] = round($registro -> calcularTPNPMinutos(8) / 60, 2);
            $datos[$fila]['rdtiemp_TF_metodo'] = round($tf, 2);
            $datos[$fila]['rdtiemp_TO_metodo'] = round($to, 2);
            $datos[$fila]['observaciones'] = $registro -> getRumObservaciones();

            $horasFin = $registro -> getRumHoraFinTrabajo('H');
            $minutosFin = $registro -> getRumHoraFinTrabajo('i');
            $segundosFin = $registro -> getRumHoraFinTrabajo('s');

            $fila++;
        }

        $params = array();
        if ($analista_codigo != '-1' && $analista_codigo != '')
        {
            $params['codigo_operario'] = $analista_codigo;
        }
        if ($metodo_codigo != '-1' && $metodo_codigo != '')
        {
            $params['codigo_metodo'] = $metodo_codigo;
        }

        $dateTime = date_create($fecha_dia);
        $dia = $dateTime -> format('d');
        $mes = $dateTime -> format('m');
        $año = $dateTime -> format('Y');

        $tpnp_dia = null;
        $tnp_dia = null;
        $tpp_dia = null;
        $tf_dia = null;
        $to_dia = null;
        $tp_dia = null;

        if ($maquina_codigo != '-1' && $maquina_codigo != '')
        {
            $maquina = MaquinaPeer::retrieveByPK($maquina_codigo);

            $tpnp_dia = RegistroUsoMaquinaPeer::calcularTPNPDiaEnHoras($maquina_codigo, $dia, $mes, $año, $params, 8);
            $tnp_dia = RegistroUsoMaquinaPeer::calcularTNPDiaEnHoras($maquina_codigo, $dia, $mes, $año, $params, 8);
            $tpp_dia = RegistroUsoMaquinaPeer::calcularTPPDiaEnHoras($maquina_codigo, $dia, $mes, $año, $params, 8);
            $tf_dia = $maquina -> calcularNumeroHorasActivasDelDia($dia, $mes, $año) - $tpp_dia - $tnp_dia;
            $to_dia = RegistroUsoMaquinaPeer::calcularTODiaMesAño($tf_dia, $tpnp_dia);
            $tp_dia = RegistroUsoMaquinaPeer::calcularTPDiaEnHoras($maquina_codigo, $dia, $mes, $año, $params, 8);
        } else
        {
            $criteria = new Criteria();
            $criteria -> add(MaquinaPeer::MAQ_INDICADORES, true);
            $maquinas = MaquinaPeer::doSelect($criteria);
            $tpnp_dia = 0;
            $tnp_dia = 0;
            $tpp_dia = 0;
            $tf_dia = 0;
            $to_dia = 0;
            $tp_dia = 0;
            foreach ($maquinas as $maquina)
            {
                //				                    $maquina = new Maquina();

                $codigoTemporalMaquina = $maquina -> getMaqCodigo();

                $tpnp_dia += RegistroUsoMaquinaPeer::calcularTPNPDiaEnHoras($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
                $tnp_dia += RegistroUsoMaquinaPeer::calcularTNPDiaEnHoras($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
                $tpp_dia += RegistroUsoMaquinaPeer::calcularTPPDiaEnHoras($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
                $tf_dia += $maquina -> calcularNumeroHorasActivasDelDia($dia, $mes, $año);
                $tp_dia += RegistroUsoMaquinaPeer::calcularTPDiaEnHoras($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
            }
            $tf_dia = $tf_dia - $tpp_dia - $tnp_dia;
            $to_dia = RegistroUsoMaquinaPeer::calcularTODiaMesAño($tf_dia, $tpnp_dia);
        }

        $datos[0]['rdtiemp_TP_dia'] = round($tp_dia, 2);
        $datos[0]['rdtiemp_TPP_dia'] = round($tpp_dia, 2);
        $datos[0]['rdtiemp_TNP_dia'] = round($tnp_dia, 2);
        $datos[0]['rdtiemp_TPNP_dia'] = round($tpnp_dia, 2);
        $datos[0]['rdtiemp_TF_dia'] = round($tf_dia, 2);
        $datos[0]['rdtiemp_TO_dia'] = round($to_dia, 2);
        //		}

        //		if($fila>0){
        $jsonresult = json_encode($datos);
        $salida = '({"total":"' . $fila . '","results":' . $jsonresult . '})';
        //		}

        return $this -> renderText($salida);
    }

    /**
     @autor:maryit sanchez
     @date:3 de fecbrero de 2011
     lista los indicadores por metodo*/
    public function executeListarReporteIndicadoresMetodo(sfWebRequest $request)
    {

        $salida = '({"total":"0", "results":""})';
        $fila = 0;
        $datos;

        $conexion = $this -> obtenerConexion();
        $registros_uso_maquinas = RegistroUsoMaquinaPeer::doSelect($conexion);

        foreach ($registros_uso_maquinas as $temporal)
        {
            //												$temporal = new RegistroUsoMaquina();

            $maq_tiempo_inyeccion = $temporal -> obtenerTiempoInyeccionMaquina();
            $tp = $temporal -> obtenerTPMetodo($maq_tiempo_inyeccion);
            $tf = $temporal -> obtenerTOMetodo($maq_tiempo_inyeccion);
            $to = $temporal -> obtenerTPMetodo($maq_tiempo_inyeccion);

            $datos[$fila]['rdindic_maquina'] = $temporal -> obtenerMaquina();
            $datos[$fila]['rdindic_analista'] = $temporal -> obtenerAnalista();
            $datos[$fila]['rdindic_metodo'] = $temporal -> obtenerMetodo();
            $datos[$fila]['rdindic_fecha'] = $temporal -> getRumFecha();

            $d = $temporal -> calcularDisponibilidad(8);
            $e = $temporal -> calcularEficiencia(8);
            $c = $temporal -> calcularCalidad(8);
            $oee = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($d, $e, $c);

            $datos[$fila]['rdindic_D_metodo'] = round($d, 2);
            $datos[$fila]['rdindic_E_metodo'] = round($e, 2);
            $datos[$fila]['rdindic_C_metodo'] = round($c, 2);
            $datos[$fila]['rdindic_OEE_metodo'] = round($oee, 2);
            $fila++;
        }

        //		if($fila>0){
        $conexion_dia = $this -> obtenerConexion();

        $fecha_dia = $this -> getRequestParameter('fecha');
        $maquina_codigo = $this -> getRequestParameter('codigo_maquina');
        $metodo_codigo = $this -> getRequestParameter('metodo_codigo');
        $analista_codigo = $this -> getRequestParameter('codigo_usu_operario');

        $params = array();
        if ($analista_codigo != '-1' && $analista_codigo != '')
        {
            $params['codigo_operario'] = $analista_codigo;
        }
        if ($metodo_codigo != '-1' && $metodo_codigo != '')
        {
            $params['codigo_metodo'] = $metodo_codigo;
        }

        $dateTime = date_create($fecha_dia);
        $dia = $dateTime -> format('d');
        $mes = $dateTime -> format('m');
        $año = $dateTime -> format('Y');

        $tpnp_dia = 0;
        $tnp_dia = 0;
        $tpp_dia = 0;
        $tf_dia = 0;
        $to_dia = 0;
        $tp_dia = 0;
        $numeroInyeccionesDia = 0;
        $numeroReinyeccionesDia = 0;

        $cantidadMaquinas = null;

        if ($maquina_codigo != '-1' && $maquina_codigo != '')
        {
            $maquina = MaquinaPeer::retrieveByPK($maquina_codigo);

            $tpnp_dia = RegistroUsoMaquinaPeer::calcularTPNPDiaEnHoras($maquina_codigo, $dia, $mes, $año, $params, 8);
            $tnp_dia = RegistroUsoMaquinaPeer::calcularTNPDiaEnHoras($maquina_codigo, $dia, $mes, $año, $params, 8);
            $tpp_dia = RegistroUsoMaquinaPeer::calcularTPPDiaEnHoras($maquina_codigo, $dia, $mes, $año, $params, 8);
            $tf_dia = $maquina -> calcularNumeroHorasActivasDelDia($dia, $mes, $año) - $tpp_dia - $tnp_dia;
            $to_dia = RegistroUsoMaquinaPeer::calcularTODiaMesAño($tf_dia, $tpnp_dia);
            $tp_dia = RegistroUsoMaquinaPeer::calcularTPDiaEnHoras($maquina_codigo, $dia, $mes, $año, $params, 8);
            $numeroInyeccionesDia = RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasDia($maquina_codigo, $dia, $mes, $año, $params, 8);
            $numeroReinyeccionesDia = RegistroUsoMaquinaPeer::contarReinyeccionesDia($maquina_codigo, $dia, $mes, $año, $params, 8);
            $cantidadMaquinas = 1;
        } else
        {
            $criteria = new Criteria();
            $criteria -> add(MaquinaPeer::MAQ_INDICADORES, true);
            $maquinas = MaquinaPeer::doSelect($criteria);
            $tpp_dia = 0;
            $tf_dia = 0;
            $tp_dia = 0;
            foreach ($maquinas as $maquina)
            {
                //                    $maquina = new Maquina();

                $codigoTemporalMaquina = $maquina -> getMaqCodigo();

                $tpnp_dia += RegistroUsoMaquinaPeer::calcularTPNPDiaEnHoras($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
                $tnp_dia += RegistroUsoMaquinaPeer::calcularTNPDiaEnHoras($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
                $tpp_dia += RegistroUsoMaquinaPeer::calcularTPPDiaEnHoras($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
                $tf_dia += $maquina -> calcularNumeroHorasActivasDelDia($dia, $mes, $año);
                $tp_dia += RegistroUsoMaquinaPeer::calcularTPDiaEnHoras($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
                $numeroInyeccionesDia += RegistroUsoMaquinaPeer::contarInyeccionesObligatoriasDia($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
                $numeroReinyeccionesDia += RegistroUsoMaquinaPeer::contarReinyeccionesDia($codigoTemporalMaquina, $dia, $mes, $año, $params, 8);
            }
            $tf_dia = $tf_dia - $tpp_dia - $tnp_dia;
            $to_dia = RegistroUsoMaquinaPeer::calcularTODiaMesAño($tf_dia, $tpnp_dia);
            $cantidadMaquinas = count($maquinas);
        }

        $cantidadHoras = $cantidadMaquinas * 24;

        // Calculo de indicadores
        $d_dia = RegistroUsoMaquinaPeer::calcularDisponibilidad($to_dia, $tf_dia);
        $e_dia = RegistroUsoMaquinaPeer::calcularEficiencia($tp_dia, $to_dia);
        $c_dia = RegistroUsoMaquinaPeer::calcularCalidad($numeroInyeccionesDia, $numeroReinyeccionesDia);
        $oee_dia = RegistroUsoMaquinaPeer::calcularEfectividadGlobalEquipo($d_dia, $e_dia, $c_dia);
        $ae_dia = RegistroUsoMaquinaPeer::calcularAprovechamiento($tf_dia, $cantidadHoras);
        $ptee_dia = RegistroUsoMaquinaPeer::calcularProductividadTotalEfectiva($ae_dia, $oee_dia);

        //como el consolidado del dia es lo mismo para todas las tuplas, entonces consulto una sola vez y asigno en la primera
        for ($cont = 0; $cont < 1; $cont++)
        {
            $datos[$cont]['rdindic_D_dia'] = round($d_dia, 2);
            $datos[$cont]['rdindic_E_dia'] = round($e_dia, 2);
            $datos[$cont]['rdindic_C_dia'] = round($c_dia, 2);
            $datos[$cont]['rdindic_OEE_dia'] = round($oee_dia, 2);
            $datos[$cont]['rdindic_AE_dia'] = round($ae_dia, 2);
            $datos[$cont]['rdindic_PTEE_dia'] = round($ptee_dia, 2);
        }
        //		}

        //		if($fila>0){
        $jsonresult = json_encode($datos);
        $salida = '({"total":"' . $fila . '","results":' . $jsonresult . '})';
        //		}

        return $this -> renderText($salida);
    }

    public function executeListarReporteInyeccionMuestraPorMetodo(sfWebRequest $request)
    {
        $user = $this -> getUser();
        $codigo_usuario = $request -> getParameter('codigo_usu_operario');
        $codigo_maquina = $request -> getParameter('codigo_maquina');

        $criteria = new Criteria();
        if ($codigo_maquina != '-1' && $codigo_maquina != '')
        {
            $criteria -> add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $codigo_maquina);
        }
        if ($codigo_usuario != '-1' && $codigo_usuario != '')
        {
            $criteria -> add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $codigo_usuario);
        }
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_FECHA, $request -> getParameter('fecha'));
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
        $registros = RegistroUsoMaquinaPeer::doSelect($criteria);

        $criteria = new Criteria();
        if ($codigo_usuario != '-1' && $codigo_usuario != '')
        {
            $criteria -> add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
        }
        $operario = EmpleadoPeer::doSelectOne($criteria);
        $criteria = new Criteria();
        $criteria -> add(EmpresaPeer::EMP_CODIGO, $operario -> getEmplEmpCodigo());
        $empresa = EmpresaPeer::doSelectOne($criteria);
        $inyeccionesEstandarPromedio = $empresa -> getEmpInyectEstandarPromedio();

        $result = array();
        $data = array();

        foreach ($registros as $registro)
        {
            $fields = array();

            $fields['nombre_operario'] = $registro -> obtenerAnalista();
            $fields['nombre_maquina'] = $registro -> obtenerMaquina();
            $fields['nombre_metodo'] = $registro -> obtenerMetodo();

            $tiempoCorrida = round($registro -> calcularTiempoCorridaHoras(), 2);
            $fields['numero_muestras'] = number_format($registro -> contarNumeroMuestrasProgramadas(), 1, '.', '');
            $fields['numero_muestras_reanalizadas'] = number_format($registro -> contarNumeroMuestrasReAnalizadas(), 1, '.', '');
            $fields['numero_inyecciones'] = number_format($registro -> contarNumeroInyeccionesObligatorias($inyeccionesEstandarPromedio), 1, '.', '');
            $fields['numero_reinyecciones'] = number_format($registro -> contarNumeroTotalReinyecciones($inyeccionesEstandarPromedio), 1, '.', '');

            $data[] = $fields;
        }

        //		if(count($data)>0) {
        $data[0]['numero_muestras_dia'] = number_format(RegistroUsoMaquinaPeer::contarMuestrasAnalizadas($registros), 1, '.', '');
        $data[0]['numero_muestras_reanalizadas_dia'] = number_format(RegistroUsoMaquinaPeer::contarMuestrasReanalizadas($registros), 1, '.', '');
        $data[0]['numero_inyecciones_dia'] = number_format(RegistroUsoMaquinaPeer::contarNumeroInyeccionesObligatorias($registros, $inyeccionesEstandarPromedio), 1, '.', '');
        $data[0]['numero_reinyecciones_dia'] = number_format(RegistroUsoMaquinaPeer::contarNumeroReinyecciones($registros, $inyeccionesEstandarPromedio), 1, '.', '');
        //		}

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    public function executeListarReportePerdidasPorMetodo(sfWebRequest $request)
    {
        $user = $this -> getUser();
        $codigo_usuario = $request -> getParameter('codigo_usu_operario');
        $codigo_maquina = $request -> getParameter('codigo_maquina');

        $criteria = new Criteria();
        if ($codigo_maquina != '-1' && $codigo_maquina != '')
        {
            $criteria -> add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $codigo_maquina);
        }
        if ($codigo_usuario != '-1' && $codigo_usuario != '')
        {
            $criteria -> add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $codigo_usuario);
        }
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_FECHA, $request -> getParameter('fecha'));
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
        $registros = RegistroUsoMaquinaPeer::doSelect($criteria);

        $criteria = new Criteria();
        if ($codigo_usuario != '-1' && $codigo_usuario != '')
        {
            $criteria -> add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
        }
        $operario = EmpleadoPeer::doSelectOne($criteria);
        $criteria = new Criteria();
        $criteria -> add(EmpresaPeer::EMP_CODIGO, $operario -> getEmplEmpCodigo());
        $empresa = EmpresaPeer::doSelectOne($criteria);
        $inyeccionesEstandarPromedio = $empresa -> getEmpInyectEstandarPromedio();

        $result = array();
        $data = array();

        foreach ($registros as $registro)
        {
            $fields = array();

            $fields['nombre_operario'] = $registro -> obtenerAnalista();
            $fields['nombre_maquina'] = $registro -> obtenerMaquina();
            $fields['nombre_metodo'] = $registro -> obtenerMetodo();

            $tiempoCorrida = round($registro -> calcularTiempoCorridaHoras(), 2);
            $fields['paros_menores'] = number_format(round($registro -> calcularParosMenoresMinutos($inyeccionesEstandarPromedio), 2), 2);
            $fields['retrabajos'] = number_format($registro -> calcularRetrabajosMinutos($inyeccionesEstandarPromedio), 2);
            $fields['fallas'] = number_format(round($registro -> getRumFallas(), 2), 2);
            $fields['perdidas_velocidad'] = number_format(round($registro -> calcularPerdidasVelocidadMinutos($inyeccionesEstandarPromedio), 2), 2);

            $data[] = $fields;
        }

        //		if(count($data)>0) {
        $data[0]['paros_menores_dia'] = number_format(round(RegistroUsoMaquinaPeer::contarParosMenoresDiaEnMinutos($registros, $inyeccionesEstandarPromedio), 2), 2);
        $data[0]['retrabajos_dia'] = number_format(round(RegistroUsoMaquinaPeer::contarRetrabajosEnMinutos($registros, $inyeccionesEstandarPromedio), 2), 2);
        $data[0]['fallas_dia'] = number_format(round(RegistroUsoMaquinaPeer::contarFallasEnMinutos($registros, $inyeccionesEstandarPromedio), 2), 2);
        $data[0]['perdidas_velocidad_dia'] = number_format(round(RegistroUsoMaquinaPeer::contarPerdidasVelocidadEnMinutos($registros, $inyeccionesEstandarPromedio), 2), 2);
        //		}

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    public function executeListarEquiposActivos()
    {
        $criteria = new Criteria();
        $criteria -> add(MaquinaPeer::MAQ_INDICADORES, true);
        $criteria -> add(MaquinaPeer::MAQ_ELIMINADO, false);
        $maquinas = MaquinaPeer::doSelect($criteria);

        $result = array();
        $data = array();

        foreach ($maquinas as $maquina)
        {
            $fields = array();
            $fields['codigo'] = $maquina -> getMaqCodigo();
            $fields['nombre'] = $maquina -> getMaqNombre();
            //$maquina->getNombreCompleto();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    public function executeListarMaquinas()
    {
        $criteria = new Criteria();
        $criteria -> add(MaquinaPeer::MAQ_INDICADORES, true);
        $maquinas = MaquinaPeer::doSelect($criteria);

        $result = array();
        $data = array();

        foreach ($maquinas as $maquina)
        {
            $fields = array();
            $fields['codigo'] = $maquina -> getMaqCodigo();
            $fields['nombre'] = $maquina -> getMaqNombre();
            //$maquina->getNombreCompleto();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    public function executeListarOperarios()
    {
        $result = array();
        $data = array();

        $criteria = new Criteria();
        $criteria -> add(UsuarioPeer::USU_PER_CODIGO, 3);
        $operarios = UsuarioPeer::doSelect($criteria);

        foreach ($operarios as $operario)
        {
            $fields = array();
            $fields['codigo'] = $operario -> getUsuCodigo();

            $criteria = new Criteria();
            $criteria -> add(EmpleadoPeer::EMPL_USU_CODIGO, $operario -> getUsuCodigo());
            $empleado = EmpleadoPeer::doSelectOne($criteria);

            $fields['nombre'] = $empleado -> getNombreCompleto();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    /*public function obtenerTNPMetodo($registo1,$registro2){
     $tnp=0;

     $minutosFin = $this->getRumHoraFinTrabajo('H') + ($this->getRumHoraFinTrabajo('i')/60) + ($this->getRumHoraFinTrabajo('s')/3600);
     //calcularPerdidaCambioMetodoAjusteMinutos por  getRumTiempoCambioModeloPerdi
     $minutoCambioModelo=($this->getRumTiempoCambioModelo()+$this->calcularPerdidaCambioMetodoAjusteMinutos())/60;
     $tiempoDeCorrida=$this->calcularTiempoCorridaHoras();

     $tnp= $minutosFin -$tiempoDeCorrida - $minutoCambioModelo;
     return $tnp;
     }*/
}
