
<?php

class SgEcom_Upsap_Block_Adminhtml_Points_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'upsap';
        $this->_controller = 'adminhtml_points';

        $this->_removeButton('save');
        $this->_removeButton('delete');
        $this->_removeButton('reset');

    }

    public function getHeaderText()
    {
        return Mage::helper('upsap')->__("View");
    }
}