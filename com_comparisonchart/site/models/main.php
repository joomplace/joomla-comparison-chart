<?php

/**
* JpObituary component for Joomla 1.6
* @package JpObituary
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'base'.DIRECTORY_SEPARATOR.'item.php');

class ComparisonChartModelMain extends BaseItem
{
	public function getCategories()
    {
   		if (!isset($this->children))
   		{
   			$model = JModelLegacy::getInstance('Categories', 'ComparisonChartModel', array('ignore_request' => true));

   			$model->setState('filter.id', $this->getState('item.catid'));
   			$model->setState('filter.level', '1');
   			$model->setState('query.select', '(SELECT COUNT(pc.item_id) FROM #__cmp_chart_href AS pc WHERE pc.cat_id=cc.id) AS items_count');
   			$model->setState('array.key', 'id');
   			$this->children = $model->getItems();
   		}

   		return $this->children;
   	}
}
