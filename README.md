zf-model-boilerplate
====================

An abstract class for Models in Zend Framework to inherit from.

Methods
-------

Refer to the class' [PHPDoc](http://en.wikipedia.org/wiki/PHPDoc "PHPDoc - Wikipedia, the free encyclopedia").

```php
/**
 * Prepend table prefix.
 */
protected function _setupTableName() {...}

/**
 * Get primary key.
 *
 * Only works when primary key doesn't consist of more than one column.
 *
 * @return mixed
 */
public function getPrimaryKey() {...}

/**
 * Get name for joined table.
 *
 * This function obsoletes the need to hardcode the name of a joined table.
 * This way we ensure better maintenance.
 *
 * @return string
 */
public function getTableName() {...}

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
public function find($keys, $columns = '') {...}

/**
 * Returns value of first column in first row.
 *
 * @param Zend_Db_Select $query
 * @return mixed Value or false if nothing found.
 */
public function fetchOne($query) {...}

/**
 * Returns value of first column in first row and cast for boolean.
 *
 * @param Zend_Db_Select $query
 * @return bool
 */
public function fetchBool($query) {...}

/**
 * Init select-query with certain columns.
 *
 * @param string|array $cols
 * @return Zend_Db_Table_Select
 */
public function selectColumns($cols) {...}

/**
 * Set up a query where we want to join a second table.
 *
 * @return Zend_Db_Select
 */
public function selectWithJoin() {...}
```