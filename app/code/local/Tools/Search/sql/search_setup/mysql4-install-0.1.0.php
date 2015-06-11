<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('tools_search')};
CREATE TABLE {$this->getTable('tools_search')} (
  `search_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `content` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`search_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('tools_search_download')};
CREATE TABLE {$this->getTable('tools_search_download')} (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isbn` char(20) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

    ");



$installer->endSetup(); 