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


class ComparisonchartViewMigration extends JViewLegacy {

    protected $state;
    protected $item;
    protected $form;

    function display($tpl = null) {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');

        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->custom = $this->get('Custom');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        ComparisonChartHelper::addSettingsSubmenu('migration');
        $this->sidebar = JHtmlSidebar::render();
        $this->addToolBar();
        $this->setDocument();

        parent::display($tpl);
    }

    protected function addToolBar() {
        //JRequest::setVar('hidemainmenu', true);
        JToolBarHelper::title(JText::_('COM_COMPARISONCHART_MIGRATION'), 'migration');
    }

    protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_COMPARISONCHART_MIGRATION'));
    }

}