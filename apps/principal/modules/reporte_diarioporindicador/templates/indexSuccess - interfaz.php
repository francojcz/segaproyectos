<div id="div_form_reporte_diarioporindicador"></div>
<?php echo javascript_include_tag('examples_extjs/Ext.ux.grid.Search.js') ?>
<?php echo javascript_include_tag('extjs/src/ux/ColumnHeaderGroup.js') ?>

<?php //retornarTablaIndicadoresXMetodo(); ?>

<script>

	var ancho_columna=50;
	/*
	var te=new Ext.Panel( {
layout:'table',
layoutConfig: {
    columns: 3
},
items: [
    {html:'1,1',rowspan:3,border:false,},//ctCls:'poner_azul',bodyCssClass:'poner_azul'
    {html:'1,2'},
    {html:'1,3'},
    {html:'2,2',colspan:2},
    {html:'3,2'},
    {html:'3,3'}
]});
         
	te.render('div_form_reporte_diarioporindicador');*/
	
	
	var table =new Ext.Panel( {
		id: 'table-panel',
		title: '',
		layout: 'table',
		layoutConfig: {
			columns: 26
		},
		defaults: {
			width:30,
			bodyStyle:'padding:2px 2px'
		},
		items: [
		{html:'FECHA',border:false, rowspan:2},
		{html:'MAQUINA',border:false, rowspan:2},
		{html:'ANALISTA',border:false, rowspan:2},
		{html:'METODO',border:false, rowspan:2},
		{html:'TP',border:false, colspan:2},
		{html:'TNP',border:false, colspan:2},
		{html:'TPP',border:false, colspan:2},
		{html:'TPNP',border:false, colspan:2},
		{html:'TF',border:false, colspan:2},
		{html:'TO',border:false, colspan:2},
		{html:'D',border:false, colspan:2},
		{html:'E',border:false, colspan:2},
		{html:'C',border:false, colspan:2},
		{html:'AE',border:false, colspan:2},
		{html:'OEE',border:false, colspan:2},
		
		{html:'Metodo',border:false},
		{html:'Dia',border:false},
		{html:'Metodo',border:false},
		{html:'Dia',border:false},
		{html:'Metodo',border:false},
		{html:'Dia',border:false},
		{html:'Metodo',border:false},
		{html:'Dia',border:false},
		{html:'Metodo',border:false},
		{html:'Dia',border:false},
		{html:'Metodo',border:false},
		{html:'Dia',border:false},		
		{html:'Metodo',border:false},
		{html:'Dia',border:false},
		{html:'Metodo',border:false},
		{html:'Dia',border:false},		
		{html:'Metodo',border:false},
		{html:'Dia',border:false},		
		{html:'Metodo',border:false},
		{html:'Dia',border:false},		
		{html:'Metodo',border:false},
		{html:'Dia',border:false},		
		
	
	<?php
	$cantidad_indicadores_x_metodo=6;//count($array_indicadores_metodo);
	?>
	
	<?php  
	for($cant_basica=0;$cant_basica<$cantidad_indicadores_x_metodo;$cant_basica++){
		if($cant_basica==0){
		echo("{html: '".$datos_basicos[$cant_basica]['rdpi_fecha']."',width:70}");
		}
		else{
		echo(",{html: '".$datos_basicos[$cant_basica]['rdpi_fecha']."',width:70}");
		}
		
		echo(",{html: '".$datos_basicos[$cant_basica]['rdpi_maquina']."',width:70}");
		echo(",{html: '".$datos_basicos[$cant_basica]['rdpi_analista']."',width:70}");
		echo(",{html: '".$datos_basicos[$cant_basica]['rdpi_metodo']."',width:70}");
		
		//ctCls:'poner_azul',bodyCssClass:'poner_azul'
		echo(",{html: '".$array_indicadores_metodo[$cant_basica]['rdpi_tp_metodo']."',bodyCssClass:'poner_azul'}");
		if($cant_basica==0){
			echo(",{html: '".$array_indicadores_dia['rdpi_tp_dia']."',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_azul'}");
		}
		
		echo(",{html: '".$array_indicadores_metodo[$cant_basica]['rdpi_tnp_metodo']."',bodyCssClass:'poner_amarillo'}");
		if($cant_basica==0){
			echo(",{html: '".$array_indicadores_dia['rdpi_tnp_dia']."',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_amarillo'}");
		}
		
		echo(",{html: '".$array_indicadores_metodo[$cant_basica]['rdpi_tpp_metodo']."',bodyCssClass:'poner_verde'}");
		if($cant_basica==0){
			echo(",{html: '".$array_indicadores_dia['rdpi_tpp_dia']."',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_verde'}");
			//echo(",{html: '".$datos_basicos['rdpi_tp_dia']."',rowspan:".$cantidad_indicadores_x_metodo."}");
			//echo(",{html: '2',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_verde'}");
		}
		
		echo(",{html: '".$array_indicadores_metodo[$cant_basica]['rdpi_tpnp_metodo']."',bodyCssClass:'poner_verde'}");
		if($cant_basica==0){
			echo(",{html: '".$array_indicadores_dia['rdpi_tpnp_dia']."',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_verde'}");
			//echo(",{html: '".$datos_basicos['rdpi_tp_dia']."',rowspan:".$cantidad_indicadores_x_metodo."}");
			//echo(",{html: '2',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_verde'}");
		}

		echo(",{html: '".$array_indicadores_metodo[$cant_basica]['rdpi_tf_metodo']."',bodyCssClass:'poner_rojo'}");
		if($cant_basica==0){
			//echo(",{html: '".$datos_basicos['rdpi_tp_dia']."',rowspan:".$cantidad_indicadores_x_metodo."}");
			echo(",{html: '2',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_rojo'}");
		}

		echo(",{html: '".$array_indicadores_metodo[$cant_basica]['rdpi_to_metodo']."',bodyCssClass:'poner_rojo'}");
		if($cant_basica==0){
			//echo(",{html: '".$datos_basicos['rdpi_tp_dia']."',rowspan:".$cantidad_indicadores_x_metodo."}");
			echo(",{html: '2',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_rojo'}");
		}

		echo(",{html: '".$array_indicadores_metodo[$cant_basica]['rdpi_d_metodo']."',bodyCssClass:'poner_azul'}");
		if($cant_basica==0){
			//echo(",{html: '".$datos_basicos['rdpi_tp_dia']."',rowspan:".$cantidad_indicadores_x_metodo."}");
			echo(",{html: '2',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_azul'}");
		}

		echo(",{html: '".$array_indicadores_metodo[$cant_basica]['rdpi_e_metodo']."',bodyCssClass:'poner_cafe'}");
		if($cant_basica==0){
			//echo(",{html: '".$datos_basicos['rdpi_tp_dia']."',rowspan:".$cantidad_indicadores_x_metodo."}");
			echo(",{html: '2',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_cafe'}");
		}

	
		echo(",{html: '".$array_indicadores_metodo[$cant_basica]['rdpi_c_metodo']."',bodyCssClass:'poner_cafe'}");
		if($cant_basica==0){
			//echo(",{html: '".$datos_basicos['rdpi_tp_dia']."',rowspan:".$cantidad_indicadores_x_metodo."}");
			echo(",{html: '2',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_cafe'}");
		}

		echo(",{html: '".$array_indicadores_metodo[$cant_basica]['rdpi_ae_metodo']."',bodyCssClass:'poner_cafe'}");
		if($cant_basica==0){
			//echo(",{html: '".$datos_basicos['rdpi_tp_dia']."',rowspan:".$cantidad_indicadores_x_metodo."}");
			echo(",{html: '2',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_cafe'}");
		}

		echo(",{html: '".$array_indicadores_metodo[$cant_basica]['rdpi_oee_metodo']."',bodyCssClass:'poner_cafe'}");
		if($cant_basica==0){
			//echo(",{html: '".$datos_basicos['rdpi_tp_dia']."',rowspan:".$cantidad_indicadores_x_metodo."}");
			echo(",{html: '2',rowspan:".$cantidad_indicadores_x_metodo.",bodyCssClass:'poner_cafe'}");
		}
	
	}
	?>
	]});
		
table.render('div_form_reporte_diarioporindicador');
</script>
<?php echo javascript_include_tag('reporte_diarioporindicador/form_reporte_diarioporindicador.js' ) ?>
