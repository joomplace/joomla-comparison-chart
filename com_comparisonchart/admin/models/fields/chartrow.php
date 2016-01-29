<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');
jimport('joomla.form.helper');

class JFormFieldChartRow extends JFormField
{
	public $type = 'chartrow';

	public function getInput()
	{
		$chart_id = (int)$this->form->getField('id')->value;

		if (!$chart_id) {
			return '<div class="error" >'.JText::_('COM_COMPARISONCHART_ERROR_NO_CHART').'</div>';
		}

		JText::script('COM_COMPARISONCHART_FIELD_ROW_TYPE_INT');
		JText::script('COM_COMPARISONCHART_FIELD_ROW_TYPE_TEXT');
		JText::script('COM_COMPARISONCHART_FIELD_ROW_TYPE_CHECK');
		JText::script('COM_COMPARISONCHART_FIELD_ROW_TYPE_SPACER');
		JText::script('COM_COMPARISONCHART_FIELD_ROW_TYPE_RATING');		
		JText::script('COM_COMPARISONCHART_FIELD_ROW_DIRECTION_ASC');
		JText::script('COM_COMPARISONCHART_FIELD_ROW_DIRECTION_DESC');
		JText::script('COM_COMPARISONCHART_FIELD_ROW_DIRECTION_NONE');

		JHtml::_('behavior.modal');

		ob_start();
		include(dirname(__FILE__).DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'chartrow.tmpl.php');
		$input = ob_get_contents();
		ob_get_clean();
				
		return $input;
	}

	public function getLabel()
	{
		return '';
	}
}