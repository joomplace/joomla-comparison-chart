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

class ComparisonchartControllerRow extends JControllerAdmin {

//    function getModel($name = 'Chart', $prefix = 'Comparisonchart', $config = array('ignore_request' => true))
//    {
//        $model = parent::getModel($name, $prefix, $config);
//        return $model;
//    }

    public function save() {
        $data = array();
        $data['id'] = JRequest::getVar('id');
        $data['name'] = JRequest::getVar('name');
        $data['type'] = JRequest::getVar('type');
        $data['direction'] = JRequest::getVar('direction');
        if (isset($_GET['description'])) {
            $data['description'] = str_replace("'", "''", $_GET['description']); //JRequest::getVar('description');
        } else {
            $data['description'] = "";
        }
        $data['ordering'] = JRequest::getVar('ordering');
        $data['chart_id'] = JRequest::getVar('chart_id');

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

    public function delete() {
        $cid = JRequest::getVar('cid', false, '', 'int');

        if ($cid) {
            $model = $this->getModel('Chart');
            $table = $model->getTable('Row');
            $table->delete($cid);
        }

        exit();
    }

    public function resetrating() {
        $id = JRequest::getInt('id');
        $rid = JRequest::getInt('rid');
        $db = JFactory::getDBO();
        $query = "DELETE FROM #__cmp_chart_content WHERE item_id=" . $id . " AND row_id=" . $rid;
        $db->setQuery($query);
        $db->execute();
        $query = "DELETE FROM #__cmp_chart_rating WHERE item_id=" . $id . " AND row_id=" . $rid;
        $db->setQuery($query);
        if ($db->execute()) {
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

    public function move() {
        $order = JRequest::getVar('order', false, 'post', 'array');

        if ($order) {
            $model = $this->getModel('Chart');

            $table = $model->getTable('Row');
            $data = array(
                'id' => $order['id'],
                'ordering' => $order['ordering']
            );
            $table->save($data);
            $table = $model->getTable('Row');
            $data = array(
                'id' => $order['prev_id'],
                'ordering' => $order['prev_ordering']
            );
            $table->save($data);
        }
    }

    public function test() {
        $data = array();
        $data['elName'] = JRequest::getVar('elName');
        $data['elType'] = JRequest::getVar('elType');
        $data['best'] = JRequest::getVar('direction');
        $data['direction'] = JRequest::getVar('direction');
        if (isset($_POST['description'])) {
            $data['description'] =  str_replace("'","''", strip_tags($_POST['description'], '<p><a><span><ul><li><ol><img><br><strong><em><hr/>')); /* str_replace("'", "''", $_GET['description']); */
        } else {
            $data['description'] = "";
        }
        $data['chartid'] = JRequest::getVar('chart_id');
        $data['itemid'] = intval(JRequest::getVar('itemid'));
        $db = JFactory::getDBO();
        if ($data['itemid'] != 0) {
            $query = "UPDATE #__cmp_chart_rows
                     SET name='" . $data['elName'] . "', type='" . $data['elType'] . "',direction='" . $data['best'] . "', description='" . $data['description'] . "'
                     WHERE id=" . $data['itemid'];
            $db->setQuery($query);
            $db->execute();
            $data['action'] = 'applay';
            $data['itemid'] = intval(JRequest::getVar('itemid'));
            $query = "SELECT id, chart_id, name,type,direction,description,color,ordering FROM #__cmp_chart_rows WHERE chart_id=" . $data['chartid'] . " ORDER BY ordering";
            $db->setQuery($query);
            $prop = $db->loadAssocList();
            echo json_encode($prop);
            JFactory::getApplication()->close();
        } else {
            $query = "INSERT INTO #__cmp_chart_rows (chart_id,name,type,units,direction,description,color,ordering)
                  VALUES (" . intval($data['chartid']) . ",'" . $data['elName'] . "','" . $data['elType'] . "','','" . $data['best'] . "','" . $data['description'] . "','',1)";
            $db->setQuery($query);
            $db->execute();
            $query = "SELECT MAX(id) FROM  #__cmp_chart_rows";
            $db->setQuery($query);
            $id = $db->loadRow();
            $data['itemid'] = $id[0];
            $data['action'] = 'save';
            $query = "SELECT id, chart_id,name,type,direction,description,color,ordering FROM #__cmp_chart_rows WHERE chart_id=" . $data['chartid'] . " ORDER BY ordering";
            $db->setQuery($query);
            $prop = $db->loadAssocList();
            echo json_encode($prop);
            JFactory::getApplication()->close();
        }
    }

    public function deleteitems() {
        $data = JRequest::getVar('val');
        $chartid = JRequest::getVar('chart_id');
        $db = JFactory::getDBO();
        for ($i = 0; $i < count($data); $i++) {
            $query = "DELETE FROM #__cmp_chart_rows WHERE id=" . intval($data[$i]);
            $db->setQuery($query);
            $db->query();
        }
        $query = "SELECT id, chart_id,name,type,direction,description,color,ordering FROM #__cmp_chart_rows WHERE chart_id=" . $chartid . " ORDER BY ordering";
        $db->setQuery($query);
        $prop = $db->loadAssocList();
        echo json_encode($prop);
        JFactory::getApplication()->close();
    }

    public function moverows() {
        $arr = array();
        $data = array();
        $data['el_id'] = intval(JFactory::getApplication()->input->get('el_id'));
        $data['rownumber'] = intval(JFactory::getApplication()->input->get('rownumber'));
        $data['el_move_id'] = intval(JFactory::getApplication()->input->get('el_move_id'));
        $data['el_move_ordering'] = JFactory::getApplication()->input->get('el_move_ordering');
        $data['chart_id'] = intval(JFactory::getApplication()->input->get('chart_id'));
        $arr['ids'] = JRequest::getVar('ids');
        $arr['ord'] = JRequest::getVar('ord');
        $db = JFactory::getDBO();
      
        for ($i = 0; $i < count($arr['ids']); $i++) {
            $query = "UPDATE #__cmp_chart_rows
                     SET ordering='" . $arr['ord'][$i] . "'
                     WHERE id=" . $arr['ids'][$i];
            $db->setQuery($query);
            $db->execute();
        }
     
       $query2 = "UPDATE #__cmp_chart_rows
                     SET ordering='" . $data['el_move_ordering'] . "'
                     WHERE id=" . $data['el_id'];
        $db->setQuery($query2);
       $db->execute();
    
        $query3 = "UPDATE #__cmp_chart_rows
                     SET ordering='" . $data['rownumber'] . "'
                     WHERE id=" . $data['el_move_id'];
        $db->setQuery($query3);
        $db->execute();
        $query = "SELECT id, chart_id, name,type,direction,description,color,ordering FROM #__cmp_chart_rows WHERE chart_id=" . $data['chart_id'] . " ORDER BY ordering";
        $db->setQuery($query);
        $prop = $db->loadAssocList();
        echo json_encode($prop);
        JFactory::getApplication()->close();
    }

}
