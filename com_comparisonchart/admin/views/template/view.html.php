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


class ComparisonchartViewTemplate extends JViewLegacy {

    public function display($tpl = null) {
        $this->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');

        $this->form = $this->get('Form');
        $this->item = $this->get('Item');



        //var_dump($this->form->getFieldsets());



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
        JRequest::setVar('hidemainmenu', true);
        $user = JFactory::getUser();
        $userId = $user->id;
        $isNew = $this->item->id == 0;
        $canDo = ComparisonchartHelper::getActions($this->item->id);
        JToolBarHelper::title($isNew ? JText::_('COM_COMPARISONCHART_TEMPLATE_CREATING') : JText::_('COM_COMPARISONCHART_TEMPLATE_EDITING'), 'templates');
        if ($isNew) {
            if ($canDo->get('core.create')) {
                JToolBarHelper::apply('template.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('template.save', 'JTOOLBAR_SAVE');
                JToolBarHelper::custom('template.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
            }
            JToolBarHelper::cancel('template.cancel', 'JTOOLBAR_CANCEL');
        } else {
            if ($canDo->get('core.edit')) {
                JToolBarHelper::apply('template.apply', 'JTOOLBAR_APPLY');
                JToolBarHelper::save('template.save', 'JTOOLBAR_SAVE');

                if ($canDo->get('core.create')) {
                    JToolBarHelper::custom('template.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
                }
            }
            if ($canDo->get('core.create')) {
                JToolBarHelper::custom('template.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
            }
            JToolBarHelper::cancel('template.cancel', 'JTOOLBAR_CLOSE');
        }
    }

    protected function setDocument() {
        $isNew = $this->item->id == 0;
        $document = JFactory::getDocument();
        $document->setTitle($isNew ? JText::_('COM_COMPARISONCHART_TEMPLATE_CREATING') : JText::_('COM_COMPARISONCHART_TEMPLATE_EDITING'));
    }

}

?>