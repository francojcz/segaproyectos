<?php

/**
 * ingreso_datos actions.
 *
 * @package    tpmlabs
 * @subpackage ingreso_datos
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ingreso_datosActions extends sfActions
{
    public function executeDividirRegistro(sfWebRequest $request)
    {
        $codigoMaquina = $request -> getParameter('codigo_maquina');
        $user = $this -> getUser();
        $codigo_usuario = $user -> getAttribute('usu_codigo');

        $criteria = new Criteria();
        $criteria -> add(MaquinaPeer::MAQ_CODIGO, $codigoMaquina);
        $maquina = MaquinaPeer::doSelectOne($criteria);

        $criteria = new Criteria();
        $criteria -> add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
        $operario = EmpleadoPeer::doSelectOne($criteria);
        $criteria = new Criteria();
        $criteria -> add(EmpresaPeer::EMP_CODIGO, $operario -> getEmplEmpCodigo());
        $empresa = EmpresaPeer::doSelectOne($criteria);
        $inyeccionesEstandarPromedio = $empresa -> getEmpInyectEstandarPromedio();

        $criteria = new Criteria();
        $codigoMaquina = $request -> getParameter('codigo_maquina');
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $codigoMaquina);
        $fecha = $request -> getParameter('fecha');
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_FECHA, $fecha);
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
        $criteria -> addDescendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_TIEMPO_ENTRE_MODELO);
        $registro = RegistroUsoMaquinaPeer::doSelectOne($criteria);

        $segundosFallas = $registro -> getRumFallas() * 60;
        $segundosInicio = ($registro -> getRumHoraInicioTrabajo('H') * 3600) + $registro -> getRumHoraInicioTrabajo('i') * 60 + $registro -> getRumHoraInicioTrabajo('s');
        $segundosFin = ($registro -> getRumHoraFinTrabajo('H') * 3600) + $registro -> getRumHoraFinTrabajo('i') * 60 + $registro -> getRumHoraFinTrabajo('s');

        $deficitTiempo = null;

        $tiempoDisponible = RegistroUsoMaquinaPeer::calcularTiempoDisponibleMinutos($codigoMaquina, $fecha, $inyeccionesEstandarPromedio, TRUE);

        if ($tiempoDisponible < 0)
        {
            $deficitTiempo = 0 - ($tiempoDisponible * 60);
        } else
        {
            return $this -> renderText('1');
        }

        $registroSegundoDia = new RegistroUsoMaquina();
        $datetimeSegundoDia = new DateTime('@' . ($registro -> getRumFecha('U') + 86400));
        $timezone = date_default_timezone_get();
        $datetimeSegundoDia -> setTimezone(new DateTimeZone($timezone));
        $registroSegundoDia -> setRumTiempoEntreModelo('00:00:00');
        $registroSegundoDia -> setRumFecha($datetimeSegundoDia -> format('Y-m-d'));
        $registroSegundoDia -> setRumMaqCodigo($registro -> getRumMaqCodigo());
        $registroSegundoDia -> setRumMetCodigo($registro -> getRumMetCodigo());
        $registroSegundoDia -> setRumEliminado(FALSE);
		$registroSegundoDia -> setRumUsuCodigo($registro -> getRumUsuCodigo());

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirFallas($deficitTiempo, $registro, $registroSegundoDia);

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirParosMenores($deficitTiempo, $registro, $registroSegundoDia);

        // Después del proceso de división, la hora de fin de corrida del registro original debe corresponder a la medianoche
        $registro -> setRumHoraFinTrabajo('00:00:00');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirMuestras($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), 'RumTcUniformidad', 'RumNumMuestrasUniformidad', 'RumNumInyecXMuestraUnifor');
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosMuestras($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), 'RumTcUniformidad', 'RumNumMuestrasUniformidadP', 'RumNumInyecXMuUniforPerd');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirMuestras($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), 'RumTcDisolucion', 'RumNumMuestrasDisolucion', 'RumNumInyecXMuestraDisolu');
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosMuestras($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), 'RumTcDisolucion', 'RumNumMuestrasDisolucionPe', 'RumNumInyecXMuDisoluPerd');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirMuestras($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), 'RumTcPureza', 'RumNumMuestrasPureza', 'RumNumInyecXMuestraPureza');
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosMuestras($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), 'RumTcPureza', 'RumNumMuestrasPurezaPerdid', 'RumNumInyecXMuPurezaPerd');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirMuestras($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), 'RumTcMateriaPrima', 'RumNumMuestrasMateriaPrima', 'RumNumInyecXMuestraMateri');
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosMuestras($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), 'RumTcMateriaPrima', 'RumNumMuMateriaPrimaPerdi', 'RumNumInyecXMuMateriPerd');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirMuestras($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), 'RumTcEstabilidad', 'RumNumMuestrasEstabilidad', 'RumNumInyecXMuestraEstabi');
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosMuestras($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), 'RumTcEstabilidad', 'RumNumMuEstabilidadPerdida', 'RumNumInyecXMuEstabiPerd');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirMuestras($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), 'RumTcProductoTerminado', 'RumNumMuestrasProducto', 'RumNumInyecXMuestraProduc');
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosMuestras($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), 'RumTcProductoTerminado', 'RumNumMuProductoPerdida', 'RumNumInyecXMuProducPerd');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirCurvasCalibracion($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), '6');
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosCurvasCalibracion($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), '6');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirCurvasCalibracion($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), '5');
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosCurvasCalibracion($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), '5');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirCurvasCalibracion($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), '4');
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosCurvasCalibracion($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), '4');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirCurvasCalibracion($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), '3');
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosCurvasCalibracion($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), '3');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirCurvasCalibracion($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), '2');
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosCurvasCalibracion($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), '2');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirCurvasCalibracion($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), '1');
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosCurvasCalibracion($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion(), '1');

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirSystemSuitability($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion());
        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirRetrabajosSystemSuitability($deficitTiempo, $registro, $registroSegundoDia, $maquina -> getMaqTiempoInyeccion());

        list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirPerdidaAlistamiento($deficitTiempo, $registro, $registroSegundoDia);
		
		if($deficitTiempo > 0)
		{
			list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirTiempoAlistamientoConDeficit($deficitTiempo, $registro, $registroSegundoDia);
		}
		else
		{
			list($registro, $registroSegundoDia, $deficitTiempo) = RegistroUsoMaquinaPeer::dividirTiempoAlistamientoSinDeficit($deficitTiempo, $registro, $registroSegundoDia);
		}
		
		$registro -> setRumHoraFinTrabajo('23:59:59.999');
        $registro -> save();
        $registroSegundoDia -> save();

        return $this -> renderText('Ok');
    }

    public function executeListarRegistrosHistorial(sfWebRequest $request)
    {
        $result = array();
        $data = array();

        $criteria = new Criteria();
        $criteria -> clearSelectColumns();
        $criteria -> addJoin(RegistroModificacionPeer::REM_USU_CODIGO, UsuarioPeer::USU_CODIGO);
        $criteria -> addSelectColumn(UsuarioPeer::USU_LOGIN);
        $criteria -> addSelectColumn(RegistroModificacionPeer::REM_NOMBRE_CAMPO);
        $criteria -> addSelectColumn(RegistroModificacionPeer::REM_VALOR_ANTIGUO);
        $criteria -> addSelectColumn(RegistroModificacionPeer::REM_VALOR_NUEVO);
        $criteria -> addSelectColumn(RegistroModificacionPeer::REM_CAUSA);
        $criteria -> addSelectColumn(RegistroModificacionPeer::REM_FECHA_HORA);
        $criteria -> addAscendingOrderByColumn(RegistroModificacionPeer::REM_FECHA_HORA);

        if ($request -> hasParameter('codigo_rum'))
        {
            $criteria -> add(RegistroModificacionPeer::REM_RUM_CODIGO, $request -> getParameter('codigo_rum'));
        }

        $statement = RegistroModificacionPeer::doSelectStmt($criteria);

        $registros = $statement -> fetchAll(PDO::FETCH_NUM);

        foreach ($registros as $registro)
        {
            $fields = array();

            $fields['username'] = $registro[0];
            $fields['nombre_campo'] = $registro[1];
            $fields['valor_antiguo'] = $registro[2];
            $fields['valor_nuevo'] = $registro[3];
            $fields['causa'] = $registro[4];

            $dateTime = new DateTime($registro[5]);
            $timestamp = $dateTime -> getTimestamp();

            $fields['fecha'] = date('Y-m-d', $timestamp);
            $fields['hora'] = date('H:i:s', $timestamp);

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
            $fields['identificacion'] = $empleado -> getEmplNumeroIdentificacion();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    public function executeConsultarTiempoInyeccionMaquina(sfWebRequest $request)
    {
        $codigoMaquina = $request -> getParameter('codigo_maquina');
        $maquina = MaquinaPeer::retrieveByPK($codigoMaquina);

        return $this -> renderText($maquina -> getMaqTiempoInyeccion());
    }

    public function executeCalcularTiempoDisponibleDia(sfWebRequest $request)
    {
        $codigoMaquina = $request -> getParameter('codigo_maquina');
        $fecha = $request -> getParameter('fecha');

        $user = $this -> getUser();
        $codigo_usuario = $user -> getAttribute('usu_codigo');
        $criteria = new Criteria();
        $criteria -> add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
        $operario = EmpleadoPeer::doSelectOne($criteria);
        $criteria = new Criteria();
        $criteria -> add(EmpresaPeer::EMP_CODIGO, $operario -> getEmplEmpCodigo());
        $empresa = EmpresaPeer::doSelectOne($criteria);

        $inyeccionesEstandarPromedio = $empresa -> getEmpInyectEstandarPromedio();

        $tiempoDisponibleHoras = RegistroUsoMaquinaPeer::calcularTiempoDisponibleHoras($codigoMaquina, $fecha, $inyeccionesEstandarPromedio);
        $tiempoDisponibleHoras = round($tiempoDisponibleHoras, 2);
        return $this -> renderText('' . $tiempoDisponibleHoras);
    }

    public function executeGenerarConfiguracionGrafico(sfWebRequest $request)
    {
        $user = $this -> getUser();
        $codigo_usuario = $user -> getAttribute('usu_codigo');
        $criteria = new Criteria();
        //		$criteria->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $codigo_usuario);
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $request -> getParameter('codigo_maquina'));
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_FECHA, $request -> getParameter('fecha'));
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
        $criteria -> addAscendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_TIEMPO_ENTRE_MODELO);
        $registros = RegistroUsoMaquinaPeer::doSelect($criteria);

        $this -> renderText('<?xml version="1.0" encoding="UTF-8"?>');
        $this -> renderText('<settings>');
        $this -> renderText('<type>bar</type>');
        $this -> renderText('<data_type>csv</data_type>');
        $this -> renderText('<font>Tahoma</font>');
        $this -> renderText('<depth>10</depth>');
        $this -> renderText('<angle>45</angle>');
        
        $this -> renderText('
		<column>
			<type>stacked</type>
			<width>50</width>
			<spacing>0</spacing>
			<grow_time>1</grow_time>
			<grow_effect>regular</grow_effect>
			<data_labels><![CDATA[<b>{value}</b>]]></data_labels>
			<balloon_text><![CDATA[{title}: {value} minutos ]]></balloon_text>
		</column>');
        $this -> renderText('<background><border_alpha>15</border_alpha></background>');
        $this -> renderText('<plot_area>
		<margins>
			<left>14</left>
	    <top>40</top>
	    <right>25</right>
	    <bottom>0</bottom>
    </margins>
    </plot_area>');
        $this -> renderText('<grid>
			<category>
			 <alpha>5</alpha>
			</category>
			<value>
			 <alpha>15</alpha>
			 <approx_count>15</approx_count>
			</value>
		</grid>');
        $this -> renderText('<values>
			<value>
				<min>0</min>
				<max>1440</max>
			</value>
		</values>');
        $this -> renderText('<axes>
		<category>
		  <width>1</width>
		</category>
			<value>
			 <width>1</width>
			</value>
		</axes>');
        $this -> renderText('<balloon>
		<alpha>80</alpha>
		<text_color>#000000</text_color>
		<max_width>300</max_width>
		<corner_radius>5</corner_radius>
		<border_width>3</border_width>
		<border_alpha>60</border_alpha>
		<border_color>#000000</border_color>
		</balloon>');
        $this -> renderText('<legend><width>1024</width>
		<max_columns>4</max_columns><spacing>5</spacing></legend>');

        require_once (dirname(__FILE__) . '/../../../../../config/variablesGenerales.php');
        $this -> renderText('<export_as_image>
    <file>' . $urlWeb . 'flash/amcolumn/export.php</file>     
    <color>#CC0000</color>                      
    <alpha>50</alpha>                           
    </export_as_image>');

        $this -> renderText('<graphs>');

        $this -> renderText('<graph>
			<type>column</type>
      <title>Tiempo no programado</title>
      <color>#ffdc44</color>
      </graph>');
      $this -> renderText('<graph>
      <title>Tiempo parada programada</title>
      <color>#47d552</color>
      </graph>');
        $this -> renderText('<graph>
      <title>Tiempo parada no programada</title>
      <color>#ff5454</color>
      </graph>');
        $this -> renderText('<graph>
      <title>Tiempo operativo</title>
      <color>#72a8cd</color>
      </graph>');
        $this -> renderText('<graph>
      <title>Tiempo de parada no programada</title>
      <color>#ff5454</color>
      <visible_in_legend>false</visible_in_legend>
      </graph>');
        //			$this->renderText('<graph>
        //      <title>Tiempo de funcionamiento</title>
        //      <color>#f0a05f</color>
        //      </graph>');
        if (count($registros) > 0)
        {
            unset($registros[0]);
        }

        foreach ($registros as $registro)
        {
            $this -> renderText('<graph>
			<title>Tiempo no programado</title>
			<color>#ffdc44</color>
			<visible_in_legend>false</visible_in_legend>
			</graph>');
            $this -> renderText('<graph>
			<title>Tiempo de parada programada</title>
			<color>#47d552</color>
			<visible_in_legend>false</visible_in_legend>
			</graph>');
            $this -> renderText('<graph>
      <title>Tiempo de parada no programada</title>
      <color>#ff5454</color>
      <visible_in_legend>false</visible_in_legend>
      </graph>');
            $this -> renderText('<graph>
      <title>Tiempo programado</title>
      <color>#72a8cd</color>
			<visible_in_legend>false</visible_in_legend>
      </graph>');
            $this -> renderText('<graph>
      <title>Tiempo de parada no programada</title>
      <color>#ff5454</color>
			<visible_in_legend>false</visible_in_legend>
      </graph>');
            //			$this->renderText('<graph>
            //      <title>Tiempo de funcionamiento</title>
            //      <color>#f0a05f</color>
            //			<visible_in_legend>false</visible_in_legend>
            //      </graph>');
        }

        $this -> renderText('<graph>
      <title>Tiempo no programado</title>
      <color>#ffdc44</color>
      <visible_in_legend>false</visible_in_legend>
      </graph>');

        $this -> renderText('</graphs>');
        $this -> renderText('<labels>
		<label lid="1">
      <x>50%</x> 
      <y>10</y>
      <width>200</width>
      <align>left</align>
      <text>
        <![CDATA[<b>Tiempo (minutos)</b>]]>
      </text> 
    </label>
    </labels>');
        $this -> renderText('<guides>
		<guide>
		<behind>true</behind>
		<width>3</width>
		<start_value>1440</start_value>
		</guide>
		</guides>');
        return $this -> renderText('<settings>');
    }
    
    public function executeGenerarConfiguracionGrafico1(sfWebRequest $request)
    {
        $user = $this -> getUser();
        $codigo_usuario = $user -> getAttribute('usu_codigo');
        $criteria = new Criteria();
        //		$criteria->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $codigo_usuario);
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $request -> getParameter('codigo_maquina'));
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_FECHA, $request -> getParameter('fecha'));
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
        $criteria -> addAscendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_TIEMPO_ENTRE_MODELO);
        $registros = RegistroUsoMaquinaPeer::doSelect($criteria);

        $this -> renderText('<?xml version="1.0" encoding="UTF-8"?>');
        $this -> renderText('<settings>');
        $this -> renderText('<type>bar</type>');
        $this -> renderText('<data_type>csv</data_type>');
        $this -> renderText('<font>Tahoma</font>');
        $this -> renderText('<depth>10</depth>');
        $this -> renderText('<angle>45</angle>');
        
        $this -> renderText('
		<column>
			<type>stacked</type>
			<width>50</width>
			<spacing>0</spacing>
			<grow_time>1</grow_time>
			<grow_effect>regular</grow_effect>
			<data_labels><![CDATA[<b>{value}</b>]]></data_labels>
			<balloon_text><![CDATA[{title}: {value} horas ]]></balloon_text>
		</column>');
        $this -> renderText('<background><border_alpha>15</border_alpha></background>');
        $this -> renderText('<plot_area>
		<margins>
			<left>14</left>
	    <top>40</top>
	    <right>25</right>
	    <bottom>0</bottom>
    </margins>
    </plot_area>');
        $this -> renderText('<grid>
			<category>
			 <alpha>5</alpha>
			</category>
			<value>
			 <alpha>15</alpha>
			 <approx_count>15</approx_count>
			</value>
		</grid>');
        $this -> renderText('<values>
			<value>
				<min>0</min>
				<max>30</max>
			</value>
		</values>');
        $this -> renderText('<axes>
		<category>
		  <width>1</width>
		</category>
			<value>
			 <width>1</width>
			</value>
		</axes>');
        $this -> renderText('<balloon>
		<alpha>80</alpha>
		<text_color>#000000</text_color>
		<max_width>300</max_width>
		<corner_radius>5</corner_radius>
		<border_width>3</border_width>
		<border_alpha>60</border_alpha>
		<border_color>#000000</border_color>
		</balloon>');
        $this -> renderText('<legend><width>1024</width>
		<max_columns>4</max_columns><spacing>5</spacing></legend>');

        require_once (dirname(__FILE__) . '/../../../../../config/variablesGenerales.php');
        $this -> renderText('<export_as_image>
    <file>' . $urlWeb . 'flash/amcolumn/export.php</file>     
    <color>#CC0000</color>                      
    <alpha>50</alpha>                           
    </export_as_image>');

        $this -> renderText('<graphs>');

        $this -> renderText('<graph>
			<type>column</type>
      <title>Tiempo no programado</title>
      <color>#ffdc44</color>
      </graph>');
      $this -> renderText('<graph>
      <title>Tiempo parada programada</title>
      <color>#47d552</color>
      </graph>');
        $this -> renderText('<graph>
      <title>Tiempo parada no programada</title>
      <color>#ff5454</color>
      </graph>');
        $this -> renderText('<graph>
      <title>Tiempo operativo</title>
      <color>#72a8cd</color>
      </graph>');
        $this -> renderText('<graph>
      <title>Tiempo de parada no programada</title>
      <color>#ff5454</color>
      <visible_in_legend>false</visible_in_legend>
      </graph>');
        //			$this->renderText('<graph>
        //      <title>Tiempo de funcionamiento</title>
        //      <color>#f0a05f</color>
        //      </graph>');
        if (count($registros) > 0)
        {
            unset($registros[0]);
        }

        foreach ($registros as $registro)
        {
            $this -> renderText('<graph>
			<title>Tiempo no programado</title>
			<color>#ffdc44</color>
			<visible_in_legend>false</visible_in_legend>
			</graph>');
            $this -> renderText('<graph>
			<title>Tiempo de parada programada</title>
			<color>#47d552</color>
			<visible_in_legend>false</visible_in_legend>
			</graph>');
            $this -> renderText('<graph>
      <title>Tiempo de parada no programada</title>
      <color>#ff5454</color>
      <visible_in_legend>false</visible_in_legend>
      </graph>');
            $this -> renderText('<graph>
      <title>Tiempo programado</title>
      <color>#72a8cd</color>
			<visible_in_legend>false</visible_in_legend>
      </graph>');
            $this -> renderText('<graph>
      <title>Tiempo de parada no programada</title>
      <color>#ff5454</color>
			<visible_in_legend>false</visible_in_legend>
      </graph>');
            //			$this->renderText('<graph>
            //      <title>Tiempo de funcionamiento</title>
            //      <color>#f0a05f</color>
            //			<visible_in_legend>false</visible_in_legend>
            //      </graph>');
        }

        $this -> renderText('<graph>
      <title>Tiempo no programado</title>
      <color>#ffdc44</color>
      <visible_in_legend>false</visible_in_legend>
      </graph>');

        $this -> renderText('</graphs>');
        $this -> renderText('<labels>
		<label lid="1">
      <x>50%</x> 
      <y>10</y>
      <width>200</width>
      <align>left</align>
      <text>
        <![CDATA[<b>Tiempo (horas)</b>]]>
      </text> 
    </label>
    </labels>');
        $this -> renderText('<guides>
		<guide>
		<behind>true</behind>
		<width>3</width>
		<start_value>24</start_value>
		</guide>
		</guides>');
        return $this -> renderText('<settings>');
    }
    
    public function executeGenerarDatosGrafico(sfWebRequest $request)
    {
        $user = $this -> getUser();
        $codigo_usuario = $user -> getAttribute('usu_codigo');

        $criteria = new Criteria();
        //		$criteria->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $codigo_usuario);

        $codigoMaquina = $request -> getParameter('codigo_maquina');
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $codigoMaquina);
        $fecha = $request -> getParameter('fecha');
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_FECHA, $fecha);
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
        $criteria -> addAscendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_TIEMPO_ENTRE_MODELO);
        $registros = RegistroUsoMaquinaPeer::doSelect($criteria);

        $criteria = new Criteria();
        $criteria -> add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
        $operario = EmpleadoPeer::doSelectOne($criteria);
        $criteria = new Criteria();
        $criteria -> add(EmpresaPeer::EMP_CODIGO, $operario -> getEmplEmpCodigo());
        $empresa = EmpresaPeer::doSelectOne($criteria);

        $criteria = new Criteria();
        $criteria -> add(MaquinaPeer::MAQ_CODIGO, $codigoMaquina);
        $maquina = MaquinaPeer::doSelectOne($criteria);

        if (count($registros) == 0)
        {
            return $this -> renderText(';0;0;');
        }

        $minutosActuales = 0;
        foreach ($registros as $registro)
        {
            //									$registro = new RegistroUsoMaquina();

            $minutosTiempoNoProgramado = round($registro -> getRumTiempoEntreModelo('H') * 60 + $registro -> getRumTiempoEntreModelo('i') + ($registro -> getRumTiempoEntreModelo('s') / 60), 2);
            $minutosTiempoNoProgramado -= $minutosActuales;
            $this -> renderText(';' . round($minutosTiempoNoProgramado, 2));

            $minutosTiempoParadaProgramada = $registro -> getRumTiempoCambioModelo();
            $this -> renderText(';' . round($minutosTiempoParadaProgramada, 2));

            $minutosTiempoParadaNoProgramada1 = $registro -> calcularPerdidaCambioMetodoAjusteMinutos();
            $this -> renderText(';' . round($minutosTiempoParadaNoProgramada1, 2));

            $minutosTiempoProgramado = ($registro -> getRumTiempoCorridaSistema() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumeroInyeccionEstandar();
            $inyeccionesEstandarPromedio = $empresa -> getEmpInyectEstandarPromedio();
            for ($i = 1; $i <= $inyeccionesEstandarPromedio; $i++)
            {
                $minutosTiempoProgramado += ($registro -> getRumTiempoCorridaCurvas() + $maquina -> getMaqTiempoInyeccion()) * eval('return $registro->getRumNumeroInyeccionEstandar' . $i . '();');
            }

            $minutosTiempoProgramado += ($registro -> getRumTcProductoTerminado() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumMuestrasProducto() * $registro -> getRumNumInyecXMuestraProduc();
            $minutosTiempoProgramado += ($registro -> getRumTcEstabilidad() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumMuestrasEstabilidad() * $registro -> getRumNumInyecXMuestraEstabi();
            $minutosTiempoProgramado += ($registro -> getRumTcMateriaPrima() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumMuestrasMateriaPrima() * $registro -> getRumNumInyecXMuestraMateri();
            $minutosTiempoProgramado += ($registro -> getRumTcPureza() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumMuestrasPureza() * $registro -> getRumNumInyecXMuestraPureza();
            $minutosTiempoProgramado += ($registro -> getRumTcDisolucion() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumMuestrasDisolucion() * $registro -> getRumNumInyecXMuestraDisolu();
            $minutosTiempoProgramado += ($registro -> getRumTcUniformidad() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumMuestrasUniformidad() * $registro -> getRumNumInyecXMuestraUnifor();
            $this -> renderText(';' . round($minutosTiempoProgramado, 2));

            $minutosTiempoParadaNoProgramada2 = $registro -> calcularParosMenoresMinutos($inyeccionesEstandarPromedio);
            $minutosTiempoParadaNoProgramada2 += $registro -> calcularRetrabajosMinutos($inyeccionesEstandarPromedio);
            $minutosTiempoParadaNoProgramada2 += $registro -> getRumFallas();
            $this -> renderText(';' . round($minutosTiempoParadaNoProgramada2, 2));

            $minutosActuales = ($registro -> getRumHoraFinTrabajo('H') * 60) + $registro -> getRumHoraFinTrabajo('i') + ($registro -> getRumHoraFinTrabajo('s') / 60);
        }

        $tiempoDisponible = RegistroUsoMaquinaPeer::calcularTiempoDisponibleHoras($codigoMaquina, $fecha, $inyeccionesEstandarPromedio, TRUE) * 60;
        $this -> renderText(';' . round($tiempoDisponible, 2));

        return $this -> renderText('');
    }

    public function executeGenerarDatosGrafico1(sfWebRequest $request)
    {
        $user = $this -> getUser();
        $codigo_usuario = $user -> getAttribute('usu_codigo');

        $criteria = new Criteria();
        //		$criteria->add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $codigo_usuario);

        $codigoMaquina = $request -> getParameter('codigo_maquina');
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $codigoMaquina);
        $fecha = $request -> getParameter('fecha');
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_FECHA, $fecha);
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
        $criteria -> addAscendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_TIEMPO_ENTRE_MODELO);
        $registros = RegistroUsoMaquinaPeer::doSelect($criteria);

        $criteria = new Criteria();
        $criteria -> add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
        $operario = EmpleadoPeer::doSelectOne($criteria);
        $criteria = new Criteria();
        $criteria -> add(EmpresaPeer::EMP_CODIGO, $operario -> getEmplEmpCodigo());
        $empresa = EmpresaPeer::doSelectOne($criteria);

        $criteria = new Criteria();
        $criteria -> add(MaquinaPeer::MAQ_CODIGO, $codigoMaquina);
        $maquina = MaquinaPeer::doSelectOne($criteria);

        if (count($registros) == 0)
        {
            return $this -> renderText(';0;0;');
        }

        $minutosActuales = 0;
        foreach ($registros as $registro)
        {            
            $minutosTiempoNoProgramado = round($registro -> getRumTiempoEntreModelo('H') * 60 + $registro -> getRumTiempoEntreModelo('i') + ($registro -> getRumTiempoEntreModelo('s') / 60), 2);
            $minutosTiempoNoProgramado -= $minutosActuales;
            $this -> renderText(';' . round($minutosTiempoNoProgramado/60, 2));

            $minutosTiempoParadaProgramada = $registro -> getRumTiempoCambioModelo();
            $this -> renderText(';' . round($minutosTiempoParadaProgramada/60, 2));

            $minutosTiempoParadaNoProgramada1 = $registro -> calcularPerdidaCambioMetodoAjusteMinutos();
            $this -> renderText(';' . round($minutosTiempoParadaNoProgramada1/60, 2));

            $minutosTiempoProgramado = ($registro -> getRumTiempoCorridaSistema() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumeroInyeccionEstandar();
            $inyeccionesEstandarPromedio = $empresa -> getEmpInyectEstandarPromedio();
            for ($i = 1; $i <= $inyeccionesEstandarPromedio; $i++)
            {
                $minutosTiempoProgramado += ($registro -> getRumTiempoCorridaCurvas() + $maquina -> getMaqTiempoInyeccion()) * eval('return $registro->getRumNumeroInyeccionEstandar' . $i . '();');
            }

            $minutosTiempoProgramado += ($registro -> getRumTcProductoTerminado() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumMuestrasProducto() * $registro -> getRumNumInyecXMuestraProduc();
            $minutosTiempoProgramado += ($registro -> getRumTcEstabilidad() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumMuestrasEstabilidad() * $registro -> getRumNumInyecXMuestraEstabi();
            $minutosTiempoProgramado += ($registro -> getRumTcMateriaPrima() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumMuestrasMateriaPrima() * $registro -> getRumNumInyecXMuestraMateri();
            $minutosTiempoProgramado += ($registro -> getRumTcPureza() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumMuestrasPureza() * $registro -> getRumNumInyecXMuestraPureza();
            $minutosTiempoProgramado += ($registro -> getRumTcDisolucion() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumMuestrasDisolucion() * $registro -> getRumNumInyecXMuestraDisolu();
            $minutosTiempoProgramado += ($registro -> getRumTcUniformidad() + $maquina -> getMaqTiempoInyeccion()) * $registro -> getRumNumMuestrasUniformidad() * $registro -> getRumNumInyecXMuestraUnifor();
            $this -> renderText(';' . round($minutosTiempoProgramado/60, 2));

            $minutosTiempoParadaNoProgramada2 = $registro -> calcularParosMenoresMinutos($inyeccionesEstandarPromedio);
            $minutosTiempoParadaNoProgramada2 += $registro -> calcularRetrabajosMinutos($inyeccionesEstandarPromedio);
            $minutosTiempoParadaNoProgramada2 += $registro -> getRumFallas();
            $this -> renderText(';' . round($minutosTiempoParadaNoProgramada2/60, 2));

            $minutosActuales = ($registro -> getRumHoraFinTrabajo('H') * 60) + $registro -> getRumHoraFinTrabajo('i') + ($registro -> getRumHoraFinTrabajo('s') / 60);
        }

        $tiempoDisponible = RegistroUsoMaquinaPeer::calcularTiempoDisponibleHoras($codigoMaquina, $fecha, $inyeccionesEstandarPromedio, TRUE) * 60;
        $this -> renderText(';' . round($tiempoDisponible/60, 2));

        return $this -> renderText('');
    }
    
    public function executeIndex(sfWebRequest $request)
    {
        $user = $this -> getUser();
        $codigo_usuario = $user -> getAttribute('usu_codigo');
        $criteria = new Criteria();
        $criteria -> add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
        $operario = EmpleadoPeer::doSelectOne($criteria);
        $criteria = new Criteria();
        $criteria -> add(EmpresaPeer::EMP_CODIGO, $operario -> getEmplEmpCodigo());
        $empresa = EmpresaPeer::doSelectOne($criteria);

        $this -> nombreEmpresa = $empresa -> getEmpNombre();
        $this -> urlLogo = $empresa -> getEmpLogoUrl();
        $this -> inyeccionesEstandarPromedio = $empresa -> getEmpInyectEstandarPromedio();

        $this -> esAdministrador = ($user -> getAttribute('usu_per_codigo') == '2') ? 'true' : 'false';
    }

    public function executeConsultarDatosOperario()
    {
        $user = $this -> getUser();

        $result = array();
        $data = array();
        $fields = array();

        if ($user -> getAttribute('usu_per_codigo') == '3')
        {
            $codigo_usuario = $user -> getAttribute('usu_codigo');

            $criteria = new Criteria();
            $criteria -> add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
            $operario = EmpleadoPeer::doSelectOne($criteria);

            $fields['codigo'] = $operario -> getEmplCodigo();
            $fields['nombre'] = $operario -> getNombreCompleto();
            $fields['identificacion'] = $operario -> getEmplNumeroIdentificacion();
        } else
        {
            $fields['codigo'] = '';
            $fields['nombre'] = '';
            $fields['identificacion'] = '';
        }

        $data[] = $fields;
        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    public function executeListarEquiposPorComputador()
    {
        $user = $this -> getUser();
        $criteria = new Criteria();
        if ($user -> getAttribute('usu_per_codigo') == '3')
        {
            $criteria -> add(MaquinaPeer::MAQ_COM_CERTIFICADO, $user -> getAttribute('certificado'));
        }
        $criteria -> add(MaquinaPeer::MAQ_ELIMINADO, false);
        $maquinas = MaquinaPeer::doSelect($criteria);

        $result = array();
        $data = array();

        foreach ($maquinas as $maquina)
        {
            $fields = array();

            //            $maquina = new Maquina();

            $fields['codigo'] = $maquina -> getMaqCodigo();
            $fields['nombre'] = $maquina -> getMaqNombre();
            $fields['codigo_inventario'] = $maquina -> getMaqCodigoInventario();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    public function executeListarMaquinasPorComputador()
    {
        $user = $this -> getUser();
        $criteria = new Criteria();
        if ($user -> getAttribute('usu_per_codigo') == '3')
        {
            $criteria -> add(MaquinaPeer::MAQ_COM_CERTIFICADO, $user -> getAttribute('certificado'));
        }
        $maquinas = MaquinaPeer::doSelect($criteria);

        $result = array();
        $data = array();

        foreach ($maquinas as $maquina)
        {
            $fields = array();

            //						$maquina = new Maquina();

            $fields['codigo'] = $maquina -> getMaqCodigo();
            $fields['nombre'] = $maquina -> getMaqNombre();
            $fields['codigo_inventario'] = $maquina -> getMaqCodigoInventario();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    //	public function executeConsultarNombreOperario() {
    //		$user = $this->getUser();
    //		$codigo_usuario = $user->getAttribute('usu_codigo');
    //		$criteria = new Criteria();
    //		$criteria->add(EmpleadoPeer::EMPL_USU_CODIGO, $codigo_usuario);
    //		$empleado = EmpleadoPeer::doSelectOne($criteria);
    //		return $this->renderText($empleado->getNombreCompleto());
    //	}
    public function executeEliminarRegistroEvento(sfWebRequest $request)
    {        
        $user = $this -> getUser();
        $codigo_perfil_usuario = $user -> getAttribute('usu_per_codigo');
        
        if ($request -> hasParameter('codigo'))
        {
            $registroEvento = EventoEnRegistroPeer::retrieveByPK($request -> getParameter('codigo'));

            $registro = RegistroUsoMaquinaPeer::retrieveByPK($registroEvento -> getEvrgRumCodigo());

            $dateTimeFechaUso = new DateTime($registro -> getRumFecha('Y-m-d'));
            $timeStampFechaUso = $dateTimeFechaUso -> getTimestamp();
            $dateTimeFechaActual = new DateTime(date('Y-m-d'));
            $timeStampFechaActual = $dateTimeFechaActual -> getTimestamp();

            if (($timeStampFechaUso < $timeStampFechaActual) && ($codigo_perfil_usuario!='2'))
            {
                return $this -> renderText('No es posible eliminar un evento con fecha pasada');
            }

            $registroEvento -> delete();
        }
        return $this -> renderText('Ok');
    }

    public function executeListarCategoriasEventos()
    {
        $categorias = CategoriaEventoPeer::doSelect(new Criteria());

        $result = array();
        $data = array();

        foreach ($categorias as $categoria)
        {
            $fields = array();

            //			$categoria = new CategoriaEvento();

            $fields['codigo'] = $categoria -> getCatCodigo();
            $fields['nombre'] = $categoria -> getCatNombre();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    public function executeListarEventosPorCategoria(sfWebRequest $request)
    {        
        $criteria = new Criteria();
        $criteria -> addJoin(EventoPeer::EVE_CODIGO, EventoPorCategoriaPeer::EVCA_EVE_CODIGO);
        $criteria -> add(EventoPorCategoriaPeer::EVCA_CAT_CODIGO, $request -> getParameter('codigo_categoria'));
        $criteria -> add(EventoPeer::EVE_ELIMINADO, 0);
        $eventos = EventoPeer::doSelect($criteria);

        $result = array();
        $data = array();

        foreach ($eventos as $evento)
        {
            $fields = array();

            //			$evento = new Evento();

            $fields['codigo'] = $evento -> getEveCodigo();
            $fields['nombre'] = $evento -> getEveNombre();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    public function executeListarEventos()
    {
        $eventos = EventoPeer::doSelect(new Criteria());

        $result = array();
        $data = array();

        foreach ($eventos as $evento)
        {
            $fields = array();

            //			$evento = new Evento();

            $fields['codigo'] = $evento -> getEveCodigo();
            $fields['nombre'] = $evento -> getEveNombre();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    public function executeModificarRegistroEvento(sfWebRequest $request)
    {
        $user = $this -> getUser();
        $codigo_perfil_usuario = $user -> getAttribute('usu_per_codigo');
        //		if($request->hasParameter('codigo')) {
        $registro = RegistroUsoMaquinaPeer::retrieveByPK($request -> getParameter('codigo_rum'));

        $dateTimeFechaUso = new DateTime($registro -> getRumFecha('Y-m-d'));
        $timeStampFechaUso = $dateTimeFechaUso -> getTimestamp();
        $dateTimeFechaActual = new DateTime(date('Y-m-d'));
        $timeStampFechaActual = $dateTimeFechaActual -> getTimestamp();

        if (($timeStampFechaUso < $timeStampFechaActual) && ($codigo_perfil_usuario!='2'))
        {
            return $this -> renderText('No es posible adicionar/modificar un evento con una fecha pasada');
        }

        $registroEvento = null;
        if ($request -> getParameter('codigo') == '')
        {
            $registroEvento = new EventoEnRegistro();
            $registroEvento -> setEvrgRumCodigo($request -> getParameter('codigo_rum'));
            $registroEvento -> setEvrgHoraRegistro(date('H:i'));
            $registroEvento -> setEvrgEveCodigo($request -> getParameter('codigo_evento'));
        } else
        {
            $registroEvento = EventoEnRegistroPeer::retrieveByPK($request -> getParameter('codigo'));
        }

        $registroEvento -> setEvrgEveCodigo($request -> getParameter('id_evento'));
        $registroEvento -> setEvrgHoraOcurrio($request -> getParameter('hora_ocurrio'));
        $registroEvento -> setEvrgDuracion($request -> getParameter('evrg_duracion'));
        $registroEvento -> setEvrgObservaciones($request -> getParameter('observaciones'));

        $registroEvento -> save();
        //		}
        return $this -> renderText('Ok');
    }

    public function executeListarRegistrosEventos(sfWebRequest $request)
    {
        $criteria = new Criteria();
        if ($request -> hasParameter('codigo_rum'))
        {
            $criteria -> add(EventoEnRegistroPeer::EVRG_RUM_CODIGO, $request -> getParameter('codigo_rum'));
        }
        $registrosEventos = EventoEnRegistroPeer::doSelect($criteria);

        $result = array();
        $data = array();

        foreach ($registrosEventos as $registroEvento)
        {
            $fields = array();

            //												$registroEvento = new EventoEnRegistro();

            $fields['codigo'] = $registroEvento -> getEvrgCodigo();
            $fields['id_evento'] = $registroEvento -> getEvrgEveCodigo();
            $fields['hora_ocurrio'] = $registroEvento -> getEvrgHoraOcurrio('H:i');
            $fields['evrg_duracion'] = number_format($registroEvento -> getEvrgDuracion(), 2, '.', '');

            $fields['observaciones'] = $registroEvento -> getEvrgObservaciones();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    //	public function executeRegistrarEvento() {
    //		$registroEvento = new EventoEnRegistro();
    //		$registroEvento->save();
    //		return $this->renderText('Ok');
    //	}
    public function executeEliminarRegistroUsoMaquina(sfWebRequest $request)
    {
        $user = $this -> getUser();
        $codigo_perfil_usuario = $user -> getAttribute('usu_per_codigo');
        
        if ($request -> hasParameter('id_registro_uso_maquina'))
        {
            $registro = RegistroUsoMaquinaPeer::retrieveByPK($request -> getParameter('id_registro_uso_maquina'));

            $dateTimeFechaUso = new DateTime($registro -> getRumFecha('Y-m-d'));
            $timeStampFechaUso = $dateTimeFechaUso -> getTimestamp();
            $dateTimeFechaActual = new DateTime(date('Y-m-d'));
            $timeStampFechaActual = $dateTimeFechaActual -> getTimestamp();
            
            if (($timeStampFechaUso < $timeStampFechaActual) && ($codigo_perfil_usuario!='2'))
            {
                return $this -> renderText('No es posible eliminar un registro con una fecha pasada');
            }

            $user = $this -> getUser();
            $codigo_usuario = $user -> getAttribute('usu_codigo');
            $registro -> setRumUsuCodigoElimino($codigo_usuario);
            $registro -> setRumCausaEliminacion($request -> getParameter('causa'));
            $registro -> setRumFechaHoraElimSistema(date('Y-m-d H:i:s'));
            $registro -> setRumEliminado(true);
            $registro -> save();
            //			$registro->delete();
        }
        return $this -> renderText('Ok');
    }

    public function executeModificarRegistroUsoMaquina(sfWebRequest $request)
    {
        if ($request -> hasParameter('id_registro_uso_maquina'))
        {
            $registro = RegistroUsoMaquinaPeer::retrieveByPK($request -> getParameter('id_registro_uso_maquina'));

            $dateTimeFechaUso = new DateTime($registro -> getRumFecha('Y-m-d'));
            $timeStampFechaUso = $dateTimeFechaUso -> getTimestamp();
            $dateTimeFechaActual = new DateTime(date('Y-m-d'));
            $timeStampFechaActual = $dateTimeFechaActual -> getTimestamp();
            $timeStampDiaAnterior = $timeStampFechaActual - 86400;

            $user = $this -> getUser();

            if ($timeStampFechaUso < $timeStampDiaAnterior && $user -> getAttribute('usu_per_codigo') == '3')
            {
                return $this -> renderText('1');
            }
            //			if($request->hasParameter('id_metodo')) {
            //				$metodo = MetodoPeer::retrieveByPK($request->getParameter('id_metodo'));
            //				$registro->setRumTiempoCambioModelo($metodo->getMetTiempoCambioModelo());
            //				$registro->setRumTiempoCorridaSistema($metodo->getMetTiempoCorridaSistema());
            //				$registro->setRumNumeroInyeccionEstandar($metodo->getMetNumeroInyeccionEstandar());
            //				$registro->setRumTiempoCorridaCurvas($metodo->getMetTiempoCorridaCurvas());
            //				$registro->setRumTiempoCorridaMuestras($metodo->getMetTiempoCorridaMuestra());
            //				$registro->setRumTiempoCorridaSistemaEst($metodo->getMetTiempoCorridaSistema());
            //				$registro->setRumTiempoCorridaCurvasEsta($metodo->getMetTiempoCorridaCurvas());
            //				$registro->setRumTiempoCorridaMuestrasEs($metodo->getMetTiempoCorridaMuestra());
            //				$registro->setRumNumeroInyeccionEstandar1($metodo->getMetNumInyeccionEstandar1());
            //				$registro->setRumNumeroInyeccionEstandar2($metodo->getMetNumInyeccionEstandar2());
            //				$registro->setRumNumeroInyeccionEstandar3($metodo->getMetNumInyeccionEstandar3());
            //				$registro->setRumNumeroInyeccionEstandar4($metodo->getMetNumInyeccionEstandar4());
            //				$registro->setRumNumeroInyeccionEstandar5($metodo->getMetNumInyeccionEstandar5());
            //				$registro->setRumNumeroInyeccionEstandar6($metodo->getMetNumInyeccionEstandar6());
            //				$registro->setRumNumeroInyeccionEstandar7($metodo->getMetNumInyeccionEstandar7());
            //				$registro->setRumNumeroInyeccionEstandar8($metodo->getMetNumInyeccionEstandar8());
            //				$registro->setRumNumInyecXMuestraProduc($metodo->getMetNumInyecXMuProducto());
            //				$registro->setRumNumInyecXMuestraEstabi($metodo->getMetNumInyecXMuEstabilidad());
            //				$registro->setRumNumInyecXMuestraMateri($metodo->getMetNumInyecXMuMateriaPri());
            //				$registro->setRumNumInyecXMuestraEstand($metodo->getMetNumInyecXMuEstandar());
            //				$registro->setRumMetCodigo($request->getParameter('id_metodo'));
            //			}

            $user = $this -> getUser();
            $codigo_usuario = $user -> getAttribute('usu_codigo');

            $registroModificacion = new RegistroModificacion();
            $registroModificacion -> setRemUsuCodigo($codigo_usuario);
            $registroModificacion -> setRemRumCodigo($registro -> getRumCodigo());
            $registroModificacion -> setRemFechaHora(date('Y-m-d H:i:s'));

            if ($request -> hasParameter('tiempo_entre_metodos'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo entre metodos');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTiempoEntreModelo('H:i:s'));

                $registro -> setRumTiempoEntreModelo($request -> getParameter('tiempo_entre_metodos'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTiempoEntreModelo('H:i:s'));
            }
            //			if($request->hasParameter('cambio_metodo_ajuste')) {
            //				$registro->setRumTiempoCambioModelo($request->getParameter('cambio_metodo_ajuste'));
            //			}
            if ($request -> hasParameter('tiempo_corrida_ss'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - System suitability');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTiempoCorridaSistema());

                $registro -> setRumTiempoCorridaSistema($request -> getParameter('tiempo_corrida_ss'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTiempoCorridaSistema());
            }
            //			if($request->hasParameter('numero_inyecciones_ss')) {
            //				$registro->setRumNumeroInyeccionEstandar($request->getParameter('numero_inyecciones_ss'));
            //			}
            if ($request -> hasParameter('tiempo_corrida_cc'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Curva de calibración');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTiempoCorridaCurvas());

                $registro -> setRumTiempoCorridaCurvas($request -> getParameter('tiempo_corrida_cc'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTiempoCorridaCurvas());
            }
            //			if($request->hasParameter('numero_inyecciones_estandar1')) {
            //				$registro->setRumNumeroInyeccionEstandar1($request->getParameter('numero_inyecciones_estandar1'));
            //			}
            //			if($request->hasParameter('numero_inyecciones_estandar2')) {
            //				$registro->setRumNumeroInyeccionEstandar2($request->getParameter('numero_inyecciones_estandar2'));
            //			}
            //			if($request->hasParameter('numero_inyecciones_estandar3')) {
            //				$registro->setRumNumeroInyeccionEstandar3($request->getParameter('numero_inyecciones_estandar3'));
            //			}
            //			if($request->hasParameter('numero_inyecciones_estandar4')) {
            //				$registro->setRumNumeroInyeccionEstandar4($request->getParameter('numero_inyecciones_estandar4'));
            //			}
            //			if($request->hasParameter('numero_inyecciones_estandar5')) {
            //				$registro->setRumNumeroInyeccionEstandar5($request->getParameter('numero_inyecciones_estandar5'));
            //			}
            //			if($request->hasParameter('numero_inyecciones_estandar6')) {
            //				$registro->setRumNumeroInyeccionEstandar6($request->getParameter('numero_inyecciones_estandar6'));
            //			}
            //			if($request->hasParameter('numero_inyecciones_estandar7')) {
            //				$registro->setRumNumeroInyeccionEstandar7($request->getParameter('numero_inyecciones_estandar7'));
            //			}
            //			if($request->hasParameter('numero_inyecciones_estandar8')) {
            //				$registro->setRumNumeroInyeccionEstandar8($request->getParameter('numero_inyecciones_estandar8'));
            //			}
            if ($request -> hasParameter('tiempo_corrida_producto'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Producto');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTcProductoTerminado());

                $registro -> setRumTcProductoTerminado($request -> getParameter('tiempo_corrida_producto'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTcProductoTerminado());
            }
            if ($request -> hasParameter('tiempo_corrida_estabilidad'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Estabilidad');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTcEstabilidad());

                $registro -> setRumTcEstabilidad($request -> getParameter('tiempo_corrida_estabilidad'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTcEstabilidad());
            }
            if ($request -> hasParameter('tiempo_corrida_materia_prima'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Materia prima');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTcMateriaPrima());

                $registro -> setRumTcMateriaPrima($request -> getParameter('tiempo_corrida_materia_prima'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTcMateriaPrima());
            }
            if ($request -> hasParameter('tiempo_corrida_pureza'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Pureza');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTcPureza());

                $registro -> setRumTcPureza($request -> getParameter('tiempo_corrida_pureza'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTcPureza());
            }
            if ($request -> hasParameter('tiempo_corrida_disolucion'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Disolución');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTcDisolucion());

                $registro -> setRumTcDisolucion($request -> getParameter('tiempo_corrida_disolucion'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTcDisolucion());
            }
            if ($request -> hasParameter('tiempo_corrida_uniformidad'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Uniformidad');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTcUniformidad());

                $registro -> setRumTcUniformidad($request -> getParameter('tiempo_corrida_uniformidad'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTcUniformidad());
            }
            if ($request -> hasParameter('numero_muestras_producto'))
            {
                $registroModificacion -> setRemNombreCampo('Número muestras - Producto');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumMuestrasProducto());

                $registro -> setRumNumMuestrasProducto($request -> getParameter('numero_muestras_producto'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumMuestrasProducto());
            }
            //			if($request->hasParameter('numero_inyecciones_x_muestra_producto')) {
            //				$registro->setRumNumInyecXMuestraProduc($request->getParameter('numero_inyecciones_x_muestra_producto'));
            //			}
            if ($request -> hasParameter('numero_muestras_estabilidad'))
            {
                $registroModificacion -> setRemNombreCampo('Número muestras - Estabilidad');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumMuestrasEstabilidad());

                $registro -> setRumNumMuestrasEstabilidad($request -> getParameter('numero_muestras_estabilidad'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumMuestrasEstabilidad());
            }
            //			if($request->hasParameter('numero_inyecciones_x_muestra_estabilidad')) {
            //				$registro->setRumNumInyecXMuestraEstabi($request->getParameter('numero_inyecciones_x_muestra_estabilidad'));
            //			}
            if ($request -> hasParameter('numero_muestras_materia_prima'))
            {
                $registroModificacion -> setRemNombreCampo('Número muestras - Materia prima');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumMuestrasMateriaPrima());

                $registro -> setRumNumMuestrasMateriaPrima($request -> getParameter('numero_muestras_materia_prima'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumMuestrasMateriaPrima());
            }
            //			if($request->hasParameter('numero_inyecciones_x_muestra_materia_prima')) {
            //				$registro->setRumNumInyecXMuestraMateri($request->getParameter('numero_inyecciones_x_muestra_materia_prima'));
            //			}
            if ($request -> hasParameter('numero_muestras_pureza'))
            {
                $registroModificacion -> setRemNombreCampo('Número muestras - Pureza');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumMuestrasPureza());

                $registro -> setRumNumMuestrasPureza($request -> getParameter('numero_muestras_pureza'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumMuestrasPureza());
            }
            if ($request -> hasParameter('numero_muestras_disolucion'))
            {
                $registroModificacion -> setRemNombreCampo('Número muestras - Disolución');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumMuestrasDisolucion());

                $registro -> setRumNumMuestrasDisolucion($request -> getParameter('numero_muestras_disolucion'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumMuestrasDisolucion());
            }
            if ($request -> hasParameter('numero_muestras_uniformidad'))
            {
                $registroModificacion -> setRemNombreCampo('Número muestras - Uniformidad');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumMuestrasUniformidad());

                $registro -> setRumNumMuestrasUniformidad($request -> getParameter('numero_muestras_uniformidad'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumMuestrasUniformidad());
            }
            if ($request -> hasParameter('hora_inicio_corrida'))
            {
                $registroModificacion -> setRemNombreCampo('Hora inicio');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumHoraInicioTrabajo('H:i:s'));

                $registro -> setRumHoraInicioTrabajo($request -> getParameter('hora_inicio_corrida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumHoraInicioTrabajo('H:i:s'));
            }
            if ($request -> hasParameter('hora_fin_corrida'))
            {
                $registroModificacion -> setRemNombreCampo('Hora fin');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumHoraFinTrabajo('H:i:s'));

                $registro -> setRumHoraFinTrabajo($request -> getParameter('hora_fin_corrida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumHoraFinTrabajo('H:i:s'));
            }
            if ($request -> hasParameter('fallas'))
            {
                $registroModificacion -> setRemNombreCampo('Fallas');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumFallas());

                $registro -> setRumFallas($request -> getParameter('fallas') * 60);

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumFallas());
            }            
            if ($request -> hasParameter('observaciones'))
            {
                $registroModificacion -> setRemNombreCampo('Observaciones');
                $registroModificacion -> setRemValorAntiguo('' . $registro ->getRumObservaciones());

                $registro ->setRumObservaciones($request -> getParameter('observaciones'));
                
                $registroModificacion -> setRemValorNuevo('' . $registro ->getRumObservaciones());
            }

            if ($request -> hasParameter('tiempo_entre_metodos_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo entre métodos');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTiempoEntreModelo('H:i:s'));

                $registro -> setRumTiempoEntreModelo($request -> getParameter('tiempo_entre_metodos_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTiempoEntreModelo('H:i:s'));
            }

            //			if($request->hasParameter('cambio_metodo_ajuste_perdida')) {
            //				$registro->setRumTiempoCambioModeloPerdi($request->getParameter('cambio_metodo_ajuste_perdida'));
            //			}

            if ($request -> hasParameter('tiempo_corrida_ss_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - System suitability');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTiempoCorridaSistema());

                $registro -> setRumTiempoCorridaSistema($request -> getParameter('tiempo_corrida_ss_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTiempoCorridaSistema());
            }
            if ($request -> hasParameter('numero_inyecciones_ss_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Reinyecciones - System suitability');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyeccionEstandarPer());

                $registro -> setRumNumInyeccionEstandarPer($request -> getParameter('numero_inyecciones_ss_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyeccionEstandarPer());
            }
            if ($request -> hasParameter('tiempo_corrida_cc_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Curva de calibración');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTiempoCorridaCurvas());

                $registro -> setRumTiempoCorridaCurvas($request -> getParameter('tiempo_corrida_cc_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTiempoCorridaCurvas());
            }
            if ($request -> hasParameter('numero_inyecciones_estandar1_perdida') && $registro -> getRumNumeroInyeccionEstandar1() != 0)
            {
                $registroModificacion -> setRemNombreCampo('Reinyecciones estandar 1 - Curva de calibración');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyeccionEstandar1Pe());

                $registro -> setRumNumInyeccionEstandar1Pe($request -> getParameter('numero_inyecciones_estandar1_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyeccionEstandar1Pe());
            }
            if ($request -> hasParameter('numero_inyecciones_estandar2_perdida') && $registro -> getRumNumeroInyeccionEstandar2() != 0)
            {
                $registroModificacion -> setRemNombreCampo('Reinyecciones estandar 2 - Curva de calibración');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyeccionEstandar2Pe());

                $registro -> setRumNumInyeccionEstandar2Pe($request -> getParameter('numero_inyecciones_estandar2_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyeccionEstandar2Pe());
            }
            if ($request -> hasParameter('numero_inyecciones_estandar3_perdida') && $registro -> getRumNumeroInyeccionEstandar3() != 0)
            {
                $registroModificacion -> setRemNombreCampo('Reinyecciones estandar 3 - Curva de calibración');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyeccionEstandar3Pe());

                $registro -> setRumNumInyeccionEstandar3Pe($request -> getParameter('numero_inyecciones_estandar3_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyeccionEstandar3Pe());
            }
            if ($request -> hasParameter('numero_inyecciones_estandar4_perdida') && $registro -> getRumNumeroInyeccionEstandar4() != 0)
            {
                $registroModificacion -> setRemNombreCampo('Reinyecciones estandar 4 - Curva de calibración');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyeccionEstandar4Pe());

                $registro -> setRumNumInyeccionEstandar4Pe($request -> getParameter('numero_inyecciones_estandar4_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyeccionEstandar4Pe());
            }
            if ($request -> hasParameter('numero_inyecciones_estandar5_perdida') && $registro -> getRumNumeroInyeccionEstandar5() != 0)
            {
                $registroModificacion -> setRemNombreCampo('Reinyecciones estandar 5 - Curva de calibración');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyeccionEstandar5Pe());

                $registro -> setRumNumInyeccionEstandar5Pe($request -> getParameter('numero_inyecciones_estandar5_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyeccionEstandar5Pe());
            }
            if ($request -> hasParameter('numero_inyecciones_estandar6_perdida') && $registro -> getRumNumeroInyeccionEstandar6() != 0)
            {
                $registroModificacion -> setRemNombreCampo('Reinyecciones estandar 6 - Curva de calibración');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyeccionEstandar6Pe());

                $registro -> setRumNumInyeccionEstandar6Pe($request -> getParameter('numero_inyecciones_estandar6_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyeccionEstandar6Pe());
            }
            if ($request -> hasParameter('numero_inyecciones_estandar7_perdida') && $registro -> getRumNumeroInyeccionEstandar7() != 0)
            {
                $registroModificacion -> setRemNombreCampo('Reinyecciones estandar 7 - Curva de calibración');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyeccionEstandar7Pe());

                $registro -> setRumNumInyeccionEstandar7Pe($request -> getParameter('numero_inyecciones_estandar7_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyeccionEstandar7Pe());
            }
            if ($request -> hasParameter('numero_inyecciones_estandar8_perdida') && $registro -> getRumNumeroInyeccionEstandar8() != 0)
            {
                $registroModificacion -> setRemNombreCampo('Reinyecciones estandar 8 - Curva de calibración');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyeccionEstandar8Pe());

                $registro -> setRumNumInyeccionEstandar8Pe($request -> getParameter('numero_inyecciones_estandar8_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyeccionEstandar8Pe());
            }

            if ($request -> hasParameter('tiempo_corrida_producto_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Producto');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTcProductoTerminado());

                $registro -> setRumTcProductoTerminado($request -> getParameter('tiempo_corrida_producto_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTcProductoTerminado());
            }
            if ($request -> hasParameter('tiempo_corrida_estabilidad_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Estabilidad');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTcEstabilidad());

                $registro -> setRumTcEstabilidad($request -> getParameter('tiempo_corrida_estabilidad_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTcEstabilidad());
            }
            if ($request -> hasParameter('tiempo_corrida_materia_prima_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Materia prima');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTcMateriaPrima());

                $registro -> setRumTcMateriaPrima($request -> getParameter('tiempo_corrida_materia_prima_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTcMateriaPrima());
            }
            if ($request -> hasParameter('tiempo_corrida_pureza_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Pureza');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTcPureza());

                $registro -> setRumTcPureza($request -> getParameter('tiempo_corrida_pureza_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTcPureza());
            }
            if ($request -> hasParameter('tiempo_corrida_disolucion_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Disolución');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTcDisolucion());

                $registro -> setRumTcDisolucion($request -> getParameter('tiempo_corrida_disolucion_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTcDisolucion());
            }
            if ($request -> hasParameter('tiempo_corrida_uniformidad_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Tiempo de corrida - Uniformidad');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumTcUniformidad());

                $registro -> setRumTcUniformidad($request -> getParameter('tiempo_corrida_uniformidad_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumTcUniformidad());
            }

            if ($request -> hasParameter('numero_muestras_producto_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Número de muestras reanalizadas - Producto');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumMuProductoPerdida());

                $registro -> setRumNumMuProductoPerdida($request -> getParameter('numero_muestras_producto_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumMuProductoPerdida());
            }
            if ($request -> hasParameter('numero_inyecciones_x_muestra_producto_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Número de reinyecciones x muestra - Producto');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyecXMuProducPerd());

                $registro -> setRumNumInyecXMuProducPerd($request -> getParameter('numero_inyecciones_x_muestra_producto_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyecXMuProducPerd());
            }
            if ($request -> hasParameter('numero_muestras_estabilidad_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Número de muestras reanalizadas - Estabilidad');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumMuEstabilidadPerdida());

                $registro -> setRumNumMuEstabilidadPerdida($request -> getParameter('numero_muestras_estabilidad_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumMuEstabilidadPerdida());
            }
            if ($request -> hasParameter('numero_inyecciones_x_muestra_estabilidad_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Número de reinyecciones x muestra - Estabilidad');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyecXMuEstabiPerd());

                $registro -> setRumNumInyecXMuEstabiPerd($request -> getParameter('numero_inyecciones_x_muestra_estabilidad_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyecXMuEstabiPerd());
            }
            if ($request -> hasParameter('numero_muestras_materia_prima_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Número de muestras reanalizadas - Materia prima');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumMuMateriaPrimaPerdi());

                $registro -> setRumNumMuMateriaPrimaPerdi($request -> getParameter('numero_muestras_materia_prima_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumMuMateriaPrimaPerdi());
            }
            if ($request -> hasParameter('numero_inyecciones_x_muestra_materia_prima_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Número de reinyecciones x muestra - Materia prima');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyecXMuMateriPerd());

                $registro -> setRumNumInyecXMuMateriPerd($request -> getParameter('numero_inyecciones_x_muestra_materia_prima_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyecXMuMateriPerd());
            }
            if ($request -> hasParameter('numero_muestras_pureza_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Número de muestras reanalizadas - Pureza');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumMuestrasPurezaPerdid());

                $registro -> setRumNumMuestrasPurezaPerdid($request -> getParameter('numero_muestras_pureza_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumMuestrasPurezaPerdid());
            }
            if ($request -> hasParameter('numero_inyecciones_x_muestra_pureza_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Número de reinyecciones x muestra - Pureza');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyecXMuPurezaPerd());

                $registro -> setRumNumInyecXMuPurezaPerd($request -> getParameter('numero_inyecciones_x_muestra_pureza_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyecXMuPurezaPerd());
            }
            if ($request -> hasParameter('numero_muestras_disolucion_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Número de muestras reanalizadas - Disolución');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumMuestrasDisolucionPe());

                $registro -> setRumNumMuestrasDisolucionPe($request -> getParameter('numero_muestras_disolucion_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumMuestrasDisolucionPe());
            }
            if ($request -> hasParameter('numero_inyecciones_x_muestra_disolucion_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Número de reinyecciones x muestra - Disolución');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyecXMuDisoluPerd());

                $registro -> setRumNumInyecXMuDisoluPerd($request -> getParameter('numero_inyecciones_x_muestra_disolucion_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyecXMuDisoluPerd());
            }
            if ($request -> hasParameter('numero_muestras_uniformidad_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Número de muestras reanalizadas - Uniformidad');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumMuestrasUniformidadP());

                $registro -> setRumNumMuestrasUniformidadP($request -> getParameter('numero_muestras_uniformidad_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumMuestrasUniformidadP());
            }
            if ($request -> hasParameter('numero_inyecciones_x_muestra_uniformidad_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Número de reinyecciones x muestra - Uniformidad');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumNumInyecXMuUniforPerd());

                $registro -> setRumNumInyecXMuUniforPerd($request -> getParameter('numero_inyecciones_x_muestra_uniformidad_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumNumInyecXMuUniforPerd());
            }

            if ($request -> hasParameter('hora_inicio_corrida_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Hora inicio');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumHoraInicioTrabajo('H:i:s'));

                $registro -> setRumHoraInicioTrabajo($request -> getParameter('hora_inicio_corrida_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumHoraInicioTrabajo('H:i:s'));
            }
            if ($request -> hasParameter('hora_fin_corrida_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Hora fin');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumHoraFinTrabajo('H:i:s'));

                $registro -> setRumHoraFinTrabajo($request -> getParameter('hora_fin_corrida_perdida'));

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumHoraFinTrabajo('H:i:s'));
            }
            if ($request -> hasParameter('fallas_perdida'))
            {
                $registroModificacion -> setRemNombreCampo('Fallas');
                $registroModificacion -> setRemValorAntiguo('' . $registro -> getRumFallas());

                $registro -> setRumFallas($request -> getParameter('fallas_perdida') * 60);

                $registroModificacion -> setRemValorNuevo('' . $registro -> getRumFallas());
            }

            if ($registro -> isModified())
            {
                $causa = '';
                if ($request -> hasParameter('causa'))
                {
                    $causa = $request -> getParameter('causa');
                }

                $registroModificacion -> setRemCausa($causa);

                // Solo almacena las modificaciones hechas por el administrador
                if ($user -> getAttribute('usu_per_codigo') == '2')
                {
                    $registroModificacion -> save();
                }
            }

            $registro -> save();
        }
        return $this -> renderText('Ok');
    }

    public function executeCrearRegistroUsoMaquina(sfWebRequest $request)
    {
        $user = $this -> getUser();
        $codigo_usuario = $user -> getAttribute('usu_codigo');
        $codigo_perfil_usuario = $user -> getAttribute('usu_per_codigo');
        if (($codigo_perfil_usuario!='3') && ($codigo_perfil_usuario!='2'))
        {
            return $this -> renderText('3');
        }
        
        $dateTimeFechaUso = new DateTime($request -> getParameter('fecha'));
        $timeStampFechaUso = $dateTimeFechaUso -> getTimestamp();
        $dateTimeFechaActual = new DateTime(date('Y-m-d'));
        $timeStampFechaActual = $dateTimeFechaActual -> getTimestamp();
        if (($timeStampFechaUso < $timeStampFechaActual) && ($codigo_perfil_usuario!='2'))
        {            
            return $this -> renderText('2');
        }

        $criteria = new Criteria();
        if ($request -> hasParameter('codigo_maquina'))
        {
            $criteria -> add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $request -> getParameter('codigo_maquina'));
        }
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_FECHA, $request -> getParameter('fecha'));
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
        $criteria -> addAscendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_TIEMPO_ENTRE_MODELO);
        $registros = RegistroUsoMaquinaPeer::doSelect($criteria);

        $criterion = $criteria -> getNewCriterion(RegistroUsoMaquinaPeer::RUM_TIEMPO_ENTRE_MODELO, NULL, Criteria::ISNULL);
        $criterion -> addOr($criteria -> getNewCriterion(RegistroUsoMaquinaPeer::RUM_HORA_INICIO_TRABAJO, NULL, Criteria::ISNULL));
        $criterion -> addOr($criteria -> getNewCriterion(RegistroUsoMaquinaPeer::RUM_HORA_FIN_TRABAJO, NULL, Criteria::ISNULL));

        $criteria -> add($criterion);

        $numeroDeRegistrosConCamposHoraNulos = RegistroUsoMaquinaPeer::doCount($criteria);

        if ($numeroDeRegistrosConCamposHoraNulos > 0)
        {
            return $this -> renderText('1');
        }

        $registro = new RegistroUsoMaquina();

        $metodo = MetodoPeer::retrieveByPK($request -> getParameter('id_metodo'));
        //$registro->setRumTiempoCambioModelo($metodo->getMetTiempoCambioModelo()); //cambio maryit el 9 de feb de 2011
        $registro -> setRumTiempoCambioModelo($metodo -> getMetTiempoEstandar());
        $registro -> setRumTiempoCorridaSistema($metodo -> getMetTiempoCorridaSistema());
        $registro -> setRumNumeroInyeccionEstandar($metodo -> getMetNumeroInyeccionEstandar());
        $registro -> setRumTiempoCorridaCurvas($metodo -> getMetTiempoCorridaCurvas());

        // Version 1.1 {
        $registro -> setRumTcProductoTerminado($metodo -> getMetTcProductoTerminado());
        $registro -> setRumTcEstabilidad($metodo -> getMetTcEstabilidad());
        $registro -> setRumTcMateriaPrima($metodo -> getMetTcMateriaPrima());
        $registro -> setRumTcPureza($metodo -> getMetTcPureza());
        $registro -> setRumTcDisolucion($metodo -> getMetTcDisolucion());
        $registro -> setRumTcUniformidad($metodo -> getMetTcUniformidad());
        // }

        $registro -> setRumTiempoCorridaSistemaEst($metodo -> getMetTiempoCorridaSistema());
        $registro -> setRumTiempoCorridaCurvasEsta($metodo -> getMetTiempoCorridaCurvas());

        // Version 1.1 {
        $registro -> setRumTcProductoTerminadoEsta($metodo -> getMetTcProductoTerminado());
        $registro -> setRumTcEstabilidadEstandar($metodo -> getMetTcEstabilidad());
        $registro -> setRumTcMateriaPrimaEstandar($metodo -> getMetTcMateriaPrima());
        $registro -> setRumTcPurezaEstandar($metodo -> getMetTcPureza());
        $registro -> setRumTcDisolucionEstandar($metodo -> getMetTcDisolucion());
        $registro -> setRumTcUniformidadEstandar($metodo -> getMetTcUniformidad());
        // }

        $registro -> setRumNumeroInyeccionEstandar1($metodo -> getMetNumInyeccionEstandar1());
        $registro -> setRumNumeroInyeccionEstandar2($metodo -> getMetNumInyeccionEstandar2());
        $registro -> setRumNumeroInyeccionEstandar3($metodo -> getMetNumInyeccionEstandar3());
        $registro -> setRumNumeroInyeccionEstandar4($metodo -> getMetNumInyeccionEstandar4());
        $registro -> setRumNumeroInyeccionEstandar5($metodo -> getMetNumInyeccionEstandar5());
        $registro -> setRumNumeroInyeccionEstandar6($metodo -> getMetNumInyeccionEstandar6());
        $registro -> setRumNumeroInyeccionEstandar7($metodo -> getMetNumInyeccionEstandar7());
        $registro -> setRumNumeroInyeccionEstandar8($metodo -> getMetNumInyeccionEstandar8());
        $registro -> setRumNumInyecXMuestraProduc($metodo -> getMetNumInyecXMuProducto());
        $registro -> setRumNumInyecXMuestraEstabi($metodo -> getMetNumInyecXMuEstabilidad());
        $registro -> setRumNumInyecXMuestraMateri($metodo -> getMetNumInyecXMuMateriaPri());

        // Version 1.1 {
        $registro -> setRumNumInyecXMuestraPureza($metodo -> getMetNumInyecXMuPureza());
        $registro -> setRumNumInyecXMuestraDisolu($metodo -> getMetNumInyecXMuDisolucion());
        $registro -> setRumNumInyecXMuestraUnifor($metodo -> getMetNumInyecXMuUniformidad());
        // }

        $registro -> setRumMetCodigo($request -> getParameter('id_metodo'));

        $registro -> setRumMaqCodigo($request -> getParameter('codigo_maquina'));
        $registro -> setRumFecha($request -> getParameter('fecha'));
        $registro -> setRumFechaHoraRegSistema(date('Y-m-d H:i:s'));
        $registro -> setRumUsuCodigo($codigo_usuario);
        $registro -> setRumEliminado(false);
        $registro -> save();
        return $this -> renderText('Ok');
    }

    public function executeListarRegistrosUsoMaquina(sfWebRequest $request)
    {
        $criteria = new Criteria();
        if ($request -> hasParameter('codigo_maquina'))
        {
            $criteria -> add(RegistroUsoMaquinaPeer::RUM_MAQ_CODIGO, $request -> getParameter('codigo_maquina'));
        }
        if ($request -> hasParameter('codigo_operario') && $request -> getParameter('codigo_operario') != '-1')
        {
            $criteria -> add(RegistroUsoMaquinaPeer::RUM_USU_CODIGO, $request -> getParameter('codigo_operario'));
        }
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_FECHA, $request -> getParameter('fecha'));
        $criteria -> add(RegistroUsoMaquinaPeer::RUM_ELIMINADO, false);
        $criteria -> addAscendingOrderByColumn(RegistroUsoMaquinaPeer::RUM_TIEMPO_ENTRE_MODELO);
        $registros = RegistroUsoMaquinaPeer::doSelect($criteria);

        $result = array();
        $data = array();

        $horasFin = 0;
        $minutosFin = 0;
        $segundosFin = 0;

        foreach ($registros as $registro)
        {
            $fields = array();

            //			$registro = new RegistroUsoMaquina();

            $fields['id_registro_uso_maquina'] = $registro -> getRumCodigo();
            $fields['id_metodo'] = $registro -> getRumMetCodigo();
            $fields['tiempo_entre_metodos'] = $registro -> getRumTiempoEntreModelo('H:i:s');
            $fields['cambio_metodo_ajuste'] = number_format($registro -> getRumTiempoCambioModelo(), 2, '.', '');
            $fields['tiempo_corrida_ss'] = number_format($registro -> getRumTiempoCorridaSistema(), 2, '.', '');
            $fields['numero_inyecciones_ss'] = number_format($registro -> getRumNumeroInyeccionEstandar(), 2, '.', '');
            $fields['tiempo_corrida_cc'] = number_format($registro -> getRumTiempoCorridaCurvas(), 2, '.', '');

            $fields['numero_inyecciones_estandar1'] = number_format($registro -> getRumNumeroInyeccionEstandar1(), 2, '.', '');
            $fields['numero_inyecciones_estandar2'] = number_format($registro -> getRumNumeroInyeccionEstandar2(), 2, '.', '');
            $fields['numero_inyecciones_estandar3'] = number_format($registro -> getRumNumeroInyeccionEstandar3(), 2, '.', '');
            $fields['numero_inyecciones_estandar4'] = number_format($registro -> getRumNumeroInyeccionEstandar4(), 2, '.', '');
            $fields['numero_inyecciones_estandar5'] = number_format($registro -> getRumNumeroInyeccionEstandar5(), 2, '.', '');
            $fields['numero_inyecciones_estandar6'] = number_format($registro -> getRumNumeroInyeccionEstandar6(), 2, '.', '');
            $fields['numero_inyecciones_estandar7'] = number_format($registro -> getRumNumeroInyeccionEstandar7(), 2, '.', '');
            $fields['numero_inyecciones_estandar8'] = number_format($registro -> getRumNumeroInyeccionEstandar8(), 2, '.', '');

            // Version 1.1 {
            $fields['tiempo_corrida_producto'] = number_format($registro -> getRumTcProductoTerminado(), 2, '.', '');
            $fields['tiempo_corrida_estabilidad'] = number_format($registro -> getRumTcEstabilidad(), 2, '.', '');
            $fields['tiempo_corrida_materia_prima'] = number_format($registro -> getRumTcMateriaPrima(), 2, '.', '');
            $fields['tiempo_corrida_pureza'] = number_format($registro -> getRumTcPureza(), 2, '.', '');
            $fields['tiempo_corrida_disolucion'] = number_format($registro -> getRumTcDisolucion(), 2, '.', '');
            $fields['tiempo_corrida_uniformidad'] = number_format($registro -> getRumTcUniformidad(), 2, '.', '');
            // }

            $fields['numero_muestras_producto'] = number_format($registro -> getRumNumMuestrasProducto(), 2, '.', '');
            $fields['numero_inyecciones_x_muestra_producto'] = number_format($registro -> getRumNumInyecXMuestraProduc(), 2, '.', '');
            $fields['numero_muestras_estabilidad'] = number_format($registro -> getRumNumMuestrasEstabilidad(), 2, '.', '');
            $fields['numero_inyecciones_x_muestra_estabilidad'] = number_format($registro -> getRumNumInyecXMuestraEstabi(), 2, '.', '');
            $fields['numero_muestras_materia_prima'] = number_format($registro -> getRumNumMuestrasMateriaPrima(), 2, '.', '');
            $fields['numero_inyecciones_x_muestra_materia_prima'] = number_format($registro -> getRumNumInyecXMuestraMateri(), 2, '.', '');

            // Version 1.1 {
            $fields['numero_muestras_pureza'] = number_format($registro -> getRumNumMuestrasPureza(), 2, '.', '');
            $fields['numero_inyecciones_x_muestra_pureza'] = number_format($registro -> getRumNumInyecXMuestraPureza(), 2, '.', '');
            $fields['numero_muestras_disolucion'] = number_format($registro -> getRumNumMuestrasDisolucion(), 2, '.', '');
            $fields['numero_inyecciones_x_muestra_disolucion'] = number_format($registro -> getRumNumInyecXMuestraDisolu(), 2, '.', '');
            $fields['numero_muestras_uniformidad'] = number_format($registro -> getRumNumMuestrasUniformidad(), 2, '.', '');
            $fields['numero_inyecciones_x_muestra_uniformidad'] = number_format($registro -> getRumNumInyecXMuestraUnifor(), 2, '.', '');
            // }

            $fields['hora_inicio_corrida'] = $registro -> getRumHoraInicioTrabajo('H:i:s');
            $fields['hora_fin_corrida'] = $registro -> getRumHoraFinTrabajo('H:i:s');
            $fields['fallas'] = number_format($registro -> getRumFallas() / 60, 2, '.', '');
            $fields['observaciones'] = $registro -> getRumObservaciones();

            $data[] = $fields;

            $fields = array();

            $fields['id_registro_uso_maquina'] = $registro -> getRumCodigo();
            $fields['id_metodo'] = $registro -> getRumMetCodigo();
            $fields['tiempo_entre_metodos'] = number_format(round($registro -> calcularTiempoEntreMetodosHoras($horasFin, $minutosFin, $segundosFin), 2), 2, '.', '');
            $fields['cambio_metodo_ajuste'] = number_format($registro -> calcularPerdidaCambioMetodoAjusteMinutos(), 2, '.', '');
            //			$fields['cambio_metodo_ajuste'] = sprintf('%g', $registro->getRumTiempoCambioModeloPerdi());
            $fields['tiempo_corrida_ss'] = number_format($registro -> getRumTiempoCorridaSistema(), 2, '.', '');
            $fields['numero_inyecciones_ss'] = number_format($registro -> getRumNumInyeccionEstandarPer(), 2, '.', '');
            $fields['tiempo_corrida_cc'] = number_format($registro -> getRumTiempoCorridaCurvas(), 2, '.', '');

            $fields['numero_inyecciones_estandar1'] = number_format($registro -> getRumNumInyeccionEstandar1Pe(), 2, '.', '');
            $fields['numero_inyecciones_estandar2'] = number_format($registro -> getRumNumInyeccionEstandar2Pe(), 2, '.', '');
            $fields['numero_inyecciones_estandar3'] = number_format($registro -> getRumNumInyeccionEstandar3Pe(), 2, '.', '');
            $fields['numero_inyecciones_estandar4'] = number_format($registro -> getRumNumInyeccionEstandar4Pe(), 2, '.', '');
            $fields['numero_inyecciones_estandar5'] = number_format($registro -> getRumNumInyeccionEstandar5Pe(), 2, '.', '');
            $fields['numero_inyecciones_estandar6'] = number_format($registro -> getRumNumInyeccionEstandar6Pe(), 2, '.', '');
            $fields['numero_inyecciones_estandar7'] = number_format($registro -> getRumNumInyeccionEstandar7Pe(), 2, '.', '');
            $fields['numero_inyecciones_estandar8'] = number_format($registro -> getRumNumInyeccionEstandar8Pe(), 2, '.', '');

            // Version 1.1 {
            $fields['tiempo_corrida_producto'] = number_format($registro -> getRumTcProductoTerminado(), 2, '.', '');
            $fields['tiempo_corrida_estabilidad'] = number_format($registro -> getRumTcEstabilidad(), 2, '.', '');
            $fields['tiempo_corrida_materia_prima'] = number_format($registro -> getRumTcMateriaPrima(), 2, '.', '');
            $fields['tiempo_corrida_pureza'] = number_format($registro -> getRumTcPureza(), 2, '.', '');
            $fields['tiempo_corrida_disolucion'] = number_format($registro -> getRumTcDisolucion(), 2, '.', '');
            $fields['tiempo_corrida_uniformidad'] = number_format($registro -> getRumTcUniformidad(), 2, '.', '');
            // }

            $fields['numero_muestras_producto'] = number_format($registro -> getRumNumMuProductoPerdida(), 2, '.', '');
            $fields['numero_inyecciones_x_muestra_producto'] = number_format($registro -> getRumNumInyecXMuProducPerd(), 2, '.', '');
            $fields['numero_muestras_estabilidad'] = number_format($registro -> getRumNumMuEstabilidadPerdida(), 2, '.', '');
            $fields['numero_inyecciones_x_muestra_estabilidad'] = number_format($registro -> getRumNumInyecXMuEstabiPerd(), 2, '.', '');
            $fields['numero_muestras_materia_prima'] = number_format($registro -> getRumNumMuMateriaPrimaPerdi(), 2, '.', '');
            $fields['numero_inyecciones_x_muestra_materia_prima'] = number_format($registro -> getRumNumInyecXMuMateriPerd(), 2, '.', '');

            // Version 1.1 {
            $fields['numero_muestras_pureza'] = number_format($registro -> getRumNumMuestrasPurezaPerdid(), 2, '.', '');
            $fields['numero_inyecciones_x_muestra_pureza'] = number_format($registro -> getRumNumInyecXMuPurezaPerd(), 2, '.', '');
            $fields['numero_muestras_disolucion'] = number_format($registro -> getRumNumMuestrasDisolucionPe(), 2, '.', '');
            $fields['numero_inyecciones_x_muestra_disolucion'] = number_format($registro -> getRumNumInyecXMuDisoluPerd(), 2, '.', '');
            $fields['numero_muestras_uniformidad'] = number_format($registro -> getRumNumMuestrasUniformidadP(), 2, '.', '');
            $fields['numero_inyecciones_x_muestra_uniformidad'] = number_format($registro -> getRumNumInyecXMuUniforPerd(), 2, '.', '');
            // }

            $fields['hora_inicio_corrida'] = $registro -> getRumHoraInicioTrabajo('H:i:s');
            $fields['hora_fin_corrida'] = $registro -> getRumHoraFinTrabajo('H:i:s');
            $fields['fallas'] = number_format($registro -> getRumFallas() / 60, 2, '.', '');
            $fields['observaciones'] = $registro -> getRumObservaciones();

            $horasFin = $registro -> getRumHoraFinTrabajo('H');
            $minutosFin = $registro -> getRumHoraFinTrabajo('i');
            $segundosFin = $registro -> getRumHoraFinTrabajo('s');

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

    public function executeListarMetodos()
    {
        //15 de mayo cambio maryit
        //se deben listar los metodos que no han sido eliminados
        //los metodos eliminados tiene en la columna MET_ELIMINADO un 1 los activos tiene un 0
        $conexion = new Criteria();
        $conexion -> add(MetodoPeer::MET_ELIMINADO, FALSE);
        $conexion -> addAscendingOrderByColumn(MetodoPeer::MET_NOMBRE);
        $metodos = MetodoPeer::doSelect($conexion);

        $result = array();
        $data = array();

        foreach ($metodos as $metodo)
        {
            $fields = array();

            $fields['codigo'] = $metodo -> getMetCodigo();
            $fields['nombre'] = $metodo -> getMetNombre();

            $data[] = $fields;
        }

        $result['data'] = $data;
        return $this -> renderText(json_encode($result));
    }

}
