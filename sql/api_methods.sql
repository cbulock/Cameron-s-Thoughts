CREATE TABLE `api_methods` (
   `id` int(6) not null auto_increment,
   `value` varchar(64),
   UNIQUE KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=36;

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
INSERT INTO `api_methods` (`id`, `value`) VALUES ('16', 'login');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('17', 'getAuthUser');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('18', 'postComment');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('19', 'logout');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('20', 'getCatEntries');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('21', 'getNewEntries');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('22', 'getMonthlyEntries');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('23', 'postEntry');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('24', 'commentCountText');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('25', 'getLatestStatus');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('26', 'getCatList');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('27', 'sendMessage');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('28', 'getSetting');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('29', 'clearCache');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('30', 'getImageDetails');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('31', 'getComment');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('32', 'createUser');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('33', 'nameFree');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('34', 'search');
INSERT INTO `api_methods` (`id`, `value`) VALUES ('35', 'addStat');
