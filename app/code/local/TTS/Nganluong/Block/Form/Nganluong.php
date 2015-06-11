<?php

class TTS_Nganluong_Block_Form_Nganluong extends Mage_Payment_Block_Form
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payment/form/nganluong.phtml');
    }

}
