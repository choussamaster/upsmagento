<?php

class SgEcom_Upsap_Model_Config_ShippingSettingsLink
{
    public function getCommentText()
    {
        return '<a href="'.Mage::helper("adminhtml")->getUrl("adminhtml/upsap_method/index").'" target="_blank">'.Mage::helper('upsap')->__("Shipping Methods for UPS Access Point").'</a>';
    }
}