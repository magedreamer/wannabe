<?php

class Tools_Search_Block_Adminhtml_Autodownload_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('autodownload_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('search')->__('Add products item (ISBN)'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('search')->__('Add products item (ISBN)'),
          'title'     => Mage::helper('search')->__('Add products item (ISBN)'),
          'content'   => $this->getLayout()->createBlock('search/adminhtml_autodownload_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}