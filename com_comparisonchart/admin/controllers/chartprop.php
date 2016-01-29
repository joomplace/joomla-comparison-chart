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

class ComparisonchartControllerChartprop extends JControllerForm
{
    public function save()
    {

        $id = intval(JRequest::getVar('id'));
        parent::save('chartprop', $id);

        $model = $this->getModel();
        if ($id != 0) {
            $chart_id = $model->getChartId($id);
        } else {
            $maxid = $model->maxId();
            $chart_id = $model->getChartId($maxid);
        }
        $task = JRequest::getVar('task');
        switch ($task) {
            case "apply":
                if($id==0){
                    $this->setRedirect('index.php?option=com_comparisonchart&view=chartprop&layout=edit&id=' . intval($maxid));
                }else{
                    $this->setRedirect('index.php?option=com_comparisonchart&view=chartprop&layout=edit&id=' . intval($id));
                }
                break;
            case "save2new":
                $this->setRedirect('index.php?option=com_comparisonchart&view=chartprop&layout=edit');
                break;
            case "save":
                $this->setRedirect('index.php?option=com_comparisonchart&view=chartprops&filter_chart=' . intval($chart_id));
                break;
        }
    }

    public function cancel(){
        parent::cancel();
        $filter_chart=intval(JRequest::getVar('filter_chart'));
        $this->setRedirect('index.php?option=com_comparisonchart&view=chartprops&filter_chart=' . intval($filter_chart));
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
        if ($return) {
            echo "1";
        }
        // Close the application
        JFactory::getApplication()->close();
    }

}
