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

class ComparisonchartModelTemplate extends JModelAdmin
{
	protected $context = 'com_comparisonchart';

	protected function allowEdit($data = array(), $key = 'id')
	{
		return JFactory::getUser()->authorise('core.edit', 'com_comparisonchart.template.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}

	public function getTable($type = 'Template', $prefix = 'ComparisonchartTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getItem($pk = null)
	{
		if (!isset($this->item)) {
			$pk		= (!empty($pk)) ? $pk : (int) $this->getState($this->getName().'.id');
			$table	= $this->getTable();

			if ($pk > 0) {
				$return = $table->load($pk);

				if ($return === false && $table->getError()) {
					$this->setError($table->getError());
					return false;
				}
			}

			$properties = $table->getProperties(1);
			$this->item = new JObject($properties);
		}

		return $this->item;
	}
	
	public function getForm($data = array(), $loadData = false)
	{
		$form = $this->loadForm('com_comparisonchart.template', 'template', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($data)) {
			$template = $this->getItem();
		} else {
			$template = $data;
		}
		
		$file = JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'forms'.DIRECTORY_SEPARATOR.'template.xml';
		$form->loadFile($file, 'template');
		
		$form->bind($template);

		if (empty($form)) {
			return false;
		}
		return $form;
	}
}