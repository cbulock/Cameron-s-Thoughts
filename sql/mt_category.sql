CREATE TABLE `mt_category` (
   `category_id` int(11) not null auto_increment,
   `category_blog_id` int(11) not null default '0',
   `category_allow_pings` tinyint(4),
   `category_label` varchar(100) not null,
   `category_basename` varchar(64) not null,
   `category_description` text,
   `category_author_id` int(11),
   `category_ping_urls` text,
   `category_parent` int(11),
   PRIMARY KEY (`category_id`),
   KEY `category_blog_id` (`category_blog_id`),
   KEY `category_label` (`category_label`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
