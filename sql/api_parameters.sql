CREATE TABLE `api_parameters` (
   `id` int(8) not null auto_increment,
   `value` varchar(64),
   `method` int(6),
   `required` tinyint(1) not null default '0',
   UNIQUE KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=60;

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
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('22', 'pass', '16', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('21', 'user', '16', '1');
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
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('34', 'title', '23', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('35', 'text', '23', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('37', 'blogid', '23', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('38', 'convert_breaks', '23', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('39', 'excerpt', '23', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('40', 'keywords', '23', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('36', 'category', '23', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('41', 'count', '24', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('42', 'name', '27', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('43', 'email', '27', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('44', 'message', '27', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('45', 'setting', '28', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('46', 'name', '30', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('47', 'id', '31', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('48', 'login', '32', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('49', 'name', '32', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('50', 'url', '32', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('51', 'pass', '32', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('52', 'type', '32', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('53', 'email', '32', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('54', 'service', '32', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('55', 'service_id', '32', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('56', 'login', '33', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('57', 'term', '34', '1');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('58', 'blogid', '34', '0');
INSERT INTO `api_parameters` (`id`, `value`, `method`, `required`) VALUES ('59', 'type', '35', '0');
