CREATE TABLE `pmkUsers` (
  `id` int(11) NOT NULL auto_increment,
  `vorname` varchar(50) NOT NULL COMMENT 'Vorname',
  `nachname` varchar(50) NOT NULL COMMENT 'Nachname',
  `email` varchar(255) NOT NULL COMMENT 'Email',
  `psw` varchar(20) NOT NULL COMMENT 'Passwort',
  `rights` int(11) NOT NULL default '5' COMMENT 'Rechte [1-10]',
  `last_action` datetime default NULL COMMENT 'Letzte Tätigkeit',
  `noscript` int(11) NOT NULL default '0' COMMENT 'JavaScript',
  `telefon1` varchar(50) NOT NULL COMMENT 'Telefon 1',
  `telefon2` varchar(50) NOT NULL COMMENT 'Telefon 2',
  `comment` text NOT NULL COMMENT 'Kommentar',
  `browser` varchar(255) NOT NULL COMMENT 'Browser',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- 
-- Daten für Tabelle `pmkUsers`
-- 
INSERT INTO `pmkUsers` (`vorname`, `nachname`, `email`, `psw`, `rights`, `last_action`, `noscript`, `telefon1`, `telefon2`, `comment`, `browser`) VALUES ('admin', 'admin', 'admin', 'foxtrott', '10', '', '', '', '', 'admin', '');
