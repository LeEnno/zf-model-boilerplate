<?php
abstract class Application_Model_Abstract extends Zend_Db_Table_Abstract
{
	/**
	 * Prepend table prefix.
	 *
	 * By default table prefix must be in registry with key 'Db_Prefix'.
	 */
	protected function _setupTableName()
	{
		$registryPrefix = 'Db_Prefix';
		if (Zend_Registry::isRegistered($registryPrefix))
			$this->_name =  Zend_Registry::get($registryPrefix) . $this->_name;
		parent::_setupTableName();
	}

	/**
	 * Get primary key.
	 *
	 * Only works when primary key doesn't consist of more than one column.
	 *
	 * @return mixed
	 */
	public function getPrimaryKey()
	{
		return $this->_primary[1];
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
	 * To check if something was found at all use:
	 *   $res = $this->find($id);
	 *   if (count($res) > 0) { // row exists }
	 *
	 * @param mixed $keys Single value or array of primary keys.
	 * @param string|array $columns Columns to retrieve.
	 * @return Zend_Db_Table_Row|Zend_Db_Table_Rowset_Abstract
	 */
	public function find($keys, $columns = '')
	{
		// do we have limited columns to retrieve?
		if ($columns == '') {

			// if not, simply do a "normal" find on the parent
			$res = parent::find($keys);

		// if we have limited columns
		} else {

			// init a query with columns
			$query = $this->selectColumns($columns);

			// set up primary key as array if single value
			$vals = is_array($keys)
				? $keys
				: array($this->getPrimaryKey() => $keys);

			// append where clauses
			foreach ($vals as $key => $val)
				$query->where("$key = ?", $val);

			// fetch result
			$res = $this->fetchAll($query);
		}

		// if we have multiple keys or an empty result: return rowset
		if (is_array($keys) || $res->count() < 1)
			return $res;

		// return first row of result
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
		$row = $this->fetchRow($query);

		if (count($row) < 1)
			return false;

		foreach ($row as $col) // $row[0][0] won't work
			return $col;
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