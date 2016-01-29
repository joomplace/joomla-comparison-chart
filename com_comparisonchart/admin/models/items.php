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

class ComparisonchartModelItems extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'i.id',
                'i.title',
                'i.chart_id',
                'i.published',
                'charts',
                'i.ordering'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        $this->setState('filter.search', $this->getUserStateFromRequest('com_comparisonchart.filter.search', 'filter_search'));
        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
      // $chart_title = $this->getUserStateFromRequest($this->context . '.filter.chart', 'filter_chart', '');
        $this->setState('filter.published', $published);
       //$this->setState('filter.chart', $chart_title);
        parent::populateState();
    }

    protected function getListQuery() {
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('i.*');
        $query->from('#__cmp_chart_items AS i');

        $query->select('c.title AS charts');
        $query->join('LEFT', '#__cmp_chart_list AS c ON c.id=i.chart_id');

        $query->group('i.id');

        // Filter by published state
        $published = $this->getState('filter.published');

        if (is_numeric($published)) {
            $query->where('i.published = ' . (int) $published);
        } elseif ($published === '') {
            $query->where('(i.published = 0 OR i.published = 1)');
        }

        // Filter by charts

       /* $char = $this->getState('filter.chart');
        var_dump($char);
        if ($char != "") {
            $ch = $this->getChartsTitle();
            $chars = $db->escape($ch[intval($char)]);
            $query->where('(i.chart_id=' . intval($chars) . ')');
        }*/

        $search = trim($this->getState('filter.search'));
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('i.id = ' . (int) substr($search, 3));
            } else {
                $search = $this->_db->quote('%' . $search . '%', true);

                $query->where('(i.title LIKE ' . $search . ' OR i.description  LIKE ' . $search . ')');
            }
        }

        $search = JRequest::getVar('filter_chart', 0, 'get', 'int');
        if ($search) {
            $query->where('i.chart_id=' . intval($search));
        }

        $orderCol = $this->state->get('list.ordering', 'id');
        $orderDirn = $this->state->get('list.direction', 'ASC');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));
        //$query->order($db->escape($this->getState('list.ordering', 'i.title')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));



        return $query;
    }

    public function getCharts() {
        $model = JModelLegacy::getInstance('Charts', 'ComparisonchartModel', array('ignore_request' => true));
        $this->charts = $model->getItems();

        return $this->charts;
    }

    public function getForm() {
        $model = JModelLegacy::getInstance('Import', 'ComparisonchartModel', array('ignore_request' => true));
        $form = $model->getForm();

        if (empty($form)) {
            return false;
        }
        return $form;
    }

    public function getAllItems() {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('i.*');
        $query->from('`#__cmp_chart_items` AS `i`');
        $query->order($db->escape($this->getState('list.ordering', 'i.title')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
        $db->setQuery($query);
        $results = $db->loadObjectList();

        return $results;
    }

    public function getChartsTitle() {
        $db = JFactory::getDBO();

        $query = "SELECT c.title"
                . "\n FROM #__cmp_chart_list AS c"
                . "\n ORDER BY c.title"
        ;
        $db->setQuery($query);
        $charts = $db->loadColumn();

        return $charts;
    }



}
