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

class ComparisonchartModelChartprops extends JModelList
{

    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'i.id',
                'i.name',
                'i.published',
                'i.type',
                'i.ordering'
            );
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $this->setState('filter.search', $this->getUserStateFromRequest('com_comparisonchart.filter.search', 'filter_search'));
        $published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);
        parent::populateState();
    }

    protected function getListQuery()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);

        $query->select('i.*');
        $query->from('#__cmp_chart_rows AS i');

        // Filter by published state
        // Filter by published state
        $published = $this->getState('filter.published');

        if (is_numeric($published)) {
            $query->where('i.published = ' . (int) $published);
        } elseif ($published === '') {
            $query->where('(i.published = 0 OR i.published = 1)');
        }

        $search = trim($this->getState('filter.search'));
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('i.id = ' . (int)substr($search, 3));
            } else {
                $search = $this->_db->quote('%' . $search . '%', true);

                $query->where('(i.name LIKE ' . $search . ' OR i.description  LIKE ' . $search . ')');
            }
        }

        $search =JRequest::getVar('filter_chart', 0, 'get', 'int');
        if ($search) {
            $app = JFactory::getApplication();
            $app->setUserState( 'filter_chart', $search );
            $query->where('i.chart_id=' . intval($search));
        }

        $orderCol = $this->state->get('list.ordering', 'id');
        $orderDirn = $this->state->get('list.direction', 'ASC');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));
        //$query->order($db->escape($this->getState('list.ordering', 'i.title')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));


        return $query;
    }

    public function getCharts()
    {
        $model = JModelLegacy::getInstance('Charts', 'ComparisonchartModel', array('ignore_request' => true));
        $this->charts = $model->getItems();

        return $this->charts;
    }

    public function getForm()
    {
        $model = JModelLegacy::getInstance('Import', 'ComparisonchartModel', array('ignore_request' => true));
        $form = $model->getForm();

        if (empty($form)) {
            return false;
        }
        return $form;
    }

    public function getAllItems()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('i.*');
        $query->from('`#__cmp_chart_rows` AS `i`');
        $query->order($db->escape($this->getState('list.ordering', 'i.title')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
        $db->setQuery($query);
        $results = $db->loadObjectList();

        return $results;
    }

    public function getChartsTitle()
    {
        $db = JFactory::getDBO();

        $query = "SELECT c.title"
            . "\n FROM #__cmp_chart_list AS c"
            . "\n ORDER BY c.title";
        $db->setQuery($query);
        $charts = $db->loadColumn();

        return $charts;
    }

    public function publish($ids)
    {
        $db = JFactory::getDBO();
        if ($ids) {
            for ($i = 0; $i < count($ids); $i++) {
                $ids[$i] = intval($ids[$i]);
                $ids[$i] = "'".$ids[$i]."'";
            }
            $ids = implode(',',$ids);

            $query = "SELECT c.published"
                . "\n FROM #__cmp_chart_rows AS c"
                . "\n WHERE c.id IN ($ids)";
            ;
            $db->setQuery($query);
            $ordering = $db->loadResult();
            if (intval($ordering) == 1) {
                $db->setQuery("UPDATE #__cmp_chart_rows  SET published=0  WHERE id IN ($ids)");
                $db->execute();
                return $ordering;
            } else {
                $db->setQuery("UPDATE #__cmp_chart_rows  SET published=1  WHERE id IN ($ids)");
                $db->execute();
                return $ordering;
            }
            return $ordering;
        }
        return false;
    }

    public function delete($ids)
    {
        $db = JFactory::getDBO();
        if (!empty($ids)) {
           for($i=0; $i<count($ids); $i++){
                $db->setQuery("DELETE FROM #__cmp_chart_rows   WHERE id=" . intval($ids[$i]));
                $db->execute();
           }
           return true;
        }
        return false;
    }

    public function saveorder($idArray = null, $lft_array = null)
    {
        // Get an instance of the table object.
        $table = $this->getTable();

        if (!$table->saveorder($idArray, $lft_array))
        {
            $this->setError($table->getError());
            return false;
        }

        // Clear the cache
        $this->cleanCache();

        return true;
    }

}
