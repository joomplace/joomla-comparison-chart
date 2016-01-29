<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');
if ($this->category->parent_id > 1) {
    $link = JRoute::_('index.php?option=com_comparisonchart&view=category&catid=' . $this->category->parent_id . '&id=' . $this->chartid);
} else {
    $link = JRoute::_('index.php?option=com_comparisonchart&view=main');
}

if ($this->chart->flag != false) {
    if ($this->chart->css != 'default.css') {
        $this->document->addStyleSheet('components/com_comparisonchart/assets/css/' . $this->chart->css . '.css');
    }
    ?>

    <div class="comparisonchart style-<?php echo $this->chart->css; ?>" >
        <h1> <?php echo $this->category->title ?></h1>

        <?php if (($this->category->params->image) && ($this->params->get('category_image'))) { ?>
            <div class="image" >
                <img src="<?php echo $this->category->params->image ?>" alt="" width="100"/>
            </div>
        <?php
        }

        if ($this->params->get('category_description') && ($this->category->description)) {
            ?>
            <div class="description" >
            <?php echo $this->category->description; ?>
            </div>
    <?php } ?>
        <div id="chart-notice" style="display: none"></div>

    <?php if ($this->children) { ?>
            <div class="categories" >
                <div class="subcategories_wrap">Subcategories:</div>
                <?php
                $i = 0;
                $n = count($this->children);
                foreach ($this->children as $category) {
                    $category->params = json_decode($category->params);
                    ?>
                    <div class="grid_2" style="width:22%" >
                        <div class="subcategory_title">
                            <a href="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=category&id=' . $this->getchartid($category->id) . '&catid=' . $category->slug); ?>" ><?php echo $category->title; ?></a>
                        </div>
                    </div>
        <?php } ?>
            </div>
            <div class="clear">&nbsp;</div>

        <?php } ?>
            <?php if ($this->chart->description_before and $this->params->get('chart_description', 1)) { ?>
            <div class="description" >
            <?php echo $this->chart->description_before; ?>
            </div>
    <?php } ?>
        <form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=charts&id=' . $this->chartid . '&catid=' . $this->category->id); ?>" method="post" name="charts-form" id="charts-form">
            <input type="hidden" value="<?php echo $this->chartid; ?>" name="chart-id" />
    <?php if ($this->items) { ?>
                <input type="submit" class="button" value="<?php echo JText::_('COM_COMPARISONCHART_BUTTON_SUBMIT'); ?>" />
                <input type="button"  class="button ch_reset" value="<?php echo JText::_('COM_COMPARISONCHART_BUTTON_RESET'); ?>" />
                <?php
            }
            ?>
            <input type="hidden" value="<?php echo $this->Itemid; ?>" name="Itemid" />
        <!--</form>
        <br />
        <form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=items&id=' . $this->chartid); ?>"method="post" name="items-form" >-->
            <?php
            if ($this->items) {
                echo $this->loadTemplate('items');
            } else {
                ?>
                <div class="error" >
                <?php echo JText::_('COM_COMPARISONCHART_ERROR_NO_ITEMS'); ?>
                </div>
                <?php
            }
            ?>
            <input type="hidden" value="<?php echo $this->Itemid; ?>" name="Itemid" />
       <!-- </form>
        <form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=charts&id=' . $this->chartid . '&catid=' . $this->category->id); ?>" method="post" name="charts-form" >-->
            <input type="hidden" value="<?php echo $this->chartid; ?>" name="chart-id" />
    <?php if ($this->items) { ?>
                <input type="submit" class="button" value="<?php echo JText::_('COM_COMPARISONCHART_BUTTON_SUBMIT'); ?>" />
                <input type="button" class="button ch_reset" value="<?php echo JText::_('COM_COMPARISONCHART_BUTTON_RESET'); ?>" />
                <?php
            }
            ?>
            <input type="hidden" value="<?php echo $this->Itemid; ?>" name="Itemid" />
        </form>
            <?php if ($this->chart->description_after and $this->params->get('chart_description', 1)) { ?>
            <div class="description" >
            <?php echo $this->chart->description_after; ?>
            </div>
                <?php } ?>
        <div class="category-link-back"><a href="<?php echo $link; ?>" >
    <?php echo JText::_('COM_COMPARISONCHART_LINK_BACK'); ?>
            </a></div>
    </div>
<?php } else { // if this only category page, not chart page   ?>
    <div class="comparisonchart" >
        <h1> <?php echo $this->category->title ?></h1>
        <div class="description" >
    <?php echo $this->category->description; ?>
        </div>
        <div id="chart-notice" style="display: none"></div>
    <?php if ($this->children) { ?>
            <div class="categories" >
                <div class="subcategories_wrap">Subcategories:</div>
                <?php
                $i = 0;
                $n = count($this->children);
                foreach ($this->children as $category) {
                    $category->params = json_decode($category->params);
                    ?>
                    <div class="grid_2" >
                        <div class="subcategory_title">
                            <a class="mainlevel"  href="<?php echo JRoute::_('index.php?option=com_comparisonchart&view=category&id=' . $this->getchartid($category->id) . '&catid=' . $category->slug); ?>" ><?php echo $category->title; ?></a>
                        </div>
                        <!--                 --><?php //if ($category->params->image) {   ?>
                        <!--                  <a href="--><?php //echo JRoute::_('index.php?option=com_comparisonchart&view=category&id='.$this->chart->id.'&catid='.$category->slug);   ?><!--" ><img src="--><?php //echo $category->params->image   ?><!--" width="100" />-->
                        <!--                     </a>-->
                        <!---->
                        <!--                 --><?php
                        //}
                        if ($this->getChildrenChildren($category->id)) {
                            echo '<ul class="subs">';
                            foreach ($this->getChildrenChildren($category->id) as $sub) {
                                echo '<li><a href="' . JRoute::_('index.php?option=com_comparisonchart&view=category&id=' . $this->getChildrenChildrenChartID($sub->id) . '&catid=' . $sub->slug) . '">' . $sub->title . '</a></li>';
                            }
                            echo '</ul>';
                        }
                        ?>
                    </div>
        <?php } ?>
            </div>
            <div class="category-link-back"><a href="<?php echo $link; ?>" >
        <?php echo JText::_('COM_COMPARISONCHART_LINK_BACK'); ?>
                </a></div>
        </div>

        <div class="clear">&nbsp;</div>

    <?php } ?>


<?php } ?>
