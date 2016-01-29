<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.database.table');

class ComparisonchartTableRow extends JTable
{
	function __construct(&$db) {
		parent::__construct('#__cmp_chart_rows', 'id', $db);
	}

	public function delete($pk = null)
	{
		$db = $this->getDBO();

		$db->setQuery('DELETE FROM #__cmp_chart_content WHERE row_id='.$pk);
		$db->execute();

		return parent::delete($pk);
	}
}