
<?php

class SgEcom_Upsap_Block_Adminhtml_Method_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('methodGrid');
        $this->setDefaultSort('upsapshippingmethod_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('upsap/method')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('upsapshippingmethod_id', array(
            'header' => Mage::helper('upsap')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'upsapshippingmethod_id',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('upsap')->__('Title'),
            'align' => 'left',
            'index' => 'title',
            'type' => 'text',
        ));

        $this->addColumn('name', array(
            'header' => Mage::helper('upsap')->__('Method Name'),
            'align' => 'left',
            'index' => 'name',
            'type' => 'text',
        ));

        $this->addColumn('upsmethod_id', array(
            'header' => Mage::helper('upsap')->__('UPS Shipping Method'),
            'align' => 'left',
            'width' => '80px',
            'index' => 'upsmethod_id',
            'type' => 'values',
            'options' => Mage::getModel('upsap/config_upsmethod')->getUpsMethods(),
        ));

        $this->addColumn('price', array(
            'header' => Mage::helper('upsap')->__('Price'),
            'align' => 'left',
            'index' => 'price',
            'type' => 'text',
        ));

        $this->addColumn('country_ids', array(
            'header' => Mage::helper('upsap')->__('Countries'),
            'align' => 'left',
            'width' => '200px',
            'index' => 'country_ids',
            'type' => 'text',
            'frame_callback' => array($this, 'callback_countries'),
        ));

        $this->addColumn('amount_min', array(
            'header' => Mage::helper('upsap')->__('Min Order Amount'),
            'align' => 'left',
            'index' => 'amount_min',
            'type' => 'text',
        ));

        $this->addColumn('amount_max', array(
            'header' => Mage::helper('upsap')->__('Max Order Amount'),
            'align' => 'left',
            'index' => 'amount_max',
            'type' => 'text',
        ));

        $this->addColumn('weight_min', array(
            'header' => Mage::helper('upsap')->__('Min Weight'),
            'align' => 'left',
            'index' => 'weight_min',
            'type' => 'text',
        ));

        $this->addColumn('weight_max', array(
            'header' => Mage::helper('upsap')->__('Max Weight'),
            'align' => 'left',
            'index' => 'weight_max',
            'type' => 'text',
        ));

        $this->addColumn('qty_min', array(
            'header' => Mage::helper('upsap')->__('Min Qty'),
            'align' => 'left',
            'index' => 'qty_min',
            'type' => 'text',
        ));

        $this->addColumn('qty_max', array(
            'header' => Mage::helper('upsap')->__('Max Qty'),
            'align' => 'left',
            'index' => 'qty_max',
            'type' => 'text',
        ));

        /*multistore*/
        $this->addColumn('store_id', array(
            'header' => Mage::helper('upsap')->__('Store'),
            'align' => 'left',
            'width' => '100px',
            'index' => 'store_id',
            'type' => 'options',
            'frame_callback' => array($this, 'callback_stores'),
            'filter_condition_callback' => array($this, '_filterStores'),
            'options' => Mage::helper('upsap')->getStores(),
        ));
        /*multistore*/
        $this->addColumn('status', array(
            'header' => Mage::helper('upsap')->__('Status'),
            'align' => 'left',
            'width' => '50px',
            'index' => 'status',
            'type' => 'options',
            'options' => array('1' => Mage::helper('adminhtml')->__('Enabled'), '0' => Mage::helper('adminhtml')->__('Disabled'))
        ));

        $this->addColumn('action',
            array(
                'header' => Mage::helper('upsap')->__('Action'),
                'width' => '100',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('upsap')->__('Edit'),
                        'url' => array('base' => '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'is_system' => true,
            ));

        $this->addExportType('*/*/exportCsv', Mage::helper('upsap')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('upsap')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('upsapshippingmethod_id');
        $this->getMassactionBlock()->setFormFieldName('method');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('upsap')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('upsap')->__('Are you sure?')
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function callback_countries($value, $row, $column, $isExport)
    {
        if ($row->getIsCountryAll() == 0) {
            return Mage::helper('upsap')->__('All');
        }

        return str_replace(',', ', ', $value);
    }

    public function callback_stores($value, $row, $column, $isExport)
    {
        $stores = Mage::helper('upsap')->getStores();

        $storeNames = array();
        if ($row->getIsStoreAll() != 0) {
            if ($stores && count($stores) > 0) {
                $storeIds = explode(",", $row->getStoreId());
                if ($storeIds && count($storeIds) > 0) {
                    foreach ($storeIds as $storeId) {
                        if (array_key_exists($storeId, $stores)) {
                            $storeNames[] = $stores[$storeId];
                        }
                    }
                }
            }
        } else {
            $storeNames = array(0 => Mage::helper('upsap')->__('All'));
        }

        return implode(", ", $storeNames);
    }

    public function _filterStores($collection, $column)
    {
        if (!$storeId = $column->getFilter()->getValue()) {
            return $this;
        }

        $collection->addFieldToFilter(array('is_store_all', 'store_id'), array(array('eq' => 0), array(
                array(
                    array('like' => '%,' . $storeId . ',%'),
                    array('like' => '%,' . $storeId),
                    array('like' => $storeId . ',%'),
                    array('like' => $storeId),
                )
            )
            )
        );

        return $this;
    }
}