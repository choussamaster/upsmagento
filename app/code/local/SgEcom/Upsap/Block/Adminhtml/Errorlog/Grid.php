
<?php

class SgEcom_Upsap_Block_Adminhtml_Errorlog_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('errorlogGrid');
        $this->setDefaultSort('created_time');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('upsap/errorlog')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('aperr_id', array(
            'header' => Mage::helper('upsap')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'aperr_id',
        ));

        $this->addColumn('error_message', array(
            'header' => Mage::helper('upsap')->__('Message'),
            'align' => 'left',
            'index' => 'error_message',
            'type'  => 'text',
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('upsap')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('upsap')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('aperr_id');
        $this->getMassactionBlock()->setFormFieldName('errorlog');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('upsap')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('upsap')->__('Are you sure?')
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return false;
    }
}