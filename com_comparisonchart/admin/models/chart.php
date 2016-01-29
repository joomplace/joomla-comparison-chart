<?php

/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class ComparisonchartModelChart extends JModelAdmin {

    protected $context = 'com_comparisonchart';

    protected function allowEdit($data = array(), $key = 'id') {
        return JFactory::getUser()->authorise('core.edit', 'com_comparisonchart.chart.' . ((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
    }

    public function getTable($type = 'Chart', $prefix = 'ComparisonchartTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

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

    public function getForm($data = array(), $loadData = false) {
        $form = $this->loadForm('com_comparisonchart.chart', 'chart', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($data)) {
            $chart = $this->getItem();
            $css = $chart->css;
        } else {
            $chart = $data;
            $css = $data['css'];
        }

        if ($css == 'custom') {
            $file = JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'template.xml';
            $form->loadFile($file, 'template');
        }

        $form->bind($chart);

        if (empty($form)) {
            return false;
        }
        return $form;
    }

    public function deleteReferences() {
        $db = JFactory::getDBO();
        $input = JFactory::getApplication()->input;
        $ids = $input->get('cid', array(), 'post', 'array');

        if (!empty($ids)) {
            for ($i = 0; $i < count($ids); $i++) {
                $query = "SELECT id"
                        . "\n FROM #__cmp_chart_items"
                        . "\n WHERE chart_id=" . $ids[$i]
                ;
                $db->setQuery($query);
                $prop_id = $db->loadAssocList();

                $db->setQuery('DELETE FROM #__cmp_chart_rows WHERE chart_id=' . $ids[$i].' OR chart_id=0');
                $db->execute();
                $db->setQuery('DELETE FROM #__cmp_chart_items WHERE chart_id=' . $ids[$i]);
                $db->execute();
            }
            if (!empty($prop_id)) {
                for ($i = 0; $i < count($ids); $i++) {
                    $db->setQuery('DELETE FROM #__cmp_chart_content WHERE item_id=' . $prop_id[$i]['id']);
                    $db->execute();
                }
            }
            return true;
        }
        return false;
    }

}