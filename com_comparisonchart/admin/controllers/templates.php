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

class ComparisonchartControllerTemplates extends JControllerForm
{
	
	public function delete()
	{
		$db = JFactory::getDBO();
		
		$data = JRequest::get('post');
		if (isset($data['cid']) && is_array($data['cid'])){
			foreach($data['cid'] as $cid){
				$db->setQuery('DELETE FROM `#__cmp_chart_templates` WHERE `id`='.$cid);
				$db->execute();
			}

			$msg='Template(s) successfully deleted';
			$link="index.php?option=com_comparisonchart&view=templates";
			$this->setRedirect( $link, $msg );
		} else {
			return false;
		}
	}
	
}
