<?php
/**
 * Created by PhpStorm.
 * User: chous
 * Date: 1/14/2018
 * Time: 4:17 PM
 */
class Oussama_Sample_Block_Item extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct()
    {
        $this->_blockGroup      = 'oussama_sample';
        $this->_controller      = 'item';
        // $this->_headerText      = $this->__('Grid Header Text');
        // $this->_addButtonLabel  = $this->__('Add Button Label');
        parent::__construct();
            }

    public function getCreateUrl()
    {
        return $this->getUrl('*/*/new');
    }

}

