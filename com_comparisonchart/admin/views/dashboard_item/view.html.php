<?php

/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class ComparisonchartViewDashboard_item extends JViewLegacy {

    protected $form;
    protected $item;
    protected $state;

    public function display($tpl = null) {

        $this->addTemplatePath(JPATH_BASE . '/components/com_comparisonchart/helpers/html');

        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
    
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }


        $this->addToolBar();
        JToolBarHelper::title(JText::_('COM_COMPARISONCHART').': '.JText::_('COM_CHART_DASHBOARD_EDIT_ITEM'));
        parent::display($tpl);
    }

    protected function addToolBar() {
        $user = JFactory::getUser();
        JToolBarHelper::apply('dashboard_item.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('dashboard_item.save', 'JTOOLBAR_SAVE');

        JToolBarHelper::cancel('dashboard_item.cancel', 'JTOOLBAR_CANCEL');
    }
    

}
