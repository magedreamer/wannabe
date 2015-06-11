<?php
class Tools_Swatch_Block_Adminhtml_Swatch_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
      parent::__construct();
      $this->setId('swatch_info_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('swatch')->__('Swatch Information'));
    }

    protected function _beforeToHtml()
    {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('swatch')->__('Swatch Information'),
          'title'     => Mage::helper('swatch')->__('Swatch Information'),
          'content'   => $this->getLayout()->createBlock('swatch/adminhtml_swatch_edit_tab_form')->toHtml(),
      ));

      return parent::_beforeToHtml();
    }
}

?>
