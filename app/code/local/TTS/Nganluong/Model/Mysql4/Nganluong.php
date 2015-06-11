<?php

class TTS_Nganluong_Model_Mysql4_Nganluong extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the nganluong_id refers to the key field in your database table.
        $this->_init('nganluong/nganluong', 'nganluong_id');
    }
}