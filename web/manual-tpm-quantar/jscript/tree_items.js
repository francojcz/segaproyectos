/* 
	the format of the tree definition file is simple,
	you can find specification in the Tigra Menu documentation at:
	http://www.softcomplex.com/products/tigra_menu/docs/items.html	
*/

var TREE_ITEMS = [
	['TPM-QLabs', 'html/bienvenida.html', /*Raíz del Árbol*/
		['Uso de la Ayuda', 'html/ayuda_uso.html'],
		['Inicio sesi&oacute;n', 'html/iniciar/IniciarSesion.html'], 
		['Super Administrador', 'html/interfaz_superadmin/InterfazSuperAdministrador.html',
			['Empresas', 'html/interfaz_superadmin/ManejoEmpresa.html'],
			['Super Usuarios', 'html/interfaz_superadmin/ManejoUsuario.html'],		
			['Empleados', 'html/interfaz_superadmin/ManejoEmpleados.html'],
			['Certificados', 'html/interfaz_superadmin/Certificados.html'],
			['Estado del equipo', 'html/interfaz_superadmin/MaestraEstadoEquipo.html'],
			['Indicador', 'html/interfaz_superadmin/MaestraIndicador.html'],
			['Perfil', 'html/interfaz_superadmin/MaestraPerfil.html'],
			['Tipo de identificaci&oacute;n', 'html/interfaz_superadmin/MaestraTipoIdentificacion.html'],
			['Salir', 'html/interfaz_superadmin/Salir.html']
		],
		['Gracias', 'html/agradecimientos/gracias.html']
	] 
	
]; /*Cierra el Árbol*/

