<?php
class SgEcom_Upsap_Model_Config_AddedValueType
{
    public function toOptionArray()
    {
        $arr = array();
        $arr[] = array('value' => 'static', 'label' => 'Amount');
        $arr[] = array('value' => 'percent', 'label' => 'Percent');
        return $arr;
    }
}