<?php
/**
 * 

 * @package    Uni_Upload


 */
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('tools_upload')};
CREATE TABLE {$this->getTable('tools_upload')} (
  `upload_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `upload_content` text NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `sort_order` int(11) NOT NULL default '0',
  `created_time` DATETIME NULL,
  `update_time` DATETIME NULL,
  PRIMARY KEY (`upload_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 

");
$installer->endSetup();
