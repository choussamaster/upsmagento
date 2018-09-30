<?php

class SgEcom_Upsap_Model_Config_PriceFormat
{
    public function toOptionArray()
    {
        return array(
            array('label' => 'xx.yy', 'value' => 2),
            array('label' => 'xx.y0', 'value' => 1),
        );
    }
}