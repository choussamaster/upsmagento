<?php
$installer = $this;
$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapaccesspoint'), 'appu_id',
    'varchar(30)'
);
$installer->endSetup();