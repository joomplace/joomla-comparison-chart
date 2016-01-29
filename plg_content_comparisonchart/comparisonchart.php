<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgContentComparisonchart extends JPlugin {

    public function __construct(& $subject, $config) {
        parent::__construct($subject, $config);
        $this->loadLanguage();
        $this->document = JFactory::getDocument();
        //$this->document->addStyleSheet('/components/com_comparisonchart/assets/css/global.css');


    }

    public function onContentPrepare($context, &$article, &$params, $limitstart) {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();

        $artext = $returntext = $tag = $replace = '';
        $count = 0;

        $regex = '/{comparisonchart (\d+)}/i';

        if (!isset($article->text))
            return true;

        $artext = $article->text;

        if (strpos($artext, 'comparisonchart') === false) {
            return true;
        }

        if ($artext) {

            preg_match_all($regex, $artext, $matches);

            if (count($matches[1])) {
                $this->document = JFactory::getDocument();
                $lang = JFactory::getLanguage();
                $lang->load('com_comparisonchart', JPATH_SITE);

                $document = JFactory::getDocument();
                $document->addScriptDeclaration("try {SqueezeBox.initialize({});
                    SqueezeBox.assign($$('a.modal'), {parse: 'rel'});} catch(e){} ");

                $this->document->addScript(JURI::base().'components/com_comparisonchart/assets/js/jquery.js');
                $this->document->addScript(JURI::base().'components/com_comparisonchart/assets/js/charts.js');
                $this->document->addStyleSheet(JURI::base().'components/com_comparisonchart/assets/css/global.css');
                $this->document->addStyleSheet(JURI::base().'administrator/components/com_comparisonchart/assets/css/comparisonchart.css');
                //$this->document->addScript('components/com_comparisonchart/assets/js/scrollto.js');

                $query = "
                    SELECT `c_par_value`
                    FROM #__cmp_chart_settings
                    WHERE `c_par_name`='settings'
                ";
                $db->setQuery($query);
                $this->params = $db->loadObject();
                $this->params = json_decode($this->params->c_par_value);
                $this->params = JComponentHelper::getParams('com_comparisonchart',$this->params);


                for ($i = 0; $i < count($matches[1]); $i++) {
                    $replace = '';
                    if (isset($matches[1][$i])) {
                        JHTML::_('behavior.modal');
                        JHTML::_('behavior.tooltip');
                        // $chid = intval($matches[1][$i]);
                        $chid = intval($matches[1][$i]);

                        $this->chart = $this->getChartItems($chid);
                        $this->style = $this->getChartStyle($this->chart);
                        $this->columns = $this->getChartColumns($chid);
                        $this->rows = $this->getChartRows($chid);
                        $this->content = $this->getChartContent($chid);

                        $replace = $this->showHtml();
                    }

                    $article->text = str_replace($matches[0][$i], $replace, $article->text);
                }
            }
        }
        return;
    }

    public function getChartItems($id = 0) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('cl.*, "" as items');
        $query->from('#__cmp_chart_list AS cl');
        if ($id) {
            $query->where('cl.id=' . $id);
        }
        $query->where('cl.published=1');
        $db->setQuery($query);
        $this->items = $db->loadObject();
        return $this->items;
    }

    public function getChartStyle($chart = null) {
        $style = '';
        $db = JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__cmp_chart_templates WHERE id=" . intval($chart->css));
        $templates_settings = $db->loadObjectList();
        $settings = $templates_settings[0];
        $this->template = $settings;
        ob_start();
        include(JPATH_SITE . DS . 'administrator' . DS . 'components' . DS . 'com_comparisonchart' . DS . 'helpers' . DS . 'style.php');
        $style = @ob_get_contents();
        @ob_end_clean();
        return $style;
    }

    public function getChartColumns($id = 0) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('SQL_CALC_FOUND_ROWS ci.*');
        $query->from('#__cmp_chart_items AS ci');

        //$query->select('ct.lastip AS rating_lastip, ct.sum AS rating_sum, ct.count as rating_count');
        //$query->join('LEFT', '#__cmp_chart_rating AS ct ON ct.item_id=ci.id');
        if ($id) {
            $query->where('ci.chart_id=' . $id);
        }

        $query->where('ci.published=1');
        $query->order('`ci`.`ordering` ASC');
        $db->setQuery($query);
        $this->columns = $db->loadObjectList();
        return $this->columns;
    }

    public function getChartRows($id = 0) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('cr.*');
        $query->from('#__cmp_chart_rows AS cr');

        //$best_query = '(SELECT CASE WHEN cr.direction = "asc" THEN MAX(cc.value_data) WHEN cr.direction = "desc" THEN MIN(cc.value_data) END AS best ';
        $best_query = '(SELECT ' .
                'CASE WHEN cr.direction = "asc" AND (cr.type="int" OR cr.type="rating") THEN MAX(CONVERT(cc.value_data,SIGNED)) ' .
                'WHEN cr.direction = "desc" AND (cr.type="int" OR cr.type="rating") THEN MIN(CONVERT(cc.value_data,SIGNED)) ' .
                'WHEN cr.direction = "desc" AND cr.type<>"int" AND cr.type<>"rating" THEN MIN(cc.value_data) ' .
                'WHEN cr.direction = "asc" AND cr.type<>"int" AND cr.type<>"rating" THEN MAX(cc.value_data) ' .
                'END AS best ';
        $best_query .= 'FROM #__cmp_chart_content AS cc ';
        $best_query .= 'WHERE cc.row_id=cr.id';

        $best_query .= ') AS best';
        $query->select($best_query);

        $count_query = '(SELECT COUNT(item_id) FROM #__cmp_chart_content AS cc ';
        $count_query .= 'WHERE cc.value_data=best AND cc.row_id=cr.id';

        $count_query .= ') AS best_count';
        $query->select($count_query);

        if ($id) {
            $query->where('cr.chart_id=' . $id);
        }

        $query->order('cr.ordering');

        $db->setQuery($query);

        $this->rows = $db->loadObjectList();
        return $this->rows;
    }

    public function getChartContent($id = 0) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $content = array();

        $query->select('cr.chart_id, cr.id AS row_id');
        $query->from('#__cmp_chart_rows AS cr');

        $query->select('cc.value_data, cc.value_description, cc.item_id');
        $query->join('LEFT', '#__cmp_chart_content AS cc ON cc.row_id=cr.id');

        if ($id) {
            $query->where('cr.chart_id=' . $id);
        }
        $db->setQuery($query);

        $rows = $db->loadObjectList();

        foreach ($rows as $item) {
            $obj = new JObject();
            $obj->value = $item->value_data;
            $obj->desc = $item->value_description;

            $content[$item->row_id][$item->item_id] = $obj;
        }
        return $content;
    }

    function getRatingStars($id, $rid, $rating_sum = 0, $rating_count = 0) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select('ct.sum, ct.count ');
        $query->from('#__cmp_chart_rating AS ct');
        $query->where('item_id=' . (int) $id);
        $query->where('row_id=' . (int) $rid);
        $db->setQuery($query);
        $rate = $db->loadObject();

        $rating_sum = $rate->sum ? $rate->sum : 0;
        $rating_count = $rate->count ? $rate->count : 0;

        $document = JFactory::getDocument();
        $document->addStyleSheet(JURI::root() . 'components/com_comparisonchart/assets/css/vote.css');
        $document->addScript(JURI::root() . 'components/com_comparisonchart/assets/js/vote.js');

        $document->addScriptDeclaration("var sfolder = '" . JURI::base(true) . "';
					var jrate_text=Array('" . JTEXT::_('COM_COMAPRISONCHART_RATING_NO_AJAX') . "','" . JTEXT::_('COM_COMAPRISONCHART_RATING_LOADING') . "','" . JTEXT::_('COM_COMAPRISONCHART_RATING_THANKS') . "','" . JTEXT::_('COM_COMAPRISONCHART_RATING_LOGIN') . "','" . JTEXT::_('COM_COMAPRISONCHART_RATING_RATED') . "','" . JTEXT::_('COM_COMAPRISONCHART_RATING_VOTES') . "','" . JTEXT::_('COM_COMAPRISONCHART_RATING_VOTE') . "');");

        $live_path = JURI::base();

        $counter = 1;
        $percent = 0;

        if ($rating_count != 0) {
            $percent = number_format((intval($rating_sum) / intval($rating_count)) * 20, 2);
        }

        $html = "<span class=\"jrate-container\" style=\"margin-top:5px;\">
					  <ul class=\"jrate-stars\">
						<li class=\"current-rating rating-" . $id . '-' . $rid . "\" style=\"width:" . (int) $percent . "%;\"></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(" . $id . "," . $rid . ",1," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_COMAPRISONCHART_RATING_VERY_POOR') . "\" class=\"jp-one-star\">1</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(" . $id . "," . $rid . ",2," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_COMAPRISONCHART_RATING_POOR') . "\" class=\"jp-two-stars\">2</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(" . $id . "," . $rid . ",3," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_COMAPRISONCHART_RATING_REGULAR') . "\" class=\"jp-three-stars\">3</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(" . $id . "," . $rid . ",4," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_COMAPRISONCHART_RATING_GOOD') . "\" class=\"jp-four-stars\">4</a></li>
						<li><a href=\"javascript:void(null)\" onclick=\"javascript:JoomRate(" . $id . "," . $rid . ",5," . $rating_sum . "," . $rating_count . "," . $counter . ");\" title=\"" . JTEXT::_('COM_COMAPRISONCHART_RATING_VERY_GOOD') . "\" class=\"jp-five-stars\">5</a></li>
					  </ul>
				</span>
					  <span class=\"jrate-count jrate-" . $id . '-' . $rid . "\"><small>";

        $html .= "( ";
        if ($rating_count != 1) {
            $html .= $rating_count . " " . JTEXT::_('COM_COMAPRISONCHART_RATING_VOTES');
        } else {
            $html .= $rating_count . " " . JTEXT::_('COM_COMAPRISONCHART_RATING_VOTE');
        }
        $html .=" )";
        $html .="</small></span>";

        return $html;
    }

    public function showHtml() {
        $cont = '';
        ob_start();

        $rows_count = count($this->rows);
        $items_count = count($this->columns);
        $col_width = floor(100 / ($items_count + 1));
        ?>
        </style>
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
             console.log(123);
             });
            window.onload = function() {
                if (jQuery('#rgMasterTable2Container').height() != 0) {
                    jQuery('#rgMasterTableContainer').css({"height": (jQuery('#rgMasterTable2Container').height()) + "px"});
                }
            }
        </script>
        <div class="comparisonchart style-<?php echo $this->chart->css; ?>" >
            <?php
            if ($this->chart) {
                if ($this->style) {
                    $this->document->addStyleDeclaration($this->style);
                }
                ?>
                <?php if ($this->params->get("show_title")) { ?>
                    <h1 class="title" >
                        <?php echo $this->chart->title; ?>
                    </h1>
                <?php } ?>
                <?php if ($this->chart->description_before and $this->params->get("chart_description")) { ?>
                    <div class="description" >
                        <?php echo $this->chart->description_before; ?>
                    </div>
                <?php } ?>
                <?php if ($this->columns and $this->rows) { ?>
             <div class="wr1">
                    <div class="cl">
                        &nbsp;
                    </div>
                </div>
                    <div class="rel" >
                        <div id="rgMasterTableContainer" class="comparisonchart style-<?php echo $this->chart->css; ?>">
                            <table cellspacing="0" cellpadding="0" border="0" class="pdtable" id="rgMasterTable" >
                                <tbody>
                                    <tr class="pdtitle" >
                                        <td valign="top" style="min-width:230px;" >
                                            <?php
                                            if ($this->params->get("show_toogle_button")) {
                                                ?>
                                                <a href="#" class="ch_toggle_equal" >
                                                    <?php echo JText::_('COM_COMPARISONCHART_TOGGLE_EQUAL'); ?>
                                                </a>
                                                <?php
                                            }

                                            if ($this->params->get("show_hidden_param_button")) {
                                                ?>
                                                <a href="#" class="ch_show_params" >
                                                    <?php echo JText::_('COM_COMPARISONCHART_SHOW_PARAMS'); ?>
                                                </a>
                                                <?php
                                            }

                                            if ($this->params->get("show_hidden_items_button")) {
                                                ?>
                                                <a href="#" class="ch_show_items" >
                                                    <?php echo JText::_('COM_COMPARISONCHART_SHOW_ITEMS'); ?>
                                                </a>
                                            <?php }
                                            ?>
                                        </td>
                                        <?php foreach ($this->columns as $item) { ?>
                                            <td valign="top" >
                                                <?php if ($this->params->get("allow_to_hide_items")) { ?>
                                                    <a class="ch_hide_item" alt="" style="cursor:pointer;"/>
                                                    <?php
                                                    if ($this->template) {
                                                        if ($this->template->close_image)
                                                            echo '<img src="' . JURI::root() . $this->template->close_image . '" >';
                                                    }
                                                    ?>
                                                    </a>
                                                <?php } ?>
                                                <div class="name_item">
                                                    <?php if ($item->description) { ?>
                                                        <a class="modal" rel="{handler: 'iframe'}" href="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=item&tmpl=component&id=' . $item->id); ?>" >
                                                            <?php echo $item->title; ?>
                                                        </a>
                                                        <?php
                                                    } else {
                                                        echo $item->title;
                                                    }
                                                    ?>
                                                </div>
                                                <?php if ($item->image and $this->params->get("item_image")) { ?>

                                                    <div class="img_item">
                                                        <img src="<?php echo $item->image; ?>" alt="<?php echo $item->title; ?>" width="<?php echo $this->params->get("item_image_width"); ?>" />
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
                                                    <span class="no-scroll"><?php echo $row->name; ?></span>
                                                </td>
                                            </tr>
                                            <?php
                                        } else {
                                            $i++;
                                            ?>
                                            <tr class="pline row<?php echo $row->id; ?> <? echo ($i % 2) ? 'odd' : 'even'; ?>" border="2" >
                                                <td class="pdinfohead" <?php echo $style; ?>>
                                                    <?php if ($this->params->get("allow_to_hide_properties")) { ?>
                                                        <a class="ch_hide_property" alt="" style="cursor:pointer;"/>
                                                        <?php
                                                        if ($this->template) {
                                                            if ($this->template->close_image)
                                                                echo '<img src="' . JURI::root() . $this->template->close_image . '" >';
                                                        }
                                                        ?>
                                                        </a>
                                                        <?php
                                                    }

                                                    if ($row->description) {
                                                        if ($this->params->get("row_tooltip")) {
                                                            echo JHTML::tooltip($row->description, $row->name, '', $row->name);
                                                        } else {
                                                            ?>
                                                            <a rel="lightbox" href="<?php echo JRoute::_('index.php?option=com_comparisonchart&task=item.getRow&id=' . $row->id); ?>" >
                                                                <?php echo $row->name; ?>
                                                            </a>
                                                            <?php
                                                        }
                                                    } else {
                                                        echo $row->name;
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
                                                                    if ($this->template->check_true)
                                                                        echo '<img src="' . JURI::root() . $this->template->check_true . '" >';
                                                                    else
                                                                        echo '<img src="components/com_comparisonchart/assets/images/ico_yes.png" >';
                                                                }
                                                                else
                                                                    echo '<img src="components/com_comparisonchart/assets/images/ico_yes.png" >';
                                                            } else {
                                                                if ($this->template->check_false)
                                                                    echo '<img src="' . JURI::root() . $this->template->check_false . '" >';
                                                                else
                                                                    echo '<img src="components/com_comparisonchart/assets/images/ico_no.png" >';
                                                            }
                                                            break;
                                                        case 'rating':
                                                            echo $this->getRatingStars($item->id, $row->id);
                                                            break;
                                                        default:
                                                            if ($item_value) {
                                                                echo $item_value->get('value', 0);
                                                            }
                                                            break;
                                                    }
                                                    if ($item_value->get('desc', false)) {
                                                        echo '&nbsp;' . $item_value->get('desc');
                                                    }

                                                    echo '</td>';
                                                }
                                                ?>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
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
                <?php if ($this->chart->description_after and $this->params->get("chart_description")) { ?>
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
        <?php
        $cont = @ob_get_contents();
        @ob_end_clean();
        return $cont;
    }

}
?>