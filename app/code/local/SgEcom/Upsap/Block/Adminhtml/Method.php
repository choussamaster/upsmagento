
<?php
class SgEcom_Upsap_Block_Adminhtml_Method extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        /*multistore*/
        $store = $this->getRequest()->getParam('store', 0);
        /*multistore*/
        $this->_controller = 'adminhtml_method';
        $this->_blockGroup = 'upsap';
        $this->_headerText = Mage::helper('upsap')->__('UPS Access Point Methods');
        $this->_addButtonLabel = Mage::helper('upsap')->__('Add method');

        $data = array(
            'label' =>  Mage::helper('upsap')->__('Add method'),
            'class' => 'scalable add',
            'onclick'   => "setLocation('".$this->getUrl('adminhtml/upsap_method/new', array('store' => $store))."')"
        );
        $this->addButton('method_add', $data, 0, 100,  'header', 'header');
        parent::__construct();
        $this->_removeButton('add');
    }
}