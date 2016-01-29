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


class ComparisonchartViewAbout extends JViewLegacy {

    function display($tpl = null) {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');

        $this->version = ComparisonChartHelper::getVersion();
        $this->menu=ComparisonChartHelper::getControlPanel();
        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar() {
        $this->canDo = $canDo = ComparisonchartHelper::getActions();
        JToolBarHelper::title(JText::_('COM_COMPARISONCHART_MANAGER_ABOUT'), 'about');
        if ($canDo->get('core.admin')) {
            //JToolBarHelper::preferences('com_comparisonchart');
        }
    }

    protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_COMPARISONCHART_MANAGER_ABOUT'));
        $document->addScript(JURI::root() . 'administrator/components/com_comparisonchart/assets/js/js.js');
    }

}