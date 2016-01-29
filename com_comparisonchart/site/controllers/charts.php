<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controlleradmin');

class ComparisonchartControllerCharts extends JControllerAdmin
{
	public function getModel($name = 'Chart', $prefix = 'ComparisonchartModel') 
	{
			$model = parent::getModel($name, $prefix, array('ignore_request' => true));
			return $model;
	}  
}