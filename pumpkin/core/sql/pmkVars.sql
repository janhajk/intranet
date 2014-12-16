-- 
-- Tabellenstruktur für Tabelle `pmkVars`
-- 

CREATE TABLE `pmkVars` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `pmkVars`
-- 

INSERT INTO `pmkVars` (`id`, `name`, `value`) VALUES (1, 'pmkPageTitle', '["s","pmkPageTitle"]'),
(2, 'pmkPageProducer', '["s","pmkPageProducer"]'),
(3, 'pmkPageProjectTitle', '["s","pmkPageProjectTitle"]');
