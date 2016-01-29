<?php
// no direct access
defined('_JEXEC') or die;
$document = JFactory::getDocument();
		$document->addScript('components/com_comparisonchart/assets/js/js.js');
		$document->addStyleSheet('components/com_comparisonchart/assets/css/comparisonchart.css');
?>
<?php echo $this->loadTemplate('menu');?>

<?php include('components/com_comparisonchart/views/categories/tmpl/default.php'); ?>
