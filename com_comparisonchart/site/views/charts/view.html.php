<?php

/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'baseview.php');

class ComparisonChartViewCharts extends BaseView {

    function display($tpl = null) {
        $model = $this->getModel();
        $model->getColumns();

        $this->chart = $model->getItems();

        $this->Itemid = $itemId = JRequest::getInt('Itemid');
        $helper = new ComparisonChartHelper;
		$helper->clearTrash();
        $canDo = ComparisonchartHelper::getActions();

        if ($canDo->get('core.xls')) {
            $this->downloadxls = true;
        } else {
            $this->downloadxls = false;
        }

        if ($canDo->get('core.chartview')) {
            $this->chartview = true;
        } else {
            $this->chartview = false;
        }

        $db = JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__cmp_chart_templates WHERE id=" . $this->chart->css);
        $templates_settings = $db->loadObjectList();
        $settings = $templates_settings[0];
        $this->catid = JRequest::getVar('catid', 0, 'get', 'int');

        $this->template = $settings;

        $height = (isset($this->chart->title_height)) ? $this->chart->title_height : 150;

        ob_start();
        include(JPATH_SITE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_comparisonchart' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'style.php');
        $style = @ob_get_contents();
        @ob_end_clean();

        if ($style) {
            $this->style = $style;
        }
        $this->params = json_decode($model->getParams());

        //$this->params = $model->getState('params');

        if ($this->getLayout() == 'default') {
            $this->columns = $model->getColumns();

            $this->rows = $model->getRows();
            $this->content = $model->getContent();
        }

        if (count($errors = $model->getErrors())) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        parent::display($tpl);
    }

    public function setDocument() {
        $doc = $this->document;
        $doc->addScript('components/com_comparisonchart/assets/js/charts.js');
        $doc->addScript('components/com_comparisonchart/assets/js/scrollto.js');
    }

    public function setPathway() {
        $title = array();

        $app = JFactory::getApplication();
        $pathway = $app->getPathway();

        $jsite = new JSite;
        $menu = $jsite->getMenu();
        $active = $menu->getActive();

        if ($active and $active->component == 'com_comparisonchart' and $active->query['view'] != 'charts') {
            if ($this->chart) {
                $pathway->addItem($this->chart->title, '');
            }
        }
    }

    function getRatingStars($id, $rid, $rating_sum = 0, $rating_count = 0) {

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('ct.sum, ct.count ');
        $query->from('#__cmp_chart_rating AS ct');
        $query->where('item_id=' . (int) $id);
        $query->where('row_id=' . (int) $rid);
        $db->setQuery($query);
        $rate = $db->loadObject();

        if ($rate) {
            $rating_sum = $rate->sum ? $rate->sum : 0;
            $rating_count = $rate->count ? $rate->count : 0;
        }

        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'components/com_comparisonchart/assets/css/vote.css');
        $document->addScript(JURI::root() . 'components/com_comparisonchart/assets/js/vote.js');

        $document->addScriptDeclaration("var sfolder = '" . JURI::base(true) . "';
					var jrate_text=Array('" . JTEXT::_('COM_COMAPRISONCHART_RATING_NO_AJAX') . "','" . JTEXT::_('COM_COMAPRISONCHART_RATING_LOADING') . "','" . JTEXT::_('COM_COMAPRISONCHART_RATING_THANKS') . "','" . JTEXT::_('COM_COMAPRISONCHART_RATING_LOGIN') . "','" . JTEXT::_('COM_COMAPRISONCHART_RATING_RATED') . "','" . JTEXT::_('COM_COMAPRISONCHART_RATING_VOTES') . "','" . JTEXT::_('COM_COMAPRISONCHART_RATING_VOTE') . "');");

        $live_path = JURI::base();

        $counter = 1;
        $percent = 0;

        if ($rating_count != 0) {
            $percent = number_format((intval($rating_sum) / intval($rating_count)) * 20, 2);
        }

        $html = "<span class=\"jrate-container\" style=\"margin-top:5px;\">
					  <ul class=\"jrate-stars\">
						<li class=\"current-rating rating-" . $id . '-' . $rid . "\" style=\"width:" . (int) $percent . "%;\"></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(" . $id . "," . $rid . ",1," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_COMAPRISONCHART_RATING_VERY_POOR') . "\" class=\"jp-one-star\">1</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(" . $id . "," . $rid . ",2," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_COMAPRISONCHART_RATING_POOR') . "\" class=\"jp-two-stars\">2</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(" . $id . "," . $rid . ",3," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_COMAPRISONCHART_RATING_REGULAR') . "\" class=\"jp-three-stars\">3</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(" . $id . "," . $rid . ",4," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_COMAPRISONCHART_RATING_GOOD') . "\" class=\"jp-four-stars\">4</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(" . $id . "," . $rid . ",5," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_COMAPRISONCHART_RATING_VERY_GOOD') . "\" class=\"jp-five-stars\">5</a></li>
					  </ul>
				</span>
					  <span class=\"jrate-count jrate-" . $id . '-' . $rid . "\"><small>";

        $html .= "( ";
        if ($rating_count != 1) {
            $html .= $rating_count . " " . JTEXT::_('COM_COMAPRISONCHART_RATING_VOTES');
        } else {
            $html .= $rating_count . " " . JTEXT::_('COM_COMAPRISONCHART_RATING_VOTE');
        }
        $html .=" )";
        $html .="</small></span>";

        return $html;
    }

}
