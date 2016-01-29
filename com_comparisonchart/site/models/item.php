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

class ComparisonChartModelItem extends JModelLegacy
{
	public function populateState()
	{
		$app = JFactory::getApplication('site');
		$params = $app->getParams();
		$this->setState('params', $params);

		$id = JRequest::getVar('id', null, 'get', 'int');
		$this->setState('item.id', $id);
	}

	public function getItem()
	{
		$db = $this->getDBO();
		$query = $db->getQuery(true);

		$id = $this->getState('item.id');

		$query->select('ci.*');
		$query->from('#__cmp_chart_items AS ci');
		
		$query->select('ct.lastip AS rating_lastip, ct.sum AS rating_sum, ct.count as rating_count');
		$query->join('LEFT', '#__cmp_chart_rating AS ct ON ct.item_id=ci.id');
		
		$query->where('ci.id='.$id);

		$db->setQuery($query);

		$item = $db->loadObject();

		return $item;
	}

	public function getRow()
	{
		$db = $this->getDBO();
		$query = $db->getQuery(true);
$input = JFactory::getApplication()->input;
        $id = $input->get('id',0,'INT');
		//$id = $this->getState('item.id');
		//if (!$id) $id = JRequest::getInt('id',0);
		
		$query->select('cr.*');
		$query->from('#__cmp_chart_rows AS cr');
		$query->where('cr.id='.$id);

		$db->setQuery($query);

		$item = $db->loadObject();

		return $item;
	}

    public function studentsChangeLimit() {
        $db = JFactory::getDbo();
        $chart_id=intval(JRequest::getVar('chart_id'));
        $limit = intval(JRequest::getVar('limit'));
        $query = "SELECT *"
            . "\n FROM #__cmp_chart_items WHERE (chart_id=" . intval($chart_id) . ") LIMIT 0," . $limit;
        $db->setQuery($query);
        $students = $db->loadObjectList();
        return $students;
    }

    public function studentsChangePage() {
        $db = JFactory::getDbo();
        $limit = intval(JRequest::getVar('limit'));
        $newPage = intval(JRequest::getVar('newPage'));
        if ($newPage != 1) {
            $start = $limit * ($newPage - 1);
        } else {
            $start = 0;
        }
        $chart_id = JRequest::getVar('chart_id');
        $query = "SELECT *"
            . "\n FROM #__cmp_chart_items WHERE (chart_id=" . intval($chart_id) . ") LIMIT " . $start . "," . $limit;
        $db->setQuery($query);
        $students = $db->loadObjectList();
        return $students;
    }


}