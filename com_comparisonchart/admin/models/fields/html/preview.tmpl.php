<?php

/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;

$path = JUri::root().'components/com_comparisonchart/assets/images/';

?>
<style>
<?php 

$db = JFactory::getDBO();
$db->setQuery("SELECT * FROM #__cmp_chart_templates");
$templates_settings = $db->loadObjectList();

if (count($templates_settings)){
	foreach($templates_settings as $settings){
		ob_start();
		include(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_comparisonchart'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'style.php');
		$style = @ob_get_contents();
		@ob_end_clean();
		echo $style;
	}
}

?>
</style>
<div class="comparisonchart style-<?php echo $value; ?>" >
<table cellspacing="0" cellpadding="0" border="0" class="pdtable" <?php echo $tbl_style; ?>>
	<tbody>
		<tr class="pdtitle" >
			<td valign="top">
				<!--x-->
			</td>
			<td valign="top" width="20%" align="center" >
				<strong>Item 1</strong>
				<img class="ch_hide_item" src="<?php echo $path; ?>default_hide.png" alt="">
			</td>
			<td valign="top" width="20%" align="center">
				<strong>Item 2</strong>
				<img class="ch_hide_item" src="<?php echo $path; ?>default_hide.png" alt="">
			</td>
			<td valign="top" width="20%" align="center">
				<strong>Item 3</strong>
				<img class="ch_hide_item" src="<?php echo $path; ?>default_hide.png" alt="">
			</td>
		</tr>
		<tr class="pdsection" >
			<td colspan="4" >Separator</td>
		</tr>
		<tr class="pline odd" >
			<td class="pdinfohead" >
				<img class="ch_hide_property" src="<?php echo $path; ?>default_hide.png" alt="">
				Property 1
			</td>
			<td class="">
				<span class="cmp-yes">&nbsp;</span>
			</td>
			<td>
				<span class="cmp-no">&nbsp;</span>
			</td>
			<td>
				<span class="cmp-no">&nbsp;</span>
			</td>
		</tr>
		<tr class="pline even" >
			<td class="pdinfohead">
				<img class="ch_hide_property" src="<?php echo $path; ?>default_hide.png" alt="">
				<span class="hasTip" title="Property2::Property2 description" >Property 2</span>
			</td>
			<td>1000</td>
			<td>500</td>
			<td class="">100</td>
		</tr>
		<tr class="pline odd equal">
			<td class="pdinfohead">
				<img class="ch_hide_property" src="<?php echo $path; ?>default_hide.png" alt="">
				Property 3
			</td>
			<td>
				<span class="cmp-no">&nbsp;</span>
			</td>
			<td>
				<span class="cmp-no">&nbsp;</span>
			</td>
				<td><span class="cmp-no">&nbsp;</span>
			</td>
		</tr>
	</tbody>
</table>
<br class="clr" />
<br />
</div>