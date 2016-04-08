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
<div class="comparisonchart-item">
	<h1 class="cmp-item-header"><?php echo $this->item->title;?></h1>
	<div class="cmp-item-image">
		<img src="<?php	echo '/'.JUri::root(true).$item->image;?>" />
	</div>
	<div class="cmp-item-description">
		<?php
		 echo $item->description;
		?>
           
	</div>
	<div class="clr"><!--x--></div>
</div>