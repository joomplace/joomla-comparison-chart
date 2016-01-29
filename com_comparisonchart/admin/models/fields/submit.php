<?php

/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;

jimport('joomla.form.formfield');
jimport('joomla.form.helper');

class JFormFieldSubmit extends JFormField
{
	public $type = 'submit';

	public function getInput()
	{
		$input = '<input type="submit" name="'.$this->name.'" value="'.JText::_((string)$this->element['label']).'" ';
		
		$task = isset($this->element['task']) ? $this->element['task'] : false;
		if ($task) {
			$input .= 'onclick="javascript:Joomla.submitbutton(\''.$task.'\')"';
		}
		
		$input .= ' />';
		return $input;
	}

	public function getLabel()
	{
		return '<label></label>';
	}
}