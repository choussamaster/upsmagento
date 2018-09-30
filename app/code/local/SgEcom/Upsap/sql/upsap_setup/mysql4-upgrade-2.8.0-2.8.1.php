<?php
$installer = $this;
$installer->startSetup();
$installer->run("
ALTER TABLE {$installer->getTable('upsapshippingmethod')} CHANGE `store_id` `store_id` VARCHAR(255) NOT NULL DEFAULT '0';
	");
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'is_store_all',
    'tinyint(1) NOT NULL default 0'
);
$installer->endSetup();