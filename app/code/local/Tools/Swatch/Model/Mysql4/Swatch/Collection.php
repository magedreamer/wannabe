<?php

class Tools_Swatch_Model_Mysql4_Swatch_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('swatch/swatch');
    }
    
    
}