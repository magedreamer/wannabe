<?php

class Tools_Upload_Block_Adminhtml_Upload_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('upload_form', array('legend' => Mage::helper('upload')->__('Item information')));
        $version = substr(Mage::getVersion(), 0, 3);
        //$config = (($version == '1.4' || $version == '1.5') ? "'config' => Mage::getSingleton('upload/wysiwyg_config')->getConfig()" : "'class'=>''");

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('upload')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));
       
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('upload/upload')->load($id);

        if ($model->getId() || $id == 0) {
            $link = Mage::getBaseUrl('media').$model->getFilename();
        }
        $fieldset->addField('filename', 'file', array(
            'label' => Mage::helper('upload')->__('File label'),
            'required' => false,
            'name' => 'filename',
             'after_element_html' => "<br/><a href=\"$link\">$link",
        ));
        
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('upload')->__('Status'),
            'class' => 'required-entry',
            'name' => 'status',
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('upload')->__('Enabled'),
                ),
                array(
                    'value' => 2,
                    'label' => Mage::helper('upload')->__('Disabled'),
                ),
            ),
        ));

        if (Mage::getSingleton('adminhtml/session')->getUploadData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getUploadData());
            Mage::getSingleton('adminhtml/session')->setUploadData(null);
        } elseif (Mage::registry('upload_data')) {
            $form->setValues(Mage::registry('upload_data')->getData());
        }
        return parent::_prepareForm();
    }

}