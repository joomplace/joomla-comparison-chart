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

class ComparisonchartControllerChartprops extends JControllerAdmin
{

	public $view_list = 'chartprops';
	
	public function __construct($config = array())
	{
		$chart = JFactory::getApplication()->input->get('filter_chart','');
		if($chart)
			$this->view_list .='&filter_chart='.$chart;
			
		parent::__construct($config);
	}
	
	
    public function getModel($name = 'Chartprop', $prefix = 'ComparisonchartModel', $config = array())
    {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

   
    public function publishProp()
    {
        $id = intval(JRequest::getVar('id'));
        $model = $this->getModel();

        $data = array();
        // Save the ordering
        $return = $model->publish($id);

        $data['publish'] = $return;
        echo json_encode($data);
        JFactory::getApplication()->close();
    }

    public function delete()
    {
        $ids = JRequest::getVar('cid', 'array');
        $model = $this->getModel();
        $filter_chart = intval(JRequest::getVar('filter_chart'));
        if ($model->delete($ids)) {
            $this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($ids)));
        } else {
            $this->setMessage($model->getError());
        }
        $this->setRedirect(('index.php?option=com_comparisonchart&view=chartprops&filter_chart='.$filter_chart));
    }

}