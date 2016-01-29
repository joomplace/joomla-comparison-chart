<?php

/**
 * ComparisonChart component for Joomla 3.0
 * @package ComparisonChart
 * @author JoomPlace Team
 * @Copyright Copyright (C) JoomPlace, www.joomplace.com
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgButtonComparisonchart extends JPlugin {

    public function __construct(& $subject, $config) {
        parent::__construct($subject, $config);
        $this->loadLanguage();
    }

    function onDisplay($name, $asset, $author) {
        $js = "
               
                        function jSelectChart(id) {
                        var tag = '{comparisonchart '+id+'}';
			jInsertEditorText(tag, '" . $name . "');
			SqueezeBox.close();
		}
                
";
  
        $css = "a.modal-button  {
                    display: inline-block;
                    padding: 4px 12px;
                    margin-bottom: 0;
                    font-size: 13px;
                    line-height: 18px;
                    text-align: center;
                    vertical-align: middle;
                    cursor: pointer;
                    color: #333;
                    text-shadow: 0 1px 1px rgba(255,255,255,0.75);
                    background-color: #f5f5f5;
                    background-image: linear-gradient(to bottom,#fff,#e6e6e6);
                    background-repeat: repeat-x;
                    border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
                    filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
                    border: 1px solid #bbb;
                    -webkit-border-radius: 4px;
                    -moz-border-radius: 4px;
                    border-radius: 4px;
                    -webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05);
                    -moz-box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05);
                    box-shadow: inset 0 1px 0 rgba(255,255,255,.2), 0 1px 2px rgba(0,0,0,.05);
                }
               
";
        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration($js);
        $doc->addStyleDeclaration($css);
        JHTML::_('behavior.modal');

        $link = 'index.php?option=com_comparisonchart&amp;view=charts&amp;layout=modal&amp;tmpl=component';

        $button = new JObject();
        $button->set('modal', true);
        $button->set('link', $link);
        $button->set('text', JText::_('PLG_COMPARISONCHART_BUTTON_TEXT'));
        $button->set('name', 'article');
        $button->set('options', "{handler: 'iframe', size: {x: 770, y: 400}}");

        //if (JRequest::getVar('option')=='com_content')
        return $button;
        //else return null;
    }

}
