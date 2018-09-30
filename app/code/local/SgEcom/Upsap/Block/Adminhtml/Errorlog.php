
<?php
class SgEcom_Upsap_Block_Adminhtml_Errorlog extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_errorlog';
        $this->_blockGroup = 'upsap';
        $this->_headerText = Mage::helper('upsap')->__('UPS Access Point Errors log');
        parent::__construct();
        $this->_removeButton('add');
    }
}