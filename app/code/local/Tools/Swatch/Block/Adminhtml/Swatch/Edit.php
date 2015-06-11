<?php

class Tools_Swatch_Block_Adminhtml_Swatch_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = 'id';
        $this->_blockGroup = 'swatch';
        $this->_controller = 'adminhtml_swatch';
        
       $this->_updateButton('save', 'label', Mage::helper('swatch')->__('Save Swatch'));
        
        //$this->_updateButton('delete', 'label', Mage::helper('author')->__('Delete Author'));
        $this->removeButton('delete');

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('swatch_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'swatch_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'swatch_content');
                }
            };
            ";
        
    }

    public function getHeaderText()
    {
        if( Mage::registry('swatch_data') && Mage::registry('swatch_data')->getId() ) {
            return Mage::helper('swatch')->__("Edit swatch '%s'", $this->htmlEscape(Mage::registry('swatch_data')->getTitle()));
        } else {
            return Mage::helper('swatch')->__('Add New swatch');
        }
    }
}