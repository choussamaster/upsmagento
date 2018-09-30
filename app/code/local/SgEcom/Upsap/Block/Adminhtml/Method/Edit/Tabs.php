
<?php

class SgEcom_Upsap_Block_Adminhtml_Method_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('method_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('upsap')->__('UPS Access Point method information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('upsap')->__('UPS Access Point method Information'),
            'title'     => Mage::helper('upsap')->__('UPS Access Point method Information'),
            'content'   => $this->getLayout()->createBlock('upsap/adminhtml_method_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}