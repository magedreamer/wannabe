<?php
class TTS_Nganluong_Block_Info_Nganluong extends Mage_Payment_Block_Info
{

    protected $_affilateCode;
    protected $_mailingAddress;
    protected $_return_url;
    protected $_checkout;
    protected $_merchantID;
    protected $_secure_code;
	protected $_feeCal;
	
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('payment/info/nganluong.phtml');
    }

    /**
     * Enter description here...
     *
     * @return string
     */
    public function getAffilateCode()
    {
        if (is_null($this->_affilateCode)) {
            $this->_convertAdditionalData();
        }
        return $this->_affilateCode;
    }
	
	public function getFeeCal()
    {
        if (is_null($this->_feeCal)) {
            $this->_convertAdditionalData();
        }
        return $this->_feeCal;
    }
    /**
     * Enter description here...
     *
     * @return string
     */
    public function getMailingAddress()
    {
        if (is_null($this->_mailingAddress)) {
            $this->_convertAdditionalData();
        }
        return $this->_mailingAddress;
    }
    public function getCheckout()
    {
    	 if (is_null($this->_checkout)) {
            $this->_convertAdditionalData();
        }
        return $this->_checkout;
    }
	
    public function getMerchantSite()
    {
    	if (is_null($this->_merchantID)) {
            $this->_convertAdditionalData();
        }
        return $this->_merchantID;
    }
	
    public function getSecureCode()
    {
    	if (is_null($this->_secure_code)) {
            $this->_convertAdditionalData();
        }
        return $this->_secure_code;
    }
     public function getReturnUrl()
    {
        if (is_null($this->_return_url)) {
            $this->_convertAdditionalData();
        }
        return $this->_return_url;
    }
    /**
     * Enter description here...
     *
     * @return Mage_Payment_Block_Info_Checkmo
     */
    protected function _convertAdditionalData()
    {
        $details = @unserialize($this->getInfo()->getAdditionalData());
        if (is_array($details)) {
            $this->_affilateCode = isset($details['affilateCode']) ? (string) $details['affilateCode'] : '';
            $this->_mailingAddress = isset($details['mailing_address']) ? (string) $details['mailing_address'] : '';
            $this->_return_url = isset($details['return_url']) ? (string) $details['return_url'] : '';
			
            $this->_checkout = isset($details['checkout']) ? (string) $details['checkout'] : '';
            $this->_merchantID = isset($details['merchantID']) ? (string) $details['merchantID'] : '';
			
            $this->_secure_code = isset($details['secure_code']) ? (string) $details['secure_code'] : '';
			$this->_feeCal = isset($details['feeCal']) ? (string) $details['feeCal'] : '';
        } 
		else {
            $this->_feeCal = '';
			$this->_affilateCode = '';
            $this->_mailingAddress = '';
            $this->_return_url='';
            $this->_checkout='';
            $this->_merchantID='';
            $this->_secure_code='';
        }
        return $this;
    }
    
    public function toPdf()
    {
        $this->setTemplate('payment/info/pdf/nganluong.phtml');
        return $this->toHtml();
    }

}
