<?php
/**
 * Created by PhpStorm.
 * User: chous
 * Date: 1/14/2018
 * Time: 4:07 PM
 */ 
class Oussama_Sample_Model_Resource_Item extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('oussama_sample/Item', 'id');
        $this->_isPkAutoIncrement = true;
    }

}