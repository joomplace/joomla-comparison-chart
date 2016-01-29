<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

// For security reasons use build in content table class
//require_once(JPATH_ROOT.DIRECTORY_SEPARATOR.'libraries'.DIRECTORY_SEPARATOR.'joomla'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'table'.DIRECTORY_SEPARATOR.'extension.php');

class ComparisonchartTableSettings extends JTable
{
    var $id = 1;

	function __construct($db) {
		parent::__construct('#__extensions', 'extension_id', $db);
	}

	protected function _getAssetName()
	{
		return 'com_comparisonchart';
	}


	public function bind($array, $ignore = '')
        {

                if (isset($array['rules']) && is_array($array['rules'])) {
                        $rules = new JRules($array['rules']);
                        $this->setRules($rules);
                }
                return parent::bind($array, $ignore);
        }

    function store($updateNulls = false)
    {
        $options = array();
        $jform = JRequest::getVar('jform',array());

        if (sizeof($jform))
        {
            foreach ( $jform as $key => $jv )
            {
                if ($key!='rules')
                {
                    $options[$key]=$jv;
                }
                if ($key=='rules')
                {
                    $permissions[$key]=$jv;
                }
            }
        }

        $item = $this->item;
        $this->extension_id = $item->id;
        $this->params = json_encode($options);
        $this->permissions = json_encode($permissions['rules']);


        //echo "<pre>"; print_r($permissions['rules']); die;

        $query = "UPDATE `#__cmp_chart_settings` SET `c_par_value`='".$this->params."' WHERE `c_par_name`='settings';
                        UPDATE `#__assets` SET `rules`='".$this->permissions."' WHERE `name`='com_comparisonchart'";
        $db = JFactory::getDbo();
        $db->setQuery($query);
        if ($db->queryBatch()) {
            return parent::store();
        } else {
            return false;
        }
    }
}
