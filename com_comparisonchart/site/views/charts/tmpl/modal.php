<?php
/**
 * Testimonials Component for Joomla 3.0
 * @package Testimonials
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted Access');

JHtml::_('behavior.tooltip');
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$saveOrder = ($listOrder == 'i.ordering');
$function = JRequest::getCmd('function', 'jSelectChart');
?>
<?php echo $this->loadTemplate('menu'); ?>
<form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=charts'); ?>" method="post" name="adminForm" >
    <table class="admin" width="100%">
        <tbody>
            <tr>
                <td valign="top">
                    <fieldset id="filter-bar">
                        <div class="filter-search fltlft">
                            <label class="filter-search-lbl" for="filter_search_chart">
                                <?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>
                            </label>
                            <input type="text" name="filter_search_chart" id="filter_search_chart" value="<?php echo $this->escape($this->state->get('filter.search_chart')); ?>" title="" />
                            <button type="submit">
                                <?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>
                            </button>
                            <button type="button" onclick="document.id('filter_search_chart').value = '';
                                    this.form.submit();">
                                        <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
                            </button>
                        </div>
                    </fieldset>
                    <div class="clr"> </div>
                    <table class="adminlist">
                        <thead>
                            <tr>
                                <th>
                                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'c.title', $listDirn, $listOrder); ?>
                                </th>
                                <th width="5%">
                                    <?php echo JHtml::_('grid.sort', 'JSTATUS', 'c.published', $listDirn, $listOrder); ?>
                                </th>
                                <th width="10%">
                                    <?php echo JHtml::_('grid.sort', 'COM_COMPARISONCHART_CHART_COUNT_ROWS', 'rows_count', $listDirn, $listOrder); ?> 
                                </th>
                                <th width="10%">
                                    <?php echo JHtml::_('grid.sort', 'COM_COMPARISONCHART_CHART_COUNT_ITEMS', 'items_count', $listDirn, $listOrder); ?> 
                                </th>
                                <th width="10%"  class="jch_left">
                                    <?php echo JHtml::_('grid.sort', 'COM_COMPARISONCHART_CHART_TEMPLATE', 't.temp_name', $listDirn, $listOrder); ?>
                                </th>
                                <th width="1%" class="nowrap" >
                                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'c.id', $listDirn, $listOrder); ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>	
                        <tbody>	
                            <?php
                            if (count($this->items)) {
                                foreach ($this->items as $i => $item) {
                                    ?>
                                    <tr class="row<?php echo $i % 2; ?>">
                                        <td>
                                            <a class="pointer" onclick="if (window.parent)
                                                window.parent.<?php echo $function; ?>('<?php echo $item->id; ?>');">
                                                   <?php echo $this->escape($item->title); ?>
                                            </a>
                                        </td>
                                        <td class="center" >
                                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'charts.', false); ?>
                                        </td>
                                        <td align="center">
                                            <?php echo $item->rows_count; ?>
                                        </td>
                                        <td align="center">
                                            <?php echo $item->items_count; ?>
                                        </td>
                                        <td>
                                            <?php echo $item->temp_name ? $item->temp_name : substr($item->css, 0, -4); ?>
                                        </td>
                                        <td>
                                            <?php echo $item->id; ?>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="8" align="center" >
                                        You have no any chart
                                    </td>
                                </tr>
<?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="8" >
<?php echo $this->pagination->getListFooter(); ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="tmpl" value="component" />
        <input type="hidden" name="layout" value="modal" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHtml::_('form.token'); ?>
    </div>
</form>