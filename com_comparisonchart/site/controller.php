<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class ComparisonChartController extends JControllerLegacy
{
	public function display($cachable = false, $urlparams = false)
	{
		$this->default_view = 'charts';

		$doc =JFactory::getDocument();
		$doc->addStyleSheet('components/com_comparisonchart/assets/css/global.css');
		$doc->addScript('components/com_comparisonchart/assets/js/jquery.js');
		 
		parent::display($cachable, $urlparams);
	}
}
