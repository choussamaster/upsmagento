
<?php

class SgEcom_Upsap_Adminhtml_Upsap_PointsController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

        return $this;
    }

    protected function _isAllowed()
    {
        return true;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('upsap/accesspoint')->load($id);

        if ($model->getId() || $id == 0) {
            Mage::register('points_data', $model);

            $this->loadLayout();
            /*$this->_setActiveMenu('upsap/conformity');*/

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('UPS Access Point'), Mage::helper('adminhtml')->__('UPS Access Point'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('upsap/adminhtml_points_edit'))
                ->_addLeft($this->getLayout()->createBlock('upsap/adminhtml_points_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('upsap')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }
}