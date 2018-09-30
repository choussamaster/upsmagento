<?php

class SgEcom_Upsap_Model_Config_ErrorLogLink
{
    public function getCommentText()
    {
        return '<a href="'.Mage::helper("adminhtml")->getUrl("adminhtml/upsap_errorlog/index").'" target="_blank">'.Mage::helper('upsap')->__("Errors log").'</a>';
    }
}