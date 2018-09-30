<?php
$installer = $this;

$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'increment_package_by_weight',
    'double(7,2) NULL DEFAULT 0'
);
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'increment_price_by_weight',
    'double(7,2) DEFAULT 0'
);
$installer->endSetup();

