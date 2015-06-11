<?php

class TTS_Nganluong_Model_Mysql4_Nganluong_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('nganluong/nganluong');
    }
}