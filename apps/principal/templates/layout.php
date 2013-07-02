<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<link rel="shortcut icon" href="/favicon.ico" />

<script type="text/javascript">
	 <?php require_once dirname(__FILE__).'/../../../config/variablesGenerales.php';?>
	 var urlWeb = '<?php echo $urlWeb; ?>';
		urlPrefix = '<?php echo url_for('default/index'); ?>';
	 </script>
	 <?php include_stylesheets() ?>
	 <?php include_javascripts() ?>
</head>
<body>
    <?php echo $sf_content ?>
</body>
</html>
