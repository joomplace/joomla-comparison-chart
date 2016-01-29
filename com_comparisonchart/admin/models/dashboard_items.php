<?php

/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

class ComparisonchartModelDashboard_items extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array('id', 'title', 'url', 'icon', 'published');
        }
        parent::__construct($config);
    }

    protected function populateState($ordering = null, $direction = null) {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        parent::populateState();
    }

    protected function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('`id`,`title`,`url`,`icon`,`published`');
        $query->from('`#__cmp_chart_dashboard_items`');
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = $db->Quote('%' . $db->Escape($search, true) . '%');
            $query->where('title LIKE ' . $search);
        }
        $orderCol = $this->state->get('list.ordering', 'id');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));
        return $query;
    }

    function delete($cid) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__cmp_chart_dashboard_items');
        $query->where('id IN (' . implode(',', $cid) . ')');
        $db->setQuery($query);
        $db->execute();  //Remove all milistones
    }

    public function publish($cid, $value = 1) {
        $database = JFactory::getDBO();
        $task = JFactory::getApplication()->input->getCmd('task');
        $state = ($task == 'publish') ? 1 : 0;

        if (!is_array($cid) || count($cid) < 1) {
            $action = ($task == 'publish') ? 'publish' : 'unpublish';
            echo "<script> alert('" . JText::_('COM_CHART_SELECT_AN_ITEM_TO') . " $action'); window.history.go(-1);</script>\n";
            exit();
        }

        $cids = implode(',', $cid);

        $query = "UPDATE #__cmp_chart_dashboard_items"
                . "\n SET published = " . intval($state)
                . "\n WHERE id IN ( $cids )"
        ;
        $database->setQuery($query);
        if (!$database->execute()) {
            echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
            exit();
        }

        return true;
    }

}
