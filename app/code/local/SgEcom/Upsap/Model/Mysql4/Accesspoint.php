
<?php

class SgEcom_Upsap_Model_Mysql4_Accesspoint extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('upsap/accesspoint', 'ap_id');
    }
}