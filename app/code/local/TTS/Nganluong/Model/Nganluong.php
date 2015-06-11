<?php
class TTS_Nganluong_Model_Nganluong extends Mage_Payment_Model_Method_Abstract
{

    protected $_code  = 'nganluong';
    protected $_formBlockType = 'nganluong/form_nganluong';
    protected $_infoBlockType = 'nganluong/info_nganluong';

    public function assignData($data)
    {
        $details = array();
		
        if ($this->getAffilateCode()) {
            $details['affilate_code'] = $this->getAffilateCode();
        }
        if ($this->getMailingAddress()) {
            $details['mailing_address'] = $this->getMailingAddress();
        }
        if ($this->getReturnUrl()){
        	$details['return_url']=$this->getReturnUrl();
        }
        if($this->getCheckout()){
        	$details['checkout']=$this->getCheckout();
        }
		if($this->getFeeCal()){
        	$details['fee_cal']=$this->getFeeCal();
        }
        if($this->getMerchantSite()){
        	$details['merchantID']=$this->getMerchantSite();
        }
        if($this->getSecureCode()){
        	$details['secure_code']=$this->getSecureCode();
        }
        if (!empty($details)) {
            $this->getInfoInstance()->setAdditionalData(serialize($details));
        }
        return $this;
    }

    public function getAffilateCode()
    {
        return $this->getConfigData('affilate_code');
    }
	
	public function getFeeCal()
    {
        return $this->getConfigData('fee_cal');
    }

    public function getMailingAddress()
    {
        return $this->getConfigData('mailing_address');
    }
    public function getCheckout()
    {
    	return $this->getConfigData('checkout');
    }
    public function getMerchantSite()
    {
    	return $this->getConfigData('merchantID');
    }
    public function getSecureCode()
    {
    	return $this->getConfigData('secure_code');
    }
    public function getReturnUrl()
    {
       return $this->getConfigData('return_url');
    }
}
