<?php
$installer = $this;
$installer->startSetup();
$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('upsapaccesspoint')};
CREATE TABLE IF NOT EXISTS {$this->getTable('upsapaccesspoint')} (
  `ap_id` int(11) unsigned NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default 0,
  `address` text,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`ap_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");
$installer->endSetup();