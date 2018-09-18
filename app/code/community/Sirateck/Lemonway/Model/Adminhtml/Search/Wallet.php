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
 * Admin search model
 *
 * @category    Sirateck
 * @package     Sirateck_Lemonway
 * @author Kassim Belghait kassim@sirateck.com
 */
class Sirateck_Lemonway_Model_Adminhtml_Search_Wallet extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Sirateck_Lemonway_Model_Adminhtml_Search_Wallet
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('sirateck_lemonway/wallet_collection')
            ->addFieldToFilter('wallet_id', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $wallet) {
            $arr[] = array(
                'id'          => 'wallet/1/'.$wallet->getId(),
                'type'        => Mage::helper('sirateck_lemonway')->__('Wallet'),
                'name'        => $wallet->getWalletId(),
                'description' => $wallet->getWalletId(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/lemonway_wallet/edit',
                    array('id'=>$wallet->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
