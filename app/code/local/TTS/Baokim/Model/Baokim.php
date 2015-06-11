<?php


class TTS_Baokim_Model_Baokim extends Mage_Payment_Model_Method_Abstract
{
private $baokim_url = 'http://sandbox.baokim.vn/payment/customize_payment/order';
    protected $_code  = 'baokim';
    protected $_formBlockType = 'baokim/form_baokim';
    protected $_infoBlockType = 'baokim/info_baokim';

    public function assignData($data)
    {
        $details = array();
		
        if ($this->get_email_bussiness()) {
            $details['email_bussiness'] = $this->get_email_bussiness();
        }
        if ($this->get_merchant_id()) {
            $details['merchant_id'] = $this->get_merchant_id();
        }
        if ($this->get_secure_pass()){
        	$details['secure_pass']=$this->get_secure_pass();
        }
        if ($this->get_url_Success()){
        	 $details['url_success']=$this->get_url_Success();
        }

        return $this;
    }
 
    public function get_email_bussiness()
    {	
        return $this->getConfigData('email_bussiness');
    }

    public function get_merchant_id()
    {
        return $this->getConfigData('merchant_id');
    }
    
    public function get_secure_pass()
    {
       return $this->getConfigData('secure_pass');
    }
	 
	 public function get_url_Success()
    {
       return $this->getConfigData('url_success');
    }

}