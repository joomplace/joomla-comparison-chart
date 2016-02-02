<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'base'.DIRECTORY_SEPARATOR.'list.php');

class ComparisonChartModelItems extends JModelList
{
	protected $context = 'com_comparisonchart';

	public function populateState($ordering = null, $direction = null)
	{
        $jinput = JFactory::getApplication()->input;
		$app = JFactory::getApplication('site');
		$params = $app->getParams();
		$this->setState('params', $params);

		$id =$jinput->get('id',$jinput->get('chart_id',0,'INT'),'INT');
        if(!$id){
            $id=$jinput->get('chart-id',0,'INT');
        }

		$this->setState('chart.id', $id);

		parent::populateState($ordering, $direction);
	}

	public function getListQuery()
	{
		$this->populateState();
		
        $jinput = JFactory::getApplication()->input;
		$db = $this->getDBO();
		$query = $db->getQuery(true);
		$id = $this->getState('chart.id');
        if(!$id){
            $id=$jinput->get('chart-id',0,'INT');
        }
        if(!$id){
            $id = $jinput->get('id',0,'INT');
        }

		$query->select('SQL_CALC_FOUND_ROWS ci.*');
		$query->from('#__cmp_chart_items AS ci');

		//$query->select('ct.lastip AS rating_lastip, ct.sum AS rating_sum, ct.count as rating_count');
		//$query->join('LEFT', '#__cmp_chart_rating AS ct ON ct.item_id=ci.id');

		if ($id) {
			$query->where('ci.chart_id='.$id);
		}

		$items = $this->getState('item.ids');
		if ($items) {
			if (is_array($items)) {
				$query->where('ci.id IN ('.implode(',', $items).')');
			} else {
				$query->where('ci.id='.$items);
			}
		}

		$query->where('ci.published=1');
		$query->order('`ci`.`ordering` ASC');

        if(!$this->getState('limit')){
            $this->setState('limit', $jinput->get('id',5,'INT'));
        }

        $db->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
       // die(var_dump($query->__toString()));
		return $query;
	}
	
	public function getChart()
	{
		if (!isset($this->chart)) {
			$model = JModelLegacy::getInstance('Charts', 'ComparisonChartModel', array('ignore_request' => true));
			$model->setState('chart.id', $this->getState('chart.id'));
			$this->chart = $model->getItems();
		}
		return $this->chart;
	}
	
	public function getCompare()
	{
		$id = $this->getState('chart.id');
        if(!$id){
            $id=intval(JRequest::getVar('id'));
        }
		return ComparisonChartHelper::getCompare($id);
	}




    }