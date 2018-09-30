<?php
$installer = $this;


$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'addday',
    'INT(2) DEFAULT 0', 0
);
$installer->endSetup();

