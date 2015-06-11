<?php
class TTS_Baokim_Block_Baokim extends Mage_Checkout_Block_Onepage_Success
{

private $baokim_url = 'https://www.baokim.vn/payment/customize_payment/order';
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getBaokim()     
     { 
        if (!$this->hasData('baokim')) {
            $this->setData('baokim', Mage::registry('baokim'));
        }
        return $this->getData('baokim');
        
    }
	
	 
	public function createRequestUrl($order_id, $business, $total_amount, $shipping_fee, $tax_fee, $order_description, $url_success, $url_cancel, $url_detail,$merchant_id,$secure_pass)
	{
		
		$params = array(
			'merchant_id'		=>	strval($merchant_id),
			'order_id'			=>	strval($order_id),
			'business'			=>	strval($business),
			'total_amount'		=>	strval($total_amount),
			'shipping_fee'		=>  strval($shipping_fee),
			'tax_fee'			=>  strval($tax_fee),
			'order_description'	=>	strval($order_description),
			'url_success'		=>	strtolower($url_success),
			'url_cancel'		=>	strtolower($url_cancel),
			'url_detail'		=>	strtolower($url_detail)
		);
		ksort($params);
		
		$str_combined = $secure_pass.implode('', $params);
		$params['checksum'] = strtoupper(md5($str_combined));
		
		
		$redirect_url = $this->baokim_url;
		if (strpos($redirect_url, '?') === false)
		{
			$redirect_url .= '?';
		}
		else if (substr($redirect_url, strlen($redirect_url)-1, 1) != '?' && strpos($redirect_url, '&') === false)
		{
			
			$redirect_url .= '&';			
		}
				
		
		$url_params = '';
		foreach ($params as $key=>$value)
		{
			if ($url_params == '')
				$url_params .= $key . '=' . urlencode($value);
			else
				$url_params .= '&' . $key . '=' . urlencode($value);
		}
		return $redirect_url.$url_params;
	}
	

	public function verifyResponseUrl($GET)
	{
		$checksum = $_GET['checksum'];
		unset($_GET['checksum']);
		
		ksort($_GET);
		$str_combined = $this->secure_pass.implode('', $_GET);

      
		$verify_checksum = strtoupper(md5($str_combined));
		
		
		if ($verify_checksum === $checksum) 
			return true;
		
		return false;
	}



public function ktcheckout(){
 $order = Mage::getModel('sales/order')->loadByIncrementId($this->getOrderId());
if(isset($_GET['checksum']) && !empty($_GET['checksum'])) 
{

$array		=	array(
	'created_on'			=>	'',
	'customer_email'		=> '',
	'customer_name'		=> '',
	'customer_phone'		=> '',
	'fee_amount'			=> '',
	'merchant_email'		=> '',
	'merchant_id'			=> '',
	'merchant_name'		=> '',
	'merchant_phone'		=> '',
	'order_id'				=> '',
	'payment_type'			=> '',
	'total_amount'			=> '',
	'transaction_id'		=> '',
	'created_on'			=> '',
	'transaction_status'	=> '',
	'checksum'				=> ''
);

foreach($array as $key=>$value){
	$array[$key]	=	isset($_GET[$key]) ? $_GET[$key] : '';
}

	
	$check = $this->verifyResponseUrl($array);
	if($check === false)
	{
		
		echo $this->__('K?t qu? thanh toán không h?p l?');
	}
	else
	{
		echo $this->__('Thanh toán thành công!');
	}
}
else
{

//$this->bk_price($price);	
}

}
	public function bk_price($price){
		$price_bk	= strip_tags($price);
		$price_bk 	= str_replace(',','',$price_bk);
		$price_bk 	= str_replace('.','',$price_bk);
		$price_bk 	= str_replace('VNÐ','',$price_bk);
		$price_bk 	= trim($price_bk);
		return $price_bk;
	}
	
		
public function thanhtoanbaokim(){


$merchant_id = nl2br($_SESSION['merchant_id']);
	$secure_pass = nl2br($_SESSION['secure_pass']);
	$business =nl2br($_SESSION['email_bussiness']);
	$order_id =nl2br(Mage::getSingleton('checkout/session')->getLastRealOrderId());
	$total_amount=$this->bk_price($_SESSION['total_amount']); 
	$url_success=nl2br($_SESSION['url_Success']);
	unset($_SESSION['merchant_id']);
	unset($_SESSION['secure_pass']);
	unset($_SESSION['email_bussiness']);
	unset($_SESSION['total_amount']);
	unset($_SESSION['url_Success']);
return 	$this->createRequestUrl($order_id, $business, $total_amount, $shipping_fee, $tax_fee, $order_description, $url_success, $url_cancel, $url_detail,$merchant_id,$secure_pass);
	
}


}