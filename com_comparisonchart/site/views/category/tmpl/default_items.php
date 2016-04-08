<?php

/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.modal');

?>

<?php if (($this->pagination->get('pages.total') > 1) and ($this->params->get('pagination') == 2 or $this->params->get('pagination') == 0)) { ?>
<div class="pagination" >
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<?php } ?>

<ul class="items" >
<?php foreach ($this->items as $item) { ?>
	<li>
		<h3 class="title" >
			<a class="modal" rel="{handler: 'iframe'}" href="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=itemdesc&tmpl=component&id='.$item->id); ?>" >
				<?php echo $item->title; ?></a>
                 
		</h3>
		<?php
		$id = (int)$item->id;
		$chid = (int)$item->chart_id;
		?>
		<label for="item<?php echo $item->id; ?>" class="label_check" >

			<?php
			if (isset($this->compare[$item->id])) { ?>
			<input type="checkbox" name="item[]" value="<?php echo $item->id; ?>" id="item<?php echo $item->id; ?>" checked="checked" />
			<?php } else { ?>
                        <input type="checkbox" name="item[]" value="<?php echo $item->id; ?>" id="item<?php echo $item->id; ?>" />
			<?php } ?>
			<?php echo JText::_('COM_COMPARISONCHART_CHECK');
			?>
		</label>
		<?php if ($item->image and $this->params->get('item_image', 1)) { ?>
			<img src="<?php echo '/'.JUri::root(true).$item->image; ?>" align="<?php echo $this->params->get('item_image_align', 'right'); ?>" alt="<?php echo $item->title; ?>" width="<?php echo $this->params->get('item_image_width', 128); ?>" />
		<?php } ?>
		<div class="description" >
			<?php echo $item->description; ?>
		</div>
		<br class="clr" />
	</li>
<?php } ?>
</ul>
<?php if (($this->pagination->get('pages.total') > 1) and ($this->params->get('pagination') == 1 or $this->params->get('pagination') == 2)) { ?>
<div class="pagination" >
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<?php } ?>