<?php

/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
$item = $this->item;

?>
<?php if(!empty($item)){
   $item=$item[0]; 
    ?>
<div class="comparisonchart-item">
	<h1 class="cmp-item-header"><?php echo $item->title;?></h1>
	 <?php if($item->image!=''){?>
        <div class="cmp-item-image">
           
		<img src="<?php	echo '/'.JUri::root(true).$item->image;?>" />
	</div>
         <?php }?>
	<div class="cmp-item-description">
		<?php
		 echo $item->description;
		?>
           
	</div>
	<div class="clr"><!--x--></div>
</div>
<?php }?>