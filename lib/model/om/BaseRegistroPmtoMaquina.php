<?php

/**
 * Base class that represents a row from the 'registro_pmto_maquina' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * 12/13/13 12:15:29
 *
 * @package    lib.model.om
 */
abstract class BaseRegistroPmtoMaquina extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        RegistroPmtoMaquinaPeer
	 */
	protected static $peer;

	/**
	 * The value for the rpm_codigo field.
	 * @var        int
	 */
	protected $rpm_codigo;

	/**
	 * The value for the rpm_maq_codigo field.
	 * @var        int
	 */
	protected $rpm_maq_codigo;

	/**
	 * The value for the rpm_pmto_codigo field.
	 * @var        int
	 */
	protected $rpm_pmto_codigo;

	/**
	 * The value for the rpm_fecha_inicio field.
	 * @var        string
	 */
	protected $rpm_fecha_inicio;

	/**
	 * The value for the rpm_usu_registra field.
	 * @var        int
	 */
	protected $rpm_usu_registra;

	/**
	 * The value for the rpm_fecha_registro field.
	 * @var        string
	 */
	protected $rpm_fecha_registro;

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
	
	const PEER = 'RegistroPmtoMaquinaPeer';

	/**
	 * Get the [rpm_codigo] column value.
	 * 
	 * @return     int
	 */
	public function getRpmCodigo()
	{
		return $this->rpm_codigo;
	}

	/**
	 * Get the [rpm_maq_codigo] column value.
	 * 
	 * @return     int
	 */
	public function getRpmMaqCodigo()
	{
		return $this->rpm_maq_codigo;
	}

	/**
	 * Get the [rpm_pmto_codigo] column value.
	 * 
	 * @return     int
	 */
	public function getRpmPmtoCodigo()
	{
		return $this->rpm_pmto_codigo;
	}

	/**
	 * Get the [optionally formatted] temporal [rpm_fecha_inicio] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getRpmFechaInicio($format = 'Y-m-d')
	{
		if ($this->rpm_fecha_inicio === null) {
			return null;
		}


		if ($this->rpm_fecha_inicio === '0000-00-00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->rpm_fecha_inicio);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->rpm_fecha_inicio, true), $x);
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
	 * Get the [rpm_usu_registra] column value.
	 * 
	 * @return     int
	 */
	public function getRpmUsuRegistra()
	{
		return $this->rpm_usu_registra;
	}

	/**
	 * Get the [optionally formatted] temporal [rpm_fecha_registro] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getRpmFechaRegistro($format = 'Y-m-d')
	{
		if ($this->rpm_fecha_registro === null) {
			return null;
		}


		if ($this->rpm_fecha_registro === '0000-00-00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->rpm_fecha_registro);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->rpm_fecha_registro, true), $x);
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
	 * Set the value of [rpm_codigo] column.
	 * 
	 * @param      int $v new value
	 * @return     RegistroPmtoMaquina The current object (for fluent API support)
	 */
	public function setRpmCodigo($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->rpm_codigo !== $v) {
			$this->rpm_codigo = $v;
			$this->modifiedColumns[] = RegistroPmtoMaquinaPeer::RPM_CODIGO;
		}

		return $this;
	} // setRpmCodigo()

	/**
	 * Set the value of [rpm_maq_codigo] column.
	 * 
	 * @param      int $v new value
	 * @return     RegistroPmtoMaquina The current object (for fluent API support)
	 */
	public function setRpmMaqCodigo($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->rpm_maq_codigo !== $v) {
			$this->rpm_maq_codigo = $v;
			$this->modifiedColumns[] = RegistroPmtoMaquinaPeer::RPM_MAQ_CODIGO;
		}

		return $this;
	} // setRpmMaqCodigo()

	/**
	 * Set the value of [rpm_pmto_codigo] column.
	 * 
	 * @param      int $v new value
	 * @return     RegistroPmtoMaquina The current object (for fluent API support)
	 */
	public function setRpmPmtoCodigo($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->rpm_pmto_codigo !== $v) {
			$this->rpm_pmto_codigo = $v;
			$this->modifiedColumns[] = RegistroPmtoMaquinaPeer::RPM_PMTO_CODIGO;
		}

		return $this;
	} // setRpmPmtoCodigo()

	/**
	 * Sets the value of [rpm_fecha_inicio] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     RegistroPmtoMaquina The current object (for fluent API support)
	 */
	public function setRpmFechaInicio($v)
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

		if ( $this->rpm_fecha_inicio !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->rpm_fecha_inicio !== null && $tmpDt = new DateTime($this->rpm_fecha_inicio)) ? $tmpDt->format('Y-m-d') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->rpm_fecha_inicio = ($dt ? $dt->format('Y-m-d') : null);
				$this->modifiedColumns[] = RegistroPmtoMaquinaPeer::RPM_FECHA_INICIO;
			}
		} // if either are not null

		return $this;
	} // setRpmFechaInicio()

	/**
	 * Set the value of [rpm_usu_registra] column.
	 * 
	 * @param      int $v new value
	 * @return     RegistroPmtoMaquina The current object (for fluent API support)
	 */
	public function setRpmUsuRegistra($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->rpm_usu_registra !== $v) {
			$this->rpm_usu_registra = $v;
			$this->modifiedColumns[] = RegistroPmtoMaquinaPeer::RPM_USU_REGISTRA;
		}

		return $this;
	} // setRpmUsuRegistra()

	/**
	 * Sets the value of [rpm_fecha_registro] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     RegistroPmtoMaquina The current object (for fluent API support)
	 */
	public function setRpmFechaRegistro($v)
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

		if ( $this->rpm_fecha_registro !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->rpm_fecha_registro !== null && $tmpDt = new DateTime($this->rpm_fecha_registro)) ? $tmpDt->format('Y-m-d') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->rpm_fecha_registro = ($dt ? $dt->format('Y-m-d') : null);
				$this->modifiedColumns[] = RegistroPmtoMaquinaPeer::RPM_FECHA_REGISTRO;
			}
		} // if either are not null

		return $this;
	} // setRpmFechaRegistro()

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

			$this->rpm_codigo = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->rpm_maq_codigo = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
			$this->rpm_pmto_codigo = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
			$this->rpm_fecha_inicio = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
			$this->rpm_usu_registra = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->rpm_fecha_registro = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 6; // 6 = RegistroPmtoMaquinaPeer::NUM_COLUMNS - RegistroPmtoMaquinaPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating RegistroPmtoMaquina object", $e);
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
			$con = Propel::getConnection(RegistroPmtoMaquinaPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = RegistroPmtoMaquinaPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
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
			$con = Propel::getConnection(RegistroPmtoMaquinaPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseRegistroPmtoMaquina:delete:pre') as $callable)
			{
			  if (call_user_func($callable, $this, $con))
			  {
			    $con->commit();
			
			    return;
			  }
			}

			if ($ret) {
				RegistroPmtoMaquinaPeer::doDelete($this, $con);
				$this->postDelete($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseRegistroPmtoMaquina:delete:post') as $callable)
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
			$con = Propel::getConnection(RegistroPmtoMaquinaPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseRegistroPmtoMaquina:save:pre') as $callable)
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
				foreach (sfMixer::getCallables('BaseRegistroPmtoMaquina:save:post') as $callable)
				{
				  call_user_func($callable, $this, $con, $affectedRows);
				}

				RegistroPmtoMaquinaPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = RegistroPmtoMaquinaPeer::RPM_CODIGO;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = RegistroPmtoMaquinaPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setRpmCodigo($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += RegistroPmtoMaquinaPeer::doUpdate($this, $con);
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


			if (($retval = RegistroPmtoMaquinaPeer::doValidate($this, $columns)) !== true) {
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
		$pos = RegistroPmtoMaquinaPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getRpmCodigo();
				break;
			case 1:
				return $this->getRpmMaqCodigo();
				break;
			case 2:
				return $this->getRpmPmtoCodigo();
				break;
			case 3:
				return $this->getRpmFechaInicio();
				break;
			case 4:
				return $this->getRpmUsuRegistra();
				break;
			case 5:
				return $this->getRpmFechaRegistro();
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
		$keys = RegistroPmtoMaquinaPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getRpmCodigo(),
			$keys[1] => $this->getRpmMaqCodigo(),
			$keys[2] => $this->getRpmPmtoCodigo(),
			$keys[3] => $this->getRpmFechaInicio(),
			$keys[4] => $this->getRpmUsuRegistra(),
			$keys[5] => $this->getRpmFechaRegistro(),
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
		$pos = RegistroPmtoMaquinaPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setRpmCodigo($value);
				break;
			case 1:
				$this->setRpmMaqCodigo($value);
				break;
			case 2:
				$this->setRpmPmtoCodigo($value);
				break;
			case 3:
				$this->setRpmFechaInicio($value);
				break;
			case 4:
				$this->setRpmUsuRegistra($value);
				break;
			case 5:
				$this->setRpmFechaRegistro($value);
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
		$keys = RegistroPmtoMaquinaPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setRpmCodigo($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setRpmMaqCodigo($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setRpmPmtoCodigo($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setRpmFechaInicio($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setRpmUsuRegistra($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setRpmFechaRegistro($arr[$keys[5]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(RegistroPmtoMaquinaPeer::DATABASE_NAME);

		if ($this->isColumnModified(RegistroPmtoMaquinaPeer::RPM_CODIGO)) $criteria->add(RegistroPmtoMaquinaPeer::RPM_CODIGO, $this->rpm_codigo);
		if ($this->isColumnModified(RegistroPmtoMaquinaPeer::RPM_MAQ_CODIGO)) $criteria->add(RegistroPmtoMaquinaPeer::RPM_MAQ_CODIGO, $this->rpm_maq_codigo);
		if ($this->isColumnModified(RegistroPmtoMaquinaPeer::RPM_PMTO_CODIGO)) $criteria->add(RegistroPmtoMaquinaPeer::RPM_PMTO_CODIGO, $this->rpm_pmto_codigo);
		if ($this->isColumnModified(RegistroPmtoMaquinaPeer::RPM_FECHA_INICIO)) $criteria->add(RegistroPmtoMaquinaPeer::RPM_FECHA_INICIO, $this->rpm_fecha_inicio);
		if ($this->isColumnModified(RegistroPmtoMaquinaPeer::RPM_USU_REGISTRA)) $criteria->add(RegistroPmtoMaquinaPeer::RPM_USU_REGISTRA, $this->rpm_usu_registra);
		if ($this->isColumnModified(RegistroPmtoMaquinaPeer::RPM_FECHA_REGISTRO)) $criteria->add(RegistroPmtoMaquinaPeer::RPM_FECHA_REGISTRO, $this->rpm_fecha_registro);

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
		$criteria = new Criteria(RegistroPmtoMaquinaPeer::DATABASE_NAME);

		$criteria->add(RegistroPmtoMaquinaPeer::RPM_CODIGO, $this->rpm_codigo);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getRpmCodigo();
	}

	/**
	 * Generic method to set the primary key (rpm_codigo column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setRpmCodigo($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of RegistroPmtoMaquina (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setRpmMaqCodigo($this->rpm_maq_codigo);

		$copyObj->setRpmPmtoCodigo($this->rpm_pmto_codigo);

		$copyObj->setRpmFechaInicio($this->rpm_fecha_inicio);

		$copyObj->setRpmUsuRegistra($this->rpm_usu_registra);

		$copyObj->setRpmFechaRegistro($this->rpm_fecha_registro);


		$copyObj->setNew(true);

		$copyObj->setRpmCodigo(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     RegistroPmtoMaquina Clone of current object.
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
	 * @return     RegistroPmtoMaquinaPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new RegistroPmtoMaquinaPeer();
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
	  if (!$callable = sfMixer::getCallable('BaseRegistroPmtoMaquina:'.$method))
	  {
	    throw new sfException(sprintf('Call to undefined method BaseRegistroPmtoMaquina::%s', $method));
	  }
	
	  array_unshift($arguments, $this);
	
	  return call_user_func_array($callable, $arguments);
	}

} // BaseRegistroPmtoMaquina
