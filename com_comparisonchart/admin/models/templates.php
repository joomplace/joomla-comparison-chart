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

class ComparisonchartModelTemplates extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                't.temp_name', 't.id'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        $this->setState('filter.search', $this->getUserStateFromRequest('com_comparisonchart.filter.search', 'filter_search'));

        parent::populateState();
    }

    protected function getListQuery() {
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('t.*');
        $query->from('`#__cmp_chart_templates` AS `t`');
        $query->order($db->escape($this->getState('list.ordering', 't.id')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

        //search filter        
        $search = trim($this->getState('filter.search'));
        
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('t.id = ' . (int) substr($search, 3));
            } else {
                $search = $db->Quote('%' . $db->escape($search, true) . '%');
                $query->where('(t.temp_name LIKE ' . $search . ')');
            }
        }

        $orderCol = $this->state->get('list.ordering', 'id');
        $orderDirn = $this->state->get('list.direction', 'ASC');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));
    
        return $query;
    }

}
