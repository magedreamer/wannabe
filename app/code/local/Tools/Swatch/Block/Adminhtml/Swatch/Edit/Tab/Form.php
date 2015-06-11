<?php

class Tools_Swatch_Block_Adminhtml_Swatch_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('swatch_form', array('legend'=>Mage::helper('swatch')->__('Swatch information')));
      $model = Mage::getModel('swatch/swatch');
      $value = $model->getAttributeAuthor();
      $arr[0]= array(
                  'value'     => "",
                  'label'     => Mage::helper('swatch')->__('Add New Swatch'),
              );
      for($i=0;$i<count($value);$i++){
      		$arr[$i+1] = $value[$i];
      }
      
      $fieldset->addField('swatch_name', 'text', array(
          'label'     => Mage::helper('swatch')->__('Swatch Name'),
          'class'     => 'entry',
          'required'  => false,
          'name'      => 'publisher_name',
      ));

       $fieldset->addField('image', 'image', array(
          'label'     => Mage::helper('swatch')->__('Image'),
          'required'  => false,
          'name'      => 'image',
       ));

      $fieldset->addField('option_id', 'hidden', array(
          'label'     => Mage::helper('publishers')->__('Option Id'),
          'name'      => 'option_id'

      ));

      $fieldset->addField('attribute_id', 'hidden', array(
          'label'     => Mage::helper('publishers')->__('Attribute Id'),
          'name'      => 'attribute_id'

      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getSwatchData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getSwatchData());
          Mage::getSingleton('adminhtml/session')->setSwatchData(null);
      } elseif ( Mage::registry('swatch_data') ) {
          $form->setValues(Mage::registry('swatch_data')->getData());
      }
      return parent::_prepareForm();
  }
}