<?php

/**
 * Base class that represents a row from the 'documento' table.
 *
 * 
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * 02/26/14 04:34:24
 *
 * @package    lib.model.om
 */
abstract class BaseDocumento extends BaseObject  implements Persistent {


	/**
	 * The Peer class.
	 * Instance provides a convenient way of calling static methods on a class
	 * that calling code may not be able to identify.
	 * @var        DocumentoPeer
	 */
	protected static $peer;

	/**
	 * The value for the doc_codigo field.
	 * @var        int
	 */
	protected $doc_codigo;

	/**
	 * The value for the doc_documento_url field.
	 * @var        string
	 */
	protected $doc_documento_url;

	/**
	 * The value for the doc_fecha_registro field.
	 * @var        string
	 */
	protected $doc_fecha_registro;

	/**
	 * The value for the doc_eliminado field.
	 * @var        int
	 */
	protected $doc_eliminado;

	/**
	 * The value for the doc_tipd_codigo field.
	 * @var        int
	 */
	protected $doc_tipd_codigo;

	/**
	 * The value for the doc_usu_codigo field.
	 * @var        int
	 */
	protected $doc_usu_codigo;

	/**
	 * The value for the doc_pro_codigo field.
	 * @var        int
	 */
	protected $doc_pro_codigo;

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
	
	const PEER = 'DocumentoPeer';

	/**
	 * Get the [doc_codigo] column value.
	 * 
	 * @return     int
	 */
	public function getDocCodigo()
	{
		return $this->doc_codigo;
	}

	/**
	 * Get the [doc_documento_url] column value.
	 * 
	 * @return     string
	 */
	public function getDocDocumentoUrl()
	{
		return $this->doc_documento_url;
	}

	/**
	 * Get the [optionally formatted] temporal [doc_fecha_registro] column value.
	 * 
	 *
	 * @param      string $format The date/time format string (either date()-style or strftime()-style).
	 *							If format is NULL, then the raw DateTime object will be returned.
	 * @return     mixed Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
	 * @throws     PropelException - if unable to parse/validate the date/time value.
	 */
	public function getDocFechaRegistro($format = 'Y-m-d H:i:s')
	{
		if ($this->doc_fecha_registro === null) {
			return null;
		}


		if ($this->doc_fecha_registro === '0000-00-00 00:00:00') {
			// while technically this is not a default value of NULL,
			// this seems to be closest in meaning.
			return null;
		} else {
			try {
				$dt = new DateTime($this->doc_fecha_registro);
			} catch (Exception $x) {
				throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->doc_fecha_registro, true), $x);
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
	 * Get the [doc_eliminado] column value.
	 * 
	 * @return     int
	 */
	public function getDocEliminado()
	{
		return $this->doc_eliminado;
	}

	/**
	 * Get the [doc_tipd_codigo] column value.
	 * 
	 * @return     int
	 */
	public function getDocTipdCodigo()
	{
		return $this->doc_tipd_codigo;
	}

	/**
	 * Get the [doc_usu_codigo] column value.
	 * 
	 * @return     int
	 */
	public function getDocUsuCodigo()
	{
		return $this->doc_usu_codigo;
	}

	/**
	 * Get the [doc_pro_codigo] column value.
	 * 
	 * @return     int
	 */
	public function getDocProCodigo()
	{
		return $this->doc_pro_codigo;
	}

	/**
	 * Set the value of [doc_codigo] column.
	 * 
	 * @param      int $v new value
	 * @return     Documento The current object (for fluent API support)
	 */
	public function setDocCodigo($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->doc_codigo !== $v) {
			$this->doc_codigo = $v;
			$this->modifiedColumns[] = DocumentoPeer::DOC_CODIGO;
		}

		return $this;
	} // setDocCodigo()

	/**
	 * Set the value of [doc_documento_url] column.
	 * 
	 * @param      string $v new value
	 * @return     Documento The current object (for fluent API support)
	 */
	public function setDocDocumentoUrl($v)
	{
		if ($v !== null) {
			$v = (string) $v;
		}

		if ($this->doc_documento_url !== $v) {
			$this->doc_documento_url = $v;
			$this->modifiedColumns[] = DocumentoPeer::DOC_DOCUMENTO_URL;
		}

		return $this;
	} // setDocDocumentoUrl()

	/**
	 * Sets the value of [doc_fecha_registro] column to a normalized version of the date/time value specified.
	 * 
	 * @param      mixed $v string, integer (timestamp), or DateTime value.  Empty string will
	 *						be treated as NULL for temporal objects.
	 * @return     Documento The current object (for fluent API support)
	 */
	public function setDocFechaRegistro($v)
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

		if ( $this->doc_fecha_registro !== null || $dt !== null ) {
			// (nested ifs are a little easier to read in this case)

			$currNorm = ($this->doc_fecha_registro !== null && $tmpDt = new DateTime($this->doc_fecha_registro)) ? $tmpDt->format('Y-m-d H:i:s') : null;
			$newNorm = ($dt !== null) ? $dt->format('Y-m-d H:i:s') : null;

			if ( ($currNorm !== $newNorm) // normalized values don't match 
					)
			{
				$this->doc_fecha_registro = ($dt ? $dt->format('Y-m-d H:i:s') : null);
				$this->modifiedColumns[] = DocumentoPeer::DOC_FECHA_REGISTRO;
			}
		} // if either are not null

		return $this;
	} // setDocFechaRegistro()

	/**
	 * Set the value of [doc_eliminado] column.
	 * 
	 * @param      int $v new value
	 * @return     Documento The current object (for fluent API support)
	 */
	public function setDocEliminado($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->doc_eliminado !== $v) {
			$this->doc_eliminado = $v;
			$this->modifiedColumns[] = DocumentoPeer::DOC_ELIMINADO;
		}

		return $this;
	} // setDocEliminado()

	/**
	 * Set the value of [doc_tipd_codigo] column.
	 * 
	 * @param      int $v new value
	 * @return     Documento The current object (for fluent API support)
	 */
	public function setDocTipdCodigo($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->doc_tipd_codigo !== $v) {
			$this->doc_tipd_codigo = $v;
			$this->modifiedColumns[] = DocumentoPeer::DOC_TIPD_CODIGO;
		}

		return $this;
	} // setDocTipdCodigo()

	/**
	 * Set the value of [doc_usu_codigo] column.
	 * 
	 * @param      int $v new value
	 * @return     Documento The current object (for fluent API support)
	 */
	public function setDocUsuCodigo($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->doc_usu_codigo !== $v) {
			$this->doc_usu_codigo = $v;
			$this->modifiedColumns[] = DocumentoPeer::DOC_USU_CODIGO;
		}

		return $this;
	} // setDocUsuCodigo()

	/**
	 * Set the value of [doc_pro_codigo] column.
	 * 
	 * @param      int $v new value
	 * @return     Documento The current object (for fluent API support)
	 */
	public function setDocProCodigo($v)
	{
		if ($v !== null) {
			$v = (int) $v;
		}

		if ($this->doc_pro_codigo !== $v) {
			$this->doc_pro_codigo = $v;
			$this->modifiedColumns[] = DocumentoPeer::DOC_PRO_CODIGO;
		}

		return $this;
	} // setDocProCodigo()

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

			$this->doc_codigo = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
			$this->doc_documento_url = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
			$this->doc_fecha_registro = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
			$this->doc_eliminado = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
			$this->doc_tipd_codigo = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
			$this->doc_usu_codigo = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
			$this->doc_pro_codigo = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
			$this->resetModified();

			$this->setNew(false);

			if ($rehydrate) {
				$this->ensureConsistency();
			}

			// FIXME - using NUM_COLUMNS may be clearer.
			return $startcol + 7; // 7 = DocumentoPeer::NUM_COLUMNS - DocumentoPeer::NUM_LAZY_LOAD_COLUMNS).

		} catch (Exception $e) {
			throw new PropelException("Error populating Documento object", $e);
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
			$con = Propel::getConnection(DocumentoPeer::DATABASE_NAME, Propel::CONNECTION_READ);
		}

		// We don't need to alter the object instance pool; we're just modifying this instance
		// already in the pool.

		$stmt = DocumentoPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
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
			$con = Propel::getConnection(DocumentoPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		try {
			$ret = $this->preDelete($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseDocumento:delete:pre') as $callable)
			{
			  if (call_user_func($callable, $this, $con))
			  {
			    $con->commit();
			
			    return;
			  }
			}

			if ($ret) {
				DocumentoPeer::doDelete($this, $con);
				$this->postDelete($con);
				// symfony_behaviors behavior
				foreach (sfMixer::getCallables('BaseDocumento:delete:post') as $callable)
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
			$con = Propel::getConnection(DocumentoPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
		}
		
		$con->beginTransaction();
		$isInsert = $this->isNew();
		try {
			$ret = $this->preSave($con);
			// symfony_behaviors behavior
			foreach (sfMixer::getCallables('BaseDocumento:save:pre') as $callable)
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
				foreach (sfMixer::getCallables('BaseDocumento:save:post') as $callable)
				{
				  call_user_func($callable, $this, $con, $affectedRows);
				}

				DocumentoPeer::addInstanceToPool($this);
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
				$this->modifiedColumns[] = DocumentoPeer::DOC_CODIGO;
			}

			// If this object has been modified, then save it to the database.
			if ($this->isModified()) {
				if ($this->isNew()) {
					$pk = DocumentoPeer::doInsert($this, $con);
					$affectedRows += 1; // we are assuming that there is only 1 row per doInsert() which
										 // should always be true here (even though technically
										 // BasePeer::doInsert() can insert multiple rows).

					$this->setDocCodigo($pk);  //[IMV] update autoincrement primary key

					$this->setNew(false);
				} else {
					$affectedRows += DocumentoPeer::doUpdate($this, $con);
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


			if (($retval = DocumentoPeer::doValidate($this, $columns)) !== true) {
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
		$pos = DocumentoPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				return $this->getDocCodigo();
				break;
			case 1:
				return $this->getDocDocumentoUrl();
				break;
			case 2:
				return $this->getDocFechaRegistro();
				break;
			case 3:
				return $this->getDocEliminado();
				break;
			case 4:
				return $this->getDocTipdCodigo();
				break;
			case 5:
				return $this->getDocUsuCodigo();
				break;
			case 6:
				return $this->getDocProCodigo();
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
		$keys = DocumentoPeer::getFieldNames($keyType);
		$result = array(
			$keys[0] => $this->getDocCodigo(),
			$keys[1] => $this->getDocDocumentoUrl(),
			$keys[2] => $this->getDocFechaRegistro(),
			$keys[3] => $this->getDocEliminado(),
			$keys[4] => $this->getDocTipdCodigo(),
			$keys[5] => $this->getDocUsuCodigo(),
			$keys[6] => $this->getDocProCodigo(),
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
		$pos = DocumentoPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
				$this->setDocCodigo($value);
				break;
			case 1:
				$this->setDocDocumentoUrl($value);
				break;
			case 2:
				$this->setDocFechaRegistro($value);
				break;
			case 3:
				$this->setDocEliminado($value);
				break;
			case 4:
				$this->setDocTipdCodigo($value);
				break;
			case 5:
				$this->setDocUsuCodigo($value);
				break;
			case 6:
				$this->setDocProCodigo($value);
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
		$keys = DocumentoPeer::getFieldNames($keyType);

		if (array_key_exists($keys[0], $arr)) $this->setDocCodigo($arr[$keys[0]]);
		if (array_key_exists($keys[1], $arr)) $this->setDocDocumentoUrl($arr[$keys[1]]);
		if (array_key_exists($keys[2], $arr)) $this->setDocFechaRegistro($arr[$keys[2]]);
		if (array_key_exists($keys[3], $arr)) $this->setDocEliminado($arr[$keys[3]]);
		if (array_key_exists($keys[4], $arr)) $this->setDocTipdCodigo($arr[$keys[4]]);
		if (array_key_exists($keys[5], $arr)) $this->setDocUsuCodigo($arr[$keys[5]]);
		if (array_key_exists($keys[6], $arr)) $this->setDocProCodigo($arr[$keys[6]]);
	}

	/**
	 * Build a Criteria object containing the values of all modified columns in this object.
	 *
	 * @return     Criteria The Criteria object containing all modified values.
	 */
	public function buildCriteria()
	{
		$criteria = new Criteria(DocumentoPeer::DATABASE_NAME);

		if ($this->isColumnModified(DocumentoPeer::DOC_CODIGO)) $criteria->add(DocumentoPeer::DOC_CODIGO, $this->doc_codigo);
		if ($this->isColumnModified(DocumentoPeer::DOC_DOCUMENTO_URL)) $criteria->add(DocumentoPeer::DOC_DOCUMENTO_URL, $this->doc_documento_url);
		if ($this->isColumnModified(DocumentoPeer::DOC_FECHA_REGISTRO)) $criteria->add(DocumentoPeer::DOC_FECHA_REGISTRO, $this->doc_fecha_registro);
		if ($this->isColumnModified(DocumentoPeer::DOC_ELIMINADO)) $criteria->add(DocumentoPeer::DOC_ELIMINADO, $this->doc_eliminado);
		if ($this->isColumnModified(DocumentoPeer::DOC_TIPD_CODIGO)) $criteria->add(DocumentoPeer::DOC_TIPD_CODIGO, $this->doc_tipd_codigo);
		if ($this->isColumnModified(DocumentoPeer::DOC_USU_CODIGO)) $criteria->add(DocumentoPeer::DOC_USU_CODIGO, $this->doc_usu_codigo);
		if ($this->isColumnModified(DocumentoPeer::DOC_PRO_CODIGO)) $criteria->add(DocumentoPeer::DOC_PRO_CODIGO, $this->doc_pro_codigo);

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
		$criteria = new Criteria(DocumentoPeer::DATABASE_NAME);

		$criteria->add(DocumentoPeer::DOC_CODIGO, $this->doc_codigo);

		return $criteria;
	}

	/**
	 * Returns the primary key for this object (row).
	 * @return     int
	 */
	public function getPrimaryKey()
	{
		return $this->getDocCodigo();
	}

	/**
	 * Generic method to set the primary key (doc_codigo column).
	 *
	 * @param      int $key Primary key.
	 * @return     void
	 */
	public function setPrimaryKey($key)
	{
		$this->setDocCodigo($key);
	}

	/**
	 * Sets contents of passed object to values from current object.
	 *
	 * If desired, this method can also make copies of all associated (fkey referrers)
	 * objects.
	 *
	 * @param      object $copyObj An object of Documento (or compatible) type.
	 * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
	 * @throws     PropelException
	 */
	public function copyInto($copyObj, $deepCopy = false)
	{

		$copyObj->setDocDocumentoUrl($this->doc_documento_url);

		$copyObj->setDocFechaRegistro($this->doc_fecha_registro);

		$copyObj->setDocEliminado($this->doc_eliminado);

		$copyObj->setDocTipdCodigo($this->doc_tipd_codigo);

		$copyObj->setDocUsuCodigo($this->doc_usu_codigo);

		$copyObj->setDocProCodigo($this->doc_pro_codigo);


		$copyObj->setNew(true);

		$copyObj->setDocCodigo(NULL); // this is a auto-increment column, so set to default value

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
	 * @return     Documento Clone of current object.
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
	 * @return     DocumentoPeer
	 */
	public function getPeer()
	{
		if (self::$peer === null) {
			self::$peer = new DocumentoPeer();
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
	  if (!$callable = sfMixer::getCallable('BaseDocumento:'.$method))
	  {
	    throw new sfException(sprintf('Call to undefined method BaseDocumento::%s', $method));
	  }
	
	  array_unshift($arguments, $this);
	
	  return call_user_func_array($callable, $arguments);
	}

} // BaseDocumento
