<?php
abstract class Application_Model_Abstract extends Zend_Db_Table_Abstract
{
	/**
	 * Prepend table prefix.
	 */
	protected function _setupTableName()
	{
		$registryPrefix = 'Db_Prefix';
		if (Zend_Registry::isRegistered($registryPrefix))
			$this->_name =  Zend_Registry::get($registryPrefix) . $this->_name;
		parent::_setupTableName();
	}

	/**
	 * Get name for joined table.
	 *
	 * This function obsoletes the need to hardcode the name of a joined table.
	 * This way we ensure better maintenance.
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return $this->_name;
	}

	/**
	 * Returns set of rows, if multiple keys are queried. Otherwise returns the
	 * row identified by the key.
	 *
	 * @param mixed $keys Single value or array of primary keys.
	 * @return Zend_Db_Table_Row|Zend_Db_Table_Rowset_Abstract
	 */
	public function find($keys)
	{
		$res = parent::find($keys);

		if (is_array($keys))
			return $res;
		return $res[0];
	}

	/**
	 * Returns value of first column in first row.
	 *
	 * @param Zend_Db_Select $query
	 * @return mixed Value or false if nothing found.
	 */
	public function fetchOne($query)
	{
		$res = parent::fetchRow($query);
		if (count($res) < 1)
			return false;

		foreach ($res as $col) {
			return $col;
		}
	}

	/**
	 * Returns value of first column in first row and cast for boolean.
	 *
	 * @param Zend_Db_Select $query
	 * @return bool
	 */
	public function fetchBool($query)
	{
		return (bool)$this->fetchOne($query);
	}

	/**
	 * Init select-query with certain columns.
	 *
	 * @param string|array $cols
	 * @return Zend_Db_Table_Select
	 */
	public function selectColumns($cols)
	{
		return $this->select()->from($this, $cols);
	}

	/**
	 * Set up a query where we want to join a second table.
	 *
	 * @return Zend_Db_Select
	 */
	public function selectWithJoin()
	{
		return $this->select()
			->setIntegrityCheck(false);
	}
}