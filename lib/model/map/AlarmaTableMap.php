<?php


/**
 * This class defines the structure of the 'alarma' table.
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
class AlarmaTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.AlarmaTableMap';

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
		$this->setName('alarma');
		$this->setPhpName('Alarma');
		$this->setClassname('Alarma');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ALA_CODIGO', 'AlaCodigo', 'INTEGER', true, 11, null);
		$this->addColumn('ALA_CONCEPTO', 'AlaConcepto', 'VARCHAR', false, 200, null);
		$this->addColumn('ALA_CON_CODIGO', 'AlaConCodigo', 'VARCHAR', false, 200, null);
		$this->addColumn('ALA_DESCRIPCION', 'AlaDescripcion', 'VARCHAR', false, 500, null);
		$this->addColumn('ALA_ENVIADO', 'AlaEnviado', 'SMALLINT', false, 6, null);
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

} // AlarmaTableMap
