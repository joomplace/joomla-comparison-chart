<?php

/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'baseview.php');
jimport('joomla.html.pagination');

class ComparisonChartViewitemdesc extends BaseView
{
	function display($tpl = null) 
	{
		$model = $this->getModel('Itemdesc');
               
		$this->item = $model->info();
		if (count($errors = $model->getErrors())) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		parent::display($tpl);
	}
	
	public function setDocument()
	{
		$doc = $this->document;	
	}
	
	
}
