<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.modal');
$rows_count = count($this->rows);
$items_count = count($this->columns);
$col_width = floor(100 / ($items_count + 1));
if ($this->catid) {
    $link = JRoute::_('index.php?option=com_comparisonchart&view=category&catid=' . $this->catid . '&id=' . $this->chart->id);
} else {
    $link = JRoute::_('index.php?option=com_comparisonchart&view=main');
}
?>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#rgMasterTable2Container').scroll(function() {
            jQuery('.wr1').scrollLeft(jQuery('#rgMasterTable2Container').scrollLeft());
        });
        jQuery('.wr1').scroll(function() {
            jQuery('#rgMasterTable2Container').scrollLeft(jQuery('.wr1').scrollLeft());
        });
        if (jQuery("#rgMasterTable").width() <= jQuery("#rgMasterTableContainer").width()) {
            jQuery('.wr1').css('overflow-x', 'hidden');

        } else {
            jQuery('.wr1').width(jQuery('#rgMasterTable2Container').width());
            jQuery('.cl').width(jQuery("#rgMasterTable").width() - jQuery('#rgMasterTable2').find('tr').find('td:first').width());
        }
    });
</script>

    <?php if ($this->catid) { ?>
        <div class="chart-link-back"><a href="<?php echo $link; ?>" >
                <?php echo JText::_('COM_COMPARISONCHART_LINK_BACK_CAT'); ?>
            </a></div>
    <?php } ?>
    <div class="comparisonchart style-<?php echo $this->chart->css; ?>" >
        <?php
        if ($this->chart) {
            if ($this->style) {

                $this->document->addStyleDeclaration($this->style);
            }
            ?>
            <!--	--><?php if ($this->params->show_title==1) { ?>
                <h1 class="title" >
                    <?php echo ($this->params->title_text != '') ? $this->params->title_text : $this->chart->title; ?>
                </h1>
                <!--	--><?php } ?>

            <?php if ($this->chart->description_before and $this->params->chart_description==1) { ?>
                <div class="description" >
                    <?php echo $this->chart->description_before; ?>
                </div>
            <?php } ?>
            <?php

            if ($this->columns and $this->rows) {
                /*
                  <div id="upper_scroll" >
                  <div style="">&nbsp;</div>
                  </div>
                 */
                ?>
                <div class="wr1">
                    <div class="cl">
                        &nbsp;
                    </div>
                </div>


                <div class="rel" >
                    <form name="chart_xls" action="index.php?option=com_comparisonchart&task=item.savexls" method="POST" id="chart_xls" class="chart_form_<?php echo $this->chart->id ?>" />
                    <div id="rgMasterTableContainer" class="comparisonchart style-<?php echo $this->chart->css; ?>">
                        <table cellspacing="0" cellpadding="0" border="0" class="pdtable" id="rgMasterTable" >
                            <tbody>
                                <tr class="pdtitle" style="height: 20px;">
                                    <td valign="top" style="min-width:230px; vertical-align: bottom !important;" >
                                        <?php
                                        if ($this->params->show_toogle_button) {
                                            ?>
                                            <a href="#" class="ch_toggle_equal" >
                                                <?php echo JText::_('COM_COMPARISONCHART_TOGGLE_EQUAL'); ?>
                                            </a>
                                            <?php
                                        }

                                        if ($this->params->show_hidden_param_button==1) {
                                            ?>
                                            <a href="#" class="ch_show_params" >
                                                <?php echo JText::_('COM_COMPARISONCHART_SHOW_PARAMS'); ?>
                                            </a>
                                            <?php
                                        }

                                        if ($this->params->show_hidden_items_button) {
                                            ?>
                                            <a href="#" class="ch_show_items" >
                                                <?php echo JText::_('COM_COMPARISONCHART_SHOW_ITEMS'); ?>
                                            </a>
                                            <?php
                                        }
                                        
                                        if (($this->params->show_xls_export_button==1) && ($this->downloadxls)) {
                                            ?>
                                            <a href="#" class="ch_xls_export">
                                                <?php echo JText::_('COM_COMPARISONCHART_XLS_BUTTON'); ?>
                                            </a>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <?php foreach ($this->columns as $item) { ?>
                                        <td valign="top" id="col<?php echo $item->id ?>" >
                                            <input type="hidden" name="items[]" value="<?php echo $item->id ?>" />
                                            <?php if ($this->params->allow_to_hide_items==1) { ?>
                                                <a class="ch_hide_item" alt="" style="cursor:pointer;"/>
                                                <?php
                                                if ($this->template) {
                                                    if ($this->template->close_image)
                                                        echo '<img src="' . '/'.JURI::root(true) . $this->template->close_image . '" >';
                                                }
                                                ?>
                                                </a>
                                            <?php } ?>
                                            <div class="name_item">
                                                <?php if ($item->description) { ?>
                                                   <a class="modal" rel="{handler: 'iframe'}" href="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=itemdesc&tmpl=component&id='.$item->id); ?>" >
                                                        <?php
                                                        echo $item->title;
                                                        echo '<input type="hidden" value="' . $item->title . '" name="name_item' . $item->id . '" />';
                                                        ?>
                                                    </a>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <a class="modal" rel="{handler: 'iframe'}" href="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=itemdesc&tmpl=component&id='.$item->id); ?>" >
                                                   <?php
                                                    echo $item->title;?>
                                                    </a>
                                                        <?php
                                                    echo '<input type="hidden" value="' . $item->title . '" name="name_item' . $item->id . '" />';
                                                }
                                                ?>
                                            </div>
                                                    <!--<img class="ch_hide_item" src="components/com_comparisonchart/assets/images/close_compare.png" alt="" style="cursor:pointer;"/>-->
                                            <?php JHTML::_('behavior.modal', 'a.cmp-modal'); if ($item->image and $this->params->item_image==1) { ?>

                                                <div class="img_item">
													<a class="cmp-modal" href="<?php echo $item->image; ?>">
														<img src="<?php echo '/'.JUri::root(true).$item->image; ?>" alt="<?php echo $item->title; ?>" width="<?php echo $this->params->item_image_width; ?>" />
													</a>
                                                    <?php
                                                    echo '<input type="hidden" value="' . $item->image . '" name="img_item' . $item->id . '" />';
                                                    ?>
                                                </div>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                                <?php
                                $i = 0;

                                foreach ($this->rows as $row) {


                                    $style = ($row->color && $row->color != '#FFFFFF') ? "style='background-color:" . $row->color . "'" : "";
                                    if (strtolower($row->type) == 'spacer') {

                                        $i = 0;
                                        ?>
                                        <tr class="pdsection row<?php echo $row->id; ?>" <?php echo $style; ?>>
                                            <td colspan="<?php echo $items_count + 1; ?>">
                                                <span class="no-scroll"><?php echo $row->name;
                                        ?></span>
                                            </td>
                                        </tr>
                                        <?php
                                    } else {
                                        echo '<input type="hidden" value="' . $row->id . '" name="rows[]" />';
                                        $i++;
                                        ?>
                                        <tr class="pline row<?php echo $row->id; ?> <?php echo ($i % 2) ? 'odd' : 'even'; ?>" border="2" id ="row<?php echo $row->id; ?>" >
                                            <td class="pdinfohead" <?php echo $style; ?>>
                                                <?php if ($this->params->allow_to_hide_properties==1) { ?>
                                                    <a class="ch_hide_property" alt="" style="cursor:pointer;"/>
                                                    <?php
                                                    if ($this->template) {
                                                        if ($this->template->close_image)
                                                            echo '<img src="' . '/'.JURI::root(true) . $this->template->close_image . '" >';
                                                    }
                                                    ?>
                                                    </a>
                                                    <?php
                                                }

                                                if ($row->description) {
                                                   
                                                        /*echo JHTML::tooltip($row->description, $row->name, '', $row->name);
                                                        echo '<input type="hidden" value="' . $row->name . '" name="pdsection_row' . $row->id . '" />';*/
                                                    ?> <a rel="lightbox" href="<?php echo JRoute::_('index.php?option=com_comparisonchart&task=item.getRow&id=' . $row->id); ?>" >
                                                            <?php
                                                            echo $row->name;
                                                            echo '<input type="hidden" value="' . $row->name . '" name="pdsection_row' . $row->id . '" />';
                                                            ?>
                                                        </a>
                                                <?php    
                                                } else {
                                                    echo $row->name;

                                                    echo '<input type="hidden" value="' . $row->name . '" name="pdsection_row' . $row->id . '" />';
                                                }
                                                ?>
                                            </td>
                                            <?php
                                           
                                            foreach ($this->columns as $item) {

                                                $item_id = $item->id;
                                                $item_value = isset($this->content[$row->id][$item_id]) ? $this->content[$row->id][$item_id] : new JObject();
                                                if (!isset($this->rating[$item_id])) {
                                                    $this->rating[$item_id] = 0;
                                                }
                                                if (isset($item_value->value) and $row->best == $item_value->get('value', 0) and $row->best_count != $items_count && $row->best_count != NULL && $row->best != NULL) {
                                                    echo '<td class="pdadv column' . $item->id . '" >';
                                                    $this->rating[$item_id]++;
                                                } else {
                                                    echo '<td class="column' . $item->id . '" ' . $style . ' >';
                                                }

                                                switch ($row->type) {

                                                    case 'check':
                                                        if ($item_value->get('value', 0)) {
                                                            if ($this->template) {
                                                                if ($this->template->check_true) {

                                                                    echo '<img src="' . '/'.JURI::root(true) . $this->template->check_true . '" >';


                                                                    echo '<input type="hidden" value="+" name="column[' . $row->id . '][' . $item->id . ']" />';
                                                                } else {
                                                                    echo '<img src="'.'/'.JUri::root(true).'components/com_comparisonchart/assets/images/ico_yes.png" >';

                                                                    echo '<input type="hidden" value="+"  name="column[' . $row->id . '][' . $item->id . ']" />';
                                                                }
                                                            } else {
                                                                echo '<img src="'.'/'.JUri::root(true).'components/com_comparisonchart/assets/images/ico_yes.png" >';

                                                                echo '<input type="hidden" value="+"  name="column[' . $row->id . '][' . $item->id . ']" />';
                                                            }
                                                        } else {
                                                            if ($this->template->check_false) {
                                                                echo '<img src="' . '/'.JUri::root(true). $this->template->check_false . '" >';

                                                                echo '<input type="hidden" value="-"  name="column[' . $row->id . '][' . $item->id . ']" />';
                                                            } else {
                                                                echo '<img src="'.'/'.JUri::root(true).'components/com_comparisonchart/assets/images/ico_no.png" >';

                                                                echo '<input type="hidden" value="-"  name="column[' . $row->id . '][' . $item->id . ']" />';
                                                            }
                                                        }
                                                        break;
                                                    case 'rating':
                                                        echo $this->getRatingStars($item->id, $row->id);
                                                        ;
                                                        echo '<input type="hidden" value="" name="column[' . $row->id . '][' . $item->id . ']" />';
                                                        break;
                                                    case 'spacer':

                                                        break;
                                                    default:
                                                        if ($item_value) {
                                                            echo $item_value->get('value', 0);

                                                            echo '<input type="hidden" value="' . $item_value->get('value', 0) . '"  name="column[' . $row->id . '][' . $item->id . ']" />';
                                                        }
                                                        break;
                                                }
                                                if ($item_value->get('desc', false)) {
                                                    echo '&nbsp;<span style="font-style: italic;">(' . $item_value->get('desc').')</span>';
                                                }

                                                echo '</td>';
                                            }
                                            ?>
                                        </tr>
                                    <?php }
                                }
                                ?>
                                <?php
                                /*
                                  if ($this->params->get('chart_rating', 1)) { ?>
                                  <tr class="pdsection pline" >
                                  <td class="pdinfohead" >
                                  <?php echo JText::_('COM_COMPARISONCHART_CHART_RATING'); ?>
                                  </td>
                                  <?php
                                  /*
                                  foreach ($this->columns as $item) { ?>
                                  <td>
                                  <?php echo $this->rating[$item->id];
                                  ?>
                                  </td>
                                  <?php }
                                  ?>
                                  </tr>
                                  <?php

                                  } */
                                /* 	 
                                  ?>
                                  <?php if ($this->params->get('show_all_button', 1)) { ?>
                                  <tr class="pnone" >
                                  <td>
                                  <!--<img src="components/com_comparisonchart/assets/images/ico_bulb.gif" alt="" width="10" height="10" border="0">-->
                                  <a class="ch_show_items" href="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=items&id='.$this->chart->id); ?>" >
                                  <?php echo JText::_('COM_COMPARISONCHART_SHOW_ALL'); ?>
                                  </a>

                                  </td>
                                  </tr>
                                  <?php } */
                                ?>
                            </tbody>
                        </table>
                    </div>

                    </form>
                    <div id="rgMasterTable2Container"></div>
                </div>
                <?php } elseif ($this->columns) { ?>
                <div class="error" >
                <?php echo JText::_('COM_COMPARISONCHART_ERROR_NO_ROWS'); ?>
                </div>
                <?php } else { ?>
                <div class="error" >
                <?php echo JText::_('COM_COMPARISONCHART_ERROR_NO_COLUMNS'); ?>
                </div>
            <?php } ?>
            <br class="clr" />
                <?php if ($this->chart->description_after and $this->params->chart_description==1) { ?>
                <div class="description" >
                <?php echo $this->chart->description_after; ?>
                </div>
            <?php } ?>
            <?php } else { ?>
            <div class="error" >
            <?php echo JText::_('COM_COMPARISONCHART_ERROR_NO_CHART'); ?>
            </div>
    <?php } ?>
    </div>
