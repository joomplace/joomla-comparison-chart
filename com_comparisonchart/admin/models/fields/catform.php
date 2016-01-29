<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dovgailo_v
 * Date: 23.02.12
 * Time: 9:03
 * To change this template use File | Settings | File Templates.
 */

class JFormFieldCatForm extends JFormField
{
    public $type = 'charttable';

    public function getInput()
    {

        $chart_id = (int)$this->form->getField('chart_id')->value;

        if ($chart_id) {
            $db =JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('r.*');
            $query->from('#__cmp_chart_rows AS r');
            $query->where('r.chart_id=' . $chart_id);
            $query->order('r.ordering ASC');
            $db->setQuery($query);

            $rows = $db->loadObjectList();

            $db->setQuery("SELECT `catid` FROM #__cmp_chart_list WHERE id=" . $chart_id);
            $cid = $db->loadResult();

            /* Categories for this item*/

            jimport('joomla.application.categories');
            $cat = JCategories::getInstance('comparisonchart');
            $cat1 = $cat->get($cid);
            if($cat1){
                $cats = $cat1->getChildren();
                $cat_array = array();

                //echo '<label id="jform_catid-lbl" for="jform_catid" class="hasTip required" title="" aria-invalid="false">Category<span class="star">&nbsp;*</span></label>';
                echo '<select class="inputbox required" id="jform_catid" name="jform[catid][]" size="10" multiple="multiple" aria-required="true" required="required">';
                echo '<option value="' . $cat1->id . '" '.$this->IfChecked($cat1->id).'>' . $cat1->title . '</option>';
                foreach ($cats as $c) {
                    $no_child = false;
                    echo '<option value="' . $c->id . '" '.$this->IfChecked($c->id).'>' . str_repeat('-', $c->level - 1) . $c->title . '</option>';
                    while (!$no_child) {
                        if (is_object($c)) {
                            if ($c->hasChildren()) {
                                $c = $c->getChildren(true);
                                foreach ($c as $child) {
                                    echo '<option value="' . $child->id .'" '.$this->IfChecked($child->id).'>' . str_repeat('-', $child->level - 1) . $child->title . '</option>';
                                }
                            } else {
                                $no_child = true;
                            }
                        } else {
                            $no_child = true;

                        }
                    }
                }
                echo '</select>';
            }
            else{
                echo '<select class="inputbox required" name="jform[catid][]" disabled size="10" multiple="multiple" aria-required="true" required="required">';
                echo '<option>'.JText::_('COM_COMPARISONCHART_CREATE_OR_PUBLISH_CATS').'</option>';
                echo '</select>';
            }
        }
    }
    public function IfChecked($id) {
        foreach ($this->value as $val) {
            if ($val==$id) return "selected='selected'";
        }
    }
}