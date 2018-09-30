
<?php

class SgEcom_Upsap_Model_Mysql4_Errorlog_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('upsap/errorlog');
    }
}