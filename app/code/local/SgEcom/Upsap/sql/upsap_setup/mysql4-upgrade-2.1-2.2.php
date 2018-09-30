<?php
$installer = $this;

$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'company_type',
    'varchar(30) NULL DEFAULT "ups"'
);
$installer->endSetup();

