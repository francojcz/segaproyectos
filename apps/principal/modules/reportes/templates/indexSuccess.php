<div id="div_form_inferfaz_reportes"></div>

<?php use_stylesheet('extjs/examples/ux/css/ux-all.css' ) ?>
<?php echo javascript_include_tag('extjs/examples/shared/examples.js') ?>
<?php use_stylesheet('examples_extjs/data-view.css' ) ?>
<?php echo javascript_include_tag('extjs/examples/ux/DataView-more.js') ?>
<?php echo javascript_include_tag('reporte/form_reporte.js' ) ?>
<?php echo javascript_include_tag('extjs/src/ux/ColumnHeaderGroup.js') ?>

<div id="titulo_interfaz_reporte" style="height: 80px;">
    <div style="float: left; padding-top: 15px; padding-left: 15px;">
        <a><font face="arial" size=6 color=#4E79B2>Seguimiento a Proyectos</font></a>
    </div>
	
    <div style="float: right; padding-top: 8px; padding-right: 15px;" >
        <img height=60 src="<?php echo (url_for('default/index').'../images/logo-cinara.jpg'); ?>" alt="Logo" />
    </div>
</div>

<?php echo javascript_include_tag('extjs/examples/ux/SpinnerField.js' ) ?>
<?php echo javascript_include_tag('extjs/examples/ux/Spinner.js' ) ?>
<?php echo javascript_include_tag('../flash/ampie/swfobject.js') ?>
<?php echo javascript_include_tag('../flash/amline/swfobject.js') ?>
