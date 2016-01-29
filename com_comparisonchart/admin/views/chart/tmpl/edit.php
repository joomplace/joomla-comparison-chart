<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.keepalive');
JHTML::_('behavior.modal');
JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'chart.cancel' || document.formvalidator.isValid(document.id('chart-form'))) {
            Joomla.submitform(task, document.getElementById('chart-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }






    function saveListRow() {


        var rowid = $$('#sbox-content input[name="row[id]"]')[0].value;


        var req = new Request({
            method: 'post',
            url: 'index.php?option=com_comparisonchart&task=row.save&tmpl=component&id=' + rowid
        }).send();
    }


</script>
<?php echo $this->loadTemplate('menu'); ?>
<form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="chart-form" class="form-validate">
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
                                    if ($field->id != 'jform_rows' && $field->id != 'jform_preview' && $field->name != 'jform[css]') {
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
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_COMPARISONCHART_TEMPLETE', true)); ?>
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
                                    if ($field->id == 'jform_preview' || $field->name == 'jform[css]') {
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
        <?php //echo JLayoutHelper::render('joomla.edit.details', $this);  ?>
        <!-- End Sidebar -->
    </div>
</form>

