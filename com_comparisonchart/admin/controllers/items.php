<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controlleradmin');

class ComparisonchartControllerItems extends JControllerAdmin
{
	public function getModel($name = 'Item', $prefix = 'ComparisonchartModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	} 
        
        public function saveOrderAjax()
		{
			$pks = $this->input->post->get('cid', array(), 'array');
			$order = $this->input->post->get('order', array(), 'array');

			// Sanitize the input
			JArrayHelper::toInteger($pks);
			JArrayHelper::toInteger($order);

			// Get the model
			$model = $this->getModel();
			// Save the ordering
			$return = $model->saveorder($pks, $order);
			if ($return)
			{
				echo "1";
			}
			// Close the application
			JFactory::getApplication()->close();
		}

    public function publishProp()
    {
        $id =intval(JRequest::getVar('id')) ;
        $model = $this->getModel();
        $data=array();
        // Save the ordering
        $return = $model->publish($id);

        $data['publish'] = $return;
        echo json_encode($data);
        JFactory::getApplication()->close();
    }

}