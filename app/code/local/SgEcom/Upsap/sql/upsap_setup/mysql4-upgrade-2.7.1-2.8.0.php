<?php
$installer = $this;
$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'free_shipping',
    'tinyint(2) NOT NULL DEFAULT 0'
);
$installer->endSetup();