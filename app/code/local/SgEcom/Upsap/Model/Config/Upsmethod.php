<?php

class SgEcom_Upsap_Model_Config_Upsmethod
{
    public function toOptionArray()
    {
        return array(
            array('label' => 'UPS Next Day Air', 'value' => '01'),
            array('label' => 'UPS Second Day Air', 'value' => '02'),
            array('label' => 'UPS Ground', 'value' => '03'),
            array('label' => 'UPS Three-Day Select', 'value' => '12'),
            array('label' => 'UPS Next Day Air Saver', 'value' => '13'),
            array('label' => 'UPS Next Day Air Early A.M. SM', 'value' => '14'),
            array('label' => 'UPS Second Day Air A.M.', 'value' => '59'),
            array('label' => 'UPS Saver', 'value' => '65'),
            array('label' => 'UPS Worldwide ExpressSM', 'value' => '07'),
            array('label' => 'UPS Worldwide ExpeditedSM', 'value' => '08'),
            array('label' => 'UPS Standard', 'value' => '11'),
            array('label' => 'UPS Worldwide Express PlusSM', 'value' => '54'),
            array('label' => 'UPS Today StandardSM', 'value' => '82'),
            array('label' => 'UPS Today Dedicated CourrierSM', 'value' => '83'),
            array('label' => 'UPS Today Express', 'value' => '85'),
            array('label' => 'UPS Today Express Saver', 'value' => '86'),
            array('label' => 'UPS Access Point™ Economy', 'value' => '70'),
        );
    }

    public function getUpsMethods(){
        return $this->toOptionArray();
    }
}