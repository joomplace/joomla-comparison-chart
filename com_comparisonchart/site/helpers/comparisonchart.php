<?php

/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

class ComparisonChartHelper {

    public static function getCompare($id = 0) {
        $app = JFactory::getApplication();
        $session =JFactory::getSession();
        if(!$id){
            $id=intval(JRequest::getVar('id'));
        }

        $asset = 'com_comparisonchart.chart' . $id;
        //$items = $app->getUserState($asset);
        $items=$session->get( $asset );
        return $items;
    }

    public static function getActions($itemID = 0) {
        $user = JFactory::getUser();
        $result = new JObject;

        if (empty($itemID)) {
            $assetName = 'com_comparisonchart';
        } else {
            $assetName = 'com_comparisonchart.chart.' . (int) $itemID;
        }

        $actions = array(
            'core.create', 'core.edit', 'core.admin', 'core.manage', 'core.chartview', 'core.delete', 'core.xls', 'core.edit.state'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        //echo "<pre>"; print_r($result); die;

        return $result;
    }

    public function clearTrash() {
        $db = JFactory::getDBO();
        $db->setQuery('DELETE FROM #__cmp_chart_rows WHERE chart_id=0');
        $db->execute();

        if (!$db->execute()) {
            return false;
        }
        return true;
    }

}