<?php


class SgEcom_Upsap_Model_Config_Specific
{
    public function toOptionArray()
    {
        return array(
            array('label' => Mage::helper('upsap')->__('All'), 'value' => '0'),
            array('label' => Mage::helper('upsap')->__('Specific'), 'value' => '1'),
        );
    }
}