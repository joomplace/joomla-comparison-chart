<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;


JHtml::_('behavior.keepalive');

JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
JHtml::_('behavior.formvalidation');
$chart_list = $this->item->chart_id;
$cat_list = $this->item->catid;
?>
<style type="text/css" >
    #rowHolder th{
        text-align: center;
    }

    #rowHolder{
        /*margin-left: -80px;*/
    }

</style>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'item.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
            Joomla.submitform(task, document.getElementById('item-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }

    var $ = jQuery.noConflict();
    $(document).ready(function() {
        $("#rowHolder tbody tr:even").css("background-color", "#F9F9F9");
        $("#rowHolder tbody tr:odd").css("background-color", "#FFFFFF");
    });
</script>
<?php echo $this->loadTemplate('menu'); ?>

<form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate">
    <div class="row-fluid">
        <!-- Begin Content -->
        <div class="span10 form-horizontal">
            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_COMPARISONCHART_DETAILS', true)); ?>
            <?php
            foreach ($this->form->getFieldsets() as $fieldset) {
                $fields = $fields = $this->form->getFieldset($fieldset->name);
                if (count($fields) > 0) {
                    ?>
                    <fieldset class="adminform" >
                        <ul class="adminformlist">
                            <?php
                            foreach ($this->form->getFieldset($fieldset->name) as $field) {
                                ?>
                                <li><?php
                                    if ($field->id != 'jform_content') {
                                        echo $field->label;
                                        echo $field->input;
                                    }
                                    ?>
                                </li>
                            <?php } ?>
                        </ul>
                        <br class="clr" />
                    </fieldset>
                    <?php
                }
            }
            ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_COMPARISONCHART_PROPERTIES', true)); ?>
            <?php
            foreach ($this->form->getFieldsets() as $fieldset) {
                $fields = $fields = $this->form->getFieldset($fieldset->name);
                if (count($fields) > 0) {
                    ?>
                    <fieldset class="adminform" >
                        <ul class="adminformlist">
                            <?php
                            foreach ($this->form->getFieldset($fieldset->name) as $field) {
                                
                                ?>
                                <li><?php
                                    if ($field->id == 'jform_content') {
                                       
                                        echo $field->label;
                                        echo $field->input;
                                    }
                                    ?>
                                </li>
                            <?php } ?>
                        </ul>
                        <br class="clr" />
                    </fieldset>
                    <?php
                }
            }
            ?>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <input type="hidden" name="task" value="chart.edit" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
        <!-- End Content -->
        <!-- Begin Sidebar -->
        <?php //echo JLayoutHelper::render('joomla.edit.details', $this);   ?>
        <!-- End Sidebar -->
    </div>
</form>
