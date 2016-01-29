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

class ComparisonChartViewItems extends BaseView
{
	function display($tpl = null) 
	{
		$model = $this->getModel();
		$this->chart = $model->getChart();
		$this->items = $model->getItems();
		$this->compare = $model->getCompare();

		$this->pagination = $model->getPagination();

        $this->state        =   $this->get('State');
        $this->pagination   =   $this->get('Pagination');
		$this->params = $model->getState('params');
		$this->Itemid = $itemId	= JRequest::getInt('Itemid');
		if (count($errors = $model->getErrors())) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		parent::display($tpl);
	}
	
	public function setDocument()
	{
		$doc = $this->document;
		$doc->addScript('components'.DIRECTORY_SEPARATOR.'com_comparisonchart'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'items.js');		
	}

	public function setPathway()
	{
		$app =JFactory::getApplication();
		$pathway = $app->getPathway();

        $jsite = new JSite;
        $menu = $jsite->getMenu();
		$active = $menu->getActive();

		if ($active and $active->component == 'com_comparisonchart' and $active->query['view'] != 'items') {
			if ($this->chart) {
				$pathway->addItem($this->chart->title, '');
			}
		}
	}
	
	public function getRatingStars($id, $rating_sum, $rating_count)
	{
		$rating_sum = $rating_sum ? $rating_sum : 0;
		$rating_count = $rating_count ? $rating_count : 0;
	
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'components'.DIRECTORY_SEPARATOR.'com_comparisonchart'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'vote.css');
				
		$document->addScriptDeclaration( "var sfolder = '".JURI::base(true)."';
					var jrate_text=Array('".JTEXT::_('COM_COMAPRISONCHART_RATING_NO_AJAX')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_LOADING')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_THANKS')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_LOGIN')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_RATED')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_VOTES')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_VOTE')."');");

		$live_path = JURI::base();

		$counter =1; 
		$percent = 0;

		if ($rating_count!=0) {$percent = number_format((intval($rating_sum) / intval( $rating_count ))*20,2);}

		$html = "<span class=\"jrate-container\">
				  <ul class=\"jrate-stars-small cmpnodotted\">
					<li id=\"rating_".$id."\" class=\"current-rating\" style=\"width:".(int)$percent."%;\"></li>
				  </ul>
				</span>
				</span>";

		return $html;
	}
}
