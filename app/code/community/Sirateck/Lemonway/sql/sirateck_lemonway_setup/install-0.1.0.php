<?php
/**
 * Sirateck_Lemonway extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Sirateck
 * @package        Sirateck_Lemonway
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Lemonway module install script
 *
 * @category    Sirateck
 * @package     Sirateck_Lemonway
 * @author Kassim Belghait kassim@sirateck.com
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('sirateck_lemonway/wallet'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Wallet ID'
    )
    ->addColumn(
        'lw_id',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Lemonway ID'
    )
    ->addColumn(
        'customer_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'Customer ID'
    )
    ->addColumn(
        'wallet_id',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Wallet ID'
    )
    ->addColumn(
        'is_admin',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
            'nullable'  => false,
        ),
        'Is Admin'
    )
    ->addColumn(
        'customer_email',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Email'
    )
    ->addColumn(
        'customer_prefix',
        Varien_Db_Ddl_Table::TYPE_TEXT, 100,
        array(
            'nullable'  => false,
        ),
        'Prefix'
    )
    ->addColumn(
        'customer_firstname',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Firstname'
    )
    ->addColumn(
        'customer_lastname',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Lastname'
    )
    ->addColumn(
        'billing_address_street',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Street'
    )
    ->addColumn(
        'billing_address_postcode',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Postcode'
    )
    ->addColumn(
        'billing_address_city',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'City'
    )
    ->addColumn(
        'billing_address_country',
        Varien_Db_Ddl_Table::TYPE_TEXT, 2,
        array(),
        'Country'
    )
    ->addColumn(
        'billing_address_phone',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Phone Number'
    )
    ->addColumn(
        'billing_address_mobile',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Mobile Number'
    )
    ->addColumn(
        'customer_dob',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Dob'
    )
    ->addColumn(
        'is_company',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Is company'
    )
    ->addColumn(
        'company_name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Company name'
    )
    ->addColumn(
        'company_website',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Company website'
    )
    ->addColumn(
        'company_description',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Company description'
    )
    ->addColumn(
        'company_id_number',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Company ID number'
    )
    ->addColumn(
        'is_debtor',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Is debtor'
    )
    ->addColumn(
        'customer_nationality',
        Varien_Db_Ddl_Table::TYPE_TEXT, 2,
        array(),
        'Nationality'
    )
    ->addColumn(
        'customer_birth_city',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'City of Birth'
    )
    ->addColumn(
        'customer_birth_country',
        Varien_Db_Ddl_Table::TYPE_TEXT, 2,
        array(),
        'Birth country'
    )
    ->addColumn(
        'payer_or_beneficiary',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(),
        'Payer or beneficiary'
    )
    ->addColumn(
        'is_onetime_customer',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
            'nullable'  => false,
        ),
        'Is One time customer'
    )
    ->addColumn(
        'is_default',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
            'nullable'  => false,
        ),
        'Is default'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Wallet Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Wallet Creation Time'
    ) 
    ->setComment('Wallet Table');
$this->getConnection()->createTable($table);
$this->endSetup();
