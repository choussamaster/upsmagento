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
 * Wallet admin controller
 *
 * @category    Sirateck
 * @package     Sirateck_Lemonway
 * @author Kassim Belghait kassim@sirateck.com
 */
class Sirateck_Lemonway_Adminhtml_Lemonway_WalletController extends Sirateck_Lemonway_Controller_Adminhtml_Lemonway
{
    /**
     * init the wallet
     *
     * @access protected
     * @return Sirateck_Lemonway_Model_Wallet
     */
    protected function _initWallet()
    {
        $walletId  = (int) $this->getRequest()->getParam('id');
        $wallet    = Mage::getModel('sirateck_lemonway/wallet');
        if ($walletId) {
            $wallet->load($walletId);
        }
        Mage::register('current_wallet', $wallet);
        return $wallet;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('sirateck_lemonway')->__('LW'))
             ->_title(Mage::helper('sirateck_lemonway')->__('Wallets'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit wallet - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function editAction()
    {
        $walletId    = $this->getRequest()->getParam('id');
        $wallet      = $this->_initWallet();
        if ($walletId && !$wallet->getId()) {
            $this->_getSession()->addError(
                Mage::helper('sirateck_lemonway')->__('This wallet no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getWalletData(true);
        if (!empty($data)) {
            $wallet->setData($data);
        }
        Mage::register('wallet_data', $wallet);
        $this->loadLayout();
        $this->_title(Mage::helper('sirateck_lemonway')->__('LW'))
             ->_title(Mage::helper('sirateck_lemonway')->__('Wallets'));
        if ($wallet->getId()) {
            $this->_title($wallet->getWalletId());
        } else {
            $this->_title(Mage::helper('sirateck_lemonway')->__('Add wallet'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new wallet action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save wallet - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('wallet')) {
            try {
                $data = $this->_filterDates($data, array('customer_dob'));
                $wallet = $this->_initWallet();
                $wallet->addData($data);
                $wallet->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('sirateck_lemonway')->__('Wallet was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $wallet->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setWalletData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was a problem saving the wallet.')
                );
                Mage::getSingleton('adminhtml/session')->setWalletData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('sirateck_lemonway')->__('Unable to find wallet to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete wallet - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $wallet = Mage::getModel('sirateck_lemonway/wallet');
                $wallet->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('sirateck_lemonway')->__('Wallet was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was an error deleting wallet.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('sirateck_lemonway')->__('Could not find wallet to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete wallet - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function massDeleteAction()
    {
        $walletIds = $this->getRequest()->getParam('wallet');
        if (!is_array($walletIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sirateck_lemonway')->__('Please select wallets to delete.')
            );
        } else {
            try {
                foreach ($walletIds as $walletId) {
                    $wallet = Mage::getModel('sirateck_lemonway/wallet');
                    $wallet->setId($walletId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('sirateck_lemonway')->__('Total of %d wallets were successfully deleted.', count($walletIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was an error deleting wallets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function massStatusAction()
    {
        $walletIds = $this->getRequest()->getParam('wallet');
        if (!is_array($walletIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sirateck_lemonway')->__('Please select wallets.')
            );
        } else {
            try {
                foreach ($walletIds as $walletId) {
                $wallet = Mage::getSingleton('sirateck_lemonway/wallet')->load($walletId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d wallets were successfully updated.', count($walletIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was an error updating wallets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Is Admin change - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function massIsAdminAction()
    {
        $walletIds = $this->getRequest()->getParam('wallet');
        if (!is_array($walletIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sirateck_lemonway')->__('Please select wallets.')
            );
        } else {
            try {
                foreach ($walletIds as $walletId) {
                $wallet = Mage::getSingleton('sirateck_lemonway/wallet')->load($walletId)
                    ->setIsAdmin($this->getRequest()->getParam('flag_is_admin'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d wallets were successfully updated.', count($walletIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was an error updating wallets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Country change - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function massBillingAddressCountryAction()
    {
        $walletIds = $this->getRequest()->getParam('wallet');
        if (!is_array($walletIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sirateck_lemonway')->__('Please select wallets.')
            );
        } else {
            try {
                foreach ($walletIds as $walletId) {
                $wallet = Mage::getSingleton('sirateck_lemonway/wallet')->load($walletId)
                    ->setBillingAddressCountry($this->getRequest()->getParam('flag_billing_address_country'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d wallets were successfully updated.', count($walletIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was an error updating wallets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Is company change - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function massIsCompanyAction()
    {
        $walletIds = $this->getRequest()->getParam('wallet');
        if (!is_array($walletIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sirateck_lemonway')->__('Please select wallets.')
            );
        } else {
            try {
                foreach ($walletIds as $walletId) {
                $wallet = Mage::getSingleton('sirateck_lemonway/wallet')->load($walletId)
                    ->setIsCompany($this->getRequest()->getParam('flag_is_company'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d wallets were successfully updated.', count($walletIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was an error updating wallets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Is debtor change - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function massIsDebtorAction()
    {
        $walletIds = $this->getRequest()->getParam('wallet');
        if (!is_array($walletIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sirateck_lemonway')->__('Please select wallets.')
            );
        } else {
            try {
                foreach ($walletIds as $walletId) {
                $wallet = Mage::getSingleton('sirateck_lemonway/wallet')->load($walletId)
                    ->setIsDebtor($this->getRequest()->getParam('flag_is_debtor'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d wallets were successfully updated.', count($walletIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was an error updating wallets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Nationality change - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function massCustomerNationalityAction()
    {
        $walletIds = $this->getRequest()->getParam('wallet');
        if (!is_array($walletIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sirateck_lemonway')->__('Please select wallets.')
            );
        } else {
            try {
                foreach ($walletIds as $walletId) {
                $wallet = Mage::getSingleton('sirateck_lemonway/wallet')->load($walletId)
                    ->setCustomerNationality($this->getRequest()->getParam('flag_customer_nationality'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d wallets were successfully updated.', count($walletIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was an error updating wallets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Birth country change - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function massCustomerBirthCountryAction()
    {
        $walletIds = $this->getRequest()->getParam('wallet');
        if (!is_array($walletIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sirateck_lemonway')->__('Please select wallets.')
            );
        } else {
            try {
                foreach ($walletIds as $walletId) {
                $wallet = Mage::getSingleton('sirateck_lemonway/wallet')->load($walletId)
                    ->setCustomerBirthCountry($this->getRequest()->getParam('flag_customer_birth_country'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d wallets were successfully updated.', count($walletIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was an error updating wallets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Payer or beneficiary change - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function massPayerOrBeneficiaryAction()
    {
        $walletIds = $this->getRequest()->getParam('wallet');
        if (!is_array($walletIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sirateck_lemonway')->__('Please select wallets.')
            );
        } else {
            try {
                foreach ($walletIds as $walletId) {
                $wallet = Mage::getSingleton('sirateck_lemonway/wallet')->load($walletId)
                    ->setPayerOrBeneficiary($this->getRequest()->getParam('flag_payer_or_beneficiary'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d wallets were successfully updated.', count($walletIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was an error updating wallets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Is One time customer change - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function massIsOnetimeCustomerAction()
    {
        $walletIds = $this->getRequest()->getParam('wallet');
        if (!is_array($walletIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sirateck_lemonway')->__('Please select wallets.')
            );
        } else {
            try {
                foreach ($walletIds as $walletId) {
                $wallet = Mage::getSingleton('sirateck_lemonway/wallet')->load($walletId)
                    ->setIsOnetimeCustomer($this->getRequest()->getParam('flag_is_onetime_customer'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d wallets were successfully updated.', count($walletIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was an error updating wallets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Is default change - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function massIsDefaultAction()
    {
        $walletIds = $this->getRequest()->getParam('wallet');
        if (!is_array($walletIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('sirateck_lemonway')->__('Please select wallets.')
            );
        } else {
            try {
                foreach ($walletIds as $walletId) {
                $wallet = Mage::getSingleton('sirateck_lemonway/wallet')->load($walletId)
                    ->setIsDefault($this->getRequest()->getParam('flag_is_default'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d wallets were successfully updated.', count($walletIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sirateck_lemonway')->__('There was an error updating wallets.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function exportCsvAction()
    {
        $fileName   = 'wallet.csv';
        $content    = $this->getLayout()->createBlock('sirateck_lemonway/adminhtml_wallet_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function exportExcelAction()
    {
        $fileName   = 'wallet.xls';
        $content    = $this->getLayout()->createBlock('sirateck_lemonway/adminhtml_wallet_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Kassim Belghait kassim@sirateck.com
     */
    public function exportXmlAction()
    {
        $fileName   = 'wallet.xml';
        $content    = $this->getLayout()->createBlock('sirateck_lemonway/adminhtml_wallet_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Kassim Belghait kassim@sirateck.com
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/sirateck_lemonway/wallet');
    }
}
