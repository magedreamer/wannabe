<?php
class Tools_Search_Model_Autodownload extends Mage_Core_Model_Abstract
{
	
    public function _construct()
    {
        parent::_construct();
        $this->_init("search/autodownload");
    }
    
    
}