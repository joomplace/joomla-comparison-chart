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
require_once( JPATH_COMPONENT.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'comparisonchart.php' );

class ComparisonChartModelCategory extends BaseList
{
	protected $context = 'com_comparisonchart';

	public function populateState()
	{
		$app = JFactory::getApplication('site');
		$params = $app->getParams();
		$this->setState('params', $params);

        $catid = JRequest::getVar('catid', 0, 'get', 'int');
      	$this->setState('chart.catid', $catid);

		$id = JRequest::getVar('id', 0, 'get', 'int');

        if (!$id) {
                    $db = JFactory::getDbo();
                    $query="SELECT `id` FROM `#__cmp_chart_list` WHERE `catid`=".$catid;
                    $db->setQuery($query);
                    $result =  $db->loadResult();
            if ($result) {
                        $id = $result;
                       $this->setState('chart.id', $id);
                        JRequest::setVar('id', $id, 'get');
                   }
        } else {
            $this->setState('chart.id', $id);
            JRequest::setVar('id', $id, 'get');
            $db = JFactory::getDbo();
            $query="SELECT `id` FROM `#__cmp_chart_list` WHERE `catid`=".$catid;
            $db->setQuery($query);
            $result =  $db->loadResult();
    if ($result) {
                $id = $result;
               $this->setState('chart.id', $id);
                JRequest::setVar('id', $id, 'get');
           }
        }

		$limit = $app->getCfg('list_limit');
		$this->setState('list.limit', $limit);

		$start = JRequest::getInt('limitstart', 0);
		$this->setState('list.start', $start);

		parent::populateState();
	}

	public function getListQuery()
	{
		$db = $this->getDBO();
		$query = $db->getQuery(true);

		$query->select('SQL_CALC_FOUND_ROWS ci.*');
		$query->from('#__cmp_chart_items AS ci');

		$id = $this->getState('chart.id');
		if ($id) {
			$query->where('ci.chart_id='.$id);
		}

        $catid = $this->getState('chart.catid');
      		if ($catid) {
                $query->join('LEFT', '#__cmp_chart_href AS hr ON hr.item_id=ci.id');
      			$query->where('hr.cat_id='.$catid);
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

		return $query;
	}

	public function getChart()
	{
		if (!isset($this->chart)) {
			$model = JModelLegacy::getInstance('Charts', 'ComparisonChartModel', array('ignore_request' => true));
			$model->setState('chart.id', $this->getState('chart.id'));
            $this->chart = $model->getItems();
            if ($this->getState('chart.id')!=NULL) {
                $this->chart->flag = true;
            } else {
                $this->chart->flag = false;
            }
		}
		return $this->chart;
	}

	public function getCompare()
	{
		$id = $this->getState('chart.id');
                
		return ComparisonChartHelper::getCompare($id);
	}



    public function getCategory()
   	{
   		if (!isset($this->category)) {
   			//$catid = $this->getState('item.catid', JRequest::getVar('catid', null, 'get', 'int'));
                    $catid = intval($this->getState('item.catid', JRequest::getVar('catid')));

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
    public function getChildren()
   	{
   		if (!isset($this->children))
   		{

            $catid = JRequest::getVar('catid', null, 'get', 'int');
            $this->setState('item.catid', $catid);

   			$model = JModelLegacy::getInstance('Categories', 'ComparisonChartModel', array('ignore_request' => true));

   			$model->setState('filter.id', $this->getState('item.catid'));
   			$model->setState('filter.level', 'cp.level+1');
   			$model->setState('query.select', '(SELECT COUNT(pc.item_id) FROM #__cmp_chart_href AS pc WHERE pc.cat_id=cc.id) AS items_count');
   			$model->setState('array.key', 'id');
   			$this->children = $model->getItems();
   		}

   		return $this->children;
   	}
    public function getChildrenChildren($id) {
        $db =JFactory::getDBO();
        $query = $db->getQuery(true);

        $query = 'SELECT *, CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(":", id, alias) ELSE id END as slug FROM `#__categories` WHERE `parent_id`='.$id;
         $db->SetQuery($query);
        $result = $db->loadObjectList();
//        echo '<pre>';
//        print_r($result);
//        echo '</pre>';
        $query="SELECT `id` FROM `#__cmp_chart_list` WHERE `catid`=".$id;
         return $result;

    }


}