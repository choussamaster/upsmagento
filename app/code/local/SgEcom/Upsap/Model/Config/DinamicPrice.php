<?php


class SgEcom_Upsap_Model_Config_DinamicPrice
{
    public function toOptionArray()
    {
        return array(
            array('label' => Mage::helper('upsap')->__('Static'), 'value' => 0),
            array('label' => 'UPS', 'value' => 1),
        );
    }
}