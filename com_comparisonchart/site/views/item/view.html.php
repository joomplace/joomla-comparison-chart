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

class ComparisonChartViewItem extends BaseView
{
	function display($tpl = null) 
	{
		$model = $this->getModel();
		$this->item = $model->getItem();
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
	}

	public function setPathway()
	{
		$app =JFactory::getApplication();
		$pathway = $app->getPathway();
		
		$menu =JSite::getMenu();
		$active = $menu->getActive();

		if ($active and $active->component == 'com_comparisonchart' and $active->query['view'] != 'items') {
			if (isset($this->chart)) {
				$pathway->addItem($this->chart->title, '');
			}
		}
	}
	
	function getRatingStars($id, $rating_sum, $rating_count)
	{
		$rating_sum = $rating_sum ? $rating_sum : 0;
		$rating_count = $rating_count ? $rating_count : 0;

		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'components/com_comparisonchart/assets/css/vote.css');
		$document->addScript(JURI::root().'components/com_comparisonchart/assets/js/vote.js');

		$document->addScriptDeclaration( "var sfolder = '".JURI::base(true)."';
					var jrate_text=Array('".JTEXT::_('COM_COMAPRISONCHART_RATING_NO_AJAX')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_LOADING')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_THANKS')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_LOGIN')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_RATED')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_VOTES')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_VOTE')."');");

		$live_path = JURI::base();

		$counter =1; 
		$percent = 0;

		if ($rating_count!=0) {$percent = number_format((intval($rating_sum) / intval( $rating_count ))*20,2);}

		$html = "<span class=\"jrate-container\" style=\"margin-top:5px;\">
					  <ul class=\"jrate-stars\">
						<li class=\"current-rating rating-".$id."\" style=\"width:".(int)$percent."%;\"></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(".$id.",1,".$rating_sum.",".$rating_count.",".$counter.");\" title=\"".JTEXT::_('COM_COMAPRISONCHART_RATING_VERY_POOR')."\" class=\"jp-one-star\">1</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(".$id.",2,".$rating_sum.",".$rating_count.",".$counter.");\" title=\"".JTEXT::_('COM_COMAPRISONCHART_RATING_POOR')."\" class=\"jp-two-stars\">2</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(".$id.",3,".$rating_sum.",".$rating_count.",".$counter.");\" title=\"".JTEXT::_('COM_COMAPRISONCHART_RATING_REGULAR')."\" class=\"jp-three-stars\">3</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(".$id.",4,".$rating_sum.",".$rating_count.",".$counter.");\" title=\"".JTEXT::_('COM_COMAPRISONCHART_RATING_GOOD')."\" class=\"jp-four-stars\">4</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(".$id.",5,".$rating_sum.",".$rating_count.",".$counter.");\" title=\"".JTEXT::_('COM_COMAPRISONCHART_RATING_VERY_GOOD')."\" class=\"jp-five-stars\">5</a></li>
					  </ul>
				</span>
					  <span class=\"jrate-count jrate-".$id."\"><small>";
				
				$html .= "( ";
					if($rating_count!=1) {
						$html .= $rating_count." ".JTEXT::_('COM_COMAPRISONCHART_RATING_VOTES');
					} else { 
						$html .= $rating_count." ".JTEXT::_('COM_COMAPRISONCHART_RATING_VOTE');
					}
				$html .=" )";
		$html .="</small></span>";

		return $html;
	}
}
