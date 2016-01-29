<?php

/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

class ComparisonchartControllerDashboard_items extends JControllerAdmin {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function getModel($name = 'Dashboard_items', $prefix = 'ComparisonchartModel') {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

    public function add() {
        $this->setRedirect('index.php?option=com_comparisonchart&task=dashboard_item.add');
    }

    public function delete() {
        // Get items to remove from the request.
        $cid = JFactory::getApplication()->input->get('cid', array(), '', 'array');
        $tmpl = JFactory::getApplication()->input->get('tmpl');
        if ($tmpl == 'component')
            $tmpl = '&tmpl=component';
        else
            $tmpl = '';

        if (!is_array($cid) || count($cid) < 1) {
            JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
        } else {
            // Get the model.
            $model = $this->getModel();

            // Make sure the item ids are integers
            jimport('joomla.utilities.arrayhelper');
            JArrayHelper::toInteger($cid);

            // Remove the items.
            if ($model->delete($cid)) {
                $this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
            } else {
                $this->setMessage($model->getError());
            }
        }

        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $tmpl, false));
    }

    public function edit() {
        $cid = JFactory::getApplication()->input->get('cid', array(), '', 'array');
        $item_id = $cid['0'];
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&task=dashboard_item.edit&id=' . $item_id, false));
    }

    public function publish() {
        $model = $this->getModel();
        $cid = JFactory::getApplication()->input->get('cid', array(), '', 'array');
        if ($model->publish($cid)) {
            $this->setMessage(JText::_('COM_CHART_ITEM_SUCCES_CHANGED'));
        } else {
            $this->setMessage($model->getError());
        }
        $this->setRedirect('index.php?option=com_comparisonchart&view=dashboard_items');
    }

    public function dashboard() {
        $this->setRedirect('index.php?option=com_comparisonchart&view=about');
    }

}
