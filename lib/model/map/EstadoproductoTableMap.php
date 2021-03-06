<?php


/**
 * This class defines the structure of the 'estadoproducto' table.
 *
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * 02/26/14 04:34:25
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class EstadoproductoTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.EstadoproductoTableMap';

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
		$this->setName('estadoproducto');
		$this->setPhpName('Estadoproducto');
		$this->setClassname('Estadoproducto');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('EST_PROD_CODIGO', 'EstProdCodigo', 'INTEGER', true, 11, null);
		$this->addColumn('EST_PROD_NOMBRE', 'EstProdNombre', 'VARCHAR', false, 30, null);
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

} // EstadoproductoTableMap
