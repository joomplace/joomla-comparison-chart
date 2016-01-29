<?php

/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modeladmin');

class ComparisonchartModelDashboard_item extends JModelAdmin {

    protected $context = 'com_comparisonchart';

    public function getTable($type = 'Dashboard_item', $prefix = 'ComparisonchartTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /*public function getItem($pk = null) {
        if (isset($_GET['id'])) {
            $db = JFactory::getDBO();
            $id = intval($_GET['id']);
            $query = "SELECT c.*"
                    . "\n FROM #__cmp_chart_dashboard_items AS c WHERE c.id=" . $id
            ;
            $db->setQuery($query);
            $items = $db->loadResult();
            return $items;
        }
        return false;
    }*/
      public function getItem($pk = null) {
        if (!isset($this->item)) {
            $pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
            $table = $this->getTable();

            if ($pk > 0) {
                $return = $table->load($pk);

                if ($return === false && $table->getError()) {
                    $this->setError($table->getError());
                    return false;
                }
            }

            $properties = $table->getProperties(1);
            $this->item = new JObject($properties);
        }

        return $this->item;
    }

    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm($this->context . '.' . $this->getName(), $this->getName(), array('control' => 'jform', 'load_data' => false));
        $form->bind($this->getItem());
        if (empty($form)) {
            return false;
        }
        return $form;
    }

   /* public function save($arr) {
        $db = $this->getDBO();
        $db->setQuery("UPDATE `#__cmp_chart_dashboard_items` SET title='" . $arr['title'] . "', icon='" . $arr['icon'] . "',  published=" . $arr['published'] . " WHERE id=" . $arr['id']);
        if (!$db->execute()) {
            return false;
        } else {
            return true;
        }
    }*/

}
