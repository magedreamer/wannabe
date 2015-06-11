<?php


class TTS_Baokim_Block_Info_Baokim extends Mage_Payment_Block_Info
{

    protected $_email_bussiness;
    protected $_merchant_id;
    protected $_secure_pass;
	 protected $_url_Success;
 


   public function get_email_bussiness()
    {
        if (is_null($this->_email_bussiness)) {
            $this->_convertAdditionalData();
        }
        return $this->_email_bussiness;
    }


    public function get_merchant_id()
    {
        if (is_null($this->_merchant_id)) {
            $this->_convertAdditionalData();
        }
        return $this->_merchant_id;
    }
     public function get_secure_pass()
    {
        if (is_null($this->_secure_pass)) {
            $this->_convertAdditionalData();
        }
        return $this->_secure_pass;
    }
	 
	  public function get_url_Success()
    {
        if (is_null($this->_url_Success)) {
            $this->_convertAdditionalData();
        }
        return $this->_url_Success;
    }

    protected function _convertAdditionalData()
    {
	 	  $info = $this->getInfo();
		  var_dump($info);
		  
        $details = @unserialize($this->getInfo()->getAdditionalData());
        if (is_array($details)) {
  				$this->_email_bussiness = isset($details['email_bussiness']) ? (string) $details['email_bussiness'] : '';
            $this->_merchant_id = isset($details['merchant_id']) ? (string) $details['merchant_id'] : '';
            $this->_secure_pass = isset($details['secure_pass']) ? (string) $details['secure_pass'] : '';
				$this->_url_Success = isset($details['url_Success']) ? (string) $details['url_Success'] : '';
        } else {
            $this->_email_bussiness = '';
            $this->_merchant_id = '';
            $this->_secure_pass='';
				$this->_url_Success='';
        }
        return $this;
    }
    
    public function toPdf()
    {
        $this->setTemplate('payment/info/pdf/baokim.phtml');
        return $this->toHtml();
    }

}
?>