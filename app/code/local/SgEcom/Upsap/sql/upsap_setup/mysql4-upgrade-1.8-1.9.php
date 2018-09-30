<?php
$installer = $this;

$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'qty_min',
    'int(11) DEFAULT 0', 0
);
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'qty_max',
    'int(11) DEFAULT 0', 0
);

$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'zip_min',
    'varchar(20) DEFAULT ""', 0
);
$installer->getConnection()->addColumn($installer->getTable('upsapshippingmethod'), 'zip_max',
    'varchar(20) DEFAULT ""', 0
);
$installer->endSetup();

