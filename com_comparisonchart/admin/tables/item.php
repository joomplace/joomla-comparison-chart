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

class ComparisonchartTableItem extends JTable
{
	protected $content = array();
    protected $catid = array();

	function __construct(&$db) {
		parent::__construct('#__cmp_chart_items', 'id', $db);
	}

	public function load($pk = null, $reset = true) 
	{
		if (parent::load($pk, $reset)) {
			$db = $this->getDBO();
            $query = $db->getQuery(true);
         	$query->select('c.cat_id');
         	$query->from('#__cmp_chart_href AS c');
         	$query->where('c.item_id='.$this->id);
         	$db->setQuery($query);
         	$this->catid = $db->loadResultArray();
			$query = $db->getQuery(true);
			$query->select('c.*');
			$query->from('#__cmp_chart_content AS c');
			$query->where('c.item_id='.$this->id);
			$db->setQuery($query);
			$this->content = $db->loadObjectList('row_id');


			return true;
		} else {
			return false;
		}
	}

	public function delete($pk = null)
	{
		$db = $this->getDBO();

		$db->setQuery('DELETE FROM #__cmp_chart_content WHERE item_id='.$pk);
		if (!$db->execute()) {
			return false;
		}

		return parent::delete($pk);
	}

	public function store($updateNulls = false)
	{
		if (!$this->id) {
			$isNew = true;
		}

		$content = $this->content;
		unset($this->content);
//
        if (!empty($this->catid)) {
     				$db = $this->getDBO();
     				$db->setQuery('DELETE FROM `#__cmp_chart_href` WHERE `item_id`='.$this->id);
     				$db->execute();

     				if (!is_array($this->catid)) {
     					$this->catid = array($this->catid);
     				}

     				foreach ($this->catid as $cid) {
     					$query = $db->getQuery(true);
     					$query->insert('#__cmp_chart_href');
     					$query->set('cat_id='.$cid);
     					$query->set('item_id='.$this->id);
     					$db->setQuery($query);
     					$db->execute();
     				}
     			}

		if (isset($this->chart_id))
			{
				if ($isNew) $this->ordering = $this->getNextOrder('chart_id='.(int)$this->chart_id); 
				//else 
				//$this->reorder('chart_id='.(int)$this->chart_id);
			}
		
		if (parent::store()) {
			if (!empty($content)) {
				$db = $this->getDBO();
				$db->setQuery('DELETE FROM #__cmp_chart_content WHERE item_id='.$this->id);
				if (!$db->execute()) {
					return false;
				}
				
				foreach ($content as $k=>$item) {
					$query = $db->getQuery(true);
					
					$query->insert('#__cmp_chart_content');
					$query->set('item_id='.$this->id);
					$query->set('row_id='.$db->Quote($k));
					if (is_object($item)) 
					{
						$query->set('value_data='.$db->Quote($item->value_data));
					    $query->set('value_description = '.$db->Quote($item->value_description));
					}
					else
					{
					
					$query->set('value_data='.$db->Quote($item['value']));
					$query->set('value_description = '.$db->Quote($item['description']));
					}
					
					$db->setQuery($query);
					
					if (!$db->execute()) {
						return false;
					}
					//}
				}
			}			
			return true;
		} else {
			return false;
		}
	}

	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_comparisonchart.item.'.(int)$this->$k;
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