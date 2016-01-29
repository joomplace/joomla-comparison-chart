<?php

/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;
?>
<script type="text/javascript" >
	function toggleCheckbox (el) {
		var check = el.previousSibling;
		if (check.checked == true) {
			check.checked = false;
			<?php if ($template_settings)
			{
				$img_false = JUri::root().$template_settings->check_false;
			}else $img_false = JUri::root().'components/com_comparisonchart/assets/images/ico_no.gif';
			?>
			el.src = '<?php echo $img_false;?>';
		} else {
			check.checked = true;
			<?php if ($template_settings)
			{
				$img_true = JUri::root().$template_settings->check_true;
			}else $img_true = JUri::root().'components/com_comparisonchart/assets/images/ico_yes.gif';
			?>
			el.src = '<?php echo $img_true;?>';
		}
	}
	function reset_rating(id,rid)
	{
		var url="<?php echo JURI::root()?>administrator/index.php?option=com_comparisonchart&task=row.resetrating&tmpl=component";
		var dan="id=" + id+"&rid=" + rid;
		var rst =  $('rst_'+ rid);
		 var myAjax = new Request.HTML({
				url:url,
				method: "post",
				data: dan,
				encoding:"utf-8",
				update: rst
			});
		myAjax.send();	
	}
</script>
<table class="adminlist" id="rowHolder" >
	<thead>
		<tr>
			<th width="200px" >
				<?php echo JText::_('COM_COMPARISONCHART_FIELD_PROPERTY_NAME'); ?>
			</th>
			<th width="200px" >
				<?php echo JText::_('COM_COMPARISONCHART_FIELD_PROPERTY_VALUE'); ?>
			</th>
			<th>
				<?php echo JText::_('COM_COMPARISONCHART_FIELD_PROPERTY_VALUE_DESCRIPTION'); ?>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php
			if ($rows) {foreach ($rows as $i => $row) {
			if ($row->type == 'spacer') {
		?>
		<tr class="row<?php echo $i % 2; ?>">
			<td class="center" valign="top">
				<strong>
					<?php echo $row->name ?>
				</strong>
			</td>
                        <td class="center" valign="top" colspan="2">
                            <i><?php echo JText::_('COM_COMPARISONCHART_SPACER'); ?></i>
			</td>
                        
		</tr>
		<?php } else { ?>
		<tr class="row<?php echo $i % 2; ?>" >
			<td class="center" valign="top" >
				<?php echo $row->name ?>
			</td>
			<td valign="top" class="cmp-row-val">
				<?php
				switch ($row->type) {
					case 'integer':
					case 'Integer':
					case 'int':
					default:
						$value = '';
						if (isset($this->value[$row->id])) {
							$value = $this->value[$row->id]->value_data;
						}
						echo '<textarea stule="width: 50%" class="inputbox" name="'.$this->name.'['.$row->id.'][value]">'.$value.'</textarea>';
						break;
					case 'check':
					case 'checkbox':
					case 'Checkbox':
						$check = '';
						$ico = 'no';
						if (isset($this->value[$row->id])) {
							if ($this->value[$row->id]->value_data) {
								$check = 'checked="checked"';
								$ico = 'yes';
							}
						}
						if ($template_settings)
							{
								$img_false = JUri::root().$template_settings->check_false;
								$img_true = JUri::root().$template_settings->check_true;
								if ($ico=='no') $img = $img_false; else $img = $img_true;
							}
						else
						{
							$img = JUri::root().'components/com_comparisonchart/assets/images/ico_'.$ico.'.gif';
						}
						echo '<input class="hide" type="checkbox" name="'.$this->name.'['.$row->id.'][value]" value="1" '.$check.' />';
						echo '<img style="cursor:pointer;" src="'.$img.'" onclick="toggleCheckbox(this);" />';
						break;
					case 'rating':
					case 'Rating':
					 		echo $this->getRatingStars($row->id);
					break;
				}
				?>
				<span>
					<?php echo $row->units; ?>
				</span>
			</td>
			<td>

				<textarea style="width: 98%;" class="inputbox" name="<?php echo $this->name.'['.$row->id.'][description]'; ?>" ><?php if (isset($this->value[$row->id]->value_description)) { echo $this->value[$row->id]->value_description; } ?></textarea>
			</td>
		</tr>
		<?php }}} else { ?>
		<tr>
			<td colspan="3" align="center" >
				<?php echo JText::_('COM_COMPARISONCHART_FIELD_NONE'); ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>