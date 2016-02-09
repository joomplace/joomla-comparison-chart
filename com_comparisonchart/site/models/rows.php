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

class ComparisonChartModelRows extends JModelLegacy {

    public function getItems() {
        if (!isset($this->items)) {

            $items = implode(',', JRequest::getVar('item', array(), 'post'));

            if($items==""){
                $items = implode(',', $this->getState('item.ids'));
            }

            $db = $this->getDBO();
            $query = $db->getQuery(true);

            $query->select('cr.*');
            $query->from('#__cmp_chart_rows AS cr');

            $best_query = '(SELECT ' .
                    'CASE WHEN cr.direction = "asc" AND (cr.type="int" OR cr.type="rating") THEN MAX(CONVERT(cc.value_data,SIGNED)) ' .
                    'WHEN cr.direction = "desc" AND (cr.type="int" OR cr.type="rating") THEN MIN(CONVERT(cc.value_data,SIGNED)) ' .
                    'WHEN cr.direction = "desc" AND cr.type<>"int" AND cr.type<>"rating" THEN MIN(cc.value_data) ' .
                    'WHEN cr.direction = "asc" AND cr.type<>"int" AND cr.type<>"rating" THEN MAX(cc.value_data) ' .
                    'END AS best ';

            $best_query .= 'FROM #__cmp_chart_content AS cc ';
            $best_query .= 'WHERE cc.row_id=cr.id';
            if ($items) {
                $best_query .= ' AND cc.item_id IN (' . $items . ')';
            }
            $best_query .= ' ORDER BY (cc.value_data)) AS best';
            $query->select($best_query);

            $count_query = '(SELECT COUNT(item_id) FROM #__cmp_chart_content AS cc ';
            $count_query .= 'WHERE cc.value_data=best AND cc.row_id=cr.id';
            if ($items) {
                $count_query .= ' AND cc.item_id IN (' . $items . ')';
            }
            //$count_query .=" CASE WHEN cr.type = 'int' THEN cc.value_data NOT LIKE '%[a-z]%' AND ISNUMERIC(cc.value_data) = 1  END";
            $count_query .= ') AS best_count';
            $query->select($count_query);

            $id = $this->getState('chart.id');
            if(!$id){
                $id=JRequest::getVar('id',JRequest::getVar('chart_id',0,'INT'),'INT');
            }

            if ($id) {
                $query->where('cr.chart_id=' . $id);
            }

            $query->where('cr.published = "1"');
            $query->order('cr.ordering');

            $db->setQuery($query);

            $this->items = $db->loadObjectList();
        }

        return $this->items;
    }

}