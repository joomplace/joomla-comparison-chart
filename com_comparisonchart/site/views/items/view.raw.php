<?php

/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'view.html.php');
?>
<script type="text/javascript">
	try {
		SqueezeBox.assign($$('a.modal'), {parse: 'rel'});
	} catch(e){}
</script>
<?php
