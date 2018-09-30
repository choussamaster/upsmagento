
<?php

class SgEcom_Upsap_Block_Adminhtml_Points_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('points_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('upsap')->__('UPS Access Point Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('upsap')->__('UPS Access Point Information'),
            'title'     => Mage::helper('upsap')->__('UPS Access Point Information'),
            'content'   => $this->getLayout()->createBlock('upsap/adminhtml_points_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}