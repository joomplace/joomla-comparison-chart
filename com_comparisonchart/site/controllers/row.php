<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');

class ComparisonchartControllerRow extends JControllerAdmin
{
//    function getModel($name = 'Chart', $prefix = 'Comparisonchart', $config = array('ignore_request' => true))
//    {
//        $model = parent::getModel($name, $prefix, $config);
//        return $model;
//    }

    public function save()
    {
        $data = array();
        $data['id'] =           JRequest::getVar('id');
        $data['name'] =         JRequest::getVar('name');
        $data['type'] =         JRequest::getVar('type');
        $data['direction'] =    JRequest::getVar('direction');
        $data['description'] =  JRequest::getVar('description');
        $data['ordering'] =     JRequest::getVar('ordering');
        $data['chart_id'] =     JRequest::getVar('chart_id');

        $model = $this->getModel('Chart');
        $table = $model->getTable('Row');

        $db = JFactory::getDBO();
        if (!$data['id']) {
            $db->setQuery("SELECT MAX(ordering) FROM #__cmp_chart_rows");
            $ord = $db->loadResult();
            $data['ordering'] = $ord + 1;
        }

        if ($table->save($data)) {
            $data['id'] = $table->id;
            echo json_encode($data);
        }

        exit();
    }

	public function delete()
	{
		$cid = JRequest::getVar('cid', false, '', 'int');

		if ($cid) {
			$model = $this->getModel('Chart');
			$table = $model->getTable('Row');
			$table->delete($cid);
		}

		exit();
	}
	
	public function resetrating()
	{
		$id = JRequest::getInt('id');
		$rid = JRequest::getInt('rid');
		$db  =JFactory::getDBO();
		$query = "DELETE FROM #__cmp_chart_content WHERE item_id=".$id." AND row_id=".$rid;
		$db->setQuery( $query );
		$db->execute();
		$query = "DELETE FROM #__cmp_chart_rating WHERE item_id=".$id." AND row_id=".$rid;
		$db->setQuery( $query );
		if ($db->execute())
		{ 
			?>
		<span class="jrate-container" style="margin-top:5px;">
					  <ul class="jrate-stars">
						<li class="current-rating" style="width:0%;"></li>
					  </ul>
		</span>
		<?php
			echo '<div class="message">successfully reset</div>';
		}
		exit();		
	}
	
	public function move()
	{
		$order = JRequest::getVar('order', false, 'post', 'array');
		
		if ($order) {
			$model = $this->getModel('Chart');

			$table = $model->getTable('Row');
			$data = array(
				'id'=>$order['id'],
				'ordering'=>$order['ordering']
			);
			$table->save($data);

			$table = $model->getTable('Row');
			$data = array(
				'id'=>$order['prev_id'],
				'ordering'=>$order['prev_ordering']
			);
			$table->save($data);
		}		
	}
}
