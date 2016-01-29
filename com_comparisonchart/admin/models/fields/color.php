<?php

/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die;

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('text');

class JFormFieldColor extends JFormFieldText
{
	public $type = 'color';

	public function getInput()
	{
		/*
		if (!$this->value) {
			$this->value = 'FFFFFF';
		}
		$rgb = $this->hex2rgb($this->value);

		$doc =JFactory::getDocument();
		$doc->addStyleSheet('components/com_comparisonchart/assets/css/mooRainbow.css');
		$doc->addScript('components/com_comparisonchart/assets/js/mooRainbow.js');
		$doc->addScriptDeclaration("
			window.addEvent('domready', function() {
				new MooRainbow('".$this->id."_img', {
					'startColor': [".$rgb['red'].", ".$rgb['green'].", ".$rgb['blue']."],
					'onChange': function(color) {
						var input = $('".$this->id."');
						input.value = color.hex;
						input.setStyle('background', color.hex);
						if (color.rgb[0] < 128 && color.rgb[1] < 128 && color.rgb[2] < 128) {
							input.setStyle('color', '#FFF');
						} else {
							input.setStyle('color', '#000');
						}
					}
				});
			});
		");
		$img = '<img id="'.$this->id.'_img" src="components/com_comparisonchart/assets/images/rainbow.png" alt="[r]" width="16" height="16" />';
		$text = parent::getInput();
		//echo 'SMT DEBUG: <pre>'; print_R($text); echo '</pre>';
		$input = $text.$img;
		*/
		$input=null;
		return $input;
	}
	
	protected function hex2rgb($hexStr) {
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr);
		$rgbArray = array();
		if (strlen($hexStr) == 6) {
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) {
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false;
		}
		return $rgbArray;
	}
}