

<div id="div_login" align="center" ></div>
<center><div id="titulo"  ></div></center>
<center><div id="login" ></div></center>
<center><div id="colaboradores" ></div></center>

<?php echo javascript_include_tag('gears/gears_init.js') ?>
<?php echo javascript_include_tag( 'login/md5.js' ) ?>
<?php echo javascript_include_tag( 'login/form_login.js' ) ?>

<!-- background-color: #4E79B2 !important; esto es azul-->
<style type="text/css"> 
	body {
	    background-image: url(<?php echo(url_for('default/index').'../images/fondo_gris_login.png')?>)  !important;
		background-repeat: repeat-x; 
		top: 5%;
		margin-left: auto;
		margin-right: auto;
	}
</style>