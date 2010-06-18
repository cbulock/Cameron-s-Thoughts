CREATE TABLE `api_parameters` (
   `id` int(8) not null auto_increment,
   `value` varchar(64),
   `method` int(6),
   `required` tinyint(1) not null default '0',
   UNIQUE KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=26;

INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('1', 'value', '1', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('2', 'blogid', '1', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('3', 'callby', '1', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('4', 'id', '2', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('5', 'blogid', '2', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('6', 'where', '2', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('7', 'id', '3', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('8', 'blogid', '3', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('9', 'where', '3', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('10', 'blogid', '4', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('11', 'where', '4', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('12', 'postid', '5', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('13', 'blogid', '5', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('14', 'postid', '6', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('15', 'blogid', '6', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('16', 'entryid', '7', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('17', 'methodid', '12', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('18', 'catid', '13', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('19', 'value', '15', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('20', 'callby', '15', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('21', 'user', '16', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('22', 'pass', '16', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('23', 'postid', '18', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('24', 'text', '18', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('25', 'token', '18', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('26', 'catid', '20', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('27', 'offset', '20', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('28', 'count', '20', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('29', 'count', '21', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('30', 'month', '22', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('31', 'year', '22', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('32', 'offset', '22', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('33', 'count', '22', '0');
