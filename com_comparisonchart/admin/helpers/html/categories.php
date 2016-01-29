<?php
// no direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
		$document->addScript('components/com_comparisonchart/assets/js/js.js');
		$document->addStyleSheet('components/com_comparisonchart/assets/css/comparisonchart.css');
?>
<?php echo $this->loadTemplate('menu');?>
<table class="admin">
	<tbody>
		<tr>
			<td valign="top" class="lefmenutd" >
				
			</td>
			<td valign="top" width="100%" >
				<?php include('components/com_comparisonchart/views/categories/tmpl/default.php'); ?>
			</td>
		</tr>
	</tbody>
</table>
