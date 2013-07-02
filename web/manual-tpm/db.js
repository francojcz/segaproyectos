
	searchname = 'resultado.html'
	
	usebannercode=false
	ButtonCode = "<img src='searchbutton.gif' border=0>" 
	
	function templateBody() {
		document.write('<html><head><title>Búsqueda</title><'+
		'script language="Javascript">'+
		'<'+'/'+'script'+'></head><body bgcolor="#ffffff" text="#000000" link="#000099" vlink="#996699" alink="#996699"><left>');
	} 

	function templateEnd() {
		document.write('</td></tr></table></font></center></body></html>');
	}
	


	add("<a href='html/bienvenida.html' target='right'> Bienvenido al sistema</a>",
		"bienvenida tpm telefono contactenos contacto quantar",
		'Bienvenida');
	
	
	add("<a href='html/iniciar/IniciarSesion.html' target='right'>Inicio sesión </a>",
		"session iniciar login clave",
		'Para Inicio de sesión');
	
	//administrador
	add("<a href='html/interfaz_admin/InterfazAdministrador.html' target='right'>Interfaz administrador </a>",
		"administrador partes maestra equipo meta ",
		'Interfaz administrador');
		
	add("<a href='html/interfaz_admin/ManejoEmpleados.html' target='right'>Empleados </a>",
		"usuario empleado login habilitado",
		'Manejo de empleado ');
		
	add("<a href='html/interfaz_admin/ManejoMaquina.html' target='right'>Equipos </a>",
		"maquina equipo certificado inventario computador",
		'Manejo de equipos o máquinas');
	
	add("<a href='html/interfaz_admin/ManejoMetodo.html' target='right'>Métodos </a>",
		"metodo  tiempo corrida estandar curvas de calibración inyecciones",
		'Manejo de métodos');
	
	add("<a href='html/interfaz_admin/MaestraCategoria.html' target='right'>Categoría de evento</a>",
		"categoria evento subevento",
		'Manejo de categorías');
		
	add("<a href='html/interfaz_admin/MaestraEvento.html' target='right'>Evento</a>",
		"categoria evento",
		'Manejo de eventos');
	
	add("<a href='html/interfaz_admin/GestionMetas.html' target='right'>Metas</a>",
		"metas anuales tp tnp tpnp tpp to tf disponibilidad calidad efectividad aprovechamiento",
		'Manejo de metas anuales');
	
	add("<a href='html/interfaz_admin/MaestraTipoIdentificacion.html' target='right'>Indetificación </a>",
		"tipo identificación cedula tarjeta identidad extrangeria",
		'Manejo de tipos de identificación');
	
	add("<a href='html/interfaz_admin/Salir.html' target='right'>Salir </a>",
		"Salir administrador cerrar sesion",
		'Salir de administrador');
		
	//analista
	add("<a href='html/interfaz_analista/InterfazAnalista.html' target='right'>Interfaz analista </a>",
		"analista ingreso corridas analiticas ",
		'Interfaz analista');
	
	
	add("<a href='html/interfaz_analista/IngresarDatos.html' target='right'>Ingresar datos </a>",
		"tiempo entre metodos cambio de modelo fallas corridas analiticas",
		'Ingreso corridas analiticas');
	
	add("<a href='html/interfaz_analista/IngresarEvento.html' target='right'>Ingresar evento </a>",
		"ocurrencia de eventos falla",
		'Ocurrencia de eventos');
		
	add("<a href='html/interfaz_analista/Salir.html' target='right'>Salir </a>",
		"Salir analista cerrar sesion",
		'Salir de analista');
	
	//reportes
	add("<a href='html/interfaz_reporte/InterfazReporte.html' target='right'>Interfaz reporte</a>",
		"reportes ",
		'Interfaz reporte');
		
	add("<a href='html/interfaz_reporte/ReporteDiario.html' target='right'>Reporte diario</a>",
		"reporte diario tiempo perdida indicador muestras inyecciones",
		'Reporte diario');

	add("<a href='html/interfaz_reporte/ReporteMensual.html' target='right'>Reporte diario</a>",
		"reporte mensual tiempo perdida indicador muestras inyecciones",
		'Reporte mensual');
	
	add("<a href='html/interfaz_reporte/ReporteAnual.html' target='right'>Reporte anual</a>",
		"reporte anual tiempo perdida indicador muestras inyecciones",
		'Reporte anual');
		
	add("<a href='html/interfaz_reporte/ReporteEventos.html' target='right'>Reporte eventos</a>",
		"reporte eventos cantidad veces ocurrencia",
		'Reporte eventos');
		
	add("<a href='html/interfaz_reporte/ExportarCorridas.html' target='right'>Exportar corridas</a>",
		"exportar corrida",
		'Exportar corridas');
	
	add("<a href='html/interfaz_reporte/Salir.html' target='right'>Salir</a>",
		"salir reportes cerrar sesion",
		'Salir');
	
