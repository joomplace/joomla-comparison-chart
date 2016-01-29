<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
    
defined('_JEXEC') or die;

class modChartsHelper
{
	static function getList(&$limit)
	{
		// Initialise variables.
		$list		= array();
		$db			= JFactory::getDbo();
		$user		= JFactory::getUser();
		$app		= JFactory::getApplication();
		$menu		= $app->getMenu();

		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__cmp_chart_list');
		$query->where('`published`=1');
		$db->setQuery($query,0,$limit);
		$items = $db->loadObjectList();
		return $items;
	}
}
