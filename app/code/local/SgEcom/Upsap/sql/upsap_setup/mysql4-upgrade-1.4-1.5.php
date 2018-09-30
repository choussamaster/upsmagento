<?php
$installer = $this;

$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'tax',
    'INT(2) DEFAULT 0', 0
);
$installer->endSetup();

