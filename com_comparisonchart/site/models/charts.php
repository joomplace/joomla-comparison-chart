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

class ComparisonChartModelCharts extends JModelLegacy {

    public function populateState() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('`c_par_value`')
                ->from('#__cmp_chart_settings')
                ->where("`c_par_name`='settings'");
        $db->setQuery($query);
        $paramsjson = $db->loadResult();

        $params = json_decode($paramsjson);

        $paramObj = new JObject();

        foreach ($params as $k => $v) {
            $paramObj->set($k, $v);
        }
//        echo "<pre>"; print_r($paramObj->get('show_title'));die;
        $this->setState('params', $paramObj);

        $id = JRequest::getVar('id', 0, 'get', 'int');
        $this->setState('chart.id', $id);

        $items = array();
        $show = JRequest::getVar('show', false, 'get', 'bool');
        if ($show) {
            
        } else {
            $session = JFactory::getSession();
            $registry = $session->get('registry');
            $compare = ComparisonChartHelper::getCompare($id);

            if ($compare) {
                $items = array_keys($compare);
            }
        }
        $this->setState('item.ids', $items);
    }

    public function getItems() {
        $jinput = JFactory::getApplication()->input;
        if (!isset($this->items)) {
            $db = $this->getDBO();
            $query = $db->getQuery(true);

            $query->select('cl.*, "" as items');
            $query->from('#__cmp_chart_list AS cl');

            $id = $this->getState('chart.id');
            if(!$id){
                $id=JRequest::getVar('id', 0, 'int');
            }
            if(!$id){
                $id = $jinput->get('id',0,'INT');
            }
            if ($id) {
                $query->where('cl.id=' . (int)$id);
            }
            $query->where('cl.published=1');

           /* $str='';
            if($this->getState('item.ids')){
                $items_id=$this->getState('item.ids');
                if(!empty($items_id)){
                    for($i=0; $i<count($items_id); $i++){
                        $str.='id='.intval($items_id[$i]);
                        if($i!=count($items_id)-1){
                            $str.=' OR ';
                        }
                    }
                }
            }

            if($str!=''){
                $query->where('('.$str.')');
            }*/

            $db->setQuery($query);

           //die(var_dump($query->__toString()));
            $this->items = $db->loadObject();
        }

        return $this->items;
    }

    public function getRows() {

        if (!isset($this->rows)) {
            $model = JModelLegacy::getInstance('Rows', 'ComparisonChartModel', array('ignore_request' => true));
            $model->setState('chart.id', $this->getState('chart.id'));
            $model->setState('item.ids', $this->getState('item.ids'));
            $this->rows = $model->getItems();
        }

        return $this->rows;
    }

    public function getColumns() {
        $jinput = JFactory::getApplication()->input;
        $items = $this->getState('item.ids');

        $chart_id = $jinput->get('id',0,'INT');
        if(!$chart_id){
            $chart_id = $jinput->get('chart-id',0,'INT');
        }

        if (!isset($this->columns)) {
            $model = JModelLegacy::getInstance('Items', 'ComparisonChartModel', array('ignore_request' => true));

            $model->setState('chart.id', $chart_id);
            $model->setState('item.ids', $items);

            $this->columns = $model->getItems();
        }

        return $this->columns;
    }

    public function getContent() {
        $content = array();

        $db = $this->getDBO();
        $query = $db->getQuery(true);

        $query->select('cr.chart_id, cr.id AS row_id');
        $query->from('#__cmp_chart_rows AS cr');

        $query->select('cc.value_data, cc.value_description, cc.item_id');
        $query->join('LEFT', '#__cmp_chart_content AS cc ON cc.row_id=cr.id');

        $id = $this->getState('chart.id');

        if(!$id){
            $id=JRequest::getVar('id', 0, 'int');
        }
        if ($id) {
            $query->where('cr.chart_id=' . intval($id));
        }

        $items = implode(',', $this->getState('item.ids'));

        if ($items) {
            $query->where('cc.item_id IN (' . $items . ')');
        }

        $db->setQuery($query);
//die(var_dump($query->__toString()));
        $rows = $db->loadObjectList();

        foreach ($rows as $item) {
            $obj = new JObject();
            $obj->value = $item->value_data;
            $obj->desc = $item->value_description;

            $content[$item->row_id][$item->item_id] = $obj;
        }

        return $content;
    }

    public function getParams() {
        $db = JFactory::getDBO();

        $query = "SELECT params"
                . "\n FROM #__extensions"
                . "\n WHERE type='component' AND element='com_comparisonchart'"
        ;
        $db->setQuery($query);
        $params = $db->loadResult();
        return $params;
    }

}