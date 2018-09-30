
<?php

class SgEcom_Upsap_Block_Adminhtml_Points_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('points_form', array('legend' => Mage::helper('upsap')->__('UPS Access Point information')));

        if (Mage::registry('points_data') && count(Mage::registry('points_data')->getData()) > 0) {
            $data = Mage::registry('points_data')->getData();
            $form->setValues($data);
            $orderIncrementId = Mage::getModel('sales/order')->load($data['order_id']);
            if($orderIncrementId){
                $orderIncrementId = $orderIncrementId->getIncrementId();
            } else {
                $orderIncrementId = $data['order_id'];
            }

            $fieldset->addField('order_id', 'text', array(
                'name' => 'order_id',
                'label' => Mage::helper('upsap')->__('Order #'),
                'title' => Mage::helper('upsap')->__('Order #'),
                'value' => $orderIncrementId
            ));

            $fieldset->addField('appu_id', 'text', array(
                'name' => 'appu_id',
                'label' => Mage::helper('upsap')->__('UPS Access Point ID'),
                'title' => Mage::helper('upsap')->__('UPS Access Point ID'),
                'value' => $data['appu_id']
            ));

            $fieldset->addField('address', 'textarea', array(
                'name' => 'address',
                'label' => Mage::helper('upsap')->__('Address'),
                'title' => Mage::helper('upsap')->__('Address'),
                'value' => $this->parseAddress($data['address'])
            ));
        }

        return parent::_prepareForm();
    }

    private function parseAddress($address){
        $address = json_decode($address, true);
        $addressForm = $address['name'].": 
   ";
        $addressForm .= $address['addLine1']." ";
        if(isset($address['addLine2'])) {
            $addressForm .= $address['addLine2'] . " ";
        }

        $addressForm .= $address['city']." 
   ";

        if(isset($address['state'])) {
            $addressForm .= $address['state'] . " 
   ";
        }

        if(isset($address['postal'])) {
            $addressForm .= $address['postal'] . " 
   ";
        }

        $addressForm .= Mage::getModel('directory/country')->loadByCode($address['country'])->getName()." ";

        return $addressForm;
    }
}