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

class ComparisonchartTableChart extends JTable
{
	protected $rows = array();

	function __construct(&$db) {
		parent::__construct('#__cmp_chart_list', 'id', $db);
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

		$db->setQuery('DELETE FROM `#__cmp_chart_rows` WHERE `chart_id`='.$pk);
		$db->execute();
		

		return parent::delete($pk);
	}

	public function store($updateNulls = false)
	{

		if (!$this->id) {
			$isNew = true;
		}

		if (is_array($this->rows)) {
			$rows = $this->rows;
		}
		unset($this->rows);

		if(empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = JFilterOutput::stringURLSafe($this->alias);

		if (parent::store() && $this->id) {

			if (isset($rows)) {
				$ids = array();
			
				foreach ($rows as $row) {
					
					$trow = JTable::getInstance('Row', 'ComparisonchartTable');
					@$row->chart_id = $this->id;

					if (!$trow->save($row)) {
						return false;						
					}

					$ids[] = $trow->id;
				}
				
			/*	$db = $this->getDBO();
				$db->setQuery('DELETE FROM #__cmp_chart_rows WHERE id NOT IN ('.implode(',', $ids).') AND `chart_id`='.$this->id);
				$db->execute();
			            $categories = implode(';',$this->catid);*/
            //$categories = json_encode($this->catid);
                  /*      $db = $this->getDBO();
                        $db->setQuery('UPDATE #__cmp_chart_list SET catid='.$categories.' WHERE id='.$this->id);
                        $db->execute();*/

			return true;
		} else {
			return false;
		}
	}
    }

	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_comparisonchart.chart.'.(int)$this->$k;
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