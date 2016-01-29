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

class ComparisonchartController extends JControllerLegacy
{
	function display($cachable = false, $urlparams = false)
	{
		$document = JFactory::getDocument();
		$document->addScript('components/com_comparisonchart/assets/js/js.js');
		$document->addStyleSheet('components/com_comparisonchart/assets/css/comparisonchart.css');

		$viewName = JFactory::getApplication()->input->get('view', 'about', 'CMD');
		$this->default_view = $viewName;
                
                require_once dirname(__FILE__).'/helpers/comparisonchart.php';
               ComparisonChartHelper::addSubmenu();
                //ComparisonChartHelper::addSubmenu($view);
		parent::display($cachable);
	}
	
	public function getstyle(){
		
		$tid = JRequest::getInt('tid');
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__cmp_chart_templates WHERE id = '".$tid."'");
		$template_settings = $db->loadObjectList();
		$settings = $template_settings[0];
		
		ob_start();
		require(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_comparisonchart'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'style.php');
		$style = @ob_get_contents();
		@ob_end_clean();
		
		@header ('Expires: Fri, 14 Mar 1980 20:53:00 GMT');
		@header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		@header ('Cache-Control: no-cache, must-revalidate');
		@header ('Pragma: no-cache');
		@header('Content-Type: text/xml');
		echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
		echo '<response>' . "\n";
		echo "\t" . '<style><![CDATA['.$style.']]></style>' . "\n";
		echo '</response>' . "\n";
		
		exit();
	}
	
	function latestVersion()
	{
		require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/Snoopy.class.php' );
                require_once dirname(__FILE__).'/helpers/comparisonchart.php';
		$tm_version= ComparisonChartHelper::getVersion();
		$s = new Snoopy();
		$s->read_timeout = 90;
		$s->referer = JURI::root();
		@$s->fetch('http://www.joomplace.com/version_check/componentVersionCheck.php?component=comparisonchart&current_version='.urlencode($tm_version));
		$version_info = $s->results;
		$version_info_pos = strpos($version_info, ":");
		if ($version_info_pos === false) {
			$version = $version_info;
			$info = null;
		} else {
			$version = substr( $version_info, 0, $version_info_pos );
			$info = substr( $version_info, $version_info_pos + 1 );
		}
		if ($s->error || $s->status != 200) {
			echo '<font color="red">Connection to update server failed: ERROR: ' . $s->error . ($s->status == -100 ? 'Timeout' : $s->status).'</font>';
		} else if($version == $tm_version) {
			echo '<font color="green">' . $version . '</font>' . $info;
		} else {
			echo '<font color="red">' . $version . '</font>&nbsp;<a href="http://www.joomplace.com/members-area.html" target="_blank">(Upgrade to the latest version)</a>' ;
		}
		exit();
	}
	
	public function latestNews()
	{
		require_once(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/Snoopy.class.php' );
		$s = new Snoopy();
		$s->read_timeout = 10;
		$s->referer = JURI::root();
		@$s->fetch('http://www.joomplace.com/news_check/componentNewsCheck.php?component=comparisonchart');
		$news_info = $s->results;
		
		if ($s->error || $s->status != 200) {
			echo '<font color="red">Connection to update server failed: ERROR: ' . $s->error . ($s->status == -100 ? 'Timeout' : $s->status).'</font>';
		} else {
			echo $news_info;
		}
		exit();
	}

	public function history()
	{
		echo '<h2>'.JText::_('COM_COMPARISONCHART_VERSION_HISTORY').'</h2><br/>';
		jimport ('joomla.filesystem.file');
		if (!JFile::exists(JPATH_COMPONENT_ADMINISTRATOR.'/changelog.txt')) {
			echo 'History file not found.';
		} else {
			echo '<textarea class="editor" rows="30" cols="50" style="width:100%">';
			echo JFile::read(JPATH_COMPONENT_ADMINISTRATOR.'/changelog.txt');
			echo '</textarea>';
		}
		exit();
	}
	
	function downloadsample()
	{
		$filename= 'sample.csv';
				$fname = str_replace(" ","_",$filename);
				$path = JPATH_COMPONENT_ADMINISTRATOR . "/assets/";
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: private",false);
				header("Content-Type: text/x-csv");
				header("Content-Disposition: attachment; filename=".$fname);
				header("Content-Transfer-Encoding: binary");
				header("Content-Length: ".@filesize($path.$filename));
				@set_time_limit(0);
				@$this->RT_readfile($path.$filename) or die("File not found.");
				exit;
	}
	
	function RT_readfile($filename, $retbytes=true){
	
   $chunksize = 1*(1024*1024); 
   $buffer = '';
   $cnt =0;

   $handle = fopen($filename, 'rb');
   if ($handle === false)
   {
       return false;
   }
   while (!feof($handle))
   {
       $buffer = fread($handle, $chunksize);
       echo $buffer;
	   flush();
       if ($retbytes) {
           $cnt += strlen($buffer);
       }
   }
       $status = fclose($handle);
   if ($retbytes && $status)
   {
       return $cnt; 
   }
   return $status;

}
	
	function migration()
	{$document = JFactory::getDocument();
	 $document->addStyleSheet('components/com_comparisonchart/assets/css/comparisonchart.css');
		?>
		
					<?php
										
					$db = JFactory::getDBO();
					
					
					$query = "SHOW TABLES LIKE '%rt_chart_list%'";
						$db->setQuery($query);
						$list_table = $db->loadResult();
					
						$query = "SHOW TABLES LIKE '%rt_chart_content%'";
						$db->setQuery($query);
						$chart_table = $db->loadResult();
						
						$query = "SHOW TABLES LIKE '%rt_chart_rows%'";
						$db->setQuery($query);
						$rows_table = $db->loadResult();
						
						$query = "SHOW TABLES LIKE '%rt_chart_options%'";
						$db->setQuery($query);
						$options_table = $db->loadResult();
						
					
					if ($chart_table && $rows_table && $options_table && $list_table)
					{
					?>
					<h3><?php echo JText::_('COM_COMPARISONCHART_MIGRATION_STEP2');?></h3>
					<?php
						
						$db->setQuery("SELECT * FROM `".$list_table."`");
						$list_objs = $db->loadObjectList();
						
						if (sizeof($list_objs))
						{							
							foreach ( $list_objs as $chart ) 
							{
								//echo 'SMT DEBUG: <pre>'; print_R($chart); echo '</pre>';
								echo '<ul  style="list-style: none outside none;">';
								$db->setQuery("SELECT `id` FROM `#__cmp_chart_list` WHERE `title`='".$chart->rt_name."'");
								$db->execute();
								$rt_id = $db->loadResult();
								
								if (!$rt_id)
								{
									$db->setQuery("INSERT INTO `#__cmp_chart_list` (`id` ,`title` ,`alias` ,`css` ,`description_before` ,`description_after` ,`hits` ,`published`)
												VALUES ( NULL , '".$chart->rt_name."', '".JFilterOutput::stringURLSafe($chart->rt_name)."', 'default.css', '".$chart->rt_descriptions_before."', '".$chart->rt_descriptions_after."', '0', '".$chart->rt_include."');");
									$db->execute();
									$rt_id = $db->insertid();
									echo '<div class="cmp_succ_import">Chart "'.$chart->rt_name.'" successfully added</div>';
								}else echo '<div class="cmp_succ_import">Chart "'.$chart->rt_name.'" is already isset</div>';
								
								$db->setQuery("SELECT * FROM `".$options_table."` WHERE `chart_id`=".$chart->id);
								$list_options = $db->loadObjectList();
								if (sizeof($list_options))
								{							
									foreach ( $list_options as $option ) 
									{
										//echo 'SMT DEBUG: <pre>'; print_R($option); echo '</pre>';
								
										$db->setQuery("SELECT `id` FROM `#__cmp_chart_rows` WHERE `name`='".$option->rto_name."'");
										$opt_id = $db->loadResult();
										
										if (!$opt_id)
										{
											$type="spacer";
											if ($option->rto_check==1) $type = "check"; else
											if ($option->rto_text==1)  $type = "text";
											
											
											$db->setQuery("INSERT INTO `#__cmp_chart_rows` (`id` ,`chart_id` ,`name` ,`type` ,`units` ,`direction` ,`description` ,`color` ,`ordering`)
														VALUES ( NULL , '".$rt_id."', '".$option->rto_name."', '".$type."', '', 'none', '".$option->rto_description."', '".($option->rto_color?('#'.$option->rto_color):'')."','0');");
											$db->execute();
											$opt_id = $db->insertid();
											 echo '<li style="padding-left: 21px;"><div class="cmp_succ_import">Option "'.$option->rto_name.'" successfully added</div></li>';											
										}else echo '<li style="padding-left: 21px;"><div class="cmp_succ_import">Option "'.$option->rto_name.'" is already isset</div></li>';
								
									}
								}
								echo '</ul>';
								
							}
							
						}
						/*ITEMS aka (old)rows*/
					?>
					<h3><?php echo JText::_('COM_COMPARISONCHART_MIGRATION_STEP3');?></h3>
					<?php	
						$db->setQuery("SELECT * FROM `".$rows_table."`");
						$items_objs = $db->loadObjectList();
						
						if (sizeof($items_objs))
						{							
							foreach ( $items_objs as $item ) 
							{
								$db->setQuery("SELECT `id` FROM `#__cmp_chart_items` WHERE `title`='".$item->row_name."'");
								$item_id = $db->loadResult();
									echo '<ul  style="list-style: none outside none;">';
								if (!$item_id)
										{
											$db->setQuery("SELECT `rt_name` FROM `".$list_table."` WHERE `id`=".$item->chart_id);
											$chartname = $db->loadResult();
											$db->setQuery("SELECT `id` FROM `#__cmp_chart_list` WHERE `title`= '".$chartname."'");
											$chartid = $db->loadResult();
											if ($chartid)
											{											
											$db->setQuery("INSERT INTO `#__cmp_chart_items` (`id`, `title`, `description`, `image`, `link`, `chart_id`, `ordering`, `published`, `template`, `user_rating`, `title_height`) 
														VALUES ( NULL , '".$item->row_name."', '', '', '', '".$chartid."', '0','1','0','0','0');");
											 $db->execute();
											$item_id = $db->insertid();
											echo '<li style="padding-left: 21px;"><div class="cmp_succ_import">Item "'.$item->row_name.'" successfully added</div></li>';
											}
																						
										}else echo '<li style="padding-left: 21px;"><div class="cmp_succ_import">Item "'.$item->row_name.'" is already isset</div></li>';						
								echo '</ul>';
							}
						}
						
						/*CONTENT*/
						
						$db->setQuery("SELECT * FROM `".$chart_table."`");
						$content_objs = $db->loadObjectList();
						
						if (sizeof($content_objs))
						{							
							foreach ( $content_objs as $content ) 
							{
								$item_id=$row_id=0;
								
								$db->setQuery("SELECT `rto_name` FROM `".$options_table."` WHERE `id`=".$content->opt_id);
								$optname = $db->loadResult();
								
								if ($optname)
								{
								$db->setQuery("SELECT `id` FROM `#__cmp_chart_rows` WHERE `name`='".$optname."'");
								$row_id = $db->loadResult();
								
								 if ($row_id)
								 {
								 	$db->setQuery("SELECT `type` FROM `#__cmp_chart_rows` WHERE `id`='".$row_id."'");
									$type = $db->loadResult();
									if ($type=='text') 
									{
										$content->value_check=$content->value_text;
										$content->value_text='';
									}
								 }
								 	
								
								}
								
								$db->setQuery("SELECT `row_name` FROM `".$rows_table."` WHERE `id`=".$content->row_id);
								$rowname = $db->loadResult();
								
								if ($rowname)
								{
								$db->setQuery("SELECT `id` FROM `#__cmp_chart_items` WHERE `title`='".$rowname."'");
								$item_id = $db->loadResult();	
								}
								
								if ($row_id && $item_id)
								{
									$db->setQuery("SELECT `id` FROM `#__cmp_chart_content` WHERE `row_id`=".$row_id." AND `item_id`=".$item_id);
									$cont = $db->loadResult();
									if (!$cont)
									{
											$db->setQuery("INSERT INTO `#__cmp_chart_content` (`id`, `row_id`, `item_id`, `value_data`, `value_description`)  
														VALUES ( NULL , '".$row_id."', '".$item_id."', '".$content->value_check."','".$content->value_text."');");
											$db->execute();	
																					
									}
								}
											
							}
						}
					?>
					<h3><?php echo JText::_('COM_COMPARISONCHART_MIGRATION_STEP4');?></h3>
					<div class="cmp_succ_import"><b>Completed</b></div>										
					<?php
					}

	}
	
	  public function datedb()
    {
        $db = JFactory::getDbo();

                $query	= "UPDATE `#__cmp_chart_setup` SET `c_par_value`='1' WHERE `c_par_name`='improve_chart'";
                $db->setQuery( $query );
                $db->execute();

    }
	
}