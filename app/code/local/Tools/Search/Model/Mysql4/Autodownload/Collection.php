<?php

class Tools_Search_Model_Mysql4_Autodownload_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('search/autodownload');
    }
    
    public function getDownloadList(){
    	 $this->load();
    	 return $this;
    }
}