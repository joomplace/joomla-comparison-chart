<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted Access');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'c.title';
$user = JFactory::getUser();
$userId = $user->get('id');
$extension = 'com_comparisonchart';

$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
    Joomla.orderTable = function()
    {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>')
        {
            dirn = 'asc';
        }
        else
        {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
</script>
<?php echo $this->loadTemplate('menu'); ?>
<form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=charts'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty($this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">

        <?php echo $this->sidebar; ?>
    </div>
    <div style="margin-top: 10px;" id="j-main-container" class="span10">
    <?php else : ?>
        <div style="margin-top: 10px;" id="j-main-container">
        <?php endif; ?>

            <div id="filter-bar" class="btn-toolbar">
                <div class="filter-search btn-group pull-left">
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_COMPARISONCHART_SEARCH_TITLE'); ?></label>
                    <input type="text" name="filter_search_chart" id="filter_search" placeholder="<?php echo JText::_('COM_COMPARISONCHART_SEARCH_TITLE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search_chart')); ?>" title="<?php echo JText::_('COM_COMPARISONCHART_SEARCH_TITLE'); ?>" />
                </div>
                <div class="btn-group pull-left">
                    <button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                    <button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value = '';
        this.form.submit();"><i class="icon-remove"></i></button>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>

                <div class="btn-group pull-right hidden-phone">
                    <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?>:</label>
                    <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?>:</option>
                        <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
                        <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
                    </select>
                </div>
                <div class="btn-group pull-right">
                    <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                    <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
                        <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                    </select>
                </div>
            </div>
            <div class="clearfix"> </div>
            <table class="table table-striped" id="templatesList">
                <thead>
                    <tr>
<th width="2%">
                            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'c.id', $listDirn, $listOrder); ?>
                        </th>
                        <th width="2%" class="hidden-phone">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>
                        <th width="10%"class="pull-left" >
                            <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'c.title', $listDirn, $listOrder); ?>
                        </th>
                        <th width="8%" class="tm_left">
                            <?php echo JHtml::_('grid.sort', 'JSTATUS', 'c.published', $listDirn, $listOrder); ?>
                        </th>
                        <th width="10%">
                            <?php echo JHtml::_('grid.sort', 'COM_COMPARISONCHART_CHART_COUNT_ROWS', 'rows_count', $listDirn, $listOrder); ?> 
                        </th>
                        <th width="10%">
                            <?php echo JHtml::_('grid.sort', 'COM_COMPARISONCHART_CHART_COUNT_ITEMS', 'items_count', $listDirn, $listOrder); ?>
                        </th>
                        <th width="10%">
                            <?php echo JHtml::_('grid.sort', 'COM_COMPARISONCHART_CHART_TEMPLATE', 't.temp_name', $listDirn, $listOrder); ?>
                        </th>
                        
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="3">

                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    if (count($this->items)) {
                        foreach ($this->items as $i => $item) {
                            ?>
                            <tr class="row<?php echo $i % 2; ?>">
                                <td>
                                    <?php echo $item->id; ?>
                                </td>
                                <td  >
                                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td class="title">
                                    <?php if ($this->canDo->get('core.edit')) { ?>
                                        <a href="<?php echo JRoute::_('index.php?option=com_comparisonchart&task=chart.edit&id=' . $item->id); ?>">
                                            <?php echo $this->escape($item->title); ?>
                                        </a>
                                    <?php } else { ?>
                                        <?php echo $this->escape($item->title); ?>
                                    <?php } ?>
                                    &nbsp;[<a href="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=items&filter_chart=' . $item->id); ?>"><?php echo JText::_('COM_COMPARISONCHART_VIEW_ITEMS'); ?></a>]
                                    &nbsp;[<a href="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=chartprops&filter_chart=' . $item->id); ?>"><?php echo JText::_('COM_COMPARISONCHART_VIEW_PROP'); ?></a>]
                                </td>
                                <td >
                                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'charts.', $this->canDo->get('core.edit'), 'cb'); ?>
                                </td>
                                <td >
                                    <?php echo $item->rows_count; ?>
                                </td>
                                <td >
                                    <?php echo $item->items_count; ?>
                                </td>
                                <td>
                                    <?php echo $item->temp_name ? $item->temp_name : substr($item->css, 0, -4); ?>
                                </td>
                                
                            </tr>
                            <?php
                        }
                    } else
                    if ($this->state->get('filter.search_chart')) {
                        ?>
                        <tr>
                            <td colspan="8" class="center" >
                                <?php echo JText::_('COM_COMPARISONCHART_CHARTS_NOTFOUND'); ?>
                            </td>
                        </tr>
                        <?php
                    } else {
                        ?>
                        <tr>
                            <td colspan="8"  class="center">
                                <?php echo JText::_('COM_COMPARISONCHART_CHARTS_CREATE_NEW1'); ?> <a onclick="javascript:Joomla.submitbutton('chart.add')" href="javascript:void(0)"><?php echo JText::_('COM_COMPARISONCHART_CREATE_NEW_ONE'); ?></a>
                            </td>
                        </tr>
                    <?php } ?>
                   
                </tbody>
                <tfoot>
                            <tr>
                                <td colspan="7" >
                                       <?php echo $this->pagination ? $this->pagination->getListFooter() : ''; ?>
                                </td>
                            </tr>
                        </tfoot>
            </table>
            <div>
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
                <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </div>
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