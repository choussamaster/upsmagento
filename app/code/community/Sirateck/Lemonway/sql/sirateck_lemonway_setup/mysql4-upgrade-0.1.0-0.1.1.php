<?php
/**
 * Sirateck_Lemonway extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category       Sirateck
 * @package        Sirateck_Lemonway
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Lemonway module update script
 *
 * @category    Sirateck
 * @package     Sirateck_Lemonway
 * @author Kassim Belghait kassim@sirateck.com
 */
$installerCustomer = new Mage_Customer_Model_Entity_Setup('sirateck_lemonway_setup');
/* @var $installerCustomer Mage_Customer_Model_Entity_Setup */

$installerCustomer->startSetup();

$entityId = $installerCustomer->getEntityTypeId('customer');
$attribute = $installerCustomer->getAttribute($entityId,'lw_card_id');
if(!$attribute)
{
	
	$installerCustomer->addAttribute('customer','lw_card_id',array(
		'type'         => 'varchar',
	    'label'        => 'Card ID one clic',
	    'visible'      => true,
	    'required'     => false,
		'unique'       => false,
		'sort_order'   	   => 700,
	    'default'	   => 0,
		'input'		   => 'text',

		));
		
	$usedInForms = array(
				'adminhtml_customer',
	        );
	
	$attribute   = Mage::getSingleton('eav/config')->getAttribute('customer', 'lw_card_id');
	$attribute->setData('used_in_forms', $usedInForms);
	$attribute->setData('sort_order', 700);

	$attribute->save();

}

$installerCustomer->endSetup();

