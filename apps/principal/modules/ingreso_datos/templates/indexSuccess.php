<?php echo stylesheet_tag('extjs/ux/css/GroupTab.css') ?>
<?php echo stylesheet_tag('extjs/ux/css/RowEditor.css') ?>
<?php echo javascript_include_tag('ingreso_datos/ingreso_datos.js') ?>
<?php echo javascript_include_tag('extjs/src/ux/ColumnHeaderGroup.js') ?>
<?php echo javascript_include_tag('extjs/src/ux/RowEditor.js') ?>
<div id="panel_principal"></div>
<div id="ventana_flotante"	class="x-hidden"></div>
<div id="ventana_flotante_historial"  class="x-hidden"></div>
<div id="ventana_calculadora"	class="x-hidden"></div>
<script>
var nombreEmpresa = '<?php echo $nombreEmpresa; ?>';
var urlLogo = urlPrefix+'../'+'<?php echo $urlLogo; ?>';
var inyeccionesEstandarPromedio = <?php echo $inyeccionesEstandarPromedio; ?>;
var esAdministrador = <?php echo $esAdministrador;  ?>;
</script>

<div id="titulo_ingreso_datos">
	<div style="float:left;" >
	<img align=left  hspace=10  height=55 width=100 src="<?php if($urlLogo!=''){ echo (url_for('default/index').'../'.$urlLogo);} else {echo(url_for('default/index').'../images/vacio.png');}  ?>" alt="empresa logo"/>
	</div>

	<div  style="padding-left:10px;padding-top:7px;float:left;" >
	<p style="font-size:x-large;"><font face="arial" size=6 color=#4E79B2><?php echo $nombreEmpresa; ?></font></p>
	</div>

	<div  style="float:right;width:200px;" onclick="abrirAcercaDe();" >
	<img   height=50  src="<?php echo (url_for('default/index').'../images/logo-quantar-color-horizontal.jpg'); ?>" alt="logo Quantar"/>
	</div>
</div>
	
<?php echo javascript_include_tag('../flash/amcolumn/swfobject.js') ?>
