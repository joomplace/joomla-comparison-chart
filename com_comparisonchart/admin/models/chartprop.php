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

class ComparisonchartModelChartprop extends JModelAdmin
{
	protected $context = 'com_comparisonchart';


	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_comparisonchart.chartprop.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Chartprop', $prefix = 'ComparisonchartTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getItem($pk = null)
	{
		if (!isset($this->item)) {
			$pk		= (!empty($pk)) ? $pk : (int) $this->getState($this->getName().'.id');
			$table	= $this->getTable();

			if ($pk > 0) {
				$return = $table->load($pk);

				if ($return === false && $table->getError()) {
					$this->setError($table->getError());
					return false;
				}
			}

			$properties = $table->getProperties(1);
			$this->item = new JObject($properties);
		}

		return $this->item;
	}
	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_comparisonchart.chartprop', 'chartprop', array('control' => 'jform', 'load_data' => false));
		$form->bind($this->getItem());

		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}


    public function publish($ids)
    {
        $db = JFactory::getDBO();
        if ($ids) {
            $query = "SELECT c.published"
                . "\n FROM #__cmp_chart_items AS c"
                . "\n WHERE c.id=" . intval($ids);
            ;
            $db->setQuery($query);
            $ordering = $db->loadResult();
            if (intval($ordering) == 1) {
                $db->setQuery("UPDATE #__cmp_chart_items  SET published=0  WHERE id=" . intval($ids));
                $db->execute();
                return $ordering;
            } else {
                $db->setQuery("UPDATE #__cmp_chart_items  SET published=1  WHERE id=" . intval($ids));
                $db->execute();
                return $ordering;
            }
            return $ordering;
        }
        return false;
    }

    public function getChartId($id)
    {
        $db = JFactory::getDBO();
            $query = "SELECT c.chart_id"
                . "\n FROM #__cmp_chart_rows AS c"
                . "\n WHERE c.id=" . intval($id);
            ;
            $db->setQuery($query);
            $chart_id = $db->loadResult();
        return  $chart_id;
    }

    public function maxId()
    {
        $db = JFactory::getDBO();
        $query = "SELECT MAX(c.id)"
            . "\n FROM #__cmp_chart_rows AS c"
           ;
        ;
        $db->setQuery($query);
        $id = $db->loadResult();
        return  $id;
    }



}