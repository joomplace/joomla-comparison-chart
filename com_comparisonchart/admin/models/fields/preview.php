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

class JFormFieldPreview extends JFormField
{
	public $type = 'submit';

	public function getInput()
	{
		$field = $this->form->getField((string)$this->element['target']);
		$value = $field->value?$field->value:1;
		
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__cmp_chart_templates WHERE temp_name = '".$value."'");
		$template_settings = $db->loadObjectList();
		
		$tbl_style = 'style="float:left;width:400px;"';
		$height = 30;
		
		ob_start();
		include(dirname(__FILE__).DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'preview.tmpl.php');
		$preview = ob_get_contents();
		ob_get_clean();
		return $preview;
	}
}