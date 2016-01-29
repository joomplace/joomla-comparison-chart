<?php
/**
* ComparisonChart component for Joomla 3.0
* @package ComparisonChart
* @author JoomPlace Team
* @Copyright Copyright (C) JoomPlace, www.joomplace.com
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
*/

defined('_JEXEC') or die('Restricted access');

?>

.style-<?php echo $settings->id;?> .pbigimage {
    margin: 30px;
    text-align: center;
}

.style-<?php echo $settings->id;?> .pcheck {
    color: #999999;
    font: 9px Tahoma, Arial, Geneva, Helvetica, sans-serif;
    padding: 10px 5px 10px 0;
    text-align: center;
}

.style-<?php echo $settings->id;?> .pcompbtn {
    display: block;
    margin: 10px 0;
}

.style-<?php echo $settings->id;?> .pdadv {
    background-color: <?php echo $settings->best_color;?>;
}

.style-<?php echo $settings->id;?> .pdescr {
    font: 11px Verdana, Geneva, Arial, Helvetica, sans-serif;
    padding: 10px 5px;
}

.style-<?php echo $settings->id;?> .pdimage {
    border-bottom: 1px solid #DDDDDD;
    font: 9px Tahoma, Arial, Geneva, Helvetica, sans-serif;
    margin: 15px 15px 10px 15px;
    padding-bottom: 10px;
    text-align: center;
    white-space: nowrap;
}

.style-<?php echo $settings->id;?> .pdimage img {
    margin-bottom: 3px;
}

.style-<?php echo $settings->id;?> .pdinfohead {
    font-weight: bold;
    width: 35%;
}

.style-<?php echo $settings->id;?> .pdtable {
	table-layout: auto;
	/*table-layout: fixed;*/
	empty-cells: show;
	border-collapse: collapse;
	border-spacing: 0;
}

.style-<?php echo $settings->id;?> .pdtable td {
	border: none;
    padding: 5px 10px 5px 10px;
}

.style-<?php echo $settings->id;?> .pdtable td a:hover {
    background: none;
}

.style-<?php echo $settings->id;?> .pdtable tr.pline {
    padding: 0;
}

.style-<?php echo $settings->id;?> .pdtable tr {
    font-size: 11px;
	border: solid 1px #DDD;
}

.style-<?php echo $settings->id;?> .pdtable tr.pnone {
	border: 0px;
}

.style-<?php echo $settings->id;?> .pdtable tr.pdsection {
    background-color: <?php echo $settings->spacer_bkg;?>;
    color: <?php echo $settings->spacer_ctext;?>;
    font: bold <?php echo $settings->table_spacer_font_size;?>px <?php echo $settings->table_spacer_font;?>;
}

.style-<?php echo $settings->id;?> .pdtable tr.pdsection td {
    vertical-align: top;
}

.style-<?php echo $settings->id;?> .pdtable img {
	margin: 0px;
}

.style-<?php echo $settings->id;?> .pdtable tr.pdtitle {
    background-color: <?php echo $settings->header_bkg;?>;
    color: <?php echo $settings->header_ctext;?>;
    font: bold <?php echo $settings->table_header_font_size;?>px <?php echo $settings->table_header_font;?>;
	height: <?php echo $height;?>px;
}

.style-<?php echo $settings->id;?> tr.odd{
	background: <?php echo $settings->odd_bkg;?>;
}

.style-<?php echo $settings->id;?> tr.pline.odd
{
	background-color: <?php echo $settings->odd_bkg;?>;
    color: <?php echo $settings->odd_ctext;?>;
    font: bold <?php echo $settings->table_row_font_size;?>px <?php echo $settings->table_row_font;?>;
}

.style-<?php echo $settings->id;?> tr.pline.even
{
	background-color: <?php echo $settings->even_bkg;?>;
    color: <?php echo $settings->even_ctext;?>;
    font: bold <?php echo $settings->table_row_font_size;?>px <?php echo $settings->table_row_font;?>;
}

.style-<?php echo $settings->id;?> tr.pline.odd:hover
{
	background-color: <?php echo $settings->odd_hover_bkg;?> !important;
}

.style-<?php echo $settings->id;?> tr.pline.even:hover
{
	background-color: <?php echo $settings->even_hover_bkg;?> !important;
}


.style-<?php echo $settings->id;?> .cmp-yes
{
	background: url('<?php echo JURI::root().$settings->check_true;?>') no-repeat scroll 0 0 transparent;
	height: 25px;
    width: 25px;
	display: inline-block;

}

.style-<?php echo $settings->id;?> .cmp-no
{
	background: url('<?php echo JURI::root().$settings->check_false;?>') no-repeat scroll 0 0 transparent;
	height: 25px;
    width: 25px;
	display: inline-block;
}

.style-<?php echo $settings->id;?> tr.pline td
{
	text-align: <?php echo $settings->text_align?$settings->text_align:'center';?>;
}