<?php echo stylesheet_tag('extjs/ux/css/GroupTab.css') ?>
<?php echo javascript_include_tag('proximos_mantenimientos/proximos_mantenimientos.js') ?>
<?php echo javascript_include_tag('extjs/src/ux/ColumnHeaderGroup.js') ?>
<div id="panel_principal_proximos"></div>
<div id="ventana_flotante" class="x-hidden"></div>
<script>
var nombreEmpresa = '<?php echo $nombreEmpresa; ?>';
var urlLogo = urlPrefix+'../'+'<?php echo $urlLogo; ?>';
var inyeccionesEstandarPromedio = <?php echo $inyeccionesEstandarPromedio; ?>;
</script>
