<?php

/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class ComparisonchartModelCharts extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'c.id', 
                'c.title', 
                't.temp_name', 
                'rows_count', 
                'c.published', 
                'items_count'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        $this->setState('filter.search_chart', $this->getUserStateFromRequest('com_comparisonchart.filter.search_chart', 'filter_search_chart'));
        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);
        $templete = $this->getUserStateFromRequest($this->context . '.filter.templete', 'filter_templete', '');
        $this->setState('filter.templete', $templete);
        parent::populateState();
    }

    protected function getListQuery() {
        $this->deleteEmptyProp();
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('c.*, t.`temp_name`');

        $query->select('(SELECT COUNT(r.chart_id) FROM #__cmp_chart_rows AS r WHERE r.chart_id=c.id ) AS rows_count');
        $query->select('(SELECT COUNT(i.chart_id) FROM #__cmp_chart_items AS i WHERE i.chart_id=c.id) AS items_count');
        $query->from('`#__cmp_chart_list` AS `c`');
        $query->leftJoin('`#__cmp_chart_templates` AS `t` ON `t`.id=`c`.`css`');
        $query->group('`c`.`id`');


        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published)) {
            $query->where('c.published = ' . (int) $published);
        } elseif ($published === '') {
            $query->where('(c.published = 0 OR c.published = 1)');
        }

        // Filter templete
         $templete = $this->getState('filter.templete');
        if ($templete != "") {
            $temp = $this->getTempletes();
            $templates = $db->escape($temp[intval($templete)]);
            $query->where('(t.temp_name="' . $templates . '")');
        }else {
            $query->where('(t.temp_name LIKE"%%")');
        }

        //search filter
        $search = $this->getState('filter.search_chart');
        if (!empty($search)) {

            if (stripos($search, 'id:') === 0) {
                $query->where('c.id = ' . (int) substr($search, 3));
            } else {

                $search = $this->_db->quote('%' . $search . '%', true);

                $query->where('(c.title LIKE ' . $search . ' OR c.description_before  LIKE ' . $search . ' OR	c.description_after LIKE ' . $search . ')');
            }
        }
        $query->order($db->escape($this->getState('list.ordering', 'c.title')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
        return $query;
    }

    public function getTempletes() {
        $db = JFactory::getDBO();

        $query = "SELECT t.temp_name"
                . "\n FROM #__cmp_chart_templates AS t"
                . "\n ORDER BY t.temp_name"
        ;
        $db->setQuery($query);
        $templetes = $db->loadColumn();

        return $templetes;
    }
    
    public function deleteEmptyProp() {
        $db = JFactory::getDBO();
        $db->setQuery('DELETE FROM #__cmp_chart_rows WHERE chart_id=0');
        $db->execute();
    }

}