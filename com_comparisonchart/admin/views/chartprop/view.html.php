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


class ComparisonchartViewChartprop extends JViewLegacy {

    public function display($tpl = null) {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');

        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        //echo "<pre>"; print_r($this->item); die;

        $this->isNew = false;
        if (!$this->item) {
            $this->isNew = true;
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
        $canDo = ComparisonchartHelper::getActions();
        JToolBarHelper::title($this->isNew ? JText::_('COM_COMPARISONCHART_ITEM_CREATING') : JText::_('COM_COMPARISONCHART_ITEM_EDITING'), 'chartprop');
        if ($canDo->get('core.admin')) {
            JToolBarHelper::apply('chartprop.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('chartprop.save', 'JTOOLBAR_SAVE');
            JToolBarHelper::custom('chartprop.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            if (!$this->isNew) {
                //	JToolBarHelper::custom('item.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
            }
        }
        JToolBarHelper::cancel('chartprop.cancel', 'JTOOLBAR_CANCEL');
    }

    protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle($this->isNew ? JText::_('COM_COMPARISONCHART_ITEM_CREATING') : JText::_('COM_COMPARISONCHART_ITEM_EDITING'));
    }

}

?>