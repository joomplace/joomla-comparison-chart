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


class ComparisonchartViewChart extends JViewLegacy {

    public function display($tpl = null) {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');

        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        if (isset($this->item->template)) {
            ComparisonChartHelper::getCustomCSS($this->item->template, 'style-custom');
        }

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        ComparisonChartHelper::addManagementSubmenu('items');
        $this->sidebar = JHtmlSidebar::render();
        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar() {
        JRequest::setVar('hidemainmenu', true);
        $user = JFactory::getUser();
        $userId = $user->id;
        $isNew = $this->item->id == 0;
        $canDo = ComparisonchartHelper::getActions($this->item->id);
        JToolBarHelper::title($isNew ? JText::_('COM_COMPARISONCHART_CHART_CREATING') : JText::_('COM_COMPARISONCHART_CHART_EDITING'), 'charts');
        if ($isNew) {
            if ($canDo->get('core.create')) {
                JToolBarHelper::apply('chart.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('chart.save', 'JTOOLBAR_SAVE');
                JToolBarHelper::custom('chart.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            }
            JToolBarHelper::cancel('chart.cancel', 'JTOOLBAR_CANCEL');
        } else {
            if ($canDo->get('core.edit')) {
                JToolBarHelper::apply('chart.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('chart.save', 'JTOOLBAR_SAVE');

                if ($canDo->get('core.create')) {
                    JToolBarHelper::custom('chart.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
                }
            }
            if ($canDo->get('core.create')) {
                //JToolBarHelper::custom('chart.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
            }
            JToolBarHelper::cancel('chart.cancel', 'JTOOLBAR_CLOSE');
        }
    }

    protected function setDocument() {
        $isNew = $this->item->id == 0;
        $document = JFactory::getDocument();
        $document->setTitle($isNew ? JText::_('COM_COMPARISONCHART_CHART_CREATING') : JText::_('COM_COMPARISONCHART_CHART_EDITING'));
    }

}

?>