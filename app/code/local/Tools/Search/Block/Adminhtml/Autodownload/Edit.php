<?php
class Tools_Search_Block_Adminhtml_Autodownload_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'search';
        $this->_controller = 'adminhtml_autodownload';
        
        $this->_updateButton('save', 'label', Mage::helper('search')->__('Save'));
        $this->removeButton('delete');
		
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('autodownload_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'autodownload_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'autodownload_content');
                }
            }

            function save(){
                editForm.submit($('edit_form').action);
            };
        ";
    }

    public function getHeaderText()
    {
        return Mage::helper('search')->__('Add products item (ISBN)');
    }
}