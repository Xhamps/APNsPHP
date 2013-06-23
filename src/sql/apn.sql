--
-- Table structure for table `apn_devices`
--

CREATE TABLE IF NOT EXISTS `apn_devices` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `devicetoken` char(64) NOT NULL,
  `devicename` varchar(255) NOT NULL,
  `devicemodel` varchar(100) NOT NULL,
  `deviceversion` varchar(25) NOT NULL,
  `status` enum('active','uninstalled') NOT NULL DEFAULT 'active',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `devicetoken` (`devicetoken`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Store devices' AUTO_INCREMENT=20 ;
