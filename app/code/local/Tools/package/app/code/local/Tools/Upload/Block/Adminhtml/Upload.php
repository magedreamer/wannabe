<?php
/**
 * 

 * @package    Uni_Upload


 */
class Tools_Upload_Block_Adminhtml_Upload extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {
        $this->_controller = 'adminhtml_upload';
        $this->_blockGroup = 'upload';
        $this->_headerText = Mage::helper('upload')->__('Upload Manager');
        $this->_addButtonLabel = Mage::helper('upload')->__('Add Upload Item');
        parent::__construct();
    }
}