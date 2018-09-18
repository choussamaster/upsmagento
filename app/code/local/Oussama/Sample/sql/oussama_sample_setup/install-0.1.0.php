<?php
/**
 * Created by PhpStorm.
 * User: chous
 * Date: 1/14/2018
 * Time: 4:04 PM
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$table = $installer->getConnection()
    ->newTable("sample_item")
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    'identity' => true,
    'unsigned' => true,
    'nullable' => false,
    'primary' => true
), 'id')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 50, array(
        'nullable' => false,
    ),'Title')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(),'Description')
;
$installer->getConnection()->createTable($table);
$installer->endSetup();