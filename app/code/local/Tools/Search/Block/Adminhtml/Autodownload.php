<?php
class Tools_Search_Block_Adminhtml_Autodownload extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
  	{
    	$this->_controller = 'adminhtml_autodownload';
    	$this->_blockGroup = 'search';
    	$this->_headerText = Mage::helper('search')->__('Download Product Manage');
    	parent::__construct();
  	}
}