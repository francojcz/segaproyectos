<div id="div_form_inferfaz_admin"></div>

<?php use_stylesheet('extjs/examples/ux/css/ux-all.css' ) ?>

<?php echo javascript_include_tag('extjs/examples/shared/examples.js') ?>

<?php use_stylesheet('examples_extjs/data-view.css' ) ?>
<?php echo javascript_include_tag('extjs/examples/ux/DataView-more.js') ?>

<?php echo javascript_include_tag('interfaz_reporte/form_interfazreporte.js' ) ?>

<?php echo javascript_include_tag('extjs/src/ux/ColumnHeaderGroup.js') ?>

<div id="titulo_interfaz_reporte" style="height: 61px;">
<div style="float: left; "><img align=left hspace=10 height=55 width=100 
	src="<?php if($urlLogo!=''){ echo (url_for('default/index').'../'.$urlLogo);} else {echo(url_for('default/index').'../images/vacio.png');}  ?>"
	alt="empresa logo" /></div>

	<div style="padding-left: 10px; padding-top: 7px; float: left;">
	<p style="font-size: x-large;"><font face="arial" size=6 color=#4E79B2><?php echo $nombreEmpresa; ?></font></p>
	</div>
	
	<div style="float: right; width: 220px;" onclick="abrirAcercaDe();"  >
		<img height=60
		src="<?php echo (url_for('default/index').'../images/logo-quantar-color-horizontal.jpg'); ?>"
		alt="logo Quantar" />
	</div>
</div>

<?php echo javascript_include_tag('extjs/examples/ux/SpinnerField.js' ) ?>
<?php echo javascript_include_tag('extjs/examples/ux/Spinner.js' ) ?>
<?php echo javascript_include_tag('../flash/ampie/swfobject.js') ?>
<?php echo javascript_include_tag('../flash/amline/swfobject.js') ?>
