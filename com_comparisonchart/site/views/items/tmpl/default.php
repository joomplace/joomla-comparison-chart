<?php

/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');
$app = JFactory::getApplication();
if ($this->chart->css != 'default.css') {
    $this->document->addStyleSheet('components/com_comparisonchart/assets/css/' . $this->chart->css . '.css');
}

?>

<script type="text/javascript">
    function formValidate() {
        if (jQuery("input:checked").length > 0) {
            //for(var i=0; i<jQuery("input:checked").length; i++ )

            return true;
           // return false;
        }
        else {
            alert("<?php echo JText::_('COM_COMPARISONCHART_CHECKED_ITEM'); ?>");
            return false;
        }
    }


</script>


<div class="comparisonchart style-<?php echo $this->chart->css; ?>">
    <?php

    if(isset($this->params->show_title)) { ?>
        if((int)$this->params->show_title){
        <h3 class="title">
            <?php echo $this->chart->title; ?>
        </h3>}
    <?php } ?>
    <?php if ($this->chart->description_before and $this->params->get('chart_description', 1)) { ?>
        <div class="description">
            <?php echo $this->chart->description_before; ?>
        </div>
    <?php } ?>
    <form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=charts&id=' . $this->chart->id); ?>"
          method="post" name="charts-form" id="charts-form" onsubmit="return formValidate();">
        <input type="hidden" value="<?php echo $this->chart->id; ?>" name="chart-id"/>
        <input type="submit" class="button" value="<?php echo JText::_('COM_COMPARISONCHART_BUTTON_SUBMIT'); ?>"/>
        <input type="button" class="button ch_reset"
               value="<?php echo JText::_('COM_COMPARISONCHART_BUTTON_RESET'); ?>"/>
        <input type="hidden" value="<?php echo $this->Itemid; ?>" name="Itemid"/>

        </form>

        <?php

        if ($this->items) {
            echo $this->loadTemplate('items');
        } else {
            ?>
            <div class="error">
                <?php echo JText::_('COM_COMPARISONCHART_ERROR_NO_ITEMS'); ?>
            </div>
        <?php
        }
        ?>
        <input type="hidden" value="<?php echo $this->Itemid; ?>" name="Itemid"/>


    <?php if ($this->chart->description_after and $this->params->get('chart_description', 1)) { ?>
        <div class="description">
            <?php echo $this->chart->description_after; ?>
        </div>
    <?php } ?>

</div>

<div class="compare-block"  id="chart-notice">
    <?php

    // $count_compare=count($app->getUserState('com_comparisonchart.chart'.intval($this->chart->id)));
    if(!count($this->compare)){
        $session =JFactory::getSession();
        $this->compare=$session->get('com_comparisonchart.chart'.$this->chart->id);
    }
    $count_compare = count($this->compare);
    if ($count_compare > 0) {
        echo $count_compare . ' item(-s) <a href="#" onclick="jQuery(\'#charts-form\').submit();">added to compare.</a>';
        $ids = array();
        $ids = array_keys($this->compare);?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                <?php    for($i=0; $i<count($ids); $i++){
                        ?>


                jQuery('#item<?php echo $ids[$i];?>').prop('checked', true);
                jQuery("label[for='item<?php echo $ids[$i];?>']").addClass('c_on');

                <?
                    }?>
            });
        </script>
    <?php
    } else {
        echo 'No items selected.';
    }?>

</div>
