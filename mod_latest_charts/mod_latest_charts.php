<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');


$limit = $params->get('count_charts',5);
$list	= modChartsHelper::getList($limit);

if(count($list)) {
        require(JModuleHelper::getLayoutPath('mod_latest_charts'));
}

