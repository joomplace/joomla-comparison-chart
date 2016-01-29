<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$chart_id=JRequest::getVar('filter_chart');
$saveOrder = $listOrder == 'i.ordering';
$user = JFactory::getUser();
$userId = $user->get('id');
$extension = 'com_comparisonchart';
$app = JFactory::getApplication();

$saveOrder = $listOrder == 'i.ordering';
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_comparisonchart&task=items.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'itemsList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<style type="text/css">
    #itemsList th, td {
        text-align: left !important;
    }
</style>


<script type="text/javascript">

    Joomla.orderTable = function () {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        }
        else {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
    jQuery(document).ready(function () {
        jQuery('[name="jform[submit]"]').addClass('btn btn-small');
    });

</script>

<?php echo $this->loadTemplate('menu'); ?>

<div class="parent-wrap">

    <form action="<?php if(intval($chart_id)){echo JRoute::_('index.php?option=com_comparisonchart&view=items&filter_chart='.intval($chart_id));}else{echo JRoute::_('index.php?option=com_comparisonchart&view=items');} ?>" method="post"
          name="adminForm" id="adminForm">
        <?php if (!empty($this->sidebar)) { ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $this->sidebar; ?>
        </div>
        <div style="margin-top: 10px;" id="j-main-container" class="span10">
            <?php } else { ?>
            <div style="margin-top: 10px;" id="j-main-container">
                <?php } ?>
                <div id="filter-bar" class="btn-toolbar">
                    <div class="filter-search btn-group pull-left">
                        <!-- <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?></label>-->
                        <input type="text" name="filter_search" id="filter_search"
                               placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
                               value="<?php echo $this->escape($this->state->get('filter.search')); ?>"
                               class="hasTooltip" title="<?php echo JText::_('COM_CONTENT_FILTER_SEARCH_DESC'); ?>"/>
                    </div>
                    <div class="btn-group pull-left hidden-phone">
                        <button type="submit" class="btn hasTooltip"
                                title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i>
                        </button>
                        <button type="button" class="btn hasTooltip"
                                title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value = '';
        this.form.submit();"><i class="icon-remove"></i></button>
                    </div>
                    <div class="btn-group pull-right hidden-phone">
                        <label for="limit"
                               class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                        <?php echo $this->pagination->getLimitBox(); ?>
                    </div>

                    <div class="btn-group pull-right hidden-phone">
                        <label for="directionTable"
                               class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?>:</label>
                        <select name="directionTable" id="directionTable" class="input-medium"
                                onchange="Joomla.orderTable()">
                            <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?>:</option>
                            <option
                                value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
                            <option
                                value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
                        </select>
                    </div>
                    <div class="btn-group pull-right">
                        <label for="sortTable"
                               class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                        <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                            <option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
                            <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                        </select>
                    </div>

                </div>
                <div class="clearfix">
                    <table class="adminlist table table-striped" id="itemsList">
                        <thead>
                        <tr>
                            <th width="1%" class="title">
                                <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'i.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                            </th>
                            <th width="1%" class="title">
                                <input type="checkbox" name="checkall-toggle" value=""
                                       title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>"
                                       onclick="Joomla.checkAll(this);"/>
                            </th>
                            <th width="1%" class="nowrap item-table-id">
                                <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'i.id', $listDirn, $listOrder); ?>
                            </th>
                            <th width="15%" class="title">
                                <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'i.title', $listDirn, $listOrder); ?>
                            </th>
                            <th width="15%" class="title">
                                <?php echo JHtml::_('grid.sort', 'JSTATUS', 'i.published', $listDirn, $listOrder); ?>
                            </th>
                            <th width="15%" class="title">
                                <?php echo JHtml::_('grid.sort', 'COM_COMPARISONCHART_ITEM_CHART', 'i.chart_id', $listDirn, $listOrder); ?>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (count($this->items)) {
                            foreach ($this->items as $i => $item) {
                                ?>
                                <tr class="row<?php echo $i % 2; ?>" sortable-group-id="1"
                                    id="quest_row_<?php echo $item->id; ?>">
                                    <td class="order-items title">
                                        <?php
                                        $ordering = ($listOrder == 'i.ordering');
                                        $canEdit = $user->authorise('core.edit', $extension . '.items.' . $item->id);
                                        $canCheckin = $user->authorise('core.admin', 'com_checkin');
                                        $canChange = $user->authorise('core.edit.state', $extension . '.questions.' . $item->id) && $canCheckin;

                                        if ($canChange) :
                                            $disableClassName = '';
                                            $disabledLabel = '';
                                            if (!$saveOrder) :
                                                $disabledLabel = JText::_('JORDERINGDISABLED');
                                                $disableClassName = 'inactive tip-top';
                                            endif;
                                            ?>
                                            <span class="sortable-handler hasTooltip <?php echo $disableClassName ?>"
                                                  title="<?php echo $disabledLabel ?>">
                                                    <i class="icon-menu"></i>
                                                </span>

                                        <?php else : ?>
                                            <span class="sortable-handler inactive">
                                                    <i class="icon-menu"></i>
                                                </span>
                                        <?php endif; ?>
                                        <input type="text" style="display:none" name="order[]" size="5"
                                               value="<?php echo $item->ordering; ?>" class="width-20 text-area-order"/>
                                    </td>


                                    <td class="check-items title">
                                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                    </td>
                                    <td>
                                        <?php echo $item->id; ?>
                                    </td>
                                    <td class="title">
                                        <?php if ($this->canDo->get('core.edit')) { ?>
                                            <a href="<?php echo JRoute::_('index.php?option=com_comparisonchart&task=item.edit&id=' . $item->id); ?>">
                                                <?php echo $this->escape($item->title); ?>
                                            </a>
                                        <?php } else { ?>
                                            <?php echo $this->escape($item->title); ?>
                                        <?php } ?>
                                    </td>
                                    <td class="title publish-prop">
                                        <?php if ($item->published == 0) { ?>
                                            <a class="btn btn-micro" id="<?php echo $item->id ?>" class="item-publish"
                                               href="javascript:void(0);">
                                                <i class="icon-unpublish" ></i>
                                            </a>
                                        <?php } else { ?>
                                            <a class="btn btn-micro active" id="<?php echo $item->id ?>" class="item-publish"
                                               href="javascript:void(0);">
                                                <i class="icon-publish"></i>
                                            </a>
                                        <?php }?>
                                    </td>
                                    <td class="title">
                                        <?php echo $item->charts; ?>
                                    </td>

                                </tr>
                            <?php
                            }
                        } else
                            if ($this->state->get('filter.search')) {
                                ?>
                                <tr>
                                    <td colspan="8" align="center">
                                        <?php echo JText::_('COM_COMPARISONCHART_ITEMS_NOTFOUND'); ?>
                                    </td>
                                </tr>
                            <?php
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6" style='text-align:center !important;'>
                                        <?php echo JText::_('COM_COMPARISONCHART_ITEMS_CREATE_NEW1'); ?> <a
                                            onclick="javascript:Joomla.submitbutton('item.add')"
                                            href="javascript:void(0)"><?php echo JText::_('COM_COMPARISONCHART_CREATE_NEW_ONE'); ?></a>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6" style='text-align:center;'>
                                <?php echo $this->pagination ? $this->pagination->getListFooter() : ''; ?>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div>
                    <input type="hidden" name="task" value=""/>
                    <input type="hidden" name="boxchecked" value="0"/>
                    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
                    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
                    <?php echo JHtml::_('form.token'); ?>
                </div>
    </form>
</div>

<div id="j-sidebar-container" >
    <fieldset class="items-import">
        <h4 class="page-header"><?php echo JText::_('COM_COMPARISONCHART_IMPORT_CSV'); ?>:</h4>
        <form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&task=chart.importItems'); ?>"
              method="post" name="importForm" class="importForm" enctype="multipart/form-data">
            <?php
            foreach ($this->form->getFieldsets() as $fieldset) {
                $fields = $this->form->getFieldset($fieldset->name);
                if (count($fields) > 0) {
                   // echo JHtml::_('tabs.panel', JText::_($fieldset->label), 'item-' . $fieldset->name);
                    ?>
                    <div class="items-import-adminform">
                        <fieldset class="adminform">
                            <ul class="">
                                <?php
                                foreach ($this->form->getFieldset($fieldset->name) as $field) {
                                    ?>
                                    <li><?php
                                        echo $field->label;
                                        echo $field->input;
                                        ?></li>
                                <?php } ?>
                            </ul>
                            <br class="clr"/>

                            <div class="ex_down_sample">
                                <a href="index.php?option=com_comparisonchart&task=downloadsample"
                                   alt=""><?php echo JText::_('COM_COMPARISONCHART_DOWNLOAD_EXAMPLE'); ?></a>
                            </div>
                        </fieldset>
                    </div>
                <?php
                }
            }
            ?>
            <?php echo JHtml::_('form.token'); ?>
        </form>
</div>
</fieldset>


</div>

<script type="text/javascript">

    jQuery(document).ready(function ($) {
        $('#itemsList').on("click", ".publish-prop", function (e) {
            var id=$(this).find('a').attr('id');
            $.ajax({
                url: "index.php?option=com_comparisonchart&task=items.publishProp",
                type: "POST",
                data:{
                    'id':id
                },
                success: function (obj) {
                    var data = $.parseJSON(obj);
                    if (data.publish === '0') {
                        $('#'+id).addClass('active');
                        $('#'+id).html('<i class="icon-publish"></i>');
                    } else {
                        $('#'+id).removeClass('active');
                        $('#'+id).html('<i class="icon-unpublish"></i>');
                    }
                }
            });
        });
    });


</script>
<?php $improve_chart=ComparisonChartHelper::improveChart();
if(!intval($improve_chart)){
    ?>
    <div id="notification" class="chart-notification-wrap clearfix" style="clear: both">
        <div class="jb-survey">
            <span><?php echo JText::_("COM_CHART_NOTIFICMES1"); ?><a onclick="jb_dateAjaxRef()" style="cursor: pointer" rel="nofollow" target="_blank"><?php echo JText::_("COM_CHART_NOTIFICMES2"); ?></a><?php echo JText::_("COM_CHART_NOTIFICMES3"); ?><i id="close-icon" class="icon-remove" onclick="jb_dateAjaxIcon()"></i></span>
        </div>
    </div>
<?php } ?>