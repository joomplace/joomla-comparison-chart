<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Migration model.
 */
class ComparisonchartModelMigration extends JModelAdmin
{
	protected $context = 'com_comparisonchart';

	protected function allowEdit($data = array(), $key = 'id')
	{
		return JFactory::getUser()->authorise('core.admin', 'com_comparisonchart');
	}

	public function getTable($type = 'settings', $prefix = 'ComparisonchartTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	
	protected function loadFormData()
	{
			$data = $this->getItem();
		return $data;
	}
	
	public function getItem($pk = null)
	{
		if (!isset($this->item)) {
			$db	= JFactory::getDBO();
			$db->setQuery("SELECT `extension_id`, `params` FROM #__extensions WHERE name='com_comparisonchart' AND element='com_comparisonchart'");
			$config = $db->loadObject();
			
			$configString = $config->params;
			$cfg = json_decode($configString);
			$item = new JObject;
			foreach ($cfg as $key=>$val) {
				$item->$key = $val;
			}
			$item->id = $config->extension_id;
		}
		return $item;
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		$app	= JFactory::getApplication();

		$form = $this->loadForm('com_comparisonchart.migration', 'migration', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}	
	
	
}