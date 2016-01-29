<?php

/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted Access');

class ComparisonChartHelper {

    public static function addSubmenu($submenu = 'comparisonchart') {
        /* JSubMenuHelper::addEntry(JText::_('COM_COMPARISONCHART_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_comparisonchart', $submenu == 'categories');
          JSubMenuHelper::addEntry(JText::_('COM_COMPARISONCHART_SUBMENU_CHARTS'), 'index.php?option=com_comparisonchart&view=charts', $submenu == 'charts');
          JSubMenuHelper::addEntry(JText::_('COM_COMPARISONCHART_SUBMENU_ITEMS'), 'index.php?option=com_comparisonchart&view=items', $submenu == 'items');
          JSubMenuHelper::addEntry(JText::_('COM_COMPARISONCHART_SUBMENU_TEMPLATES'), 'index.php?option=com_comparisonchart&view=templates', $submenu == 'templates');
         */
        if ($submenu == 'categories') {

            ComparisonChartHelper::addManagementSubmenu('categories');
            $document = JFactory::getDocument();
            $document->setTitle(JText::_('COM_COMPARISONCHART_CATEGORIES_TITLE'));
            $controller = JControllerLegacy::getInstance('Categories');
            $view = $controller->getView('categories', 'html');
            $view->addTemplatePath(JPATH_BASE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_comparisonchart' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'html');
            $view->setLayout('categories');
        }
    }

    public static function getVersion() {
        $params = self::getManifest();
        return $params->version;
    }

    public static function getManifest() {
        $db = JFactory::getDbo();
        $db->setQuery('SELECT `manifest_cache` FROM #__extensions WHERE element="com_comparisonchart"');
        $params = json_decode($db->loadResult());
        return $params;
    }

    public static function getActions($itemID = 0) {
        $user = JFactory::getUser();
        $result = new JObject;

        if (empty($itemID)) {
            $assetName = 'com_comparisonchart';
        } else {
            $assetName = 'com_comparisonchart.chart.' . (int) $itemID;
        }

        $actions = array(
            'core.create', 'core.edit', 'core.admin', 'core.manage', 'core.chartview', 'core.delete', 'core.xls', 'core.edit.state'
        );

        foreach ($actions as $action) {
            $result->set($action, $user->authorise($action, $assetName));
        }

        return $result;
    }

    public static function getCustomCSS($template, $prefix = '') {
        if (empty($template)) {
            return false;
        }

        if ($prefix) {
            $prefix = 'div.' . $prefix;
        }

        $doc = JFactory::getDocument();

        $style = "
			" . $prefix . " table.pdtable {
				font-family: " . $template->table_row_font . ";
				font-size: " . $template->table_row_font_size . ";
			}

			" . $prefix . " table.pdtable tr.pline.odd {
				background: " . $template->odd_color_back . ";
				color: " . $template->odd_color_text . ";
			}

			" . $prefix . " table.pdtable tr.pline.odd:hover {
				background: " . $template->odd_color_hover . ";
			}

			" . $prefix . " table.pdtable tr.pline.even {
				background: " . $template->even_color_back . ";
				color: " . $template->even_color_text . ";
			}

			" . $prefix . " table.pdtable tr.pline.even:hover {
				background: " . $template->even_color_hover . ";
			}

			" . $prefix . " table.pdtable tr.pdsection {
				background: " . $template->spacer_color_back . ";
				color: " . $template->spacer_color_text . ";
				font-family: " . $template->table_spacer_font . ";
				font-size: " . $template->table_spacer_font_size . ";
			}

			" . $prefix . " table.pdtable tr.pdtitle {
				background: " . $template->header_color_back . ";
				color: " . $template->header_color_text . ";
				font-family: " . $template->table_header_font . ";
				font-size: " . $template->table_header_font_size . ";
			}
		";

        $doc->addStyleDeclaration($style);
    }

    public static function getCompare($id = 0) {
        $app = JFactory::getApplication();

        $asset = 'com_comparisonchart.chart' . $id;
        $items = $app->getUserState($asset);

        return $items;
    }

    public static function addSettingsSubmenu($vName) {
        JHtmlSidebar::addEntry(
                JText::_('COM_COMPARISONCHART_SUBMENU_SETTINGS'), 'index.php?option=com_comparisonchart&view=settings', $vName == 'settings'
        );
        JHtmlSidebar::addEntry(
                JText::_('COM_COMPARISONCHART_MENU_ITEM_MIGRATION'), 'index.php?option=com_comparisonchart&view=migration', $vName == 'migration');
    }

    public static function addManagementSubmenu($vName) {
        JHtmlSidebar::addEntry('<i class="icon-list-view"></i> '.
                JText::_('COM_COMPARISONCHART_SUBMENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_comparisonchart', $vName == 'categories'
        );
        JHtmlSidebar::addEntry('<i class="icon-chart"></i> '.
                JText::_('COM_COMPARISONCHART_SUBMENU_CHARTS'), 'index.php?option=com_comparisonchart&view=charts', $vName == 'charts');

        JHtmlSidebar::addEntry('<i class="icon-tablet"></i> '.
                JText::_('COM_COMPARISONCHART_SUBMENU_ITEMS'), 'index.php?option=com_comparisonchart&view=items', $vName == 'items');

        JHtmlSidebar::addEntry('<i class="icon-pictures"></i> '.
                JText::_('COM_COMPARISONCHART_SUBMENU_TEMPLATES'), 'index.php?option=com_comparisonchart&view=templates', $vName == 'templates');
    }

    public static function getControlPanel() {
        $db = JFactory::getDbo();
        $query = "SELECT t.*"
                . "\n FROM #__cmp_chart_dashboard_items AS t WHERE published=1"
        ;
        $db->setQuery($query);
        $templetes = $db->loadAssocList();
        return $templetes;
    }

    public static function checkParamsSetting() {
        $db = JFactory::getDBO();
        $sql = "SELECT params FROM `#__extensions` WHERE `type`='component' AND `element`='com_comparisonchart'";
        $db->setQuery($sql);
        $config = json_decode($db->loadResult(), true);
        return $config;
    }

    public static function setSettings() {
        $db = JFactory::getDBO();
        $params = array();
        $params['chart_description'] = 1;
        $params['plg_enabled'] = 0;
        $params['pagination'] = 1;
        $params['item_image'] = 1;
        $params['item_image_width'] = 75;
        $params['show_toogle_button'] = 0;
        $params['show_hidden_param_button'] = 0;
        $params['show_hidden_items_button'] = 0;
        $params['show_xls_export_button'] = 1;
        $params['allow_to_hide_items'] = 1;
        $params['allow_to_hide_properties'] = 1;
        $params['show_title'] = 0;
        $params['title_text'] = '';
        $paramsjson = json_encode($params);
        $db->setQuery("UPDATE #__extensions  SET params='" . $paramsjson . "'   WHERE `type`='component' AND `element`='com_comparisonchart'");
          $db->execute();
    }
	
	
    public static function improveChart() {
        $db = JFactory::getDBO();
        $sql = "SELECT c_par_value FROM `#__cmp_chart_setup` WHERE `c_par_name`='improve_chart'";
        $db->setQuery($sql);
        $config = $db->loadResult();
         if($config==NULL){
        if(!$config){
            $query = "INSERT INTO #__cmp_chart_setup(`c_par_name`,`c_par_value`) VALUES('improve_chart', '0')";
            $db->setQuery($query);
            $db->execute();
            $sql = "SELECT c_par_value FROM `#__cmp_chart_setup` WHERE `c_par_name`='improve_chart'";
            $db->setQuery($sql);
            $config = $db->loadResult();
        }}
       
        return $config;
    }

}