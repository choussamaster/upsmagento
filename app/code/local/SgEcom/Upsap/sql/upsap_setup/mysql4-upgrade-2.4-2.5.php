<?php
$installer = $this;
$installer->startSetup();
$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('upsap_error')} (
  `aperr_id` int(11) unsigned NOT NULL auto_increment,
  `error_message` text,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp,
  PRIMARY KEY (`aperr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");
$installer->endSetup();