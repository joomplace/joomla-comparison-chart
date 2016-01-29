<?php

/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');

class ComparisonchartModelImport extends JModelForm
{
	protected $context = 'com_comparisonchart';

	public function getTable($type = 'Chart', $prefix = 'ComparisonchartTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($form = 'import', $data = array(), $loadData = false)
	{
		$form = $this->loadForm('com_comparisonchart.'.$form, $form, array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) {
			return false;
		}
		return $form;
	}

	public function export($id = 0)
	{
		if (!$id) {
			return false;
		}
	
		$db =JFactory::getDBO();

		$query = $db->getQuery(true);
		$query->select('ci.id, ci.title, ci.description');
		$query->from('#__cmp_chart_items AS ci');
		$query->where('ci.chart_id='.$id);
		$db->setQuery($query);
		$items = $db->loadObjectList();

		$query = $db->getQuery(true);
		$query->select('cr.id, cr.name, cr.type');
		$query->from('#__cmp_chart_rows AS cr');
		$query->where('cr.chart_id='.$id);
		$query->where('cr.type<>"spacer"');
		$db->setQuery($query);
		$rows = $db->loadObjectList();

		$query = $db->getQuery(true);
		$query->select('cc.value_data, cc.item_id, cc.row_id');
		$query->from('#__cmp_chart_content AS cc');
		$query->join('LEFT', '#__cmp_chart_rows AS cr ON cr.id=cc.row_id');
		$query->where('cr.chart_id='.$id);
		$db->setQuery($query);
		$content = $db->loadObjectList();
		/*
		foreach ($content as $data) {
			$temp[$data->item_id][$data->row_id] = $data->value_data;
		}
		*/
		foreach ($content as $data) {
			$temp[$data->row_id][$data->item_id] = $data->value_data;
		}

		$export[0][0] = '';
		foreach ($items as $item) 
		{
			$export[0][] = $item->title;
		}
			
		foreach ($rows as $row) {
			$export[$row->id]['title'] =ucfirst($row->name);
			foreach ($items as $item) {
				$setted = isset($temp[$row->id][$item->id]) ? $temp[$row->id][$item->id] : false;
				switch ($row->type) {
					case 'int':
						$value = $setted ? (int)$temp[$row->id][$item->id] : 0;
						break;
					case 'check':
						$value = $setted ? '+' : '-';
						break;
					case 'text':
						$value = $setted ? $temp[$row->id][$item->id] : '';
						break;
				}
				$export[$row->id][$item->id] = $value;
			}
		}

/*

		$export[0][0] = '';
		foreach ($rows as $row) {
			$export[0][] = ucfirst($row->name);
		}

		foreach ($items as $item) {
			$export[$item->id]['title'] = $item->title;

			foreach ($rows as $row) {
				$setted = isset($temp[$item->id][$row->id]) ? $temp[$item->id][$row->id] : false;
				switch ($row->type) {
					case 'int':
						$value = $setted ? (int)$temp[$item->id][$row->id] : 0;
						break;
					case 'check':
						$value = $setted ? '+' : '-';
						break;
					case 'text':
						$value = $setted ? $temp[$item->id][$row->id] : '';
						break;
				}
				$export[$item->id][$row->id] = $value;
			}
		}
*/
		$out = '';
		foreach ($export as $fields) {
			$out .= $this->arrayToCSV($fields);
		}
		
		return $out;
	}

	public function import($data = array())
	{
		if (!isset($data['cid'])) {
			return false;
		}

		$db =JFactory::getDBO();
		
		$files = JRequest::getVar('jform', array(), 'files', 'array');
		$csv_file = $files['tmp_name']['file'];
		if (!$files['tmp_name']['file'])
		{
			JError::raiseWarning(0,'CVS File not found');
			return false;
		}
		
		$allcontent = array();
		
		$csv_handle = fopen($csv_file, "r");
		$items = fgetcsv($csv_handle, false, ';', '"');
		if (!empty($items[0])) {
			return false;
		}
		while (($line = fgetcsv($csv_handle, false, ';', '"')) !== false) {
			$item = new stdClass();
			if ($line) 
			{
				$query = $db->getQuery(true);
					$query->select('cr.id, cr.type');
					$query->from('#__cmp_chart_rows AS cr');
					$query->where('cr.name='.$db->Quote( $line[0]));
					$query->where('cr.`chart_id`='.$db->Quote((int)$data['cid']));
					$db->setQuery($query);
				$res = $db->loadObject();
				if (!$res) 
					{
						$type = 'check';
						if (isset($line[1]))
						{
							if ($line[1]=='-' || $line[1]=='+') {$type = 'check';} else
							if (is_int($line[1])) {$type = 'int';} else
							if (is_string($line[1])) {$type = 'text';}
						} 
						
						$row = JTable::getInstance('Row', 'ComparisonChartTable');
						$row->chart_id = (int)$data['cid'];
						$row->name 	= $line[0];
						$row->type = $type;
						$row->units = ''; 	
						$row->direction = 'none';
						$row->description = '';
						$row->color = '';
						$row->ordering = $row->getNextOrder();
						$row->store();
						$row->reorder();
						$res = $row;						
					}		
					for ($i = 1, $n = count($line); $i<$n; $i++) 
					{
						switch ($res->type) 
						{
							case 'check':
								if ($line[$i] == '+') {
									$value = 1;
								} else {
									$value = 0;
								}
								break;
							case 'int':
								$value = empty($line[$i]) ? 0 : (int)$line[$i];
								break;
							case 'text':
								$value = empty($line[$i]) ? '' : (string)$line[$i];
								break;
						}
					
						$allcontent[$items[$i]][$res->id]= $value;
					}
			}
			
		}

		foreach ( $items as $it ) 
		{
			if (!$it) continue;
			$content = array();
			$item = JTable::getInstance('Item', 'ComparisonChartTable');
				$data['title'] = $it;
				$data['chart_id'] = $data['cid'];
				$cont = $allcontent[$it];
				
				foreach ( $cont as $k=> $val ) 
				{
					$content[$k]['value']=$val;
					$content[$k]['description'] = '';
				}
				$data['content'] = $content;
				$item->save($data);
		}
			
		return true;
	}

	public function old_import($data = array())
	{
		if (!isset($data['cid'])) {
			return false;
		}

		$db =JFactory::getDBO();
		
		$files = JRequest::getVar('jform', array(), 'files', 'array');
		$csv_file = $files['tmp_name']['file'];
		if (!$files['tmp_name']['file'])
		{
			JError::raiseWarning(0,'CVS File not found');
			return false;
		}
		$csv_handle = fopen($csv_file, "r");
		$properties = fgetcsv($csv_handle, false, ';', '"');
		if (!empty($properties[0])) {
			return false;
		}
		
		
		$z = 0;
		for ($i = 1, $n = count($properties); $i<$n; $i++) {
			$query = $db->getQuery(true);
			$query->select('cr.id, cr.type');
			$query->from('#__cmp_chart_rows AS cr');
			$query->where('cr.name='.$db->Quote($properties[$i]));
			$query->where('cr.`chart_id`='.$db->Quote((int)$data['cid']));
			$db->setQuery($query);
			$res = $db->loadObject();
			if (!$res) 
			{
				$type = 'check';
				$line = fgetcsv($csv_handle, false, ';', '"');
				if (isset($line[$i]))
				{
					if ($line[$i]=='-' || $line[$i]=='+') {$type = 'check';} else
					if (is_int($line[$i])) {$type = 'int';} else
					if (is_string($line[$i])) {$type = 'text';}
				} 
				
				$row = JTable::getInstance('Row', 'ComparisonChartTable');
				$row->chart_id = (int)$data['cid'];
				$row->name 	= $properties[$i];
				$row->type = $type;
				$row->units = ''; 	
				$row->direction = 'none';
				$row->description = '';
				$row->color = '';
				$row->ordering = $row->getNextOrder();
				$row->store();
				$row->reorder();
				$res = $row;
			}
			$rows[$i] =  $res;
		}
		unset($csv_handle);
		$csv_handle = fopen($csv_file, "r");
		$properties = fgetcsv($csv_handle, false, ';', '"');		
		while (($line = fgetcsv($csv_handle, false, ';', '"')) !== false) {
			$item = new stdClass();
			if ($line) {
				$item = JTable::getInstance('Item', 'ComparisonChartTable');

				$data['title'] = $line[0];
				$data['chart_id'] = $data['cid'];

				$content = array();
				for ($i = 1, $n = count($line); $i<$n; $i++) {
					switch ($rows[$i]->type) {
						case 'check':
							if ($line[$i] == '+') {
								$value = 1;
							} else {
								$value = 0;
							}
							break;
						case 'int':
							$value = empty($line[$i]) ? 0 : (int)$line[$i];
							break;
						case 'text':
							$value = empty($line[$i]) ? '' : (string)$line[$i];
							break;
					}

					$content[$rows[$i]->id]['value'] = $value;
					$content[$rows[$i]->id]['description'] = '';
				}
				$data['content'] = $content;
				
				$item->save($data);
			}
		}
		return true;
	}

    protected function arrayToCSV($data)
    {
        $outstream = fopen("php://temp", 'r+');
        fputcsv($outstream, $data, ';', '"');
        rewind($outstream);
        $csv = fgets($outstream);
        fclose($outstream);
        return $csv;
    }
}