<?php

/**
* JpObituary component for Joomla 1.6
* @package JpObituary
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
?>
<div id="obituary" class="container container_6" >
	<?php if ($this->params->get('ategory_title', 1)) { ?>
	<h1 class="title" >
		<?php echo $this->params->get('title_text', JText::_('COM_COMPARISONCHART')); ?>
	</h1>
	<?php } ?>
	<?php if ($this->params->get('show_intro', 0)) { ?>
	<p class="intro" >
		<?php
			echo $this->params->get('intro_text', '');
		?>
	</p>
	<?php } ?>
	<?php if ($this->categories) { ?>
	<div class="categories" >
		<?php
		$i = 0;
		$n = count($this->categories);
		foreach ($this->categories as $category) {
		$category->params = json_decode($category->params);
		$subs_count = ($category->rgt - $category->lft - 1)/2;
		if (!$i or ($i+1)%3 == 1) {
			$class = 'alpha';
		} elseif ($n == $i+1 or ($i+1)%3 == 0) {
			$class = 'omega';
		} else {
			$class = '';
		}
		$i++;

		?>

		<div class="grid_2 <?php echo $class; ?>" >
			<?php if ($this->params->get('category_title', 1)) { ?>
			<a class="mainlevel" href="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=category&id='.$this->getchartid($category->id).'&catid='.$category->slug); ?>" >
				<?php echo $category->title; ?>
			</a>
			<?php } ?>
<!--			--><?php //if ($this->params->get('category_description', 1)) { ?>
<!--			<div class="description" >-->
<!--				--><?php
//				if ($this->params->get('category_plugin_enabled', 0)) {
//					echo JHTML::_('content.prepare', $category->description);
//				} else {
//					echo $category->description;
//				}
//				?>
<!--			</div>-->
<!--			--><?php //}
            if ($this->getChildrenChildren($category->id)) {
            echo '<ul class="subs">';
            foreach ($this->getChildrenChildren($category->id) as $sub) {
                if (!$this->getChildrenChildrenChartID($sub->id)) {
                    $chartid = $this->getchartid($sub->id);
                } else {
                    $chartid = $this->getChildrenChildrenChartID($sub->id);
                }
            echo '<li><a href="'.JRoute::_('index.php?option=com_comparisonchart&view=category&id='.$chartid.'&catid='.$sub->slug).'">'.$sub->title.'</a></li>';
            }
            echo '</ul>';
            }    ?>
		</div>
            <?php if ($class=='omega') echo '<div class="clear"></div>'; ?>
		<?php } ?>
	</div>
	<?php } ?>
	<div class="clear">&nbsp;</div>
</div>