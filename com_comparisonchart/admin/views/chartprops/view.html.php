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


class ComparisonchartViewChartprops extends JViewLegacy {

    function display($tpl = null) {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');

        $this->items = $this->get('Items');
        $this->charts = $this->get('Charts');
        $this->form = $this->get('Form');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        //print_r($this->items);die();

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }


        JHtmlSidebar::setAction('index.php?option=com_comparisonchart&view=chartprops');

        $enabled = array();
        $enabled[] = JHTML::_('select.option', 0, JText::_('Unpublised'));
        $enabled[] = JHTML::_('select.option', 1, JText::_('Publised'));
        JHtmlSidebar::addFilter(
                JText::_('JOPTION_SELECT_PUBLISHED'), 
                'filter_published', 
                JHtml::_('select.options', $enabled, 'value', 'text', $this->state->get('filter.published'), true)
        );

        $list_charts = $this->get("ChartsTitle");
        $charts = array();
        for ($i = 0; $i < count($list_charts); $i++) {
            $charts[] = JHTML::_('select.option', $i, JText::_('' . $list_charts[$i] . ''));
        }

        ComparisonChartHelper::addManagementSubmenu('chartprop');
        $this->sidebar = JHtmlSidebar::render();
        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar() {
        $this->canDo = $canDo = ComparisonchartHelper::getActions();
        JToolBarHelper::title(JText::_('COM_COMPARISONCHART_MANAGER_PROPS'), 'chartprops');
        if ($canDo->get('core.create')) {
            JToolBarHelper::addNew('chartprop.add', 'JTOOLBAR_NEW');
        }
        if ($canDo->get('core.edit')) {
            JToolBarHelper::editList('chartprop.edit', 'JTOOLBAR_EDIT');
            JToolBarHelper::divider();
            JToolBarHelper::custom('chartprops.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::custom('chartprops.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::divider();
        }
        if ($canDo->get('core.delete')) {
            JToolBarHelper::deleteList('', 'chartprops.delete', 'JTOOLBAR_DELETE');
        }
        if ($canDo->get('core.admin')) {
            JToolBarHelper::divider();
            //JToolBarHelper::preferences('com_comparisonchart');
        }
        JToolBarHelper::cancel('chart.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_COMPARISONCHART_MANAGER_PROPS'));
    }

    protected function getSortFields() {
        return array(
            'i.title' => JText::_('JGLOBAL_TITLE'),
            'i.ordering' => JText::_('JGRID_HEADING_ORDERING'),
            'i.published' => JText::_('JSTATUS'),
            'i.id' => JText::_('JGRID_HEADING_ID')
        );
    }

}
