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


<form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=items'); ?>" method="post" name="adminForm" id="adminForm">
<ul class="items" >

<?php
    if ($this->items) {

        for ($i = 0; $i < count($this->items); $i++) {
            $item=$this->items;?>
	<li>
		<h3 class="title" >
                    <a class="modal" rel="{handler: 'iframe'}" href="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=itemdesc&tmpl=component&id='.$item[$i]->id); ?>" >
				<?php echo $item[$i]->title; ?></a>
		</h3>
		<?php 
		$id = (int)$item[$i]->id;
		$chid = (int)$item[$i]->chart_id;
               
		?>
        <input name="chart-id" value="<?php echo $this->chart->id;?>" type="hidden"/>
		<label for="item<?php echo $item[$i]->id; ?>" class="label_check" >
			
			<?php
			if (isset($this->compare[$item[$i]->id])) { ?>
			<input type="checkbox" name="item[]" value="<?php echo $item[$i]->id; ?>" id="item<?php echo $item[$i]->id; ?>" checked="checked" />
			<?php } else { ?>
			<input type="checkbox" name="item[]" value="<?php echo $item[$i]->id; ?>" id="item<?php echo $item[$i]->id; ?>" />
			<?php } ?>
                        
			<?php echo JText::_('COM_COMPARISONCHART_CHECK'); 
			?>
                        <input type="text" name="somename[]" value="1"/>
		</label>
		<?php if ($item[$i]->image and $this->params->get('item_image', 1)) { ?>
			<img src="<?php echo JUri::root().$item[$i]->image; ?>" align="<?php echo $this->params->get('item_image_align', 'right'); ?>" alt="<?php echo $item[$i]->title; ?>" width="<?php echo $this->params->get('item_image_width', 128); ?>" />
		<?php } ?>
		<div class="description" >
			<?php echo $item[$i]->description; ?>
		</div>
		<br class="clr" />
	</li>
<?php }
    }
    ?>


</ul>
    <?php echo $this->pagination->getLimitBox(); ?>
<?php if (($this->pagination->get('pages.total') > 1) and ($this->params->get('pagination') == 2 or $this->params->get('pagination') == 1)) { ?>
<div class="pagination" >
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<?php } ?>
     <input name="option" value="com_comparisonchart" type="hidden"/>
    <input name="view" value="items" type="hidden"/>

</form>
