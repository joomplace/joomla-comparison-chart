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

class ComparisonchartViewDashboard_Items extends JViewLegacy {

    protected $items = null;

    function display($tpl = null) {
        $this->addTemplatePath(JPATH_BASE . '/components/com_comparisonchart/helpers/html');

        $this->items = $this->get('Items');
        $this->state = $this->get('State');
        $this->pagination = $this->get('Pagination');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar() {
        $this->canDo = $canDo = ComparisonchartHelper::getActions();
        JToolBarHelper::title(JText::_('COM_COMPARISONCHART') . ': ' . JText::_('COM_CHART_MANAGE_DASHBOARD_ITEMS'), 'dashboard items');
		JToolBarHelper::addNew('dashboard_items.add','JTOOLBAR_NEW');
        JToolBarHelper::editList('dashboard_items.edit', 'JTOOLBAR_EDIT');
		JToolBarHelper::deleteList('','dashboard_items.delete','JTOOLBAR_DELETE');


    }

    protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_COMPARISONCHART') . ': ' . JText::_('COM_CHART_MANAGE_DASHBOARD_ITEMS'));
    }

}
