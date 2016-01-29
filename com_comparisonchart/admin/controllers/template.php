<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.application.component.controllerform');


class ComparisonchartControllerTemplate extends JControllerForm
{
	public function preview(){
		
		$value = JRequest::getInt('id');
		$tbl_style = 'style="align:center;width:400px;"';
		
		ob_start();
		include(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_comparisonchart'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'fields'.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'preview.tmpl.php');
		$preview = @ob_get_contents();
		@ob_end_clean();
		echo $preview;
		exit();
	}
	
}
