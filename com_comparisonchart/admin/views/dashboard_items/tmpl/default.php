<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
$extension = 'com_comparisonchart';
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$search = $this->escape($this->state->get('filter.search'));
?>
<?php echo $this->loadTemplate('menu'); ?>
<form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=dashboard_items'); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (!empty($this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
        <div id="j-main-container" class="span10">
        <?php else : ?>
            <div id="j-main-container">
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th width="1%" class="nowrap center hidden-phone">
                            <input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
                        </th>
                        <th width="15%">
                            <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'title', $listDirn, $listOrder); ?>
                        </th>

                        <th width="5%">
                            <?php echo JHtml::_('grid.sort', 'COM_CHART_DASHBOARD_ITEM_URL', 'url', $listDirn, $listOrder); ?>
                        </th>
                        <th width="5%">
                            <?php echo JHtml::_('grid.sort', 'COM_CHART_DASHBOARD_ITEM_ICON', 'icon', $listDirn, $listOrder); ?>
                        </th>

                        <th width="5%" class="nowrap">
                            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
                        </th>
                        <th width="5%" class="nowrap">
                            <?php echo JHtml::_('grid.sort', 'JSTATUS', 'published', $listDirn, $listOrder); ?>
                        </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="9"><?php if ($this->pagination) echo $this->pagination->getListFooter(); ?></td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    if (sizeof($this->items)) {
                        foreach ($this->items as $i => $item) {
                            $canEdit = $user->authorise('core.edit', $extension . '.questions.' . $item->id);
                            $canCheckin = $user->authorise('core.admin', 'com_checkin');
                            $canChange = $user->authorise('core.edit.state', $extension . '.dashboard_items.' . $item->id) && $canCheckin;
                            ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td class="center">
                                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td class="item-name">
                                    <a href="<?php echo JRoute::_('index.php?option=com_comparisonchart&task=dashboard_item.edit&id='.$item->id); ?>">
                                        <?php echo $this->escape($item->title); ?>
                                    </a>
                                </td>
                                <td>
                                    <?php echo $item->url; ?>
                                </td>
                                <td>
                                    <?php
                                    echo("<img src='" . JURI::root() . $item->icon . "'>");
                                    ?>
                                </td>

                                <td>
                                    <?php echo $item->id; ?>
                                </td>
                                <td>
                                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'dashboard_items.'); ?>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="9" align="center" >
                                    <?php echo JText::sprintf('COM_CHART_DASHBOARD_ITEMS_NONE', 'dashboard items'); ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_comparisonchart&task=dashboard_item.add'); ?>" >
    <?php echo JText::_('COM_CHART_DASHBOARD_ITEMS_NONE_A'); ?>
                                </a>
                            </td>
                        </tr>
<?php } ?>
                </tbody>
            </table>
            <div>
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
                <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHtml::_('form.token'); ?>
            </div>
            </form>
<?php $improve_chart=ComparisonChartHelper::improveChart();
if(!intval($improve_chart)){
    ?>
    <div id="notification" class="chart-notification-wrap clearfix" style="clear: both">
        <div class="jb-survey">
            <span><?php echo JText::_("COM_CHART_NOTIFICMES1"); ?><a onclick="jb_dateAjaxRef()" style="cursor: pointer" rel="nofollow" target="_blank"><?php echo JText::_("COM_CHART_NOTIFICMES2"); ?></a><?php echo JText::_("COM_CHART_NOTIFICMES3"); ?><i id="close-icon" class="icon-remove" onclick="jb_dateAjaxIcon()"></i></span>
        </div>
    </div>
<?php } ?>

