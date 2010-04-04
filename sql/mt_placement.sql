CREATE TABLE `mt_placement` (
   `placement_id` int(11) not null auto_increment,
   `placement_entry_id` int(11) not null default '0',
   `placement_blog_id` int(11) not null default '0',
   `placement_category_id` int(11) not null default '0',
   `placement_is_primary` tinyint(4) not null default '0',
   PRIMARY KEY (`placement_id`),
   KEY `placement_entry_id` (`placement_entry_id`),
   KEY `placement_category_id` (`placement_category_id`),
   KEY `placement_is_primary` (`placement_is_primary`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
