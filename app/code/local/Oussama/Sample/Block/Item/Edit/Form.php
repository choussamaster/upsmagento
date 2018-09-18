<?php
/**
 * Created by PhpStorm.
 * User: chous
 * Date: 1/14/2018
 * Time: 4:17 PM
 */
class Oussama_Sample_Block_Item_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _getModel(){
        return Mage::registry('current_model');
    }

    protected function _getHelper(){
        return Mage::helper('oussama_sample');
    }

    protected function _getModelTitle(){
        return 'Item';
    }

    protected function _prepareForm()
    {
        $model  = $this->_getModel();
        $modelTitle = $this->_getModelTitle();
        $form   = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save'),
            'method'    => 'post'
        ));

        $fieldset   = $form->addFieldset('base_fieldset', array(
            'legend'    => $this->_getHelper()->__("$modelTitle Information"),
            'class'     => 'fieldset-wide',
        ));

        if ($model && $model->getId()) {
            $modelPk = $model->getResource()->getIdFieldName();
            $fieldset->addField($modelPk, 'hidden', array(
                'name' => $modelPk,
            ));
        }

        /* select | multiselect | hidden | password | ...  */
        /*$fieldset->addField('name', 'text' , array(
            'name'      => 'name',
            'label'     => $this->_getHelper()->__('Label here'),
            'title'     => $this->_getHelper()->__('Tooltip text here'),
            'required'  => true,
            'options'   => array( OPTION_VALUE => OPTION_TEXT, ),                  //used when type = "select"
            'values'    => array(array('label' => LABEL, 'value' => VALUE), ),     //used when type = "multiselect"
            'style'     => 'css rules',
            'class'     => 'css classes',
        ));*/

        $fieldset->addField('title', 'text', array(
            'name' => 'title',
            'label' => $this->_getHelper()->__('Title'),
            'title' => $this->_getHelper()->__('Title'),
            'required' => true,
        ));
        $fieldset->addField('description', 'text', array(
            'name' => 'description',
            'label' => $this->_getHelper()->__('Description'),
            'title' => $this->_getHelper()->__('Description'),
            'required' => false,
        ));
          //custom renderer (optional)
          //$renderer = $this->getLayout()->createBlock('Block implementing Varien_Data_Form_Element_Renderer_Interface');
          //$field->setRenderer($renderer);

        //New Form type element (extends Varien_Data_Form_Element_Abstract)
        //$fieldset->addType('custom_element','MyCompany_MyModule_Block_Form_Element_Custom');  // you can use "custom_element" as the type now in ::addField([name], [HERE], ...)


        if($model){
            $form->setValues($model->getData());
        }
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
