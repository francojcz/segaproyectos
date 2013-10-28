/* 
	the format of the tree definition file is simple,
	you can find specification in the Tigra Menu documentation at:
	http://www.softcomplex.com/products/tigra_menu/docs/items.html	
*/

var TREE_ITEMS = [
	['TPM-QLabs', 'html/bienvenida.html', /*Raíz del Árbol*/
		['Uso de la Ayuda', 'html/ayuda_uso.html'],
		['Inicio sesi&oacute;n', 'html/iniciar/IniciarSesion.html'], 
		['Administrador', 'html/interfaz_admin/InterfazAdministrador.html',
			['Usuarios', 'html/interfaz_admin/ManejoEmpleados.html'],
			['Equipos', 'html/interfaz_admin/ManejoMaquina.html'],
			['Categor&iacute;a evento', 'html/interfaz_admin/MaestraCategoria.html'],
			['Evento', 'html/interfaz_admin/MaestraEvento.html'],
			['M&eacute;todos', 'html/interfaz_admin/ManejoMetodo.html'],
			['Metas', 'html/interfaz_admin/GestionMetas.html'],
			['Tipo de identificaci&oacute;n', 'html/interfaz_admin/MaestraTipoIdentificacion.html'],
			['Salir', 'html/interfaz_admin/Salir.html']
		],
		['Analista', 'html/interfaz_analista/InterfazAnalista.html',
			['Ingreso datos', 'html/interfaz_analista/IngresarDatos.html'],
			['Ingreso ocurrecia de eventos', 'html/interfaz_analista/IngresarEvento.html'],
			['Historial', 'html/interfaz_analista/InterfazHistorial.html'],
			['Salir', 'html/interfaz_analista/Salir.html']
		],
		['Reportes', 'html/interfaz_reporte/InterfazReporte.html',
			['Reporte diario', 'html/interfaz_reporte/ReporteDiario.html'],
			//['Reporte de indicadores', 'html/interfaz_reporte/ReporteIndicadores.html'],
			['Reporte mensual', 'html/interfaz_reporte/ReporteMensual.html'],
			['Reporte anual', 'html/interfaz_reporte/ReporteAnual.html'],
			['Reporte de eventos ocurridos', 'html/interfaz_reporte/ReporteEventos.html'],
			['Exportar datos', 'html/interfaz_reporte/ExportarCorridas.html'],
			
			['Salir', 'html/interfaz_reporte/Salir.html']
		]
		,
		['Gracias', 'html/agradecimientos/gracias.html']
	] 
	
]; /*Cierra el Árbol*/

//==========================================================================================
