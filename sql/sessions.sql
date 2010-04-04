CREATE TABLE `sessions` (
   `id` int(12) not null auto_increment,
   `user` int(8) not null default '0',
   `guid` varchar(32) not null,
   `last` timestamp default CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
