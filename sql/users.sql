CREATE TABLE `users` (
   `id` int(8) not null auto_increment,
   `login` varchar(64) not null,
   `pass` varchar(32) not null,
   `type` varchar(8) not null,
   `name` varchar(128) not null,
   `email` varchar(128) not null,
   `url` varchar(255) not null,
   `created` timestamp default CURRENT_TIMESTAMP,
   `style` varchar(128) not null,
   `style_altor` tinyint(1) not null default '0',
   PRIMARY KEY (`id`),
   UNIQUE KEY (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
