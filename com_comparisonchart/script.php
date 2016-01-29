<?php
/**
 * ComparisonChart component for Joomla 1.7 & Joomla 2.5
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

class com_comparisonchartInstallerScript {

    function install($parent) {
        echo '<font style="font-size:2em; color:#55AA55;" >Comparisonchart component successfully installed.</font><br/><br/>';

        com_comparisonchartInstallerScript::installtemplates();
    }

    function installtemplates() {
        $db = JFactory::getDBO();
        $sql = "SELECT `id` FROM `#__cmp_chart_dashboard_items` ";
        $db->setQuery($sql);
        if (!$db->loadResult()) {
			$query = "INSERT INTO #__cmp_chart_dashboard_items(`title`,`url`,`icon`,`published`) VALUES('Categories','index.php?option=com_categories&amp;view=categories&amp;extension=com_comparisonchart', '/administrator/components/com_comparisonchart/assets/images/icon_48_categories.png', 1)";
            $db->setQuery($query);
            $db->execute();
            $query = "INSERT INTO #__cmp_chart_dashboard_items(`title`,`url`,`icon`,`published`) VALUES('Manage Items','index.php?option=com_comparisonchart&amp;view=items', '/administrator/components/com_comparisonchart/assets/images/milistones48.png', 1)";
            $db->setQuery($query);
            $db->execute();
            $query = "INSERT INTO #__cmp_chart_dashboard_items(`title`,`url`,`icon`,`published`) VALUES('Manage Charts','index.php?option=com_comparisonchart&amp;view=charts', '/administrator/components/com_comparisonchart/assets/images/categories2.png', 1)";
            $db->setQuery($query);
            $db->execute();
            $query = "INSERT INTO #__cmp_chart_dashboard_items(`title`,`url`,`icon`,`published`) VALUES('Settings','index.php?option=com_config&amp;view=component&amp;component=com_comparisonchart', '/administrator/components/com_comparisonchart/assets/images/settings-new.png', 1)";
            $db->setQuery($query);
            $db->execute();
        }
        $sql = "SELECT `id` FROM `#__cmp_chart_templates` WHERE `temp_name`='default'";
        $db->setQuery($sql);
        if (!$db->loadResult()) {
            $sql = "INSERT INTO `#__cmp_chart_templates` (`id`, `temp_name`, `header_bkg`, `header_ctext`, `spacer_bkg`, `spacer_ctext`, `odd_bkg`, `even_bkg`, `odd_hover_bkg`, `even_hover_bkg`, `odd_ctext`, `even_ctext`, `text_color`, `table_header_font`, `table_header_font_size`, `table_spacer_font`, `table_spacer_font_size`, `table_row_font`, `table_row_font_size`, `check_true`, `check_false`, `best_color`, `text_align`, `close_image`) VALUES
			(null, 'default', '#F1F1F1', '#292727', '#333399', '#FFFFFF', '#f4f5f5', '#ffffff', '#CFDFF1', '#ABC3D4', '#5e5c5c', '#302e2e', '', 'Verdana', 14, 'Arial', 14, 'Arial', 11, 'administrator/components/com_comparisonchart/assets/images/default_yes_1.png', 'administrator/components/com_comparisonchart/assets/images/default_no_1.png', '#FFFF33', 'center','administrator/components/com_comparisonchart/assets/images/default_hide.png');";
            $db->setQuery($sql);
            $db->execute();
        }

        
        $params = array();
        $params['id'] = 1;
        $params['chart_description'] = '1';
        $params['plg_enabled'] = '0';
        $params['pagination'] = '1';
        $params['item_image'] = '1';
        $params['item_image_width'] = '75';
        $params['row_tooltip'] = '0';
        $params['show_toogle_button'] = '0';
        $params['show_hidden_param_button'] = '0';
        $params['show_hidden_items_button'] = '0';
        $params['show_xls_export_button'] = '1';
        $params['allow_to_hide_items'] = '1';
        $params['allow_to_hide_properties'] = '1';
        $params['show_title'] = '0';
        $params['title_text'] = '';
        $paramsjson = json_encode($params);

        $query = "CREATE TABLE IF NOT EXISTS `#__cmp_chart_settings` (
	              `c_par_name` varchar(50) NOT NULL default '',
	              `c_par_value` varchar(255) NOT NULL default '',
	                UNIQUE KEY `c_par_name` (`c_par_name`)
                  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        $db->setQuery($query);
        $db->execute();
        $sql = "SELECT `c_par_value` FROM `#__cmp_chart_settings` WHERE `c_par_name`='settings'";
        $db->setQuery($sql);
        if (!$db->loadResult()) {
            $query = "INSERT INTO #__cmp_chart_settings(`c_par_name`,`c_par_value`) VALUES('settings', '" . $paramsjson . "')";
            $db->setQuery($query);
            $db->execute();
        }

    }

    function update($parent) {
        echo '<font style="font-size:2em; color:#55AA55;" >Comparisonchart component successfully updated.</font><br/><br/>';
        /* $db = JFactory::getDBO();

          $sql="ALTER TABLE `#__cmp_chart_rating` ADD `row_id` INT UNSIGNED NOT NULL ";
          $db->setQuery($sql);
          $db->execute();

          $sql="ALTER TABLE `#__cmp_chart_templates` ADD `best_color` VARCHAR( 7 ) NOT NULL,  ADD `text_align` VARCHAR( 12 ) NOT NULL";
          $db->setQuery($sql);
          $db->execute();

          $sql="ALTER TABLE `#__cmp_chart_templates`  ADD `check_true` VARCHAR(250) NOT NULL,  ADD `check_false` VARCHAR(250) NOT NULL";
          $db->setQuery($sql);
          $db->execute();

          $sql="CREATE TABLE IF NOT EXISTS `#__cmp_chart_href` (`item_id` int(11) NOT NULL, `cat_id` int(11) NOT NULL,  KEY `item_id` (`item_id`), KEY `cat_id` (`cat_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
          $db->setQuery($sql);
          $db->execute();
          $sql="ALTER TABLE `#__cmp_chart_items`  ADD `catid` INT(11) NOT NULL DEFAULT  '0'";
          $db->setQuery($sql);
          $db->execute();
          $sql="ALTER TABLE `#__cmp_chart_list`  ADD `catid` INT(11) NOT NULL DEFAULT  '0'";
          $db->setQuery($sql);
          $db->execute();
         */
        $db = JFactory::getDBO();
        $query = "CREATE TABLE IF NOT EXISTS `#__cmp_chart_settings` (
                      `c_par_name` varchar(50) NOT NULL default '',
                      `c_par_value` varchar(500) NOT NULL default '',
                        UNIQUE KEY `c_par_name` (`c_par_name`)
                      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
            $db->setQuery($query);
            $db->execute();
        $sql = "SELECT * FROM `#__cmp_chart_settings` WHERE `c_par_name`='settings'";
        $db->setQuery($sql);
        $config = $db->loadObject();
        if ($config) {
            $params = array();
            $params['id'] = $config->id;
            $params['chart_description'] = $config->chart_description;
            $params['plg_enabled'] = $config->plg_enabled;
            $params['pagination'] = $config->pagination;
            $params['item_image'] = $config->item_image;
            $params['item_image_width'] = $config->item_image_width;
            $params['show_toogle_button'] = $config->show_toogle_button;
            $params['show_hidden_param_button'] = $config->show_hidden_param_button;
            $params['show_hidden_items_button'] = $config->show_hidden_items_button;
            $params['show_xls_export_button'] = $config->show_xls_export_button;
            $params['allow_to_hide_items'] = $config->allow_to_hide_items;
            $params['allow_to_hide_properties'] = $config->allow_to_hide_properties;
            $params['show_title'] = $config->show_title;
            $params['title_text'] = $config->title_text;
            $paramsjson = json_encode($params);

            $query = "DROP TABLE `#__cmp_chart_settings`";
            $db->setQuery($query);
            $db->execute();

            $query = "CREATE TABLE IF NOT EXISTS `#__cmp_chart_settings` (
                      `c_par_name` varchar(50) NOT NULL default '',
                      `c_par_value` varchar(500) NOT NULL default '',
                        UNIQUE KEY `c_par_name` (`c_par_name`)
                      ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
            $db->setQuery($query);
            $db->execute();
            $query = "INSERT INTO `#__cmp_chart_settings` (`c_par_name`,`c_par_value`) VALUES ('settings', '" . $paramsjson . "')";
            $db->setQuery($query);
            $db->execute();

            $sql = "SELECT `id` FROM `#__cmp_chart_dashboard_items` ";
            $db->setQuery($sql);
            if (!$db->loadResult()) {
                $query = "INSERT INTO #__cmp_chart_dashboard_items(`title`,`url`,`icon`,`published`) VALUES('Manage Items','index.php?option=com_comparisonchart&amp;view=items', '/administrator/components/com_comparisonchart/assets/images/milistones48.png', 1)";
                $db->setQuery($query);
                $db->execute();
                $query = "INSERT INTO #__cmp_chart_dashboard_items(`title`,`url`,`icon`,`published`) VALUES('Manage Charts','index.php?option=com_comparisonchart&amp;view=charts', '/administrator/components/com_comparisonchart/assets/images/categories2.png', 1)";
                $db->setQuery($query);
                $db->execute();
                $query = "INSERT INTO #__cmp_chart_dashboard_items(`title`,`url`,`icon`,`published`) VALUES('Categories','index.php?option=com_categories&amp;view=categories&amp;extension=com_comparisonchart', '/administrator/components/com_comparisonchart/assets/images/icon_48_categories.png', 1)";
            $db->setQuery($query);
            $db->execute();
                $query = "INSERT INTO #__cmp_chart_dashboard_items(`title`,`url`,`icon`,`published`) VALUES('Templates','index.php?option=com_comparisonchart&amp;view=templates', '/administrator/components/com_comparisonchart/assets/images/settings-new.png', 1)";
                $db->setQuery($query);
                $db->execute();
                $query = "INSERT INTO #__cmp_chart_dashboard_items(`title`,`url`,`icon`,`published`) VALUES('Settings','index.php?option=com_config&amp;view=component&amp;component=com_comparisonchart', '/administrator/components/com_comparisonchart/assets/images/settings-new.png', 1)";
                $db->setQuery($query);
                $db->execute();
            }
        }
    }

    function postflight($type, $parent) {
        jimport('joomla.filesystem.folder');

        /* Copy template images */
        $path = JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_comparisonchart' . DIRECTORY_SEPARATOR . 'comparisonchart';
        $dest = JPATH_SITE . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'comparisonchart';
        if (!JFolder::exists($dest)) {
            if (JFolder::exists($path)) {
                JFolder::move($path, $dest);
            }
        }
        else
            JFolder::delete($path);
        /**/

        com_comparisonchartInstallerScript::updateDB();

        $imgpath = JURI::root() . '/administrator/components/com_comparisonchart/assets/images/';
        ?>
        <style type="text/css">
            .installtable
            {
                border: 1px solid #D5D5D5;
                background-color: #F7F8F9;
                width: 100%;
                padding: 10px; 
                border-collapse: collapse;
            }
            .installtable tr, .installtable th, .installtable td
            {
                border: 1px solid #D5D5D5;
            }
        </style>
        <table border="1" cellpadding="5" width="100%" class="installtable">		
            <tr>
                <td colspan="2" style="background-color: #e7e8e9;text-align:left; font-size:16px; font-weight:400; line-height:18px "><strong><img src="<?php echo $imgpath; ?>tick.png"/> Getting started.</strong> Helpfull links:</td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left:20px">
                    <div style="font-size:1.2em">
                        <ul>
                            <li><a href="index.php?option=com_comparisonchart&view=charts">Manage Charts</a></li>
                            <li><a href="index.php?option=com_comparisonchart&view=templates">Manage Templates</a></li>
                            <li><a href="http://www.joomplace.com/video-tutorials-and-documentation.html" target="_blank">Component's help</a></li>
                            <li><a href="http://www.joomplace.com/forum/joomla-components/comparison-chart.html" target="_blank">Support forum</a></li>
                            <li><a href="http://www.joomplace.com/helpdesk/ticket_submit.php" target="_blank">Submit request to our technicians</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: #e7e8e9;text-align:left; font-size:16px; font-weight:400; line-height:18px "><strong><img src="<?php echo $imgpath; ?>tick.png"/> Say your "Thank you" to Joomla community</strong></td>
            </tr>
            <tr>
                <td colspan="2" style="padding-left:20px">
                    <div style="font-size:1.2em">
                        <p style="font-size:12px;"><span style="font-size:14pt;">Say your "Thank you" to Joomla community</span> for WonderFull Joomla CMS and <span style="font-size:14pt;">help it</span> by sharing your experience with this component. It will only take 1 min for registration on <a href="http://extensions.joomla.org" target="_blank">http://extensions.joomla.org</a> and 3 minutes to write useful review! A lot of people will thank you!<br />
                            <a href="http://extensions.joomla.org/extensions/communities-a-groupware/ratings-a-reviews/11305" target="_blank"><img src="http://www.joomplace.com/components/com_jparea/assets/images/rate-2.png" title="Rate Us!" alt="Rate us at Extensions Joomla.org"  style="padding-top:5px;"/></a></p>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="background-color: #e7e8e9;text-align:left; font-size:14px; font-weight:400; line-height:18px "><strong><img src="<?php echo $imgpath; ?>tick.png"/>Latest changes: </strong></td>
            </tr>
        </table>
        <?php
        jimport('joomla.filesystem.file');
        $file = JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_comparisonchart' . DIRECTORY_SEPARATOR . 'changelog.txt';
        if (file_exists($file)) {
            $content = JFile::read($file);
            echo '<pre>' . $content . '</pre>';
        }
    }

    public function updateDB() {

        $db = JFactory::getDBO();
        $params = array();
        $params['id'] = 1;
        $params['chart_description'] = '1';
        $params['plg_enabled'] = '0';
        $params['pagination'] = '1';
        $params['item_image'] = '1';
        $params['item_image_width'] = '75';
        $params['show_toogle_button'] = '0';
        $params['show_hidden_param_button'] = '0';
        $params['show_hidden_items_button'] = '0';
        $params['show_xls_export_button'] = '1';
        $params['allow_to_hide_items'] = '1';
        $params['allow_to_hide_properties'] = '1';
        $params['show_title'] = '0';
        $params['title_text'] = '';
        $paramsjson = json_encode($params);

        $sql = "SELECT `c_par_value` FROM `#__cmp_chart_settings` WHERE `c_par_name`='settings'";
        $db->setQuery($sql);
        if (!$db->loadResult()) {
            $query = "INSERT INTO #__cmp_chart_settings(`c_par_name`,`c_par_value`) VALUES('settings', '" . $paramsjson . "')";
            $db->setQuery($query);
            $db->execute();
        }
    }

}