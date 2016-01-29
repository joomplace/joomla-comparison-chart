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
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 't.temp_name';
$user = JFactory::getUser();
$userId = $user->get('id');
$extension = 'com_comparisonchart';

$sortFields = $this->getSortFields();
?>
<style type="text/css" >
    #sbox-window{
 width: 420px !important;
height: 210px !important;
    }

    #directionTable{
        width: 165px;
    }
</style>
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
<form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=templates'); ?>" method="post" name="adminForm" id="adminForm">
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
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_COMPARISONCHART_SEARCH_TITLE'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_COMPARISONCHART_SEARCH_TITLE'); ?>" />
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
                        
                        <th width="2%" class="hidden-phone">
                            <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                        </th>
                         <th width="5%">
                            <?php echo JHtml::_('grid.sort', 'COM_COMPARISONCHART_TEMPLATE_ID', 't.id', $listDirn, $listOrder); ?>
                        </th>
                        <th width="40%"class="">
                            <?php echo JHtml::_('grid.sort', 'COM_COMPARISONCHART_TEMPLATE_NAME', 't.temp_name', $listDirn, $listOrder); ?> 
                        </th>
                        
                        <th width="10%" class="tm_left">
                            <?php echo JText::_('COM_COMPARISONCHART_TEMPLATES_PREVIEW'); ?>
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
                        foreach ($this->items as $i => $item) :

                            $canEdit = $user->authorise('core.edit', $extension . '.templates.' . $item->id);
                            $canCheckin = $user->authorise('core.admin', 'com_checkin');
                            $canChange = $user->authorise('core.edit.state', $extension . '.templates.' . $item->id) && $canCheckin;
                            ?>
                            <tr class="row<?php echo $i % 2; ?>" sortable-group-id="1">
                                
                                <td class="center">
                                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                </td>
                                <td>
                                    <?php echo $item->id; ?>
                                </td>
                                <td>
                                    <?php if ($canEdit) : ?>
                                        <a href="<?php echo JRoute::_('index.php?option=com_comparisonchart&task=template.edit&id=' . $item->id); ?>">
                                            <?php echo $this->escape($item->temp_name); ?></a>
                                    <?php else : ?>
                                        <?php echo $this->escape($item->temp_name); ?>
                                    <?php endif; ?>
                                </td>

                                <td class="nowrap has-context">
                                    <div class="button2-left">
                                        <div class="blank">
                                            <a class="modal" rel="{handler: 'iframe'}" href="<?php echo JRoute::_('index.php?option=com_comparisonchart&task=template.preview&id=' . $item->id . '&tmpl=component'); ?>"><?php echo JText::_('COM_COMPARISONCHART_TEMPLATES_PREVIEW'); ?></a>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                            <?php
                        endforeach;
                    }
                    else {
                        echo JText::_('COM_COMPARISONCHART_CHARTS_CREATE_NEW1');
                        ?> <a onclick="javascript:Joomla.submitbutton('template.add')" href="javascript:void(0)"><?php echo JText::_('COM_COMPARISONCHART_CREATE_NEW_ONE'); ?></a>
                    <?php }
                    ?>
                
                <tr>
                    <?php if($this->pagination->pagesTotal>1){ ?>
                    <td colspan="5">
                        <?php  echo $this->pagination ? $this->pagination->getListFooter() : ''; ?>
                    </td>
                   <?php } ?>
                </tr>
                 
                </tbody>
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