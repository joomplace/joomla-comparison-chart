<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
?>
 <?php echo $this->loadTemplate('menu'); ?>
<table class="admin" >
    <tr>
        <td valign="top" class="lefmenutd" >
           
        </td>
        <td valign="top" width="100%" >
            <div class="helptable">
                <div class="button2-left">
                    <div class="blank">
                        <a class="modal" rel="{handler: 'iframe'}" href="index.php?option=com_comparisonchart&amp;task=history&amp;tmpl=component">
                            <?php echo JText::_('COM_COMPARISONCHART') . ' ' . ComparisonchartHelper::getVersion() . " " . JText::_('COM_COMPARISONCHART_HELP_VERHIS'); ?>
                        </a>
                    </div>
                </div>
                <div class="clr"><!----></div>
                <table class="adminlist">
                    <tr>
                        <td>
                            <div >
                                <h3><?php echo JText::_('COM_COMPARISONCHART_HELP_LINKS'); ?>:</h3>
                            </div>
                            <ul>
                                <li><a href="http://www.joomplace.com/video-tutorials-and-documentation.html" target="_blank"><?php echo JText::_('COM_COMPARISONCHART_HELP_LINK1'); ?></a></li>
                                <li><a href="http://www.joomplace.com/helpdesk/index.php" target="_blank" title="Support Desk"><?php echo JText::_('COM_COMPARISONCHART_HELP_LINK2'); ?></a></li>
                                <li><a href="http://www.joomplace.com/forum.html" target="_blank" title="Support Forum"><?php echo JText::_('COM_COMPARISONCHART_HELP_LINK3'); ?></a></li>
                                <li><a href="http://www.joomplace.com/services/joomla-support-service-packages.html" target="_blank" title="Joomla! Support"><?php echo JText::_('COM_COMPARISONCHART_HELP_LINK4'); ?></a></li>
                                <li><a href="http://www.joomplace.com/services/joomla-support-service-packages.html" target="_blank" title="Joomla! Support policy"><?php echo JText::_('COM_COMPARISONCHART_HELP_LINK5'); ?></a></li>
                                <li><a href="http://www.joomplace.com/disclaimer.html" target="_blank" title="Disclaimer"><?php echo JText::_('COM_COMPARISONCHART_HELP_LINK6'); ?></a></li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
