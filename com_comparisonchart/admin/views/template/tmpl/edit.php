<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');

$app = JFactory::getApplication();
$input = $app->input;
?>

<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'template.cancel' || document.formvalidator.isValid(document.id('adminform'))) {
            Joomla.submitform(task, document.getElementById('adminform'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
        }
    }

    $(function() {
        $(".tabs").tabs();
    });
    jQuery(document).ready(function() {
        jQuery("div#jform_text_align1_chzn").hide();
    });
</script>
<?php echo $this->loadTemplate('menu'); ?>
<form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminform" class="form-validate">

    <?php echo JLayoutHelper::render('joomla.edit.item_title', $this); ?>

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
                                if ($field->fieldname == 'check_true' || $field->fieldname == 'check_false' || $field->fieldname == 'close_image') {
                                    echo $field->label;
                                    echo $field->input . '<img class="img-view" src="' . JURI::root() . $field->value . '"/>';
                                } else if ($field->name == 'jform[temp_name]') {
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


<?php echo JHtml::_('bootstrap.endTab');/* ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_COMPARISONCHART_CUSTOM_TEMPLETE', true)); ?>

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
                                if ($field->type == 'color') {
                                    echo '<label>' . $field->title . '</label>';
                                    echo '<input class="inputbox" type="text" id="' . $field->id . '" name="jform[' . $field->fieldname . ']" size="10" maxlength="7"
                                    value="' . $field->value . '" /> <a href="#" onclick="showColorPicker(this,document.getElementById(\'' . $field->id . '\'))">
                                        <img src="' . JURI::root() . 'administrator/components/com_comparisonchart/assets/images/color_picker.gif" border="0" width="16" height="16" alt="Color Picker" /></a>
                                        <div class="clr"></div>';
                                } else if ($field->fieldname == 'check_true' || $field->fieldname == 'check_false' || $field->fieldname == 'close_image') {
                                    
                                } else if ($field->name != 'jform[temp_name]') {
                                    echo $field->label;
                                    echo $field->input;
                                }

                                if ($field->fieldname == 'text_align') {
                                    $isCenter = $isLeft = $isRight = '';
                                    if ($field->value == "right") {
                                        $isRight = ' selected';
                                    } elseif ($field->value == "left") {
                                        $isLeft = ' selected';
                                    } else {
                                        $isCenter = ' selected';
                                    }

                                    echo '<select name="jform[' . $field->fieldname . ']" id="' . $field->id . '" value="' . $field->value . '">
                              <option value="center" ' . $isCenter . '>Center</option>
                              <option value="left" ' . $isLeft . '>Left</option>
                              <option value="right" ' . $isRight . '>Right</option>
                              </select>';
                                    echo '<script>
                              document.getElementById("jform_text_align").setAttribute("id", "jform_text_align1");
                              document.getElementById("jform_text_align1").setAttribute("style", "display:none");
                              </script>';
                                }
                                ?>
                                </li>
                            <?php } ?>
                        </ul>

                    </fieldset>
                    <?php
                }
            }
            ?>



            <?php echo JHtml::_('bootstrap.endTab'); */?>
            <?php //endif;    ?>

            <input type="hidden" name="task" value="chart.edit" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
        <!-- End Content -->
        <!-- Begin Sidebar -->
        <?php //echo JLayoutHelper::render('joomla.edit.details', $this);   ?>
        <!-- End Sidebar -->
    </div>
</form>





