<?php
$installer = $this;
$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'user_group_ids',
    'text'
);
$installer->endSetup();