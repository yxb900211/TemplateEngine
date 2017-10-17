CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `savepath` text NOT NULL COMMENT 'MD5',
  `savename` text NOT NULL COMMENT 'MD5',
  `size` int(11) NOT NULL COMMENT 'MD5',
  `type` varchar(50) NOT NULL COMMENT 'MD5',
  `ext` varchar(50) NOT NULL COMMENT 'MD5',
  `md5` char(32) NOT NULL COMMENT 'MD5',
  `sha1` varchar(40) NOT NULL COMMENT 'sha1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;