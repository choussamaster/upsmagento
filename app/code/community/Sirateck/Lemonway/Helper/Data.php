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
 * Lemonway default helper
 *
 * @category    Sirateck
 * @package     Sirateck_Lemonway
 * @author Kassim Belghait kassim@sirateck.com
 */
class Sirateck_Lemonway_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }
    
    public function reAddToCart($incrementId) {
    
    	$cart = Mage::getSingleton('checkout/cart');
    	$order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
    
    	if ($order->getId()) {
    		$items = $order->getItemsCollection();
    		foreach ($items as $item) {
    			try {
    				$cart->addOrderItem($item);
    			} catch (Mage_Core_Exception $e) {
    				if (Mage::getSingleton('checkout/session')->getUseNotice(true)) {
    					Mage::getSingleton('checkout/session')->addNotice($e->getMessage());
    				} else {
    					Mage::getSingleton('checkout/session')->addError($e->getMessage());
    				}
    			} catch (Exception $e) {
    				Mage::getSingleton('checkout/session')->addException($e, Mage::helper('checkout')->__('Cannot add the item to shopping cart.')
    						);
    			}
    		}
    	}
    
    	$cart->save();
    }
    
    /**
     * @return Sirateck_Lemonway_Model_Config
     */
    public function getConfig(){
    	return Mage::getSingleton('sirateck_lemonway/config');
    }
    
    /**
     * 
     * @return boolean
     */
    public function oneStepCheckoutInstalled(){
    	return Mage::getStoreConfigFlag('onestepcheckout/general/enabled');
    }
}
