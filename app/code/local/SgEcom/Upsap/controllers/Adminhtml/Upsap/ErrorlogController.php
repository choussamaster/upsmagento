
<?php

class SgEcom_Upsap_Adminhtml_Upsap_ErrorlogController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Errors Manager'), Mage::helper('adminhtml')->__('Errors Manager'));

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

    public function massDeleteAction()
    {
        $upsapIds = $this->getRequest()->getParam('errorlog');
        if (!is_array($upsapIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($upsapIds as $upsapId) {
                    $collection = Mage::getModel('upsap/errorlog')->load($upsapId);
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
        $fileName = 'access_point_errorlog.csv';
        $content = $this->getLayout()->createBlock('upsap/adminhtml_errorlog_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName = 'access_point_errorlog.xml';
        $content = $this->getLayout()->createBlock('upsap/adminhtml_errorlog_grid')
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