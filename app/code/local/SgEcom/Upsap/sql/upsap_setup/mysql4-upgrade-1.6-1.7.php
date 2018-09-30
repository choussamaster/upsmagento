<?php
$installer = $this;


$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'timeintransit',
    'TINYINT(1) DEFAULT 0', 0
);
$installer->endSetup();

