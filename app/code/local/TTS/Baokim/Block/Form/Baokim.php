<?php

class TTS_Baokim_Block_Form_Baokim extends Mage_Payment_Block_Form
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payment/form/baokim.phtml');
    }

}
