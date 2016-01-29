<?php

/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 * Model class for handling lists of data.
 *
 * @package		Bomba.Components
 * @subpackage	Forum
 */
class BaseList extends JModelLegacy
{
	protected $context = 'com_forum';
	protected $cache = array();

	protected $items;
	protected $pagination;
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return	mixed	An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = 'getItems';

		// Try to load the data from internal storage.
		if (!empty($this->cache[$store])) {
			return $this->cache[$store];
		}

		// Load the list items.
		$db = $this->_db;
		$query	= $this->getListQuery();

		$limit = $this->getState('list.limit');
		$start = $this->getState('list.start');

		$db->setQuery($query, $start, $limit);
		$items = $db->loadObjectList($this->getState('array.key'));

		if (empty($items)) {
			$total = $this->getTotal();
			// overload test
			if ($start > $total) {
				$start = max(0, (int)(ceil($total / $limit) - 1) * $limit);
				$db->setQuery($query, $start, $limit);
				$items = $db->loadObjectList($this->getState('array.key'));
			}

			// if ok update for paginator
			if ($items) {
				$this->setState('list.start', $start);
			}
		}

		// Check for a database error.
		if ($db->getErrorNum()) {
			$this->setError($db->getErrorMsg());
			return false;
		}

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	/**
	 * Method to get a JPagination object for the data set.
	 *
	 * @return	object	A JPagination object for the data set.
	 */
	public function getPagination()
	{
		$store = 'getPagination';

		if (!empty($this->cache[$store])) {
			return $this->cache[$store];
		}

		jimport('joomla.html.pagination');
		$limit = $this->getState('list.limit');

		$page = new JPagination($this->getTotal(), $this->getStart(), $limit);

		// quick ajax hack
		$page->setAdditionalUrlParam('format', 'html');
		$page->setAdditionalUrlParam('layout', 'default');

		$this->cache[$store] = $page;
		return $page;
	}

	/**
	 * Method to get the total number of items for the data set.
	 *
	 * @return	integer	The total number of items available in the data set.
	 */
	public function getTotal()
	{
		$store = 'getTotal';

		if (!empty($this->cache[$store])) {
			return $this->cache[$store];
		}

		// Load the total.
		$db =JFactory::getDBO();
		$query = 'SELECT FOUND_ROWS()';
		$db->setQuery($query);
		$total = $db->loadResult();
		$this->cache[$store] = $total;

		return $total;
	}

	/**
	 * Method to get the starting number of items for the data set.
	 *
	 * @return	integer	The starting number of items available in the data set.
	 */
	public function getStart()
	{
		$store = 'getStart';

		if (!empty($this->cache[$store])) {
			return $this->cache[$store];
		}

		$start = $this->getState('list.start');

		// Add the start to the internal cache.
		$this->cache[$store] = $start;

		return $start;
	}

	/**
	 * Method to get a store id based on the model configuration state.
	 *
	 * @param	string	An identifier string to generate the store id.
	 * @return	string	A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Add the list state to the store id.
		$id	.= ':'.$this->getState('list.start');
		$id	.= ':'.$this->getState('list.limit');
		$id	.= ':'.$this->getState('list.ordering');
		$id	.= ':'.$this->getState('list.direction');

		return md5($this->context.':'.$id);
	}
}