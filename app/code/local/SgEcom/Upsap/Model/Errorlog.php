
<?php

class SgEcom_Upsap_Model_Errorlog extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('upsap/errorlog');
    }
}