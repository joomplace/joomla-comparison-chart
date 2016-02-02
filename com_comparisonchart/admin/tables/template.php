<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

class ComparisonchartTableTemplate extends JTable
{
	protected $rows = array();

	function __construct(&$db) {
		parent::__construct('#__cmp_chart_templates', 'id', $db);
	}

	public function load($pk = null, $reset = true) 
	{
		if (parent::load($pk, $reset)) {
			$db = $this->getDBO();

			$query = $db->getQuery(true);
			$query->select('t.*');
			$query->from('#__cmp_chart_templates AS t');
			$query->where('t.id='.$this->id);
			$query->order('t.id');
			$db->setQuery($query);
			$this->rows = $db->loadObjectList();

			return true;
		} else {
			return false;
		}
	}

	public function delete($pk = null)
	{
		
		$db = $this->getDBO();
		$db->setQuery('DELETE FROM `#__cmp_chart_templates` WHERE `id`='.$pk);
		$db->execute();

		return parent::delete($pk);
	}

	public function store($updateNulls = false)
	{
		
		if (!$this->id) {
			$isNew = true;
		}
				
		if (!parent::store()) {
				return false;
			}
				
		return true;
	}

	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_comparisonchart.template.'.(int)$this->$k;
	}

	protected function _getAssetTitle()
	{
		return $this->greeting;
	}

	protected function _getAssetParentId(JTable $table = NULL, $id = NULL)
	{
		$asset = JTable::getInstance('Asset');
		$asset->loadByName('com_comparisonchart');
		return $asset->id;
	}
}