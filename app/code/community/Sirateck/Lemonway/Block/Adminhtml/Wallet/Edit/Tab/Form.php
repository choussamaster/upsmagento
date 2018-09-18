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
 * Wallet edit form tab
 *
 * @category    Sirateck
 * @package     Sirateck_Lemonway
 * @author Kassim Belghait kassim@sirateck.com
 */
class Sirateck_Lemonway_Block_Adminhtml_Wallet_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Sirateck_Lemonway_Block_Adminhtml_Wallet_Edit_Tab_Form
     * @author Kassim Belghait kassim@sirateck.com
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('wallet_');
        $form->setFieldNameSuffix('wallet');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'wallet_form',
            array('legend' => Mage::helper('sirateck_lemonway')->__('Wallet'))
        );

        $fieldset->addField(
            'lw_id',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Lemonway ID'),
                'name'  => 'lw_id',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'customer_id',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Customer ID'),
                'name'  => 'customer_id',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'wallet_id',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Wallet ID'),
                'name'  => 'wallet_id',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'is_admin',
            'select',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Is Admin'),
                'name'  => 'is_admin',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('sirateck_lemonway')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('sirateck_lemonway')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'customer_email',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Email'),
                'name'  => 'customer_email',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'customer_prefix',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Prefix'),
                'name'  => 'customer_prefix',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'customer_firstname',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Firstname'),
                'name'  => 'customer_firstname',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'customer_lastname',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Lastname'),
                'name'  => 'customer_lastname',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'billing_address_street',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Street'),
                'name'  => 'billing_address_street',

           )
        );

        $fieldset->addField(
            'billing_address_postcode',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Postcode'),
                'name'  => 'billing_address_postcode',

           )
        );

        $fieldset->addField(
            'billing_address_city',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('City'),
                'name'  => 'billing_address_city',

           )
        );

        $fieldset->addField(
            'billing_address_country',
            'select',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Country'),
                'name'  => 'billing_address_country',

            'values'=> Mage::getResourceModel('directory/country_collection')->toOptionArray(),
           )
        );

        $fieldset->addField(
            'billing_address_phone',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Phone Number'),
                'name'  => 'billing_address_phone',

           )
        );

        $fieldset->addField(
            'billing_address_mobile',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Mobile Number'),
                'name'  => 'billing_address_mobile',

           )
        );

        $fieldset->addField(
            'customer_dob',
            'date',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Dob'),
                'name'  => 'customer_dob',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'is_company',
            'select',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Is company'),
                'name'  => 'is_company',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('sirateck_lemonway')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('sirateck_lemonway')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'company_name',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Company name'),
                'name'  => 'company_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'company_website',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Company website'),
                'name'  => 'company_website',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'company_description',
            'textarea',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Company description'),
                'name'  => 'company_description',

           )
        );

        $fieldset->addField(
            'company_id_number',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Company ID number'),
                'name'  => 'company_id_number',

           )
        );

        $fieldset->addField(
            'is_debtor',
            'select',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Is debtor'),
                'name'  => 'is_debtor',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('sirateck_lemonway')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('sirateck_lemonway')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'customer_nationality',
            'select',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Nationality'),
                'name'  => 'customer_nationality',

            'values'=> Mage::getResourceModel('directory/country_collection')->toOptionArray(),
           )
        );

        $fieldset->addField(
            'customer_birth_city',
            'text',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('City of Birth'),
                'name'  => 'customer_birth_city',

           )
        );

        $fieldset->addField(
            'customer_birth_country',
            'select',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Birth country'),
                'name'  => 'customer_birth_country',

            'values'=> Mage::getResourceModel('directory/country_collection')->toOptionArray(),
           )
        );

        $fieldset->addField(
            'payer_or_beneficiary',
            'select',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Payer or beneficiary'),
                'name'  => 'payer_or_beneficiary',

            'values'=> Mage::getModel('sirateck_lemonway/wallet_attribute_source_payerorbeneficiary')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'is_onetime_customer',
            'select',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Is One time customer'),
                'name'  => 'is_onetime_customer',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('sirateck_lemonway')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('sirateck_lemonway')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'is_default',
            'select',
            array(
                'label' => Mage::helper('sirateck_lemonway')->__('Is default'),
                'name'  => 'is_default',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('sirateck_lemonway')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('sirateck_lemonway')->__('No'),
                ),
            ),
           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('sirateck_lemonway')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('sirateck_lemonway')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('sirateck_lemonway')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_wallet')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getWalletData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getWalletData());
            Mage::getSingleton('adminhtml/session')->setWalletData(null);
        } elseif (Mage::registry('current_wallet')) {
            $formValues = array_merge($formValues, Mage::registry('current_wallet')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
