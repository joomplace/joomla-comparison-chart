<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
jimport('joomla.form.helper');

class JFormFieldChartContent extends JFormField
{
	public $type = 'charttable';

	public function getInput()
	{
		$chart_id = (int)$this->form->getField('chart_id')->value;
		
		if (!$chart_id) {
			return '<div class="error" >'.JText::_('COM_COMPARISONCHART_ERROR_NO_CHART_CONT').'</div>';
		}

		if ($chart_id) {
			$db =JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('r.*');
			$query->from('#__cmp_chart_rows AS r');
			$query->where('r.chart_id='.$chart_id);
			$query->order('r.ordering ASC');
			$db->setQuery($query);
			
			$rows = $db->loadObjectList();
			
			$db->setQuery("SELECT `css` FROM `#__cmp_chart_list` WHERE `id`=".$chart_id);
			$chartcss = $db->loadResult();
			$db->setQuery("SELECT * FROM #__cmp_chart_templates WHERE id=".$chart_id);
			$template_settings = $db->loadObject();
//            $db->setQuery("SELECT `catid` FROM #__cmp_chart_list WHERE id=".$chartcss);
//            $cid = $db->loadResult();
//            /* Categories for this item*/
//
//            jimport( 'joomla.application.categories' );
//            $cat = JCategories::getInstance('comparisonchart');
//            $cat1 = $cat->get($cid);
//            $cats = $cat1->getChildren();
//            $cat_array = array();
//            echo '<label id="jform_catid-lbl" for="jform_catid" class="hasTip required" title="" aria-invalid="false">Category<span class="star">&nbsp;*</span></label>';
//            echo '<select class="inputbox required" id="jform_catid" name="jform[catid][]" size="10" multiple="multiple" aria-required="true" required="required">';
//            echo '<option value="'.$cat1->id.'">'.$cat1->title.'</option>';
//            foreach ($cats as $c) {
//                $no_child = false;
//                echo '<option value="'.$c->id.'">'.str_repeat('-',$c->level-1) . $c->title . '</option>';
//                while (!$no_child) {
//                    if (is_object($c)) {
//                    if ($c->hasChildren()) {
//                        $c = $c->getChildren(true);
//                        foreach ($c as $child) {
//                            echo '<option value="'.$child->id.'">'.str_repeat('-',$child->level-1) .  $child->title . '</option>';
//                        }
//                    } else {
//                        $no_child = true;
//                    }
//                    } else {
//                        $no_child = true;
//
//                    }
//                }
//            }
//            echo '</select>';
            }

		ob_start();
		include(dirname(__FILE__).DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'chartcontent.tmpl.php');
		$input = ob_get_contents();
		ob_get_clean();
		return $input;
	}

	public function getLabel()
	{
		return '';
	}
	
	function getRatingStars($rid)
	{
        $rating_sum = 0;
        $rating_count = 0;

		$id = (int)$this->form->getField('id')->value;
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('ct.sum, ct.count ');
		$query->from('#__cmp_chart_rating AS ct');
		$query->where('item_id='.(int)$id);
		$query->where('row_id='.(int)$rid);
		$db->setQuery($query);
		$rate = $db->loadObject();

        if($rate){
	    	$rating_sum = $rate->sum ? $rate->sum : 0;
		    $rating_count = $rate->count ? $rate->count : 0;
        }

		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'components/com_comparisonchart/assets/css/vote.css');

		$document->addScriptDeclaration( "var sfolder = '".JURI::base(true)."';
					var jrate_text=Array('".JTEXT::_('COM_COMAPRISONCHART_RATING_NO_AJAX')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_LOADING')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_THANKS')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_LOGIN')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_RATED')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_VOTES')."','".JTEXT::_('COM_COMAPRISONCHART_RATING_VOTE')."');");

		$live_path = JURI::base();

		$counter = 1;
		$percent = 0;

		if ($rating_count!=0) {$percent = number_format((intval($rating_sum) / intval( $rating_count ))*20,2);}

		$html = "<div id='rst_".$rid."'><span class=\"jrate-container\" style=\"margin-top:5px;\">
					  <ul class=\"jrate-stars\">
						<li class=\"current-rating rating-".$id.'-'.$rid."\" style=\"width:".(int)$percent."%;\"></li>
					  </ul>
				</span>
				";
		$html .='<div style="float:right;"><input type="button" onclick="reset_rating('.$id.','.$rid.')" class="button" name="reset" value="reset" /></div></div>';
		return $html;
	}
	
}