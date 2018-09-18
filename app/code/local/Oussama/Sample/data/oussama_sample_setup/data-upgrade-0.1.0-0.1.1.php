<?php
/**
 * Created by PhpStorm.
 * User: chous
 * Date: 1/14/2018
 * Time: 4:04 PM
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
Mage::getModel('oussama_sample/item')->setData(array(
    'title' => "test 1",
    'description' => "Ma première description"
))->save();
Mage::getModel('oussama_sample/item')->setData(array(
    'title' => "test 2",
    'description' => "Ma deuxieme description"
))->save();
