<?php
class Tools_Swatch_Block_Adminhtml_Swatch extends Mage_Adminhtml_Block_Widget_Grid_Container
{
      public function __construct()
      {
        $this->_controller = 'adminhtml_swatch';
        $this->_blockGroup = 'swatch';
        $this->_headerText = Mage::helper('swatch')->__('Manager swatch');
        $this->_addButtonLabel = Mage::helper('swatch')->__('Add New swatch');
        parent::__construct();
        $this->removeButton('add');
      }
}

?>
