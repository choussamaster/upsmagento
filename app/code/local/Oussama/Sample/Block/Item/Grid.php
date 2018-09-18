<?php
/**
 * Created by PhpStorm.
 * User: chous
 * Date: 1/14/2018
 * Time: 4:17 PM
 */
class Oussama_Sample_Block_Item_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct()
    {
        parent::__construct();
        $this->setId('grid_id');
        // $this->setDefaultSort('COLUMN_ID');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('oussama_sample/item')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

       $this->addColumn('id',
           array(
               'header'=> $this->__('Id'),
               'width' => '50px',
               'index' => 'id'
           )
       )
           ->addColumn('title',
               array(
                   'header'=> $this->__('Title'),
                   'width' => '200px',
                   'index' => 'title'
               )
           )
           ->addColumn('description',
               array(
                   'header'=> $this->__('Description'),
                   'width' => '500px',
                   'index' => 'description'
               )
           );


                $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        
                $this->addExportType('*/*/exportExcel', $this->__('Excel XML'));
        
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
       return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

        protected function _prepareMassaction()
    {
        $modelPk = Mage::getModel('oussama_sample/item')->getResource()->getIdFieldName();
        $this->setMassactionIdField($modelPk);
        $this->getMassactionBlock()->setFormFieldName('ids');
        // $this->getMassactionBlock()->setUseSelectAll(false);
        $this->getMassactionBlock()->addItem('delete', array(
             'label'=> $this->__('Delete'),
             'url'  => $this->getUrl('*/*/massDelete'),
        ));
        return $this;
    }
    }
