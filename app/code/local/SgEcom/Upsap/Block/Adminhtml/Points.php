
<?php
class SgEcom_Upsap_Block_Adminhtml_Points extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_points';
        $this->_blockGroup = 'upsap';
        $this->_headerText = Mage::helper('upsap')->__('UPS Access Point Shipments');

        parent::__construct();
        $this->_removeButton('add');
    }
}