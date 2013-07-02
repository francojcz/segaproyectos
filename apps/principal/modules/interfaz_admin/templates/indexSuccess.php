<div id="div_form_inferfaz_admin"></div>

<style type="text/css">
body {
	background-color: #4E79B2 !important;
	padding:0px;
	margin:0px;
}
</style>

<?php use_stylesheet('extjs/examples/panel/css/bubble.css' ) ?>
<?php use_stylesheet('extjs/examples/ux/css/Portal.css' ) ?>
<?php use_stylesheet('extjs/examples/ux/css/GroupTab.css' ) ?>
<?php use_stylesheet('extjs/examples/ux/css/ux-all.css' ) ?>


<?php echo javascript_include_tag('extjs/examples/ux/RowEditor.js') ?>
<?php //echo javascript_include_tag('extjs/Ext.ux.grid.Search.js') ?>
<?php echo javascript_include_tag('extjs/examples/panel/BubblePanel.js') ?>

<?php echo javascript_include_tag('extjs/examples/shared/examples.js') ?>
<?php echo javascript_include_tag('extjs/examples/ux/GroupTabPanel.js') ?>
<?php echo javascript_include_tag('extjs/examples/ux/GroupTab.js') ?>
<?php echo javascript_include_tag('extjs/examples/ux/Portal.js') ?>
<?php echo javascript_include_tag('extjs/examples/ux/PortalColumn.js') ?>
<?php echo javascript_include_tag('extjs/examples/ux/Portlet.js') ?>
<?php echo javascript_include_tag('interfaz_admin/form_interfazadmin.js' ) ?>

<?php echo javascript_include_tag('extjs/src/ux/ColumnHeaderGroup.js') ?>

<div id="titulo_interfaz_admin" style="background-color:white;" >
	<div style="float:left;" >
	<img align=left  hspace=10  height=55 width=100  src="<?php if($urlLogo!=''){ echo (url_for('default/index').'../'.$urlLogo);} else {echo(url_for('default/index').'../images/vacio.png');}  ?>" alt="empresa logo"/>
	</div>

	<div  style="padding-left:10px;padding-top:7px;float:left;">
	<font face="arial" size=6 color=#4E79B2><?php echo $nombreEmpresa; ?></font>
	</div>

	<div  style="float:right;width:200px;"  onclick="abrirAcercaDe();"  >
	<img   height=50  src="<?php echo (url_for('default/index').'../images/logo-quantar-color-horizontal.jpg'); ?>" alt="logo Quantar"/>
	</div>
</div>

<?php echo javascript_include_tag('extjs/examples/ux/fileuploadfield/FileUploadField.js' ) ?>
<?php echo javascript_include_tag('examples_extjs/RowEditor.js') ?>
<?php echo javascript_include_tag('examples_extjs/Ext.ux.grid.Search.js') ?>
<?php echo javascript_include_tag('extjs/examples/ux/SpinnerField.js' ) ?>
<?php echo javascript_include_tag('extjs/examples/ux/Spinner.js' ) ?>

