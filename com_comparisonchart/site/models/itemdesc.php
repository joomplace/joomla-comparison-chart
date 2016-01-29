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

class ComparisonChartModelItemdesc extends JModelLegacy
{
	public function info() {
        $id = intval(JRequest::getVar('id'));
        $db = JFactory::getDbo();
        $query = "SELECT title, description, image"
                . "\n FROM #__cmp_chart_items WHERE id=" . $id;
        $db->setQuery($query);
        $info = $db->loadObjectList();
        return $info;
    }
}