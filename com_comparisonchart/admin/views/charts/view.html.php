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


class ComparisonchartViewCharts extends JViewLegacy {

    function display($tpl = null) {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        JHtmlSidebar::setAction('index.php?option=com_comparisonchart&view=charts');

        $enabled = array();
        $enabled[] = JHTML::_('select.option', 0, JText::_('Unpublised'));
        $enabled[] = JHTML::_('select.option', 1, JText::_('Publised'));
        JHtmlSidebar::addFilter(
                JText::_('JOPTION_SELECT_PUBLISHED'), 'filter_published', JHtml::_('select.options', $enabled, 'value', 'text', $this->state->get('filter.published'), true)
        );

        $temp = $this->get("Templetes");
        $templete = array();
        for ($i = 0; $i < count($temp); $i++) {
            $templete[] = JHTML::_('select.option', $i, JText::_('' . $temp[$i] . ''));
        }
        JHtmlSidebar::addFilter(
                JText::_('COM_COMPARISONCHAR_SELECT_TEMPLETE'), 'filter_templete', JHtml::_('select.options', $templete, 'value', 'text', $this->state->get('filter.templete'), true)
        );

        ComparisonChartHelper::addManagementSubmenu('charts');
        $this->sidebar = JHtmlSidebar::render();
        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar() {
        $this->canDo = $canDo = ComparisonchartHelper::getActions();
        JToolBarHelper::title(JText::_('COM_COMPARISONCHART_MANAGER_CHARTS'), 'charts');
        if ($canDo->get('core.create')) {
            JToolbarHelper::addNew('chart.add', 'JTOOLBAR_NEW');
        }
        if ($canDo->get('core.edit')) {
            JToolBarHelper::editList('chart.edit', 'JTOOLBAR_EDIT');
            JToolBarHelper::divider();
            JToolBarHelper::custom('charts.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
            JToolBarHelper::custom('charts.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            JToolBarHelper::divider();
        }
        if ($canDo->get('core.delete')) {
            JToolBarHelper::deleteList('', 'charts.delete', 'JTOOLBAR_DELETE');
        }

    }

    protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_COMPARISONCHART_MANAGER_CHARTS'));
    }

    protected function getSortFields() {
        return array(
            'c.title' => JText::_('JGLOBAL_TITLE'),
            'c.published' => JText::_('JSTATUS'),
            'c.id' => JText::_('JGRID_HEADING_ID'),
            'i.items_count' => JText::_('COM_COMPARISONCHART_ITEMS_COUNT')
        );
    }

}
