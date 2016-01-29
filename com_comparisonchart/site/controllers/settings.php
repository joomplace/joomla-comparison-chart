<?php

/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controllerform');

class ComparisonchartControllerSettings extends JControllerForm {

    protected function allowEdit($data = array(), $key = 'id') {
        return JFactory::getUser()->authorise('core.manage', 'com_comparisonchart');
    }

    public function cancel($key = NULL) {
        $this->setRedirect('index.php?option=com_comparisonchart', false);
    }

    public function save($key = null, $urlVar = null) {
       
        $this->option = 'com_comparisonchart';
        $responce = parent::save($key, $urlVar);
        $this->setMessage(JText::_('COM_COMPARISONCHART_CONFIG_SAVED'));
        return $responce;
    }

}
