<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Settings model.
 */
class ComparisonchartModelSettings extends JModelAdmin
{
	protected $context = 'com_comparisonchart';

	protected function allowEdit($data = array(), $key = 'id')
	{
		return JFactory::getUser()->authorise('core.admin', 'com_comparisonchart');
	}

	public function getTable($type = 'settings', $prefix = 'ComparisonchartTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	
	protected function loadFormData()
	{
			$data = $this->getItem();
		return $data;
	}

    public function getItem($pk = null)
    {
        if (!isset($this->item)) {
            $db	= JFactory::getDBO();
            $db->setQuery("SELECT `extension_id` FROM #__extensions WHERE name like '%comparisonchart' AND element='com_comparisonchart'");
            $extension_id = $db->loadResult();
            $item = new JObject;;
            $item->id = $extension_id;
            $query = "SELECT `c_par_value` FROM `#__cmp_chart_settings` WHERE `c_par_name`='settings'";
            $db->setQuery($query);
            $settingsjson = $db->loadObject();
            $settings = json_decode($settingsjson->c_par_value);
            foreach ($settings as $key=>$val) {
                $item->$key = $val;
            }
        }
        return $item;
    }
	
	public function getForm($data = array(), $loadData = true)
	{
		$app	= JFactory::getApplication();

		$form = $this->loadForm('com_comparisonchart.settings', 'settings', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}	
	
	
}