<?php

class SgEcom_Upsap_Block_Adminhtml_Config_Export extends Mage_Adminhtml_Block_System_Config_Form_Field  implements Varien_Data_Form_Element_Renderer_Interface
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $buttonBlock = Mage::app()->getLayout()->createBlock('adminhtml/widget_button');

        $data = array(
            'label'     => Mage::helper('adminhtml')->__('Csv export'),
            'onclick'   => 'setLocation(\''.Mage::helper('adminhtml')->getUrl("/upsap_method/export") . '\' )',
            'class'     => '',
        );

        $html = $buttonBlock->setData($data)->toHtml();

        return $html;
    }
}