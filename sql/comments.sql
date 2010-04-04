CREATE TABLE `comments` (
   `id` int(11) not null auto_increment,
   `blogid` int(2) not null default '0',
   `postid` int(11) not null default '0',
   `user` int(8) not null default '0',
   `ip` varchar(16),
   `author` varchar(100),
   `email` varchar(75),
   `url` varchar(255),
   `text` text,
   `created` timestamp default CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   KEY `comment_created_on` (`created`),
   KEY `comment_entry_id` (`postid`),
   KEY `comment_blog_id` (`blogid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
