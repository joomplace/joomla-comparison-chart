<?php

/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

class ComparisonChartControllerItem extends JControllerForm
{
	public function getItem()
	{
		$app =JFactory::getApplication();

		$model = $this->getModel('item');
		$item = $model->getItem();

		$out = new stdClass();
		$out->title = $item->title;
		$out->img = JURI::root().$item->image;
		$out->description = $item->description;

		echo json_encode($out);

		$app->close();
	}

	public function getRow()
	{
		$app =JFactory::getApplication();

		$model = $this->getModel('item');
		$row = $model->getRow();

		$out = new stdClass();
		$out->title = $row->name;
		$out->description = $row->description;

		echo json_encode($out);

		$app->close();
	}

	public function toCompare()
	{
        $jinput = JFactory::getApplication()->input;
		$state = 'error';
		$app =JFactory::getApplication();
        $session =JFactory::getSession();

		$item = JRequest::getInt('item', 0);
		$chart = JRequest::getInt('chart', 0);
        $num = JRequest::getInt('num', 0);
		if ($chart) {
			$asset = 'com_comparisonchart.chart'.$chart;
			//$items = $app->getUserState($asset);
            $items=$session->get( $asset );
			if ($item) {
				if (isset($items[$item])) {
					unset($items[$item]);

				} else {
					$items[$item] = 1;

				}
                $num =count($items);
			}

			//$app->setUserState($asset, $items);
            $session->set($asset, $items);
			$state = 'ready;'.$num;
		}
		echo $state;
		$app->close();
	}

	public function toCompareRemove()
	{
		$state = 'error';
		$app =JFactory::getApplication();

		$item = JRequest::getInt('item', 0);
		$chart = JRequest::getInt('chart', 0);

		if ($chart) {
			$asset = 'com_comparisonchart.chart'.$chart;
			$items = $app->getUserState($asset);
			if ($item) {
			}
			$app->setUserState($asset, $items);
			$state = 'ready';
		}

		echo $state;

		$app->close();
	}

	public function getCompare()
	{
		$app =JFactory::getApplication();
        $jinput = JFactory::getApplication()->input;
		$chart = JRequest::getVar('chart', 0, 'get', 'int');
		$asset = 'com_comparisonchart.chart'.$chart;
		$items = $app->getUserState($asset);

		echo json_encode($items);

		$app->close();
	}

	public function getRateAjax()
	{
        $jinput = JFactory::getApplication()->input;
		$user_rating = $jinput->get('user_rating',0,INT);
		$id = $jinput->get('id',0,INT);
		$rid = $jinput->get('rid',0,INT);
		$db  =JFactory::getDBO();
		if (($user_rating >= 1) and ($user_rating <= 5) && $id && $rid){
			$currip = ( phpversion() <= '4.2.1' ? @getenv( 'REMOTE_ADDR' ) : $_SERVER['REMOTE_ADDR'] );
				$id = intval($id);
				$rid = (int)$rid;
				$query = "SELECT * FROM #__cmp_chart_rating WHERE item_id=".$id." AND row_id=".$rid;
				$db->setQuery( $query );
				$votesdb = $db->loadObject();
				if ( !$votesdb ) {
					$query = "INSERT INTO #__cmp_chart_rating (item_id,lastip,sum,count,row_id)"
					. "\n VALUES (".$id.",".$db->Quote($currip).",".$user_rating.",1,".$rid.")";
					$db->setQuery( $query );
					$db->execute() or die( $db->stderr() );;
				} else {
					if ($currip != ($votesdb->lastip)) {
						$query = "UPDATE #__cmp_chart_rating"
						. "\n SET count = count + 1, sum = sum + " .  $user_rating . ", lastip = " . $db->Quote( $currip )
						. "\n WHERE item_id=".$id." AND row_id=".$rid;
						$db->setQuery( $query );
						$db->execute() or die( $db->stderr() );
					} else {
						echo 'voted';
						exit();
					}
				}
			}
			
			$query = "SELECT `sum` FROM #__cmp_chart_rating WHERE item_id=".$id." AND row_id=".$rid;
			$db->setQuery( $query );
			$sum = $db->loadResult();
			
			$query = "SELECT `id` FROM #__cmp_chart_content WHERE item_id=".$id." AND row_id=".$rid;
			$db->setQuery( $query );
			$cid = $db->loadResult();
			if (!$cid)
			{
				
				$query = "INSERT INTO #__cmp_chart_content (row_id,item_id,value_data,value_description)"
					. "\n VALUES (".$rid.",".$id.",".(int)$sum.",'')";
					$db->setQuery( $query );
					$db->execute() or die( $db->stderr() );;
			}else
			{
				$query = "UPDATE #__cmp_chart_content"
						. "\n SET value_data = ".(int)$sum
						. "\n WHERE item_id=".$id." AND row_id=".$rid;
						$db->setQuery( $query );
						$db->execute() or die( $db->stderr() );
			}
			
		echo 'thanks';exit();
	}

/*SMT*/	

	public function clearCompare()
	{
		$app =JFactory::getApplication();
		$chart = JRequest::getInt('chart', 0);
		if ($chart) {
			$asset = 'com_comparisonchart.chart'.$chart;
			$items = null;
			$app->setUserState($asset, $items);
			$items = $app->getUserState($asset);
            $session = JFactory::getSession();
            $session->clear('com_comparisonchart.chart'.$chart);
		}
		echo 'cleared';
		$app->close();	
	}
	
	public function toAjaxCompare()
	{
		$state = 'error';
		$app =JFactory::getApplication();

		$item = JRequest::getInt('id', 0);
		$chart = JRequest::getInt('chid', 0);

		if ($chart) {
			$asset = 'com_comparisonchart.chart'.$chart;
			$items = $app->getUserState($asset);
			if ($item) 
			{
				if (!isset($items[$item])) 
				{
					$items[$item] = 1;
				}
			}
			$app->setUserState($asset, $items);
			$state = '<a class="ch_added_compare_button" onclick="ch_del_from_compare('.$item.','.$chart.');" href="javascript:void(0);"><span>'.JText::_('COM_COMPARISONCHART_CHECKED').'</span></a>';
		}

		echo $state;

		$app->close();
	}
	
	public function toAjaxDelCompare()
	{
		$state = 'error';
		$app =JFactory::getApplication();

		$item = JRequest::getInt('id', 0);
		$chart = JRequest::getInt('chid', 0);

		if ($chart) {
			$asset = 'com_comparisonchart.chart'.$chart;
			$items = $app->getUserState($asset);
			if ($item) {
				if (isset($items[$item])) {
					unset($items[$item]);
			} else {
					$items[$item] = 1;
			}
			}
			$app->setUserState($asset, $items);
			$state = '<a class="ch_add_to_compare_button" onclick="ch_add_to_compare('.$item.','.$chart.');" href="javascript:void(0);"><span>'.JText::_('COM_COMPARISONCHART_CHECK').'</span></a>';
		}

		echo $state;

		$app->close();
	}

    public function savexls() {
        @ob_end_clean();

        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=Compare_".date("d.m.y-H:i:s").".xls");
        header("Content-Transfer-Encoding: binary ");

        $this->xlsBOF();

        $this->xlsWriteLabel(0,0,'');


        if ((isset($_POST['deleted_rows'])) || (isset($_POST['deleted_cols']))) {
                if (isset($_POST['deleted_rows'])) {
                    $deleted_rows = $_POST['deleted_rows'];
                    foreach ($deleted_rows as $dr) {
                        $new_dr[] = str_replace('row','',$dr);
                    }
                    $_POST['rows'] =  array_diff($_POST['rows'],$new_dr);
                }
                if (isset($_POST['deleted_cols'])) {
                    $deleted_cols = $_POST['deleted_cols'];
                    foreach ($deleted_cols as $dc) {
                        $new_dc[] = str_replace('col','',$dc);
                    }
                    $_POST['items'] =  array_diff($_POST['items'],$new_dc);

                }
        }



        $i = 1;
    foreach ($_POST['items'] as $items) {
        $this->xlsWriteLabel(0,$i,$_POST['name_item'.$items]);
        $i++;
    }
        $i = 1;
        foreach ($_POST['rows'] as $items) {
                $this->xlsWriteLabel($i,0,$_POST['pdsection_row'.$items]);
                $i++;
            }
    $i = 1;
    $j = 1;
    $arr = $_POST['column'];
    foreach ($_POST['rows'] as $rows) {
        foreach ($_POST['items'] as $items) {
            $this->xlsWriteLabel($i,$j,$arr[$rows][$items]);
            $j++;
        }
        $j = 1;
        $i++;
    }


        $this->xlsEOF(); //заканчиваем собирать
        die;

    }

    function xlsBOF() {
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
    return;
    }

    function xlsEOF() {
    echo pack("ss", 0x0A, 0x00);
    return;
    }

    function xlsWriteNumber($Row, $Col, $Value) {
    echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
    echo pack("d", $Value);
    return;
    }

    function xlsWriteLabel($Row, $Col, $Value ) {
    $L = strlen($Value);
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    echo $Value;
    return;
    }








}
?>