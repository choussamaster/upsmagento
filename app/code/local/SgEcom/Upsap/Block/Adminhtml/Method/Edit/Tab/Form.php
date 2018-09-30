
<?php

class SgEcom_Upsap_Block_Adminhtml_Method_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $store = $this->getRequest()->getParam('store', 0);
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('method_form', array('legend' => Mage::helper('upsap')->__('UPS Access Point method information')));

        $fieldset->addField('title', 'text', array(
            'name' => 'title',
            'label' => Mage::helper('upsap')->__('Title'),
            'title' => Mage::helper('upsap')->__('Title'),
            'required' => true,
            'after_element_html' => '<p class="nm"><small>' . Mage::helper('upsap')->__('It appears only in admin interface') . '</small></p>',
        ));

        $fieldset->addField('name', 'text', array(
            'name' => 'name',
            'label' => Mage::helper('upsap')->__('Method name'),
            'title' => Mage::helper('upsap')->__('Method name'),
            'required' => true,
        ));

        $fieldset->addField('upsmethod_id', 'select', array(
            'name' => 'upsmethod_id',
            'label' => Mage::helper('upsap')->__('UPS Shipping method'),
            'title' => Mage::helper('upsap')->__('UPS Shipping method'),
            'required' => true,
            'values' => Mage::getModel('upsap/config_upsmethod')->toOptionArray(),
        ));

        $isDinamic = $fieldset->addField('dinamic_price', 'select', array(
            'name' => 'dinamic_price',
            'label' => Mage::helper('upsap')->__('Price Source'),
            'title' => Mage::helper('upsap')->__('Price Source'),
            'values' => Mage::getModel('upsap/config_dinamicPrice')->toOptionArray(),
        ));

        $price = $fieldset->addField('price', 'text', array(
            'name' => 'price',
            'label' => Mage::helper('upsap')->__('Price'),
            'title' => Mage::helper('upsap')->__('Price'),
        ));

        /*$company_type = $fieldset->addField('company_type', 'select', array(
            'name' => 'company_type',
            'label' => Mage::helper('upsap')->__('Carrier'),
            'title' => Mage::helper('upsap')->__('Carrier'),
            'values' => Mage::getModel('upsap/config_shippingCompany')->toOptionArray(false),
        ));*/

        $addedValueType = $fieldset->addField('added_value_type', 'select', array(
            'name' => 'added_value_type',
            'label' => Mage::helper('upsap')->__('Add extra price type'),
            'title' => Mage::helper('upsap')->__('Add extra price type'),
            'values' => Mage::getModel('upsap/config_addedValueType')->toOptionArray(),
        ));

        $addedValue = $fieldset->addField('added_value', 'text', array(
            'name' => 'added_value',
            'label' => Mage::helper('upsap')->__('Add extra value'),
            'title' => Mage::helper('upsap')->__('Add extra value'),
        ));

        $negotiated = $fieldset->addField('negotiated', 'select', array(
            'name' => 'negotiated',
            'label' => Mage::helper('upsap')->__('Negotiated rates'),
            'title' => Mage::helper('upsap')->__('Negotiated rates'),
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $negotiatedAmountFrom = $fieldset->addField('negotiated_amount_from', 'text', array(
            'name' => 'negotiated_amount_from',
            'label' => Mage::helper('upsap')->__('Add price from which the Negotiated rates starts'),
            'title' => Mage::helper('upsap')->__('Add price from which the Negotiated rates starts'),
            'value' => 0,
        ));

        $timeintransit = $fieldset->addField('timeintransit', 'select', array(
            'name' => 'timeintransit',
            'label' => Mage::helper('upsap')->__('Time in transit'),
            'title' => Mage::helper('upsap')->__('Time in transit'),
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $titshowformat = $fieldset->addField('tit_show_format', 'select', array(
            'name' => 'tit_show_format',
            'label' => Mage::helper('upsap')->__('Time in transit format'),
            'title' => Mage::helper('upsap')->__('Time in transit format'),
            'options' => array('days' => '1 day(s)', 'datetime' => '09 July 2016 10:30')
        ));

        $addday = $fieldset->addField('addday', 'text', array(
            'name' => 'addday',
            'label' => Mage::helper('upsap')->__('Additional days'),
            'title' => Mage::helper('upsap')->__('Additional days'),
            'value' => 0,
        ));

        /*$increment_price_by_weight = $fieldset->addField('increment_price_by_weight', 'text', array(
            'name' => 'increment_price_by_weight',
            'label' => Mage::helper('upsap')->__('Weight jump for price doubling'),
            'title' => Mage::helper('upsap')->__('Weight jump for price doubling'),
        ));

        $increment_package_by_weight = $fieldset->addField('increment_package_by_weight', 'text', array(
            'name' => 'increment_package_by_weight',
            'label' => Mage::helper('upsap')->__('Weight jump for new package'),
            'title' => Mage::helper('upsap')->__('Weight jump for new package'),
        ));*/

        $fieldset->addField('amount_min', 'text', array(
            'name' => 'amount_min',
            'label' => Mage::helper('upsap')->__('Minimum Order Amount'),
            'title' => Mage::helper('upsap')->__('Minimum Order Amount'),
            'value' => 0,
        ));
        $fieldset->addField('amount_max', 'text', array(
            'name' => 'amount_max',
            'label' => Mage::helper('upsap')->__('Maximum Order Amount'),
            'title' => Mage::helper('upsap')->__('Maximum Order Amount'),
            'value' => 0,
            'after_element_html' => '<p class="note"><span>' . Mage::helper('upsap')->__('If 0 then infinity') . '</span></p>',
        ));
        $fieldset->addField('weight_min', 'text', array(
            'name' => 'weight_min',
            'label' => Mage::helper('upsap')->__('Minimum Order Weight'),
            'title' => Mage::helper('upsap')->__('Minimum Order Weight'),
            'value' => 0,
        ));
        $fieldset->addField('weight_max', 'text', array(
            'name' => 'weight_max',
            'label' => Mage::helper('upsap')->__('Maximum Order Weight'),
            'title' => Mage::helper('upsap')->__('Maximum Order Weight'),
            'value' => 0,
            'after_element_html' => '<p class="note"><span>' . Mage::helper('upsap')->__('If 0 then infinity') . '</span></p>',
        ));
        $fieldset->addField('qty_min', 'text', array(
            'name' => 'qty_min',
            'label' => Mage::helper('upsap')->__('Minimum Product Quantity'),
            'title' => Mage::helper('upsap')->__('Minimum Product Quantity'),
            'value' => 0,
        ));
        $fieldset->addField('qty_max', 'text', array(
            'name' => 'qty_max',
            'label' => Mage::helper('upsap')->__('Maximum Product Quantity'),
            'title' => Mage::helper('upsap')->__('Maximum Product Quantity'),
            'value' => 0,
            'after_element_html' => '<p class="note"><span>' . Mage::helper('upsap')->__('If 0 then infinity') . '</span></p>',
        ));
        $fieldset->addField('zip_min', 'text', array(
            'name' => 'zip_min',
            'label' => Mage::helper('upsap')->__('Minimum ZIP/Postal code'),
            'title' => Mage::helper('upsap')->__('Minimum ZIP/Postal code'),
            'value' => "",
        ));
        $fieldset->addField('zip_max', 'text', array(
            'name' => 'zip_max',
            'label' => Mage::helper('upsap')->__('Maximum ZIP/Postal code'),
            'title' => Mage::helper('upsap')->__('Maximum ZIP/Postal code'),
            'value' => "",
        ));
        $fieldset->addField('tax', 'select', array(
            'name' => 'tax',
            'label' => Mage::helper('upsap')->__('Duty and Tax'),
            'title' => Mage::helper('upsap')->__('Duty and Tax'),
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));
        $is_country_all = $fieldset->addField('is_country_all', 'select', array(
            'name' => 'is_country_all',
            'label' => __('Ship to Applicable Countries'),
            'title' => __('Ship to Applicable Countries'),
            'values' => Mage::getModel('adminhtml/system_config_source_shipping_allspecificcountries')->toOptionArray(),
        ));
        $country_ids = $fieldset->addField('country_ids', 'multiselect', array(
            'name' => 'country_ids',
            'label' => Mage::helper('upsap')->__('Allowed Countries'),
            'title' => Mage::helper('upsap')->__('Allowed Countries'),
            'required' => true,
            'values' => Mage::getModel('adminhtml/system_config_source_country')->toOptionArray(),
        ));

        $fieldset->addField('user_group_ids', 'multiselect', array(
            'name' => 'user_group_ids',
            'label' => Mage::helper('upsap')->__('User Groups'),
            'title' => Mage::helper('upsap')->__('User Groups'),
            'required' => false,
            'values' => Mage::getResourceModel('customer/group_collection')
                ->loadData()->toOptionArray(),
        ));

/*
        $paymentMethods = $fieldset->addField('payment_methods', 'select', array(
            'name' => 'payment_methods',
            'label' => Mage::helper('upsap')->__('Payment Methods'),
            'title' => Mage::helper('upsap')->__('Payment Methods'),
            'values' => Mage::getModel('upsap/config_paymentMethods')->toOptionArray(),
            'after_element_html' => '<p class="note"><span>' . Mage::helper('upsap')->__('If 0 then infinity') . '</span></p>',
        ));*/

        /*multistore*/
        $is_store_all = $fieldset->addField('is_store_all', 'select', array(
            'name' => 'is_store_all',
            'label' => __('Apply to Store'),
            'title' => __('Apply to Store'),
            'values' => Mage::getModel('upsap/config_Specific')->toOptionArray(),
        ));

        $store_ids = $fieldset->addField('store_id', 'multiselect', array(
            'name' => 'store_id',
            'label' => Mage::helper('upsap')->__('Allowed Stores'),
            'value' => $store,
            'values' => Mage::helper('upsap')->toOptionArrayStores(true),
            /*'disabled' => true,*/
        ));
        /*multistore*/

        $fieldset->addField('free_shipping', 'select', array(
            'name' => 'free_shipping',
            'label' => Mage::helper('upsap')->__('Free Shipping'),
            'title' => Mage::helper('upsap')->__('Free Shipping'),
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $fieldset->addField('status', 'select', array(
            'name' => 'status',
            'label' => Mage::helper('upsap')->__('Enabled'),
            'title' => Mage::helper('upsap')->__('Enabled'),
            'values' => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $this->setChild('form_after', $this->getLayout()
            ->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap($price->getHtmlId(), $price->getName())
            ->addFieldMap($isDinamic->getHtmlId(), $isDinamic->getName())
            ->addFieldMap($titshowformat->getHtmlId(), $titshowformat->getName())
            /*->addFieldMap($company_type->getHtmlId(), $company_type->getName())*/
            ->addFieldMap($is_country_all->getHtmlId(), $is_country_all->getName())
            ->addFieldMap($country_ids->getHtmlId(), $country_ids->getName())
            ->addFieldMap($is_store_all->getHtmlId(), $is_store_all->getName())
            ->addFieldMap($store_ids->getHtmlId(), $store_ids->getName())
            ->addFieldMap($addedValueType->getHtmlId(), $addedValueType->getName())
            ->addFieldMap($addedValue->getHtmlId(), $addedValue->getName())
            /*->addFieldMap($increment_price_by_weight->getHtmlId(), $increment_price_by_weight->getName())
            ->addFieldMap($increment_package_by_weight->getHtmlId(), $increment_package_by_weight->getName())*/
            ->addFieldDependence($price->getName(), $isDinamic->getName(), 0)
            ->addFieldMap($negotiated->getHtmlId(), $negotiated->getName())
            ->addFieldDependence($negotiated->getName(), $isDinamic->getName(), 1)
            /*->addFieldDependence($company_type->getName(), $isDinamic->getName(), 1)*/
            ->addFieldMap($negotiatedAmountFrom->getHtmlId(), $negotiatedAmountFrom->getName())
            ->addFieldDependence($negotiatedAmountFrom->getName(), $negotiated->getName(), 1)
            ->addFieldMap($timeintransit->getHtmlId(), $timeintransit->getName())
            ->addFieldMap($addday->getHtmlId(), $addday->getName())
            ->addFieldDependence($timeintransit->getName(), $isDinamic->getName(), 1)
            ->addFieldDependence($addday->getName(), $timeintransit->getName(), 1)
            ->addFieldDependence($addday->getName(), $titshowformat->getName(), 'days')
            ->addFieldDependence($titshowformat->getName(), $timeintransit->getName(), 1)
            ->addFieldDependence($country_ids->getName(), $is_country_all->getName(), 1)
            ->addFieldDependence($store_ids->getName(), $is_store_all->getName(), 1)
            ->addFieldDependence($addedValueType->getName(), $isDinamic->getName(), 1)
            ->addFieldDependence($addedValue->getName(), $isDinamic->getName(), 1)
            /*->addFieldDependence($increment_price_by_weight->getName(), $isDinamic->getName(), 0)
            ->addFieldDependence($increment_package_by_weight->getName(), $isDinamic->getName(), 0)*/
        );
        if (Mage::getSingleton('adminhtml/session')->getAccountData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getAccountData());
            Mage::getSingleton('adminhtml/session')->setAccountData(null);
        } elseif (Mage::registry('method_data') && count(Mage::registry('method_data')->getData()) > 0) {
            $data = Mage::registry('method_data')->getData();
            $form->setValues($data);
        }
        return parent::_prepareForm();
    }
}