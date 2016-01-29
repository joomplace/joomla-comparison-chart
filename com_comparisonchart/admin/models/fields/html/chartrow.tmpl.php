<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;
JHtml::script(JURI::base() . 'components/com_comparisonchart/assets/jplace.jquery.js');

JHtml::script(JURI::base() . 'components/com_comparisonchart/assets/wysihtml/advanced.js');
JHtml::script(JURI::base() . 'components/com_comparisonchart/assets/wysihtml/wysihtml5-0.3.0_rc2.js');
?>
<!--<link rel="stylesheet" href="<?php echo(JUri::root()); ?>administrator/components/com_comparisonchart/assets/css/font-awesome.css" type="text/css" />-->
<style type="text/css" >

    a.modal-button {
        display: block;
        float: left;
        width: auto;
        margin: 5px 5px 5px 0px;
        padding: 3px;
        background: white;
        border: 1px solid #CCC;
        text-decoration: none;
    }

    #rowHolder {
        position: relative;
        border-spacing: 0px;
        width: 100%
    }

    #rowHolder thead th:first-child {
        border-left: 1px solid #F3F3F3;
    }

    #rowHolder thead th:last-child {
        border-right: 1px solid #F3F3F3;
    }

    #rowHolder tbody td {
        border: 1px solid #F3F3F3;
    }

    #rowHolder tbody tr td.order {
        width: auto;
        text-align: center;
    }

    #rowHolder span.state {
        cursor: pointer;
    }

    ul.adminformlist li{
        margin-bottom: 10px;
    }

    #sbox-window{
        height: 450px !important;
    }

    .texteditor-toolbar{
        margin-left: 215px;
    }

    textarea{
        width: 260px;
    }
    #name-error{
        margin-left: 220px;
    }


    div#chart-box{
        position: absolute;
        background-color: #000;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 150%;
        opacity: 0.7;
    }

    #popup-prop{
        z-index: 6;
        position: relative;
        background-color: white;
        width: 650px;
        height: 460px;
        top: 50%; /* Отступ в процентах от верхнего края окна */
        left: 50%; /* Отступ в процентах от левого края окна */
        margin-top: -230px; /* Отрицательный отступ от верхнего края страницы, должен равняться половине высоты блока со знаком минус */
        margin-left: -230px; /* Отрицательный отступ от левого края страницы, должен равняться половине высоты блока со знаком минус */
    }

    .wrap-area{
        margin-left: 210px;
    }

   /* #popup-close{
        position: absolute;
        width: 30px;
        height: 30px;
        right: -15px;
        top: -15px;
        background: url(/administrator/components/com_comparisonchart/assets/images/closebox.png) no-repeat center ;
        border: none;
        z-index: 7;
    }*/

    #elements-table{
        width: 100%
    }


   /* span.state.uparrow{
        background:url(/administrator/components/com_comparisonchart/assets/images/uparrow.png) no-repeat !important;
        height: 13px;
        width: 13px;
        display: inline-block;
    }
    span.state.downarrow{
        background:url(/administrator/components/com_comparisonchart/assets/images/downarrow.png) no-repeat !important;
        height: 13px;
        width: 13px;
        display: inline-block;
    }*/
    .jgrid span.text {
        display: none;
    }
</style>

<script type="text/javascript" >
    var $ = jQuery.noConflict();
    $(document).ready(function() {
        tinyMCE.init({
            mode: "textareas",
            theme: "advanced",
            editor_selector: "mceSimple"
        });
        tableColor();

        $("#check-all").click(function() {
            $(".item-check").attr('checked', this.checked);
        });
        $('#elements-table').on('click', '.prop-name', function(e) {
            var elname = $(e.target).text(),
                    propId = $(e.target).attr('id'),
                    eldescription = $(e.target.parentNode.parentNode).find('.prop-description').html(),
                    eltype = $(e.target.parentNode.parentNode).find('.prop-type').text(),
                    eldirection = $(e.target.parentNode.parentNode).find('.prop-direction').text();
            popupShow();
            $('#elname').val(elname);
            $('#itemid').val(propId);
            $("[name='eltype'] option[value='" + eltype + "']").attr("selected", "selected");
            $("[name='best'] option[value='" + eldirection + "']").attr("selected", "selected");
            jQuery('[name="description"]').attr("value", eldescription);
            $('#description_ifr').contents().find('#tinymce').html(eldescription);

        });

        $('#elements-table').on('click', 'span.state', function(e) {
            var text = $(this).find('span.text').text(),
                    el_id = $(this.parentNode.parentNode).find('.prop-name a').attr("id"), //item id
                    rownumber = parseInt($(this.parentNode.parentNode).attr("rownumber"), 10), //attr table tr (int)
                    rows_count = $('#elements-table tbody tr').length,
                    up, //previous row
                    down, //next row
                    el_move_id, //next or previous element id
                    chart_id = jQuery('[name="chartid"]').val(),
                    ids = [], //array all ids
                    ord = [], //array all ordering
                    k;

            for (k = 0; k < rows_count; k += 1) {
                ids.push($('.prop-name a').eq(k).attr('id'));
            }
            for (k = 0; k < rows_count; k += 1) {
                ord.push($('tr.chart-prop').eq(k).attr('rownumber'));
            }
            if (text == 'Move Up') {
                if (rownumber != 1) {
                    up = rownumber - 1;
                    el_move_id = $('[rownumber="' + up + '"]').find('.prop-name a').attr("id");
                    jQuery.ajax({
                        url: 'index.php?option=com_comparisonchart&task=row.moverows',
                        type: "POST",
                        data: {
                            el_id: el_id,
                            rownumber: rownumber,
                            el_move_id: el_move_id,
                            el_move_ordering: up,
                            chart_id: chart_id,
                            ids: ids,
                            ord: ord
                        },
                        success: function(json) {
                            var data = obj = JSON.parse(json),
                                    i = 0,
                                    table_count = 1,
                                    count = data.length;
                            $("#elements-table > tbody").html("");
                            for (; i < count; i++) {
                                table_count = $("#elements-table").length;
                                jQuery('#elements-table > tbody:last').append('<tr class="chart-prop" rownumber="' + (i + 1) + '"><td><input type="hidden" id="itemid" value="' + data[i].id + '"/><input type="checkbox" name="item-check" class="item-check" value="' + data[i].id + '"/></td><td class="prop-name" ><a href="javascript:void(0)" id="' + data[i].id + '">' + data[i].name + '</a></td><td class="prop-description">' + data[i].description + '</td><td class="prop-type">' + data[i].type + '</td><td class="prop-direction">' + data[i].direction + '</td><td class="order jgrid"><span class="state uparrow" ><span class="text">Move Up</span></span><span class="state downarrow" ><span class="text">Move Down</span></span> <span class="stateorder" >' + table_count + '</span><input name="jform[rows][' + (table_count - 1) + '][ordering]" type="hidden" value="' + table_count + '" /></td></tr>');
                                jQuery('[name="chartid"]').val(data[i].chart_id);
                                tableColor();
                            }
                        }
                    });
                }
            } else {
                if (rownumber != rows_count) {
                    down = rownumber + 1;
                    el_move_id = $('[rownumber="' + down + '"]').find('.prop-name a').attr("id");
                    jQuery.ajax({
                        url: 'index.php?option=com_comparisonchart&task=row.moverows',
                        type: "POST",
                        data: {
                            el_id: el_id,
                            rownumber: rownumber,
                            el_move_id: el_move_id,
                            el_move_ordering: down,
                            chart_id: chart_id,
                            ids: ids,
                            ord: ord
                        },
                        success: function(json) {
                            var data = obj = JSON.parse(json),
                                    i = 0,
                                    table_count = 1,
                                    count = data.length;
                            $("#elements-table > tbody").html("");
                            for (; i < count; i++) {
                                table_count = $("#elements-table").length;
                                jQuery('#elements-table > tbody:last').append('<tr class="chart-prop" rownumber="' + (i + 1) + '"><td><input type="hidden" id="itemid" value="' + data[i].id + '"/><input type="checkbox" name="item-check" class="item-check" value="' + data[i].id + '"/></td><td class="prop-name" ><a href="javascript:void(0)" id="' + data[i].id + '">' + data[i].name + '</a></td><td class="prop-description">' + data[i].description + '</td><td class="prop-type">' + data[i].type + '</td><td class="prop-direction">' + data[i].direction + '</td><td class="order jgrid"><span class="state uparrow" ><span class="text">Move Up</span></span><span class="state downarrow" ><span class="text">Move Down</span></span> <span class="stateorder" >' + table_count + '</span><input name="jform[rows][' + (table_count - 1) + '][ordering]" type="hidden" value="' + table_count + '" /></td></tr>');
                                jQuery('[name="chartid"]').val(data[i].chart_id);
                                tableColor();
                            }
                        }
                    });
                }
            }

        });


    });
    function test() {
        var elName = jQuery('[name="elname"]').val(),
                elType = jQuery('[name="eltype"]').find(":selected").val(),
                best = jQuery('[name="best"]').find(":selected").val(),
                description = $('#description_ifr').contents().find('#tinymce').html(), /*jQuery('#tinymce').contents().find('body').text(),jQuery('[name="description"]').val(),*/
                chartid = jQuery('[name="chartid"]').val(),
                itemid = jQuery('#itemid').val();
        console.log(itemid);
        if (jQuery('[name="elname"]').val() == '') {
            jQuery('#name-error').show();
            jQuery('#elname').css({'border-color': 'red'});
        }
        else {
            jQuery.ajax({
                url: 'index.php?option=com_comparisonchart&task=row.test',
                type: "POST",
                data: {
                    elName: elName,
                    elType: elType,
                    direction: best,
                    chart_id: chartid,
                    itemid: itemid,
                    description: description,
                },
                success: function(json) {
                    var data = obj = JSON.parse(json),
                            i = 0,
                            table_count = 1,
                            count = data.length;
                    $("#elements-table > tbody").html("");
                    if (count == 0) {
                        jQuery('#elements-table > tbody:last').append('<tr class="chart-empty"><td colspan="6" align="center"><?php echo JText::sprintf('COM_COMPARISONCHART_FIELD_NONE', 'rows'); ?></td></tr>');
                        $('#check-all').prop('checked', false);
                    } else {
                        for (; i < count; i++) {
                            table_count = $("#elements-table").length;
                            jQuery('#elements-table > tbody:last').append('<tr class="chart-prop" class="chart-prop" rownumber="' + table_count + '"><td><input type="hidden" id="itemid" value="' + data[i].id + '"/><input type="checkbox" name="item-check" class="item-check" value="' + data[i].id + '"/></td><td class="prop-name" ><a href="javascript:void(0)" id="' + data[i].id + '">' + data[i].name + '</a></td><td class="prop-description">' + data[i].description + '</td><td class="prop-type">' + data[i].type + '</td><td class="prop-direction">' + data[i].direction + '</td><td class="order jgrid"><span class="state uparrow" ><span class="text">Move Up</span></span><span class="state downarrow" ><span class="text">Move Down</span></span> <span class="stateorder" >' + table_count + '</span><input name="jform[rows][' + (table_count - 1) + '][ordering]" type="hidden" value="' + table_count + '" /></td></tr>');
                            jQuery('[name="chartid"]').val(data[i].chart_id);
                            $('#check-all').prop('checked', false);
                            popupHide();
                            tableColor();
                        }
                    }
                }
            });
        }
    }

    function popupShow() {
        jQuery('#itemid').val('0');
        jQuery('#elname').css({'border-color': '#D4D4D4'});
        jQuery('#chart-box').show();
        jQuery('#popup-prop').show();
    }

    function tableColor() {
        $("#elements-table tbody tr:even").css("background-color", "#F9F9F9");
        $("#elements-table tbody tr:odd").css("background-color", "#FFFFFF");
        $("#elements-table th").css("background-color", "#FFFFFF");
    }

    function popupHide() {
        jQuery('#itemid').val('0');
        jQuery('[name="elname"]').val('');
        $("[name='eltype'] option[value='int']").attr("selected", "selected");
        $("[name='best'] option[value='none']").attr("selected", "selected");
        jQuery('[name="description"]').val('');
        $('#description_ifr').contents().find('#tinymce').html('<p><br data-mce-bogus="1"></p>');
        $('#elname').css({'border-color': '#D4D4D4'});
        jQuery('#popup-prop').hide();
        jQuery('#chart-box').hide();
        jQuery('#name-error').hide();
    }

    function deleteItems() {
        var val = [],
                chartid = jQuery('[name="chartid"]').val();
        $(':checkbox:checked').each(function(i) {
            val[i] = $(this).val();
        });
        if (val.length == 0) {
            alert('Property do not selected');
        } else {
            jQuery.ajax({
                url: 'index.php?option=com_comparisonchart&task=row.deleteitems',
                type: "POST",
                data: {
                    val: val,
                    chart_id: chartid
                },
                success: function(json) {
                    var data = obj = JSON.parse(json),
                            i = 0,
                            table_count = 1,
                            count = data.length;
                    console.log(data);
                    $("#elements-table > tbody").html("");
                    if (count == 0) {
                        jQuery('#elements-table > tbody:last').append('<tr class="chart-empty"><td colspan="6" align="center"><?php echo JText::sprintf('COM_COMPARISONCHART_FIELD_NONE', 'rows'); ?></td></tr>');
                        $('#check-all').prop('checked', false);
                    } else {
                        for (; i < count; i++) {
                            table_count = $("#elements-table").length;
                            jQuery('#elements-table > tbody:last').append('<tr class="chart-prop" rownumber="' + table_count + '"><td><input type="hidden" id="itemid" value="' + data[i].id + '"/><input type="checkbox" name="item-check" class="item-check" value="' + data[i].id + '"/></td><td class="prop-name" ><a href="javascript:void(0)" id="' + data[i].id + '">' + data[i].name + '</a></td><td class="prop-description">' + data[i].description + '</td><td class="prop-type">' + data[i].type + '</td><td class="prop-direction">' + data[i].direction + '</td><td class="order jgrid"><span class="state uparrow" ><span class="text">Move Up</span></span><span class="state downarrow" ><span class="text">Move Down</span></span> <span class="stateorder" >' + table_count + '</span><input name="jform[rows][' + (table_count - 1) + '][ordering]" type="hidden" value="' + table_count + '" /></td></tr>');
                            jQuery('[name="chartid"]').val(data[i].chart_id);
                            popupHide();
                            tableColor();
                            $('#check-all').prop('checked', false);
                        }
                    }
                }
            });
        }
    }

</script>

<table id="elements-table">
    <thead>
        <tr>
            <th width="20px" >
                <input type="checkbox"  id="check-all" />
            </th>
            <th width="150px" >
                <?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_NAME'); ?>
            </th>
            <th>
                <?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_DESCRIPTION'); ?>
            </th>
            <th width="8%" >
                <?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_TYPE'); ?>
            </th>
            <th width="8%" >
                <?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_DIRECTION'); ?>
            </th>
            <th width="8%" >
                <?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_ORDER'); ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($this->value) {
            foreach ($this->value as $i => $row) {
                if (!$row->color) {
                    $row->color = '#FFFFFF';
                }
                ?>
                <tr class="chart-prop" rownumber="<?php echo $i + 1; ?>">
                    <td >
                        <input type="checkbox" name="item-check" class="item-check" value="<?php echo $row->id; ?>" />
                        <input type="hidden" id="itemid" value="<?php echo $row->id; ?>"/>
                    </td>
                    <td  class="prop-name" >
                        <a href="javascript:void(0)" id="<?php echo $row->id; ?>"><?php echo $row->name; ?></a>
                    </td>
                    <td  class="prop-description">
                        <?php echo $row->description; ?>
                        <!--<input name="<?php //echo $this->name . '[' . $i . '][description]';                          ?>" type="hidden" value='<?php //echo str_replace("'", '"', $row->description);                          ?>' />-->
                    </td>
                    <td  class="prop-type">
                        <?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_TYPE_' . $row->type); ?>

                    </td>
                    <td style=" width:70px;" class="prop-direction">
                        <?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_DIRECTION_' . $row->direction); ?>

                    </td>
                    <td class="order jgrid" >
                        <span class="state uparrow" ><span class="text">Move Up</span></span>
                        <span class="state downarrow" ><span class="text">Move Down</span></span>
                        <span class="stateorder" ><?php echo $i + 1; ?></span>
                        <input name="<?php echo $this->name . '[' . $i . '][ordering]'; ?>" type="hidden" value="<?php echo $i + 1; ?>" />
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr id="jp_none" >
                <td colspan="6" align="center" class="chart-empty">
                    <?php echo JText::sprintf('COM_COMPARISONCHART_FIELD_NONE', 'rows'); ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</tbody>
</table>

<div id="popup-prop" style="display: none">
    <div id="popup-close" onclick="popupHide();"></div>
    <div class="in">
        <legend>
            <?php echo JText::_('COM_COMPARISONCHART_FIELDSET_ROW'); ?>
        </legend>
        <ul class="adminformlist" >
            <li>
                <label>
                    <?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_NAME'); ?>
                </label>
                <input type="text" name="elname" id="elname"/>
                <input type="hidden" name="item_id" id="itemid" value="0"/>
                <div id="name-error" style="color:red; display: none">Name is empty!</div>
            </li>
            <li>
                <label>
                    <?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_TYPE'); ?>
                </label>
                <select name="eltype" id="eltype">
                    <option value="int" ><?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_TYPE_INT'); ?></option>
                    <option value="text" ><?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_TYPE_TEXT'); ?></option>
                    <option value="check" ><?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_TYPE_CHECK'); ?></option>
                    <option value="spacer" ><?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_TYPE_SPACER'); ?></option>
                    <option value="rating" ><?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_TYPE_RATING'); ?></option>
                </select>
                <img src="components/com_comparisonchart/assets/images/int.png" alt="" class="img-int preview-type" />
                <img src="components/com_comparisonchart/assets/images/text.png" alt="" class="img-text preview-type" />
                <img src="components/com_comparisonchart/assets/images/check.png" alt="" class="img-check preview-type" />
                <img src="components/com_comparisonchart/assets/images/spacer.png" alt="" class="img-spacer preview-type" />
                <img src="components/com_comparisonchart/assets/images/rating.png" alt="" class="img-rating preview-type" />
            </li>
            <li>
                <label>
                    <?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_DIRECTION'); ?>
                </label>
                <select name="best" id="best">
                    <option value="none" ><?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_DIRECTION_NONE'); ?></option>
                    <option value="asc" ><?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_DIRECTION_ASC'); ?></option>
                    <option value="desc" ><?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_DIRECTION_DESC'); ?></option>
                </select>
                <img src="components/com_comparisonchart/assets/images/none.png" alt="" class="img-none preview-dir" />
                <img src="components/com_comparisonchart/assets/images/asc.png" alt="" class="img-asc preview-dir" />
                <img src="components/com_comparisonchart/assets/images/desc.png" alt="" class="img-desc preview-dir" />
            </li>
            <li>
                <label>
                    <?php echo JText::_('COM_COMPARISONCHART_FIELD_ROW_DESCRIPTION'); ?>
                </label>
                <div class="wrap-area">
                    <textarea name="description" rows="5" class="mceSimple" cols="70" ></textarea>
                </div>
            </li>
            <li>
                <label>
                </label>
                <input type="hidden" name="row[ordering]" value="0" />
                <input type="hidden" name="chartid" value="<?php echo $chart_id; ?>" />
                <input type="button" value="<?php echo JText::_('COM_COMPARISONCHART_ROW_SAVE'); ?>" class="button" onclick="test();" />
                <img src="components/com_comparisonchart/assets/images/loader.gif" class="loader" />
            </li>
        </ul>
    </div>
</div>

<div class="button-mag">
    <input type="button" value="Add new prop" class="btn btn-small" onclick="popupShow();"/>
    <input type="button" value="Delete" class="btn btn-small" onclick="deleteItems();"/>
</div>

<div id="chart-box" style="display: none" onclick="popupHide();"></div>