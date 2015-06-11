<?php

class Tools_Search_Block_Adminhtml_Autodownload_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('autodownload_form', array('legend'=>Mage::helper('search')->__('Add products item (ISBN)')));
     
      $fieldset->addField('isbn', 'textarea', array(
          'label'     => Mage::helper('search')->__('ISBN'),
          'style'     => 'width:800px; height:600px;',
          'name'      => 'isbn',
      ));
		
      return parent::_prepareForm();
  }
}