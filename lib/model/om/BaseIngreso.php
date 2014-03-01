<?php

/**
 * Base class that represents a row from the 'ingreso' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * 02/26/14 04:34:26
 *
 * @package    lib.model.om
 */
abstract class BaseIngreso extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        IngresoPeer
	 */
	protected static $peer;

	/**
	 * The value for the ing_codigo field.
	 * @var        int
	 */
	protected $ing_codigo;

	/**
	 * The value for the ing_concepto field.
	 * @var        string
	 */
	protected $ing_concepto;

	/**
	 * The value for the ing_valor field.
	 * @var        double
	 */
	protected $ing_valor;

	/**
	 * The value for the ing_fecha field.
	 * @var        string
	 */
	protected $ing_fecha;

	/**
	 * The value for the ing_fecha_registro field.
	 * @var        string
	 */
	protected $ing_fecha_registro;

	/**
	 * The value for the ing_eliminado field.
	 * @var        int
	 */
	protected $ing_eliminado;

	/**
	 * The value for the ing_usu_codigo field.
	 * @var        int
	 */
	protected $ing_usu_codigo;

	/**
	 * The value for the ing_pro_codigo field.
	 * @var        int
	 */
	protected $ing_pro_codigo;

	/**
	 * Flag to prevent endless save loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInSave = false;

	/**
	 * Flag to prevent endless validation loop, if this object is referenced
	 * by another object which falls in this transaction.
	 * @var        boolean
	 */
	protected $alreadyInValidation = false;

	// symfony behavior
	
	const PEER = 'IngresoPeer';

	/**
	 * Get the [ing_codigo] column value.
	 * 
	 * @return     int
	 */
	public function getIngCodigo()
	{
		return $this->ing_codigo;
	}

	/**
	 * Get the [ing_concepto] column value.
	 * 
	 * @return     string
	 */
	public function getIngConcepto()
	{
		return $this->ing_concepto;
	}

	/**
	 * Get the [ing_valor] column value.
	 * 
	 * @return     double
	 */
	public function getIngValor()
	{
		return $this->ing_valor;
	}

	/**
	 * Get the [optionally formatted] temporal [ing_fecha] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getIngFecha($format = 'Y-m-d')
	{
		if ($this->ing_fecha === null) {
			return null;
		}


		if ($this->ing_fecha === '0000-00-00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->ing_fecha);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->ing_fecha, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Get the [optionally formatted] temporal [ing_fecha_registro] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getIngFechaRegistro($format = 'Y-m-d H:i:s')
	{
		if ($this->ing_fecha_registro === null) {
			return null;
		}


		if ($this->ing_fecha_registro === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->ing_fecha_registro);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->ing_fecha_registro, true), $x);
			}
		}

		if ($format === null) {
			// Because propel.useDateTimeClass is TRUE, we return a DateTime object.
			return $dt;
		} elseif (strpos($format, '%') !== false) {
			return strftime($format, $dt->format('U'));
		} else {
			return $dt->format($format);
		}
	}

	/**
	 * Get the [ing_eliminado] column value.
	 * 
	 * @return     int
	 */
	public function getIngEliminado()
	{
		return $this->ing_eliminado;
	}

	/**
	 * Get the [ing_usu_codigo] column value.
	 * 
	 * @return     int
	 */
	public function getIngUsuCodigo()
	{
		return $this->ing_usu_codigo;
	}

	/**
	 * Get the [ing_pro_codigo] column value.
	 * 
	 * @return     int
	 */
	public function getIngProCodigo()
	{
		return $this->ing_pro_codigo;
	}

	/**
	 * Set the value of [ing_codigo] column.
	 * 
	 * @param      int $v new value
	 * @return     Ingreso The current object (for fluent API support)
	 */
	public function setIngCodigo($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->ing_codigo !== $v) {
			$this->ing_codigo = $v;
			$this->modifiedColumns[] = IngresoPeer::ING_CODIGO;
		}

		return $this;
	} // setIngCodigo()

	/**
	 * Set the value of [ing_concepto] column.
	 * 
	 * @param      string $v new value
	 * @return     Ingreso The current object (for fluent API support)
	 */
	public function setIngConcepto($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->ing_concepto !== $v) {
			$this->ing_concepto = $v;
			$this->modifiedColumns[] = IngresoPeer::ING_CONCEPTO;
		}

		return $this;
	} // setIngConcepto()

	/**
	 * Set the value of [ing_valor] column.
	 * 
	 * @param      double $v new value
	 * @return     Ingreso The current object (for fluent API support)
	 */
	public function setIngValor($v)
	{
		if ($v !== null) {
			$v = (double) $v;
		}

		if ($this->ing_valor !== $v) {
			$this->ing_valor = $v;
			$this->modifiedColumns[] = IngresoPeer::ING_VALOR;
		}

		return $this;
	} // setIngValor()

	/**
	 * Sets the value of [ing_fecha] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Ingreso The current object (for fluent API support)
	 */
	public function setIngFecha($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->ing_fecha !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->ing_fecha !== null && $tmpDt = new DateTime($this->ing_fecha)) ? $tmpDt->format('Y-m-d') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->ing_fecha = ($dt ? $dt->format('Y-m-d') : null);
				$this->modifiedColumns[] = IngresoPeer::ING_FECHA;
			}
		} // if either are not null

		return $this;
	} // setIngFecha()

	/**
	 * Sets the value of [ing_fecha_registro] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Ingreso The current object (for fluent API support)
	 */
	public function setIngFechaRegistro($v)
	{
		// we treat '' as NULL for temporal objects because DateTime('') == DateTime('now')
		// -- which is unexpected, to say the least.
		if ($v === null || $v === '') {
			$dt = null;
		} elseif ($v instanceof DateTime) {
			$dt = $v;
		} else {
			// some string/numeric value passed; we normalize that so that we can
			// validate it.
			try {
				if (is_numeric($v)) { // if it's a unix timestamp
					$dt = new DateTime('@'.$v, new DateTimeZone('UTC'));
					// We have to explicitly specify and then change the time zone because of a
					// DateTime bug: http://bugs.php.net/bug.php?id=43003
					$dt->setTimeZone(new DateTimeZone(date_default_timezone_get()));
				} else {
					$dt = new DateTime($v);
				}
			} catch (Exception $x) {
				throw new PropelException('Error parsing date/time value: ' . var_export($v, true), $x);
			}
		}

		if ( $this->ing_fecha_registro !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->ing_fecha_registro !== null && $tmpDt = new DateTime($this->ing_fecha_registro)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->ing_fecha_registro = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = IngresoPeer::ING_FECHA_REGISTRO;
			}
		} // if either are not null

		return $this;
	} // setIngFechaRegistro()

	/**
	 * Set the value of [ing_eliminado] column.
	 * 
	 * @param      int $v new value
	 * @return     Ingreso The current object (for fluent API support)
	 */
	public function setIngEliminado($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->ing_eliminado !== $v) {
			$this->ing_eliminado = $v;
			$this->modifiedColumns[] = IngresoPeer::ING_ELIMINADO;
		}

		return $this;
	} // setIngEliminado()

	/**
	 * Set the value of [ing_usu_codigo] column.
	 * 
	 * @param      int $v new value
	 * @return     Ingreso The current object (for fluent API support)
	 */
	public function setIngUsuCodigo($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->ing_usu_codigo !== $v) {
			$this->ing_usu_codigo = $v;
			$this->modifiedColumns[] = IngresoPeer::ING_USU_CODIGO;
		}

		return $this;
	} // setIngUsuCodigo()

	/**
	 * Set the value of [ing_pro_codigo] column.
	 * 
	 * @param      int $v new value
	 * @return     Ingreso The current object (for fluent API support)
	 */
	public function setIngProCodigo($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->ing_pro_codigo !== $v) {
			$this->ing_pro_codigo = $v;
			$this->modifiedColumns[] = IngresoPeer::ING_PRO_CODIGO;
		}

		return $this;
	} // setIngProCodigo()

	/**
	 * Indicates whether the columns in this object are only set to default values.
	 *
	 * This method can be used in conjunction with isModified() to indicate whether an object is both
	 * modified _and_ has some values set which are non-default.
	 *
	 * @return     boolean Whether the columns in this object are only been set with default values.
	 */
	public function hasOnlyDefaultValues()
	{
		// otherwise, everything was equal, so return TRUE
		return true;
	} // hasOnlyDefaultValues()

	/**
	 * Hydrates (populates) the object variables with values from the database resultset.
	 *
	 * An offset (0-based "start column") is specified so that objects can be hydrated
	 * with a subset of the columns in the resultset rows.  This is needed, for example,
	 * for results of JOIN queries where the resultset row includes columns from two or
	 * more tables.
	 *
	 * @param      array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
	 * @param      int $startcol 0-based offset column which indicates which restultset column to start with.
	 * @param      boolean $rehydrate Whether this object is being re-hydrated from the database.
	 * @return     int next starting column
	 * @throws     PropelException  - Any caught Exception will be rewrapped as a PropelException.
	 */
	public function hydrate($row, $startcol = 0, $rehydrate = false)
	{
		try {

			$this->ing_codigo = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->ing_concepto = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->ing_valor = ($row[$startcol + 2] !== null) ? (double) $row[$startcol + 2] : null;
			$this->ing_fecha = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->ing_fecha_registro = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
			$this->ing_eliminado = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->ing_usu_codigo = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
			$this->ing_pro_codigo = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 8; // 8 = IngresoPeer::NUM_COLUMNS - IngresoPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Ingreso object", $e);
		}
	}

	/**
	 * Checks and repairs the internal consistency of the object.
	 *
	 * This method is executed after an already-instantiated object is re-hydrated
	 * from the database.  It exists to check any foreign keys to make sure that
	 * the objects related to the current object are correct based on foreign key.
	 *
	 * You can override this method in the stub class, but you should always invoke
	 * the base method from the overridden method (i.e. parent::ensureConsistency()),
	 * in case your model changes.
	 *
	 * @throws     PropelException
	 */
	public function ensureConsistency()
	{

	} // ensureConsistency

	/**
	 * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
	 *
	 * This will only work if the object has been saved and has a valid primary key set.
	 *
	 * @param      boolean $deep (optional) Whether to also de-associated any related objects.
	 * @param      PropelPDO $con (optional) The PropelPDO connection to use.
	 * @return     void
	 * @throws     PropelException - if this object is deleted, unsaved or doesn't have pk match in db
	 */
	public function reload($deep = false, PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("Cannot reload a deleted object.");
		}

		if ($this->isNew()) {
			throw new PropelException("Cannot reload an unsaved object.");
		}

		if ($con === null) {
			$con = Propel::getConnection(IngresoPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = IngresoPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
		$row = $stmt->fetch(PDO::FETCH_NUM);
		$stmt->closeCursor();
		if (!$row) {
			throw new PropelException('Cannot find matching row in the database to reload object values.');
		}
		$this->hydrate($row, 0, true); // rehydrate

		if ($deep) {  // also de-associate any related objects?

		} // if (deep)
	}

	/**
	 * Removes this object from datastore and sets delete attribute.
	 *
	 * @param      PropelPDO $con
	 * @return     void
	 * @throws     PropelException
	 * @see        BaseObject::setDeleted()
	 * @see        BaseObject::isDeleted()
	 */
	public function delete(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("This object has already been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(IngresoPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseIngreso:delete:pre') as $callable)
			{
			  if (call_user_func($callable, $this, $con))
			  {
			    $con->commit();
			
			    return;
			  }
			}

			if ($ret) {
				IngresoPeer::doDelete($this, $con);
				$this->postDelete($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseIngreso:delete:post') as $callable)
				{
				  call_user_func($callable, $this, $con);
				}

				$this->setDeleted(true);
				$con->commit();
			} else {
				$con->commit();
			}
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Persists this object to the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All modified related objects will also be persisted in the doSave()
	 * method.  This method wraps all precipitate database operations in a
	 * single transaction.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        doSave()
	 */
	public function save(PropelPDO $con = null)
	{
		if ($this->isDeleted()) {
			throw new PropelException("You cannot save an object that has been deleted.");
		}

		if ($con === null) {
			$con = Propel::getConnection(IngresoPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseIngreso:save:pre') as $callable)
			{
			  if (is_integer($affectedRows = call_user_func($callable, $this, $con)))
			  {
			    $con->commit();
			
			    return $affectedRows;
			  }
			}

			if ($isInsert) {
				$ret = $ret && $this->preInsert($con);
			} else {
				$ret = $ret && $this->preUpdate($con);
			}
			if ($ret) {
				$affectedRows = $this->doSave($con);
				if ($isInsert) {
					$this->postInsert($con);
				} else {
					$this->postUpdate($con);
				}
				$this->postSave($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseIngreso:save:post') as $callable)
				{
				  call_user_func($callable, $this, $con, $affectedRows);
				}

				IngresoPeer::addInstanceToPool($this);
			} else {
				$affectedRows = 0;
			}
			$con->commit();
			return $affectedRows;
		} catch (PropelException $e) {
			$con->rollBack();
			throw $e;
		}
	}

	/**
	 * Performs the work of inserting or updating the row in the database.
	 *
	 * If the object is new, it inserts it; otherwise an update is performed.
	 * All related objects are also updated in this method.
	 *
	 * @param      PropelPDO $con
	 * @return     int The number of rows affected by this insert/update and any referring fk objects' save() operations.
	 * @throws     PropelException
	 * @see        save()
	 */
	protected function doSave(PropelPDO $con)
	{
		$affectedRows = 0; // initialize var to track total num of affected rows
		if (!$this->alreadyInSave) {
			$this->alreadyInSave = true;

			if ($this->isNew() ) {
				$this->modifiedColumns[] = IngresoPeer::ING_CODIGO;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = IngresoPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setIngCodigo($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += IngresoPeer::doUpdate($this, $con);
				}

				$this->resetModified(); // [HL] After being saved an object is no longer 'modified'
			}

			$this->alreadyInSave = false;

		}
		return $affectedRows;
	} // doSave()

	/**
	 * Array of ValidationFailed objects.
	 * @var        array ValidationFailed[]
	 */
	protected $validationFailures = array();

	/**
	 * Gets any ValidationFailed objects that resulted from last call to validate().
	 *
	 *
	 * @return     array ValidationFailed[]
	 * @see        validate()
	 */
	public function getValidationFailures()
	{
		return $this->validationFailures;
	}

	/**
	 * Validates the objects modified field values and all objects related to this table.
	 *
	 * If $columns is either a column name or an array of column names
	 * only those columns are validated.
	 *
	 * @param      mixed $columns Column name or an array of column names.
	 * @return     boolean Whether all columns pass validation.
	 * @see        doValidate()
	 * @see        getValidationFailures()
	 */
	public function validate($columns = null)
	{
		$res = $this->doValidate($columns);
		if ($res === true) {
			$this->validationFailures = array();
			return true;
		} else {
			$this->validationFailures = $res;
			return false;
		}
	}

	/**
	 * This function performs the validation work for complex object models.
	 *
	 * In addition to checking the current object, all related objects will
	 * also be validated.  If all pass then <code>true</code> is returned; otherwise
	 * an aggreagated array of ValidationFailed objects will be returned.
	 *
	 * @param      array $columns Array of column names to validate.
	 * @return     mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objets otherwise.
	 */
	protected function doValidate($columns = null)
	{
		if (!$this->alreadyInValidation) {
			$this->alreadyInValidation = true;
			$retval = null;

			$failureMap = array();


			if (($retval = IngresoPeer::doValidate($this, $columns)) !== true) {
				$failureMap = array_merge($failureMap, $retval);
			}



			$this->alreadyInValidation = false;
		}

		return (!empty($failureMap) ? $failureMap : true);
	}

	/**
	 * Retrieves a field from the object by name passed in as a string.
	 *
	 * @param      string $name name
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     mixed Value of field.
	 */
	public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = IngresoPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		$field = $this->getByPosition($pos);
		return $field;
	}

	/**
	 * Retrieves a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @return     mixed Value of field at $pos
	 */
	public function getByPosition($pos)
	{
		switch($pos) {
			case 0:
				return $this->getIngCodigo();
				break;
			case 1:
				return $this->getIngConcepto();
				break;
			case 2:
				return $this->getIngValor();
				break;
			case 3:
				return $this->getIngFecha();
				break;
			case 4:
				return $this->getIngFechaRegistro();
				break;
			case 5:
				return $this->getIngEliminado();
				break;
			case 6:
				return $this->getIngUsuCodigo();
				break;
			case 7:
				return $this->getIngProCodigo();
				break;
			default:
				return null;
				break;
		} // switch()
	}

	/**
	 * Exports the object as an array.
	 *
	 * You can specify the key type of the array by passing one of the class
	 * type constants.
	 *
	 * @param      string $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                        BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. Defaults to BasePeer::TYPE_PHPNAME.
	 * @param      boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns.  Defaults to TRUE.
	 * @return     an associative array containing the field names (as keys) and field values
	 */
	public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true)
	{
		$keys = IngresoPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getIngCodigo(),
			$keys[1] => $this->getIngConcepto(),
			$keys[2] => $this->getIngValor(),
			$keys[3] => $this->getIngFecha(),
			$keys[4] => $this->getIngFechaRegistro(),
			$keys[5] => $this->getIngEliminado(),
			$keys[6] => $this->getIngUsuCodigo(),
			$keys[7] => $this->getIngProCodigo(),
		);
		return $result;
	}

	/**
	 * Sets a field from the object by name passed in as a string.
	 *
	 * @param      string $name peer name
	 * @param      mixed $value field value
	 * @param      string $type The type of fieldname the $name is of:
	 *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
	 *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
	 * @return     void
	 */
	public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
	{
		$pos = IngresoPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
		return $this->setByPosition($pos, $value);
	}

	/**
	 * Sets a field from the object by Position as specified in the xml schema.
	 * Zero-based.
	 *
	 * @param      int $pos position in xml schema
	 * @param      mixed $value field value
	 * @return     void
	 */
	public function setByPosition($pos, $value)
	{
		switch($pos) {
			case 0:
				$this->setIngCodigo($value);
				break;
			case 1:
				$this->setIngConcepto($value);
				break;
			case 2:
				$this->setIngValor($value);
				break;
			case 3:
				$this->setIngFecha($value);
				break;
			case 4:
				$this->setIngFechaRegistro($value);
				break;
			case 5:
				$this->setIngEliminado($value);
				break;
			case 6:
				$this->setIngUsuCodigo($value);
				break;
			case 7:
				$this->setIngProCodigo($value);
				break;
		} // switch()
	}

	/**
	 * Populates the object using an array.
	 *
	 * This is particularly useful when populating an object from one of the
	 * request arrays (e.g. $_POST).  This method goes through the column
	 * names, checking to see whether a matching key exists in populated
	 * array. If so the setByName() method is called for that column.
	 *
	 * You can specify the key type of the array by additionally passing one
	 * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
	 * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
	 * The default key type is the column's phpname (e.g. 'AuthorId')
	 *
	 * @param      array  $arr     An array to populate the object from.
	 * @param      string $keyType The type of keys the array uses.
	 * @return     void
	 */
	public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
	{
		$keys = IngresoPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setIngCodigo($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setIngConcepto($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setIngValor($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setIngFecha($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setIngFechaRegistro($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setIngEliminado($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setIngUsuCodigo($arr[$keys[6]]);
		if (array_key_exists($keys[7], $arr)) $this->setIngProCodigo($arr[$keys[7]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(IngresoPeer::DATABASE_NAME);

		if ($this->isColumnModified(IngresoPeer::ING_CODIGO)) $criteria->add(IngresoPeer::ING_CODIGO, $this->ing_codigo);
		if ($this->isColumnModified(IngresoPeer::ING_CONCEPTO)) $criteria->add(IngresoPeer::ING_CONCEPTO, $this->ing_concepto);
		if ($this->isColumnModified(IngresoPeer::ING_VALOR)) $criteria->add(IngresoPeer::ING_VALOR, $this->ing_valor);
		if ($this->isColumnModified(IngresoPeer::ING_FECHA)) $criteria->add(IngresoPeer::ING_FECHA, $this->ing_fecha);
		if ($this->isColumnModified(IngresoPeer::ING_FECHA_REGISTRO)) $criteria->add(IngresoPeer::ING_FECHA_REGISTRO, $this->ing_fecha_registro);
		if ($this->isColumnModified(IngresoPeer::ING_ELIMINADO)) $criteria->add(IngresoPeer::ING_ELIMINADO, $this->ing_eliminado);
		if ($this->isColumnModified(IngresoPeer::ING_USU_CODIGO)) $criteria->add(IngresoPeer::ING_USU_CODIGO, $this->ing_usu_codigo);
		if ($this->isColumnModified(IngresoPeer::ING_PRO_CODIGO)) $criteria->add(IngresoPeer::ING_PRO_CODIGO, $this->ing_pro_codigo);

		return $criteria;
	}

	/**
	 * Builds a Criteria object containing the primary key for this object.
	 *
	 * Unlike buildCriteria() this method includes the primary key values regardless
	 * of whether or not they have been modified.
	 *
	 * @return     Criteria The Criteria object containing value(s) for primary key(s).
	 */
	public function buildPkeyCriteria()
	{
		$criteria = new Criteria(IngresoPeer::DATABASE_NAME);

		$criteria->add(IngresoPeer::ING_CODIGO, $this->ing_codigo);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getIngCodigo();
	}

	/**
	 * Generic method to set the primary key (ing_codigo column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setIngCodigo($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Ingreso (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setIngConcepto($this->ing_concepto);

		$copyObj->setIngValor($this->ing_valor);

		$copyObj->setIngFecha($this->ing_fecha);

		$copyObj->setIngFechaRegistro($this->ing_fecha_registro);

		$copyObj->setIngEliminado($this->ing_eliminado);

		$copyObj->setIngUsuCodigo($this->ing_usu_codigo);

		$copyObj->setIngProCodigo($this->ing_pro_codigo);


		$copyObj->setNew(true);

		$copyObj->setIngCodigo(NULL); // this is a auto-increment column, so set to default value

	}

	/**
	 * Makes a copy of this object that will be inserted as a new row in table when saved.
	 * It creates a new object filling in the simple attributes, but skipping any primary
	 * keys that are defined for the table.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @return     Ingreso Clone of current object.
	 * @throws     PropelException
	 */
	public function copy($deepCopy = false)
	{
		// we use get_class(), because this might be a subclass
		$clazz = get_class($this);
		$copyObj = new $clazz();
		$this->copyInto($copyObj, $deepCopy);
		return $copyObj;
	}

	/**
	 * Returns a peer instance associated with this om.
	 *
	 * Since Peer classes are not to have any instance attributes, this method returns the
	 * same instance for all member of this class. The method could therefore
	 * be static, but this would prevent one from overriding the behavior.
	 *
	 * @return     IngresoPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new IngresoPeer();
		}
		return self::$peer;
	}

	/**
	 * Resets all collections of referencing foreign keys.
	 *
	 * This method is a user-space workaround for PHP's inability to garbage collect objects
	 * with circular references.  This is currently necessary when using Propel in certain
	 * daemon or large-volumne/high-memory operations.
	 *
	 * @param      boolean $deep Whether to also clear the references on all associated objects.
	 */
	public function clearAllReferences($deep = false)
	{
		if ($deep) {
		} // if ($deep)

	}

	// symfony_behaviors behavior
	
	/**
	 * Calls methods defined via {@link sfMixer}.
	 */
	public function __call($method, $arguments)
	{
	  if (!$callable = sfMixer::getCallable('BaseIngreso:'.$method))
	  {
	    throw new sfException(sprintf('Call to undefined method BaseIngreso::%s', $method));
	  }
	
	  array_unshift($arguments, $this);
	
	  return call_user_func_array($callable, $arguments);
	}

} // BaseIngreso
