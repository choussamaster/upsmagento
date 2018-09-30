
<?php
class SgEcom_Upsap_Block_Baseurl extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getUpsap()
     { 
        if (!$this->hasData('upsap')) {
            $this->setData('upsap', Mage::registry('upsap'));
        }
        return $this->getData('upsap');
        
    }
}