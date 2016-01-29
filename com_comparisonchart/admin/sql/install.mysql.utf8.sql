CREATE TABLE IF NOT EXISTS `#__cmp_chart_dashboard_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `published` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__cmp_chart_setup` (
		`c_par_name` varchar(20) NOT NULL default '',
		`c_par_value` varchar(255) NOT NULL default '',
		UNIQUE KEY `c_par_name` (`c_par_name`) ) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__cmp_chart_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `row_id` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL DEFAULT '0',
  `value_data` varchar(255) NOT NULL DEFAULT '0',
  `value_description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `row_id` (`row_id`),
  KEY `item_id` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__cmp_chart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `chart_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(10) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL,
  `template` int(3) NOT NULL,
  `user_rating` int(11) NOT NULL,
  `title_height` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `chart_id` (`chart_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__cmp_chart_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `css` varchar(30) NOT NULL DEFAULT 'default.css',
  `description_before` text,
  `description_after` text,
  `hits` int(10) NOT NULL,
  `published` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__cmp_chart_rating` (
 
  `item_id` int(10) NOT NULL,
  `lastip` varchar(50) NOT NULL,
  `sum` int(255) NOT NULL,
  `count` int(255) NOT NULL,
  `row_id` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__cmp_chart_rows` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `chart_id` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL,
  `units` varchar(255) NOT NULL,
  `direction` varchar(5) NOT NULL,
  `description` text NOT NULL,
  `color` varchar(7) NOT NULL,
  `ordering` int(10) NOT NULL DEFAULT '0',
  `published` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `chart_id` (`chart_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__cmp_chart_templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `temp_name` varchar(100) NOT NULL,
  `header_bkg` varchar(7) NOT NULL,
  `header_ctext` varchar(7) NOT NULL,
  `spacer_bkg` varchar(7) NOT NULL,
  `spacer_ctext` varchar(7) NOT NULL,
  `odd_bkg` varchar(7) NOT NULL,
  `even_bkg` varchar(7) NOT NULL,
  `odd_hover_bkg` varchar(7) NOT NULL,
  `even_hover_bkg` varchar(7) NOT NULL,
  `odd_ctext` varchar(7) NOT NULL,
  `even_ctext` varchar(7) NOT NULL,
  `text_color` varchar(7) NOT NULL,
  `table_header_font` varchar(50) NOT NULL DEFAULT 'Arial',
  `table_header_font_size` int(10) NOT NULL DEFAULT '12',
  `table_spacer_font` varchar(50) NOT NULL DEFAULT 'Arial',
  `table_spacer_font_size` int(10) NOT NULL DEFAULT '12',
  `table_row_font` varchar(50) NOT NULL DEFAULT 'Arial',
  `table_row_font_size` int(10) NOT NULL DEFAULT '12',
  `check_true` varchar(250) NOT NULL,
  `check_false` varchar(250) NOT NULL,
  `best_color` varchar(7) NOT NULL,
  `text_align` varchar(12) NOT NULL,
  `close_image` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__cmp_chart_href` (
  `item_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  KEY `item_id` (`item_id`),
  KEY `cat_id` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `#__cmp_chart_settings` (
 `c_par_name` varchar(50) NOT NULL default '',
 `c_par_value` varchar(500) NOT NULL default '',
  UNIQUE KEY `c_par_name` (`c_par_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

