CREATE TABLE `api_methods` (
   `id` int(6) not null auto_increment,
   `value` varchar(64),
   UNIQUE KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=16;

INSERT INTO `api_methods` (`id`, `value`) VALUES ('1', 'getEntry');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('2', 'prevEntry');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('3', 'nextEntry');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('4', 'lastEntry');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('5', 'commentCount');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('6', 'getComments');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('7', 'getCatID');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('8', 'getLastQuery');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('9', 'getQueryCount');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('10', 'getDirectQueryCount');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('11', 'getAPIMethods');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('12', 'getMethodParameters');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('13', 'getCat');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('14', 'getQueryLog');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('15', 'getUser');
