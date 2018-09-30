<?php
$installer = $this;

$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'tit_show_format',
    'varchar(20) NULL DEFAULT "days"'
);
$installer->endSetup();

