<?php
/**
 * ComparisonChart component for Joomla for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted Access');
$imgpath = JURI::root() . '/administrator/components/com_comparisonchart/assets/images/';
jimport('joomla.application.component.view');

JHtml::_('behavior.tooltip');
?>
<?php echo $this->loadTemplate('menu'); ?>
<div id="pgm_dashboard">
    <?php
    if (!empty($this->menu)) {
        $menu = $this->menu;
        for ($i = 0; $i < count($menu); $i++) {
            ?>
            <div onclick="window.location = '<?php echo $menu[$i]['url']; ?>'" class="pgm-dashboard_button  btn">
                <img src="<?php echo JURI::root();
        echo $menu[$i]['icon']; ?>"/>
                <div class="pgm-dashboard_button_text"><?php echo $menu[$i]['title']; ?></div>
            </div>
        <?php }
    } else {
        echo JText::_('COM_CHART_NOT_PUBLISHED_ITEMS');
        
    }
    ?>

 <div id="dashboard_items" ><a href="index.php?option=com_comparisonchart&view=dashboard_items"><?php echo JText::_('COM_CHART_MANAGE_DASHBOARD_ITEMS'); ?></a></div>
</div>
<div id="j-main-container" class="span6 form-horizontal chart_control_panel_container well" style="margin-right: 0px;">

    <table class="table">
        <tr>
            <th colspan="100%" class="chart_control_panel_title">
                <?php echo JText::_('COM_COMPARISONCHART'); ?>&nbsp; <?php echo JText::_('COM_COMPARISONCHART_ABOUT_FOR') .
                    " 3.0+. " . JText::_('COM_CHART_BE_CONTROL_PANEL_DEVELOPED_BY'); ?> <a href="http://www.joomplace.com/" target="_blank">JoomPlace</a>.
            </th>
        </tr>
        <tr>
            <td width="120"><?php echo JText::_('COM_COMPARISONCHART_ABOUT_IVER') . ':'; ?></td>
            <td class="chart_control_panel_current_version"><?php echo $this->version;  ?></td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_COMPARISONCHART_ABOUT_LVER') . ':'; ?></td>
            <td>
                <div id="compchart_LatestVersion">
                    <a href="check_now" onclick="return compchart_CheckVersion('<?php echo JText::_('COM_COMPARISONCHART_ABOUT_VER_LOADING'); ?>');" class="update_link">
                        <?php echo JText::_('COM_COMPARISONCHART_ABOUT_CHECKNOW'); ?>
                    </a>
                </div>
            </td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_COMPARISONCHART_ABOUT_ABOUT') . ':'; ?></td>
            <td>
                <?php echo JText::_('COM_COMPARISONCHART_ABOUT_DESC'); ?>
            </td>
        </tr>
        <tr>
            <td><?php echo JText::_('COM_COMPARISONCHART_ABOUT_SF') . ':'; ?></td>
            <td>
                <a target="_blank" href="http://www.joomplace.com/forum/joomla-components/comparison-chart.html"
                    >http://www.joomplace.com/forum/joomla-components/comparison-chart.html</a>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a style="text-decoration: underline !important" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                            <strong>
                                <img src="<?php echo $imgpath; ?>tick.png"><?php echo JText::_('COM_COMPARISONCHART_ABOUT_STH'); ?>
                            </strong>
                        </a>
                    </div>
                    <div id="collapseTwo" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <table border="1" cellpadding="5" width="100%" class="thank_tabl">
                                <tr>

                                </tr>
                                <tr>
                                    <td style="padding-left:20px">
                                        <div class="thank_fdiv">
                                            <p style="font-size:12px;">
                                                <span style="font-size:14pt;"><?php echo JText::_('COM_COMPARISONCHART_ABOUT_STH'); ?></span> <?php echo JText::_('COM_COMPARISONCHART_ABOUT_STH_AND'); ?> <span style="font-size:14pt;"><?php echo JText::_('COM_COMPARISONCHART_ABOUT_STH_HELP_IT'); ?></span> <?php echo JText::_('COM_COMPARISONCHART_ABOUT_STH_SHAR'); ?> <a href="http://extensions.joomla.org/extensions/communities-a-groupware/ratings-a-reviews/11305" target="_blank">http://extensions.joomla.org/</a> <?php echo JText::_('COM_COMPARISONCHART_ABOUT_STH_ANWR'); ?>
                                            </p>
                                        </div>
                                        <div style="float:right; margin:3px 5px 5px 5px;">
                                            <a href="http://extensions.joomla.org/extensions/communities-a-groupware/ratings-a-reviews/11305" target="_blank">
                                                <img src="http://www.joomplace.com/components/com_jparea/assets/images/rate-2.png" />
                                            </a>
                                        </div>
                                        <div style="clear:both"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>

</div>



<?php $improve_chart=ComparisonChartHelper::improveChart();
if(!intval($improve_chart)){
    ?>
    <div id="notification" class="chart-notification-wrap clearfix" style="clear: both">
        <div class="jb-survey">
            <span><?php echo JText::_("COM_CHART_NOTIFICMES1"); ?><a onclick="jb_dateAjaxRef()" style="cursor: pointer" rel="nofollow" target="_blank"><?php echo JText::_("COM_CHART_NOTIFICMES2"); ?></a><?php echo JText::_("COM_CHART_NOTIFICMES3"); ?><i id="close-icon" class="icon-remove" onclick="jb_dateAjaxIcon()"></i></span>
        </div>
    </div>
<?php } ?>















