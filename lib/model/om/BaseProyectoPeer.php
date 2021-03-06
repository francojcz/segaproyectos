<?php

/**
 * Base static class for performing query and update operations on the 'proyecto' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * 02/26/14 04:34:28
 *
 * @package    lib.model.om
 */
abstract class BaseProyectoPeer {

	/** the default database name for this class */
	const DATABASE_NAME = 'propel';

	/** the table name for this class */
	const TABLE_NAME = 'proyecto';

	/** the related Propel class for this table */
	const OM_CLASS = 'Proyecto';

	/** A class that can be returned by this peer. */
	const CLASS_DEFAULT = 'lib.model.Proyecto';

	/** the related TableMap class for this table */
	const TM_CLASS = 'ProyectoTableMap';
	
	/** The total number of columns. */
	const NUM_COLUMNS = 19;

	/** The number of lazy-loaded columns. */
	const NUM_LAZY_LOAD_COLUMNS = 0;

	/** the column name for the PRO_CODIGO field */
	const PRO_CODIGO = 'proyecto.PRO_CODIGO';

	/** the column name for the PRO_CODIGO_CONTABLE field */
	const PRO_CODIGO_CONTABLE = 'proyecto.PRO_CODIGO_CONTABLE';

	/** the column name for the PRO_NOMBRE field */
	const PRO_NOMBRE = 'proyecto.PRO_NOMBRE';

	/** the column name for the PRO_DESCRIPCION field */
	const PRO_DESCRIPCION = 'proyecto.PRO_DESCRIPCION';

	/** the column name for the PRO_VALOR field */
	const PRO_VALOR = 'proyecto.PRO_VALOR';

	/** the column name for the PRO_ACUMULADO_INGRESOS field */
	const PRO_ACUMULADO_INGRESOS = 'proyecto.PRO_ACUMULADO_INGRESOS';

	/** the column name for the PRO_ACUMULADO_EGRESOS field */
	const PRO_ACUMULADO_EGRESOS = 'proyecto.PRO_ACUMULADO_EGRESOS';

	/** the column name for the PRO_FECHA_INICIO field */
	const PRO_FECHA_INICIO = 'proyecto.PRO_FECHA_INICIO';

	/** the column name for the PRO_FECHA_FIN field */
	const PRO_FECHA_FIN = 'proyecto.PRO_FECHA_FIN';

	/** the column name for the PRO_OBSERVACIONES field */
	const PRO_OBSERVACIONES = 'proyecto.PRO_OBSERVACIONES';

	/** the column name for the PRO_PRESUPUESTO_URL field */
	const PRO_PRESUPUESTO_URL = 'proyecto.PRO_PRESUPUESTO_URL';

	/** the column name for the PRO_PERS_CODIGO field */
	const PRO_PERS_CODIGO = 'proyecto.PRO_PERS_CODIGO';

	/** the column name for the PRO_EST_CODIGO field */
	const PRO_EST_CODIGO = 'proyecto.PRO_EST_CODIGO';

	/** the column name for the PRO_FECHA_REGISTRO field */
	const PRO_FECHA_REGISTRO = 'proyecto.PRO_FECHA_REGISTRO';

	/** the column name for the PRO_EJE_CODIGO field */
	const PRO_EJE_CODIGO = 'proyecto.PRO_EJE_CODIGO';

	/** the column name for the PRO_TIPP_CODIGO field */
	const PRO_TIPP_CODIGO = 'proyecto.PRO_TIPP_CODIGO';

	/** the column name for the PRO_OTRO_TIPO field */
	const PRO_OTRO_TIPO = 'proyecto.PRO_OTRO_TIPO';

	/** the column name for the PRO_ELIMINADO field */
	const PRO_ELIMINADO = 'proyecto.PRO_ELIMINADO';

	/** the column name for the PRO_USU_CODIGO field */
	const PRO_USU_CODIGO = 'proyecto.PRO_USU_CODIGO';

	/**
	 * An identiy map to hold any loaded instances of Proyecto objects.
	 * This must be public so that other peer classes can access this when hydrating from JOIN
	 * queries.
	 * @var        array Proyecto[]
	 */
	public static $instances = array();


	// symfony behavior
	
	/**
	 * Indicates whether the current model includes I18N.
	 */
	const IS_I18N = false;

	/**
	 * holds an array of fieldnames
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
	 */
	private static $fieldNames = array (
		BasePeer::TYPE_PHPNAME => array ('ProCodigo', 'ProCodigoContable', 'ProNombre', 'ProDescripcion', 'ProValor', 'ProAcumuladoIngresos', 'ProAcumuladoEgresos', 'ProFechaInicio', 'ProFechaFin', 'ProObservaciones', 'ProPresupuestoUrl', 'ProPersCodigo', 'ProEstCodigo', 'ProFechaRegistro', 'ProEjeCodigo', 'ProTippCodigo', 'ProOtroTipo', 'ProEliminado', 'ProUsuCodigo', ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('proCodigo', 'proCodigoContable', 'proNombre', 'proDescripcion', 'proValor', 'proAcumuladoIngresos', 'proAcumuladoEgresos', 'proFechaInicio', 'proFechaFin', 'proObservaciones', 'proPresupuestoUrl', 'proPersCodigo', 'proEstCodigo', 'proFechaRegistro', 'proEjeCodigo', 'proTippCodigo', 'proOtroTipo', 'proEliminado', 'proUsuCodigo', ),
		BasePeer::TYPE_COLNAME => array (self::PRO_CODIGO, self::PRO_CODIGO_CONTABLE, self::PRO_NOMBRE, self::PRO_DESCRIPCION, self::PRO_VALOR, self::PRO_ACUMULADO_INGRESOS, self::PRO_ACUMULADO_EGRESOS, self::PRO_FECHA_INICIO, self::PRO_FECHA_FIN, self::PRO_OBSERVACIONES, self::PRO_PRESUPUESTO_URL, self::PRO_PERS_CODIGO, self::PRO_EST_CODIGO, self::PRO_FECHA_REGISTRO, self::PRO_EJE_CODIGO, self::PRO_TIPP_CODIGO, self::PRO_OTRO_TIPO, self::PRO_ELIMINADO, self::PRO_USU_CODIGO, ),
		BasePeer::TYPE_FIELDNAME => array ('pro_codigo', 'pro_codigo_contable', 'pro_nombre', 'pro_descripcion', 'pro_valor', 'pro_acumulado_ingresos', 'pro_acumulado_egresos', 'pro_fecha_inicio', 'pro_fecha_fin', 'pro_observaciones', 'pro_presupuesto_url', 'pro_pers_codigo', 'pro_est_codigo', 'pro_fecha_registro', 'pro_eje_codigo', 'pro_tipp_codigo', 'pro_otro_tipo', 'pro_eliminado', 'pro_usu_codigo', ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
	);

	/**
	 * holds an array of keys for quick access to the fieldnames array
	 *
	 * first dimension keys are the type constants
	 * e.g. self::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
	 */
	private static $fieldKeys = array (
		BasePeer::TYPE_PHPNAME => array ('ProCodigo' => 0, 'ProCodigoContable' => 1, 'ProNombre' => 2, 'ProDescripcion' => 3, 'ProValor' => 4, 'ProAcumuladoIngresos' => 5, 'ProAcumuladoEgresos' => 6, 'ProFechaInicio' => 7, 'ProFechaFin' => 8, 'ProObservaciones' => 9, 'ProPresupuestoUrl' => 10, 'ProPersCodigo' => 11, 'ProEstCodigo' => 12, 'ProFechaRegistro' => 13, 'ProEjeCodigo' => 14, 'ProTippCodigo' => 15, 'ProOtroTipo' => 16, 'ProEliminado' => 17, 'ProUsuCodigo' => 18, ),
		BasePeer::TYPE_STUDLYPHPNAME => array ('proCodigo' => 0, 'proCodigoContable' => 1, 'proNombre' => 2, 'proDescripcion' => 3, 'proValor' => 4, 'proAcumuladoIngresos' => 5, 'proAcumuladoEgresos' => 6, 'proFechaInicio' => 7, 'proFechaFin' => 8, 'proObservaciones' => 9, 'proPresupuestoUrl' => 10, 'proPersCodigo' => 11, 'proEstCodigo' => 12, 'proFechaRegistro' => 13, 'proEjeCodigo' => 14, 'proTippCodigo' => 15, 'proOtroTipo' => 16, 'proEliminado' => 17, 'proUsuCodigo' => 18, ),
		BasePeer::TYPE_COLNAME => array (self::PRO_CODIGO => 0, self::PRO_CODIGO_CONTABLE => 1, self::PRO_NOMBRE => 2, self::PRO_DESCRIPCION => 3, self::PRO_VALOR => 4, self::PRO_ACUMULADO_INGRESOS => 5, self::PRO_ACUMULADO_EGRESOS => 6, self::PRO_FECHA_INICIO => 7, self::PRO_FECHA_FIN => 8, self::PRO_OBSERVACIONES => 9, self::PRO_PRESUPUESTO_URL => 10, self::PRO_PERS_CODIGO => 11, self::PRO_EST_CODIGO => 12, self::PRO_FECHA_REGISTRO => 13, self::PRO_EJE_CODIGO => 14, self::PRO_TIPP_CODIGO => 15, self::PRO_OTRO_TIPO => 16, self::PRO_ELIMINADO => 17, self::PRO_USU_CODIGO => 18, ),
		BasePeer::TYPE_FIELDNAME => array ('pro_codigo' => 0, 'pro_codigo_contable' => 1, 'pro_nombre' => 2, 'pro_descripcion' => 3, 'pro_valor' => 4, 'pro_acumulado_ingresos' => 5, 'pro_acumulado_egresos' => 6, 'pro_fecha_inicio' => 7, 'pro_fecha_fin' => 8, 'pro_observaciones' => 9, 'pro_presupuesto_url' => 10, 'pro_pers_codigo' => 11, 'pro_est_codigo' => 12, 'pro_fecha_registro' => 13, 'pro_eje_codigo' => 14, 'pro_tipp_codigo' => 15, 'pro_otro_tipo' => 16, 'pro_eliminado' => 17, 'pro_usu_codigo' => 18, ),
		BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
	);

	/**
	 * Translates a fieldname to another type
	 *
	 * @param      string $name field name
	 * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @param      string $toType   One of the class type constants
	 * @return     string translated name of the field.
	 * @throws     PropelException - if the specified name could not be found in the fieldname mappings.
	 */
	static public function translateFieldName($name, $fromType, $toType)
	{
		$toNames = self::getFieldNames($toType);
		$key = isset(self::$fieldKeys[$fromType][$name]) ? self::$fieldKeys[$fromType][$name] : null;
		if ($key === null) {
			throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(self::$fieldKeys[$fromType], true));
		}
		return $toNames[$key];
	}

	/**
	 * Returns an array of field names.
	 *
	 * @param      string $type The type of fieldnames to return:
	 *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     array A list of field names
	 */

	static public function getFieldNames($type = BasePeer::TYPE_PHPNAME)
	{
		if (!array_key_exists($type, self::$fieldNames)) {
			throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
		}
		return self::$fieldNames[$type];
	}

	/**
	 * Convenience method which changes table.column to alias.column.
	 *
	 * Using this method you can maintain SQL abstraction while using column aliases.
	 * <code>
	 *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
	 *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
	 * </code>
	 * @param      string $alias The alias for the current table.
	 * @param      string $column The column name for current table. (i.e. ProyectoPeer::COLUMN_NAME).
	 * @return     string
	 */
	public static function alias($alias, $column)
	{
		return str_replace(ProyectoPeer::TABLE_NAME.'.', $alias.'.', $column);
	}

	/**
	 * Add all the columns needed to create a new object.
	 *
	 * Note: any columns that were marked with lazyLoad="true" in the
	 * XML schema will not be added to the select list and only loaded
	 * on demand.
	 *
	 * @param      criteria object containing the columns to add.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function addSelectColumns(Criteria $criteria)
	{
		$criteria->addSelectColumn(ProyectoPeer::PRO_CODIGO);
		$criteria->addSelectColumn(ProyectoPeer::PRO_CODIGO_CONTABLE);
		$criteria->addSelectColumn(ProyectoPeer::PRO_NOMBRE);
		$criteria->addSelectColumn(ProyectoPeer::PRO_DESCRIPCION);
		$criteria->addSelectColumn(ProyectoPeer::PRO_VALOR);
		$criteria->addSelectColumn(ProyectoPeer::PRO_ACUMULADO_INGRESOS);
		$criteria->addSelectColumn(ProyectoPeer::PRO_ACUMULADO_EGRESOS);
		$criteria->addSelectColumn(ProyectoPeer::PRO_FECHA_INICIO);
		$criteria->addSelectColumn(ProyectoPeer::PRO_FECHA_FIN);
		$criteria->addSelectColumn(ProyectoPeer::PRO_OBSERVACIONES);
		$criteria->addSelectColumn(ProyectoPeer::PRO_PRESUPUESTO_URL);
		$criteria->addSelectColumn(ProyectoPeer::PRO_PERS_CODIGO);
		$criteria->addSelectColumn(ProyectoPeer::PRO_EST_CODIGO);
		$criteria->addSelectColumn(ProyectoPeer::PRO_FECHA_REGISTRO);
		$criteria->addSelectColumn(ProyectoPeer::PRO_EJE_CODIGO);
		$criteria->addSelectColumn(ProyectoPeer::PRO_TIPP_CODIGO);
		$criteria->addSelectColumn(ProyectoPeer::PRO_OTRO_TIPO);
		$criteria->addSelectColumn(ProyectoPeer::PRO_ELIMINADO);
		$criteria->addSelectColumn(ProyectoPeer::PRO_USU_CODIGO);
	}

	/**
	 * Returns the number of rows matching criteria.
	 *
	 * @param      Criteria $criteria
	 * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
	 * @param      PropelPDO $con
	 * @return     int Number of matching rows.
	 */
	public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
	{
		// we may modify criteria, so copy it first
		$criteria = clone $criteria;

		// We need to set the primary table name, since in the case that there are no WHERE columns
		// it will be impossible for the BasePeer::createSelectSql() method to determine which
		// tables go into the FROM clause.
		$criteria->setPrimaryTableName(ProyectoPeer::TABLE_NAME);

		if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
			$criteria->setDistinct();
		}

		if (!$criteria->hasSelectClause()) {
			ProyectoPeer::addSelectColumns($criteria);
		}

		$criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
		$criteria->setDbName(self::DATABASE_NAME); // Set the correct dbName

		if ($con === null) {
			$con = Propel::getConnection(ProyectoPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}
		// symfony_behaviors behavior
		foreach (sfMixer::getCallables(self::getMixerPreSelectHook(__FUNCTION__)) as $sf_hook)
		{
		  call_user_func($sf_hook, 'BaseProyectoPeer', $criteria, $con);
		}

		// BasePeer returns a PDOStatement
		$stmt = BasePeer::doCount($criteria, $con);

		if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$count = (int) $row[0];
		} else {
			$count = 0; // no rows returned; we infer that means 0 matches.
		}
		$stmt->closeCursor();
		return $count;
	}
	/**
	 * Method to select one object from the DB.
	 *
	 * @param      Criteria $criteria object used to create the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     Proyecto
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
	{
		$critcopy = clone $criteria;
		$critcopy->setLimit(1);
		$objects = ProyectoPeer::doSelect($critcopy, $con);
		if ($objects) {
			return $objects[0];
		}
		return null;
	}
	/**
	 * Method to do selects.
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con
	 * @return     array Array of selected Objects
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doSelect(Criteria $criteria, PropelPDO $con = null)
	{
		return ProyectoPeer::populateObjects(ProyectoPeer::doSelectStmt($criteria, $con));
	}
	/**
	 * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
	 *
	 * Use this method directly if you want to work with an executed statement durirectly (for example
	 * to perform your own object hydration).
	 *
	 * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
	 * @param      PropelPDO $con The connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 * @return     PDOStatement The executed PDOStatement object.
	 * @see        BasePeer::doSelect()
	 */
	public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(ProyectoPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		if (!$criteria->hasSelectClause()) {
			$criteria = clone $criteria;
			ProyectoPeer::addSelectColumns($criteria);
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);
		// symfony_behaviors behavior
		foreach (sfMixer::getCallables(self::getMixerPreSelectHook(__FUNCTION__)) as $sf_hook)
		{
		  call_user_func($sf_hook, 'BaseProyectoPeer', $criteria, $con);
		}


		// BasePeer returns a PDOStatement
		return BasePeer::doSelect($criteria, $con);
	}
	/**
	 * Adds an object to the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doSelect*()
	 * methods in your stub classes -- you may need to explicitly add objects
	 * to the cache in order to ensure that the same objects are always returned by doSelect*()
	 * and retrieveByPK*() calls.
	 *
	 * @param      Proyecto $value A Proyecto object.
	 * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
	 */
	public static function addInstanceToPool(Proyecto $obj, $key = null)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if ($key === null) {
				$key = (string) $obj->getProCodigo();
			} // if key === null
			self::$instances[$key] = $obj;
		}
	}

	/**
	 * Removes an object from the instance pool.
	 *
	 * Propel keeps cached copies of objects in an instance pool when they are retrieved
	 * from the database.  In some cases -- especially when you override doDelete
	 * methods in your stub classes -- you may need to explicitly remove objects
	 * from the cache in order to prevent returning objects that no longer exist.
	 *
	 * @param      mixed $value A Proyecto object or a primary key value.
	 */
	public static function removeInstanceFromPool($value)
	{
		if (Propel::isInstancePoolingEnabled() && $value !== null) {
			if (is_object($value) && $value instanceof Proyecto) {
				$key = (string) $value->getProCodigo();
			} elseif (is_scalar($value)) {
				// assume we've been passed a primary key
				$key = (string) $value;
			} else {
				$e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or Proyecto object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
				throw $e;
			}

			unset(self::$instances[$key]);
		}
	} // removeInstanceFromPool()

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
	 * @return     Proyecto Found object or NULL if 1) no instance exists for specified key or 2) instance pooling has been disabled.
	 * @see        getPrimaryKeyHash()
	 */
	public static function getInstanceFromPool($key)
	{
		if (Propel::isInstancePoolingEnabled()) {
			if (isset(self::$instances[$key])) {
				return self::$instances[$key];
			}
		}
		return null; // just to be explicit
	}
	
	/**
	 * Clear the instance pool.
	 *
	 * @return     void
	 */
	public static function clearInstancePool()
	{
		self::$instances = array();
	}
	
	/**
	 * Method to invalidate the instance pool of all tables related to proyecto
	 * by a foreign key with ON DELETE CASCADE
	 */
	public static function clearRelatedInstancePool()
	{
	}

	/**
	 * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
	 *
	 * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
	 * a multi-column primary key, a serialize()d version of the primary key will be returned.
	 *
	 * @param      array $row PropelPDO resultset row.
	 * @param      int $startcol The 0-based offset for reading from the resultset row.
	 * @return     string A string version of PK or NULL if the components of primary key in result array are all null.
	 */
	public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
	{
		// If the PK cannot be derived from the row, return NULL.
		if ($row[$startcol] === null) {
			return null;
		}
		return (string) $row[$startcol];
	}

	/**
	 * The returned array will contain objects of the default type or
	 * objects that inherit from the default.
	 *
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function populateObjects(PDOStatement $stmt)
	{
		$results = array();
	
		// set the class once to avoid overhead in the loop
		$cls = ProyectoPeer::getOMClass(false);
		// populate the object(s)
		while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
			$key = ProyectoPeer::getPrimaryKeyHashFromRow($row, 0);
			if (null !== ($obj = ProyectoPeer::getInstanceFromPool($key))) {
				// We no longer rehydrate the object, since this can cause data loss.
				// See http://propel.phpdb.org/trac/ticket/509
				// $obj->hydrate($row, 0, true); // rehydrate
				$results[] = $obj;
			} else {
				$obj = new $cls();
				$obj->hydrate($row);
				$results[] = $obj;
				ProyectoPeer::addInstanceToPool($obj, $key);
			} // if key exists
		}
		$stmt->closeCursor();
		return $results;
	}
	/**
	 * Returns the TableMap related to this peer.
	 * This method is not needed for general use but a specific application could have a need.
	 * @return     TableMap
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function getTableMap()
	{
		return Propel::getDatabaseMap(self::DATABASE_NAME)->getTable(self::TABLE_NAME);
	}

	/**
	 * Add a TableMap instance to the database for this peer class.
	 */
	public static function buildTableMap()
	{
	  $dbMap = Propel::getDatabaseMap(BaseProyectoPeer::DATABASE_NAME);
	  if (!$dbMap->hasTable(BaseProyectoPeer::TABLE_NAME))
	  {
	    $dbMap->addTableObject(new ProyectoTableMap());
	  }
	}

	/**
	 * The class that the Peer will make instances of.
	 *
	 * If $withPrefix is true, the returned path
	 * uses a dot-path notation which is tranalted into a path
	 * relative to a location on the PHP include_path.
	 * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
	 *
	 * @param      boolean  Whether or not to return the path wit hthe class name 
	 * @return     string path.to.ClassName
	 */
	public static function getOMClass($withPrefix = true)
	{
		return $withPrefix ? ProyectoPeer::CLASS_DEFAULT : ProyectoPeer::OM_CLASS;
	}

	/**
	 * Method perform an INSERT on the database, given a Proyecto or Criteria object.
	 *
	 * @param      mixed $values Criteria or Proyecto object containing data that is used to create the INSERT statement.
	 * @param      PropelPDO $con the PropelPDO connection to use
	 * @return     mixed The new primary key.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doInsert($values, PropelPDO $con = null)
	{
    // symfony_behaviors behavior
    foreach (sfMixer::getCallables('BaseProyectoPeer:doInsert:pre') as $sf_hook)
    {
      if (false !== $sf_hook_retval = call_user_func($sf_hook, 'BaseProyectoPeer', $values, $con))
      {
        return $sf_hook_retval;
      }
    }

		if ($con === null) {
			$con = Propel::getConnection(ProyectoPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity
		} else {
			$criteria = $values->buildCriteria(); // build Criteria from Proyecto object
		}

		if ($criteria->containsKey(ProyectoPeer::PRO_CODIGO) && $criteria->keyContainsValue(ProyectoPeer::PRO_CODIGO) ) {
			throw new PropelException('Cannot insert a value for auto-increment primary key ('.ProyectoPeer::PRO_CODIGO.')');
		}


		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		try {
			// use transaction because $criteria could contain info
			// for more than one table (I guess, conceivably)
			$con->beginTransaction();
			$pk = BasePeer::doInsert($criteria, $con);
			$con->commit();
		} catch(PropelException $e) {
			$con->rollBack();
			throw $e;
		}

    // symfony_behaviors behavior
    foreach (sfMixer::getCallables('BaseProyectoPeer:doInsert:post') as $sf_hook)
    {
      call_user_func($sf_hook, 'BaseProyectoPeer', $values, $con, $pk);
    }

		return $pk;
	}

	/**
	 * Method perform an UPDATE on the database, given a Proyecto or Criteria object.
	 *
	 * @param      mixed $values Criteria or Proyecto object containing data that is used to create the UPDATE statement.
	 * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function doUpdate($values, PropelPDO $con = null)
	{
    // symfony_behaviors behavior
    foreach (sfMixer::getCallables('BaseProyectoPeer:doUpdate:pre') as $sf_hook)
    {
      if (false !== $sf_hook_retval = call_user_func($sf_hook, 'BaseProyectoPeer', $values, $con))
      {
        return $sf_hook_retval;
      }
    }

		if ($con === null) {
			$con = Propel::getConnection(ProyectoPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		$selectCriteria = new Criteria(self::DATABASE_NAME);

		if ($values instanceof Criteria) {
			$criteria = clone $values; // rename for clarity

			$comparison = $criteria->getComparison(ProyectoPeer::PRO_CODIGO);
			$selectCriteria->add(ProyectoPeer::PRO_CODIGO, $criteria->remove(ProyectoPeer::PRO_CODIGO), $comparison);

		} else { // $values is Proyecto object
			$criteria = $values->buildCriteria(); // gets full criteria
			$selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
		}

		// set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$ret = BasePeer::doUpdate($selectCriteria, $criteria, $con);

    // symfony_behaviors behavior
    foreach (sfMixer::getCallables('BaseProyectoPeer:doUpdate:post') as $sf_hook)
    {
      call_user_func($sf_hook, 'BaseProyectoPeer', $values, $con, $ret);
    }

    return $ret;
	}

	/**
	 * Method to DELETE all rows from the proyecto table.
	 *
	 * @return     int The number of affected rows (if supported by underlying database driver).
	 */
	public static function doDeleteAll($con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(ProyectoPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		$affectedRows = 0; // initialize var to track total num of affected rows
		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			$affectedRows += BasePeer::doDeleteAll(ProyectoPeer::TABLE_NAME, $con);
			// Because this db requires some delete cascade/set null emulation, we have to
			// clear the cached instance *after* the emulation has happened (since
			// instances get re-added by the select statement contained therein).
			ProyectoPeer::clearInstancePool();
			ProyectoPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Method perform a DELETE on the database, given a Proyecto or Criteria object OR a primary key value.
	 *
	 * @param      mixed $values Criteria or Proyecto object or primary key or array of primary keys
	 *              which is used to create the DELETE statement
	 * @param      PropelPDO $con the connection to use
	 * @return     int 	The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
	 *				if supported by native driver or if emulated using Propel.
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	 public static function doDelete($values, PropelPDO $con = null)
	 {
		if ($con === null) {
			$con = Propel::getConnection(ProyectoPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}

		if ($values instanceof Criteria) {
			// invalidate the cache for all objects of this type, since we have no
			// way of knowing (without running a query) what objects should be invalidated
			// from the cache based on this Criteria.
			ProyectoPeer::clearInstancePool();
			// rename for clarity
			$criteria = clone $values;
		} elseif ($values instanceof Proyecto) { // it's a model object
			// invalidate the cache for this single object
			ProyectoPeer::removeInstanceFromPool($values);
			// create criteria based on pk values
			$criteria = $values->buildPkeyCriteria();
		} else { // it's a primary key, or an array of pks
			$criteria = new Criteria(self::DATABASE_NAME);
			$criteria->add(ProyectoPeer::PRO_CODIGO, (array) $values, Criteria::IN);
			// invalidate the cache for this object(s)
			foreach ((array) $values as $singleval) {
				ProyectoPeer::removeInstanceFromPool($singleval);
			}
		}

		// Set the correct dbName
		$criteria->setDbName(self::DATABASE_NAME);

		$affectedRows = 0; // initialize var to track total num of affected rows

		try {
			// use transaction because $criteria could contain info
			// for more than one table or we could emulating ON DELETE CASCADE, etc.
			$con->beginTransaction();
			
			$affectedRows += BasePeer::doDelete($criteria, $con);
			ProyectoPeer::clearRelatedInstancePool();
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Validates all modified columns of given Proyecto object.
	 * If parameter $columns is either a single column name or an array of column names
	 * than only those columns are validated.
	 *
	 * NOTICE: This does not apply to primary or foreign keys for now.
	 *
	 * @param      Proyecto $obj The object to validate.
	 * @param      mixed $cols Column name or array of column names.
	 *
	 * @return     mixed TRUE if all columns are valid or the error message of the first invalid column.
	 */
	public static function doValidate(Proyecto $obj, $cols = null)
	{
		$columns = array();

		if ($cols) {
			$dbMap = Propel::getDatabaseMap(ProyectoPeer::DATABASE_NAME);
			$tableMap = $dbMap->getTable(ProyectoPeer::TABLE_NAME);

			if (! is_array($cols)) {
				$cols = array($cols);
			}

			foreach ($cols as $colName) {
				if ($tableMap->containsColumn($colName)) {
					$get = 'get' . $tableMap->getColumn($colName)->getPhpName();
					$columns[$colName] = $obj->$get();
				}
			}
		} else {

		}

		return BasePeer::doValidate(ProyectoPeer::DATABASE_NAME, ProyectoPeer::TABLE_NAME, $columns);
	}

	/**
	 * Retrieve a single object by pkey.
	 *
	 * @param      int $pk the primary key.
	 * @param      PropelPDO $con the connection to use
	 * @return     Proyecto
	 */
	public static function retrieveByPK($pk, PropelPDO $con = null)
	{

		if (null !== ($obj = ProyectoPeer::getInstanceFromPool((string) $pk))) {
			return $obj;
		}

		if ($con === null) {
			$con = Propel::getConnection(ProyectoPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$criteria = new Criteria(ProyectoPeer::DATABASE_NAME);
		$criteria->add(ProyectoPeer::PRO_CODIGO, $pk);

		$v = ProyectoPeer::doSelect($criteria, $con);

		return !empty($v) > 0 ? $v[0] : null;
	}

	/**
	 * Retrieve multiple objects by pkey.
	 *
	 * @param      array $pks List of primary keys
	 * @param      PropelPDO $con the connection to use
	 * @throws     PropelException Any exceptions caught during processing will be
	 *		 rethrown wrapped into a PropelException.
	 */
	public static function retrieveByPKs($pks, PropelPDO $con = null)
	{
		if ($con === null) {
			$con = Propel::getConnection(ProyectoPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		$objs = null;
		if (empty($pks)) {
			$objs = array();
		} else {
			$criteria = new Criteria(ProyectoPeer::DATABASE_NAME);
			$criteria->add(ProyectoPeer::PRO_CODIGO, $pks, Criteria::IN);
			$objs = ProyectoPeer::doSelect($criteria, $con);
		}
		return $objs;
	}

	// symfony behavior
	
	/**
	 * Returns an array of arrays that contain columns in each unique index.
	 *
	 * @return array
	 */
	static public function getUniqueColumnNames()
	{
	  return array();
	}

	// symfony_behaviors behavior
	
	/**
	 * Returns the name of the hook to call from inside the supplied method.
	 *
	 * @param string $method The calling method
	 *
	 * @return string A hook name for {@link sfMixer}
	 *
	 * @throws LogicException If the method name is not recognized
	 */
	static private function getMixerPreSelectHook($method)
	{
	  if (preg_match('/^do(Select|Count)(Join(All(Except)?)?|Stmt)?/', $method, $match))
	  {
	    return sprintf('BaseProyectoPeer:%s:%1$s', 'Count' == $match[1] ? 'doCount' : $match[0]);
	  }
	
	  throw new LogicException(sprintf('Unrecognized function "%s"', $method));
	}

} // BaseProyectoPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseProyectoPeer::buildTableMap();

