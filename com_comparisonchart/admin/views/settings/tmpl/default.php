<?php
/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

function showJbField($form, $name = '') {
    echo '<td class="jbadmintitle">';
    echo $form->getLabel($name);
    echo '</td><td>';
    echo $form->getInput($name);
    echo '</td>';
}
?>
<style type="text/css">
    .hide {
        display: none;
    }
</style>
<?php echo $this->loadTemplate('menu'); ?>
<form action="<?php echo JRoute::_('index.php?option=com_comparisonchart&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
    <?php if (!empty($this->sidebar)) : ?>
        <div id="j-sidebar-container" class="span2">

            <?php echo $this->sidebar; ?>
        </div>
        <div style="margin-top: 10px;" id="j-main-container" class="span10">
        <?php else : ?>
            <div style="margin-top: 10px;" id="j-main-container">
            <?php endif; ?>
            <table class="admin jbsettings" width="100%">
                <tr>
                    <td valign="top" class="lefmenutd" >

                    </td>
                    <td valign="top" width="100%">
                        <?php echo JHtml::_('tabs.start', 'global-tabs', array('useCookie' => 1)); ?>
                        <?php echo JHtml::_('tabs.panel', JText::_('COM_COMPARISONCHART_SETTINGS_GLOBAL'), 'global-details'); ?>
                        <br/>
                        <div class=" fltlft">
                            <fieldset class="settingfieldset ">
                                <legend><?php echo JText::_('COM_COMPARISONCHART_TITLE_AND_DESCRIPTION'); ?></legend>
                                <table cellspacing="1" class="adminlist">
                                    <tr class="row0">
                                        <?php showJbField($this->form, 'show_title'); ?>
                                    </tr>
                                    <tr class="row1">
                                        <?php showJbField($this->form, 'title_text'); ?>
                                    </tr>
                                    <tr class="row0">
                                        <?php showJbField($this->form, 'chart_description'); ?>
                                    </tr>
                                    <tr class="row1">
                                        <?php showJbField($this->form, 'pagination'); ?>
                                    </tr>
                                </table>						
                            </fieldset>
                        </div>
                        <div class=" fltrt">
                            <fieldset class="settingfieldset">
                                <legend><?php echo JText::_('COM_COMPARISONCHART_CHART_VIEW'); ?></legend>
                                <table cellspacing="1" class="adminlist">
                                    <tr class="row0">
                                        <?php showJbField($this->form, 'row_tooltip'); ?>
                                    </tr>	
                                    <tr class="row1">
                                        <?php showJbField($this->form, 'show_toogle_button'); ?>
                                    </tr>
                                    <tr class="row0">
                                        <?php showJbField($this->form, 'show_hidden_param_button'); ?>
                                    </tr>
                                    <tr class="row1">
                                        <?php showJbField($this->form, 'show_hidden_items_button'); ?>
                                    </tr>
                                    <tr class="row0">
                                        <?php showJbField($this->form, 'show_xls_export_button'); ?>
                                    </tr>
                                    <tr class="row1">
                                        <?php showJbField($this->form, 'allow_to_hide_items'); ?>
                                    </tr>
                                    <tr class="row0">
                                        <?php showJbField($this->form, 'allow_to_hide_properties'); ?>
                                    </tr>
                                </table>						
                            </fieldset>
                        </div>	
                        <div class="fltlft">
                            <fieldset class="settingfieldset">
                                <legend><?php echo JText::_('COM_COMPARISONCHART_IMAGES'); ?></legend>
                                <table cellspacing="1" class="adminlist">
                                    <tr class="row1">
                                        <?php showJbField($this->form, 'item_image'); ?>
                                    </tr>
                                    <tr class="row0">
                                        <?php showJbField($this->form, 'item_image_width'); ?>
                                    </tr>
                                </table>						
                            </fieldset>
                        </div>							
                        <div class="clr"></div>
                        <?php echo JHtml::_('tabs.panel', JText::_('JCONFIG_PERMISSIONS_LABEL'), 'permission-details'); ?>
                        <br/>
                        <fieldset class="settingfieldset">
                            <legend><?php echo JText::_('JCONFIG_PERMISSIONS_LABEL'); ?></legend>
                            <?php echo $this->form->getLabel('rules'); ?>
                            <?php echo $this->form->getInput('rules'); ?>
                        </fieldset>
                        <div class="clr"></div>
                        <input type="hidden" name="task" value="" />
                        <input type="hidden" name="id" value="<?php echo (isset($this->item->id) ? $this->item->id : 0) ?>" />				
                        <?php echo JHtml::_('form.token'); ?>
                        </div>
                        <div class="clr"></div>			
                        <?php echo JHtml::_('tabs.end'); ?>
                    </td>
                </tr>
            </table>
            </form>
