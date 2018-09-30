<?php

class SgEcom_Upsap_Model_Config_AccesspointType
{
    public function toOptionArray()
    {
        $c = array(
            array('label' => Mage::helper('upsap')->__('Hold for Pickup at UPS Access Point'), 'value' => '01'),
            array('label' => Mage::helper('upsap')->__('UPS Access Point Delivery'), 'value' => '02'),
        );
        return $c;
    }
}