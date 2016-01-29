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


class ComparisonchartViewTemplates extends JViewLegacy {

    function display($tpl = null) {
       
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');

        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'administrator/components/com_comparisonchart/assets/css/js_color_picker.css');
        $document->addScript(JURI::root() . 'administrator/components/com_comparisonchart/assets/js/js_color_picker.js');
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        ComparisonChartHelper::addManagementSubmenu('templates');
        $this->sidebar = JHtmlSidebar::render();
        $this->addToolBar();

        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar() {
        $this->canDo = $canDo = ComparisonchartHelper::getActions();
        JToolBarHelper::title(JText::_('COM_COMPARISONCHART_MANAGER_TEMPLATES'), 'templates');
        if ($canDo->get('core.create')) {
            JToolBarHelper::addNew('template.add', 'JTOOLBAR_NEW');
        }

        if ($canDo->get('core.edit')) {
            JToolBarHelper::editList('template.edit', 'JTOOLBAR_EDIT');
            JToolBarHelper::divider();
        }

        if ($canDo->get('core.delete')) {
            JToolBarHelper::deleteList('', 'templates.delete', 'JTOOLBAR_DELETE');
        }
        if ($canDo->get('core.admin')) {
            JToolBarHelper::divider();
            //JToolBarHelper::preferences('com_comparisonchart');
        }
    }

    protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_COMPARISONCHART_MANAGER_TEMPLATES'));
    }

    protected function getSortFields() {
        return array(
            'temp_name' => JText::_('JGLOBAL_TITLE')
        );
    }

}
