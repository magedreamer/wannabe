<?php
/**
 * 

 * @package    Uni_Upload


 */
class Tools_Upload_Block_Adminhtml_Upload_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('upload_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('upload')->__('Item Information'));
    }

    protected function _beforeToHtml() {
        $this->addTab('form_section', array(
            'label' => Mage::helper('upload')->__('Upload Information'),
            'alt' => Mage::helper('upload')->__('Upload information'),
            'content' => $this->getLayout()->createBlock('upload/adminhtml_upload_edit_tab_form')->toHtml(),
        ));        
        return parent::_beforeToHtml();
    }

}