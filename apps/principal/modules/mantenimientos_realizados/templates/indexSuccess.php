<?php echo stylesheet_tag('extjs/ux/css/GroupTab.css') ?>
<?php echo javascript_include_tag('mantenimientos_realizados/mantenimientos_realizados.js') ?>
<?php echo javascript_include_tag('extjs/src/ux/ColumnHeaderGroup.js') ?>
<div id="panel_principal_realizados"></div>
<div id="ventana_flotante" class="x-hidden"></div>
<script>
var nombreEmpresa = '<?php echo $nombreEmpresa; ?>';
var urlLogo = urlPrefix+'../'+'<?php echo $urlLogo; ?>';
var inyeccionesEstandarPromedio = <?php echo $inyeccionesEstandarPromedio; ?>;
</script>
