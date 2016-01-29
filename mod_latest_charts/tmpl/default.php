<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access'); 
?>

<ul class="list-charts<?php echo $params->get('moduleclass_sfx'); ?>">
<?php

if (sizeof($list))
  foreach( $list as $chart)
  {
    $link	= JRoute::_('index.php?option=com_comparisonchart&view=charts&id='.$chart->id);
?>
    <li>
      <span>
        <a href="<?php echo $link;?>"><?php echo $chart->title; ?></a>
      
      </span>
    </li>
<?php
  }
?>
</ul>
