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

class ComparisonchartTableChartprop extends JTable
{
	protected $content = array();
    protected $catid = array();

	function __construct(&$db) {
		parent::__construct('#__cmp_chart_rows', 'id', $db);
	}

	public function load($pk = null, $reset = true) 
	{
		if (parent::load($pk, $reset)) {
			$db = $this->getDBO();

            $query = $db->getQuery(true);
            $query->select('r.*');
            $query->from('#__cmp_chart_rows AS r');
            $query->where('r.chart_id='.$this->id);
            $query->order('r.ordering');
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

		$db->setQuery('DELETE FROM #__cmp_chart_rows WHERE item_id='.$pk);
		if (!$db->execute()) {
			return false;
		}

		return parent::delete($pk);
	}


	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_comparisonchart.chartprop.'.(int)$this->$k;
	}

	protected function _getAssetTitle()
	{
		return $this->greeting;
	}

	protected function _getAssetParentId($table = NULL, $id = NULL)
	{
		$asset = JTable::getInstance('Asset');
		$asset->loadByName('com_comparisonchart');
		return $asset->id;
	}
}