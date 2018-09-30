<?php

class SgEcom_Upsap_Model_Config_FrontShippingMethod
{
    public function toOptionArray($isMultiSelect = false)
    {
        $storeId = 1;
        /*multistore*/
        $storeId = Mage::app()->getRequest()->getParam('store', NULL);
        if($storeId){
            $code = Mage::helper('upsap')->getStoreByCode($storeId);
            if($code){
                $storeId = $code->getId();
            }
        }
        /*multistore*/

        $option = array();
        $_methods = Mage::getSingleton('shipping/config')->getActiveCarriers(/*multistore*/$storeId/*multistore*/);
        foreach($_methods as $_carrierCode => $_carrier){
            if($_carrierCode !=="dhlint" && $_carrierCode !=="fedex" && $_carrierCode !=="usps" && $_method = $_carrier->getAllowedMethods())  {
                /*if(!$_title = Mage::getStoreConfig('carriers/'.$_carrierCode.'/title', $storeId)) {*/
                    $_title = $_carrierCode;
                /*}*/
                foreach($_method as $_mcode => $_m){
                    $_code = $_carrierCode . '_' . $_mcode;
                    $option[] = array('label' => "(".$_title.")  ". $_m, 'value' => $_code);
                }
            }
        }
        return $option;
    }
}