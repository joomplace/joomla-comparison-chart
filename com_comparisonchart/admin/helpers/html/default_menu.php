<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.modal');
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'administrator/components/com_comparisonchart/assets/css/comparisonchart.css');
$view = JRequest::getCmd('view');
switch ($view) {
    case 'categories':
    case 'charts':

    case 'chart':
    case 'items':
    case 'item':
    case 'templates':
    case 'template':
        $show = 1;
        break;
    case 'settings':
    case 'migration':
        $show = 2;
        break;
    case 'help':
        $show = 3;
        break;

    default:
        $show = 0;
        break;
}
$arr=ComparisonChartHelper::checkParamsSetting();
if(empty($arr)){
    ComparisonChartHelper::setSettings();
}

?>

<div id="jp-navbar" class="navbar navbar-static navbar-inverse">
    <div class="navbar-inner">
        <div class="container" style="width: auto;">
            <a class="brand" href="index.php?option=com_comparisonchart&view=about"><img class="jp-panel-logo" src="<?php echo JURI::root() ?>administrator/components/com_comparisonchart/assets/images/joomplace-logo.png" /> <?php echo JText::_('COM_COMPARISONCHART_JOOMPLACE') ?></a>
            <ul class="nav" role="navigation">
                <li class="dropdown">
                    <a id="control-panel" href="index.php?option=com_comparisonchart&view=about" role="button" class="dropdown-toggle"></a>
                </li>
            </ul>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse-comparisonchart">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse-comparisonchart nav-collapse collapse">
                <ul class="nav" role="navigation">
                    <li class="dropdown">
                        <a href="index.php?option=com_comparisonchart&view=about" id="drop-comparisonchart-dashboard" role="button" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_COMPARISONCHART_SUBMENU_ABOUT') ?></a>
                       
                    </li>
                    <li class="dropdown">
                        <a href="#"  id="drop-comparisonchart-management" role="button" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_COMPARISONCHART_MENU_MANAGEMENT') ?><b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="drop-comparisonchart-management">
                            <li class="dropdown"><a href="index.php?option=com_categories&view=categories&extension=com_comparisonchart" role="button" class="dropdown-toggle"><i class="icon-list-view"></i> <?php echo JText::_('COM_COMPARISONCHART_SUBMENU_CATEGORIES'); ?></a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?option=com_comparisonchart&view=charts"><i class="icon-chart"></i> <?php echo JText::_('COM_COMPARISONCHART_SUBMENU_CHARTS'); ?></a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?option=com_comparisonchart&view=items"><i class="icon-tablet"></i> <?php echo JText::_('COM_COMPARISONCHART_SUBMENU_ITEMS'); ?></a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?option=com_comparisonchart&view=templates"><i class="icon-pictures"></i> <?php echo JText::_('COM_COMPARISONCHART_SUBMENU_TEMPLATES'); ?></a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a href="index.php?option=com_config&view=component&component=com_comparisonchart" id="drop-comparisonchart-settings" role="button" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_COMPARISONCHART_SUBMENU_SETTINGS') ?></a>
                    </li>

                </ul>
                <ul class="nav pull-right">
                    <li id="fat-menu" class="dropdown">
                        <a href="#" id="help" role="button" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_COMPARISONCHART_SUBMENU_HELP') ?><b class="caret"></b></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="help">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="index.php?option=com_comparisonchart&view=help" target="_blank"><?php echo JText::_('COM_COMPARISONCHART_SUBMENU_HELP'); ?></a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://www.joomplace.com/forum/joomla-components/comparisonchart-component.html" target="_blank"><?php echo JText::_('COM_COMPARISONCHART_ADMINISTRATION_SUPPORT_FORUM'); ?></a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://www.joomplace.com/helpdesk/index.php" target="_blank"><?php echo JText::_('COM_COMPARISONCHART_ADMINISTRATION_SUPPORT_DESC') ?></a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="http://www.joomplace.com/helpdesk/ticket_submit.php" target="_blank"><?php echo JText::_('COM_COMPARISONCHART_ADMINISTRATION_SUPPORT_REQUEST') ?></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

 