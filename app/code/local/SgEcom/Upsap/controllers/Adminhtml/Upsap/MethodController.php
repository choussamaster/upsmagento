
<?php

class SgEcom_Upsap_Adminhtml_Upsap_MethodController extends Mage_Adminhtml_Controller_Action
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
        $model = Mage::getModel('upsap/method')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
                $model->setUserGroupIds(trim($model->getUserGroupIds(), ","));
            }
            Mage::register('method_data', $model);

            $this->loadLayout();
            /*$this->_setActiveMenu('upsap/conformity');*/

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('UPS Access Point Method Manager'), Mage::helper('adminhtml')->__('UPS Access Point Method Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('UPS Access Point Method News'), Mage::helper('adminhtml')->__('UPS Access Point Method News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('upsap/adminhtml_method_edit'))
                ->_addLeft($this->getLayout()->createBlock('upsap/adminhtml_method_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('upsap')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $model = Mage::getModel('upsap/method');
            if(isset($data['country_ids']) && count($data['country_ids'])>0){
                $data['country_ids'] = implode(",", $data['country_ids']);
            }

            if (isset($data['store_id']) && !empty($data['store_id'])) {
                $data['store_id'] = implode(",", $data['store_id']);
            } else {
                $data['store_id'] = '';
            }

            if (isset($data['user_group_ids']) && !empty($data['user_group_ids'])) {
                $data['user_group_ids'] = ",".implode(",", $data['user_group_ids']).",";
            } else {
                $data['user_group_ids'] = '';
            }

            $data['company_type'] = Mage::getStoreConfig('carriers/upsap/company_type');
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            $model->save();
            try {
                    Mage::getSingleton('adminhtml/session')->setFormData(false);
                    if ($this->getRequest()->getParam('back')) {
                        $this->_redirect('*/*/edit', array('id' => $model->getId()));
                        return;
                    }

                    $this->_redirect('*/*/');
                    return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('upsap')->__('Unable to find method to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id > 0) {
            try {
                $collection = Mage::getModel('upsap/method')->load($id);
                $collection->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $upsapIds = $this->getRequest()->getParam('method');
        if (!is_array($upsapIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($upsapIds as $upsapId) {
                    $collection = Mage::getModel('upsap/method')->load($upsapId);
                    $collection->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($upsapIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction()
    {
        $fileName = 'access_point_methods.csv';
        $content = $this->getLayout()->createBlock('upsap/adminhtml_method_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportAction()
    {
        $fileName = 'ups_access_point_methods.csv';
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Description: File Transfer');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$fileName}");
        header("Expires: 0");
        header("Pragma: public");

        $fp = fopen( 'php://output', 'w');
        $collection = Mage::getModel('upsap/method')->getCollection();
        $firstRow = array(
            'title',
            'name',
            'ups_method_code',
            'country_ids',
            'zip_min',
            'zip_max',
            'weight_min',
            'weight_max',
            'qty_min',
            'qty_max',
            'order_amount_min',
            'order_amount_max',
            'dinamic_price',
            /*'carrier_code',*/
            'price',
            'added_price_type',
            'added_price_value',
            'status',
            'negotiated',
            'negotiated_amount_from',
            'tax',/*multistore*/
            'store_id',/*multistore*/
            'free_shipping',
            /*'weight_jump_for_price_doubling',
            'weight_jump_for_new_package',*/
            'user_group_ids',
        );
        fputcsv($fp, $firstRow, ',');
        foreach($collection AS $item){
            $itemData = $item->getData();
            $row = array();
            $row[] = $itemData['title'];
            $row[] = $itemData['name'];
            $row[] = $itemData['upsmethod_id'];
            if ($itemData['is_country_all'] == 0) {
                $row[] = 'all';
            } else {
                $row[] = $itemData['country_ids'];
            }
            $row[] = $itemData['zip_min'];
            $row[] = $itemData['zip_max'];
            $row[] = $itemData['weight_min'];
            $row[] = $itemData['weight_max'];
            $row[] = $itemData['qty_min'];
            $row[] = $itemData['qty_max'];
            $row[] = $itemData['amount_min'];
            $row[] = $itemData['amount_max'];
            $row[] = $itemData['dinamic_price'];
            /*$row[] = $itemData['company_type'];*/
            $row[] = $itemData['price'];
            $row[] = $itemData['added_value_type'];
            $row[] = $itemData['added_value'];
            $row[] = $itemData['status'];
            $row[] = $itemData['negotiated'];
            $row[] = $itemData['negotiated_amount_from'];
            $row[] = $itemData['tax'];
            if ($itemData['is_store_all'] == 0) {
                $row[] = 'all';
            } else {
                $row[] = $itemData['store_id'];
            }
            $row[] = $itemData['free_shipping'];
            /*$row[] = $itemData['increment_price_by_weight'];
            $row[] = $itemData['increment_package_by_weight'];*/
            $row[] = trim($itemData['user_group_ids'], ',');
            fputcsv($fp, $row, ',');
        }
        fclose($fp);
        return;
    }

    public function exportXmlAction()
    {
        $fileName = 'access_point_methods.xml';
        $content = $this->getLayout()->createBlock('upsap/adminhtml_method_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        return;
    }
}