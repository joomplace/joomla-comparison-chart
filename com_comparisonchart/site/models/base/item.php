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

class BaseItem extends JModelLegacy
{
	public function populateState()
	{
		$app = JFactory::getApplication('site');
		$params = $app->getParams();
		$this->setState('params', $params);

//		$id = JRequest::getVar('id', null, 'get', 'array');
//		$this->setState('item.id', $id);

        $catid = JRequest::getVar('catid', null, 'get', 'int');
        $this->setState('item.catid', $catid);


	}

	/**
	 * Method to get an item object.
	 *
	 * @param	int		An item id
	 * @param	string	Fields for select
	 *
	 * @return	mixed	An item object on success, false on failure.
	 */
	public function getItem($id = false, $select = false)
	{
		if (!isset($this->item)) {
			$id = $id ? $id : $this->getState('item.id');

			if ($id) {
				$db =JFactory::getDBO();
				$query = $this->getItemQuery($id, $select);

				$db->setQuery($query);
				$this->item = $db->loadObject();
			} else {
				return false;
			}
		}
		return $this->item;
	}

	/**
	 * Method to get a category of item.
	 *
	 * @return	mixed	An category object on success, false on failure.
	 */
	public function getCategory()
	{
		if (!isset($this->category)) {
			$catid = $this->getState('item.catid', JRequest::getVar('id', null, 'get', 'int'));

			$db =JFactory::getDBO();
			$query = $db->getQuery(true);

			$query->select('cc.id, cc.title, cc.description, cc.parent_id, cc.level, cc.lft, cc.rgt, cc.params');
			$query->from('#__categories AS cp, #__categories AS cc');
			$query->where('cp.lft BETWEEN cc.lft AND cc.rgt');
			$query->where('cp.id='.$catid);

			$db->setQuery($query);
			$cats = $db->loadObjectList('id');

			$this->category = new JObject($cats[$catid]);
			unset($cats[$catid]);
			$this->category->set('parents', $cats);
			$this->category->params = json_decode($this->category->params);
		}
		return $this->category;
	}

	/**
	 * Method to get a pagination object.
	 *
	 * @return	mixed	A JPagination object for the data set on succes, null on failure.
	 */
	public function getPagination()
	{
		if (empty($this->pagination)) {
			return null;
		}
		return $this->pagination;
	}

	/**
	 * Method to validate an item object.
	 *
	 * @param	array	An user input data
	 * @param 	array	An array of data fields for validate
	 *
	 * @return	mixed	An item object on success, false on failure.
	 */
	public function validate($array, $fields = array())
	{
		if ($fields) {
			foreach ($fields as $field) {
				if (!isset($array[$field]) or !$array[$field]) {
					return false;
				}			
			}
			return true;
		} else {
			foreach ($array as $key) {
				if (!$key) {
					return false;
				}			
			}
			return true;
		}
		//return false;
	}

	/**
	 * Method to store an item object.
	 *
	 * @param	array	An user input data
	 * @param 	array	An array of data fields for validate
	 *
	 * @return	mixed	An item id on success, false on failure.
	 */
	public function store($data)
	{
		$row = $this->getTable();

		if (!$row->bind($data)) {
			return false;
		}

		if (!$row->store()) {
			return false;
		}

		return $row->id;
	}

	/**
	 * Method to store an item object.
	 *
	 * @param	array	An user input data
	 * @param 	array	An array of data fields for validate
	 *
	 * @return	mixed	An item object on success, false on failure.
	 */
	public function save($data, $fields = array())
	{
		if (!$this->validate($data, $fields)) {
			return false;
		}

		$id = $this->store($data);
		if ($id === false) {
			return false;
		}

		return $id;
	}

	/**
	 * Method to delete an item.
	 *
	 * @param	int		An item id
	 *
	 * @return	mixed	An item object on success, false on failure.
	 */
	public function delete($id)
	{
		$row = $this->getTable($this->getState('item.table'));

		if (!$row->delete($id)) {
			return false;
		}
		
		return true;
	}
}