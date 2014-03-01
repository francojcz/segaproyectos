<?php


/**
 * This class defines the structure of the 'asignaciondetiempo' table.
 *
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * 02/26/14 04:34:23
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class AsignaciondetiempoTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.AsignaciondetiempoTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('asignaciondetiempo');
		$this->setPhpName('Asignaciondetiempo');
		$this->setClassname('Asignaciondetiempo');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ADT_CODIGO', 'AdtCodigo', 'INTEGER', true, 11, null);
		$this->addColumn('ADT_MES', 'AdtMes', 'VARCHAR', false, 100, null);
		$this->addColumn('ADT_ANO', 'AdtAno', 'VARCHAR', false, 100, null);
		$this->addColumn('ADT_ASIGNACION', 'AdtAsignacion', 'DOUBLE', false, null, null);
		$this->addColumn('ADT_PERS_CODIGO', 'AdtPersCodigo', 'INTEGER', false, 11, null);
		$this->addColumn('ADT_PRO_CODIGO', 'AdtProCodigo', 'INTEGER', false, 11, null);
		$this->addColumn('ADT_PERS_REG_CODIGO', 'AdtPersRegCodigo', 'INTEGER', false, 11, null);
		$this->addColumn('ADT_FECHA_REGISTRO', 'AdtFechaRegistro', 'TIMESTAMP', false, null, null);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
	} // buildRelations()

	/**
	 * 
	 * Gets the list of behaviors registered for this table
	 * 
	 * @return array Associative array (name => parameters) of behaviors
	 */
	public function getBehaviors()
	{
		return array(
			'symfony' => array('form' => 'true', 'filter' => 'true', ),
			'symfony_behaviors' => array(),
		);
	} // getBehaviors()

} // AsignaciondetiempoTableMap