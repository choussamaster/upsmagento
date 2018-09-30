<?php
$installer = $this;


$installer->startSetup();
$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('upsapshippingmethod')} (
  `upsapshippingmethod_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(250) NOT NULL default '',
  `name` varchar(250) NOT NULL default '',
  `upsmethod_id` varchar(50) NOT NULL default '',
  `store_id` int(11) NOT NULL default 1,
  `country_ids` text NOT NULL default '',
  `price` decimal(9,2) NOT NULL default 0,
  `status` tinyint(1) NOT NULL default 0,
  `dinamic_price` tinyint(1) NOT NULL default 0,
  PRIMARY KEY (`upsapshippingmethod_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();

