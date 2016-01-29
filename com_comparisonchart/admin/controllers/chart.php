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

class ComparisonchartControllerChart extends JControllerForm
{
	public function exportItems()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$cid = JRequest::getVar('cid', array(), 'post', 'array');

		if (count($cid) > 1) {
			$error = JText::_('COM_COMPARISONCHART_ERROR_EXPORT_COUNT');
			$this->setRedirect('index.php?option=com_comparisonchart&view=charts');
			JError::raiseNotice(0, $error);
			$this->redirect();
		}

		$import = $this->getModel('import');
		$out = $import->export($cid[0]);
		$model = $this->getModel('chart');
		$chart = $model->getItem($cid[0]);

		if (!$out) {
			$error = JText::_('COM_COMPARISONCHART_ERROR_EXPORT_MODEL');
			$this->setRedirect('index.php?option=com_comparisonchart&view=charts');
			$this->redirect();
		}

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		header("Content-Type: text/x-csv");
		header("Content-Disposition: attachment; filename=".$chart->alias.'.csv');
		header("Content-Transfer-Encoding: binary");
		@set_time_limit(0);
		echo $out;
		$app->close();
	}
	
	public function importItems()
	{
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$app = JFactory::getApplication();
		$data = JRequest::getVar('jform', array(), 'post', 'array');

		$import = $this->getModel('import');
		$out = $import->import($data);

		if (!$out) {
			$error = JText::_('COM_COMPARISONCHART_ERROR_IMPORT_MODEL');
			$this->setMessage($error);
		}else $this->setMessage(JText::_('COM_COMPARISONCHART_IMPORT_SUCCESSFULLY'));
		$this->setRedirect('index.php?option=com_comparisonchart&view=items');
		$this->redirect();
	}
        
        
}
