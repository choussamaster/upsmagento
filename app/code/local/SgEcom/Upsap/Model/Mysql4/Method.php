
<?php

class SgEcom_Upsap_Model_Mysql4_Method extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('upsap/method', 'upsapshippingmethod_id');
    }
}