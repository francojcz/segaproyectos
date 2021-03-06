<?php


/**
 * This class defines the structure of the 'tipoconcepto' table.
 *
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * 02/04/14 18:49:08
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class TipoconceptoTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.TipoconceptoTableMap';

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
		$this->setName('tipoconcepto');
		$this->setPhpName('Tipoconcepto');
		$this->setClassname('Tipoconcepto');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('TIPC_CODIGO', 'TipcCodigo', 'INTEGER', true, 11, null);
		$this->addColumn('TIPC_NOMBRE', 'TipcNombre', 'VARCHAR', false, 30, null);
		$this->addColumn('TIPC_FECHA_REGISTRO', 'TipcFechaRegistro', 'TIMESTAMP', false, null, null);
		$this->addColumn('TIPC_USU_CODIGO', 'TipcUsuCodigo', 'INTEGER', false, 11, null);
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

} // TipoconceptoTableMap
