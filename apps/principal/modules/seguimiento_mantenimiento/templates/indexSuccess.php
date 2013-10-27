<?php echo stylesheet_tag('extjs/ux/css/GroupTab.css') ?>
<?php echo javascript_include_tag('seguimiento_mantenimiento/seguimiento_mantenimiento.js') ?>
<?php echo javascript_include_tag('extjs/src/ux/ColumnHeaderGroup.js') ?>
<div id="panel_principal_seguimiento"></div>
<div id="ventana_flotante" class="x-hidden"></div>
<script>
var nombreEmpresa = '<?php echo $nombreEmpresa; ?>';
var urlLogo = urlPrefix+'../'+'<?php echo $urlLogo; ?>';
var inyeccionesEstandarPromedio = <?php echo $inyeccionesEstandarPromedio; ?>;
</script>
