<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
?>
<style type="text/css">
    .hide {
        display: none;
    }
</style>
<?php echo $this->loadTemplate('menu'); ?>
<?php if (!empty($this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">

        <?php echo $this->sidebar; ?>
    </div>
    <div style="margin-top: 10px;" id="j-main-container" class="span10">
    <?php else : ?>
        <div style="margin-top: 10px;" id="j-main-container">
        <?php endif; ?>
        <table class="admin jbsettings" width="100%">
            <tr>
                <td valign="top" class="lefmenutd" >

                </td>
                <td valign="top" width="100%">
                    <fieldset class="settingfieldset">
                        <legend><?php echo JText::_('COM_COMPARISONCHART_SETTINGS_IMPORT'); ?></legend>
                        <br/><div style="color:red;"><b><?php echo JText::_('COM_COMPARISONCHART_MIGRATION_NOTE'); ?></b>: <?php echo JText::_('COM_COMPARISONCHART_MIGRATION_NOTE_TEXT'); ?></div>
                        <h4><?php echo JText::_('COM_COMPARISONCHART_MIGRATION_STEP1'); ?></h4>
                        <?php
                        $db = JFactory::getDBO();


                        $query = "SHOW TABLES LIKE '%rt_chart_list%'";
                        $db->setQuery($query);
                        $list_table = $db->loadResult();
                        if ($list_table)
                            echo '<div class="cmp_succ_import">' . $list_table . '</div>';
                        else
                            echo '<div class="cmp_error_import"> rt_chart_list ' . JText::_('COM_COMPARISONCHART_MIGRATION_TNF') . '</div>';

                        $query = "SHOW TABLES LIKE '%rt_chart_content%'";
                        $db->setQuery($query);
                        $chart_table = $db->loadResult();

                        if ($chart_table)
                            echo '<div class="cmp_succ_import">' . $chart_table . '</div>';
                        else
                            echo '<div class="cmp_error_import"> rt_chart_content ' . JText::_('COM_COMPARISONCHART_MIGRATION_TNF') . '</div>';

                        $query = "SHOW TABLES LIKE '%rt_chart_rows%'";
                        $db->setQuery($query);
                        $rows_table = $db->loadResult();
                        if ($rows_table)
                            echo '<div class="cmp_succ_import">' . $rows_table . '</div>';
                        else
                            echo '<div class="cmp_error_import"> rt_chart_rows ' . JText::_('COM_COMPARISONCHART_MIGRATION_TNF') . '</div>';

                        $query = "SHOW TABLES LIKE '%rt_chart_options%'";
                        $db->setQuery($query);
                        $options_table = $db->loadResult();
                        if ($options_table)
                            echo '<div class="cmp_succ_import">' . $options_table . '</div>';
                        else
                            echo '<div class="cmp_error_import"> rt_chart_options ' . JText::_('COM_COMPARISONCHART_MIGRATION_TNF') . '</div>';

                        if ($chart_table && $rows_table && $options_table && $list_table) {
                            ?>
                            <br />
                            <div class="button2-left">
                                <div class="blank">
                                    <a class="modal" rel="{handler: 'iframe'}" href="index.php?option=com_comparisonchart&amp;task=migration&amp;tmpl=component">
                                        <?php echo JText::_('COM_COMPARISONCHART_MIGRATION_START'); ?>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>				
                    </fieldset>		
                    <div class="clr"></div>
                </td>
            </tr>
        </table>
        </form>
