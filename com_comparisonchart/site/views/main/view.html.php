<?php

/**
* JpObituary component for Joomla 1.6
* @package JpObituary
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');



include_once(JPATH_COMPONENT.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'baseview.php');

class comparisonchartViewmain extends BaseView
{
	function display($tpl = null) 
	{
        $this->chartid = $this->getchartid();
		$model = $this->getModel();
		$this->categories = $model->getCategories();
		$this->params = $model->getState('params');

		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		parent::display($tpl);
	}

    public function getChildrenChildren($id) {
        $db =JFactory::getDBO();
        $query = $db->getQuery(true);

        $query = 'SELECT *, CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(":", id, alias) ELSE id END as slug FROM `#__categories` WHERE `parent_id`='.$id;
        $db->SetQuery($query);
        $result = $db->loadObjectList();
//        echo '<pre>';
//        print_r($result);
//        echo '</pre>';

        return $result;

    }
    public function getChildrenChildrenChartID($id) {

        $db =JFactory::getDBO();

        $query="SELECT `id` FROM `#__cmp_chart_list` WHERE `catid`=".$id;
        $db->SetQuery($query);
        $catid = $db->LoadResult();
        if ($catid) {
            return $catid;} else { return 0; }
    }
    public function getchartid($id = 0) {
        if ($id) {
            $catid = $id; } else {
            $catid = JRequest::getVar('catid', 0, 'get', 'int');
        }




        $id = JRequest::getVar('id', 0, 'get', 'int');

        if (!$id) {
            $db = JFactory::getDbo();
            $query="SELECT `id` FROM `#__cmp_chart_list` WHERE `catid`=".$catid;
            $db->setQuery($query);
            $result =  $db->loadResult();
            if ($result) {
                $id = $result;
                JRequest::setVar('id', $id, 'get');
            }
        } else {

            JRequest::setVar('id', $id, 'get');
            $db = JFactory::getDbo();
            $query="SELECT `id` FROM `#__cmp_chart_list` WHERE `catid`=".$catid;
            $db->setQuery($query);
            $result =  $db->loadResult();
            if ($result) {
                $id = $result;

                JRequest::setVar('id', $id, 'get');
            }
        }
        if (!$id) $id = 0;
        return $id;
    }
}
