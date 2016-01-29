<?php

/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.tooltip');
?>
<div class="comparisonchart style-<?php echo $this->chart->css; ?>" >
	<form action="index.php?option=com_comparisonchart&task=chart.sendError" method="post" >
		<fieldset>
			<legend>
				<?php echo JText::_('COM_COMPARISONCHART_FORM_REPORT'); ?>
			</legend>
		</fieldset>
		<input type="hidden" value="<?php echo $this->Itemid; ?>" name="Itemid" />
	</form>
</div>
