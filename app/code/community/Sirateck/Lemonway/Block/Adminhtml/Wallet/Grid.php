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
 * Wallet admin grid block
 *
 * @category    Sirateck
 * @package     Sirateck_Lemonway
 * @author Kassim Belghait kassim@sirateck.com
 */
class Sirateck_Lemonway_Block_Adminhtml_Wallet_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('walletGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Sirateck_Lemonway_Block_Adminhtml_Wallet_Grid
     * @author Kassim Belghait kassim@sirateck.com
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('sirateck_lemonway/wallet')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Sirateck_Lemonway_Block_Adminhtml_Wallet_Grid
     * @author Kassim Belghait kassim@sirateck.com
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'wallet_id',
            array(
                'header'    => Mage::helper('sirateck_lemonway')->__('Wallet ID'),
                'align'     => 'left',
                'index'     => 'wallet_id',
            )
        );
        
       /* $options = array();
        foreach (Sirateck_Lemonway_Model_Wallet::$statuesLabel as $key=>$label){
        	$options[$key] = Mage::helper('sirateck_lemonway')->__($label);
        }
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('sirateck_lemonway')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => $options
            )
        );
        $this->addColumn(
            'lw_id',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Lemonway ID'),
                'index'  => 'lw_id',
                'type'=> 'text',

            )
        );*/
        $this->addColumn(
            'customer_id',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Customer ID'),
                'index'  => 'customer_id',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'is_admin',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Is Admin'),
                'index'  => 'is_admin',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('sirateck_lemonway')->__('Yes'),
                    '0' => Mage::helper('sirateck_lemonway')->__('No'),
                )

            )
        );
        $this->addColumn(
            'customer_email',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Email'),
                'index'  => 'customer_email',
                'type'=> 'text',

            )
        );
       /*  $this->addColumn(
            'customer_prefix',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Prefix'),
                'index'  => 'customer_prefix',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'customer_firstname',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Firstname'),
                'index'  => 'customer_firstname',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'customer_lastname',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Lastname'),
                'index'  => 'customer_lastname',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'billing_address_street',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Street'),
                'index'  => 'billing_address_street',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'billing_address_postcode',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Postcode'),
                'index'  => 'billing_address_postcode',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'billing_address_city',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('City'),
                'index'  => 'billing_address_city',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'billing_address_country',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Country'),
                'index'  => 'billing_address_country',
                'type'=> 'country',

            )
        );
        $this->addColumn(
            'billing_address_phone',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Phone Number'),
                'index'  => 'billing_address_phone',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'billing_address_mobile',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Mobile Number'),
                'index'  => 'billing_address_mobile',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'customer_dob',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Dob'),
                'index'  => 'customer_dob',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'is_company',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Is company'),
                'index'  => 'is_company',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('sirateck_lemonway')->__('Yes'),
                    '0' => Mage::helper('sirateck_lemonway')->__('No'),
                )

            )
        );
        $this->addColumn(
            'company_name',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Company name'),
                'index'  => 'company_name',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'company_website',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Company website'),
                'index'  => 'company_website',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'company_id_number',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Company ID number'),
                'index'  => 'company_id_number',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'is_debtor',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Is debtor'),
                'index'  => 'is_debtor',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('sirateck_lemonway')->__('Yes'),
                    '0' => Mage::helper('sirateck_lemonway')->__('No'),
                )

            )
        );
        $this->addColumn(
            'customer_nationality',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Nationality'),
                'index'  => 'customer_nationality',
                'type'=> 'country',

            )
        );
        $this->addColumn(
            'customer_birth_city',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('City of Birth'),
                'index'  => 'customer_birth_city',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'customer_birth_country',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Birth country'),
                'index'  => 'customer_birth_country',
                'type'=> 'country',

            )
        );
        $this->addColumn(
            'payer_or_beneficiary',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Payer or beneficiary'),
                'index'  => 'payer_or_beneficiary',
                'type'  => 'options',
                'options' => Mage::helper('sirateck_lemonway')->convertOptions(
                    Mage::getModel('sirateck_lemonway/wallet_attribute_source_payerorbeneficiary')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'is_onetime_customer',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Is One time customer'),
                'index'  => 'is_onetime_customer',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('sirateck_lemonway')->__('Yes'),
                    '0' => Mage::helper('sirateck_lemonway')->__('No'),
                )

            )
        );
        $this->addColumn(
            'is_default',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Is default'),
                'index'  => 'is_default',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('sirateck_lemonway')->__('Yes'),
                    '0' => Mage::helper('sirateck_lemonway')->__('No'),
                )

            )
        ); */
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('sirateck_lemonway')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('sirateck_lemonway')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('sirateck_lemonway')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('sirateck_lemonway')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('sirateck_lemonway')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sirateck_lemonway')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('sirateck_lemonway')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Sirateck_Lemonway_Block_Adminhtml_Wallet_Grid
     * @author Kassim Belghait kassim@sirateck.com
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('wallet');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('sirateck_lemonway')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('sirateck_lemonway')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('sirateck_lemonway')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sirateck_lemonway')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('sirateck_lemonway')->__('Enabled'),
                            '0' => Mage::helper('sirateck_lemonway')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'is_admin',
            array(
                'label'      => Mage::helper('sirateck_lemonway')->__('Change Is Admin'),
                'url'        => $this->getUrl('*/*/massIsAdmin', array('_current'=>true)),
                'additional' => array(
                    'flag_is_admin' => array(
                        'name'   => 'flag_is_admin',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sirateck_lemonway')->__('Is Admin'),
                        'values' => array(
                                '1' => Mage::helper('sirateck_lemonway')->__('Yes'),
                                '0' => Mage::helper('sirateck_lemonway')->__('No'),
                            )

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'billing_address_country',
            array(
                'label'      => Mage::helper('sirateck_lemonway')->__('Change Country'),
                'url'        => $this->getUrl('*/*/massBillingAddressCountry', array('_current'=>true)),
                'additional' => array(
                    'flag_billing_address_country' => array(
                        'name'   => 'flag_billing_address_country',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sirateck_lemonway')->__('Country'),
                        'values' => Mage::getResourceModel('directory/country_collection')->toOptionArray()

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'is_company',
            array(
                'label'      => Mage::helper('sirateck_lemonway')->__('Change Is company'),
                'url'        => $this->getUrl('*/*/massIsCompany', array('_current'=>true)),
                'additional' => array(
                    'flag_is_company' => array(
                        'name'   => 'flag_is_company',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sirateck_lemonway')->__('Is company'),
                        'values' => array(
                                '1' => Mage::helper('sirateck_lemonway')->__('Yes'),
                                '0' => Mage::helper('sirateck_lemonway')->__('No'),
                            )

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'is_debtor',
            array(
                'label'      => Mage::helper('sirateck_lemonway')->__('Change Is debtor'),
                'url'        => $this->getUrl('*/*/massIsDebtor', array('_current'=>true)),
                'additional' => array(
                    'flag_is_debtor' => array(
                        'name'   => 'flag_is_debtor',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sirateck_lemonway')->__('Is debtor'),
                        'values' => array(
                                '1' => Mage::helper('sirateck_lemonway')->__('Yes'),
                                '0' => Mage::helper('sirateck_lemonway')->__('No'),
                            )

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'customer_nationality',
            array(
                'label'      => Mage::helper('sirateck_lemonway')->__('Change Nationality'),
                'url'        => $this->getUrl('*/*/massCustomerNationality', array('_current'=>true)),
                'additional' => array(
                    'flag_customer_nationality' => array(
                        'name'   => 'flag_customer_nationality',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sirateck_lemonway')->__('Nationality'),
                        'values' => Mage::getResourceModel('directory/country_collection')->toOptionArray()

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'customer_birth_country',
            array(
                'label'      => Mage::helper('sirateck_lemonway')->__('Change Birth country'),
                'url'        => $this->getUrl('*/*/massCustomerBirthCountry', array('_current'=>true)),
                'additional' => array(
                    'flag_customer_birth_country' => array(
                        'name'   => 'flag_customer_birth_country',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sirateck_lemonway')->__('Birth country'),
                        'values' => Mage::getResourceModel('directory/country_collection')->toOptionArray()

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'payer_or_beneficiary',
            array(
                'label'      => Mage::helper('sirateck_lemonway')->__('Change Payer or beneficiary'),
                'url'        => $this->getUrl('*/*/massPayerOrBeneficiary', array('_current'=>true)),
                'additional' => array(
                    'flag_payer_or_beneficiary' => array(
                        'name'   => 'flag_payer_or_beneficiary',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sirateck_lemonway')->__('Payer or beneficiary'),
                        'values' => Mage::getModel('sirateck_lemonway/wallet_attribute_source_payerorbeneficiary')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'is_onetime_customer',
            array(
                'label'      => Mage::helper('sirateck_lemonway')->__('Change Is One time customer'),
                'url'        => $this->getUrl('*/*/massIsOnetimeCustomer', array('_current'=>true)),
                'additional' => array(
                    'flag_is_onetime_customer' => array(
                        'name'   => 'flag_is_onetime_customer',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sirateck_lemonway')->__('Is One time customer'),
                        'values' => array(
                                '1' => Mage::helper('sirateck_lemonway')->__('Yes'),
                                '0' => Mage::helper('sirateck_lemonway')->__('No'),
                            )

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'is_default',
            array(
                'label'      => Mage::helper('sirateck_lemonway')->__('Change Is default'),
                'url'        => $this->getUrl('*/*/massIsDefault', array('_current'=>true)),
                'additional' => array(
                    'flag_is_default' => array(
                        'name'   => 'flag_is_default',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sirateck_lemonway')->__('Is default'),
                        'values' => array(
                                '1' => Mage::helper('sirateck_lemonway')->__('Yes'),
                                '0' => Mage::helper('sirateck_lemonway')->__('No'),
                            )

                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Sirateck_Lemonway_Model_Wallet
     * @return string
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Sirateck_Lemonway_Block_Adminhtml_Wallet_Grid
     * @author Kassim Belghait kassim@sirateck.com
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
