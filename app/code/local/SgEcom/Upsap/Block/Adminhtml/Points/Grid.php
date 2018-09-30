
<?php

class SgEcom_Upsap_Block_Adminhtml_Points_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('pointsGrid');
        $this->setDefaultSort('ap_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('upsap/accesspoint')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('ap_id', array(
            'header' => Mage::helper('upsap')->__('#'),
            'align' => 'left',
            'index' => 'ap_id',
            'type' => 'text',
        ));

        $this->addColumn('order_id', array(
            'header' => Mage::helper('upsap')->__('Order #'),
            'align' => 'center',
            'index' => 'order_id',
            'type' => 'text',
            'frame_callback' => array($this, 'callback_orderIncrementId'),
            'filter_condition_callback' => array($this, '_filterOrderId'),
        ));

        $this->addColumn('appu_id', array(
            'header' => Mage::helper('upsap')->__('Access Point ID'),
            'align' => 'center',
            'index' => 'appu_id',
            'type' => 'text',
        ));

        $this->addColumn('address', array(
            'header' => Mage::helper('upsap')->__('Address'),
            'align' => 'left',
            'index' => 'address',
            'type' => 'text',
            'filter' => false,
            'sortable' => false,
            'frame_callback' => array($this, 'callback_address'),
        ));

        return parent::_prepareColumns();
    }

    public function _filterOrderId($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return $this;
        }

        $collection->getSelect()
            ->join(array('t4upslabel' => Mage::getConfig()->getTablePrefix() . 'sales_flat_order'),'main_table.order_id = t4upslabel.entity_id AND (t4upslabel.increment_id LIKE "%'.$value.'%" OR t4upslabel.entity_id = '.((int)$value).')', null);
        return $this;
    }

    public function callback_orderIncrementId($value, $row, $column, $isExport)
    {
        $orderIncrementId = Mage::getModel('sales/order')->load($value);
        if($orderIncrementId){
            $orderIncrementId = $orderIncrementId->getIncrementId();
            return '<a href="'.(Mage::getUrl('adminhtml/sales_order/view', array('order_id' => $value))).'" target="_blank">'.($orderIncrementId).'</a>';
        }

        return $value;
    }

    public function callback_address($value, $row, $column, $isExport)
    {
        $address = json_decode($value, true);
        $addressForm = "<u>" . $address['name'] . "</u>: <br>";
        $addressForm .= $address['addLine1'] . " ";
        if (isset($address['addLine2'])) {
            $addressForm .= $address['addLine2'] . " ";
        }

        $addressForm .= $address['city'] . " ";

        if (isset($address['state'])) {
            $addressForm .= $address['state'] . " ";
        }

        if (isset($address['postal'])) {
            $addressForm .= $address['postal'] . " ";
        }

        $addressForm .= Mage::getModel('directory/country')->loadByCode($address['country'])->getName() . " ";

        return $addressForm;
    }
}